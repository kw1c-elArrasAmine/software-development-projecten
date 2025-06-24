<?php
/***************************************************
 * Module: dashboardlogin.php
 * Auteur: Amine el Arras
 * Datum: 20 juni 2025
 *
 * Beschrijving:
 * Dit is het dashboard van ingelogde gebruikers.
 * Hier kunnen gebruikers zoeken naar hiphopmedia (video/audio)
 * en zien ze een overzicht van media uit de database.
 * Ook wordt de gebruiker welkom geheten op basis van
 * sessie of cookie, anders als 'gast'.
 * De pagina toont daarnaast vaste "Hall of Fame" artiesten
 * en verdeelt media in categorieën: algemene media,
 * Nederlandse hiphop, Franse hiphop en media geplaatst door de gebruiker.
 ***************************************************/

// Eerst laden we onze functies uit een apart bestand
include '../functions/function.php';

// Maak verbinding met de database via onze functie startConnection
$conn = startConnection();

// We controleren of de gebruiker ingelogd is:
// Eerst kijken we of er een sessievariabele 'gebruikersnaam' bestaat
if (isset($_SESSION['gebruikersnaam'])) {
    // Als die bestaat, gebruiken we die
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
}
// Anders kijken we of er een cookie 'gebruikersnaam' is
elseif (isset($_COOKIE['gebruikersnaam'])) {
    $gebruikersnaam = $_COOKIE['gebruikersnaam'];
}
// Als er niks is gevonden, noemen we de gebruiker 'gast'
else {
    $gebruikersnaam = "gast";
}

// Bepalen van het relatieve pad voor media en afbeeldingen
// Dit hangt af van waar de pagina geladen wordt
$fromGebruikerPage = true;

if ($fromGebruikerPage == true) {
    // We zitten op een gebruikerspagina, dus relatieve paden staan 1 map hoger
    $relPath = "../";
} else {
    // Anders is het pad direct
    $relPath = "";
}

// Nogmaals verbinding maken met database 'hiphop'
// Dit kan dubbel lijken maar zorgt dat we juiste database gebruiken
$conn = startConnection("hiphop");

// We halen de zoekwaarden op uit de URL-parameters (GET-parameters)
// Eerst controleren we of ze zijn gezet, anders gebruiken we lege string
if (isset($_GET["txtMediaTitle"])) {
    $mediaTitle = $_GET["txtMediaTitle"];
} else {
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

// We starten met een basis SQL-query die alle mp4 bestanden ophaalt
$query = "SELECT * FROM overzicht WHERE MediaFilePath LIKE '%.mp4'";

// Als de gebruiker een titel invult, voegen we dat toe als filter
if ($mediaTitle !== "") {
    // LIKE wordt gebruikt voor gedeeltelijke overeenkomsten
    $query .= " AND MediaTitle LIKE '%" . $mediaTitle . "%'";
}

// Als de creator ingevuld is, filteren we exact op creator
if ($creator !== "") {
    $query .= " AND Creator = '" . $creator . "'";
}

// Als de taal ingevuld is, filteren we ook op taal met LIKE
if ($language !== "") {
    $query .= " AND Language LIKE '%" . $language . "%'";
}

// We bepalen of er een zoekactie is geweest: als één van de zoekvelden is ingevuld
if (isset($_GET['txtMediaTitle']) || isset($_GET['txtCreator']) || isset($_GET['txtLanguage'])) {
    $zoekactie = true;
} else {
    $zoekactie = false;
}

// Resultaatvariabele initialiseren
$result = null;

// Alleen als er een zoekactie is, voeren we de query uit
if ($zoekactie) {
    $result = $conn->query($query);
    // Als de query mislukt, tonen we een foutmelding en stoppen we
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
    <!-- Stylesheets voor de opmaak van de pagina -->
    <link rel="stylesheet" href="../style/opmaak.css">
    <link rel="stylesheet" href="../style/style.css">
    <title>HipHop Dashboard</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><img src="../images/r.jpg" alt="Logo" class="logo"></li>
            <!-- Navigatie naar belangrijke pagina's -->
            <li><a href="../Gebruikerpages/gebruikerindex.php">Homepagina</a></li>
            <li><a href="dashboardlogin.php">Overzicht</a></li>
            <li><a href="insertbewerkpage.php">Muziek toevoegen/bewerken</a></li>
            <li>
                <!-- Uitlogknop in een formulier om POST te gebruiken -->
                <form action="../Gebruikerpages/logout.php" method="post">
                    <button type="submit">Uitloggen</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main>
    <!-- Welkomsttekst, met dynamische gebruikersnaam -->
    <h1>Welkom gebruiker!</h1>
    <?php echo "<h1>Welkom $gebruikersnaam</h1>"; ?>

    <!-- Zoekformulier -->
    <form method="get" action="" class="form-styled">
        <label for="txtMediaTitle">Titel:</label>
        <!-- Vult het zoekveld automatisch in met eerder ingevulde waarde -->
        <input type="text" name="txtMediaTitle" value="<?php echo htmlspecialchars($mediaTitle); ?>">
        <br><br>

        <label for="txtLanguage">Taal:</label>
        <input type="text" name="txtLanguage" value="<?php echo htmlspecialchars($language); ?>">
        <br><br>

        <input type="submit" value="Zoeken">
    </form>

    <hr>

    <?php
    // Als er een zoekactie is, tonen we de zoekresultaten
    if ($zoekactie == true) {
        // Controleren of er resultaten zijn
        if ($result && $result->num_rows > 0) {
            // Container voor zoekresultaten openen
            echo "<div id='zoekaudio'>";

            // Loop door alle gevonden media-items
            while ($row = $result->fetch_assoc()) {
                echo "<div class='media-blok'>"; // Blok per item

                // Titel en taal tonen, met htmlspecialchars ter beveiliging
                echo "<strong>Titel:</strong> " . htmlspecialchars($row['MediaTitle']) . "<br>";
                echo "<strong>Taal:</strong> " . htmlspecialchars($row['Language']) . "<br>";

                // Media tonen afhankelijk van het type en of bestandspad bekend is
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
                    // Geen media beschikbaar als geen bestandspad bekend is
                    echo 'Geen media beschikbaar';
                }

                echo "</div><br>"; // Sluit media blok
            }

            echo "</div>"; // Sluit container zoekresultaten
        } else {
            // Als geen resultaten gevonden zijn
            echo "Geen resultaten gevonden.";
        }
    }
    ?>

    <!-- Hall of Fame sectie met vaste artiesten -->
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

    <!-- Algemene media uit database -->
    <div id="media" class="videorow">
        <?php
        // Toont alle media in overzicht via een functie
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
        // Toont Nederlandse Hip Hop media via functie
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
        // Toont Franse Hip Hop media via functie
        toonMediaBlokkenFranseHipHop(true);
        ?>
    </div>

    <!-- Media geplaatst door huidige gebruiker -->
    <div id="insert-media">
        <?php
        // Titel met naam van huidige gebruiker
        echo "<h2 class='gebruiker-titel'>Geplaatst door: $gebruikersnaam</h2>";
        // Toont media die door de gebruiker is geplaatst
        toonMediaBlokkenInsert(true);
        ?>
    </div>

</main>

<footer>
    <p>&copy; 2025 Mijn Website</p>
    <?php
    // Extra welkom tekst in footer met gebruikersnaam
    echo "<p>Welkom $gebruikersnaam</p>";
    ?>
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
