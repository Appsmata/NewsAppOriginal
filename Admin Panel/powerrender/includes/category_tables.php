<?php
	include_once('variables.php'); 
	include_once('functions.php'); 
?>

<div id="content">
	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])){	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		}else{
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
		if(empty($keyword)){
			$sql_query = "SELECT Category_ID, Category_name, Category_image
					FROM tbl_category
					ORDER BY Category_ID DESC";
		}else{
			$sql_query = "SELECT Category_ID, Category_name, Category_image
					FROM tbl_category
					WHERE Category_name LIKE ? 
					ORDER BY Category_ID DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if(!empty($keyword)){
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($data['Category_ID'], 
					$data['Category_name'],
					$data['Category_image']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page){
			$from 	= ($page * $offset) - $offset;
		}else{
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if(empty($keyword)){
			$sql_query = "SELECT Category_ID, Category_name, Category_image
					FROM tbl_category
					ORDER BY Category_ID DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT Category_ID, Category_name, Category_image
					FROM tbl_category
					WHERE Category_name LIKE ? 
					ORDER BY Category_ID DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if(empty($keyword)){
				$stmt_paging ->bind_param('ss', $from, $offset);
			}else{
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result($data['Category_ID'], 
					$data['Category_name'],
					$data['Category_image']
					);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	<h1>No yet category</h1>
	<hr />
	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>
	<table class="table table-striped">
		<thead>
            <tr>
                <th style="width: 4%;">ID</th>
                <th style="width: 20%;">NEWS NAME</th>
                <th style="width: 10%;">NEWS IMAGE</th>
                <th style="width: 10%;">NEWS ACTION</th>
            </tr>
        </thead>
        <tbody>
	<?php while ($stmt_paging->fetch()){ ?>
		<tr scope="row">
			<td><?php echo $data['Category_ID'];?></td>
			<td><?php echo $data['Category_name'];?></td>
			<td><img src="<?php include "component/image_public.php";?><?php echo $data['Category_image']; ?>" class="img-responsive"/></td>
			<td><a href="category-edit.php?id=<?php echo $data['Category_ID'];?>"><button class="btn btn-warning">Edit</button></a>
			 <a href="category-delete.php?id=<?php echo $data['Category_ID'];?>"><button class="btn btn-danger">Delete</button></a></td>
		</tr>
	<?php } }?>
	</table>
	<div>
	<?php $function->doPages($offset, 'category-view.php', '', $total_records, $keyword);?>			
	</div>
</div>				
