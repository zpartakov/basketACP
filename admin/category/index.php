<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
checkUser();

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Voir les catégories';
		break;

	case 'add' :
		$content 	= 'add.php';		
		$pageTitle 	= 'Administration des commandes - Ajouter une catégorie';
		break;

	case 'modify' :
		$content 	= 'modify.php';		
		$pageTitle 	= 'Administration des commandes - Modifier les catégories';
		break;

	default :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Voir les catégories';
}


$script    = array('category.js');

require_once '../include/template.php';
?>
