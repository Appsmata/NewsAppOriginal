<?php
	include_once('../includes/variables.php');
	include_once('function_date.php');
	
	if(isset($_GET['accesskey'])) {
		$key = $_GET['accesskey'];
		
		if($key == $access_key){
			// get all category data from category table
			$sql_query = "SELECT * 
					FROM tbl_menu 
					ORDER BY Menu_ID DESC LIMIT 5";
			
			// $result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
			// $categories = array();
			// while($category = $result->fetch_assoc()) {
			// 	$categories[] = $category;
			// }
			
			// // create json output
			// $output = json_encode(array('data' => $categories));

			$result = mysqli_query($connect, $sql_query);
			$res = array(); 
		    	while($category = mysqli_fetch_array($result)){
		        	array_push($res, array(
		        		'Menu_ID'=>$category['Menu_ID'],
	                    'Menu_name'=>$category['Menu_name'],
	                    'Short_title'=>$category['Short_title'],
	                    'Menu_image'=>$category['Menu_image'],
	                    'Description'=>$category['Description'],
	                	'Date_News'=> DatePost($category['Date_News']))
		            );
		    	}
		    $output = json_encode(array('data'=>$res));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey is required.');
	}
 
	//Output the output.
	echo $output;
?>