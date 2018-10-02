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
		<script src="q_insert.js"></script>
	</head>
	
	<body>
		<?php
		if($_SESSION['is_login']!=null):
			//echo $_SESSION['is_login'];
		?>
		<form id="ask_form" action="q_insert.php" method="post">
			Title:<br>
			<input type="text" name="main_idea" /><br>
			Context:<br>
			<input type="text" name="question_context" /><br><br>
		<?php
			$sql="SELECT point FROM `user`";
			$result=mysqli_query($conn, $sql);
			mysqli_data_seek($result, $_SESSION['is_login']-1);
			$row=mysqli_fetch_assoc($result);
			if($row["point"]>=10):
		?>
				<input type="checkbox" name="hurry" id="hurry" onclick="myFunction()" />I am in a hurry<br>
				<p id="text1" style="display:none">How long:</p>
				<select id="time" name="time" style="display:none">
					<option value="5">5 minutes</option>
					<option value="10">10 minutes</option>
					<option value="15">15 minutes</option>
					<option value="30">30 minutes</option>
					<option value="60">1 hour</option>
				</select>
				<p id="text2" style="display:none">Point:</p>
				<select id="point" name="point" style="display:none">
		<?php
			
				for($i=10;$i<=50&&$i<=$row["point"];$i+=10){
					echo "<option value=" . $i . ">". $i ."points</option>";
				}
			else:
				echo "<p>You are not allowed to ask the question in hurry</p>";
			endif;
		?>
			</select><br>
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