<?php
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$response = array();
		$full_name = $_POST['full_name'];
		$gender = $_POST['gender'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$date_post = date("Y-m-d H:i:s");
		$city = $_POST['city'];
		$country = $_POST['country'];
		$txt_feed = $_POST['txt_feed'];

		include "../includes/variables.php";
		$query = "INSERT INTO tbl_feedback (id, full_name, gender, email, phone, date_post, city, country, txt_feed) VALUES ('', '$full_name', '$gender', '$email', '$phone', '$date_post', '$city', '$country', '$txt_feed')";

		if (mysqli_query($connect, $query)) {
			$response["value"] = 1;
			$response["message"] = "Success";
			echo json_encode($response);
		}else{
			$response["value"] = 0;
			$response["message"] = "try again";
			echo json_encode($response);
		}
		mysqli_close($connect);
	}else{
		$response["value"] = 0;
		$response["message"] = "try";
		echo json_encode($response);
	}

?>