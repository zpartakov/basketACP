<?php
require_once 'config.php';

/* addon on radeff begin */
#convertir date française DD-MM-YYYY au format MySQL YYYY-MM-DD
function datefr2mySQL($date) { 
$split = explode("-",$date); 
$annee = $split[2]; 
$mois = $split[1]; 
$jour = $split[0]; 
return "$annee"."-"."$mois"."-"."$jour"; 
} 
/* addon on radeff end */


/*********************************************************
*                 CHECKOUT FUNCTIONS 
*********************************************************/
function saveOrder()
{
	$orderId       = 0;
		// make sure the first character in the 
		// customer and city name are properly upper cased
		$hidShippingFirstName = ucwords($_POST['txtShippingFirstName']);
		$hidShippingLastName  = ucwords($_POST['txtShippingLastName']);
		/*$hidPaymentFirstName  = ucwords($_POST['txtShippingAddress1']);
		$hidPaymentLastName   = ucwords($_POST['']);
		$hidShippingCity      = ucwords($_POST['']);
		$hidPaymentCity       = ucwords($_POST['']);*/
			$date2livraison	 = datefr2mySQL($_POST['date2livraison']);
			#echo datefr2mySQL($date2livraison); exit;
		$cartContent = getCartContent();
		$numItem     = count($cartContent);
		
		// save order & get order id
		$sql = "
		INSERT INTO `tbl_order` (
			`od_id` ,
			`od_date` ,
			`od_last_update` ,
			`od_status` ,
			`od_memo` ,
			`od_shipping_user`,
			`date_livraison`
			)
			VALUES (
			NULL , NOW(), NOW(), 'New', '" .addslashes($_POST['message']) ."', '" .$_SESSION['uid'] ."', '" .$date2livraison ."');";
		#echo $sql; exit;

		$result = dbQuery($sql);
		
		// get the order id
		$orderId = dbInsertId();
		
		if ($orderId) {
			// save order items
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "INSERT INTO tbl_order_item(od_id, pd_id, od_qty)
						VALUES ($orderId, {$cartContent[$i]['pd_id']}, {$cartContent[$i]['ct_qty']})";
				$result = dbQuery($sql);					
			}
		
			
			// update product stock
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "UPDATE tbl_product 
				        SET pd_qty = pd_qty - {$cartContent[$i]['ct_qty']}
						WHERE pd_id = {$cartContent[$i]['pd_id']}";
				$result = dbQuery($sql);					
			}
			
			
			// then remove the ordered items from cart
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "DELETE FROM tbl_cart
				        WHERE ct_id = {$cartContent[$i]['ct_id']}";
				$result = dbQuery($sql);					
			}							
		}					
	/*} else {
		echo "error on required fields"; exit;
	}*/
	
	return $orderId;
}

/*
	Get order total amount ( total purchase + shipping cost )
*/
function getOrderAmount($orderId)
{
	$orderAmount = 0;
	/*	$sql = "SELECT SUM(pd_price * od_qty)
	        FROM tbl_order_item oi, tbl_product p 
		    WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
			
			UNION
			
			SELECT od_shipping_cost 
			FROM tbl_order
			WHERE od_id = $orderId";*/
	$sql = "SELECT SUM(pd_price * od_qty)
	        FROM tbl_order_item oi, tbl_product p 
		    WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId";
	$result = dbQuery($sql);

	if (dbNumRows($result) == 2) {
		$row = dbFetchRow($result);
		$totalPurchase = $row[0];
		
		$row = dbFetchRow($result);
		$shippingCost = $row[0];
		
		$orderAmount = $totalPurchase + $shippingCost;
	}	
	
	return $orderAmount;	
}

?>
