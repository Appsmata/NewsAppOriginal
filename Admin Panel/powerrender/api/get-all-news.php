<?php
	include_once('../includes/variables.php');
	include_once('function_date.php');
	// if(isset($_GET['accesskey'])) {
	// 	$key = $_GET['accesskey'];
		
	// 	if($key == $access_key){
	// 		// get all category data from category table
	// 		$sql_query = "SELECT * 
	// 				FROM tbl_menu 
	// 				ORDER BY Menu_ID DESC";
			
	// 		$result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
	// 		$categories = array();
	// 		while($category = $result->fetch_assoc()) {
	// 			$categories[] = $category;
	// 		}
			
	// 		// create json output
	// 		$output = json_encode(array('data' => $categories));
	// 	}else{
	// 		die('accesskey is incorrect.');
	// 	}
	// } else {
	// 	die('accesskey is required.');
	// }
 
	// //Output the output.
	// echo $output;

	error_reporting(0);
	if(isset($_GET['accesskey']) && isset($_GET['page']) && isset($_GET['total'])) {
		$key = $_GET['accesskey'];
		$page = $_GET['page'];
		$total = $_GET['total'];
		$start = 0;
		$limit = 10;
		$total = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM tbl_menu ORDER BY Menu_ID DESC"));
		$page_limit = $total*$limit;
		if($key == $access_key){
			if ($page <= $page_limit) {
				$start = ($page - 1) * $limit; 
		    	$sql = "SELECT * from tbl_menu ORDER BY Menu_ID DESC limit $start, $limit ";
		    	$result = mysqli_query($connect,$sql); 
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
		    	$output = json_encode(array('total'=>$total, 'data'=>$res));
			}else{
				
			}
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey is required.');
	}
 
	//Output the output.
	echo $output;



	// $page = $_GET['page'];  
	// $start = 0; 
	// $limit = 1; 
	// $total = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM tbl_menu ORDER BY Menu_ID DESC"));
	// $page_limit = $total/$limit; 
	// if($page<=$page_limit){
	//     $start = ($page - 1) * $limit; 
	//     $sql = "SELECT * from tbl_menu limit $start, $limit";
	//     $result = mysqli_query($connect,$sql); 
	//     $res = array(); 
	//     while($category = mysqli_fetch_array($result)){
	//         array_push($res, array(
	//         		'Menu_ID'=>$category['Menu_ID'],
 //                    'Menu_name'=>$category['Menu_name'],
 //                    'Short_title'=>$category['Short_title'],
 //                    'Menu_image'=>$category['Menu_image'])
	//             );
	//     }
	//     echo json_encode($res);
	// }else{
	//         echo "over";
	// }
?>
