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
        $limit = 5;  
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
        $start_from = ($page-1) * $limit; 
        $sql = "SELECT * FROM app_groups ORDER BY id ASC LIMIT $start_from, $limit";
        $groups = ORM::for_table('app_groups')->raw_query($sql)->find_many();

        ?> <table class="table table-bordered table-striped">  
        <thead>  
        <tr>  
        <th style="width: 10%">ID</th>  
        <th style="width: 60%">group name</th>  
        <th style="width: 30%">actions</th>  
        </tr>  
        </thead>  
        <tbody>  
        <?php  
        foreach ($groups as $group) {  
        ?>  
                    <tr>  
                    <td><?php echo $group->id; ?></td>  
                    <td><?php echo $group->groupName; ?></td> 
                    <td>
                        <?php
                            if($group->groupName != 'admins' && $group->groupName != 'users') {
                                ?>
                                    <!-- Call to action buttons -->
                                    <ul class="list-inline m-0">
                                                <li class="list-inline-item">
                                                    <button class="btn btn-danger btn-sm rounded-0 delBtnList" type="button" id="btnDel<?php echo $group->id; ?>" data-btnDelid="<?php echo $group->id; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="bx bxs-message-square-x"></i></button>
                                                </li>
                                            </ul>
                                <?php
                            } else {
                                ?>
                                    builtin group
                                <?php
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