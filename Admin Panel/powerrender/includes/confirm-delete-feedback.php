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
			$sql_query = "DELETE FROM tbl_feedback 
					WHERE id = ?";
			
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
					window.location = 'feedback.php';
				</script>
				<?php
			}
			
		}		
	?>
	<h1>Delete Feedback ?</h1>
	<hr/>
	<form method="post">
		<h3>
			Are you sure want to delete Feedback from User?
		</h3>
		<button class="btn btn-danger" type="submit" name="btnDelete">DELETE</button>
	</form>
	<div class="separator"> </div>
</div>