<?php
	include_once('variables.php'); 
	include_once('functions.php'); 
?>
<div id="content">
	<?php 
		// error_reporting(0);
		$date_post = date("Y-m-d H:i:s");
		$sql_query = "SELECT Category_ID, Category_name 
			FROM tbl_category 
			ORDER BY Category_ID ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['Category_ID'], $category_data['Category_name']);		
		}
			
		$max_serve = 10;
			
		if(isset($_POST['btnAdd'])){
			$menu_name = $_POST['menu_name'];
			$short_title = $_POST['short_menu'];
			$category_ID = $_POST['category_ID'];
			$description = $_POST['description'];
				
			// get image info
			$menu_image = $_FILES['menu_image']['name'];
			$image_error = $_FILES['menu_image']['error'];
			$image_type = $_FILES['menu_image']['type'];
			
				
			// create array variable to handle error
			$error = array();
			
			if(empty($menu_name)){
				$error['menu_name'] = "You must insert title news";
			}

			if (empty($short_title)) {
				$error['short_menu'] = "You must insert short title";
			}
				
			if(empty($category_ID)){
				$error['category_ID'] = "You must choose category";
			}							

			if(empty($description)){
				$error['description'] = "You must insert news body";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			$extension = explode(".", $_FILES["menu_image"]["name"]);
			$file_extension = end($extension);
					
			if($image_error > 0){
				$error['menu_image'] = "Image should be uploaded.";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($file_extension, $allowedExts))){
			
				$error['menu_image'] = "Image type should be jpg, jpeg, gif, or png.";
			}
				
			if(!empty($menu_name) && !empty($short_title) && !empty($category_ID) && empty($error['menu_image']) && !empty($description)){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['menu_image']['name']);
				$function = new functions;
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$file_extension;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['menu_image']['tmp_name'], 'upload/images/'.$menu_image);
		
				// insert new data to menu table
				$sql_query = "INSERT INTO tbl_menu (Menu_name, Short_title, Category_ID, Menu_image, Description, Date_News)
						VALUES(?, ?, ?, ?, ?, ?)";
						
				$upload_image = 'upload/images/'.$menu_image;
				$stmt = $connect->stmt_init();
				if($stmt->prepare($sql_query)) {	
					// Bind your variables to replace the ?s
					$stmt->bind_param('ssssss', 
								$menu_name,
								$short_title, 
								$category_ID,  
								$upload_image,
								$description,
								$date_post);
					// Execute query
					$stmt->execute();
					// store result 
					$result = $stmt->store_result();
					$stmt->close();
				}
				
				if($result){
					$error['add_menu'] = "New news has been successfully added.";
				}else{
					$error['add_menu'] = "*Failed adding new news.";
				}
			}
				
			}
	?>
	<form method="post" enctype="multipart/form-data">
      	<div class="col-md-12">
	      	<div class="form-group form-float">
	            <div class="form-line">
	                <input type="text" class="form-control" name="menu_name" id="menu_name">
	                <label class="form-label">News Title</label>
	            </div>
	            <h4><?php echo isset($error['menu_name']) ? $error['menu_name'] : '' ;?></h4>
	        </div>
       	</div>
       	<div class="col-md-12">
	      	<div class="form-group form-float">
	            <div class="form-line">
	                <input type="text" class="form-control" name="short_menu" id="short_menu" maxlength="100">
	                <label class="form-label">Short Title (Maxlength 100)</label>
	            </div>
	            <h5><?php echo isset($error['short_menu']) ? $error['short_menu'] : '' ;?></h5>
	        </div>
       	</div>
        <div class="col-md-12">
        	<h4>Your Article</h4>
            <textarea name="description" id="description" class="form-control" rows="16"></textarea>
            <script type="text/javascript" src="css/js/editors.js"></script>
            <script src="plugins/ckeditor/ckeditor.js"></script>
            <script type="text/javascript">                        
                      CKEDITOR.replace( 'description' );
              </script>
            <br>
            <h6><?php echo isset($error['description']) ? $error['description'] : '';?></h6>
        </div>

        <div class="col-md-4">
        	<h4>Choose News Category</h4>
                <select name="category_ID" class="form-control">
                  <?php while($stmt_category->fetch()){ ?>
                    <option value="<?php echo $category_data['Category_ID']; ?>"><?php echo $category_data['Category_name'];?></option>
                  <?php } ?>
                </select>
            <h6><?php echo isset($error['category_ID']) ? $error['category_ID'] : '';?></h6>
        <br>
        <br>
        <div>
        	<h4>Choose Action</h4>
          <div class="panel-heading"></div>
            <div class="panel-body">
              <input type="submit" class="btn-primary btn" value="Publish" name="btnAdd"/>
            </div>
        </div>
        </div>
        <div>
        	<div class="col-md-8">
        		<h4>Add Image Before Your Publis News</h4>
        		<input name="menu_image" type='file' onchange="readURL(this);"/>
    			<img id="blah"/>
    			<h6><?php echo isset($error['menu_image']) ? $error['menu_image'] : '';?></h6>
        	</div>
        </div>
      </div>
    </form> 		
	<div class="separator"> </div>
</div>