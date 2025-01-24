<!-- ============== Cards ================ -->
<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?= count($reservations) ?></div>
            <div class="cardName">Réservations totales</div>
        </div>
        <div class="iconBx">
            <ion-icon name="calendar-outline"></ion-icon>
        </div>
    </div>
</div>

<!-- ================ Filtres ================= -->
<div class="filters-container">
    <form method="GET" action="<?= BASE_URL ?>/admin/reservations" class="filters-form">
        <select name="habitation_id">
            <option value="">Toutes les habitations</option>
            <?php foreach ($habitations as $habitation): ?>
                <option value="<?= $habitation['id'] ?>" <?= isset($filters['habitation_id']) && $filters['habitation_id'] == $habitation['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($habitation['description']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date_debut" value="<?= $filters['date_debut'] ?? '' ?>">
        <input type="date" name="date_fin" value="<?= $filters['date_fin'] ?? '' ?>">
        <button type="submit" class="btn-filter">
            <ion-icon name="search-outline"></ion-icon> Filtrer
        </button>
    </form>
</div>

<!-- ================ Réservations List ================= -->
<div class="details">
    <div class="recentOrders">
        <div class="cardHeader">
            <h2>Liste des Réservations</h2>
        </div>
        <table class="reservations-table">
            <thead>
                <tr>
                    <td>Habitation</td>
                    <td>Client</td>
                    <td>Période</td>
                    <td>Durée</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['habitation_description']) ?></td>
                        <td>
                            <?= htmlspecialchars($reservation['client_nom']) ?><br>
                            <small><?= htmlspecialchars($reservation['client_email']) ?></small>
                        </td>
                        <td>
                            <?= date('d/m/Y', strtotime($reservation['date_arrivee'])) ?> -<br>
                            <?= date('d/m/Y', strtotime($reservation['date_depart'])) ?>
                        </td>
                        <td><?= date_diff(date_create($reservation['date_arrivee']), date_create($reservation['date_depart']))->format('%a jours') ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>" class="btn-action btn-view">
                                <ion-icon name="eye-outline"></ion-icon>
                            </a>
                            <button onclick="confirmerAnnulation(<?= $reservation['id'] ?>)" class="btn-action btn-delete">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
            if (data.success) location.reload();
            else alert('Erreur lors de l\'annulation : ' + data.error);
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation');
        });
    }
}
</script>