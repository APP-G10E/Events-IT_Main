<?php
$page_title = "Home - EventsIT";
$css_file = "homepage.css";
$customerId = $_GET['customerId'];
include '../Styles/head.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the customer ID from the URL
$customerId = $conn->real_escape_string($_GET['customerId']);

// Fetch customer information
$sql = "SELECT firstName FROM customers WHERE customerId = '$customerId'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$sql = "SELECT surname, firstName, phoneNumber FROM customers WHERE customerId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();



// Prepare and execute a statement to get the customer's associated festivals
$stmt = $conn->prepare("SELECT f.festivalName FROM festival AS f INNER JOIN customerfestivals AS cf ON f.festivalId = cf.festivalId WHERE cf.customerId = ?");
$stmt->bind_param("s", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$festivals = [];
while ($row = $result->fetch_assoc()) {
    $festivals[] = $row['festivalName'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil-EventsIT</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/Assets/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/Assets/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/Assets/Favicon/favicon-16x16.png">
    <link rel="manifest" href="/Assets/Favicon/site.webmanifest">

    <link rel="stylesheet" href="/CSS/global.css">
    <link rel="stylesheet" href="/CSS/dashboard_client.css">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const customerId = "<?php echo $customerId; ?>";

            const button = document.querySelector('.translatable.right-header-button[data-translation-key="back_to_HomePage"]');
            const button2 = document.querySelector('.translatable.right-header-button[data-translation-key="Buy"]');

            if (button) {
                button.addEventListener('click', function () {
                    const url = `homepage.php?customerId=${customerId}`;
                    window.location.href = url;
                });
            }
            if (button2) {
                button2.addEventListener('click', function () {
                    const url1 = `paiement.php?customerId=${customerId}`;
                    window.location.href = url1;
                });
            }
        });
    </script>
</head>

<header>
    <div id="header-background"><img src="/Assets/fade_logo_banner.png" id="fade-logo-banner" alt="Logo Champions">
    </div>

    <div id="left-side-header">
        <img src="/Assets/Champion.png" id="logo-header" alt="Logo Champions">
    </div>

    <div id="right-side-header">
        <div class="translatable header-hi-text" data-translation-key="headerHiText"></div>
        <div id="lang-select">
            <div class="dropdown">
                <div class="dropbtn"><a id="flag1"></a></div>
                <div class="dropdown-content">
                    <div><a class="undraggable flagplus" id="flag2"></a></div>
                    <div><a class="undraggable flagplus" id="flag3"></a></div>
                </div>
            </div>
        </div>
        <div class="translatable right-header-button" data-translation-key="payment"></div>

        <div class="translatable right-header-button" data-translation-key="back_to_HomePage"></div>

        <div id="editProfileButton" class="translatable right-header-button" data-translation-key="editProfile"
             onclick="showFestivalList()"></div>

        <script>
            function showFestivalList() {
                document.getElementById("festival-popup").style.display = "block";
                document.getElementById("festival-popup").style.overflow = "hidden";
            }

            function hideFestivalList() {
                document.getElementById("festival-popup").style.display = "none";
            }
        </script>

        <div class="translatable right-header-button" data-translation-key="disconnection"></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const button = document.querySelector('.translatable.right-header-button[data-translation-key="disconnection"]');
                if (button) {
                    button.addEventListener('click', function () {
                        window.location.href = 'homepage.php';
                    });
                }
            });
        </script>

    </div>
</header>

<body>
<div id="spectateur-container">
    <div class="translatable bjrSpectateur" data-translation-key="hello"></div>
    <div class="bjrSpectateur" id="bjrSpectateur"><?php echo '&nbsp;' . htmlspecialchars($user['firstName']); ?></div>
</div>
<div id="body-container">
    <div id="festival-banner-container">
        <div class="translatable selectionnerFestival" data-translation-key="selectionnerFestival"></div>
    </div>

    <div id="festival-info-container" class="center-column">
        <div id="festival-recherche-container">
            <p class="translatable festival-info-title" data-translation-key="choixFestival"></p>
            <label for="festival-recherche"></label><input type="text" class="translatable" id="festival-recherche"
                                                           data-translation-key="festivalRecherche">




            <p class="translatable" data-translation-key="festivalsRecherchesTitre" id="festivals-recherches-titre"></p>
            <div id="festivals-populaires">
                <?php if (empty($festivals)): ?>
                    <div class="translatable festival-populaire" id="proceedPayment" data-translation-key="proceedPayment" >
                    </div>
                <?php else: ?>
                    <?php foreach ($festivals as $festival): ?>
                        <div class="festival-populaire">
                            <p><?php echo htmlspecialchars($festival); ?></p>
                            <p>🟢</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div id="festivals-partenaires-liste" class="translatable lien-precisions" data-translation-key="festivalsPartenairesListe"><p></p></div>

        </div>

        <div id="festival-capteurs-container">
            <p class="translatable festival-info-title" data-translation-key="volumeFestival"></p>
            <div id="sensor-elements-container">
                <div class="translatable selectionnerFestival" data-translation-key="selectionnerFestival"></div>
            </div>
            <p id="risques-son" class="translatable lien-precisions" data-translation-key="risquesSon"></p>
        </div>
    </div>
</div>
<script src="../Controller/contact_handler.js"></script>
<script src="../Controller/dashboard_client.js"></script>
<script src="../Controller/popups.js"></script>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Retrieve PHP variables passed into JavaScript
        const customerId = "<?php echo $customerId; ?>";

        const button = document.querySelector('.translatable.festival-populaire[data-translation-key="proceedPayment"]');

        if (button) {
            button.addEventListener('click', function () {
                const url = `paiement.php?customerId=${customerId}`;
                window.location.href = url;
            });
        }
    });
</script>

<?php
include '../Styles/footer.php';
?>

<div id="festival-popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="hideFestivalList()">&times;</span>
        <h3 data-translation-key="editProfile" class="translatable"></h3>
        <form class="festival-table form-container" method="post" action="update_profile.php">

            <input type="hidden" name="customerId" value="<?php echo htmlspecialchars($customerId); ?>">

            <label for="surname" data-translation-key="surnamePlaceholder" class="translatable"></label>
            <input type="text" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>"
                   class="form-input" required>

            <label for="firstName" data-translation-key="firstNamePlaceholder" class="translatable"></label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>"
                   class="form-input" required>

            <label for="phoneNumber" data-translation-key="telephonePlaceholder" class="translatable"></label>
            <input type="text" name="phoneNumber" value="<?php echo htmlspecialchars($user['phoneNumber']); ?>"
                   class="form-input" required>

            <button type="submit" name="organiserForm" id="login" class="translatable form-submit"
                    data-translation-key="updateProfileButton"></button>
        </form>
    </div>
</div>

</html>
<script src="../Controller/lang-select.js"></script>
