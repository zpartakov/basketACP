<?php
//after successful command, send an email

echo "<!--- test : " .$_SESSION['shop_return_url'] ."-->";

$emailcommande="commandes@cocagne.ch"; //prod comment on test
#$emailcommande="fradeff@gmail.com"; //test uncomment on prod

require_once 'library/config.php';
#require_once 'library/cart-functions.php';
#require_once 'library/checkout-functions.php';

// if no order id defined in the session
// redirect to main page
if (!isset($_SESSION['orderId'])) {
	header('Location: ' . WEB_ROOT);
	exit;
}

$pageTitle   = 'Commande effectu&eacute;e avec succ&egrave;s';
#require_once 'include/header.php';



#############
//send email to the user begin
$Message=""; $votrecommande=""; $produits="";

$sql = "SELECT * 
			FROM tbl_order o, tbl_order_item oi, tbl_customers c, tbl_product p 
			WHERE o.od_id = '" .$_SESSION['orderId'] 
			."' AND oi.od_id = o.od_id
			 AND c.jos_user_id  = o.od_shipping_user 
			 AND p.pd_id=oi.pd_id";
		#echo $sql; exit;
		$result = dbQuery($sql);
		$remarques=mysql_query($sql);
		$PersPDDDistrNo=mysql_result($remarques,0,'PersPDDDistrNo');
		$remarques=mysql_result($remarques,0,'od_memo');
#echo "PDD: " .$PersPDDDistrNo; exit;
		while ($row = dbFetchAssoc($result)) {
			if ($row['pd_thumbnail']) {
				$row['pd_thumbnail'] = WEB_ROOT . 'images/product/' . $row['pd_thumbnail'];
			} else {
				$row['pd_thumbnail'] = WEB_ROOT . 'images/no-image-small.png';
			}
			$cartContent[] = $row;
		}
		
	$numItem = count($cartContent);
	$subTotal = 0;

	for ($i = 0; $i < $numItem; $i++) {
		extract($cartContent[$i]);
		$productUrl = "index.php?c=$cat_id&p=$pd_id";
		$subTotal += $pd_price * $ct_qty;
		$date_livraison=explode("-",$date_livraison);
		$date_livraison=$date_livraison[2]."-".$date_livraison[1]."-".$date_livraison[0];

$produits.='
	 <tr class="Stil1">	
	  <td>'.utf8_encode($pd_name) .'</td>
	  
<td align="right">' .$pd_price .'</td>	  
<td align="right">' .$od_qty .'</td>	  
	  <td>' .$date_livraison .'</td>

<td align="right">' .displayAmount($pd_price * $od_qty) .'</td>	  
	 </tr>
';

$subTotal=$subTotal+($pd_price * $od_qty);
$adresse=$PersAdresse ."<br>" .$PersNPA ." " .$PersLocalite;
$tel=$PersTelephone;
$email=$PersAdresseEmail;
$date=$od_date;
#$date_livraison=$date_livraison;
	}

//date french readable
$date=preg_replace("/ .*/","",$date);
$date=explode("-",$date);
$date=$date[2]."-".$date[1]."-".$date[0];

//date livraison french readable

$date_livraison=preg_replace("/ .*/","",$date_livraison);
$date_livraison=explode("-",$date_livraison);
$date_livraison=$date_livraison[2]."-".$date_livraison[1]."-".$date_livraison[0];

$date_livraison2=explode("-",$date_livraison);
$date_livraison2=$date_livraison2[2]."-".$date_livraison2[1]."-".$date_livraison2[0];

//vacances radeff
$sql="SELECT * FROM tbl_vacances";
$sql=mysql_query($sql);
if(!$sql) {
	echo "sql error: " .mysql_error(); 
	exit;
}
$vacances=mysql_result($sql,0,'actif');
if($vacances=="1") {
	$date_livraison="jeudi " .mysql_result($sql,0,'date');
}

//calcul point de livraison
$pdd="SELECT * FROM jos_pdds WHERE id = '" .$PersPDDDistrNo ."'";
$pdd=mysql_query("$pdd");
$pdd=mysql_result($pdd,0,'PDDTexte'); 
#echo $pdd; exit;

############ BEGIN HTML MAIL ##############
$votrecommande='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
	<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Bon de commande</title>
<style>
* {
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 12px;
} 
a:link, a:active, a:visited {
	color:#0000CC
} 

img {
	border:0
} 

pre {
white-space: pre; white-space: -moz-pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; width: 800px; overflow: auto;
}

</style>
</head>
<body>

<table width="100%" align="left" border="0" cellspacing="0" cellpadding="10">
  <tr valign="top"> 
    <td width="53%" align="left" class="Stil1">'
    .VENDOR
    .'</td>
    <td width="47%" align="right"><img src="' .LOGO .'" alt="vendor_image" border="0"></td>
  

<!-- ####### BEGIN DATAS ###### --> 
 
    <!-- ######## BEGIN ORDER DETAIL  ######## --> 


  <tr bgcolor="#CCCCCC" class="sectiontableheader"> 
    <td colspan="2" class="Stil2"><b>Votre commande</b></td>
  </tr>
  <tr class="Stil1"> 
    <td>Numéro de commande:</td><td>' .$od_id .'</td>
  </tr>
   
  <tr class="Stil1"> 
    <td>Date de commande:</td><td>' .$date .'</td>
  </tr>  <tr class="Stil1"> 
    <td>Date de livraison:</td><td>' .$date_livraison2 .'</td>
  </tr>
<tr><td colspan="2" >Voici votre commande du ' .$date .'</td></tr>
<tr bgcolor="#CCCCCC" class="sectiontableheader"><th>Article</th><th>Prix</th><th>Nb</th><th>Date livr.</th><th>Prix</th></tr>';	
  

  
  #  <tr bgcolor="#CCCCCC" class="sectiontableheader"><th>Objet</th><th>Prix unitaire</th><th>Quantité</th><th>Total</th></tr>
	$votrecommande.= $produits;
	$votrecommande.="<tr class=\"Stil1\"><td colspan=\"4\"><strong>Prix de la commande</strong></td><td><strong>" .displayAmount($subTotal)."</strong></td></tr>";
		$votrecommande.= "
	</table>";
			
if(strlen($remarques)>0) {
			$votrecommande.= "<p>Remarques: " .$remarques ."</p><br>";
}
$votrecommande.= "<p>Si vous ne recevez pas d'autres messages, les articles seront livrés aux dates demandées.</p>";
	
$votrecommande.= '
<!-- begin customer information --> 
  <!-- begin 2 column bill-ship to --> 
  <table>
  <tr class="sectiontableheader">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr bgcolor="#CCCCCC" class="sectiontableheader"> 
    <td colspan="2"><b class="Stil2">Vos coordonnées</b></td>
  </tr> 		
 <tr class="Stil1"> 
	          <td>'   .ucfirst(utf8_encode($PersPrenom)) .' ' .strtoupper(utf8_encode($PersNom)).'</td>
	        </tr>

	       				       
	   <tr class="Stil1"> 
	          <td>' .utf8_encode($adresse) .'</td>
	        </tr>
	       				       
	      <tr class="Stil1"> 
	          <td>' .$tel .'</td>
	        </tr>
      	        <tr class="Stil1"> 
	          <td>Point de distribution : ' .$pdd .'</td>
	        </tr>
	       				       

  <!-- end customer information --> 
';
	$votrecommande.= "</table>";

	//CAS PARTICULIERS
	//oeufs
	if(preg_match("/oeufs/",$produits)){
		$votrecommande.= "<h1>Commande d'oeufs: pour des raisons de gestion, nous ne prenons pas de commandes \"juste pour une fois\". Précisez dans le message \"Par semaine\" ou \"Tous les 15 jours\". Merci.</h1>";
		
		} 
		
	//pains
	if(preg_match("/pain/",$produits)||preg_match("/Baguette/",$produits)) {
		
		$votrecommande.= "<h1>Commande de pain: nous ne prenons pas de commandes \"juste pour une fois\". Vous pouvez aussi alterner une fois l'un une fois l'autre, mais quatre sortes de pain au maximum.</h1>";
	}
	
	######## END ORDER DETAIL  ########
	
#### PRINT IT #######
echo $votrecommande;
$message=$votrecommande; //make a copy for the admin email
$votrecommande=$votrecommande ."</body></html>";
//send the mail	
$Sujet = "Commande n° " .$od_id ." du " .$date ;
#echo $Sujet;
$From  = "From: " .$emailcommande ."\n";
$From .= "MIME-version: 1.0\n";
$From .= "Content-type: text/html; charset= utf-8\n";
$mailuser=mail($email, $Sujet, $votrecommande, $From);
 if(!$mailuser) {
		echo "Il y a eu un problème lors de l'envoi du mail à votre adresse, merci de prendre contact avec commandes@cocagne.ch"; exit;
	}
#############
//send email to the user end

// send notification email to the admin
if ($shopConfig['sendOrderEmail'] == 'y') {
	$subject = "[Nouvelle commande] " . $_SESSION['orderId'];
	$From  = "From: " .$email ."\n";
$From .= "MIME-version: 1.0\n";
$From .= "Content-type: text/html; charset= utf-8\n";
	$email   = $shopConfig['email'];
	#$email=$emailcommande; //tests
	
	$message .= "<br><hr><br>Vous avez une nouvelle commande, voir les détails sur \n http://" . $_SERVER['HTTP_HOST'] . WEB_ROOT . 'admin/order/index.php?view=detail&oid=' . $_SESSION['orderId'] ;
	$message=$message ."</body></html>";

	$mailadmin=mail($email, $subject, $message, $From);
	if(!$mailadmin) {
		echo "Il y a eu un problème lors de l'envoi du mail à l'administrateur, merci de prendre contact avec " .$emailcommande; exit;
	}
}
######################
//uncomment next line to continue
#exit;
unset($_SESSION['orderId']);
?>
<p>&nbsp;</p>
<br>Merci de votre commande.
<br><p><strong>Les Jardins de Cocagne</strong></p>
<table width="500" border="0" align="left" cellpadding="1" cellspacing="0">
   <tr> 
      <td align="left" valign="top" bgcolor="#333333"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
               <td align="left" bgcolor="#EEEEEE"> <p>&nbsp;</p>
                        <p> <ul><li><a href="index.php">Continuer vos commandes</a></li><li><a href="../">Revenir au site</a></li></ul></p>
                  <p>&nbsp;</p></td>
            </tr>
         </table></td>
   </tr>
</table>
<br>
<br>
<?php
require_once 'include/footer.php';
?>
