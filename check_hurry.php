<?php

class check_hurry{
	function __construct(){
		$this->refresh();
	}
	
	public function refresh(){
		$conn = mysqli_connect("localhost", "root", "123", "project");
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		mysqli_set_charset($conn, "utf8");
		
		$sql_user = "SELECT user_id, name, point FROM `user`";
		$sql_q = "SELECT id, is_hurry, Q_id, reward_point, expire_time FROM `q`";
		$result_user=mysqli_query($conn, $sql_user);
		$result_q=mysqli_query($conn, $sql_q);
		$counter_q=mysqli_num_rows($result_q);
		$counter_user=mysqli_num_rows($result_user);
			
		for($i = $counter_q-1; $i > -1; $i--){
			
			mysqli_data_seek($result_q, $i);
			$row_q = mysqli_fetch_assoc($result_q);
			
			/*User's follow list*/
			for($j = 0; $j < $counter_user; $j++){
				$follow_list[] = False;
			}
			$sql_follow="SELECT * FROM `follow`";
			$result_follow=mysqli_query($conn, $sql_follow);
			$counter_follow=mysqli_num_rows($result_follow);
			for($j = 0; $j < $counter_follow; $j++){
				mysqli_data_seek($result_follow, $j);
				$row_follow=mysqli_fetch_assoc($result_follow);
				if($row_q["id"] == $row_follow["who_follow"]){
					$follow_list[$row_follow["follow_who"]-1] = True;
				}
			}
			
			if($row_q["is_hurry"] == 1):
				/*check if there is a reply or not*/
				$sql_a="SELECT * FROM `a`";
				$result_a=mysqli_query($conn, $sql_a);
				$counter_a=mysqli_num_rows($result_a);
				$sql_recommend_a="SELECT * FROM `recommend_a`";
				$result_recommend_a=mysqli_query($conn, $sql_recommend_a);
				$counter_recommend_a=mysqli_num_rows($result_recommend_a);
			
				$counter_reply = 0;
				for($j = 0; $j < $counter_a; $j++){
					mysqli_data_seek($result_a, $j);
					$row_a=mysqli_fetch_assoc($result_a);
					if($i+1 == $row_a["Q_ref"]){
						$counter_reply++;
					}
				}
				if($counter_reply > 0):
					/*Search for the reply which have most supporter*/
					$max = -1;
					$max_supporter_reply = NULL;
					$max_counter_follow = 0;
				
					for($j = 0; $j < $counter_a; $j++){
						mysqli_data_seek($result_a, $j);
						$row_a=mysqli_fetch_assoc($result_a);
						if($i+1 == $row_a["Q_ref"]){
							$number_of_recommend = 0;
							$counter_follow_support = 0;
							for($k = 0; $k < $counter_recommend_a; $k++){
								mysqli_data_seek($result_recommend_a, $k);
								$row_recommend_a=mysqli_fetch_assoc($result_recommend_a);
								if($row_a["A_id"] == $row_recommend_a["recommend_a_id"]){
									$number_of_recommend++;
									if($follow_list[$row_recommend_a["recommend_user_id"]-1] == True){
										$counter_follow_support++;
									}
								}
							}
							if($number_of_recommend > $max){
								$max_supporter_reply = $row_a["A_id"];
								$max = $number_of_recommend;
								$max_counter_follow = $counter_follow_support;
							}
							else if($number_of_recommend == $max && $max != 0){
								if($counter_follow_support > $max_counter_follow){
									$max_supporter_reply = $row_a["A_id"];
									$max_counter_follow = $counter_follow_support;
								}
								else if($counter_follow_support == $max_counter_follow){
									if(rand()%2 == 1){
										$max_supporter_reply = $row_a["A_id"];
									} // Use random to determine which reply is going to be recommended
								}
							}
						}
					}
					
					if($max > 0):
					
						/*Fetch user's data*/
						mysqli_data_seek($result_a, $max_supporter_reply-1);
						$row_a=mysqli_fetch_assoc($result_a);	
					
						date_default_timezone_set('Asia/Taipei');
						$time1 = new DateTime('now');
						$time2 = new DateTime($row_q["expire_time"]);
						$interval = $time1->diff($time2);
						if($interval->format('%R') == '-'):// %R == - means the time now is later than the expire time
							
							/*User now have how many points*/
							mysqli_data_seek($result_user, $row_a["id"]-1);
							$row_user = mysqli_fetch_assoc($result_user);
							$tmp_point = $row_user["point"];
							
							/*Update the points*/
							$tmp_id = $row_a["id"];
							$tmp_point += $row_q["reward_point"];
							$tmp_Q_id = $row_q["Q_id"];
							
							$sql_update = "UPDATE `user` SET point='$tmp_point' WHERE user_id='$tmp_id'";
							$conn->query($sql_update);
							$sql_update = "UPDATE `q` SET reward_point='0' WHERE Q_id='$tmp_Q_id'";
							$conn->query($sql_update);
							$sql_update = "UPDATE `q` SET is_hurry='0' WHERE Q_id='$tmp_Q_id'";
							$conn->query($sql_update);
						endif;
						
					else:// no recommend reply
						date_default_timezone_set('Asia/Taipei');
						$time1 = new DateTime('now');
						$time2 = new DateTime($row_q["expire_time"]);
						$interval = $time1->diff($time2);
						if($interval->format('%R') == '-'):// %R == - means the time now is later than the expire time
						
							$tmp_Q_id = $row_q["Q_id"];
							$sql_update = "UPDATE `q` SET is_hurry='0' WHERE Q_id='$tmp_Q_id'";
							$conn->query($sql_update);
						
							/*User now have how many points*/
							mysqli_data_seek($result_user, $row_q["id"]-1);
							$row_user = mysqli_fetch_assoc($result_user);
							$tmp_point = $row_user["point"];
							
							/*Update the points*/
							$tmp_id = $row_q["id"];
							$tmp_point += $row_q["reward_point"];
							
							$sql_update = "UPDATE `user` SET point='$tmp_point' WHERE user_id='$tmp_id'";
							$conn->query($sql_update);
							$sql_update = "UPDATE `q` SET reward_point='0' WHERE Q_id='$tmp_Q_id'";
							$conn->query($sql_update);
						endif;
					
					endif;
					
				else: // there is no reply
					date_default_timezone_set('Asia/Taipei');
					$time1 = new DateTime('now');
					$time2 = new DateTime($row_q["expire_time"]);
					$interval = $time1->diff($time2);
					if($interval->format('%R') == '-'):// %R == - means the time now is later than the expire time
						
						$tmp_Q_id = $row_q["Q_id"];
						$sql_update = "UPDATE `q` SET is_hurry='0' WHERE Q_id='$tmp_Q_id'";
						$conn->query($sql_update);
						
						/*User now have how many points*/
						mysqli_data_seek($result_user, $row_q["id"]-1);
						$row_user = mysqli_fetch_assoc($result_user);
						$tmp_point = $row_user["point"];
							
						/*Update the points*/
						$tmp_id = $row_q["id"];
						$tmp_point += $row_q["reward_point"];
							
						$sql_update = "UPDATE `user` SET point='$tmp_point' WHERE user_id='$tmp_id'";
						$conn->query($sql_update);
						$sql_update = "UPDATE `q` SET reward_point='0' WHERE Q_id='$tmp_Q_id'";
						$conn->query($sql_update);
						
					endif;
				endif;
			endif;
			
		}
	}
}
?>