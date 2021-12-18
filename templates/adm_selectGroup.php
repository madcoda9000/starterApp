<?php
include_once '../api/config/core.php';
include_once '../api/vendor/autoload.php';

// create orm instance
ORM::configure('mysql:host=' . $DB_host . ';dbname='.$DB_name);
ORM::configure('username', $DB_user);
ORM::configure('password', $DB_pass);
ORM::configure('return_result_sets', true);

$groups = ORM::for_table('app_groups')->order_by_desc('id')->find_many();
?>
<h5>Please select a group</h5>
<select class="form-select" aria-label="Default select example" id="gSelect">
<?php
if($groups) {
  foreach ($groups as $group) {
    if($group->groupName == "users") {
      ?>
        <option selected><?php echo $group->groupName ?></option>
      <?php
    } else {
      ?>
        <option><?php echo $group->groupName ?></option>
      <?php
    }
  }
}
?>
  </select>