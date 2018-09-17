<?php
session_start();
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$tmp_id = $_SESSION['is_login'];
$tmp_topic = $_POST['main_idea'];
$tmp_context = $_POST['question_context'];

$sql = "INSERT INTO `q`(id, topic, context)
VALUES('$tmp_id', '$tmp_topic', '$tmp_context')";

if ($conn->query($sql) === TRUE) {
    header('Location: qa.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>