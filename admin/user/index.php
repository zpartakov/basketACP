<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
checkUser();

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Voir les utilisateurs';
		break;

	case 'add' :
		$content 	= 'add.php';		
		$pageTitle 	= 'Administration des commandes - Ajouter un utilisateur';
		break;

	case 'modify' :
		$content 	= 'modify.php';		
		$pageTitle 	= 'Administration des commandes - Modifier les utilisateurs';
		break;

	default :
		$content 	= 'list.php';		
		$pageTitle 	= 'Administration des commandes - Voir les utilisateurs';
}

$script    = array('user.js');

require_once '../include/template.php';
?>
