<?php
//vacances
/*
header("Location: http://www.cocagne.ch/cms/commandes-des-le-24-01");
exit();
*/
#echo phpinfo(); exit;
require_once 'library/config.php';
require_once 'library/category-functions.php';
require_once 'library/product-functions.php';
require_once 'library/cart-functions.php';


 
$_SESSION['shop_return_url'] = $_SERVER['REQUEST_URI'];
#echo $_SERVER['REQUEST_URI']; exit;
//check user on welcome page commandes
//other pages
if($_GET['uid']) {
$uid=$_GET['uid'];
$_SESSION['uid'] = $uid; // store session data: identifiant de l'utilisateur passé par joomla
}
if(preg_match("/utilisateur=/",$_SERVER['REQUEST_URI'])) {
	#echo "HP<br>";
	if(strlen($_GET['utilisateur'])<3||!$_GET['utilisateur']) {
			echo "Le module de commande est réservé aux membres de la coopérative et vous n'êtes pas enregistré!"; exit;
	}
} else {
	/*echo "id: " .$_GET['uid'] ."<br>";
	echo "Session: " .$_SESSION['uid'] ."<br>";*/
	if(!$_SESSION['uid']&&!$_GET['uid']) {
			echo "Le module de commande est r&eacute;serv&eacute; aux membres de la coop&eacute;rative et vous n'&ecirc;tes pas enregistr&eacute;!"; exit;
	}


}




$chercher=$_GET['cherche'];



$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;
$pdId   = (isset($_GET['p']) && $_GET['p'] != '') ? $_GET['p'] : 0;



//on interroge la base des utilisateurs joomla
$sql="SELECT * FROM jos_users WHERE id=".$_SESSION['uid'];
#do and check sql
$sql=mysql_query($sql);
if(!$sql) {
	echo "SQL error: " .mysql_error(); exit;
} elseif(mysql_num_rows($sql)<1) {
	echo "Aucune enregistrement ne correspond &agrave; votre id utilisateur!"; exit;
} elseif(mysql_num_rows($sql)>1) {
	echo "Il y a plus d'un utilisateur avec cet id utilisateur!"; exit;
}


$idj=mysql_result($sql,0,'id');
	
#####################################
//on interroge la base des clients qui commandent des produits
$sql="SELECT * FROM tbl_customers WHERE jos_user_id=".$idj;
#do and check sql
$sql=mysql_query($sql);
if(!$sql) {
	echo "SQL error: " .mysql_error(); exit;
}

if(mysql_num_rows($sql)!=1) { //the user is not registred
header("Location: register.php?jid=".$idj); //redirect user to registration
} else { //registration ok
	$_SESSION['pdduserid'] = mysql_result($sql,0,"PersNo"); // store session data: identifiant de l'utilisateur base adresses
}

require_once 'include/header.php';
	






?>
<h1><? echo SITE_NAME; ?></h1> 

<table class="maintable" align="center">
 <tr valign="top"> 
  <td width="150" height="400" id="leftnav"> 
<?php
require_once 'include/leftNav.php';
?>
  </td>
  <td>
<?php
if ($pdId) {
	require_once 'include/productDetail.php';
} else if ($catId) {
	require_once 'include/productList.php';
} else if ($chercher&&$chercher!="chercher...") {
	#require_once 'include/search.php?uid='.$uid;	
	require_once 'include/search.php';	
	
} else {
	require_once 'include/categoryList.php';
}
?>  
  </td>
  <td width="130" align="center"><?php require_once 'include/miniCart.php'; ?></td>
 </tr>
</table>
<?php
require_once 'include/footer.php';
?>
