<?php

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$id = $_POST['User_Id'];
		$profile = $_POST['profile_image'];

		$path = "$id.jpeg";
		$path_url = "".$path;

		require_once '../../../includes/variables.php';

		$sql = "UPDATE tbl_app_users SET profile_image='$path_url' WHERE User_Id='$id'";

		if (mysqli_query($connect, $sql)) {
			if (file_put_contents($path, base64_decode($profile))) {
				
				$result['success'] = "powerrender";
				$result['message'] = "success";

				echo json_encode($result);
				mysqli_close($connect);

			}
		}

	}
?>