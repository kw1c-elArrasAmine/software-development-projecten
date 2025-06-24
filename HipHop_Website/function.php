<?php
/***************************************************
 * Auteur: Amine el Arras
 * Datum: 18 juni 2025
 * Bestandsnaam: function.php
 * Beschrijving: Bevat alle herbruikbare functies voor de
 * hiphop-website, zoals databaseverbinding, media ophalen
 * en de login-functionaliteit.
 *
 * MODULE: Functies
 * DOEL:
 * - Databaseverbinding starten met `startConnection()`
 * - Verschillende media ophalen en tonen in HTML-blokken
 * - Inloggen van gebruikers controleren
 ***************************************************/

// Verbindingsfunctie: maakt verbinding met de database op localhost
function startConnection()
{
    global $conn;

    $host = 'localhost';
    $username = 'st1738846894';
    $password = '5UfXVRJqLWEmfBZ';

    // Verbinden met database "st1738846894"
    $conn = new mysqli($host, $username, $password, "st1738846894");

    // Check of de verbinding is gelukt, anders stoppen met foutmelding
    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    // Return de verbinding zodat andere functies deze kunnen gebruiken
    return $conn;
}

//////////////////////////////////////////
// MEDIA BLOKKEN FUNCTIES
//////////////////////////////////////////

// Algemene functie om media te tonen (max 3)
function toonMediaBlokken($fromGebruikerPage = false) {
    $conn = startConnection();
    $sql = "SELECT * FROM overzicht ORDER BY id LIMIT 3";
    $result = $conn->query($sql);

    // Bepaal pad voor media, afhankelijk van waar deze functie wordt gebruikt
    $relPath = $fromGebruikerPage ? "../" : "";

    // Loop door resultaten en toon elk media-item via helper-functie
    while ($row = $result->fetch_assoc()) {
        toonMediaHtml($row, $relPath);
    }

    $conn->close();
}

// Specifiek voor overzichtspagina
function toonMediaBlokkenoverzicht($fromGebruikerPage = false) {
    $conn = startConnection();
    $sql = "SELECT * FROM overzicht ORDER BY id LIMIT 3";
    $result = $conn->query($sql);

    $relPath = $fromGebruikerPage ? "../" : "";

    while ($row = $result->fetch_assoc()) {
        toonMediaHtml($row, $relPath);
    }

    $conn->close();
}

// Voor Nederlandse hiphop media (id 4,5,6)
function toonMediaBlokkenNederlandseHipHop($fromGebruikerPage = false) {
    $conn = startConnection();
    $sql = "SELECT * FROM overzicht WHERE id IN (4, 5, 6)";
    $result = $conn->query($sql);

    $relPath = $fromGebruikerPage ? "../" : "";

    while ($row = $result->fetch_assoc()) {
        toonMediaHtml($row, $relPath);
    }

    $conn->close();
}

// Voor Franse hiphop media (id 7,8,9)
function toonMediaBlokkenFranseHipHop($fromGebruikerPage = false) {
    $conn = startConnection();
    $sql = "SELECT * FROM overzicht WHERE id IN (7, 8, 9)";
    $result = $conn->query($sql);

    $relPath = $fromGebruikerPage ? "../" : "";

    while ($row = $result->fetch_assoc()) {
        toonMediaHtml($row, $relPath);
    }

    $conn->close();
}

// Media toegevoegd na id 9 en alleen video's
function toonMediaBlokkenInsert($fromGebruikerPage = false) {
    $conn = startConnection();
    $sql = "SELECT * FROM overzicht WHERE id > 9 AND MediaType = 'video'";
    $result = $conn->query($sql);

    $relPath = $fromGebruikerPage ? "../" : "";

    while ($row = $result->fetch_assoc()) {
        toonMediaHtml($row, $relPath);
    }

    $conn->close();
}

//////////////////////////////////////////
// MEDIA HTML OPMAAK FUNCTIE
//////////////////////////////////////////

// Toont één media-blok als HTML (video of audio)
function toonMediaHtml($row, $relPath) {
    echo "<div class='media-block'>";
    echo "<h3 class='titel-clip'>" . htmlspecialchars($row['MediaTitle']) . "</h3>";

    if ($row['MediaType'] === 'video') {
        echo '<video width="480" height="270" controls>
            <source src="' . $relPath . htmlspecialchars($row['MediaFilePath']) . '" type="video/mp4">
            Je browser ondersteunt deze video niet.
        </video>';
    } elseif ($row['MediaType'] === 'audio') {
        echo '<audio controls>
            <source src="' . $relPath . htmlspecialchars($row['MediaFilePath']) . '" type="audio/mpeg">
            Je browser ondersteunt deze audio niet.
        </audio>';
    }

    echo "<p>Geplaatst door: " . htmlspecialchars($row['Creator']) . " op " . $row['CreateDate'] . "</p>";
    echo "<p>Taal: " . htmlspecialchars($row['Language']) . "</p>";
    echo "</div>";
}

//////////////////////////////////////////
// LOGIN FUNCTIE
//////////////////////////////////////////

// Loginfunctie: controleert gebruikersnaam en wachtwoord
function login($username, $password) {
    // Start sessie om sessievariabelen te kunnen gebruiken
    session_start();

    // Verbinding maken met de database
    $conn = startConnection();

    // SQL query met prepared statement om SQL-injectie te voorkomen
    $sql = "SELECT * FROM login WHERE username = ?";

    // Bereidt de query voor
    $stmt = $conn->prepare($sql);

    /*
    * bind_param() verbindt de parameters aan de query:
    * - De eerste parameter is een string met types van de parameters.
    * - Hier is "s" wat betekent dat we 1 parameter meegeven van het type 'string'.
    * - Daarna geef je de variabelen door die in de query moeten komen (hier: $username).
    *
    * Voorbeeld van types:
    * "s" = string
    * "i" = integer
    * "d" = double (float)
    * "b" = blob (binary)
    *
    * Omdat username een tekst is, gebruiken we "s".
    */
    $stmt->bind_param("s", $username);

    // Voert de voorbereide query uit met de gebonden parameter
    $stmt->execute();

    // Haalt het resultaat op na uitvoeren query
    $result = $stmt->get_result();

    // Controleren of er een gebruiker met deze gebruikersnaam is
    if ($result->num_rows > 0) {
        // Gebruiker gevonden, gegevens ophalen
        $row = $result->fetch_assoc();

        // Wachtwoord vergelijken (hier zonder hashing, let op: niet veilig!)
        if ($password === $row['password']) {
            // Gebruiker is ingelogd: sessievariabelen zetten
            $_SESSION['ingelogd'] = true;
            $_SESSION['gebruikersnaam'] = $row['username'];

            // Cookie zetten zodat gebruiker onthouden wordt, 30 dagen geldig
            setcookie("gebruikersnaam", $row['username'], time() + (86400 * 30), "/");

            // Doorsturen naar dashboard
            header("Location: /Gebruikerpages/gebruikerindex.php");
            exit();
        } else {
            // Wachtwoord niet correct
            echo "Ongeldige gebruikersnaam of wachtwoord. <a href='login.php'>Opnieuw proberen</a>";
        }
    } else {
        // Gebruiker niet gevonden
        echo "Ongeldige gebruikersnaam of wachtwoord. <a href='login.php'>Opnieuw proberen</a>";
    }

    // Sluit prepared statement en databaseverbinding
    $stmt->close();
    $conn->close();
}
?>
