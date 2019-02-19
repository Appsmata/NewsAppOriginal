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
		$date_post = date("Y-m-d H:i:s");
		// create array variable to store category data
		$category_data = array();
			
		$sql_query = "SELECT Category_ID, Category_name
				FROM tbl_category 
				ORDER BY Category_ID ASC";
				
		$stmt_category = $connect->stmt_init();
		if($stmt_category->prepare($sql_query)) {	
			// Execute query
			$stmt_category->execute();
			// store result 
			$stmt_category->store_result();
			$stmt_category->bind_result($category_data['Category_ID'], 
				$category_data['Category_name']
				);
				
		}
			
		$sql_query = "SELECT Menu_image FROM tbl_menu WHERE Menu_ID = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_menu_image);
			$stmt->fetch();
			$stmt->close();
		}		
		
		if(isset($_POST['btnEdit'])){
			
			$menu_name = $_POST['menu_name'];
			$short_title = $_POST['short_title'];
			$category_ID = $_POST['category_ID'];
			$description = $_POST['description'];
			
			// get image info
			$menu_image = $_FILES['menu_image']['name'];
			$image_error = $_FILES['menu_image']['error'];
			$image_type = $_FILES['menu_image']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($menu_name)){
				$error['menu_name'] = "*Menu name should be filled.";
			}
			
			if(empty($category_ID)){
				$error['short_title'] = "Yosfa";
			}	

			if(empty($category_ID)){
				$error['category_ID'] = "*Category should be selected.";
			}						

			if(empty($description)){
				$error['description'] = "*Description should be filled.";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			$extension = explode(".", $_FILES["menu_image"]["name"]);

			$tampung = end($extension);
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($tampung, $allowedExts))){
					
					$error['menu_image'] = "*Image type should be jpg, jpeg, gif, or png.";
				}
			}
			
					
			if(!empty($menu_name) && !empty($category_ID) && !empty($description) && empty($error['menu_image'])){
				
				if(!empty($menu_image)){
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['menu_image']['name']);
					$function = new functions;
					$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$tampung;
				
					// delete previous image
					$delete = unlink("$previous_menu_image");
					
					// upload new image
					$upload = move_uploaded_file($_FILES['menu_image']['tmp_name'], 'upload/images/'.$menu_image);
	  
					// updating all data
					$sql_query = "UPDATE tbl_menu 
							SET Menu_name = ?,  Short_title = ? , Category_ID = ?, Menu_image = ?, Description = ?, Date_News = ? 
							WHERE Menu_ID = ?";
					
					$upload_image = 'upload/images/'.$menu_image;
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssssss', 
									$menu_name, 
									$short_title,
									$category_ID, 
									$upload_image,
									$description,
									$date_post,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					// updating all data except image file
					$sql_query = "UPDATE tbl_menu 
							SET Menu_name = ? , Short_title = ?,  Category_ID = ?, Description = ?, Date_News = ? 
							WHERE Menu_ID = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('ssssss', 
									$menu_name, 
									$short_title,
									$category_ID,  
									$description,
									$date_post,
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
					$error['update_data'] = "*Menu has been successfully updated.";
				}else{
					$error['update_data'] = "*Failed updating menu.";
				}
			}
			
		}
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_menu WHERE Menu_ID = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['Menu_ID'], 
					$data['Menu_name'], 
					$data['Short_title'],
					$data['Category_ID'],  
					$data['Menu_image'],
					$data['Description'],
					$data['Date_News']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
			
	?>
	<form id="form_validation" method="post" enctype="multipart/form-data">
    <div class="col-md-12">
      	<div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="menu_name" id="menu_name" value="<?php echo $data['Menu_name'];?>" required>
                <label class="form-label">Name</label>
            </div>
        </div>
        <div class="form-group form-float">
            <div class="form-line">
                <input type="text" class="form-control" name="short_title" id="short_title" value="<?php echo $data['Short_title'];?>" required>
                <label class="form-label">Short Name</label>
            </div>
        </div>
    </div>
        <div class="col-md-12">
        	<h4>Your Article</h4>
            <textarea name="description" id="description" class="form-control" rows="16"><?php echo $data['Description']; ?></textarea>
            <script type="text/javascript" src="css/js/editors.js"></script>
            <script src="plugins/ckeditor/ckeditor.js"></script>
            <script type="text/javascript">                        
                      CKEDITOR.replace( 'description' );
            </script>
            <p><?php echo isset($error['description']) ? $error['description'] : '';?></p>
        </div>

        <div class="col-md-5">
        	<h4>Choose News Category</h4>
                <select name="category_ID" class="form-control">
					<?php while($stmt_category->fetch()){ 
						if($category_data['Category_ID'] == $data['Category_ID']){?>
							<option value="<?php echo $category_data['Category_ID']; ?>" selected="<?php echo $data['Category_ID']; ?>" ><?php echo $category_data['Category_name']; ?></option>
						<?php }else{ ?>
							<option value="<?php echo $category_data['Category_ID']; ?>" ><?php echo $category_data['Category_name']; ?></option>
					<?php }} ?>
				</select>
            <p><?php echo isset($error['category_ID']) ? $error['category_ID'] : '';?></p>
	        <div>
	        	<br>
	        	<br>
	        	<h4>Choose Action</h4>
	          <div class="panel-heading">Action</div>
	            <div class="panel-body">
	              <input type="submit" class="btn-primary btn" value="Edit" name="btnEdit" />&nbsp;
	              <a href="news.php"><button class="btn btn-danger">Cancel</button></a>
	            </div>
	        </div>
        </div>
        <div>
        	<div class="col-md-7">
        		<h4>Add Image Before Your Publish</h4>
        		<input name="menu_image" type='file' onchange="readURL(this);" />
        		<img src="<?php include "component/image_public.php";?><?php echo $data['Menu_image']; ?>" width="100%" height="300"/>
    			<img id="blah"/>
    			<p><?php echo isset($error['menu_image']) ? $error['menu_image'] : '';?></p>
        	</div>
        </div>
        <p><?php echo isset($error['add_menu']) ? $error['add_menu'] : '';?></p>
      </div>
    </form> 
	<div class="separator"> </div>
</div>