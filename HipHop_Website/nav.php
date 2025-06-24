
<?php

session_start();
include '../functions/function.php';

$gebruikersnaam = isset($_SESSION['gebruikersnaam']) ? $_SESSION['gebruikersnaam'] : 'gast';
?>