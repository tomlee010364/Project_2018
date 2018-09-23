<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
		<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
		</script>
		<script src="register.js"></script>
	</head>
	
	<body>
		<?php
		if(isset($_SESSION['is_login'])):
			header('Location: homepage.php');
		else:
		?>
		<form id="register_form" method="post">
			User ID*:<br>
			<input type="text" name="account" /><br><br>
			Password*:<br>
			<input type="password" name="pw" /><br><br>
			Name*:<br>
			<input type="text" name="n" /><br><br>
			Telephone:<br>
			<input type="text" name="p" /><br><br>
			Mobilephone:<br>
			<input type="text" name="m" /><br><br>
			Email Address*:<br>
			<input type="text" name="e" /><br><br>
			<button type="submit">register</button>
		</form>
		<?php
		endif
		?>
	</body>
</html>