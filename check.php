<?php
$conn = mysqli_connect("localhost", "root", "123", "project");
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

$tmp_id=$_POST["account"];
$tmp_pw=$_POST["pw"];

$sql="SELECT user_id, account_id, password FROM `user`";
$result=mysqli_query($conn, $sql);
$flag_id=false;//match id or not
$flag_pw=false;//match password or not
$p;//record user id

if(mysqli_num_rows($result) > 0){
	while($row=mysqli_fetch_assoc($result)){
		if($tmp_id==$row["account_id"]){
			$flag_id=true;//id is correct
			if(MD5("$tmp_pw")==$row["password"]){
				$flag_pw=true;//password is correct
				$p=$row["user_id"];
				break;
			}
			else{
				$flag_pw=false;
			}
		}
		else{
			$flag_id=false;
		}
	}
}
if($flag_id==true&&$flag_pw==true){
	echo $p;
}
else if($flag_id==true&&$flag_pw==false){
	echo "password is wrong";
}
else{
	echo "account is wrong";
}
	
?>