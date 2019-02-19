<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$app_user_id = $_POST['User_Id'];
		$menu_id = $_POST['Menu_ID'];
		$comment = $_POST['comment'];
		$date_post = date("Y-m-d");

		require_once '../../includes/variables.php';

		$sql = "INSERT INTO tbl_comment_users (User_Id, Menu_ID, comment, comment_date) VALUES ('$app_user_id', '$menu_id', '$comment', '$date_post')";

		if (mysqli_query($connect, $sql)) {
			
			$result['success'] = "powerrender_comment";
			$result['message'] = "Adding comment";

			echo json_encode($result);
			mysqli_close($connect);

		}
	}
?>