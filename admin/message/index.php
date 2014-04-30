<?php
require_once '../../library/config.php';
require_once './../library/functions.php';
#require_once '../include/template.php';

$titre=$_GET['titre'];

if(!isset($titre)){
$sqlc="SELECT * FROM tbl_titre";

#do and check sql
$sqlc=mysql_query($sqlc);
if(!$sqlc) {
	echo "SQL error: " .mysql_error(); exit;
}

 #	echo "<h1>" .mysql_result($sqlc,0,'titre') ."</h1>";
} else {
#	$titre=addslashes($titre);
	$titre=utf8_encode(nl2br($titre));
	
	#echo "set titre to: " .$titre;
	$sqlc="UPDATE tbl_titre SET titre='" .$titre ."' WHERE id=1";

#do and check sql
$sqlc=mysql_query($sqlc);
if(!$sqlc) {
	echo "SQL error: " .mysql_error(); exit;
}
header("Location: ../message");
}
?>
<h1>Modifier le message de la page d'accueil</h1>
<form methode="GET">
<textarea name="titre" cols="60" rows="15"><? echo utf8_decode(mysql_result($sqlc,0,'titre')); ?></textarea>
<br>
<input type="reset">
<input type="submit" value="Modifier">
</form>
<br>
<a href="..">Retour administration des commandes</a>
