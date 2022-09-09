<?php
/**
 * Script called (Ajax) on customer update
 * fills the activity fields in the booking form
 */
session_start();
if(!isset($_SESSION['user'])) exit();

if(defined('PMS_DEMO') && PMS_DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');

$response = array();

if($pms_db !== false && isset($_POST['id']) && is_numeric($_POST['id'])){
    $result_activity = $pms_db->query('SELECT * FROM pm_activity WHERE id = '.$_POST['id'].' AND lang = '.PMS_DEFAULT_LANG);
	if($result_activity !== false && $pms_db->last_row_count() > 0){
		$response = $result_activity->fetch(PDO::FETCH_ASSOC);
		$duration = mb_strtoupper($response['duration_unit']);
		$response['duration'] .= ' '.pms_getAltText($pms_texts[substr($duration, 0, -1)], $pms_texts[$duration], $response['duration']);
		
		$response['tax_rate'] = '';
		$result_rate = $pms_db->query('SELECT value FROM pm_activity_session as a, pm_tax as t WHERE id_tax = t.id AND id_activity = '.$_POST['id']);
		if($result_rate !== false && $pms_db->last_row_count() > 0){
			$row = $result_rate->fetch();
			$response['tax_rate'] = $row['value'];
		}
	}
}

echo json_encode($response);
