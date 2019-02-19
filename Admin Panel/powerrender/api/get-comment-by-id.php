<?php
	include_once('../includes/variables.php');
	include_once('function_date.php');

	if (isset($_GET['accesskey']) && isset($_GET['Menu_ID'])) {
		
		$key = $_GET['accesskey'];
		$menu_id = $_GET['Menu_ID'];

		if ($key == $access_key) {

			$sql_query = "SELECT * FROM tbl_comment_users inner join tbl_app_users on tbl_comment_users.User_id=tbl_app_users.User_id WHERE Menu_ID = ".$menu_id."";

			$result = mysqli_query($connect, $sql_query);
			$res = array();

			while ($comment = mysqli_fetch_array($result)) {
				array_push($res, array(
					'id'=>$comment['id'],
					'User_Id'=>$comment['User_Id'],
					'Menu_ID'=>$comment['Menu_ID'],
					'comment'=>$comment['comment'],
					'comment_date'=>datePost($comment['comment_date']),
					'name'=>$comment['name'],
					'profile_image'=>$comment['profile_image']));
			}

			echo json_encode(array('data'=>$res));
		}else{
			die('accesskey is incorrect.');
		}
	}else{
		die('accesskey and comment id are required.');
	}

?>