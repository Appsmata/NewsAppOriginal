<?php 
	include_once('../includes/variables.php');
	
	if(isset($_GET['accesskey']) && isset($_GET['menu_id'])) {
		$key = $_GET['accesskey'];
		$menu_ID = $_GET['menu_id'];
		
		if($key == $access_key){
			// get menu data from menu table
			$sql_query = "SELECT Menu_ID, Menu_name, Short_title, Menu_image, Description, Date_News 
				FROM tbl_menu 
				WHERE Menu_ID = ".$menu_ID;
				
			$result = $connect->query($sql_query) or die ("Error :".mysql_error());
	 
			$news = array();
			while($menu = $result->fetch_assoc()) {
				$news = $menu;
			}
		 
			// create json output
			$output = json_encode(array('data' => $news));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey and menu id are required.');
	}
 
	//Output the output.
	echo $output;
?>