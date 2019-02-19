<?php
	include_once('../includes/variables.php');
	include_once('function_date.php');
	
	if(isset($_GET['accesskey']) && isset($_GET['category_id'])) {
		$key = $_GET['accesskey'];
		$category_ID = $_GET['category_id'];
		
		if(isset($_GET['keyword'])){
			$keyword = $_GET['keyword'];
		}else{
			$keyword = "";
		}
		
		if($key == $access_key){
			if($keyword == ""){
				// find menu by category id in menu table
				$sql_query = "SELECT Menu_ID, Menu_name, Short_title, Menu_image, Date_News 
					FROM tbl_menu 
					WHERE Category_ID = ".$category_ID." 
					ORDER BY Menu_ID DESC";
			}else{
				// find menu by category id and keyword in menu table
				$sql_query = "SELECT Menu_ID, Menu_name, Short_title, Menu_image
					FROM tbl_menu 
					WHERE Menu_name LIKE '%".$keyword."%' AND Category_ID = ".$category_ID." 
					ORDER BY Menu_ID DESC";
			}
			
			//$result = $connect->query($sql_query) or die("Error : ".mysql_error());
			
			// $news = array();
			// while($menu = $result->fetch_assoc()) {
			// 	$news[] = $menu;
			// }
			
			// // create json output
			// $output = json_encode(array('data' => $news));
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
		die('accesskey and category id are required.');
	}
 
	//Output the output.
	echo $output;
?>