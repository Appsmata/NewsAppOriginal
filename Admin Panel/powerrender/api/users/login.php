<?php
	require_once 'function_db.php';
	$db = new function_db();

	$response = array("error" => FALSE);

	if (isset($_POST['email']) && isset($_POST['password'])) {

		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = $db->getUserByEmailPassword($email, $password);

		if ($user != false) {
			
			$response["error"] = FALSE;
			$response["login"]["ids"] = $user["User_Id"];
			$response["login"]["uid"] = $user["unique_id"];
			$response["login"]["name"] = $user["name"];
			$response["login"]["email"] = $user["email"];
			$response["login"]["created_at"] = $user["created_at"];
			$response["login"]["profile_image"] = $user["profile_image"];
			echo json_encode($response);
		}else{
			$response["error"] = TRUE;
			$response["error_msg"] = "Login again bro";
			echo json_encode($response);
		}
	}else{
		$response["error"] = TRUE;
		$response["error_msg"] = "Please input email or password";
		echo json_encode($response);
	}
?>