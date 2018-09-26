<?php
session_start();
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$tmp_who_follow = $_SESSION['is_login'];
$tmp_follow_who = $_GET['id'];

$sql = "DELETE FROM `follow` WHERE who_follow=$tmp_who_follow AND follow_who=$tmp_follow_who";

if ($conn->query($sql) == TRUE) {
?>
	<meta http-equiv="refresh" content="0;url=profile.php?id=<?php echo $_GET['id'];?>" />
<?php
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>