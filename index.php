<?php
	
	require_once("br-content/libraries/password_compatibility_library.php");
	require_once("br-content/config/db.php");

	require_once("br-content/classes/Login.php");

	$login = new Login();

	if ($login->isUserLoggedIn() == true) {
	    include("br-admin/admin.php");
	} else {
	    include("br-content/views/landing.php");
	}
?>
