<?php
/***************************************************
 * Auteur: Amine el Arras
 * Datum: 18 juni 2025
 * Bestandsnaam: index.php
 * Beschrijving: Dit is de homepagina van de Hiphop-website
 * voor de praktijkopdracht PO4. Op deze pagina kun je
 * informatie zien over legendarische artiesten en hiphopmedia
 * bekijken die uit de database worden gehaald.
 *
 * MODULE: Homepagina
 * DOEL:
 * - Toont algemene introductie tot de site
 * - Bevat navigatie naar andere onderdelen van de website
 * - Presenteert legendarische hiphopartiesten (Hall of Fame)
 * - Laadt automatisch media (video/audio) uit de database via toonMediaBlokken()
 ***************************************************/

// Laad de functies in, zoals de databaseverbinding en toonMediaBlokken()
include 'functions/function.php';
?>

<!-- Start van de HTML-pagina -->
<!DOCTYPE html>
<html lang="nl"> <!-- Geeft aan dat de pagina in het Nederlands is -->
<head>
    <meta charset="UTF-8"> <!-- Tekenset-instelling -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Zorgt dat de site goed schaalt op mobiel -->
    <link rel="stylesheet" href="style/opmaak.css"> <!-- CSS voor algemene opmaak -->
    <link rel="stylesheet" href="style/style.css"> <!-- CSS voor specifieke stijlen -->
    <title>HipHop-Homepagina</title> <!-- Titel van de pagina in de browser tab -->
</head>

<body>
<!-- Header met navigatiebalk -->
<header>
    <nav>
        <ul>
            <li><img src="images/r.jpg" alt="Logo" class="logo"></li> <!-- Website logo -->
            <li><a href="index.php">Homepagina</a></li> <!-- Link naar home -->
            <li><a href="pages/Dashboard.php">Dashboard</a></li> <!-- Link naar dashboard -->
            <li><a href="pages/login.php">Inloggen</a></li> <!-- Link naar inlogpagina -->
        </ul>
    </nav>
</header>

<!-- Main content gedeelte -->
<main>
    <h1>Welkom bij de Homepagina!</h1> <!-- Welkomsttitel -->
    <h2>
        Op deze website kan je hiphop muziek beluisteren en als je inlogt kan je favoriete hiphopmuziek toevoegen!
    </h2>

    <!-- Hall of Fame gedeelte met bekende artiesten -->
    <div id="halloffame">
        <h2>Hall of Fame</h2>
        <div class="artiesten-container">

            <!-- Eerste artiestkaart -->
            <div class="artiest-card">
                <img src="images/halloffame/tupac.jpg" alt="Artiest 1"> <!-- Foto van Tupac -->
                <h3 class="txthalloffame">Tupac Shakur (2Pac)</h3>
                <p>Bekend van: All Eyez on Me</p>
            </div>

            <!-- Tweede artiestkaart -->
            <div class="artiest-card">
                <img src="images/halloffame/big.jpg" alt="Artiest 2"> <!-- Foto van Biggie -->
                <h3 class="txthalloffame">The Notorious B.I.G.</h3>
                <p>Bekend van: Suicidal Thoughts</p>
            </div>

            <!-- Derde artiestkaart -->
            <div class="artiest-card">
                <img src="images/halloffame/nas.jpg" alt="Artiest 3"> <!-- Foto van Nas -->
                <h3 class="txthalloffame">Nas</h3>
                <p>Bekend van: Illmatic </p>
            </div>
        </div>
    </div>

    <!-- Media gedeelte dat automatisch uit de database wordt geladen -->
    <div id="media" class="videorow">
        <?php
        // Roept de functie aan om de media uit de database op te halen en te tonen
        // false betekent: geen bewerk/verwijder knoppen tonen
        toonMediaBlokken();
        ?>
    </div>
</main>

<!-- Footer met informatie over de website -->
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
