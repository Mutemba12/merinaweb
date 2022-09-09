<?php
/**
 * Script called (Ajax) on customer update
 * fills the room fields in the booking form
 */
session_start();
if(!isset($_SESSION['user'])) exit();

if(defined('PMS_DEMO') && PMS_DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$response = array();

if($pms_db !== false && isset($_POST['id']) && is_numeric($_POST['id'])){
    $result_room = $pms_db->query('SELECT * FROM pm_room WHERE id = '.$_POST['id'].' AND lang = '.PMS_DEFAULT_LANG);
	if($result_room !== false && $pms_db->last_row_count() > 0){
		$response = $result_room->fetch(PDO::FETCH_ASSOC);
		
		$response['tax_rate'] = '';
		$result_rate = $pms_db->query('SELECT value FROM pm_rate as r, pm_tax as t WHERE id_tax = t.id AND id_room = '.$_POST['id']);
		if($result_rate !== false && $pms_db->last_row_count() > 0){
			$row = $result_rate->fetch();
			$response['tax_rate'] = $row['value'];
		}
	}
}

echo json_encode($response);
