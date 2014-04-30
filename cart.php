<?php
require_once 'library/config.php';
require_once 'library/cart-functions.php';

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';

switch ($action) {
	case 'add' :
		addToCart();
		break;
	case 'update' :
		updateCart();
		break;	
	case 'delete' :
		deleteFromCart();
		break;
	case 'view' :
}

$cartContent = getCartContent();
$numItem = count($cartContent);

$pageTitle = 'Panier';
require_once 'include/header.php';

// show the error message ( if we have any )
displayError();

if ($numItem > 0 ) {
?>
<form action="<?php echo $_SERVER['PHP_SELF'] . "?action=update"; ?>" method="post" name="frmCart" id="frmCart">
 <table width="780" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable">
  <tr class="entryTableHeader"> 
   <td colspan="2" align="center">Produit</td>
   <td align="center">Prix unitaire</td>
   <td width="75" align="center">Quantité</td>
   <td align="center">Total</td>
  <td width="75" align="center">Action</td>
 </tr>
 <?php
$subTotal = 0;
for ($i = 0; $i < $numItem; $i++) {
	extract($cartContent[$i]);
	$productUrl = "index.php?c=$cat_id&p=$pd_id";
	$subTotal += $pd_price * $ct_qty;
?>
<script language="JavaScript">
function miseAjour() {
	/*todo: script pour actualiser les valeurs*/
	/*
for(i=0; i<document.frmCart.elements.length; i++)
{
document.write("The field name is: " + document.frmCart.elements[i].name + " and it’s value is: " + document.frmCart.elements[i].value + ".<br />");
}
*/
}
</script>
<?
if(intval($i/2)==($i/2)) { //pair
$couleurfond="white";
} else { //impair
	$couleurfond="#A3EEA3";
}
?>
 <tr class="content" style="background-color: <? echo $couleurfond; ?>"> 
  <td width="80" align="center"><a href="<?php echo $productUrl; ?>"><img width="100" src="<?php echo $pd_thumbnail; ?>" border="0"></a></td>
  <td><a href="<?php echo $productUrl; ?>"><?php echo utf8_encode($pd_name); ?></a></td>
   <td align="right"><?php echo displayAmount($pd_price); ?></td>
  <td width="75"><input name="txtQty[]" type="text" id="txtQty[]" size="5" value="<?php echo $ct_qty; ?>" class="box" onKeyUp="checkNumber(this);" onChange="miseAjour(this);">
  <input name="hidCartId[]" type="hidden" value="<?php echo $ct_id; ?>">
  <input name="hidProductId[]" type="hidden" value="<?php echo $pd_id; ?>">
  </td>
  <td align="right"><?php echo displayAmount($pd_price * $ct_qty); ?></td>
  <td width="75" align="center"> <input name="btnDelete" type="button" id="btnDelete" value="Supprimer" onClick="window.location.href='<?php echo $_SERVER['PHP_SELF'] . "?action=delete&cid=$ct_id"; ?>';" class="box"> 
  </td>
 </tr>
 <?php
}
?>
<!-- <tr class="content"> 
  <td colspan="4" align="right">Sous-total</td>
  <td align="right"><?php echo displayAmount($subTotal); ?></td>
  <td width="75" align="center">&nbsp;</td>
 </tr>
<tr class="content"> 
   <td colspan="4" align="right">Livraison </td>
  <td align="right"><?php echo displayAmount($shopConfig['shippingCost']); ?></td>
  <td width="75" align="center">&nbsp;</td>
 </tr>-->
<tr class="content"> 
   <td colspan="5" align="right"><strong>Total <?php echo displayAmount($subTotal + $shopConfig['shippingCost']); ?></strong></td>
 <td>&nbsp;</td>
 </tr>  
 <tr class="content"> 
  <td colspan="5" align="right">&nbsp;</td>
  <td width="75" align="center">
<input name="btnUpdate" type="submit" id="btnUpdate" value="Actualiser" class="box"></td>
 </tr>
</table>
</form>
<?php
} else {
	
?>
<p>&nbsp;</p><table width="550" border="0" align="center" cellpadding="10" cellspacing="0">
 <tr>
  <td><p align="center">Votre panier est vide</p>
   <p>Si vous ne pouvez rien ajouter à votre panier, vérifiez que votre navigateur supporte les cookies et qu'un logiciel de sécurité ne bloque pas votre session.</p></td>
 </tr>
</table>
<?php
}

$shoppingReturnUrl = isset($_SESSION['shop_return_url']) ? $_SESSION['shop_return_url'] : 'index.php';
?>
<table width="550" border="0" align="center" cellpadding="10" cellspacing="0">
 <tr align="center"> 
  <td><input name="btnContinue" type="button" id="btnContinue" value="&lt;&lt; Continuer votre commande" onClick="window.location.href='<?php echo $shoppingReturnUrl; ?>';" class="box"></td>
<?php 
if ($numItem > 0) {
?>  
  <td><input name="btnCheckout" type="button" id="btnCheckout" value="Passer la commande &gt;&gt;" onClick="window.location.href='checkout.php?step=1';" class="box"></td>
<?php
}
?>  
 </tr>
</table>
<?php
require_once 'include/footer.php';
?>
