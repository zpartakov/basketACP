<?php
if (!defined('WEB_ROOT')) {
	exit;
}

if (isset($_GET['status']) && $_GET['status'] != '') {
	$status = $_GET['status'];
	$sql2   = " AND od_status = '$status'";
	$queryString = "&status=$status";
} else {
	#echo "test"; exit;
/*	$status = '';
	$sql2   = '';
	$queryString = ''; */
	$status = '%';
	$sql2   = " AND od_status LIKE '%'";
	$queryString = "&status=%";
}	

// for paging
// how many rows to show per page
$rowsPerPage = 10;

$sql = "SELECT ju.name AS realname, o.od_id, o.od_shipping_user, od_date, od_status, 
SUM( pd_price * od_qty ) AS od_amount 
FROM jos_users ju, tbl_order o, tbl_order_item oi, tbl_product p 
WHERE oi.pd_id = p.pd_id $sql2 
AND ju.id = o.od_shipping_user
GROUP BY od_id
ORDER BY od_id DESC";

#echo nl2br($sql) ."<br>"; //tests
		
		
$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
$pagingLink = getPagingLink($sql, $rowsPerPage, $queryString);
$orderStatus = array('Nouveau', 'Payé', 'Envoyé', 'Terminé', 'Supprimé');
$orderOption = '';
foreach ($orderStatus as $stat) {
	$orderOption .= "<option value=\"$stat\"";
	if ($stat == $status) {
		$orderOption .= " selected";
	}
	
	$orderOption .= ">$stat</option>\r\n";
}
?> 
<p>&nbsp;</p>
<form action="processOrder.php" method="post"  name="frmOrderList" id="frmOrderList">
 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="text">
 <tr align="center"> 
  <td align="right">Voir</td>
  <td width="75"><select name="cboOrderStatus" class="box" id="cboOrderStatus" onChange="viewOrder();">
    <option value="" selected>Toutes</option>
    <?php echo $orderOption; ?>
  </select></td>
  </tr>
</table>

 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr align="center" id="listTableHeader"> 
   <td width="60">Commmande&nbsp;n°</td>
   <td>Nom du client</td>
   <!--<td width="60">Montant</td>-->
   <td width="150">Date de la commande</td>
   <td width="70">Statut</td>
  </tr>
  <?php
$parentId = 0;
if (dbNumRows($result) > 0) {
	$i = 0;
	
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$name = $od_shipping_user;
		
		if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
		
		$i += 1;
?>
  <tr class="<?php echo $class; ?>"> 
   <td width="60"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?view=detail&oid=<?php echo $od_id; ?>"><?php echo $od_id; ?></a></td>
   <td><?php echo utf8_encode($realname) ?></td>
   <!--<td width="60" align="right"><?php echo displayAmount($od_amount); ?></td>-->
   <td width="150" align="center">
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
   
   ?></td>
   <td width="70" align="center"><?php echo utf8_encode($od_status); ?></td>
  </tr>
  <?php
	} // end while

?>
  <tr> 
   <td colspan="5" align="center">
   <?php 
   echo $pagingLink;
   ?></td>
  </tr>
<?php
} else {
?>
  <tr> 
   <td colspan="5" align="center">Pas de commandes </td>
  </tr>
  <?php
}
?>

 </table>
 <p>&nbsp;</p>
</form>
