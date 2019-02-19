<?php
	error_reporting(0);
	include_once('../includes/variables.php');
	if (isset($_GET['accesskey']) && isset($_GET['Menu_ID'])) {
		
		$key = $_GET['accesskey'];
		$menu_id = $_GET['Menu_ID'];

		if ($key == $access_key) {
			$sql = "SELECT count(id) as id FROM tbl_comment_users WHERE Menu_ID = ".$menu_id."";

			$result = mysqli_query($connect, $sql);
			
			$show = array();

			while ($showComment = $result->fetch_assoc()) {
				$show = $showComment;
			}

			echo json_encode(array('comment'=>$show));

		}

	}
?>