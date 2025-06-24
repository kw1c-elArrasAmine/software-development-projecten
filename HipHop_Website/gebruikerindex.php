

<?php

session_start(); // Vergeet dit niet!
date_default_timezone_set('Europe/Amsterdam');
$datum = date("d-m-Y H:i:s");
include '../functions/function.php';
$conn = startConnection();
if (isset($_SESSION['gebruikersnaam'])) {
    $gebruikersnaam = $_SESSION['gebruikersnaam'];
} elseif (isset($_COOKIE['gebruikersnaam'])) {
    $gebruikersnaam = $_COOKIE['gebruikersnaam'];
} else {
    $gebruikersnaam = "gast";
}
?>



<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/opmaak.css">
    <link rel="stylesheet" href="../style/style.css">
    <title>HipHop-Homepagina</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><img src="../images/r.jpg" alt="Logo" class="logo"></li>
            <li><a href="gebruikerindex.php">Homepagina</a></li>
            <li><a href="../pages/dashboardlogin.php">Dashboard</a></li>
            <li><a href="../pages/insertbewerkpage.php">Muziek toevoegen bewerken</a></li>
            <li>     <form action="logout.php" method="post">
                    <button type="submit">Uitloggen</button>
                </form>></li>
        </ul>
    </nav>
</header>

<main>
    <h1>Welkom bij de Homepagina!</h1>
    <?php
    echo "Welkom, " . htmlspecialchars($gebruikersnaam);
    ?>

    <div id="halloffame">
        <h2>Hall of Fame</h2>
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

    <!-- MEDIA BLOK VANUIT DATABASE -->
    <div id="media" class="videorow">
        <?php
        // Roep hier de functie aan om de media te tonen
        toonMediaBlokken(true);
        ?>
    </div>



</main>


<footer>

    <p>&copy; 2025 Mijn Website</p>

    <?php
    echo "Welkom, " . htmlspecialchars($gebruikersnaam). "je bent ingelogd op $datum.<br>";
    ?>

        <form action="logout.php" method="post">
            <button type="submit">Uitloggen</button>
        </form>
</footer>

</body>
</html>