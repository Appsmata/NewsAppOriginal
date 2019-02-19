<?php
	include_once('variables.php'); 
	include_once('functions.php');
	error_reporting(0); 
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
			$sql_query = "SELECT * FROM tbl_fcm_info ORDER BY id DESC";
		}else{
			$sql_query = "SELECT * FROM tbl_fcm_info AND LIKE ? ORDER BY id DESC";
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
			$stmt->bind_result($data['id'], 
					$data['fcm_token']
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
			$sql_query = "SELECT * FROM tbl_fcm_info ORDER BY id DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT * FROM tbl_fcm_info AND LIKE ? ORDER BY id DESC LIMIT ?, ?";
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
			
			$stmt_paging->bind_result($data['id'], 
					$data['fcm_token']
					);
			
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		$total_records_paging;
		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	<h1>Not yet Token Registration</h1>
	<hr />
	
	<?php 
		// otherwise, show data
		}else{
			$row_number = $from + 1;
	?>
	<table class="table table-striped">
		<thead>
            <tr>
                <th>Token Registration</th>
            </tr>
        </thead>
        <tbody>
	<?php 
		// get all data using while loop
		while ($stmt_paging->fetch()){ ?>
		<tr scope="row">
			<td><?php echo $data['fcm_token'];?></td>
		</tr>
	<?php } }?>
		</tbody>
	</table>
	<div>		
	<?php 
		$function->doPages($offset, 'push-token.php', '', $total_records, $keyword);
	?>
	</div>
</div> 			