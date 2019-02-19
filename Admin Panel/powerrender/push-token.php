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
                    Token Registration
                </h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body table-responsive">
                            <?php include "includes/notification_token_tables.php";?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "component/footer.php";?>
</body>
</html>
