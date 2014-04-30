<?php
if (!defined('WEB_ROOT')) {
	exit;
}

if (!isset($_GET['oid']) || (int)$_GET['oid'] <= 0) {
	header('Location: index.php');
}

$orderId = (int)$_GET['oid'];

// get ordered items
$sql = "SELECT pd_name, pd_price, od_qty
	    FROM tbl_order_item oi, tbl_product p 
		WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
		ORDER BY od_id ASC";

		$result = dbQuery($sql);
		while ($row = dbFetchAssoc($result)) {
			if ($row['pd_thumbnail']) {
				$row['pd_thumbnail'] = WEB_ROOT . 'images/product/' . $row['pd_thumbnail'];
			} else {
				$row['pd_thumbnail'] = WEB_ROOT . 'images/no-image-small.png';
			}
			$cartContent[] = $row;
		}
		
	$numItem = count($cartContent);
	$subTotal = 0;

	for ($i = 0; $i < $numItem; $i++) {
		extract($cartContent[$i]);
		$productUrl = "index.php?c=$cat_id&p=$pd_id";
		$subTotal += $pd_price * $ct_qty;
$produits.='
	 <tr class="content">	
	  <td>'.utf8_encode($pd_name) .'</td>
<td align="right">' .$pd_price .'</td>	  
<td align="right">' .$od_qty .'</td>	  
<td align="right">' .displayAmount($pd_price * $od_qty) .'</td>	  
	 </tr>
';
$subTotal=$subTotal+($pd_price * $od_qty);
$adresse=$PersAdresse ."<br>" .$PersNPA ." " .$PersLocalite;
$tel=$PersTelephone;
$email=$PersAdresseEmail;
$date=$od_date;
$date_livraison=$date_livraison;
	}

//date french readable
$date=preg_replace("/ .*/","",$date);
$date=explode("-",$date);
$date=$date[2]."-".$date[1]."-".$date[0];

//date livraison french readable

$date_livraison=preg_replace("/ .*/","",$date_livraison);
$date_livraison=explode("-",$date_livraison);
$date_livraison=$date_livraison[2]."-".$date_livraison[1]."-".$date_livraison[0];

//calcul pointde livraison
$pdd="SELECT * FROM jos_pdds WHERE id = '" .$PersPDDDistrNo ."'";
$pdd=mysql_query("$pdd");
$pdd=mysql_result($pdd,0,'PDDTexte'); 

$result = dbQuery($sql);
$orderedItem = array();
while ($row = dbFetchAssoc($result)) {
	$orderedItem[] = $row;
}


// get order information

$sql = "SELECT * 
			FROM tbl_order o, tbl_order_item oi, tbl_customers c, tbl_product p 
			WHERE o.od_id = '" .$orderId
			."' AND oi.od_id = o.od_id
			 AND c.jos_user_id  = o.od_shipping_user 
			 AND p.pd_id=oi.pd_id";
			 
			 #echo $sql; //tests
			 
$result = dbQuery($sql);
extract(dbFetchAssoc($result));

$orderStatus = array('Nouveau', 'Payé', 'Envoyé', 'Terminé', 'Supprimé');
$orderOption = '';
foreach ($orderStatus as $status) {
	$orderOption .= "<option value=\"$status\"";
	if ($status == utf8_encode($od_status)) {
		$orderOption .= " selected";
	}
	
	$orderOption .= ">$status</option>\r\n";
	
	
$adresse=$PersAdresse ."<br>" .$PersNPA ." " .$PersLocalite;
$tel=$PersTelephone;
$email=$PersAdresseEmail;
$date=$od_date;
$date_livraison=$date_livraison;
	
}
//calcul point de livraison
$pdd="SELECT * FROM jos_pdds WHERE id = '" .$PersPDDDistrNo ."'";
$pdd=mysql_query("$pdd");
$pdd=mysql_result($pdd,0,'PDDTexte'); 
#echo $pdd; exit;
?>
<p>&nbsp;</p>
<form action="" method="get" name="frmOrder" id="frmOrder">
    <table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
        <tr> 
            <td colspan="2" align="center" id="infoTableHeader">Détail commande</td>
        </tr>
        <tr> 
            <td width="150" class="label">Commande numéro</td>
            <td class="content"><?php echo $orderId; ?></td>
        </tr>
        <tr> 
            <td width="150" class="label">Date de la commande</td>
            <td class="content">
<?php 
               //date french readable
$date=$od_date;
$date=preg_replace("/ .*/","",$date);
$date=explode("-",$date);
$date=$date[2]."-".$date[1]."-".$date[0];
$hour=preg_replace("/.* /","",$od_date);
$hour=preg_replace("/...$/","",$hour);
$hour=preg_replace("/:/","h",$hour);
echo $date .", " .$hour; 
?>
            </td>
        </tr>
        <tr> 
            <td width="150" class="label">Dernière mise à jour</td>
            <td class="content"><?php echo $od_last_update; ?></td>
        </tr>
        <tr> 
            <td class="label">Statut</td>
            <td class="content"> <select name="cboOrderStatus" id="cboOrderStatus" class="box">
                    <?php echo $orderOption; ?> </select> <input name="btnModify" type="button" id="btnModify" value="Modifer le statut" class="box" onClick="modifyOrderStatus(<?php echo $orderId; ?>);"></td>
        </tr>
    </table>
</form>
<p>&nbsp;</p>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="3">Produits</td>
    </tr>
    <tr align="center" class="label"> 
        <td>Produit</td>
        <td>Prix unitaire</td>
        <td>Nombre</td>
        <td>Total</td>
    </tr>
    <?php
    
echo $produits;
    
?>
    <tr class="content"> 
        <td colspan="2" align="right"><strong>Total</strong></td>
        <td align="right" colspan="2"><strong><?php echo displayAmount($subTotal); ?></strong></td>
    </tr>
   
</table>
<p>&nbsp;</p>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Client</td>
    </tr>
    <tr> 
        <td width="150" class="label">Prénom</td>
        <td class="content"><?php echo utf8_encode($PersPrenom); ?> </td>
    </tr>
    <tr> 
        <td width="150" class="label">Nom</td>
        <td class="content"><?php echo utf8_encode($PersNom); ?> </td>
    </tr>
    <tr> 
        <td width="150" class="label">Adresse</td>
        <td class="content"><?php echo utf8_encode($adresse); ?> </td>
    </tr>
   
    <tr> 
        <td width="150" class="label">Téléphone</td>
        <td class="content"><?php echo $tel; ?> </td>
    </tr>
    
        <tr> 
        <td width="150" class="label">Email</td>
        <td class="content"><?php echo "<a href=\"mailto:".$PersAdresseEmail ."\">" .$PersAdresseEmail ."</a>" 	; ?> </td>
    </tr>

    <tr> 
        <td width="150" class="label">Livraison</td>
        <td class="content"><?php echo $pdd; ?> </td>
    </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Mémo</td>
    </tr>
    <tr> 
        <td colspan="2" class="label"><?php echo nl2br($od_memo); ?> </td>
    </tr>
</table>
<p>&nbsp;</p>
<p align="center"> 
    <input name="btnBack" type="button" id="btnBack" value="Retour" class="box" onClick="window.history.back();">
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
