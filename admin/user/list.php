<?php
if (!defined('WEB_ROOT')) {
	//exit;
}

$sql = "SELECT user_id, user_name, user_regdate, user_last_login
        FROM tbl_user
		ORDER BY user_name";
$result = dbQuery($sql);

?> 
<p>&nbsp;</p>
<form action="processUser.php?action=addUser" method="post"  name="frmListUser" id="frmListUser">
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr align="center" id="listTableHeader"> 
   <td>Nom d'utilisateur</td>
   <td width="120">Date d'enregistrement</td>
   <td width="120">Dernier login</td>
   <td width="120">Modifier le mot de passe</td>
   <td width="70">Supprimer</td>
  </tr>
<?php
while($row = dbFetchAssoc($result)) {
	extract($row);
	
	if ($i%2) {
		$class = 'row1';
	} else {
		$class = 'row2';
	}
	
	$i += 1;
?>
  <tr class="<?php echo $class; ?>"> 
   <td><?php echo $user_name; ?></td>
   <td width="120" align="center"><?php echo $user_regdate; ?></td>
   <td width="120" align="center"><?php echo $user_last_login; ?></td>
   <td width="120" align="center"><a href="javascript:changePassword(<?php echo $user_id; ?>);">Changer le mot de passe</a></td>
   <td width="70" align="center"><a href="javascript:deleteUser(<?php echo $user_id; ?>);">Supprimer</a></td>
  </tr>
<?php
} // end while

?>
  <tr> 
   <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
   <td colspan="5" align="right"><input name="btnAddUser" type="button" id="btnAddUser" value="Ajouter un utilisateur" class="box" onClick="addUser()"></td>
  </tr>
 </table>
 <p>&nbsp;</p>
</form>
