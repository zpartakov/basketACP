<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

checkUser();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'modify' :
        modifyOrder();
        break;

    default :
        // if action is not defined or unknown
        // move to main category page
        header('Location: index.php');
}



function modifyOrder()
{
	if (!isset($_GET['oid']) || (int)$_GET['oid'] <= 0
	    || !isset($_GET['status']) || $_GET['status'] == '') {
		header('Location: index.php');
	}
	
	$orderId = (int)$_GET['oid'];
	$status  = utf8_decode($_GET['status']);
    
    $sql = "UPDATE tbl_order
            SET od_status = '$status', od_last_update = NOW()
            WHERE od_id = $orderId";
            //echo $sql; exit;
    $result = dbQuery($sql);
	
	//header("Location: index.php?view=list&status=$status");    
	header("Location: index.php?view=list");    
}

?>
