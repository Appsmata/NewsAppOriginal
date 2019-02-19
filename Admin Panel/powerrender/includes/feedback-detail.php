<?php
	include_once('variables.php'); 
?>

<div id="content">
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store data from database
		$data = array();
	
		
		// get all data from menu table and category table
		$sql_query = "SELECT id, full_name, gender, email, phone, date_post, city, country, txt_feed 
				FROM tbl_feedback
				WHERE id = ? ";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['id'], 
					$data['full_name'], 
					$data['gender'],
					$data['email'],
					$data['phone'],
					$data['date_post'],
					$data['city'],
					$data['country'],
					$data['txt_feed']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>
	<form method="post">
      <div class="col-md-12">
	      	<div class="col-md-12">
	      		<h5>Name</h5>
				<td type="text" class="form-control" name="menu_name" id="menu_name"  placeholder="Enter News Title"><?php echo $data['full_name'];?></td>
			</div>

	        <div class="col-md-12">
	        	<h5>Gender</h5>
	            <td class="detail"><?php echo $data['gender']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>Email</h5>
	            <td class="detail"><?php echo $data['email']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>Phone</h5>
	            <td class="detail"><?php echo $data['phone']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>Date Post</h5>
	            <td class="detail"><?php echo $data['date_post']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>City</h5>
	            <td class="detail"><?php echo $data['city']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>Country</h5>
	            <td class="detail"><?php echo $data['country']; ?></td>
	        </div>

	        <div class="col-md-12">
	        	<h5>Text Feedback</h5>
	            <td class="detail"><?php echo $data['txt_feed']; ?></td>
	        </div>
      </div>
    </form> 
				
	<div class="separator"> </div>
</div>