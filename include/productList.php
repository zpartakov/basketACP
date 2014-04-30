<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$productsPerRow = 3;
$productsPerPage = 200; //nombre de produit par page

//$productList    = getProductList($catId);
$children = array_merge(array($catId), getChildCategories(NULL, $catId));
$children = ' (' . implode(', ', $children) . ')';

$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, c.cat_id
		FROM tbl_product pd, tbl_category c
		WHERE pd.cat_id = c.cat_id AND pd.cat_id IN $children 
		ORDER BY pd_name";
		#echo $sql;
		
$result     = dbQuery(getPagingQuery($sql, $productsPerPage));
$pagingLink = getPagingLink($sql, $productsPerPage, "c=$catId");
$numProduct = dbNumRows($result);

// the product images are arranged in a table. to make sure
// each image gets equal space set the cell width here
$columnWidth = (int)(100 / $productsPerRow);

$catlib="SELECT * FROM tbl_category WHERE cat_id=" .$_GET['c'];
	#echo $catlib;//tests
$catlib=mysql_query("$catlib");
echo "<h1>" .utf8_encode(mysql_result($catlib,0,'cat_name')) ."</h1>";
echo "<h2>" .utf8_encode(mysql_result($catlib,0,'cat_description')) ."</h2>";
/*
if(strlen(mysql_result($catlib,0,'cat_image'))>0){
	echo "<img src=\"images/category/" .mysql_result($catlib,0,'cat_image') ."\"><br>";
}
* */
?>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<?php 
if ($numProduct > 0 ) {

	$i = 0; $j=0;
	while ($row = dbFetchAssoc($result)) {
	
		extract($row);
		if ($pd_thumbnail) {
			$pd_thumbnail = WEB_ROOT . 'images/product/' . $pd_thumbnail;
		} else {
			$pd_thumbnail = WEB_ROOT . 'images/no-image-small.png';
		}
	
	/*1e ligne*/
		#if ($i % $productsPerRow == 0) {
		if ($i == 0) {
			echo '<tr>';
		}

		// format how we display the price
		$pd_price = displayAmount($pd_price);
		if ($pd_qty > 0) {
		$j++;
		echo "<td width=\"$columnWidth%\" align=\"center\"><a href=\"" . $_SERVER['PHP_SELF'] . "?c=$catId&p=$pd_id" . "\">";
		if(strlen($pd_thumbnail>0)) { //image
			echo "<img src=\"$pd_thumbnail\" border=\"0\" width=\"50\">";
		}
		echo "<br>" .decoder($pd_name) ."</a><br>Prix: ".  $pd_price;
}
		// if the product is no longer in stock, tell the customer
		if ($pd_qty <= 0) {
			#echo "<br>Non disponible";
		}
		if ($pd_qty > 0) {
		
		echo "</td>\r\n";
}	
#		if ($i % $productsPerRow == $productsPerRow - 1) {
		if (intval(($j)/$productsPerRow) == (($j)/$productsPerRow)) {
			echo '<!-- j= ' .($j) .' --></tr>';
		}
				if ($pd_qty > 0) {
		$i += 1;

	}
	}
	
	if ($i % $productsPerRow > 0) {
		echo '<td colspan="' . ($productsPerRow - ($i % $productsPerRow)) . '">&nbsp;</td>';
	}
	
} else {
?>
	<tr><td width="100%" align="center" valign="center">Aucun produit dans cette catégorie</td></tr>
<?php	
}	
?>
</table>
<p align="center"><?php echo $pagingLink; ?></p>
