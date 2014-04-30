<?php
if (!defined('WEB_ROOT')) {
	exit;
}

// make sure a product id exists
if (isset($_GET['productId']) && $_GET['productId'] > 0) {
	$productId = $_GET['productId'];
} else {
	// redirect to index.php if product id is not present
	header('Location: index.php');
}

// get product info
$sql = "SELECT pd.cat_id, pd_name, pd_description, pd_price, pd_qty, pd_image, pd_thumbnail
        FROM tbl_product pd, tbl_category cat
		WHERE pd.pd_id = $productId AND pd.cat_id = cat.cat_id";
$result = mysql_query($sql) or die('Produit introuvable. ' . mysql_error());
$row    = mysql_fetch_assoc($result);
extract($row);

// get category list
/*
$sql = "SELECT cat_id, cat_parent_id, cat_name
        FROM tbl_category
		ORDER BY cat_id";
$result = dbQuery($sql) or die('Produit introuvable. ' . mysql_error());

$categories = array();
while($row = dbFetchArray($result)) {
	list($id, $parentId, $name) = $row;
	
	if ($parentId == 0) {
		$categories[$id] = array('name' => $name, 'children' => array());
	} else {
		$categories[$parentId]['children'][] = array('id' => $id, 'name' => $name);	
	}
}	

#echo '<pre>'; print_r($categories); echo '</pre>'; exit;

// build combo box options
$list = '';
foreach ($categories as $key => $value) {
	$name     = $value['name'];
	$children = $value['children'];
	
	$list .= "<optgroup label=\"$name\">"; 
	
	foreach ($children as $child) {
		$list .= "<option value=\"{$child['id']}\"";
		
		if ($child['id'] == $cat_id) {
			$list .= " selected";
		}
		$list .= ">{$child['name']}</option>";
	}
	
	$list .= "</optgroup>";
}
* */
// build combo box options
$list = '';
$sql = "SELECT cat_id, cat_parent_id, cat_name
        FROM tbl_category
		ORDER BY cat_name";
$result = dbQuery($sql) or die('Produit introuvable. ' . mysql_error());
#do and check sql
$sql=mysql_query($sql);
if(!$sql) {
	echo "SQL error: " .mysql_error(); exit;
}

$i=0;

while($i<mysql_num_rows($sql)){
	$list.= "<option value=\"" .mysql_result($sql,$i,'cat_id') ."\"";
	if(mysql_result($sql,$i,'cat_id')==$cat_id) {
		$list.=" selected";
	}
	$list.=">"  .decoder(mysql_result($sql,$i,'cat_name')) ."</option>";
	$i++;
	}

?> 
<form action="processProduct.php?action=modifyProduct&productId=<?php echo $productId; ?>" method="post" enctype="multipart/form-data" name="frmAddProduct" id="frmAddProduct">
<p align="center" class="formTitle">Modifier le produit</p>
 
 <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable">
  <tr> 
   <td width="150" class="label">Catégorie</td>
   <td class="content"> <select name="cboCategory" id="cboCategory" class="box">
     <option value="">-- Choose Category --</option>
<?php
	echo $list;
?>	 
    </select></td>
  </tr>
  <tr> 
   <td width="150" class="label">Nom du produit</td>
   <td class="content"> <input name="txtName" type="text" class="box" id="txtName" value="<?php echo decoder($pd_name); ?>" size="50" maxlength="100"></td>
  </tr>
  <tr> 
   <td width="150" class="label">Description</td>
   <td class="content"> <textarea name="mtxDescription" cols="70" rows="10" class="box" id="mtxDescription"><?php echo decoder($pd_description); ?></textarea></td>
  </tr>
  <tr> 
   <td width="150" class="label">Prix</td>
   <td class="content"><input name="txtPrice" type="text" class="box" id="txtPrice" value="<?php echo $pd_price; ?>" size="10" maxlength="7"> </td>
  </tr>
  <tr> 
   <td width="150" class="label">Disponibilité</td>
   <td class="content">
   <label>oui</label>    <input name="txtQty" type="radio" id="txtQty" value="99999"
   <?
   if($pd_qty>10) {
	   echo " checked";
   }
   ?>
   
   >
   <label>non</label>    <input name="txtQty" type="radio" id="txtQty" value="0"
   
      <?
   if($pd_qty<11) {
	   echo " checked";
   }
   ?>
   >

    </td>
  </tr>
  <tr> 
   <td width="150" class="label">Image</td>
   <td class="content"> <input name="fleImage" type="file" id="fleImage" class="box">
<?php
	if ($pd_thumbnail != '') {
?>
    <br>
    <img src="<?php echo WEB_ROOT . PRODUCT_IMAGE_DIR . $pd_thumbnail; ?>"> &nbsp;&nbsp;<a href="javascript:deleteImage(<?php echo $productId; ?>);">Delete 
    Image</a> 
    <?php
	}
?>    
    </td>
  </tr>
 </table>
 <p align="center"> 
  <input name="btnModifyProduct" type="button" id="btnModifyProduct" value="Enregistrer" onClick="checkAddProductForm();" class="box">
  &nbsp;&nbsp;
<!--  <input name="btnCancel" type="button" id="btnCancel" value="Annuler" onClick="window.location.href='index.php';" class="box">-->
  <input name="btnCancel" type="button" id="btnCancel" value="Annuler" onClick="window.location.href='index.php?catId=<? echo $cat_id;?>';" class="box">
    
 </p>
</form>
