<?php


//Error Reporting 

ini_set('display_errors', 'On');
error_reporting(E_ALL);


include 'admin/connect.php';

$sessionUser = '';
if(isset($_SESSION['user'])) {
	$sessionUser = $_SESSION['user'];
}



// Routes

$tpl   = 'includes/templates/';			         // Template Directory Path
$lang  = 'includes/languages/';                  // Language Directory   
$func  = 'includes/functions/';                  // Include Functions Directory
$css   = "layout/css/";         		    	 // CSS Directory Path
$js    = "layout/js/";          				 // Js Directory Path



// Include The Important Files

include $func . 'function.php';	
include $lang . 'english.php';
include $tpl . "header.php";




