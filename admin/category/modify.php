<?php
if (!defined('WEB_ROOT')) {
	exit;
}

// make sure a category id exists
if (isset($_GET['catId']) && (int)$_GET['catId'] > 0) {
	$catId = (int)$_GET['catId'];
} else {
	header('Location:index.php');
}	
	
$sql = "SELECT cat_id, cat_name, cat_description, cat_image
		FROM tbl_category
		WHERE cat_id = $catId";
$result = dbQuery($sql);
$row = dbFetchAssoc($result);
extract($row);

?>
<p>&nbsp;</p>
<form action="processCategory.php?action=modify&catId=<?php echo $catId; ?>" method="post" enctype="multipart/form-data" name="frmCategory" id="frmCategory">
 <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable">
  <tr> 
   <td width="150" class="label">Nom de la catégorie</td>
   <td class="content"><input name="txtName" type="text" class="box" id="txtName" value="<?php 
   echo decoder($cat_name); 
   ?>" size="30" maxlength="50"></td>
  </tr>
  <tr> 
   <td width="150" class="label">Description</td>
   <td class="content"> <textarea name="mtxDescription" cols="50" rows="4" class="box" id="mtxDescription"><?php echo decoder($cat_description); ?></textarea></td>
  </tr>
  <tr> 
   <td width="150" class="label">Image</td>
   <td class="content"> 
    <input name="fleImage" type="file" id="fleImage" class="box">
<?php
	if ($cat_image != '') {
?>
    <br>
    <img src="<?php echo WEB_ROOT . CATEGORY_IMAGE_DIR . $cat_image; ?>"> &nbsp;&nbsp;<a href="javascript:deleteImage(<?php echo $cat_id; ?>);">Effacer l'image</a> 
    <?php
	}
?>
   </td>
  </tr>
<tr>
<td>Disponibilité</td>
<td>
<?
$dispo=preg_match("/^<!-- nondisp -->/",$cat_name); 
if($dispo==1) {
	$dispo="";
	} else {
	$dispo=" checked";
	}
	echo "<input type=\"checkbox\" id=\"dispo\" name=\"dispo\"" .$dispo ."\" onClick=\"disponibilite();\">";
?></td></tr>
 </table>
 <p align="center"> 
  <input name="btnModify" type="button" id="btnModify" value="Enregistrer" onClick="checkCategoryForm();" class="box">
  &nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="Annuler" onClick="window.location.href='index.php';" class="box">
 </p>
</form>
