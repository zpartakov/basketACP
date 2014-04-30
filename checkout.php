<?php
require_once 'library/config.php';
require_once 'library/cart-functions.php';
require_once 'library/checkout-functions.php';

if (isCartEmpty()) {
	// the shopping cart is still empty
	// so checkout is not allowed
	header('Location: cart.php');
} else if (isset($_GET['step']) && (int)$_GET['step'] > 0 && (int)$_GET['step'] <= 3) {
	$step = (int)$_GET['step'];

	$includeFile = '';
	if ($step == 1) {
		$includeFile = 'shippingAndPaymentInfo.php';
#		$pageTitle   = 'Confirmation de la commande - Etape 1/3';
		$pageTitle   = 'Envoi de la commande';
	
	/*
	 * } else if ($step == 2) {
		$includeFile = 'checkoutConfirmation.php';
		$pageTitle   = 'Confirmation de la commande - Etape 2/3';
	} else if ($step == 3) {
		$orderId     = saveOrder();
		$orderAmount = getOrderAmount($orderId);
	*/
	

	} else if ($step == 2) {
		$orderId     = saveOrder();
		$orderAmount = getOrderAmount($orderId);
		
		$_SESSION['orderId'] = $orderId;
		
		// our next action depends on the payment method
		// if the payment method is COD then show the 
		// success page but when paypal is selected
		// send the order details to paypal
		/*modif radeff*/
		$cod=0;
		if ($cod==0) {
		#if ($_POST['hidPaymentMethod'] == 'cod') {
			header('Location: success.php');
			exit;
		} else {
			$includeFile = 'paypal/payment.php';	
		}
	}
} else {
	// missing or invalid step number, just redirect
	header('Location: index.php');
}

require_once 'include/header.php';
?>
<script language="JavaScript" type="text/javascript" src="library/checkout.js"></script>
<?php
require_once "include/$includeFile";
require_once 'include/footer.php';
?>
