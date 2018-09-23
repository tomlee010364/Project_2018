<?php
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$tmp_id = $_POST["account"];
$tmp_pw = $_POST["pw"];
$tmp_name = $_POST["n"];
$tmp_telephone = $_POST["p"];
$tmp_mobilephone = $_POST["m"];
$tmp_email = $_POST["e"];
$flag = false;

if($tmp_id == ""){
	echo "User ID cannot be empty";
}
else if($tmp_pw == ""){
	echo "Password cannot be empty";
}
else if($tmp_name == ""){
	echo "Name cannot be empty";
}
else if($tmp_email == ""){
	echo "Email Address cannot be empty";
}
else{
	//chek available
	$flag = true; //check account_id and name is available or not
	$sql = "SELECT user_id, account_id, name FROM `user`";
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			if($tmp_id == $row["account_id"]){
				echo "Account_ID is not available";
				$flag = false;
				break;
			}
			else if($tmp_name == $row["name"]){
				echo "Name is not available";
				$flag = false;
				break;
			}
		}
		if($flag == true){
			$sql = "INSERT INTO `user`(account_id, password, name, telephone, mobilephone, email)
			VALUES('$tmp_id', MD5('$tmp_pw'), '$tmp_name', '$tmp_telephone', '$tmp_mobilephone', '$tmp_email')";
			if ($conn->query($sql) == true) {
				echo "success";
			}
			else{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}
}
?>