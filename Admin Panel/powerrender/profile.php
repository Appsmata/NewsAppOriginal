<?php include "component/header.php";?>

<body class="theme-red ls-opened">
    <?php include "component/search.php";?>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <?php include "component/navbar.php";?>
    <section class="content">
        <div class="container-fluid">
            <!-- Counter Examples -->
            <div class="block-header">
                <h2>
                    ADMIN PROFILE
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <label for="email_address">This is your profile</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <?php 
                                        include "includes/variables.php";
                                        $query = "SELECT * FROM tbl_user";
                                        $sql = mysqli_query($connect, $query);
                                        $row = mysqli_fetch_assoc($sql);
                                        $username = $row['Username'];
                                        $email = $row['Email'];
                                        ?>
                                            Username: <input type="text" class="form-control" placeholder="<?php echo $username;?>" disabled>
                                            Email: <input type="text" class="form-control" placeholder="<?php echo $email;?>" disabled>
                                        <?php
                                    ?>
                                </div>
                                <br>
                                <a href="profile-update.php"><button class="btn btn-info">CLICK HERE FOR UPDATE</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "component/footer.php";?>
</body>
</html>
