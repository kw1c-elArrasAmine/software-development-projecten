<?php
/***************************************************
 * Module: insertbewerkpage.php
 * Auteur: Amine el Arras
 * Datum: 20 juni 2025
 *
 * Beschrijving:
 * Deze pagina beheert het toevoegen en bewerken van
 * media-items in de 'overzicht' tabel. Het toont een
 * formulier om nieuwe media toe te voegen of bestaande
 * media te bewerken, en geeft een overzicht van alle
 * media-items met een link naar bewerken.
 *
 * Functies:
 * - Verwerkt POST-requests voor INSERT en UPDATE.
 * - Haalt een specifiek media-item op voor bewerken via GET.
 * - Toont een tabel met alle media-items.
 ***************************************************/

include '../functions/function.php';

// Maak verbinding met de database via startConnection() functie
$conn = startConnection();

// Check of de verbinding is gelukt, zo niet stop je met een foutmelding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// MEDIA TOEVOEGEN
// Als het formulier via POST is verzonden en er is GEEN id meegegeven (nieuw media-item)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['id'])) {
    // Waarden ophalen uit het formulier
    $mediaTitle = $_POST['MediaTitle'];
    $mediaFilePath = $_POST['MediaFilePath'];
    $mediaType = $_POST['MediaType'];
    $creator = $_POST['Creator'];
    $language = $_POST['Language'];

    // SQL INSERT query om een nieuw media-item toe te voegen
    $query = "INSERT INTO overzicht (MediaType, MediaTitle, MediaFilePath, Creator, Language)
              VALUES ('$mediaType', '$mediaTitle', '$mediaFilePath', '$creator', '$language')";

    // Voer query uit, bij succes doorsturen naar dashboard, anders foutmelding tonen
    if ($conn->query($query)) {
        header("Location: ../pages/dashboardlogin.php");
        exit;
    } else {
        echo "Fout bij invoegen: " . $conn->error;
    }
}

// MEDIA BIJWERKEN
// Als het formulier via POST is verzonden en er is wel een id meegegeven (bestaand item bijwerken)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    // Waarden ophalen uit het formulier
    $id = $_POST['id'];
    $mediaTitle = $_POST['MediaTitle'];
    $mediaFilePath = $_POST['MediaFilePath'];
    $creator = $_POST['Creator'];
    $language = $_POST['Language'];

    // SQL UPDATE query om het media-item bij te werken met de opgegeven id
    $query = "UPDATE overzicht SET 
                MediaTitle = '$mediaTitle', 
                MediaFilePath = '$mediaFilePath', 
                Creator = '$creator', 
                Language = '$language'
              WHERE id = $id";

    // Voer query uit, bij succes doorsturen naar dashboard, anders foutmelding tonen
    if ($conn->query($query)) {
        header("Location: dashboardlogin.php");
        exit;
    } else {
        echo "Fout bij bijwerken: " . $conn->error;
    }
}

// 1 MEDIA ITEM OPHALEN VOOR BEWERKEN
// Als er een id via GET is meegegeven (bijv. ?id=5), haal die data op voor het bewerkformulier
$media = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM overzicht WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $media = $result->fetch_assoc();
    }
}

// ALLE MEDIA OPHALEN
// Query om alle media uit de tabel 'overzicht' op te halen, gesorteerd op id oplopend
$selectQuery = "SELECT * FROM overzicht ORDER BY id ASC";
$result = $conn->query($selectQuery);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/opmaak.css">
    <link rel="stylesheet" href="../style/style.css">
    <title>HipHop Media</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><img src="../images/r.jpg" alt="Logo" class="logo"></li>
            <li><a href="../Gebruikerpages/gebruikerindex.php">Homepagina</a></li>
            <li><a href="dashboardlogin.php">Overzicht</a></li>
            <li><a href="insertbewerkpage.php">Toevoegen/Bewerken</a></li>
            <li>
                <!-- Uitlogformulier met POST request -->
                <form action="../Gebruikerpages/logout.php" method="post">
                    <button type="submit">Uitloggen</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main>
    <h1>Welkom gebruiker!</h1>

    <div>
        <h2>Nieuwe media toevoegen</h2>
        <form method="post" class="form-styled">
            <!-- MediaType wordt standaard op 'video' gezet via hidden input -->
            <input type="hidden" name="MediaType" value="video">

            <label for="MediaTitle">Titel:</label>
            <input type="text" name="MediaTitle" id="MediaTitle" required><br>

            <label for="MediaFilePath">Bestandslocatie:</label>
            <input type="text" name="MediaFilePath" id="MediaFilePath" required><br>

            <label for="Creator">Maker:</label>
            <input type="text" name="Creator" id="Creator" required><br>

            <label for="Language">Taal (optioneel):</label>
            <input type="text" name="Language" id="Language"><br>

            <input type="submit" value="Toevoegen">
        </form>

        <!-- Als er een media-item is opgehaald via GET (voor bewerken), dan tonen we het bewerkformulier -->
        <?php if (!is_null($media) && !empty($media)) { ?>
            <h2>Media bewerken</h2>
            <form method="post" class="form-styled">
                <!-- Verberg het id zodat we weten welk item we bijwerken -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($media['id']) ?>">

                <label for="MediaTitle">Titel:</label>
                <input type="text" name="MediaTitle" value="<?= htmlspecialchars($media['MediaTitle']) ?>" required><br>

                <label for="MediaFilePath">Bestand:</label>
                <input type="text" name="MediaFilePath" value="<?= htmlspecialchars($media['MediaFilePath']) ?>" required><br>

                <label for="Creator">Maker:</label>
                <input type="text" name="Creator" value="<?= htmlspecialchars($media['Creator']) ?>" required><br>

                <label for="Language">Taal:</label>
                <input type="text" name="Language" value="<?= htmlspecialchars($media['Language']) ?>"><br>

                <input type="submit" value="Opslaan">
            </form>
        <?php } ?>

        <h2>Overzicht van media</h2>
        <table border="1" id="tablebew">
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Titel</th>
                <th>Pad</th>
                <th>Maker</th>
                <th>Datum</th>
                <th>Taal</th>
                <th>Acties</th>
            </tr>

            <!-- Loop door alle media-items en toon ze in de tabel -->
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['MediaType']) ?></td>
                    <td><?= htmlspecialchars($row['MediaTitle']) ?></td>
                    <td><?= htmlspecialchars($row['MediaFilePath']) ?></td>
                    <td><?= htmlspecialchars($row['Creator']) ?></td>
                    <!-- Datum wordt geformatteerd naar dag-maand-jaar -->
                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['CreateDate']))) ?></td>
                    <td><?= htmlspecialchars($row['Language']) ?></td>
                    <td>
                        <!-- Link naar bewerkpagina met id parameter -->
                        <a href="insertbewerkpage.php?id=<?= $row['id'] ?>">Bewerk</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
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
