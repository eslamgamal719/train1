<?php


include 'connect.php';



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


 //Include All Nav Bar In All Pages Except Any Page That Contains $noNavBar Variable

 if(!isset($noNavBar)){ include $tpl . "navbar.php"; }

