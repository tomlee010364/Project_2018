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
			echo "<h1>" . $row["topic"] . "</h1>";
			echo $row["context"] . "<br>";
		?>
		
		<a href='qa.php'>Q&A</a>
		<?php
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>