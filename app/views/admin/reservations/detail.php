<div class="details">
    <div class="recentOrders">
        <div class="cardHeader">
            <h2>Détails de la réservation #<?= htmlspecialchars($reservation['id']) ?></h2>
            <a href="<?= BASE_URL ?>/admin/reservations" class="btn-action btn-return">
            <ion-icon name="arrow-back-outline"></ion-icon> Retour
            </a>
            <button onclick="confirmerAnnulation(<?= $reservation['id'] ?>)" class="btn-action btn-delete">
                <ion-icon name="trash-outline"></ion-icon> Annuler
            </button>
        </div>

        <div class="reservation-details">
            <div class="detail-card">
                <h3>
                    <ion-icon name="home-outline"></ion-icon> 
                    Information Habitation
                </h3>
                <div class="info-row">
                    <span>Description:</span>
                    <span><?= htmlspecialchars($reservation['habitation_description']) ?></span>
                </div>
            </div>

            <div class="detail-card">
                <h3>
                    <ion-icon name="person-outline"></ion-icon>
                    Information Client
                </h3>
                <div class="info-row">
                    <span>Nom:</span>
                    <span><?= htmlspecialchars($reservation['client_nom']) ?></span>
                </div>
                <div class="info-row">
                    <span>Email:</span>
                    <span><?= htmlspecialchars($reservation['client_email']) ?></span>
                </div>
                <div class="info-row">
                    <span>Téléphone:</span>
                    <span><?= htmlspecialchars($reservation['client_telephone']) ?></span>
                </div>
            </div>

            <div class="detail-card">
                <h3>
                    <ion-icon name="calendar-outline"></ion-icon>
                    Détails du séjour
                </h3>
                <div class="info-row">
                    <span>Arrivée:</span>
                    <span><?= date('d/m/Y', strtotime($reservation['date_arrivee'])) ?></span>
                </div>
                <div class="info-row">
                    <span>Départ:</span>
                    <span><?= date('d/m/Y', strtotime($reservation['date_depart'])) ?></span>
                </div>
                <div class="info-row">
                    <span>Durée:</span>
                    <span><?= date_diff(date_create($reservation['date_arrivee']), date_create($reservation['date_depart']))->format('%a jours') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmerAnnulation(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
        fetch('<?= BASE_URL ?>/admin/reservations/annuler', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) window.location.href = '<?= BASE_URL ?>/admin/reservations';
            else alert('Erreur lors de l\'annulation: ' + data.error);
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation');
        });
    }
}
</script>