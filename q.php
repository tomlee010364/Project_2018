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
			$tmp_id=$row_q["id"];
			mysqli_data_seek($result_user, $tmp_id-1);
			$row_user=mysqli_fetch_assoc($result_user);
			
			echo "<h1>" . $row_q["topic"] . "</h1>";
			echo $row_user["name"] . "<br>";
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
			$sql_a="SELECT * FROM `a`";
			$result_a=mysqli_query($conn, $sql_a);
			$counter=mysqli_num_rows($result_a);
			for($i=0;$i<$counter;$i++){
				mysqli_data_seek($result_a, $i);
				$row_a=mysqli_fetch_assoc($result_a);
				if($_GET["question_id"] == $row_a["Q_ref"]){
					
					/*Who reply this question*/
					$tmp_id=$row_a["id"];
					mysqli_data_seek($result_user, $tmp_id-1);
					$row_user=mysqli_fetch_assoc($result_user);
					echo $row_user["name"] . "<br>";
					
					/*Reply time*/
					echo date("Y/m/d H:i", strtotime($row_a["reply_time"])) . "<br>";
					
					/*Reply context*/
					echo $row_a["answer"];
					
					/*Recommend*/
					$sql_recommend_a="SELECT * FROM `recommend_a`";
					$result_recommend_a=mysqli_query($conn, $sql_recommend_a);
					$counter_recommend_a=mysqli_num_rows($result_recommend_a);
					$flag_recommend_a=false;
					for($j=0;$j<$counter_recommend_a;$j++){
						mysqli_data_seek($result_recommend_a, $j);
						$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
						if($row_a["A_id"] == $row_recommend_a["recommend_a_id"] && $_SESSION['is_login'] == $row_recommend_a["recommend_user_id"]){
							$flag_recommend_a = true;
							break;
						}
					}
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