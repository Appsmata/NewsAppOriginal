<?php
	if (!isset($_SESSION)) {
		session_start();
	}
	require_once('variables.php'); 
	if(isset($_POST['submit'])){
	
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$currentTime = time() + 25200;
		$expired = 3600;
		
		$error = array();
		
		if(empty($username)){
			$error['username'] = "Please Username should be filled.";
		}

		if(empty($password)){
			$error['password'] = "Please Password should be filled.";
		}
		
		if(!empty($username) && !empty($password)){
			
			$username = strtolower($username);

		    $password = hash('sha256',$username.$password);
			
			if (isset($_POST['submit'])) {
				$result = mysqli_query($connect, "SELECT * FROM tbl_user WHERE Username = '" . $_POST["username"]. "' AND password = '".$_POST["password"]."'");
				$row = mysqli_fetch_array($result);

				if (is_array($row)) {
					$_SESSION['user'] = $username["Username"];
					header("location:../dashboard.php");
				}else{
					?>
					<script type="text/javascript">
						window.alert("Your Username or Password Incorrect");
						window.location = '../index.php';
					</script>
					<?php
				}
			}
			
		}	
	}
	?>