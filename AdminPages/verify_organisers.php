<?php
$page = "verify_organisers.php";
include 'auth.php';
ensureAdmin($page);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify'])) {
        $organiserId = $_POST['organiserId'];
        $sql = "UPDATE `organisers` SET verified = TRUE WHERE organiserId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $organiserId);
        $stmt->execute();
        echo json_encode(['status' => 'verified']);
        exit;
    } elseif (isset($_POST['reject'])) {
        $organiserId = $_POST['organiserId'];
        $sql = "DELETE FROM `organisers` WHERE organiserId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $organiserId);
        $stmt->execute();
        echo json_encode(['status' => 'rejected']);
        exit;
    }
}

$unverifiedOrganisers = [];
$sql = "SELECT organiserId, email, surname, firstName, phoneNumber, reason FROM `organisers` WHERE verified = FALSE";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unverifiedOrganisers[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Organisers</title>
    <link rel="stylesheet" href="../CSS/verify.css">
    <link rel="stylesheet" href="../CSS/global.css">
    <script>
        function showReasonPopup(organiser) {
            const popup = document.createElement('div');
            popup.className = 'popup';
            popup.innerHTML = `
                <h2>${organiser.firstName} ${organiser.surname}</h2>
                <p>${organiser.reason}</p>
                <button onclick="verifyOrganiser('${organiser.organiserId}')">Verify</button>
                <button onclick="rejectOrganiser('${organiser.organiserId}')">Reject</button>
                <button onclick="document.body.removeChild(this.parentElement)">Close</button>
            `;
            document.body.appendChild(popup);
        }

        function verifyOrganiser(organiserId) {
            fetch('verify_organisers.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'verify=true&organiserId=' + encodeURIComponent(organiserId)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'verified') {
                        alert('Organiser verified');
                        location.reload();
                    }
                });
        }

        function rejectOrganiser(organiserId) {
            fetch('verify_organisers.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'reject=true&organiserId=' + encodeURIComponent(organiserId)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'rejected') {
                        alert('Organiser rejected');
                        location.reload();
                    }
                });
        }

        window.addEventListener('beforeunload', function () {
            fetch('logout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: ''
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'logged_out') {
                        console.log('Logged out successfully');
                    }
                });
        });
    </script>
</head>
<body>
<h2>Demandes de compte organisateur</h2>
<table>
    <thead>
    <tr>
        <th>E-mail</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>N° de téléphone</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($unverifiedOrganisers as $organiser): ?>
        <tr>
            <td><?php echo htmlspecialchars($organiser['email']); ?></td>
            <td><?php echo htmlspecialchars($organiser['surname']); ?></td>
            <td><?php echo htmlspecialchars($organiser['firstName']); ?></td>
            <td><?php echo htmlspecialchars($organiser['phoneNumber']); ?></td>
            <td>
                <button onclick='showReasonPopup(<?php echo json_encode($organiser); ?>)'>Check</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br>
<h2>Modifier les pop-ups</h2>
<div id="html-editor-container">
    <label for="language-select">Langue</label><select class="editor-select" id="language-select">
        <option value="fr">Français</option>
        <option value="en">Anglais</option>
        <option value="cnko">Coréen de Chine</option>
    </select>


    <label for="html-select">Pop-up à modifier:</label><select class="editor-select" id="html-select">
        <option value="htmlCGU">CGU</option>
        <option value="htmlFAQ">FAQ</option>
        <option value="htmlMentionsLegales">Mentions Légales</option>
        <option value="htmlProtectionDonnees">Protection des données</option>
        <option value="htmlContactUs">Contactez-nous</option>
        <option value="htmlFestivalsPartenaires">Festivals partenaires</option>
        <option value="htmlRisquesAuditifs">Risques auditifs</option>
        <option value="htmlCookies">Cookies</option>
    </select>

    <label for="html-editor"></label><textarea id="html-editor" class="editor-select" rows="10" cols="50"></textarea>
    <br/>
    <button id="save-button">Sauvegarder</button>

    <br>
    <h2>Rechercher un utilisateur</h2>

    <div id="searchBar">
        <label for="first-name-input"></label><input type="text" class="searchField" id="first-name-input" placeholder="Prénom">
        <label for="surname-input"></label><input type="text" class="searchField" id="surname-input" placeholder="Nom de famille">
        <label for="email-input"></label><input type="text" class="searchField" id="email-input" placeholder="E-mail">
        <label for="phone-number-input"></label><input type="text" class="searchField" id="phone-number-input"
                                                       placeholder="N° de téléphone">
        <label for="verified-select"></label><select class="searchField" id="verified-select">
            <option value="1">Vérifié</option>
            <option value="0">Pas vérifié</option>
            <option value="x" selected="selected">..</option>
        </select>
        <button id="search-button">Rechercher</button>
    </div>

    <div id="tbody"></div>

    <script src="../AdminPages/fetch_customers.js"></script>
    <script src="../Controller/footerTextHandler.js"></script>
</div>

</body>
</html>
