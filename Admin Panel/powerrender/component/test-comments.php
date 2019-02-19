<?php  
 	include_once('../includes/variables.php');  
 $sql = "SELECT * FROM tbl_comment_users inner join tbl_app_users on tbl_comment_users.User_id=tbl_app_users.User_id ORDER BY id DESC";  
 $result = mysqli_query($connect, $sql);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Webslesson Tutorial | Fetch Data from Two or more Table Join using PHP and MySql</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br />  
           <div class="container" style="width:500px;">  
                <h3 align="">Fetch Data from Two or more Table Join using PHP and MySql</h3><br />                 
                <div class="table-responsive">  
                     <table class="table table-striped">  
                          <tr>  
                               <th>Comment</th>  
                               <th>Name</th>
                               <th>Image</th>  
                          </tr>  
                          <?php  
                          if(mysqli_num_rows($result) > 0)  
                          {  
                               while($row = mysqli_fetch_array($result))  
                               {  
                          ?>  
                          <tr>  
                               <td><?php echo $row["comment"];?></td>  
                               <td><?php echo $row["name"]; ?></td> 
                               <td><img src="http://localhost/powerrender-news/api/users/images_users/<?php echo $row['profile_image'];?>" style="width: 40px; height: 40px;"></td> 
                          </tr>  
                          <?php  
                               }  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
           <br />  
      </body>  
 </html>  