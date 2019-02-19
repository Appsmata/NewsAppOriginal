<?php
	require_once 'function_db.php';
	$db = new function_db();

	$response = array("error" => FALSE);

	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
		
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];

		if ($db->isUserExist($email)) {
			$response["error"] = TRUE;
			$response["error_message"] = "Username already " . $email;
			echo json_encode($response);
		}else{
			$user = $db->storeUser($name, $email, $password);
			if ($user) {
				$response["error"] = FALSE;
				$response["register"]["ids"] = $user["User_Id"];
				$response["register"]["uid"] = $user["unique_id"];
				$response["register"]["name"] = $user["name"];
				$response["register"]["email"] = $user["email"];
				$response["register"]["created_at"] = $user["created_at"];
				$response["register"]["profile_image"] = $user["profile_image"];
				echo json_encode($response);
			}else{
				$response["error"] = TRUE;
				$response["error_message"] = "Failed";
				echo json_encode($response);
			}
		}
	}else{
		$response["error"] = TRUE;
		$response["error_message"] = "Failed load";
		echo json_encode($response);
	}
?>