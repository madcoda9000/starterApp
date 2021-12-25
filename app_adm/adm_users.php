<?php
    include_once '../api/config/core.php';
    include_once '../api/vendor/autoload.php';

    // create orm instance
    ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
    ORM::configure('username', $DB_user);
    ORM::configure('password', $DB_pass);
    ORM::configure('return_result_sets', true);

    $usersCount = ORM::for_table('users')->count();
    $limit = 5;  
    $total_pages = ceil($usersCount / $limit); 
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 
        <title>
            <?php echo $APP_title . " " . $APP_title_description; ?>
        </title>
 
        <!-- Bootstrap 4 CSS, fontawesome css and custom CSS -->
        <link rel="stylesheet" href="../assets/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../assets/css/custom.css" />
        <link rel="stylesheet" id="btTheme" href="../assets/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="../assets/boxicons/css/boxicons.min.css" />
        <link rel="stylesheet" type="text/css" href="../assets/js/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css" />
    </head>
<body>
 
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand  ms-2" href="#">
                <?php echo $APP_title . " <small class=\"text-muted\">&nbsp;&nbsp;&nbsp;" . $APP_title_description . "</small>"; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0" >
                    <li>
                        <a class="nav-link" href="#" id='home'><span class="bx bxs-home"></span> Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id='admin_menu' role='button' data-bs-toggle='dropdown' aria-expanded="false"><span class="bx bxs-wrench"></span> Admin</a>
                        <ul class="dropdown-menu" aria-labelledby="admin_menu">
                            <li><a href="adm_users.php" class="dropdown-item">Users</a></li>
                            <li><a href="adm_groups.php" class="dropdown-item">Groups</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link" href="#" id='login'>Login</a>
                    </li>
                    <li>
                        <a class="nav-link" href="#" id="sign_up">
                            <?php
                                if($APP_allow_signup == True) {
                                   echo '<span class=\'bx bxs-user\'></span> Sign Up';
                                }
                            ?>
                        </a>    
                    </li>   
                    <li>
                    <a class="nav-link" href="#" id="logout"><span class="bx bxs-log-out-circle"></span> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navbar -->
                                <br><br>
    <!-- container -->
    <main class="container-flex mx-5 my-5 shadow bg-body rounded border bg-secondary"> 
        <div class="row">
            <div class="col mx-3  my-3">                      
                <!-- where prompt / messages will appear -->
                <div id="response"></div>
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row align-items-center">                    
                            <div class="col-3">                    
                                <h2>Manage <b>users</b></h2>						
                            </div>
                        </div>                
                    </div>

            <div class="row align-items-left">
                <div class="col-md-4">
                    <div class="input-group ">
                        <input type="text" class="form-control" id="txtSearch" placeholder="search..." aria-label="search">
                        <button class="btn btn-secondary" type="button" id="btnSearch"><i class='bx bx-search-alt-2'></i></button>
                    </div> 
                </div>  
                <br />&nbsp;
            </div>
            
			<div id="target-content">
                <div class="col-16 text-center">
                    <br><br><br>Loding data<br><img src="../assets/images/progress.gif" alt="loading..." width="200" height="150" /><br><br><br><br>
                </div>
            </div>
            
			<div class="clearfix">
               
					<ul class="pagination">
                    <?php 
					if(!empty($total_pages)){
						for($i=1; $i<=$total_pages; $i++){
								if($i == 1){
									?>
								<li class="pageitem active" id="<?php echo $i;?>"><a href="#" data-id="<?php echo $i;?>" class="page-link" ><?php echo $i;?></a></li>
															
								<?php 
								}
								else{
									?>
								<li class="pageitem" id="<?php echo $i;?>"><a href="#" class="page-link" data-id="<?php echo $i;?>"><?php echo $i;?></a></li>
								<?php
								}
						}
					}
								?>
					</ul>
               </ul>
            </div>
        </div>
                
                </div>  
            </div>
        </div>
    </main>
    <!-- /container -->
 
    <!-- jQuery & Bootstrap 4 JavaScript libraries -->
    <script src="../assets/js/jquery-3.6.0.min/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/popperjs-1.12.min/popperjs-1.12.min.js"></script>
    <script src="../assets/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js"></script>
    <!-- custom javascript functions for index.php -->
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/adm_users.js"></script>

</body>
</html>