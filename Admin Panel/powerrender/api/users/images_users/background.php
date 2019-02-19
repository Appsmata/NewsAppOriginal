<?php
	include_once('includes/variables.php'); 
	include_once('includes/functions.php'); 
?>
<div id="content">

	<?php

	if (isset($_POST['submit'])) {
		
		$filetmp = $_FILES["file_img"]["tmp_name"];
		$filename = $_FILES["file_img"]["name"];
		$filetype = $_FILES["file_img"]["type"];
		$filepath = "api/users/images_users/bgdrawer/".$filename;

		$ready = "SELECT * FROM tbl_background_drawer";
		$ready_check = mysqli_query($connect, $ready);

		while ($row = mysqli_fetch_array($ready_check)) {
			$dd = $row['background_image'];
		}

		if ($ready_check->num_rows == 1) {
			unlink($dd);
			move_uploaded_file($filetmp, $filepath);
			$sql = "UPDATE tbl_background_drawer SET background_image = '$filepath' WHERE id = '1'";
			$result = mysqli_query($connect, $sql);
		}else{
			move_uploaded_file($filetmp, $filepath);
			$sql = "INSERT INTO tbl_background_drawer (background_image) VALUES ('$filepath')";
			$result = mysqli_query($connect, $sql);
		}

	}

	?>

	<form method="post" enctype="multipart/form-data">
     
		<input type="file" name="file_img" id="image">
		<br>
		<input type="submit" name="submit" id="Insert" class="btn btn-success">

	</form>

	<br>

	<table class="table table-bordered">
		
			
		<?php 
			$ready = "SELECT * FROM tbl_background_drawer";
			$ready_check = mysqli_query($connect, $ready);
			while ($row = mysqli_fetch_array($ready_check)) {

		?>

			<img src="<?php include 'component/image_public.php';?><?php echo $row['background_image'];?>" style="width: 100%; height: 100%;">

		<?php

			}

		?>

	<div class="separator"> </div>
</div>