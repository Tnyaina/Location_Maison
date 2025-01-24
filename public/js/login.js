document.getElementById('loginForm').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    // Réinitialiser les messages d'erreur
    emailError.style.display = 'none';
    passwordError.style.display = 'none';

    // Validation de l'email
    if (!email.value.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
        emailError.textContent = 'Veuillez entrer une adresse email valide';
        emailError.style.display = 'block';
        email.parentElement.classList.add('shake');
        isValid = false;
    }

    // Validation du mot de passe
    if (password.value.length < 6) {
        passwordError.textContent = 'Le mot de passe doit contenir au moins 6 caractères';
        passwordError.style.display = 'block';
        password.parentElement.classList.add('shake');
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