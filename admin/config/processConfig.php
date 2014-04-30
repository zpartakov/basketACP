<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

checkUser();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	
    case 'modify' :
        modifyShopConfig();
        break;
    

    default :
        // if action is not defined or unknown
        // move to main page
        header('Location: index.php');
}



function modifyShopConfig()
{
	$shopName  = encoder($_POST['txtShopName']);
	$address   = encoder($_POST['mtxAddress']);
	$phone     = encoder($_POST['txtPhone']);
	$email     = encoder($_POST['txtEmail']);
	$shipping  = encoder($_POST['txtShippingCost']);
	$currency  = encoder($_POST['cboCurrency']);
	$sendEmail = encoder($_POST['optSendEmail']);
	
    $sql = "UPDATE tbl_shop_config
            SET sc_name = '$shopName', sc_address = '$address', sc_phone = '$phone', sc_email = '$email',
			    sc_shipping_cost = $shipping, sc_currency = $currency, sc_order_email = '$sendEmail'";
    $result = dbQuery($sql);

	header("Location: index.php");    
}

?>
