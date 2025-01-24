<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?php echo Flight::view()->get('title', 'Makaan - Real Estate'); ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="<?= BASE_URL ?>/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?= BASE_URL ?>/lib/animate/animate.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= BASE_URL ?>/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?= BASE_URL ?>/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/css/details.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                <a href="<?= BASE_URL ?>/accueil" class="navbar-brand d-flex align-items-center text-center">
                    <div class="icon p-2 me-2">
                        <img class="img-fluid" src="<?= BASE_URL ?>/img/icon-deal.png" alt="Icon" style="width: 30px; height: 30px;">
                    </div>
                    <h1 class="m-0 text-primary">ImmoLoco</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <?php foreach ($navItems as $name => $url): ?>
                            <a href="<?= BASE_URL ?>/<?= $url ?>" class="nav-item nav-link <?= Flight::request()->url == $url ? 'active' : '' ?>"><?= $name ?></a>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= BASE_URL ?>/logout" class="btn btn-primary px-3 d-none d-lg-flex">Deconnexion</a>
                </div>
            </nav>
        </div>

        <!-- Category Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Types de Propriete</h1>
                    <p>Tongasoa eto amin'ny ImmoLoco.</p>
                </div>
                <div class="row g-4">
                    <?php foreach ($propertyTypes as $type => $data): ?>
                        <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                            <a class="cat-item d-block bg-light text-center rounded p-3" href="#">
                                <div class="rounded p-4">
                                    <div class="icon mb-3">
                                        <img class="img-fluid" src="<?= BASE_URL ?>/img/<?= $data['icon'] ?>" alt="Icon">
                                    </div>
                                    <h6><?= $type ?></h6>
                                    <span><?= $data['count'] ?> Properties</span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Include Search and Property List -->
        <?php echo $content; ?>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/lib/wow/wow.min.js"></script>
    <script src="<?= BASE_URL ?>/lib/easing/easing.min.js"></script>
    <script src="<?= BASE_URL ?>/lib/waypoints/waypoints.min.js"></script>
    <script src="<?= BASE_URL ?>/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="<?= BASE_URL ?>/js/main.js"></script>
    <script src="<?= BASE_URL ?>/js/details.js"></script>
</body>

</html>