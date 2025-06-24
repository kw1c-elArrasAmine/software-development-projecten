<?php
/*****************************************************
 * Bestand: Dashboard.php
 * Auteur: Amine el Arras
 * Datum: 20 juni 2025
 * Beschrijving: Overzichtspagina voor HipHop media met
 * zoekfunctionaliteit, mediaweergave en diverse secties
 * zoals Golden Era, Nederlandse en Franse Hip Hop.
 *****************************************************/

// Eerst importeren we onze functies uit een ander bestand
include '../functions/function.php';

// Maak verbinding met de database via onze functie startConnection()
// Deze functie maakt een verbinding en geeft die verbinding terug
$conn = startConnection();

// Nu bepalen we de gebruikersnaam:
// We kijken eerst of er een sessievariabele 'gebruikersnaam' bestaat
if (isset($_SESSION['gebruikersnaam'])) {
    // Als die bestaat, gebruiken we die
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
}
// Anders kijken we of er een cookie is met de naam 'gebruikersnaam'
elseif (isset($_COOKIE['gebruikersnaam'])) {
    $gebruikersnaam = $_COOKIE['gebruikersnaam'];
}
// Als niks van beide bestaat, noemen we de gebruiker 'gast'
else {
    $gebruikersnaam = "gast";
}

// We bepalen of deze pagina wordt geladen vanaf een "gebruikerspagina".
// Dit gebruiken we later om paden goed te zetten.
$fromGebruikerPage = true;

// We bepalen het relatieve pad, afhankelijk van waar we zitten
if ($fromGebruikerPage == true) {
    // Als we vanaf gebruikerspagina komen, staan we 1 map hoger
    $relPath = "../";
} else {
    // Anders is het pad gelijk
    $relPath = "";
}

// Nu halen we zoekwaarden op die de gebruiker misschien in de URL heeft meegegeven
// Omdat de zoekvelden optioneel zijn, moeten we eerst controleren of ze bestaan

if (isset($_GET["txtMediaTitle"])) {
    // Als de gebruiker iets heeft ingevuld voor titel, gebruiken we dat
    $mediaTitle = $_GET["txtMediaTitle"];
} else {
    // Anders zetten we het op een lege string (dus geen filter)
    $mediaTitle = "";
}

if (isset($_GET["txtCreator"])) {
    $creator = $_GET["txtCreator"];
} else {
    $creator = "";
}

if (isset($_GET["txtLanguage"])) {
    $language = $_GET["txtLanguage"];
} else {
    $language = "";
}

// We starten onze SQL-query. We zoeken alleen naar mp4 bestanden.
$query = "SELECT * FROM overzicht WHERE MediaFilePath LIKE '%.mp4'";

// Als de gebruiker een titel heeft ingevuld, voegen we dat toe als filter
if ($mediaTitle !== "") {
    // LIKE gebruiken voor gedeeltelijke matches, met % als wildcard
    $query .= " AND MediaTitle LIKE '%" . $mediaTitle . "%'";
}

// Als de creator ingevuld is, filteren we exact op creator
if ($creator !== "") {
    $query .= " AND Creator = '" . $creator . "'";
}

// Als taal ingevuld is, filteren we ook op taal met LIKE
if ($language !== "") {
    $query .= " AND Language LIKE '%" . $language . "%'";
}

// We kijken of er überhaupt een zoekactie is gestart,
// dat wil zeggen dat minstens één van de zoekvelden is ingevuld
if (isset($_GET['txtMediaTitle']) || isset($_GET['txtCreator']) || isset($_GET['txtLanguage'])) {
    $zoekactie = true;
} else {
    $zoekactie = false;
}

// We initialiseren $result op null voor het geval er geen zoekactie is
$result = null;

// Alleen als er een zoekactie is, voeren we de query uit
if ($zoekactie) {
    $result = $conn->query($query);
    // Als de query faalt, stoppen we en geven we een foutmelding
    if (!$result) {
        die("Fout bij uitvoeren van query: " . $conn->error);
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheets voor opmaak -->
    <link rel="stylesheet" href="../style/opmaak.css">
    <link rel="stylesheet" href="../style/style.css">
    <title>HipHop-</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <!-- Logo -->
            <li><img src="../images/r.jpg" alt="Logo" class="logo"></li>
            <!-- Navigatie -->
            <li><a href="../index.php">Homepagina</a></li>
            <li><a href="Dashboard.php">Overzicht</a></li>
            <li><a href="login.php">Inloggen</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Welkom!</h1>

    <!-- Zoekformulier -->
    <form method="get" action="" class="form-styled">
        <label for="txtMediaTitle">Titel:</label>
        <!-- We vullen het zoekveld automatisch met eerder ingevulde waarde -->
        <input type="text" name="txtMediaTitle" value="<?php echo htmlspecialchars($mediaTitle); ?>">
        <br><br>

        <br><br>
        <input type="submit" value="Zoeken">
        <label for="txtLanguage">Taal:</label>
        <input type="text" name="txtLanguage" value="<?php echo htmlspecialchars($language); ?>">
        <br><br>
    </form>

    <hr>

    <?php
    // Als er een zoekactie is, tonen we de zoekresultaten
    if ($zoekactie == true) {
        // Controleren of er resultaten zijn
        if ($result && $result->num_rows > 0) {
            // Container voor alle zoekresultaten
            echo "<div id='zoekaudio'>";

            // Loop door alle gevonden rijen
            while ($row = $result->fetch_assoc()) {
                // Eén blok per media-item
                echo "<div class='media-blok'>";

                // Toon titel en taal van de media (veilig afdrukken met htmlspecialchars)
                echo "<strong>Titel:</strong> " . htmlspecialchars($row['MediaTitle']) . "<br>";
                echo "<strong>Taal:</strong> " . htmlspecialchars($row['Language']) . "<br>";

                // Controleer het type media en toon video of audio element
                if ($row['MediaType'] == 'video' && $row['MediaFilePath'] != '') {
                    echo '<video class="media-video" controls>
                        <source src="' . $relPath . htmlspecialchars($row['MediaFilePath']) . '" type="video/mp4">
                        Je browser ondersteunt deze video niet.
                    </video>';
                } elseif ($row['MediaType'] == 'audio' && $row['MediaFilePath'] != '') {
                    echo '<audio class="media-audio" controls>
                        <source src="' . $relPath . htmlspecialchars($row['MediaFilePath']) . '" type="audio/mpeg">
                        Je browser ondersteunt deze audio niet.
                    </audio>';
                } else {
                    // Geen mediabestand beschikbaar
                    echo 'Geen media beschikbaar';
                }

                echo "</div><br>"; // Sluit media-blok
            }

            echo "</div>"; // Sluit zoekresultaten container
        } else {
            // Als geen resultaten zijn gevonden, tonen we een bericht
            echo "Geen resultaten gevonden.";
        }
    }
    ?>

    <!-- Golden era sectie -->
    <div id="halloffame">
        <h2>Golden era</h2>
        <div class="artiesten-container">
            <div class="artiest-card">
                <img src="../images/halloffame/tupac.jpg" alt="Artiest 1">
                <h3 class="txthalloffame">Tupac Shakur (2Pac)</h3>
                <p>Bekend van: All Eyez on Me</p>
            </div>
            <div class="artiest-card">
                <img src="../images/halloffame/big.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">The Notorious B.I.G.</h3>
                <p>Bekend van: Suicidal Thoughts</p>
            </div>
            <div class="artiest-card">
                <img src="../images/halloffame/nas.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">Nas</h3>
                <p>Bekend van: Illmatic </p>
            </div>
        </div>
    </div>

    <!-- Media blokken via functies tonen -->
    <div id="media" class="videorow">
        <?php
        // Functie aanroepen om media blokken overzicht te tonen
        toonMediaBlokkenoverzicht(true);
        ?>
    </div>

    <!-- Nederlandse Hip Hop sectie -->
    <div id="halloffame">
        <h2>Nederlandse Hip Hop</h2>
        <div class="artiesten-container">
            <div class="artiest-card">
                <img src="../images/NederlandseHipHop/Lijpe.jpg" alt="Artiest 1">
                <h3 class="txthalloffame">Lijpe</h3>
                <p>Bekend van: Voorwoord</p>
            </div>
            <div class="artiest-card">
                <img src="../images/NederlandseHipHop/Ismo.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">Ismo</h3>
                <p>Bekend van: Regenboog</p>
            </div>
            <div class="artiest-card">
                <img src="../images/NederlandseHipHop/Josylvio.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">Josylvio</h3>
                <p>Bekend van: Dolla Dolla Bill</p>
            </div>
        </div>
    </div>

    <div id="media" class="videorow">
        <?php
        // Functie aanroepen voor Nederlandse Hip Hop media blokken
        toonMediaBlokkenNederlandseHipHop(true);
        ?>
    </div>

    <!-- Franse Hip Hop sectie -->
    <div id="halloffame">
        <h2>Franse Hip Hop</h2>
        <div class="artiesten-container">
            <div class="artiest-card">
                <img src="../images/FranseHipHop/pnl.jpg" alt="Artiest 1">
                <h3 class="txthalloffame">PNL</h3>
                <p>Bekend van: j’comprends pas</p>
            </div>
            <div class="artiest-card">
                <img src="../images/FranseHipHop/jul.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">Jul</h3>
                <p>Bekend van: Tchikita </p>
            </div>
            <div class="artiest-card">
                <img src="../images/FranseHipHop/djadjadinaz.jpg" alt="Artiest 2">
                <h3 class="txthalloffame">Djada & Dinaz</h3>
                <p>Bekend van: À cœur ouvert</p>
            </div>
        </div>
    </div>

    <div id="media" class="videorow">
        <?php
        // Functie aanroepen voor Franse Hip Hop media blokken
        toonMediaBlokkenFranseHipHop(true);
        ?>
    </div>

</main>

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
