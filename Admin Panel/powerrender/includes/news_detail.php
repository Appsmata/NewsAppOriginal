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
		$sql_query = "SELECT Menu_ID, Menu_name, Category_name, Menu_image, Description 
				FROM tbl_menu m, tbl_category c
				WHERE m.Menu_ID = ? AND m.Category_ID = c.Category_ID";
		
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
					$data['Category_name'],
					$data['Menu_image'],
					$data['Description']
					);
			$stmt->fetch();
			$stmt->close();
		}
		
	?>
	<form method="post">
      <div class="col-md-12">
      	<div class="col-md-12">
      		<h4>Your Title</h4>
			<td type="text" class="form-control" name="menu_name" id="menu_name"  placeholder="Enter News Title"><?php echo $data['Menu_name'];?></td>
		</div>
        <div class="col-md-12">
        	<h4>Your Article</h4>
            <td class="detail"><?php echo $data['Description']; ?></td>
        </div>

        <div class="col-md-5">
        	<h4>Choose News Category</h4>
                <td class="detail"><?php echo $data['Category_name']; ?></td>
        <br>
        <br>
        <div>
        	<h4>Add Image Before Your Publish</h4>
        		<td class="detail"><img src="<?php include "component/image_public.php";?><?php echo $data['Menu_image']; ?>" width="310" height="200"/></td>
        </div>
        </div>
        
      </div>
    </form> 
				
	<div class="separator"> </div>
</div>