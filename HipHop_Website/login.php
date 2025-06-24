<?php
/***************************************************
 * Module: login.php
 * Auteur: Amine el Arras
 * Datum: 20 juni 2025
 *
 * Beschrijving:
 * Dit is de inlogpagina van de Hiphop-website.
 * Bezoekers kunnen hier hun gebruikersnaam en wachtwoord
 * invoeren om in te loggen.
 * Het formulier verstuurt de gegevens via POST naar
 * action_page.php voor verwerking.
 ***************************************************/
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"> <!-- Zet de tekenset van de pagina -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Zorgt dat de pagina goed schaalt op mobiele apparaten -->
    <link rel="stylesheet" href="../style/opmaak.css"> <!-- Algemene stijlen -->
    <link rel="stylesheet" href="../style/login.css"> <!-- Specifieke stijlen voor het loginformulier -->
    <title>HipHop-Login</title> <!-- Titel in de browser tab -->
</head>

<body>
<!-- Navigatie bovenin de pagina -->
<header>
    <nav>
        <ul>
            <li><img src="../images/r.jpg" alt="Logo" class="logo"></li> <!-- Logo -->
            <li><a href="../index.php">Homepagina</a></li> <!-- Link naar homepagina -->
            <li><a href="../pages/Dashboard.php">Dashboard</a></li> <!-- Link naar dashboard -->
            <li><a href="login.php">Inloggen</a></li> <!-- Actieve pagina (login) -->
        </ul>
    </nav>
</header>

<!-- Hoofdinhoud -->
<main>
    <h1>Welkom bij Login pagina!</h1> <!-- Titeltekst -->

    <!-- Loginformulier -->
    <form action="action_page.php" method="post">
        <!-- Gegevens worden verzonden naar action_page.php via POST -->

        <div class="container"> <!-- Container voor form inputs -->
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" id="uname" required>
            <!-- Gebruikersnaam invoerveld (verplicht) -->

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" id="psw" required>
            <!-- Wachtwoord invoerveld (verplicht) -->

            <button type="submit">Login</button> <!-- Inlogknop -->
        </div>
    </form>
</main>

<!-- Footer onderaan de pagina -->
<footer>
    <p>&copy; 2025 Mijn Website</p>
    <br>
    <p>Deze website is onderdeel van mijn praktijkopdracht PO4 voor het vak Project 4.</p>
    <br>
    <p>Gemaakt door Amine el Arras
        <a>amine.elarras@edu-kw1c.nl</a>
    </p>
    <br>
    <p>Onderwerp: Hiphop Media Overzicht</p>
</footer>

</body>
</html>
