<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
checkUser();

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Voir les commandes';
		break;

	case 'detail' :
		$content 	= 'detail.php';		
		$pageTitle 	= 'Administration des commandes - Détail';
		break;

	case 'modify' :
		modifyStatus();
		$content 	= 'modify.php';		
		$pageTitle 	= 'Administration des commandes - Modifier les commandes';
		break;

	default :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes';
}




$script    = array('order.js');

require_once '../include/template.php';
?>
