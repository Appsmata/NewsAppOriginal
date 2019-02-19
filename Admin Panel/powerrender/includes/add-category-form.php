<?php
	include_once('variables.php'); 
	include_once('functions.php'); 
?>
<div id="content">
	<?php 
		if(isset($_POST['btnAdd'])){
			$category_name = $_POST['category_name'];
			
			// get image info
			$menu_image = $_FILES['category_image']['name'];
			$image_error = $_FILES['category_image']['error'];
			$image_type = $_FILES['category_image']['type'];
			
			// create array variable to handle error
			$error = array();
			
			if(empty($category_name)){
				$error['category_name'] = "You must insert category name";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			$extension = (explode(".", $_FILES["category_image"]["name"]));

			$merge = end($extension);
					
			if($image_error > 0){
				$error['category_image'] = "Image should be uploaded.";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($merge, $allowedExts))){
			
				$error['category_image'] = "Image type should be jpg, jpeg, gif, or png.";
			}
			
			if(!empty($category_name) && empty($error['category_image'])){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
				$function = new functions;
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$merge;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/images/'.$menu_image);
		
				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_category (Category_name, Category_image)
						VALUES(?, ?)";
				
				$upload_image = 'upload/images/'.$menu_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ss', 
								$category_name, 
								$upload_image
								);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
				
				if($result){
					$error['add_category'] = "New category has been successfully added.";
				}else{
					$error['add_category'] = "Failed adding new category.";
				}
			}
			
		}
	?>
	<hr />
	<form method="post" enctype="multipart/form-data">
      <div class="col-md-12">
      	<div class="form-group form-float">
	        <div class="form-line">
	            <input type="text" class="form-control" name="category_name" id="category_name">
	            <label class="form-label">Category Title</label>
	        </div>
	        <p><?php echo isset($error['category_name']) ? $error['category_name'] : '';?></p>
	    </div>
      	<div class="panel col-md-12">
          	<div class="col-md-4">
          		<h4>Action</h4>
              	<input type="submit" class="btn-primary btn" value="Publish" name="btnAdd"/>
          	</div>
   
        	<div class="col-md-8">
        		<h4>Add Image Before Your Publish</h4>
        		<input name="category_image" type='file' onchange="readURL(this);"/>
    			<img id="blah"/>
    			<p><?php echo isset($error['category_image']) ? $error['category_image'] : '';?></p>
        	</div>
        </div>
      </div>
    </form>
	<div class="separator"> </div>
</div>