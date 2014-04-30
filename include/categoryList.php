<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$categoryList    = getCategoryList();
$categoriesPerRow = 3;
$numCategory     = count($categoryList);
$columnWidth    = (int)(100 / $categoriesPerRow);


$sql="SELECT * FROM tbl_titre";
#do and check sql
$sql=mysql_query($sql);
if(!$sql) {
	echo "SQL error: " .mysql_error(); exit;
}
	echo "<h1>" .mysql_result($sql,0,'titre') ."</h1>";

?>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<?php 
if ($numCategory > 0) {
	$i = 0;
	for ($i; $i < $numCategory; $i++) {
		if ($i % $categoriesPerRow == 0) {
			echo '<tr>';
		}
		
		// we have $url, $image, $name, $price
		extract ($categoryList[$i]);
		
		echo "<td width=\"$columnWidth%\" align=\"center\"><a href=\"$url\"><img src=\"$image\" border=\"0\"><br>" .decoder($name) ."</a></td>\r\n";
	
	
		if ($i % $categoriesPerRow == $categoriesPerRow - 1) {
			echo '</tr>';
		}
		
	}
	
	if ($i % $categoriesPerRow > 0) {
		echo '<td colspan="' . ($categoriesPerRow - ($i % $categoriesPerRow)) . '">&nbsp;</td>';
	}
} else {
?>
	<tr><td width="100%" align="center" valign="center">Il n'y actuellement aucune catégories</td></tr>
<?php	
}	
?>
</table>
