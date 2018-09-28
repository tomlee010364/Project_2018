<?php
session_start();
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Q&A</title>
		<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
		</script>
	</head>
	
	<body>
		<?php
		if($_SESSION['is_login']!=null):
			$sql_q="SELECT * FROM `q`";
			$result_q=mysqli_query($conn, $sql_q);
			mysqli_data_seek($result_q, $_GET["question_id"]-1);
			$row_q=mysqli_fetch_assoc($result_q);
			
			/*Who ask this question*/
			$sql_user="SELECT user_id, name FROM `user`";
			$result_user=mysqli_query($conn, $sql_user);
			mysqli_data_seek($result_user, $row_q["id"]-1);
			$row_user=mysqli_fetch_assoc($result_user);
			$counter_user=mysqli_num_rows($result_user);
			
			/*User's follow list*/
			for($i=0;$i<$counter_user;$i++){
				$follow_list[] = False;
			}
			$sql_follow="SELECT * FROM `follow`";
			$result_follow=mysqli_query($conn, $sql_follow);
			$counter_follow=mysqli_num_rows($result_follow);
			for($i=0;$i<$counter_follow;$i++){
				mysqli_data_seek($result_follow, $i);
				$row_follow=mysqli_fetch_assoc($result_follow);
				if($row_q["id"] == $row_follow["who_follow"]){
					$follow_list[$row_follow["follow_who"]-1] = True;
				}
			}
			
			echo "<h1>" . $row_q["topic"] . "</h1>";
			echo "<a href=profile.php?id=" . $row_q["id"] . ">" . $row_user["name"] . "</a><br>";
			echo date("Y/m/d H:i", strtotime($row_q["ask_time"])) . "<br>";
			echo $row_q["context"] . "<br>";
			
			/*Recommend*/
			$sql_recommend_q="SELECT * FROM `recommend_q`";
			$result_recommend_q=mysqli_query($conn, $sql_recommend_q);
			$counter_recommend_q=mysqli_num_rows($result_recommend_q);
			$flag_recommend_q=false;
			for($i=0;$i<$counter_recommend_q;$i++){
				mysqli_data_seek($result_recommend_q, $i);
				$row_recommend_q=mysqli_fetch_assoc($result_recommend_q);
				if($_GET["question_id"] == $row_recommend_q["recommend_q_id"] && $_SESSION['is_login'] == $row_recommend_q["recommend_user_id"]){
					$flag_recommend_q = true;
					break;
				}
			}
			$number_of_recommend = 0;
			for($i=0;$i<$counter_recommend_q;$i++){
				mysqli_data_seek($result_recommend_q, $i);
				$row_recommend_q=mysqli_fetch_assoc($result_recommend_q);
				if($_GET["question_id"] == $row_recommend_q["recommend_q_id"]){
					$number_of_recommend++;
				}
			}
			echo $number_of_recommend . " people recommend this question<br>";
			if($flag_recommend_q == false):
		?>
				<a href='recommend_q.php?question_id=<?php echo $_GET["question_id"];?>'>Good Question</a>
		<?php
			else:
		?>
				<a href='cancel_recommend_q.php?question_id=<?php echo $_GET["question_id"];?>'>Cancel</a>
		<?php
			endif;
		?>
			<a href='reply.php?question_id=<?php echo $_GET["question_id"];?>'>Reply</a>
			<a href='qa.php'>Q&A</a>
		<?php
			echo "<br>============================================<br>";
			
			/*Best Answer*/
			$sql_a="SELECT * FROM `a`";
			$result_a=mysqli_query($conn, $sql_a);
			$counter=mysqli_num_rows($result_a);
			$sql_recommend_a="SELECT * FROM `recommend_a`";
			$result_recommend_a=mysqli_query($conn, $sql_recommend_a);
			$counter_recommend_a=mysqli_num_rows($result_recommend_a);
			
			$counter_reply = 0;
			for($i=0;$i<$counter;$i++){
				mysqli_data_seek($result_a, $i);
				$row_a=mysqli_fetch_assoc($result_a);
				if($_GET["question_id"] == $row_a["Q_ref"]){
					$counter_reply++;
				}
			}			
			if($counter_reply > 0):
				/*Search for the reply which have most supporter*/
				$max = -1;
				$max_supporter_reply = NULL;
				$max_counter_follow = 0;
				
				for($i=0;$i<$counter;$i++){
					mysqli_data_seek($result_a, $i);
					$row_a=mysqli_fetch_assoc($result_a);
					if($_GET["question_id"] == $row_a["Q_ref"]){
						$number_of_recommend = 0;
						$counter_follow_support = 0;
						for($j=0;$j<$counter_recommend_a;$j++){
							mysqli_data_seek($result_recommend_a, $j);
							$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
							if($row_a["A_id"] == $row_recommend_a["recommend_a_id"]){
								$number_of_recommend++;
								if($follow_list[$row_recommend_a["recommend_user_id"]-1] == True){
									$counter_follow_support++;
								}
							}
						}
						if($number_of_recommend > $max){
							$max_supporter_reply = $row_a["A_id"];
							$max = $number_of_recommend;
							$max_counter_follow = $counter_follow_support;
						}
						else if($number_of_recommend == $max && $max != 0){
							if($counter_follow_support > $max_counter_follow){
								$max_supporter_reply = $row_a["A_id"];
								$max_counter_follow = $counter_follow_support;
							}
							else if($counter_follow_support == $max_counter_follow){
								if(rand()%2 == 1){
									$max_supporter_reply = $row_a["A_id"];
								} // Use random to determine which reply is going to be recommended
							}
						}
					}
				}
				
				if($max > 0):
					echo "#Best Answer<br>";
					/*Fetch user's data*/
					mysqli_data_seek($result_a, $max_supporter_reply-1);
					$row_a=mysqli_fetch_assoc($result_a);
			
					/*Who reply this question*/
					mysqli_data_seek($result_user, $row_a["id"]-1);
					$row_user=mysqli_fetch_assoc($result_user);
					echo "<a href=profile.php?id=" . $row_a["id"] . ">" . $row_user["name"] . "</a><br>";
					
					/*Reply time*/
					echo date("Y/m/d H:i", strtotime($row_a["reply_time"])) . "<br>";
					
					/*Reply context*/
					echo $row_a["answer"];
			
					/*Recommend*/
					$flag_recommend_a=false;
					for($j=0;$j<$counter_recommend_a;$j++){
						mysqli_data_seek($result_recommend_a, $j);
						$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
						if($row_a["A_id"] == $row_recommend_a["recommend_a_id"] && $_SESSION['is_login'] == $row_recommend_a["recommend_user_id"]){
							$flag_recommend_a = true;
							break;
						}
					}
					echo "<br>" . $max . " people recommend this answer<br>";
					if($flag_recommend_a == false):
		?>
						<a href='recommend_a.php?question_id=<?php echo $_GET["question_id"]?>&answer_id=<?php echo $row_a["A_id"];?>'>Good Answer</a>
		<?php
					else:
		?>
						<a href='cancel_recommend_a.php?question_id=<?php echo $_GET["question_id"]?>&answer_id=<?php echo $row_a["A_id"];?>'>Cancel</a>
		<?php
					endif;
					echo "<br>============================================<br>";
				endif;				
			else:
				echo "No Reply here QQ";
				echo "<br>-------------------------------------------------------------------------<br>";
			endif;
			
			/*Normal Answer*/
			for($i=0;$i<$counter;$i++){
				mysqli_data_seek($result_a, $i);
				$row_a=mysqli_fetch_assoc($result_a);
				if($_GET["question_id"] == $row_a["Q_ref"]){
					
					/*Who reply this question*/
					mysqli_data_seek($result_user, $row_a["id"]-1);
					$row_user=mysqli_fetch_assoc($result_user);
					echo "<a href=profile.php?id=" . $row_a["id"] . ">" . $row_user["name"] . "</a><br>";
					
					
					/*Reply time*/
					echo date("Y/m/d H:i", strtotime($row_a["reply_time"])) . "<br>";
					
					/*Reply context*/
					echo $row_a["answer"];
					
					/*Recommend*/
					$flag_recommend_a=false;
					for($j=0;$j<$counter_recommend_a;$j++){
						mysqli_data_seek($result_recommend_a, $j);
						$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
						if($row_a["A_id"] == $row_recommend_a["recommend_a_id"] && $_SESSION['is_login'] == $row_recommend_a["recommend_user_id"]){
							$flag_recommend_a = true;
							break;
						}
					}
					$number_of_recommend = 0;
					for($j=0;$j<$counter_recommend_a;$j++){
						mysqli_data_seek($result_recommend_a, $j);
						$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
						if($row_a["A_id"] == $row_recommend_a["recommend_a_id"]){
							$number_of_recommend++;
						}
					}
					echo "<br>" . $number_of_recommend . " people recommend this answer<br>";
					if($flag_recommend_a == false):
		?>
						<a href='recommend_a.php?question_id=<?php echo $_GET["question_id"]?>&answer_id=<?php echo $row_a["A_id"];?>'>Good Answer</a>
		<?php
					else:
		?>
						<a href='cancel_recommend_a.php?question_id=<?php echo $_GET["question_id"]?>&answer_id=<?php echo $row_a["A_id"];?>'>Cancel</a>
		<?php
					endif;
					
					echo "<br>-------------------------------------------------------------------------<br>";
				}
			}
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>