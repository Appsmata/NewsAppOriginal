<?php include "component/header.php";?>
<body class="theme-red">
    <?php include "component/search.php";?>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <?php include "component/navbar.php";?>
    <!-- #Top Bar -->
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Feedback Users</h2>
            </div>
            <!-- #END# Basic Table -->
            <!-- Striped Rows -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body table-responsive">
                            <?php include "includes/feedback-tables.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include "component/footer.php";?>
</body>

</html>