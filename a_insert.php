<?php
session_start();
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$tmp_id = $_SESSION['is_login'];
$tmp_q = $_GET['question_id'];
$tmp_context = $_POST['answer_context'];

$sql = "INSERT INTO `a`(id, Q_ref, answer)
VALUES('$tmp_id', '$tmp_q', '$tmp_context')";

if ($conn->query($sql) == TRUE) {
?>
	<meta http-equiv="refresh" content="0;url=q.php?question_id=<?php echo $_GET['question_id'];?>" />
<?php
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>