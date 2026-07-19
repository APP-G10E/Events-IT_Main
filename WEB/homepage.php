<!DOCTYPE html>
<html lang="fr">

<?php
$page_title = "Home - EventsIT";
$css_file = "homepage.css";
include '../Styles/head.php';
?>

<script src="../Controller/common.js"></script>
<header>
    <div id="left-side-header">
        <img src="../Assets/Champion.png" id="logo-header" alt="Logo Champions">
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
        <a href="sign_up.php" id="sign-up-link">
            <div class="translatable right-header-button" id="header-subscribe" data-translation-key="subscribe"></div>
        </a>
        <a href="login.php" id="login-link">
            <div class="translatable right-header-button" id="header-login" data-translation-key="connection"></div>
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function getQueryParam(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            const customerId = getQueryParam('customerId');
            const loginExpireTime = getQueryParam('loginExpireTime');
            const lang = getQueryParam('lang');

            if (customerId) {
                const rightSideHeader = document.getElementById('right-side-header');
                const signUpLink = document.getElementById('sign-up-link');
                const loginLink = document.getElementById('login-link');

                if (signUpLink) signUpLink.style.display = 'none';
                if (loginLink) loginLink.style.display = 'none';

                const dashboardButton = document.createElement('button');
                dashboardButton.id = 'dashboard-button';
                dashboardButton.className = 'translatable right-header-button';
                dashboardButton.dataset.translationKey = 'dashboard';
                //dashboardButton.textContent = 'Go to Dashboard';

                const url = `dashboard_client.php?customerId=${customerId}&loginExpireTime=${encodeURIComponent(loginExpireTime)}&lang=${lang}`;

                dashboardButton.addEventListener('click', function () {
                    window.location.href = url;
                });

                rightSideHeader.appendChild(dashboardButton);
            }
        });
    </script>
</header>

<body>
<section>
    <div class="id">
        <img src="../Assets/Logo_high_res_center.png" alt="logo" id="img2">
        <p class="slogan translatable" data-translation-key="ourSlogan"></p>
    </div>
    <br></br>
</section>

<main>
    <div class="image-container">
        <img src="../Assets/fest3.png" alt="fest3" id="img8">
        <div class="image-text-title"><strong class="translatable" data-translation-key="stayInDirectContact"></strong>
        </div>
        <div class="image-text-first-line translatable"
             data-translation-key="comfortableAndSafeListeningExperience"></div>
        <div class="image-text-second-line translatable" data-translation-key="championCaresForHealth"></div>
    </div>
    <br>
    <div class="image-container">
        <img src="../Assets/fest1.png" alt="logo" id="img3">
        <div class="image-text-title"><strong class="translatable"
                                              data-translation-key="interactWithFestivals"></strong></div>
        <div class="image-text-second-line translatable" data-translation-key="vote"></div>
    </div>
    <br>
    <div class="image-container">
        <img src="../Assets/fest2.png" alt="fest2" id="img4">
        <div class="image-text-title"><strong class="translatable"
                                              data-translation-key="accessPartnerFestivals"></strong></div>
        <div class="image-text-second-line translatable" data-translation-key="subscriptionBenefits"></div>
    </div>

    <div class="image-container">
        <div class="featured-festivals"><strong class="translatable" data-translation-key="featuredFestivals"></strong>
        </div>
        <br></br>
        <div class="partner-festivals-list translatable" data-translation-key="partnerFestivalsList"
             onclick="showFestivalList()"></div>
    </div>
    <br></br>

    <!-- Carrousel d'images -->
    <div class="image-list">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "app_g10e";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT `IMG-PATH` FROM festival WHERE `IMG-PATH` IS NOT NULL";
        $result = $conn->query($sql);

        $images = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['IMG-PATH'])) {
                    $images[] = $row['IMG-PATH'];
                }
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
        <?php if (!empty($images)) : ?>
            <div class="carousel">
                <div class="carousel-track">
                    <?php foreach ($images as $imagePath) : ?>
                        <div class="carousel-slide">
                            <img src="<?php echo $imagePath; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-buttons">
                    <button onclick="prevImage()">&#10094;</button>
                    <button onclick="nextImage()">&#10095;</button>
                </div>
            </div>
        <?php else : ?>
            <p></p>
        <?php endif; ?>
    </div>
</main>
<div id="festival-popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="hideFestivalList()">&times;</span>
        <h3>Festivals</h3>
        <div class="festival-table">
            <div class="table-header">
                <div class="cell">Name</div>
                <div class="cell">Begin Time</div>
                <div class="cell">End Time</div>
                <div class="cell">Ticket Price</div>
            </div>
            <div class="table-body">
                <?php
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT festivalName, beginTime, endTime, ticketPrice FROM festival";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='table-row'>";
                        echo "<div class='cell'>" . htmlspecialchars($row['festivalName']) . "</div>";
                        echo "<div class='cell'>" . $row['beginTime'] . "</div>";
                        echo "<div class='cell'>" . $row['endTime'] . "</div>";
                        echo "<div class='cell'>" . $row['ticketPrice'] . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='table-row'><div class='cell' colspan='4'>Aucun festival disponible.</div></div>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>


<script src="../Controller/popups.js"></script>
</body>
<br></br>
<br></br>

<?php
include '../Styles/footer.php';
?>

</html>
<script src="../Controller/lang-select.js"></script>

<script>
    let currentIndex = 0;
    const slidesToShow = 3;
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const totalSlides = slides.length;

    function updateCarousel() {
        const slideWidth = slides[0].getBoundingClientRect().width;
        track.style.transform = 'translateX(' + (-currentIndex * slideWidth) + 'px)';
    }

    function nextImage() {
        currentIndex = (currentIndex + slidesToShow) % totalSlides;
        updateCarousel();
    }

    function prevImage() {
        currentIndex = (currentIndex - slidesToShow + totalSlides) % totalSlides;
        updateCarousel();
    }

    function showFestivalList() {
        document.getElementById("festival-popup").style.display = "block";
    }

    function hideFestivalList() {
        document.getElementById("festival-popup").style.display = "none";
    }
</script>
