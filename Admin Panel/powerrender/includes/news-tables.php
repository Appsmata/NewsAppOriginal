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
		
		// get all data from menu table and category table
		if(empty($keyword)){
			$sql_query = "SELECT Menu_ID, Menu_name, Category_name, Menu_image
					FROM tbl_menu m, tbl_category c
					WHERE m.Category_ID = c.Category_ID  
					ORDER BY m.Category_ID DESC";
		}else{
			$sql_query = "SELECT Menu_ID, Menu_name, Category_name, Menu_image
					FROM tbl_menu m, tbl_category c
					WHERE m.Category_ID = c.Category_ID AND Menu_name LIKE ? 
					ORDER BY m.Category_ID DESC";
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
			$stmt->bind_result($data['Menu_ID'], 
					$data['Menu_name'], 
					$data['Category_name'],
					$data['Menu_image']
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
		
		// get all data from reservation table
		if(empty($keyword)){
			$sql_query = "SELECT Menu_ID, Menu_name, Category_name, Menu_image
					FROM tbl_menu m, tbl_category c
					WHERE m.Category_ID = c.Category_ID  
					ORDER BY m.Category_ID DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT Menu_ID, Menu_name, Category_name, Menu_image
					FROM tbl_menu m, tbl_category c
					WHERE m.Category_ID = c.Category_ID AND Menu_name LIKE ? 
					ORDER BY m.Category_ID DESC LIMIT ?, ?";
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
			
			$stmt_paging->bind_result($data['Menu_ID'], 
					$data['Menu_name'], 
					$data['Category_name'],
					$data['Menu_image']
					);
			
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		$total_records_paging;
		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	<h1>Not yet News</h1>
	<hr />
	
	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>
	<table class="table table-striped">
		<thead>
            <tr>
                <th style="width: 1%;">ID</th>
                <th style="width: 8%;">NEWS NAME</th>
                <th style="width: 8%;">NEWS CATEGORY</th>
                <th style="width: 10%;">NEWS IMAGE</th>
                <th style="width: 10%;">NEWS ACTION</th>
            </tr>
        </thead>
        <tbody>
	<?php 
		// get all data using while loop
		while ($stmt_paging->fetch()){ ?>
		<tr scope="row">
			<td><?php echo $data['Menu_ID'];?></td>
			<td><?php echo $data['Menu_name'];?></td>
			<td><?php echo $data['Category_name'];?></td>
			<td><img src="<?php include "component/image_public.php";?><?php echo $data['Menu_image']; ?>" class="img-responsive"/></td>

			<td><a href="news-detail.php?id=<?php echo $data['Menu_ID'];?>"><button class="btn btn-primary">View Detail</button></a>
			 <a href="news-edit.php?id=<?php echo $data['Menu_ID'];?>"><button class="btn btn-warning">Edit</button></a>
			 <a href="news-delete.php?id=<?php echo $data['Menu_ID'];?>"><button class="btn btn-danger">Delete</button></a>
			 <a href="push.php?id=<?php echo $data['Menu_ID'];?>"><button class="btn btn-danger">Push</button></a></td>
		</tr>
	<?php } }?>
		</tbody>
	</table>
	<div>		
	<?php 
		$function->doPages($offset, 'news-view.php', '', $total_records, $keyword);
	?>
	</div>
</div> 			