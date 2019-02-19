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
                    UPDATE PROFILE
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="form-group">
                            <form action="" method="post">
                                <label for="email_address">Update Username</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="username" class="form-control" placeholder="Update your username">
                                    </div>
                                    <div class="form-line">
                                        <input type="text" name="password" class="form-control" placeholder="Update your password">
                                    </div>
                                    <?php include "includes/variables.php";
                                            if (isset($_POST['update'])) {
                                                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                                                    echo "Username and Password Successfully Updated";
                                                    echo "</br>";
                                                    $username = $_POST['username'];
                                                    $password = $_POST['password'];
                                                    $query = "UPDATE tbl_user SET Username = '$username', Password = '$password' ";
                                                    $sql = mysqli_query($connect, $query);
                                                }else{
                                                    echo "Please insert username and password";
                                                    echo "</br>";
                                                }
                                            }
                                        ?>
                                    <input type="submit" class="btn btn-primary m-t-15 waves-effect" name="update" value="Update">
                                </div>
                            </form>
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
