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
		<title>Profile</title>
		<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
		</script>
	</head>
	
	<body>
		<?php
		if($_SESSION['is_login'] == $_GET['id']): // user's own page
			echo "MY OWN PAGE!!";
		else:
			/*Recommend*/
			$sql_follow="SELECT * FROM `follow`";
			$result_follow=mysqli_query($conn, $sql_follow);
			$counter_follow=mysqli_num_rows($result_follow);
			$flag_follow=false;
			for($j=0;$j<$counter_follow;$j++){
				mysqli_data_seek($result_follow, $j);
				$row_follow=mysqli_fetch_assoc($result_follow);
				if($_GET['id'] == $row_follow["follow_who"] && $_SESSION['is_login'] == $row_follow["who_follow"]){
					$flag_follow = true;
					break;
				}
			}
			$number_of_follower = 0;
			for($j=0;$j<$counter_follow;$j++){
				mysqli_data_seek($result_follow, $j);
				$row_follow=mysqli_fetch_assoc($result_follow);
				if($_GET['id'] == $row_follow["follow_who"]){
					$number_of_follower++;
				}
			}
			echo "<br>" . $number_of_follower . " people follow this person<br>";
			if($flag_follow == false):
		?>
				<a href='follow.php?id=<?php echo $_GET['id']?>'>Follow</a>
		<?php
			else:
		?>
				<a href='cancel_follow.php?id=<?php echo $_GET['id']?>'>Cancel</a>
		<?php
			endif;
			
		endif;
		?>
	</body>
</html>