class NotificationManager {
    constructor() {
        this.createContainer();
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        document.body.appendChild(container);
    }

    show(message, type = 'error', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        notification.innerHTML = `
            <i class="notification-icon fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        `;

        document.getElementById('notification-container').appendChild(notification);

        // Animation d'entrée
        setTimeout(() => notification.classList.add('show'), 10);

        // Gestionnaire pour fermer la notification
        const close = () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        };

        notification.querySelector('.notification-close').addEventListener('click', close);

        // Fermeture automatique après la durée spécifiée
        if (duration) {
            setTimeout(close, duration);
        }
    }
}

// Initialiser le gestionnaire de notifications
const notificationManager = new NotificationManager();

// Modifier la validation du formulaire de connexion
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');

    // Validation de l'email
    if (!email.value.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
        notificationManager.show('Veuillez entrer une adresse email valide');
        email.parentElement.classList.add('shake');
        isValid = false;
    }

    // Validation du mot de passe
    if (password.value.length < 6) {
        notificationManager.show('Le mot de passe doit contenir au moins 6 caractères');
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

// Modifier la validation du formulaire d'inscription
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');

    // Validation de l'email
    if (!email.value.match(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/)) {
        notificationManager.show('Veuillez entrer une adresse email valide');
        email.parentElement.classList.add('shake');
        isValid = false;
    }

    // Validation du mot de passe
    if (password.value.length < 8) {
        notificationManager.show('Le mot de passe doit contenir au moins 8 caractères');
        password.parentElement.classList.add('shake');
        isValid = false;
    }

    // Validation de la confirmation du mot de passe
    if (password.value !== confirmPassword.value) {
        notificationManager.show('Les mots de passe ne correspondent pas');
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
