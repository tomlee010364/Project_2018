<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>homepage</title>
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
		<a href='qa.php'>Q&A</a>
		<a href='profile.php?id=<?php echo $_SESSION['is_login'];?>'>Profile Page</a>
		<a href='logout.php'>logout</a>
		
		<?php
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>