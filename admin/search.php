<?php

require_once '../library/variables.inc.php';
require_once '../library/config.php';

$pageTitle = 'Administration des commandes';

$chercher=$_GET['cherche'];
#echo $chercher;
$sql="SELECT *
FROM `tbl_product`
WHERE `pd_name` LIKE '%" .$chercher ."%'
OR `pd_description` LIKE '%" .$chercher ."%'";

$sql = "SELECT pd_id, c.cat_id, cat_name, pd_name, pd_thumbnail
        FROM tbl_product p, tbl_category c
		WHERE p.cat_id = c.cat_id
		AND (`pd_name` LIKE '%" .$chercher ."%'
OR `pd_description` LIKE '%" .$chercher ."%' ) 		
		ORDER BY pd_name";
#echo $sql;
$sql=mysql_query($sql);
if(!$sql) { echo "SQL error: " .mysql_error(); }

/*$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
$pagingLink = getPagingLink($sql, $rowsPerPage, $queryString);

$categoryList = buildCategoryOptions($catId);
*/

?>
<html>
<head>
<title><?php echo $pageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo WEB_ROOT;?>admin/include/admin.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="<?php echo WEB_ROOT;?>library/common.js"></script>
<?php
$n = count($script);
for ($i = 0; $i < $n; $i++) {
	if ($script[$i] != '') {
		echo '<script language="JavaScript" type="text/javascript" src="' . WEB_ROOT. 'admin/library/' . $script[$i]. '"></script>';
	}
}
?>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="1" class="graybox">
  <tr>
    <td colspan="2"><a href="./"><img src="http://www.les-jardins-de-cocagne.ch/images/JaCoLogo1.gif"></a></td>
  </tr>

<table width="60%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
 <tr><td colspan="3"><h1>Votre recherche: <em><? echo $chercher; ?></em></h1>
</td></tr> 
<tr align="center" id="listTableHeader"> 
   <td>Nom du produit</td>
   <td width="75">Catégorie</td>

  </tr>
<?
$i=0;
while($i<mysql_num_rows($sql)){
	if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
	?>
	<tr class="<?php echo $class; ?>"> 
   <td><a href="product/index.php?view=modify&productId=<?php echo mysql_result($sql,$i,'pd_id'); ?>"><?php echo utf8_encode(mysql_result($sql,$i,'pd_name')); ?></a></td>
   <td width="75" align="center"><?php echo utf8_encode(mysql_result($sql,$i,'cat_name')); ?></td>
    </tr>
  <?
	$i++;
	}

?>
</table>
<a href="<? echo $_SERVER["HTTP_REFERER"]; ?>">Retour</a>
</body>
</html>
