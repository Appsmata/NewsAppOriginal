<div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="block-header">
                        <h2>
                            CATEGORY POSTS
                        </h2>
                    </div>
                        <div class="card">
                            <div class="header">
                                <ul class="header-dropdown m-r--5">
                                    <li class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a href="category-view.php">View All</a></li>
                                            <li><a href="category-add.php">Add Category</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="body">
                                <div class="row">
                                        <?php require_once "includes/variables.php";
                                            $query = "SELECT * FROM tbl_category ORDER BY Category_ID DESC LIMIT 4 ";
                                            $sql = mysqli_query($connect, $query);
                                            while($row = mysqli_fetch_assoc($sql)){
                                                $Menu_name = $row['Category_name'];
                                                $Menu_image = $row['Category_image'];
                                                ?>
                                                <div class="col-sm-6 col-md-3">
                                                    <div class="thumbnail" style="display: inline-block;">
                                                        <img src="<?php include "image_public.php";?><?php echo $Menu_image ;?>">
                                                            <div class="caption">
                                                                <h3><?php echo $Menu_name; ?></h3>
                                                                <p>
                                                                <a href="category-edit.php?id=<?php echo $row['Category_ID'];?>" class="btn btn-primary waves-effect" role="button">EDIT</a>
                                                                <a href="category-delete.php?id=<?php echo $row['Category_ID'];?>" class="btn btn-danger waves-effect" role="button">DELETE</a>
                                                                </p>
                                                            </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            