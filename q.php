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
			$sql="SELECT * FROM `q`";
			$result=mysqli_query($conn, $sql);
			mysqli_data_seek($result, $_GET["question_id"]-1);
			$row=mysqli_fetch_assoc($result);
			
			/*Who ask this question*/
			$sql_user="SELECT user_id, name FROM `user`";
			$result_user=mysqli_query($conn, $sql_user);
			$tmp_id=$row["id"];
			mysqli_data_seek($result_user, $tmp_id-1);
			$row_user=mysqli_fetch_assoc($result_user);
			
			echo "<h1>" . $row["topic"] . "</h1>";
			echo $row_user["name"] . "<br>";
			echo $row["context"] . "<br>";
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
					
					/*Reply context*/
					echo $row_a["answer"];
					echo "<br>-------------------------------------------------------------------------<br>";
				}
			}
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>