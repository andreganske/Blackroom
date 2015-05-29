<?php

    require_once("br-content/libraries/password_compatibility_library.php");
    require_once("br-content/config/db.php");

    require_once("br-content/classes/Registration.php");

    $registration = new Registration();

    include("br-content/views/register.php");
?>