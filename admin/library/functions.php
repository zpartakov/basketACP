<?php

/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkUser()
{
	// if the session id is not set, redirect to login page
	if (!isset($_SESSION['plaincart_user_id'])) {
		header('Location: ' . WEB_ROOT . 'admin/login.php');
		exit;
	}
	
	// the user want to logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

/*
	
*/
function doLogin()
{
	// if we found an error save the error message in this variable
	$errorMessage = '';
	
	$userName = $_POST['txtUserName'];
	$password = $_POST['txtPassword'];
	
	// first, make sure the username & password are not empty
	if ($userName == '') {
		$errorMessage = 'You must enter your username';
	} else if ($password == '') {
		$errorMessage = 'You must enter the password';
	} else {
		// check the database and see if the username and password combo do match
		$sql = "SELECT user_id
		        FROM tbl_user 
				WHERE user_name = '$userName' AND user_password = PASSWORD('$password')";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$_SESSION['plaincart_user_id'] = $row['user_id'];
			
			// log the time when the user last login
			$sql = "UPDATE tbl_user 
			        SET user_last_login = NOW() 
					WHERE user_id = '{$row['user_id']}'";
			dbQuery($sql);

			// now that the user is verified we move on to the next page
            // if the user had been in the admin pages before we move to
			// the last page visited
			if (isset($_SESSION['login_return_url'])) {
				header('Location: ' . $_SESSION['login_return_url']);
				exit;
			} else {
				header('Location: index.php');
				exit;
			}
		} else {
			$errorMessage = 'Wrong username or password';
		}		
			
	}
	
	return $errorMessage;
}

/*
	Logout a user
*/
function doLogout()
{
	if (isset($_SESSION['plaincart_user_id'])) {
		unset($_SESSION['plaincart_user_id']);
		#session_unregister('plaincart_user_id');
		session_destroy();
	}
		
	header('Location: login.php');
	exit;
}


/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/

/*function buildCategoryOptions($catId = 0)
{
	$sql = "SELECT cat_id, cat_parent_id, cat_name
			FROM tbl_category
			ORDER BY cat_id";
	$result = dbQuery($sql) or die('Cannot get Product. ' . mysql_error());
	
	$categories = array();
	while($row = dbFetchArray($result)) {
		list($id, $parentId, $name) = $row;
		
		if ($parentId == 0) {
			// we create a new array for each top level categories
			$categories[$id] = array('name' => $name, 'children' => array());
		} else {
			// the child categories are put int the parent category's array
			$categories[$parentId]['children'][] = array('id' => $id, 'name' => $name);	
		}
	}	
	
	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<optgroup label=\"$name\">"; 
		
		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">{$child['name']}</option>\r\n";
		}
		
		$list .= "</optgroup>";
	}
	
	return $list;
}
*/
/*
	If you want to be able to add products to the first level category
	replace the above function with the one below
*/


function buildCategoryOptions($catId = 0)
{
	$sql = "SELECT cat_id, cat_parent_id, cat_name
			FROM tbl_category 
ORDER BY cat_name";
		#	ORDER BY cat_id";
#			

	$result = dbQuery($sql) or die('Cannot get Product. ' . mysql_error());
	
	$categories = array();
	
	

	// build combo box options
	$list = '';
	
	$i=0;
	while($i<mysql_num_rows($result)){
		//first level
	if (mysql_result($result,$i,'cat_parent_id') == 0) {
		$list.= "<option value=\"" .mysql_result($result,$i,'cat_id') ."\">" .utf8_encode(mysql_result($result,$i,'cat_name')) ."</option>";
		
		//sous-categories
		$sql="SELECT cat_id, cat_parent_id, cat_name
			FROM tbl_category WHERE cat_parent_id = " .mysql_result($result,$i,'cat_id') ." ORDER BY cat_name";
		#do and check sql
		$sql=mysql_query($sql);
		if(!$sql) {
			echo "SQL error: " .mysql_error(); exit;
		}
		if(mysql_num_rows($sql)>1) {
		$i2=0;
		while($i2<mysql_num_rows($sql)){
		$list.= "<option style=\"background-color: #B6F7B6\" value=\"" .mysql_result($sql,$i2,'cat_id') ."\">&nbsp;&nbsp;" .utf8_encode(mysql_result($sql,$i2,'cat_name')) ."</option>";
			$i2++;
			}
		}
		
	}
		
		$i++;
		}
	
	return $list;
}


/*
	Create a thumbnail of $srcFile and save it to $destFile.
	The thumbnail will be $width pixels.
*/
function createThumbnail($srcFile, $destFile, $width, $quality = 75)
{
	$thumbnail = '';
	
	if (file_exists($srcFile)  && isset($destFile))
	{
		$size        = getimagesize($srcFile);
		$w           = number_format($width, 0, ',', '');
		$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
		
		$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
	}
	
	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}

/*
	Copy an image to a destination file. The destination
	image size will be $w X $h pixels
*/
function copyImage($srcFile, $destFile, $w, $h, $quality = 75)
{
    $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);

    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
    {
       $destFile  = substr_replace($destFile, 'jpg', -3);
       $dest      = imagecreatetruecolor($w, $h);
       #imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
       $dest = imagecreatetruecolor($w, $h);
       #imageantialias($dest, TRUE);
    } else {
      return false;
    }

    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
    return $destFile;

}

/*
	Create the paging links
*/
function getPagingNav($sql, $pageNum, $rowsPerPage, $queryString = '')
{
	$result  = mysql_query($sql) or die('Error, query failed. ' . mysql_error());
	$row     = mysql_fetch_array($result, MYSQL_ASSOC);
	$numrows = $row['numrows'];
	
	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);
	
	$self = $_SERVER['PHP_SELF'];
	
	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link
	
	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?page=$page{$queryString}\">[Prev]</a> ";
	
		$first = " <a href=\"$self?page=1{$queryString}\">[First Page]</a> ";
	}
	else
	{
		$prev  = ' [Prev] ';       // we're on page one, don't enable 'previous' link
		$first = ' [First Page] '; // nor 'first page' link
	}
	
	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?page=$page{$queryString}\">[Next]</a> ";
	
		$last = " <a href=\"$self?page=$maxPage{$queryString}{$queryString}\">[Last Page]</a> ";
	}
	else
	{
		$next = ' [Next] ';      // we're on the last page, don't enable 'next' link
		$last = ' [Last Page] '; // nor 'last page' link
	}
	
	// return the page navigation link
	return $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last; 
}


function decoder($text) 
{
	$text=utf8_encode($text);
	#$text=nl2br($text);
	#echo $text;
	return $text;
}
function encoder($text)
{
	$text=trim($text);
	$text=utf8_decode($text);
	return $text;
}
function formater_nombre($x) 
{
$x=number_format($x, 2, ".", "'");
return $x;
}

function formater_nombre_entier($x) 
{
$x=number_format($x, 0, "", "'");
return $x;
}

######### CALCUL DU DELAI DE LIVRAISON #####
/* fonction pour calculer la date de livraison
 * source: http://www.phpindex.com/index.php/2000/10/24/359-calcul-d-une-date-a-partir-du-numero-de-la-semaine
 * */
function get_lundi_dimanche_from_week($week,$year)
 {
 if(strftime("%W",mktime(0,0,0,01,01,$year))==1) { //1e semaine de l'annee
             //echo "<br>test: 1e semaine de l'annee<br>"; exit;       
 	$mon_mktime = mktime(0,0,0,01,(01+(($week-1)*7)),$year);
 } else {
   $mon_mktime = mktime(0,0,0,01,((($week)*7)),$year);
   //echo "<br>test: 1e semaine de l'annee: non<br>"; exit;       
   
 } 

if(date("w",$mon_mktime)==0) { //dimanche
	$mon_mktime=$mon_mktime+60*60*24;
//     echo "<br>test: on est un dimanche<br>"; exit;

 }

 if(date("w",$mon_mktime)>1) {//ajout un jour si après dimanche     
  //   echo "<br>test: ajout un jour si après dimanche<br>"; exit;
       $decalage = ((date("w",$mon_mktime)-1)*60*60*24);
     //echo "<br>test: on est un dimanche<br>";
 } else{
 	$decalage = ((date("w",$mon_mktime)+6)*60*60*24);
 }

 //echo   date("w",$mon_mktime); exit;
  
 $lundi = $mon_mktime - $decalage;
 //echo date("D - d/m/Y",$lundi); exit;
     $dimanche = $lundi + (6*60*60*24);
     $jeudi = $lundi + (3*60*60*24);      
     $jeudi2=     $jeudi + (7*60*60*24);
     $jeudi3=     $jeudi2 + (7*60*60*24);
     $jeudi4=     $jeudi3 + (7*60*60*24);
     $jeudi5=     $jeudi4 + (7*60*60*24);
     $jeudi6=     $jeudi5 + (7*60*60*24);
     $jeudi7=     $jeudi6 + (7*60*60*24);
     $jeudi8=     $jeudi7 + (7*60*60*24);

     return array(date("D - d/m/Y",$lundi), date("d-m-Y",$jeudi), date("d-m-Y",$jeudi2),date("d-m-Y",$jeudi3),date("d-m-Y",$jeudi4),date("d-m-Y",$jeudi5),date("d-m-Y",$jeudi6),date("d-m-Y",$jeudi7),date("d-m-Y",$jeudi8));

 }
 
function nettoie($x) {
	$x=addslashes($x);
	return $x;
}



?>
