<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Connexion - Location</title>
</head>

<body>

    <div class="theme-switch">
        <label class="switch">
            <input type="checkbox" id="themeToggle">
            <span class="slider"></span>
        </label>
    </div>
    <svg class="login__blob" viewBox="0 0 566 840" xmlns="http://www.w3.org/2000/svg">
        <mask id="mask0" mask-type="alpha">
            <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538 
            0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393 
            591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824 
            167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z" />
        </mask>
        <g mask="url(#mask0)">
            <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538 
            0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393 
            591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824 
            167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z" />
            <image class="login__img" href="assets/img/p.jpg" />
        </g>
    </svg>

    <div class="login container grid" id="loginAccessRegister">
        <!-- Section Connexion -->
        <div class="login__access">
            <h1 class="login__title">Connecte toi !</h1>

            <?php if (isset($error)): ?>
                <div class="alert alert--error">
                    <?php if (is_array($error)): ?>
                        <div>Plusieurs erreurs sont survenues :</div>
                        <ul class="alert__list">
                            <?php foreach ($error as $err): ?>
                                <li class="alert__list-item"><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <?php echo $error; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert--success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div class="login__area">
                <form action="<?= BASE_URL ?>/login" method="POST" class="login__form">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="email" id="email" name="email" required placeholder=" " class="login__input">
                            <label for="email" class="login__label">Email</label>
                            <i class="ri-mail-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="password" id="password" name="password" required placeholder=" " class="login__input">
                            <label for="password" class="login__label">Mot de passe</label>
                            <i class="ri-eye-off-fill login__icon login__password" id="loginPassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="login__button">Se connecter</button>
                </form>

                <p class="login__switch">
                    Pas encore de compte ?
                    <button id="loginButtonRegister">Créer un compte</button>
                </p>
            </div>
        </div>

        <!-- Section Inscription -->
        <div class="login__register">
            <h1 class="login__title">Créer un nouveau compte</h1>

            <?php if (isset($_SESSION['register_error'])): ?>
                <div class="alert alert--error">
                    <?php if (is_array($_SESSION['register_error'])): ?>
                        <div>Veuillez corriger les erreurs suivantes :</div>
                        <ul class="alert__list">
                            <?php foreach ($_SESSION['register_error'] as $err): ?>
                                <li class="alert__list-item"><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <?php echo $_SESSION['register_error']; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="login__area">
                <form action="<?= BASE_URL ?>/inscription" method="POST" class="login__form">
                    <div class="login__content grid">
                        <div class="login__box">
                            <input type="text" id="nom" name="nom" required placeholder=" " class="login__input">
                            <label for="nom" class="login__label">Nom complet</label>
                            <i class="ri-user-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="email" id="email" name="email" required placeholder=" " class="login__input">
                            <label for="email" class="login__label">Email</label>
                            <i class="ri-mail-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="tel" id="telephone" name="telephone" required placeholder=" " class="login__input">
                            <label for="telephone" class="login__label">Téléphone</label>
                            <i class="ri-phone-fill login__icon"></i>
                        </div>

                        <div class="login__box">
                            <input type="password" id="password" name="password" required placeholder=" " class="login__input">
                            <label for="password" class="login__label">Mot de passe</label>
                            <i class="ri-eye-off-fill login__icon login__password" id="registerPassword"></i>
                        </div>

                        <div class="login__box">
                            <input type="password" id="confirmPassword" name="confirmPassword" required placeholder=" " class="login__input">
                            <label for="confirmPassword" class="login__label">Confirmer le mot de passe</label>
                            <i class="ri-eye-off-fill login__icon login__password" id="registerConfirmPassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="login__button">Créer le compte</button>
                </form>

                <p class="login__switch">
                    Déjà un compte ?
                    <button id="loginButtonAccess">Se connecter</button>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = (inputId, iconId) => {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ri-eye-off-fill', 'ri-eye-fill');
            } else {
                input.type = 'password';
                icon.classList.replace('ri-eye-fill', 'ri-eye-off-fill');
            }
        };

        const themeToggle = document.getElementById('themeToggle');

        // Check for saved theme preference
        const getCurrentTheme = () => {
            return localStorage.getItem('theme') || 'light';
        };

        // Apply theme
        const applyTheme = (theme) => {
            document.documentElement.setAttribute('data-theme', theme);
            themeToggle.checked = theme === 'dark';
        };

        // Initial theme setup
        applyTheme(getCurrentTheme());

        // Theme toggle event listener
        themeToggle.addEventListener('change', (e) => {
            const theme = e.target.checked ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            applyTheme(theme);
        });

        // Switch between login and register forms
        document.getElementById('loginButtonRegister').addEventListener('click', () => {
            document.getElementById('loginAccessRegister').classList.add('active');
        });

        document.getElementById('loginButtonAccess').addEventListener('click', () => {
            document.getElementById('loginAccessRegister').classList.remove('active');
        });

        // Password visibility toggles
        document.getElementById('loginPassword').addEventListener('click', () => {
            togglePassword('password', 'loginPassword');
        });

        document.getElementById('registerPassword').addEventListener('click', () => {
            togglePassword('registerPassword', 'registerPassword');
        });

        document.getElementById('registerConfirmPassword').addEventListener('click', () => {
            togglePassword('confirmPassword', 'registerConfirmPassword');
        });
    </script>
</body>

</html>