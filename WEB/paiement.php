<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch festival data
$sql = "SELECT festivalId, festivalName, ticketPrice FROM festival";
$result = $conn->query($sql);

$festivals = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $festivals[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

// Get loginExpireTime from URL
$login_expire_time = isset($_GET['loginExpireTime']) ? $_GET['loginExpireTime'] : '';
?>

<?php
$page_title = "Home - EventsIT";
$css_file = "homepage.css";
include '../Styles/head.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Abonnement Accès à 4 Festivals et Contrôle</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../Assets/Favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../Assets/Favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../Assets/Favicon/favicon-16x16.png">
    <link rel="manifest" href="../Assets/Favicon/site.webmanifest">
    <link rel="stylesheet" href="../CSS/global.css">
    <link rel="stylesheet" href="../CSS/paiement.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body>
<header>
    <div id="left-side-header">
        <img src="../Assets/Champion.png" id="logo-header" alt="Logo Champions">
        <div class="traductible header-return-button" id="header-accueil" data-translation-key="returnButtonDC"></div>
    </div>
    <div id="right-side-header">
        <div id="lang-select">
            <div class="dropdown">
                <div class="dropbtn"><a id="flag1"></a></div>
                <div class="dropdown-content">
                    <div><a class="undraggable flagplus" id="flag2"></a></div>
                    <div><a class="undraggable flagplus" id="flag3"></a></div>
                </div>
            </div>
        </div>
        <div class="traductible right-header-button" data-translation-key="logout"></div>
    </div>
</header>

<main>
    <section id="subscription-section">
        <h1>Finalisez Votre Paiement</h1>
        <p>Veuillez sélectionner un ou plusieurs festivals de votre choix ci-dessous et compléter vos informations bancaires pour sécuriser votre réservation. Ces étapes nous permettront de confirmer votre participation rapidement. Préparez-vous à vivre des moments inoubliables et magiques — nos festivals promettent des expériences exceptionnelles.</p>
        <form id="subscription-form" class="payment-form">
            <input type="hidden" name="customerId" value="<?= htmlspecialchars($_GET['customerId']) ?>">
            <input type="hidden" name="loginExpireTime" value="<?= htmlspecialchars($login_expire_time) ?>">
            <div class="festival-options">
                <?php foreach ($festivals as $festival): ?>
                    <label>
                        <input type="checkbox" name="festival[]" value="<?= $festival['festivalId'] ?>" data-price="<?= $festival['ticketPrice'] ?>">
                        <?= htmlspecialchars($festival['festivalName']) ?> - <?= htmlspecialchars($festival['ticketPrice']) ?>€
                    </label>
                <?php endforeach; ?>
            </div>
            <br><br>
            <div class="form-inputs">
                <input type="text" placeholder="Nom du titulaire" name="cardholder_name">
                <input type="text" placeholder="Numéro de carte" name="card_number">
                <input type="text" placeholder="Date d'expiration" name="expiry_date">
                <input type="text" placeholder="CVV" name="cvv">
            </div>
            <p>Total: <span id="total-price">0</span>€</p>
            <button type="submit">VALIDER LE PAIEMENT</button>
        </form>
    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="festival[]"]');
        const totalPriceElement = document.getElementById('total-price');
        const form = document.getElementById('subscription-form');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let currentTotal = 0;
                checkboxes.forEach(box => {
                    if (box.checked) {
                        currentTotal += parseInt(box.dataset.price);
                    }
                });
                totalPriceElement.textContent = currentTotal;
            });
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_payment.php', true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        window.location.href = `dashboard_client.php?customerId=${response.customerId}&loginExpireTime=${encodeURIComponent(response.loginExpireTime)}`;
                    } else if (response.alreadyAssigned) {
                        alert('Festival already assigned!');
                    } else {
                        alert('An error occurred: ' + response.message);
                    }
                }
            };

            xhr.send(formData);
        });
    });
</script>

<?php include '../Styles/footer.php'; ?>

<script src="/Controller/lang-select.js"></script>
<script src="/Controller/header.js"></script>

</body>
</html>
