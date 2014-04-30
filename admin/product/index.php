<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
checkUser();

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Liste des produits';
		break;

	case 'add' :
		$content 	= 'add.php';		
		$pageTitle 	= 'Administration des commandes - Ajouter un produit';
		break;

	case 'modify' :
		$content 	= 'modify.php';		
		$pageTitle 	= 'Administration des commandes - Modifier un produit';
		break;

	case 'detail' :
		$content    = 'detail.php';
		$pageTitle  = 'Administration des commandes - Détail d\'un produit';
		break;
		
	default :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Liste des produits';
}




$script    = array('product.js');

require_once '../include/template.php';
?>
