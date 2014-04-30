<?
//vacances radeff
require_once '../../library/config.php';
require_once '../library/functions.php';
$pageTitle="Vacances";
?>
<html>
<head>
<title><?php echo $pageTitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo WEB_ROOT;?>admin/include/admin.css" rel="stylesheet" type="text/css">
<style>
body {
	text-align: left;
}
input {
	text-align: right;
}
</style>
</head>
<body>
<?

checkUser();

$sql="SELECT * FROM tbl_vacances";
$sql=mysql_query($sql);
if(!$sql) {
	echo "sql error: " .mysql_error(); 
	exit;
}
$id=mysql_result($sql,0,'id');
$vacances=mysql_result($sql,0,'actif');
$vacancesD=mysql_result($sql,0,'date');

echo "<h1>Vacances</h1>";

//variables passées?
if($_GET['date']) {
	
	$vacances=$_GET['actif'];
$vacancesD=$_GET['date'];
	#echo $id ." - " .$vacances ." - " .$vacancesD ."<br>";
	$sql="UPDATE tbl_vacances SET `date` = '" .$vacancesD ."', `actif`=" .$vacances ." WHERE `id`=" .$id;
	#echo $sql ."<br/>";
	$sql=mysql_query($sql);
if(!$sql) {
	echo "sql error: " .mysql_error(); 
	exit;
} else {
	echo "<h1>Mise à jour effectuée avec succès!</h1>";

}
} else {

echo '
<h2><em>Pour rendre actif, mettre le champ Actif = 1; <br />Pour rendre inactif, mettre le champ actif à 0</em></h2>

<form method="GET">
<table border="1">
<tr>
<th>Date</th><th>Actif</th>
</tr>
<tr>
<td>
';
echo "<input type=\"text\" name=\"date\" value=\"".$vacancesD."\">";
echo '
</td>
<td>';
echo "<input type=\"text\" size=\"3\" name=\"actif\" value=\"".$vacances."\">";
echo '
</td>
</tr>
<tr>
<td><input type="reset"></td>
<td><input type="submit"></td>
</tr>
</table>

</form>
';
}
?>
<p><a href="../">Retour</a></p>
</body>
</html>
