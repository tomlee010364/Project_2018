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
		<a href='ask.php'>ask</a>
		<a href='logout.php'>logout</a>
		<?php
		if($_SESSION['is_login']!=null):
			require "check_hurry.php";
			$check = new check_hurry();
			
			//echo $_SESSION['is_login'];
			echo "<br>";
			$sql_user="SELECT user_id, name FROM `user`";
			$sql_q="SELECT id, topic, Q_id, ask_time FROM `q`";
			$result_user=mysqli_query($conn, $sql_user);
			$result_q=mysqli_query($conn, $sql_q);
			$counter=mysqli_num_rows($result_q);
			
			for($i=$counter-1;$i>-1;$i--){
				mysqli_data_seek($result_q, $i);
				$row_q=mysqli_fetch_assoc($result_q);
				mysqli_data_seek($result_user, $row_q["id"]-1);
				$row_user=mysqli_fetch_assoc($result_user);
				echo "<a href=profile.php?id=" . $row_q["id"] . ">" . $row_user["name"] . "</a><br>";
				echo "<a href='q.php?question_id=" . $row_q["Q_id"] . "'>" . $row_q["topic"] . "</a><br>";
				echo date("Y/m/d H:i", strtotime($row_q["ask_time"]));
				echo "<br><br>";
			}
		else:
			header('Location: login.php');
		endif;
		?>
	</body>
</html>