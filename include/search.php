<?php
#echo phpinfo(); exit;

require_once './library/variables.inc.php';
require_once './library/config.php';

$pageTitle = 'Administration des commandes';

$chercher=$_GET['cherche'];



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


<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
 <tr><td colspan="3"><h1>Votre recherche: <em><? echo $chercher; ?></em></h1>
</td></tr> 
<tr align="center" id="listTableHeader"> 
   <td>Nom du produit</td>
   <td width="75">Image</td>
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
   <td><a href="index.php?p=<?php echo mysql_result($sql,$i,'pd_id'); ?>"><?php echo utf8_encode(mysql_result($sql,$i,'pd_name')); ?></a></td>
   <td width="75" align="center">
   <?
   if(strlen(mysql_result($sql,$i,'pd_thumbnail'))>0) {
	   ?>
   <img src="images/product/<?php echo mysql_result($sql,$i,'pd_thumbnail'); ?>" width="50px">
   <?
}
?>
   </td>
   <td width="75" align="center"><a href="index.php?c=<?php echo mysql_result($sql,$i,'cat_id'); ?>"><?php echo utf8_encode(mysql_result($sql,$i,'cat_name')); ?></a></td>
    </tr>
  <?
	$i++;
	}

?>
</table>

