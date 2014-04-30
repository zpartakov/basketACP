<?
require_once 'library/config.php';
$pageTitle = $shopConfig['name'] .' - ' .SITE_NAME .' : Enregistrement';
require_once 'include/header.php';
require_once 'admin/library/functions.php';


//submit form
$action=$_POST['soumettre'];
$jid=$_GET['jid'];
if(isset($action)) {
	
	/*
Prenom
Nom
Adresse
Telephone
Ville
CodePostal
Email
pdd_id
	*/

//encode before insert into db
$noreguser=encoder($_POST['noreguser']);
$Prenom=encoder($_POST['Prenom']);
$Nom=encoder($_POST['Nom']);
$Adresse=encoder($_POST['Adresse']);
$Telephone=encoder($_POST['Telephone']);
$Ville=encoder($_POST['Ville']);
$CodePostal=encoder($_POST['CodePostal']);
$Email=encoder($_POST['Email']);
$pdd_id=encoder($_POST['pdd_id']);

//mandatory fields control after (before = js)
if(strlen($Prenom)<1||strlen($Nom)<1||strlen($Adresse)<1||strlen($Telephone)<1||strlen($Ville)<1||strlen($CodePostal)<1||strlen($Email)<1||strlen($pdd_id)<1) {
	echo "<a href=\"javascript:history.go(-1);\">Certains champs n'ont pas été renseigné, merci de compléter</a>";
} else { //all ok let's insert in the db
$suid=$_SESSION['uid'];

	//insert new user
if($noreguser=="1") {
$sql="INSERT INTO `tbl_customers` (`PersNo`, `PersPDDDistrNo`, `PersNom`, `PersPrenom`, `PersAdresse`, `PersTelephone`, `PersNPA`, `PersLocalite`, `PersAdresseEmail`, `jos_user_id`) VALUES (
'', 
$pdd_id,
'$Nom',
'$Prenom',
'$Adresse',
'$Telephone',
'$CodePostal',
'$Ville',
'$Email',
'$suid'
);";
} else {
//update existing user
	$sql="UPDATE `tbl_customers` SET `PersPDDDistrNo` = '" .$pdd_id ."', `PersNom` = '" .$Nom."', `PersPrenom` = '" .$Prenom."', `PersAdresse` = '" .$Adresse."', `PersTelephone` = '" .$Telephone."', `PersNPA` = '" .$CodePostal."', `PersLocalite` = '" .$Ville."', `PersAdresseEmail` = '" .$Email."' WHERE `tbl_customers`.`jos_user_id` = '".$suid."'";
}
$to="fradeff@akademia.ch";
$to="commandes@cocagne.ch";
$obj="modification/insertion adresse commandes cocagne";
$couriel=mail($to, $obj , $sql);
	#echo $sql; exit;
	$sql=mysql_query($sql);
	if(!$sql) {
		 echo "SQL error1: " .mysql_error(); exit;
		 } else {
			 //redirect
			 //

		 }
	echo '<meta http-equiv="refresh" content="0;URL=./">';exit;

}

}





	//"id";"Prenom";"Nom";"Adresse";"Adresse2";"Telephone";"Canton";"Ville";"CodePostal";"pdd_id";"jos_user_id"
	//on interroge la base des clients qui commandent des produits
$sql1="
SELECT * FROM tbl_customers, jos_pdds 
WHERE 
jos_user_id=".$_SESSION['uid'] ." 
AND 
tbl_customers.PersPDDDistrNo=jos_pdds.id
";
#do and check sql
$sql=mysql_query($sql1);
if(!$sql) {
	echo "SQL error2: <br>" .$sql1 ."<br>".mysql_error(); exit;
}

if(mysql_num_rows($sql)==0) { //the user is not registred
$noreguser=1;
} else { //registration ok
$noreguser=0;
//mysql values
$txtShippingFirstName=utf8_encode(mysql_result($sql,0,"PersPrenom"));
$txtShippingLastName=utf8_encode(mysql_result($sql,0,"PersNom"));
$txtShippingAddress1=utf8_encode(mysql_result($sql,0,"PersAdresse"));
$txtShippingPhone=utf8_encode(mysql_result($sql,0,"PersTelephone"));
$txtShippingCity=utf8_encode(mysql_result($sql,0,"PersLocalite"));
$txtShippingPostalCode=utf8_encode(mysql_result($sql,0,"PersNPA"));
#$txtShippingState=utf8_encode(mysql_result($sql,0,"PersAdresseEmail")); //todo: replace this field with new email field
$txtEmail=utf8_encode(mysql_result($sql,0,"PersAdresseEmail")); 	
$PDDTexte=utf8_encode(mysql_result($sql,0,"PDDTexte")); 	
$PDDINo=utf8_encode(mysql_result($sql,0,"jos_pdds.id")); 	
}


//request on pdds
$pdd="SELECT * FROM jos_pdds ORDER BY PDDTexte";
#do and check sql
$pdd=mysql_query($pdd);
if(!$pdd) {
	echo "SQL error pdds: " .mysql_error(); exit;
}

?>
<style>
td {
	padding: 5px;
	}
</style>

<form name="inscription" method="post">
<input type="hidden" name="noreguser" value="<? echo $noreguser; ?>">

<table align="center">

<tr><td colspan="2"><?	echo "<h1>" .decoder($pageTitle) ."</h1>";?></td></tr>
<tr><td colspan="2"><em>Les champs marqués d'une astérisque <sup>*</sup> sont obligatoires</em></td></tr>
	<tr>
		<td colspan="2">
		<!--"id";"Prenom";"Nom";"Adresse";"Adresse2";"Telephone";"Canton";"Ville";"CodePostal";"pdd_id";"jos_user_id"-->
 </td>
	</tr>
	
<?
/*extraction des donnees personnelles dans la table des utilisateurs joomla*/
$juser="SELECT * FROM jos_users WHERE id=".$jid;
#echo $juser;
$juser=mysql_query($juser);
if(!$juser) {
	echo "SQL error jid: " .mysql_error(); exit;
}
$txtEmail=mysql_result($juser,0,'email');
?>	
	<tr><td class="label">Prénom<sup>*</sup></label></td><td><input class="donnees" type="text" name="Prenom" value="<? echo $txtShippingFirstName; ?>"></td></tr>
	<tr><td class="labelpair">Nom<sup>*</sup></td><td><input class="donnees" type="text" name="Nom" value="<? echo $txtShippingLastName; ?>"></td></tr>
	<tr><td class="label">Adresse<sup>*</sup></td><td>
	<input class="donnees" type="text" name="Adresse" value="<? echo $txtShippingAddress1; ?>">
</td></tr>	
	<!--<tr><td class="labelpair">Adresse 2</td><td><textarea class="donnees" cols="20" rows="3" name="Adresse2"></textarea></td></tr>-->
	<tr><td class="labelpair">Téléphone</td><td><input type="text" name="Telephone" value="<? echo $txtShippingPhone; ?>"></td></tr>
	<tr><td class="labelpair">Code Postal<sup>*</sup></td><td><input type="text" name="CodePostal" value="<? echo $txtShippingPostalCode; ?>"></td></tr>
		<tr><td class="label">Commune<sup>*</sup></td><td><input type="text" name="Ville" value="<? echo $txtShippingCity; ?>"></td></tr>
	<tr><td class="label">Email<sup>*</sup></td><td><input type="text" name="Email" value="<? echo $txtEmail; ?>"></td></tr>
	<tr><td class="labelpair">Point de distribution <sup>*</sup></td><td>            <select name="pdd_id">
            <option value=""
            <?
            if(strlen($PDDINo)<1) {
				echo " selected";
			}
            ?>
            >--- modifier le point de distribution ---</option>

			<?
			$ipdd=0;
			while($ipdd<mysql_num_rows($pdd)){
				echo "<option value=\"" .mysql_result($pdd,$ipdd,'id') ."\"";
					if($PDDINo==mysql_result($pdd,$ipdd,'id')) {
						echo " selected";
					}
				echo ">" .trim(utf8_encode(mysql_result($pdd,$ipdd,'PDDTexte'))) ."</option>\n";
				$ipdd++;
				}
			?>
            </select></td></tr>
	<!--<tr><td class="labelpair">jos_user_id</td><td><input type="text" name="jos_user_id"></td></tr>-->
	<tr>
		<td style="text-align: center">
<!--		<input type="reset" value="Annuler" onClick="window.location.href='<? echo HOME_SITE; ?>';">-->
 		<input type="reset" value="Annuler" onClick="window.location.href='register.php';">
 </td>
		<td class="label" style="text-align: center;"><input type="submit" name="soumettre" value="S'enregistrer">
 </td>
	</tr>


</table>	
</form>

<?
require_once 'include/footer.php';

?>
