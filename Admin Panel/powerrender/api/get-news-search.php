<?php
	include_once('../includes/variables.php');
    
    if(isset($_GET['keyword'])){
        $keyword = $_GET['keyword'];
        $sql_query = "SELECT Menu_ID, Menu_name, Short_title, Menu_image FROM tbl_menu WHERE Menu_name LIKE '%".$keyword."%'";
        $result = $connect->query($sql_query) or die ("Error :".mysql_error());
			$search_news = array();
            while($category = mysqli_fetch_assoc($result)){
                array_push($search_news,
                          array('Menu_ID'=>$category['Menu_ID'],
                               'Menu_name'=>$category['Menu_name'],
                               'Short_title'=>$category['Short_title'],
                               'Menu_image'=>$category['Menu_image'])
                          );
            }
        echo json_encode(array('data'=>$search_news));
    }else{
        $sql_query = "SELECT * FROM tbl_menu";
        $result = $connect->query($sql_query) or die ("Error :".mysql_error());
			$search_news = array();
            while($category = mysqli_fetch_assoc($result)){
                array_push($search_news,
                          array('Menu_ID'=>$category['Menu_ID'],
                               'Menu_name'=>$category['Menu_name'],
                               'Short_title'=>$category['Short_title'],
                               'Menu_image'=>$category['Menu_image'])
                          );
            }
        echo json_encode(array('data'=>$search_news));
    }

?>