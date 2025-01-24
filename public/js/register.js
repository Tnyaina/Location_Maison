
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    // Réinitialiser les messages d'erreur
    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

    // Validation de l'email
    if (!email.value.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
        document.getElementById('emailError').textContent = 'Veuillez entrer une adresse email valide';
        document.getElementById('emailError').style.display = 'block';
        email.parentElement.classList.add('shake');
        isValid = false;
    }

    // Validation du mot de passe
    const passwordRegex = {
        length: /.{8,}/,
        uppercase: /[A-Z]/,
        lowercase: /[a-z]/,
        number: /[0-9]/
    };

    Object.entries(passwordRegex).forEach(([key, regex]) => {
        const element = document.getElementById(key);
        if (regex.test(password.value)) {
            element.classList.add('valid');
            element.innerHTML = '✓ ' + element.textContent;
        } else {
            element.classList.remove('valid');
            isValid = false;
        }
    });

    // Validation de la confirmation du mot de passe
    if (password.value !== confirmPassword.value) {
        document.getElementById('confirmPasswordError').textContent = 'Les mots de passe ne correspondent pas';
        document.getElementById('confirmPasswordError').style.display = 'block';
        confirmPassword.parentElement.classList.add('shake');
        isValid = false;
    }

    // Supprimer la classe shake après l'animation
    setTimeout(() => {
        document.querySelectorAll('.shake').forEach(el => el.classList.remove('shake'));
    }, 820);

    if (!isValid) {
        e.preventDefault();
    }
});

// Validation en temps réel du mot de passe
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const requirements = {
        length: /.{8,}/,
        uppercase: /[A-Z]/,
        lowercase: /[a-z]/,
        number: /[0-9]/
    };

    Object.entries(requirements).forEach(([key, regex]) => {
        const element = document.getElementById(key);
        if (regex.test(password)) {
            element.classList.add('valid');
            element.innerHTML = '✓ ' + element.textContent.replace('✓ ', '');
        } else {
            element.classList.remove('valid');
            element.textContent = element.textContent.replace('✓ ', '');
        }
    });
});