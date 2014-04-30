<?php
if (!defined('WEB_ROOT')) {
	exit;
}
if(!isset($_SESSION['uid'])){
	echo "Vous devez vous &egrave;tre connect&eacute; avec votre identifiant et votre mot de passe sur le site";
	echo '<br><a href="' .HOME_SITE.'">Retour au site</a>';
	exit; 
}

require_once 'admin/library/functions.php';



// set the default page title
if(!isset($pageTitle)){
$pageTitle = $shopConfig['name'] ." - " .SITE_NAME;
}
// if a product id is set add the product name
// to the page title but if the product id is not
// present check if a category id exist in the query string
// and add the category name to the page title
if (isset($_GET['p']) && (int)$_GET['p'] > 0) {
	$pdId = (int)$_GET['p'];
	$sql = "SELECT pd_name
			FROM tbl_product
			WHERE pd_id = $pdId";
	
	$result    = dbQuery($sql);
	$row       = dbFetchAssoc($result);
	$pageTitle = $row['pd_name'];
	
} else if (isset($_GET['c']) && (int)$_GET['c'] > 0) {
	$catId = (int)$_GET['c'];
	$sql = "SELECT cat_name
	        FROM tbl_category
			WHERE cat_id = $catId";

    $result    = dbQuery($sql);
	$row       = dbFetchAssoc($result);
	$pageTitle = $row['cat_name'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo decoder($pageTitle); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="include/shop.css" rel="stylesheet" type="text/css">


<script language="JavaScript" type="text/javascript" src="library/common.js"></script>
</head>
<BODY>
<NOSCRIPT><h1>Vous devez activer JavaScript!</h1><br></NOSCRIPT>
<div><img id="bg_image" src="http://www.les-jardins-de-cocagne.ch/cms/images/cocagne/fondvh2.gif" alt="" title="" /></div>
<div id="scrollable">
<table align="center">
	<tr>
<td class="tablelogo"><a href="/cms""><img src="<?php echo LOGO; ?>" alt="Retour à la page d'accueil"></a></td>
		<td class="tablelogo"><div class="chercher">
<form action="index.php" method="GET">chercher:<input type="text" name="cherche" value="" onChange="submit()"></form>
</div> </td>
	</tr>
</table>

