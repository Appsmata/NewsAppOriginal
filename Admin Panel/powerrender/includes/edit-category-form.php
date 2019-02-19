<?php
	include_once('variables.php'); 
	include_once('functions.php'); 
?>
<div id="content">
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT Category_image 
				FROM tbl_category 
				WHERE Category_ID = ?";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt_category->bind_param('s', $ID);
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($previous_category_image);
			$stmt_category->fetch();
			$stmt_category->close();
		}
		
			
		if(isset($_POST['btnEdit'])){
			$category_name = $_POST['category_name'];
			
			// get image info
			$menu_image = $_FILES['category_image']['name'];
			$image_error = $_FILES['category_image']['error'];
			$image_type = $_FILES['category_image']['type'];
				
			// create array variable to handle error
			$error = array();
				
			if(empty($category_name)){
				$error['category_name'] = "*Category name should be filled.";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			$extension = explode(".", $_FILES["category_image"]["name"]);
			$tampung = end($extension);
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($tampung, $allowedExts))){
					
					$error['category_image'] = "*Image type should be jpg, jpeg, gif, or png.";
				}
			}
				
			if(!empty($category_name) && empty($error['category_image'])){
					
				if(!empty($menu_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
					$function = new functions;
					$category_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$tampung;
				
					// delete previous image
					$delete = unlink("$previous_category_image");
					
					// upload new image
					$upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/images/'.$category_image);
	  
					$sql_query = "UPDATE tbl_category 
							SET Category_name = ?, Category_image = ?
							WHERE Category_ID = ?";
							
					$upload_image = 'upload/images/'.$category_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sss', 
									$category_name, 
									$upload_image,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					$sql_query = "UPDATE tbl_category 
							SET Category_name = ?
							WHERE Category_ID = ?";
					
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ss', 
									$category_name, 
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
				
				// check update result
				if($update_result){
					$error['update_category'] = "*Category has been successfully updated.";
				}else{
					$error['update_category'] = "*Failed updating category.";
				}
			}
				
		}
			
		// create array variable to store previous data
		$data = array();
		
		$sql_query = "SELECT * 
				FROM tbl_category 
				WHERE Category_ID = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['Category_ID'], 
					$data['Category_name'],
					$data['Category_image']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>
	<form method="post" enctype="multipart/form-data">
      <div class="col-md-12">
      	<div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="category_name" id="category_name" value="<?php echo $data['Category_name'];?>" required>
                <label class="form-label">Name</label>
            </div>
        </div>
      	<div class="panel col-md-12">
          	<div class="col-md-4">
          		<h4>Action</h4>
              	<input type="submit" class="btn-primary btn" value="Edit Category" name="btnEdit" />&nbsp;
              	<a href="category-view.php"><button class="btn btn-danger">Cancel</button></a>
          	</div>
   
        	<div class="col-md-8">
        		<h4>Add Image Before Your Publish</h4>
        		<input name="category_image" type='file' onchange="readURL(this);" />
        		<img src="<?php echo $data['Category_image']; ?>" width="256" height="256"/>
    			<img id="blah"/>
    			<p class="alert"><?php echo isset($error['category_image']) ? $error['category_image'] : '';?></p>
        	</div>
        </div>
      </div>
    </form>
		
	<div class="separator"> </div>
</div>