<?php
require_once '../library/config.php';
require_once './library/functions.php';

checkUser();

$content = 'main.php';

$pageTitle = 'Administration des commandes';
$script = array();

require_once 'include/template.php';
?>
