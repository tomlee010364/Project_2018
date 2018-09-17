<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>login</title>
		<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
		</script>
		<script src="main.js"></script>
	</head>
	
	<body>
		<?php
		if(isset($_SESSION['is_login'])):
			header('Location: homepage.php');
		else:
		?>
		<form id="submit_form" method="post">
			<input type="text" name="account" /><br>
			<input type="password" name="pw" /><br>
			<button type="submit">login</button>
		</form>
		<?php
		endif
		?>
	</body>
</html>