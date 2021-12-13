<?php
    include_once '../api/config/core.php';
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
                        <a class="nav-link dropdown-toggle" href="#" id='account_menu' role='button' data-bs-toggle='dropdown' aria-expanded="false"><span class="bx bxs-user"></span> Account</a>                    
                        <ul class="dropdown-menu" aria-labelledby="account_menu">
                            <li><a class="dropdown-item" href="#" id="update_account"><span class="bx bxs-id-card"></span> Account</a></li>
                            <li><a class="dropdown-item" href="#" id="mfa"><span class="bx bxs-key"></span> MFA</a></li>
                            <div class="dropdown dropstart">
                                <a class="dropdown-item dropdown-toggle" href="#" id="themes_menu" role='button' data-bs-toggle='dropdown' aria-expanded="false"><span class="bx bxs-palette"></span> Themes</a> 
                                <ul class="dropdown-menu" aria-labelledby="themes_menu">
                                    <li><a href="#" data-theme="default" class="dropdown-item theme-link">Default</a></li>
                                    <li><a href="#" data-theme="flatly" class="dropdown-item theme-link">Flatly</a></li>
                                    <li><a href="#" data-theme="minco" class="dropdown-item theme-link">Minco</a></li>
                                    <li><a href="#" data-theme="lymcha" class="dropdown-item theme-link">Lymcha</a></li>
                                    <li><a href="#" data-theme="superhero" class="dropdown-item theme-link">Superhero</a></li>
                                    <li><a href="#" data-theme="hollar" class="dropdown-item theme-link">Hollar</a></li>
                                    <li><a href="#" data-theme="sunset" class="dropdown-item theme-link">Sunset</a></li>
                                    <li><a href="#" data-theme="preptor" class="dropdown-item theme-link">Preptor</a></li>
                                    <li><a href="#" data-theme="skeeblue" class="dropdown-item theme-link">Skeeblue</a></li>
                                    <li><a href="#" data-theme="wandoo" class="dropdown-item theme-link">Wandoo</a></li>
                                </ul>
                            </div>
                            <div class="dropdown dropstart">
                                <a class="dropdown-item dropdown-toggle" href="#" id="admin_menu" role='button' data-bs-toggle='dropdown' aria-expanded="false"><span class="bx bxs-wrench"></span> Admin</a> 
                                <ul class="dropdown-menu" aria-labelledby="admin_menu">
                                    <li><a href="adm_users.php" class="dropdown-item">Users</a></li>
                                    <li><a href="adm_groups.php" class="dropdown-item">Groups</a></li>
                                </ul>
                            </div>
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
                        
                <!-- where main content will appear -->
                <div class="display-4" id="headline"></div>
                <div id="content">

                
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
    <script src="../assets/js/adm_groups.js"></script>

</body>
</html>