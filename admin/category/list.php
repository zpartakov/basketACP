<?php
if (!defined('WEB_ROOT')) {
	exit;
}

if (isset($_GET['catId']) && (int)$_GET['catId'] >= 0) {
	$catId = (int)$_GET['catId'];
	$queryString = "&catId=$catId";
} else {
	$catId = 0;
	$queryString = '';
}
	
// for paging
// how many rows to show per page
$rowsPerPage = 100;

$sql = "SELECT cat_id, cat_parent_id, cat_name, cat_description, cat_image
        FROM tbl_category
		WHERE cat_parent_id = $catId
		ORDER BY cat_name";
$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
$pagingLink = getPagingLink($sql, $rowsPerPage);
?>
<p>&nbsp;</p>
<form action="processCategory.php?action=addCategory" method="post"  name="frmListCategory" id="frmListCategory">
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr align="center" id="listTableHeader"> 
   <th>Nom de la catégorie</th>
   <th>Description</th>
   <th width="75">Image</th>
   <th width="75">Modifier</th>
   <th width="75">Supprimer</th>
   <th width="75">Ajout sous-groupe</th>
   <th>Disponibilité</th>
  </tr>
  <?php
$cat_parent_id = 0;
if (dbNumRows($result) > 0) {
	$i = 0;
	
	while($row = dbFetchAssoc($result)) {
		extract($row);
		
		if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
		
		$i += 1;
		
		if ($cat_parent_id == 0) {
			#$cat_name = "<a href=\"index.php?catId=$cat_id\">$cat_name</a>";
		}
		
		if ($cat_image) {
			$cat_image = WEB_ROOT . 'images/category/' . $cat_image;
		} else {
			$cat_image = WEB_ROOT . 'images/no-image-small.png';
		}		
?>
  <tr class="<?php echo $class; ?>"> 
   <td><a href="javascript:modifyCategory(<?php echo $cat_id; ?>);"><?php 
   $cat_name=decoder($cat_name);
   echo $cat_name; 
   ?></a>
   <?
   /*sous-categories*/
   
   #do and check sql
   $sql="SELECT * FROM tbl_category
		WHERE cat_parent_id = $cat_id
		ORDER BY cat_name";
   $sql=mysql_query($sql);
   if(!$sql) {
	   echo "SQL error: " .mysql_error(); exit;
   }
   if(mysql_num_rows($sql)>0) {
	   echo "<br /><br /><span style='padding-left: 15px;'><strong>> Sous-catégories:</strong></span><ul>";
   $i=0;
   while($i<mysql_num_rows($sql)){
	   echo '<li><a href="javascript:modifyCategory('.mysql_result($sql,$i,'cat_id').');">' .mysql_result($sql,$i,'cat_name') .'</a></li>'; 
	   $i++;
	   }
	echo "</ul>";	   
   }
   ?>
   
   
   </td>
   <td><?php echo substr(decoder($cat_description),0,20)."..."; ?></td>
   <td width="75" align="center"><img src="<?php echo $cat_image; ?>"></td>
   <td width="75" align="center"><a href="javascript:modifyCategory(<?php echo $cat_id; ?>);">Modifier</a></td>
   <td width="75" align="center"><a href="javascript:deleteCategory(<?php echo $cat_id; ?>);">Supprimer</a></td>
   <td>
   	<?	if ($cat_parent_id == 0) {
			echo "<a href=\"index.php?catId=" .$cat_id ."\">Ajout sous-groupe</a>";
		}
		?>
</td>
<td align="center">
<?
$dispo=preg_match("/^<!-- nondisp -->/",$cat_name); 
if($dispo==1) {echo "0";}
?>
</td>
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
   <td colspan="5" align="center">Actuellement pas de catégories</td>
  </tr>
  <?php
}
?>
  <tr> 
   <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
   <td colspan="5" align="right"> <input name="btnAddCategory" type="button" id="btnAddCategory" value="Ajouter une catégorie" class="box" onClick="addCategory(<?php echo $catId; ?>)"> 
   </td>
  </tr>
 </table>
 <p>&nbsp;</p>
</form>
