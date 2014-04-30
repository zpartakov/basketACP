<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$cartContent = getCartContent();

$numItem = count($cartContent);	
?>
<table width="100%" cellspacing="0" cellpadding="2" id="minicart">
 <?php
if ($numItem > 0) {
?>
 <tr>
  <td colspan="2">Contenu du panier</td>
 </tr>
<?php
	$subTotal = 0;
	for ($i = 0; $i < $numItem; $i++) {
		extract($cartContent[$i]);
		$pd_name = "$ct_qty x $pd_name";
		$url = "index.php?c=$cat_id&p=$pd_id";
		
		$subTotal += $pd_price * $ct_qty;
	if(intval($i/2)!=($i/2)){
echo  "<tr class=\"pair\">";
	}else  {
echo  "<tr>";
	}	
?>
   <td><a href="<?php echo $url; ?>"><?php echo decoder($pd_name); ?></a></td>
   
  <td width="30%" align="right"><?php echo displayAmount($ct_qty * $pd_price); ?></td>
 </tr>
<?php
	} // end while
?>
 <!--  <tr><td align="right">Sous-total</td>
  <td width="30%" align="right"><?php echo displayAmount($subTotal); ?></td>
 </tr>
 <tr><td align="right">Expédition</td>
  <td width="30%" align="right"><?php echo displayAmount($shopConfig['shippingCost']); ?></td>
 </tr>-->
  <tr><td align="right"><strong>Total</strong></td>
  <td width="30%" align="right"><strong><?php echo displayAmount($subTotal + $shopConfig['shippingCost']); ?></strong></td>
 </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
  <td colspan="2" align="center"><a href="cart.php?action=view" title="Aller au panier"><img width="100px" src="../pics/panier.jpg" alt="Aller au panier"><br>Aller au panier / Passer la commande</a></td>
 </tr>  
<?php	
} else {
?>
  <tr><td colspan="2" align="center" valign="middle">Le panier est vide</td></tr>
<?php
}
?> 
</table>
