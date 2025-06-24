<?php


// Controleer of het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de gebruikersnaam en het wachtwoord van het formulier
    $username = $_POST['uname'];
    $password = $_POST['psw'];

    // Roep de loginfunctie aan
    include('../functions/function.php');  // Dit voegt de loginfunctie in
    login($username, $password); // Roep de loginfunctie aan met de ingevoerde gegevens
}
?>