<?php
 //required headers
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Methods: GET");
 header("Access-Control-Max-Age: 3600");
 header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // required to encode json web token
 include_once 'config/core.php';
 include_once 'vendor/autoload.php';
 include_once 'objects/user.php';

 use \Firebase\JWT\JWT;
  // create orm instance
  ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
  ORM::configure('username', $DB_user);
  ORM::configure('password', $DB_pass);
  ORM::configure('return_result_sets', true);

 $jwt=$_GET['jwt'];

 if($jwt){
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $limit = 5;
        $start_from = ($page-1) * $limit;           
        $sql = "SELECT * FROM users ORDER BY id ASC LIMIT $start_from, $limit";
        if($_GET['srchParam']) {
            $sql = "SELECT * FROM users WHERE CONCAT(firstname,lastname,email) LIKE '%". htmlspecialchars(strip_tags($_GET['srchParam'])) ."%' ORDER BY id ASC LIMIT $start_from, $limit";
            $limit = 100;
        }                
        $users = ORM::for_table('app_groups')->raw_query($sql)->order_by_desc('id')->find_many();

        ?> <table class="table table-bordered table-striped">  
        <thead>  
        <tr>  
        <th style="width: 3%">ID</th>  
        <th style="width: 10%">Firstname</th>  
        <th style="width: 10%">Lastname</th>  
        <th style="width: 30%">Email</th> 
        <th style="width: 10%">Group</th> 
        <th style="width: 3%">MFA</th> 
        <th style="width: 3%">Status</th> 
        <th style="width: 15%">Actions</th> 
        </tr>  
        </thead>  
        <tbody>  
        <?php  
        foreach ($users as $user) {  
        ?>  
                    <?php
                        if($user->totp_enabled==0 && $user->appGroup=="admins") {
                            ?>
                            <tr class="invalid" style="background-color:inherit;"> 
                            <?php
                        } else {
                            ?>
                            <tr>
                            <?php
                        }
                    ?>                     
                    <td><?php echo $user->id; ?></td>  
                    <td><?php echo $user->firstname; ?></td> 
                    <td><?php echo $user->lastname; ?></td> 
                    <td><?php echo $user->email; ?></td> 
                    <td><?php echo $user->appGroup; ?></td> 
                    <td>
                        <ul class="list-inline m-0">
                            <li class="list-inline-item">
                                <?php 
                                if($user->totp_enabled == 1) {
                                ?>
                                    <img src="../assets/images/shield_on.png" style="width: 24px; height:24px;" alt="MFA activated" data-bs-toggle="tooltip" data-bs-placement="top" title="MFA is activated" />
                                <?php
                                } elseif ($user->totp_enabled == 0) {
                                ?>
                                    <img src="../assets/images/shield_off.png" style="width: 24px; height:24px;" alt="MFA disabled" data-bs-toggle="tooltip" data-bs-placement="top" title="MFA is deactivated" />
                                <?php
                                }
                                ?> 
                            </li>
                        </ul>                   
                    </td> 
                    <td>
                        <?php
                            if($user->accState==1&&$user->firstname!="Administrator") {
                                ?>                                
                                <li class="list-inline-item">
                                    <button class="btn btn-success btn-sm rounded-2 stateBtnList" type="button" id="btnState<?php echo $user->id; ?>" data-userEnabled="<?php echo $user->accState; ?>" data-btnuID="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="deactivate use"><i class="bx bx-user"></i></button>
                                </li>
                                <?php
                            } elseif($user->accState==0&&$user->firstname!="Administrator") {
                                ?>
                                <li class="list-inline-item ">
                                    <button class="btn btn-dark btn-sm rounded-2 stateBtnList" type="button" id="btnState<?php echo $user->id; ?>" data-userEnabled="<?php echo $user->accState; ?>" data-btnuID="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="activate use"><i class="bx bx-user"></i></button>
                                </li>
                                <?php
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if($user->firstname != 'Administrator') {
                                ?>
                                    <!-- Call to action buttons -->
                                    <ul class="list-inline m-0">
                                                <li class="list-inline-item">
                                                    <button class="btn btn-danger btn-sm rounded-2 delBtnList" type="button" id="btnDel<?php echo $user->id; ?>" data-btnDelid="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="bx bx-message-square-x"></i></button>
                                                </li>
                                                <?php
                                                    if($user->totp_enabled==1) {
                                                        ?>
                                                        <li class="list-inline-item">
                                                            <button class="btn btn-warning btn-sm rounded-2 mfaBtnList" type="button" id="btnMfa<?php echo $user->id; ?>" data-btnMfaid="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="reset MFA"><i class="bx bx-log-out-circle"></i></button>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>
                                                <li class="list-inline-item">
                                                    <button class="btn btn-primary btn-sm rounded-2 groupBtnList" type="button" id="btnGroup<?php echo $user->id; ?>" data-btnGid="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="change group"><i class="bx bx-group"></i></button>
                                                </li>
                                            </ul>
                                <?php
                            } else {
                                if($user->totp_enabled==1) {
                                    ?>
                                        <li class="list-inline-item">
                                            <button class="btn btn-warning btn-sm rounded-2 mfaBtnList" type="button" id="btnMfa<?php echo $user->id; ?>" data-btnMfaid="<?php echo $user->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="reset MFA"><i class="bx bx-log-out-circle"></i></button>
                                        </li>
                                    <?php
                                } else {
                                    ?>
                                    builtin user
                                    <?php
                                }                                
                            }
                        ?>
                    </td> 
                    </tr>  
        <?php  
        };  
        ?>  
        </tbody>  
        </table>
        <?php
    } 
    // show error
    catch (Exception $e){
        
        // set response code
        http_response_code(400);
    
        // show error message
        echo $e->getMessage();
    }
 }
 // show error message if jwt is empty
 else{
    
    // set response code
    http_response_code(401);

    // tell the user access denied
    echo "Access denied.";
 }
?>