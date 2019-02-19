<?php
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$id = $_POST['User_Id'];
		$name = $_POST['name'];

		require_once '../../includes/variables.php';

		$sql = "UPDATE tbl_app_users SET name='$name' WHERE User_Id='$id'";

		if (mysqli_query($connect, $sql)) {

			$result['success'] = "powerrender";
			$result['message'] = "success";

			echo json_encode($result);
			mysqli_close($connect);

		}
	}

?>