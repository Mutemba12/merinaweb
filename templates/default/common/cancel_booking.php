<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '');

if(isset($pms_db) && $pms_db !== false && isset($_SESSION['book']['sessid'])){
        
    $pms_db->query('DELETE FROM pm_room_lock WHERE sessid = '.$pms_db->quote($_SESSION['book']['sessid']));
    unset($_SESSION['book']);
    echo json_encode($response);
}
