<?php include "component/header.php";?>

<body class="theme-red ls-opened">
    <?php include "component/search.php";?>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <?php include "component/navbar.php";?>
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Send Notification</h2>
            </div>
            <!-- #END# Basic Table -->
            <!-- Striped Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body table-responsive">
                            <form method="post" action="">
                                <?php 
                                    include "includes/variables.php";
                                    if(isset($_GET['id'])){
                                        $ID = $_GET['id'];
                                    }else{
                                        $ID = "";
                                    }
                                    
                                    // create array variable to store data from database
                                    $data = array();
                                
                                    
                                    // get all data from menu table and category table
                                    $sql_query = "SELECT Menu_ID, Menu_name, Menu_image, Date_News 
                                            FROM tbl_menu m, tbl_category c
                                            WHERE m.Menu_ID = ? AND m.Category_ID = c.Category_ID";
                                    
                                    $stmt = $connect->stmt_init();
                                    if($stmt->prepare($sql_query)) {    
                                        // Bind your variables to replace the ?s
                                        $stmt->bind_param('s', $ID);
                                        // Execute query
                                        $stmt->execute();
                                        // store result 
                                        $stmt->store_result();
                                        $stmt->bind_result($data['Menu_ID'], 
                                                $data['Menu_name'],
                                                $data['Menu_image'],
                                                $data['Date_News']
                                                );
                                        $stmt->fetch();
                                        $stmt->close();
                                    }
                                    
                                ?>
                                <div class="col-md-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $data['Menu_name']?>">
                                            <label class="form-label">Notification Title</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="photos" id="photos" value="<?php include "component/image_public.php";?><?php echo $data['Menu_image']; ?>">
                                            <label class="form-label">Notification News Image</label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                include "includes/variables.php";
                                function send_notification ($tokens, $message)
                                {
                                    $url = 'https://fcm.googleapis.com/fcm/send';
                                    $fields = array('registration_ids' => $tokens,'data' => $message);
                                    $headers = array('Authorization:key = AAAA3wwIYD0:APA91bGf86jLEJ0I9nwL-ZgrTJxswnZCmjguP0QLpFRhqtrO-DinUtoVkYWP4bGmt2yt-NIiHj95swnyV-W8TenGr9t2ks8f5pGtS71lv0EWsY8ebNG3H11_qjD1PqzNLDf9rTgxFLsUgp-y6siPQGWcSAJRHrxl6w','Content-Type: application/json');

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                                    $result = curl_exec($ch);   

                                    if ($result === FALSE) {
                                        die('Curl failed: ' . curl_error($ch));
                                    }

                                    curl_close($ch);
                                    return $result;
                                }

                                $sql = " Select id, fcm_token From tbl_fcm_info";
                                $result = mysqli_query($connect,$sql);
                                $tokens = array();
                                if(mysqli_num_rows($result) > 0 ){
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    $tokens[] = $row["fcm_token"];
                                    }
                                }

                                if (isset($_POST['submit'])) {
                                    if (!empty($_POST['title']) && !empty($_POST['photos'])) {
                                        $title = $_POST['title'];
                                        $photos = $_POST['photos'];
                                        mysqli_close($connect);
                                        $message = array("title" => $title, "photos"=> $photos);
                                        $message_status = send_notification($tokens, $message);
                                    }
                                }                                    
                                ?> 
                                <div class="col-md-12">
                                    <input type="submit" name="submit" value="Send Notification" class="btn btn-primary">
                                    <?php 
                                    if (isset($_POST['submit'])) {
                                        if (!empty($_POST['title']) && !empty($_POST['message'])) {
                                            echo("<br><br>");
                                            echo("Success");
                                        }else{
                                            echo("<br><br>");
                                            echo("Success");
                                        }   
                                    }
                                    ?> 
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "component/footer.php";?>
</body>
</html>
