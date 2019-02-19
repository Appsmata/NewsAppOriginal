<?php
	include "variables.php";
	$fcm_token = $_POST["fcm_token"];
	$sql = "INSERT INTO tbl_fcm_info (id, fcm_token) VALUES ('', '$fcm_token')";
	mysqli_query($connect, $sql);
	mysqli_close($connect);
?>