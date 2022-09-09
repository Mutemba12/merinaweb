<?php
/**
 * Script called (Ajax) on customer update
 * fills the customer fields in the booking form
 */
session_start();
if(!isset($_SESSION['user'])) exit();

if(defined('PMS_DEMO') && PMS_DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$response = array();

if($pms_db !== false && isset($_POST['id']) && is_numeric($_POST['id'])){
    $result_user = $pms_db->query('SELECT * FROM pm_user WHERE id = '.$_POST['id']);
	if($result_user !== false && $pms_db->last_row_count() > 0){
		$response = $result_user->fetch(PDO::FETCH_ASSOC);
	}
}

echo json_encode($response);
