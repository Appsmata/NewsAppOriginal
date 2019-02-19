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
                    QUICK ACCESS
                </h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">category</i>
                        </div>
                        <div class="content">
                            <?php require_once "includes/variables.php";
                                $query = "SELECT count(*) FROM tbl_category";
                                $sql = mysqli_query($connect, $query);
                                $show = mysqli_fetch_row($sql);
                                ?>
                                <div class="text">Total Category</div>
                                <div class="number"><?php echo $show[0];?></div>
                                <?php
                            ?>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-blue hover-zoom-effect">
                        <div class="icon">
                            <i class="material-icons">web</i>
                        </div>
                        <div class="content">

                            <?php 
                                $query = "SELECT count(*) FROM tbl_menu";
                                $sql = mysqli_query($connect, $query);
                                $show = mysqli_fetch_row($sql);
                                ?>
                                <div class="text">Total News</div>
                                <div class="number"><?php echo $show[0];?></div>
                                <?php
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-blue hover-zoom-effect">
                        <div class="icon">
                            <a href="feedback.php" target="_blank"><i class="material-icons">video_library</i></a>
                        </div>
                        <div class="content">
                            <?php 
                                $query = "SELECT count(*) FROM tbl_feedback";
                                $sql = mysqli_query($connect, $query);
                                $show = mysqli_fetch_row($sql);
                            ?>
                            <div class="text">Total Feedback</div>
                            <div class="number"><?php echo $show[0]; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-zoom-effect">
                        <div class="icon">
                            <a href="comments-view.php"><i class="material-icons">comments</i></a>
                        </div>
                        <div class="content">
                            <?php 
                                $query = "SELECT count(*) FROM tbl_comment_users";
                                $sql = mysqli_query($connect, $query);
                                $show = mysqli_fetch_row($sql);
                            ?>
                            <div class="text">Total Comments</div>
                            <div class="number"><?php echo $show[0];?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "component/news_post_home.php"; ?>
            <?php include "component/category_post_home.php"; ?>
        </div>
    </section> 
    <?php include "component/footer.php";?>
</body>
</html>
