<?php

//session_unset();     // Verwijder alle sessievariabelen
session_destroy();   // Beëindig de sessie

//// (Optioneel) verwijder de cookie
//setcookie("gebruikersnaam", "", time() - 3600, "/");

header("Location: /index.php");



exit();
?>