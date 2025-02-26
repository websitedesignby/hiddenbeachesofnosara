<?php

require("../inc/config.php");

$user_first_name = 'Ross';
$user_last_name = 'Sabes';
$user_username = 'webdesignby';
$user_type = 'a';
$user_password = 'art2000';

# add admin user account
$sql = "INSERT INTO wma_users (user_first_name, user_last_name, user_username, user_password, user_type, user_status)
VALUES ('".$user_first_name."','".$user_last_name."','".$user_username."',ENCODE('".$user_password."','".$passcrypt."'),
'".$user_type."','y')";
mysql_query($sql);
$user_id = mysql_insert_id();
var_dump( $user_id );