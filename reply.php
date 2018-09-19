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
		<title>Reply</title>
		<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
		</script>
	</head>
	
	<body>
		<?php
		if($_SESSION['is_login']!=null):
		?>
		<form id="answer_form" action="a_insert.php?question_id=<?php echo $_GET["question_id"];?>" method="post">
			<input type="text" name="answer_context" /><br>
			<button type="submit">submit</button>
		</form>
		<?php
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>