<?php
	include_once('variables.php'); 
	error_reporting(0);
?>

<div id="content">
	<?php 
		if(isset($_POST['btnDelete'])){
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
		
			// get image file from menu table
			$sql_query = "SELECT Menu_image 
					FROM tbl_menu 
					WHERE Menu_ID = ?";
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {	
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $ID);
				// Execute query
				$stmt->execute();
				// store result 
				$stmt->store_result();
				$stmt->bind_result($menu_image);
				$stmt->fetch();
				$stmt->close();
			}
			
			// delete image file from directory
			$delete = unlink("$menu_image");
			
			// delete data from menu table
			$sql_query = "DELETE FROM tbl_menu 
					WHERE Menu_ID = ?";
			
			$stmt = $connect->stmt_init();
			if($stmt->prepare($sql_query)) {	
				// Bind your variables to replace the ?s
				$stmt->bind_param('s', $ID);
				// Execute query
				$stmt->execute();
				// store result 
				$delete_result = $stmt->store_result();
				$stmt->close();
			}
				
			// if delete data success back to reservation page
			if($delete_result){
				?>
				<script type="text/javascript">
					window.location = 'news-view.php';
				</script>
				<?php
			}
			
		}		
	?>
	<h1>Delete News ?</h1>
	<hr />
	<form method="post">
		<h3>
			Are you sure want to delete this news?
		</h3>
		<button class="btn btn-danger" type="submit" name="btnDelete">DELETE</button>
	</form>
	<div class="separator"> </div>
</div>