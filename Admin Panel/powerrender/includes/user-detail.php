<?php
	include_once('variables.php'); 
	include_once('functions.php'); 
?>

<div id="content">

	<?php  
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		}else{
			$page = 1;
		}

		if ($page == '' || $page == 1) {
			$pages = 0;
		}else{
			$pages = ($page*10)-10;
		}

		 $sql = "SELECT * FROM tbl_app_users LIMIT ".$pages.", 10";  
		 $result = mysqli_query($connect, $sql);  
 	?>  

	<table class="table table-striped">
		<thead>
            <tr>
            	<th style="width: 20%;">IMAGE</th>
                <th style="width: 30%;">NAME</th>
                <th style="width: 50%;">CREATED</th>
            </tr>
            <?php  
                if(mysqli_num_rows($result) > 0)  
                    {  
                    	while($row = mysqli_fetch_array($result))  
                    {  
                ?>  
        </thead>
        <tbody>
		<tr scope="row">
			<td><img src="<?php include 'component/image_public.php';?>api/users/images_users/<?php echo $row['profile_image'];?>" style="width: 60px; height: 60px;"></td> 
			<td><h4><?php echo $row["name"]; ?></h4></td> 
			<td><?php echo $row["created_at"];?></td>  
		</tr>
		<?php  
            }  
        }

        $sql = "SELECT * FROM tbl_app_users";
        $query = mysqli_query($connect, $sql);
        $record = mysqli_num_rows($query);
        $pages_record = $record/10;
        $pages_record = ceil($pages_record);

        $prev = $page-1;
        $next = $page+1;

        echo '<ul class="pagination">';

        if ($prev >= 1) {
        	echo '<li><a href="?page='.$prev.'">Previous</al></li>';
        }

        if ($pages_record >= 2) {
        	for ($i=1; $i < $pages_record; $i++) { 
        		$active = $i == $page ? 'class="active"' : '';
        		echo '<li><a href="?page='.$i.'" '.$active.'>'.$i.'</a></li>';
        	}
        }

        if ($next <= $pages_record && $pages_record >= 2) {
        	echo '<li><a href="?page='.$next.'">Next</al></li>';
        }

        echo '</ul>';

        ?>  
	</table>
	<div>
</div>				
