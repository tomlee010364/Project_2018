<?php
session_start();
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
			echo $_SESSION['is_login'];
		?>
		<form id="ask_form" action="q_insert.php" method="post">
			<input type="text" name="main_idea" /><br>
			<input type="text" name="question_context" /><br>
			<button type="submit">submit</button>
		</form>
		<a href='logout.php'>logout</a>
		<?php
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>