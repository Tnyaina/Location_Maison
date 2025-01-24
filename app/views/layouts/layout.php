<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des Habitations</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/details.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/types.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/crud.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clients.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/reservations.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/loaders.css">
</head>

<body>

    <div id="loader-container">
        <div aria-label="Orange and tan hamster running in a metal wheel" role="img" class="wheel-and-hamster">
            <div class="wheel"></div>
            <div class="hamster">
                <div class="hamster__body">
                    <div class="hamster__head">
                        <div class="hamster__ear"></div>
                        <div class="hamster__eye"></div>
                        <div class="hamster__nose"></div>
                    </div>
                    <div class="hamster__limb hamster__limb--fr"></div>
                    <div class="hamster__limb hamster__limb--fl"></div>
                    <div class="hamster__limb hamster__limb--br"></div>
                    <div class="hamster__limb hamster__limb--bl"></div>
                    <div class="hamster__tail"></div>
                </div>
            </div>
            <div class="spoke"></div>
        </div>
    </div>


    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="<?= BASE_URL ?>/admin">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Gestion Habitations</span>
                    </a>
                </li>

                <li class="<?= $currentPage === 'home' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/admin">
                        <span class="icon">
                            <ion-icon name="grid-outline"></ion-icon>
                        </span>
                        <span class="title">Tableau de bord</span>
                    </a>
                </li>

                <li class="<?= $currentPage === 'types' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/admin/types">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Types d'habitations</span>
                    </a>
                </li>

                <li class="<?= $currentPage === 'reservations' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/admin/reservations">
                        <span class="icon">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </span>
                        <span class="title">Réservations</span>
                    </a>
                </li>


                <li class="<?= $currentPage === 'clients' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>/admin/clients">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Clients</span>
                    </a>
                </li>

                <li>
                    <a href="<?= BASE_URL ?>/logout">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Rechercher...">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>

                <div class="user">
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="user-info">
                            <img src="<?= BASE_URL ?>/assets/img/ETU003080.jpg" alt="User Image" class="user-image">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Conteneur principal pour le contenu dynamique -->
            <div class="content-container">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success'] ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Le contenu spécifique de chaque page sera injecté ici -->
                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="<?= BASE_URL ?>/assets/js/layout.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById('loader-container');

            // Délai minimum en millisecondes
            const minimumDisplayTime = 800; // Par exemple, 3 secondes

            const startTime = new Date().getTime(); // Enregistre le début du chargement

            window.addEventListener('load', function() {
                const currentTime = new Date().getTime();
                const elapsedTime = currentTime - startTime;

                // Si le chargement est trop rapide, attendre le délai minimum
                const remainingTime = minimumDisplayTime - elapsedTime;

                setTimeout(() => {
                    if (loader) {
                        loader.style.display = 'none';
                    }
                }, Math.max(remainingTime, 0)); // Garantit que le délai n'est pas négatif
            });
        });
    </script>
</body>

</html>