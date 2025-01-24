<div class="clients-box">
    <div class="clients-header">
        <h2>Détails du Client: <?= htmlspecialchars($client['nom']) ?></h2>
        <a href="<?= BASE_URL ?>/admin/clients" class="btn-action btn-return">
            <ion-icon name="arrow-back-outline"></ion-icon> Retour
        </a>
    </div>

    <div class="client-details">
        <div class="detail-card">
            <h3><ion-icon name="person-outline"></ion-icon> Informations Personnelles</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Nom:</span>
                    <span class="value"><?= htmlspecialchars($client['nom']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value"><?= htmlspecialchars($client['email']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Téléphone:</span>
                    <span class="value"><?= htmlspecialchars($client['telephone']) ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Statut:</span>
                    <span class="client-status <?= $client['active'] ? 'status-active' : 'status-inactive' ?>">
                        <?= $client['active'] ? 'Actif' : 'Inactif' ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3><ion-icon name="stats-chart-outline"></ion-icon> Statistiques</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Total Réservations:</span>
                    <span class="value"><?= $client['total_reservations'] ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Dernière Réservation:</span>
                    <span class="value">
                        <?= $client['derniere_reservation'] ? 
                            date('d/m/Y', strtotime($client['derniere_reservation'])) : 
                            'Aucune' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="reservations-list">
        <h3><ion-icon name="calendar-outline"></ion-icon> Historique des Réservations</h3>
        <?php foreach ($reservations as $reservation): ?>
            <div class="reservation-item">
                <div class="reservation-header">
                    <h4><?= htmlspecialchars($reservation['habitation_description']) ?></h4>
                    <div class="reservation-dates">
                        <ion-icon name="time-outline"></ion-icon>
                        Du <?= date('d/m/Y', strtotime($reservation['date_arrivee'])) ?>
                        au <?= date('d/m/Y', strtotime($reservation['date_depart'])) ?>
                        (<?= $reservation['duree_sejour'] ?> jours)
                    </div>
                </div>
                <div class="reservation-details">
                    <div class="price-per-day">
                        <ion-icon name="cash-outline"></ion-icon>
                        Prix/jour: <?= number_format($reservation['loyer_jour'], 2) ?> €
                    </div>
                    <div class="total-price">
                        Total: <?= number_format($reservation['cout_total'], 2) ?> €
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if(empty($reservations)): ?>
            <div class="no-reservations">
                <ion-icon name="alert-circle-outline"></ion-icon>
                Aucune réservation trouvée
            </div>
        <?php endif; ?>
    </div>
</div>