<nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="dashboard.php">ANDROID NEWS APP</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="upload/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <?php 
                        include "includes/variables.php";
                        $query = "SELECT Username, Email FROM tbl_user";
                        $sql = mysqli_query($connect, $query);
                        $display = mysqli_fetch_assoc($sql);
                        $username = $display['Username'];
                        $email = $display['Email'];
                        ?><div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $username;?></div><?php
                        ?><div class="email"><?php echo $email ;?></div><?php
                    ;?>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="profile.php"><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="includes/logout.php"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Menu -->
            <?php include "component/menu.php";?>
            <!-- #Footer -->
        </aside>
    </section>