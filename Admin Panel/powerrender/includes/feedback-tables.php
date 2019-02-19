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
			$sql_query = "SELECT *
					FROM tbl_feedback
					ORDER BY id DESC";
		}else{
			$sql_query = "SELECT *
					FROM tbl_feedback
					WHERE full_name LIKE ? 
					ORDER BY id DESC";
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
					$data['full_name'],
					$data['gender'],
					$data['email'],
					$data['phone'],
					$data['date_post'],
					$data['city'],
					$data['country'],
					$data['txt_feed']
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
			$sql_query = "SELECT *
					FROM tbl_feedback
					ORDER BY id DESC LIMIT ?, ?";
		}else{
			$sql_query = "SELECT *
					FROM tbl_feedback
					WHERE full_name LIKE ? 
					ORDER BY id DESC LIMIT ?, ?";
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
					$data['full_name'],
					$data['gender'],
					$data['email'],
					$data['phone'],
					$data['date_post'],
					$data['city'],
					$data['country'],
					$data['txt_feed']
					);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if($total_records_paging == 0){
	
	?>
	<h1>No yet Feedback</h1>
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
                <th style="width: 10%;">FULL NAME</th>
                <th style="width: 10%;">GENDER</th>
                <th style="width: 10%;">EMAIL</th>
                <th style="width: 10%;">PHONE</th>
                <th style="width: 10%;">DATE POST</th>
                <th style="width: 10%;">CITY</th>
                <th style="width: 10%;">COUNTRY</th>
                <th style="width: 12%;">ACTION</th>
            </tr>
        </thead>
        <tbody>
	<?php while ($stmt_paging->fetch()){ ?>
		<tr scope="row">
			<td><?php echo $data['id'];?></td>
			<td><?php echo $data['full_name'];?></td>
			<td><?php echo $data['gender'];?></td>
			<td><?php echo $data['email'];?></td>
			<td><?php echo $data['phone'];?></td>
			<td><?php echo $data['date_post'];?></td>
			<td><?php echo $data['city'];?></td>
			<td><?php echo $data['country'];?></td>
			<td><a href="feedback-detail.php?id=<?php echo $data['id'];?>"><button class="btn btn-info">Detail</button></a>
			 <a href="feedback-delete.php?id=<?php echo $data['id'];?>"><button class="btn btn-danger">Delete</button></a></td>
		</tr>
	<?php } }?>
	</table>
	<div>
	<?php $function->doPages($offset, 'category-view.php', '', $total_records, $keyword);?>			
	</div>
</div>				
