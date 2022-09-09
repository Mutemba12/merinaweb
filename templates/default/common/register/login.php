<?php
/**
 * Script called (Ajax) on login
 */
require_once("../../../../common/lib.php");
require_once("../../../../common/define.php");
    
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");

$user = htmlentities($_POST['user'], ENT_COMPAT, "UTF-8");
$password = $_POST['pass'];
        
$result_user = $pms_db->query("SELECT * FROM pm_user WHERE (login = ".$pms_db->quote($user)." OR email = ".$pms_db->quote($user).") AND pass = '".md5($password)."' AND checked = 1");
if($result_user !== false && $pms_db->last_row_count() > 0){
    $row = $result_user->fetch();
    $_SESSION['user']['id'] = $row['id'];
    $_SESSION['user']['login'] = $row['login'];
    $_SESSION['user']['email'] = $row['email'];
    $_SESSION['user']['type'] = $row['type'];
    $_SESSION['user']['add_date'] = $row['add_date'];
    
    if($_SESSION['user']['type'] != "registered") $response['redirect'] = DOCBASE.PMS_ADMIN_FOLDER;
}else
    $response['error'] = $pms_texts['INCORRECT_LOGIN'];

echo json_encode($response);
