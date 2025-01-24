<?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
    <div class="messages-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="reservation-message error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="reservation-message success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


<div class="card-wrapper">
    <div class="card">
        <!-- Images de la propriété -->
        <div class="product-imgs">
            <div class="img-display">
                <div class="img-showcase">
                    <?php if (!empty($property['photos'])): ?>
                        <?php foreach ($property['photos'] as $photo): ?>
                            <img src="<?= BASE_URL ?>/<?= $photo['url_photo'] ?>" alt="Vue de la propriété">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/img/default-property.jpg" alt="Image par défaut">
                    <?php endif; ?>
                </div>
                <?php if (!empty($property['photos']) && count($property['photos']) > 1): ?>
                    <button class="arrow left-arrow"><i class="fas fa-chevron-left"></i></button>
                    <button class="arrow right-arrow"><i class="fas fa-chevron-right"></i></button>
                <?php endif; ?>
            </div>
            <?php if (!empty($property['photos']) && count($property['photos']) > 1): ?>
                <div class="img-select">
                    <?php foreach ($property['photos'] as $index => $photo): ?>
                        <div class="img-item">
                            <a href="#" data-id="<?= $index + 1 ?>">
                                <img src="<?= BASE_URL ?>/<?= $photo['url_photo'] ?>" alt="Vue de la propriété">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Informations de la propriété -->
        <div class="product-content">
        <div id="reservation-message" class="reservation-message"></div>
            <span class="product-link"><?= htmlspecialchars($property['type_nom']) ?></span>
            <h2 class="product-title"><?= htmlspecialchars($property['quartier']) ?></h2>
            
            <div class="product-price">
                <p class="new-price">Prix: <span><?= number_format($property['loyer_jour'], 2) ?>€/jour</span></p>
            </div>

            <div class="product-detail">
                <h2>Caractéristiques: </h2>
                <p><?= htmlspecialchars($property['description']) ?></p>
                <ul>
                    <li>Quartier: <span><?= htmlspecialchars($property['quartier']) ?></span></li>
                    <li>Chambres: <span><?= htmlspecialchars($property['nb_chambres']) ?></span></li>
                    <li>Type: <span><?= htmlspecialchars($property['type_nom']) ?></span></li>
                </ul>
            </div>

            <div class="purchase-info">
                <form id="reservationForm" action="<?= BASE_URL ?>/reservation/create" method="POST">
                    <input type="hidden" name="habitation_id" value="<?= $property['id'] ?>">
                    <div class="date-inputs" style="margin-bottom: 1rem;">
                        <input type="date" id="date_arrivee" name="date_arrivee" placeholder="Date d'arrivée" class="flatpickr" required>
                        <input type="date" id="date_depart" name="date_depart" placeholder="Date de départ" class="flatpickr" required>
                    </div>
                    <button type="submit" class="btn ">
                        Réserver maintenant <i class="fas fa-calendar-check"></i>
                    </button>
                    <a href="<?= BASE_URL ?>/accueil" class="btn">
                        Retour <i class="fas fa-arrow-left"></i>
                    </a>
                </form>
            </div>

            <div class="social-links">
                <p>Partager: </p>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour le datepicker -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationMessage = document.getElementById('reservation-message');

    // Configure Flatpickr for arrival date
    flatpickr("#date_arrivee", {
        minDate: "today",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            departurePicker.set("minDate", selectedDates[0]);
        }
    });

    // Configure Flatpickr for departure date
    const departurePicker = flatpickr("#date_depart", {
        minDate: "today",
        dateFormat: "Y-m-d"
    });

    // Handle reservation form submission
    const form = document.getElementById('reservationForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const dateArrivee = document.getElementById('date_arrivee').value;
            const dateDepart = document.getElementById('date_depart').value;
            const habitationId = document.querySelector('[name="habitation_id"]').value;
            
            try {
                // Check availability
                const checkResponse = await fetch(`${BASE_URL}/admin/reservations/check-disponibilite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        habitation_id: habitationId,
                        date_arrivee: dateArrivee,
                        date_depart: dateDepart
                    })
                });
                
                const data = await checkResponse.json();

                if (data.disponible) {
                    // Success: submit the form
                    reservationMessage.style.display = 'none'; // Masquer le message
                    reservationMessage.classList.remove('show'); // Masquer avec la classe
                    form.submit();
                } else {
                    // Show error message
                    reservationMessage.textContent = 'Cette période n\'est pas disponible pour cette propriété.';
                    reservationMessage.className = 'reservation-message error';
                    reservationMessage.style.display = 'block'; // Afficher le message
                    reservationMessage.classList.add('show'); // Ajouter la classe show pour l'animation
                }
            } catch (error) {
                console.error('Erreur lors de la vérification:', error);
                reservationMessage.textContent = 'Une erreur est survenue lors de la vérification de disponibilité.';
                reservationMessage.className = 'reservation-message error';
                reservationMessage.style.display = 'block'; // Afficher le message
                reservationMessage.classList.add('show'); // Ajouter la classe show pour l'animation
            }
        });
    }
});
</script>