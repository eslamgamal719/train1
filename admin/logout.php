<?php


	 session_start();      //Start The Session

	 session_unset();      //Unset for The Data Session


	 session_destroy();    //Destroy The Session

	 header('Location: index.php');

	 exit;