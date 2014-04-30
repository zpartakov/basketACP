<?php
if (!defined('WEB_ROOT')) {
	exit;
}


if (isset($_GET['catId']) && (int)$_GET['catId'] > 0) {
	$catId = (int)$_GET['catId'];
	$sql2 = " AND p.cat_id = $catId";
	$queryString = "catId=$catId";
} else {
	$catId = 0;
	$sql2  = '';
	$queryString = '';
}

// for paging
// how many rows to show per page
$rowsPerPage = 250;

$sql = "SELECT pd_id, c.cat_id, cat_name, pd_name, pd_thumbnail, pd_price, pd_qty
        FROM tbl_product p, tbl_category c
		WHERE p.cat_id = c.cat_id $sql2
		ORDER BY pd_name";
$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
$pagingLink = getPagingLink($sql, $rowsPerPage, $queryString);

$categoryList = buildCategoryOptions($catId);

/* make plain links */
$sqlq="SELECT * FROM `tbl_category` ORDER BY `cat_name`";
$sqlq=mysql_query($sqlq);
if(!$sqlq) {
	echo mysql_error(); exit;
}
if(!$_GET['catId']) {
$i=0;
while($i<mysql_num_rows($sqlq)){
	echo "<a href=\"index.php?catId=" .mysql_result($sqlq,$i,'cat_id'). "\">" .utf8_encode(mysql_result($sqlq,$i,'cat_name'))."</a><br>";
	$i++;
	}

?> 
<p>&nbsp;</p>
<form action="processProduct.php?action=addProduct" method="post"  name="frmListProduct" id="frmListProduct">
<?
exit;
}
?>
<br>
<a href="<? echo $_SERVER["HTTP_REFERER"]; ?>">Retour</a>
<br>
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr> 
   <td colspan="5" align="right"><input name="btnAddProduct" type="button" id="btnAddProduct" value="Ajouter un produit" class="box" onClick="addProduct(<?php echo $catId; ?>)"></td>
  </tr>  <tr align="center" id="listTableHeader"> 
   <td>Nom du produit</td>
   <td>Prix</td>
   <td width="75">Image</td>
   <td width="75">Catégorie</td>
   <td width="70">Modifier</td>
   <td width="70">Supprimer</td>
  </tr>
  <?php
$parentId = 0;
if (dbNumRows($result) > 0) {
	$i = 0;
	
	while($row = dbFetchAssoc($result)) {
		extract($row);
		
		if ($pd_thumbnail) {
			$pd_thumbnail = WEB_ROOT . 'images/product/' . $pd_thumbnail;
		} else {
			$pd_thumbnail = WEB_ROOT . 'images/no-image-small.png';
		}	
		
		
		
		if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
		
		$i += 1;
?>
  <tr class="<?php echo $class; ?>"> 
   <td><a href="javascript:modifyProduct(<?php echo $pd_id; ?>);"><?php echo decoder($pd_name); ?></a>
   <?
   if($pd_qty==0) {
	   echo " (0)";
   }
   ?>
   
   </td>
   <td><?php echo $pd_price; ?></td>
   <td width="75" align="center"><img src="<?php echo $pd_thumbnail; ?>" width="50"></td>
   <td width="75" align="center"><?php echo decoder($cat_name); ?></td>
   <td width="70" align="center"><a href="javascript:modifyProduct(<?php echo $pd_id; ?>);">Modifier</a></td>
   <td width="70" align="center"><a href="javascript:deleteProduct(<?php echo $pd_id; ?>, <?php echo $catId; ?>);">Supprimer</a></td>
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
   <td colspan="5" align="center">Pas de produits pour l'instant</td>
  </tr>
  <?php
}
?>
  <tr> 
   <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
   <td colspan="5" align="right"><input name="btnAddProduct" type="button" id="btnAddProduct" value="Ajouter un produit" class="box" onClick="addProduct(<?php echo $catId; ?>)"></td>
  </tr>
 </table>
 <p>&nbsp;</p>
</form>
<a href="<? echo $_SERVER["HTTP_REFERER"]; ?>">Retour</a>
