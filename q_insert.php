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
$tmp_hurry = $_POST['hurry'];
$tmp_time = $_POST['time'];
$tmp_point = $_POST['point'];

if($tmp_hurry == 'on'){
	/*calculate the expiretime*/
	date_default_timezone_set('Asia/Taipei');
	$time = new DateTime('now');
	$time->add(new DateInterval('PT' . $tmp_time . 'M'));
	$show_time = $time->format('Y-m-d H:i:s');
	
	$sql = "INSERT INTO `q`(id, topic, context, is_hurry, reward_point, expire_time)
	VALUES('$tmp_id', '$tmp_topic', '$tmp_context', '1', '$tmp_point', '$show_time')";
	
	
	/*User now have how many points*/
	$sql_user="SELECT point FROM `user`";
	$result_user=mysqli_query($conn, $sql_user);
	mysqli_data_seek($result_user, $tmp_id-1);
	$row_user = mysqli_fetch_assoc($result_user);
	$tmp_p = $row_user["point"];
	$tmp_p -= $tmp_point;
	
	$sql_prepay = "UPDATE `user` SET point='$tmp_p' WHERE user_id='$tmp_id'";
	$conn->query($sql_prepay);
}
else{
	$sql = "INSERT INTO `q`(id, topic, context)
	VALUES('$tmp_id', '$tmp_topic', '$tmp_context')";
}

if ($conn->query($sql) == TRUE) {
    header('Location: qa.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>