<?php
	include_once('../includes/variables.php');
	if (isset($_GET['accesskey'])) {
		
		$key_access = $_GET['accesskey'];

		if ($key_access == $access_key) {

			$sql = "SELECT * FROM tbl_background_drawer";

			$result = mysqli_query($connect, $sql);

			$drawer = array();

			while ($bg_drawer = $result->fetch_assoc()) {
				$drawer = $bg_drawer;
			}

			echo json_encode(array('drawer'=>$drawer));

		}else{
			die("incorect accesskey");
		}
	}else{
		die("don't forget you must put accesskey");
	}
?>