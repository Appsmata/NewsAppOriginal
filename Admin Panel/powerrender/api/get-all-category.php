<?php
	include_once('../includes/variables.php');
	
	if(isset($_GET['accesskey'])) {
		$key = $_GET['accesskey'];
		
		if($key == $access_key){
			// get all category data from category table
			$sql_query = "SELECT * 
					FROM tbl_category 
					ORDER BY Category_ID DESC";
			
			$result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
			$categories = array();
			while($category = $result->fetch_assoc()) {
				$categories[] = $category;
			}
			
			// create json output
			$output = json_encode(array('data'=> $categories));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey is required.');
	}
 
	//Output the output.
	echo $output;
?>