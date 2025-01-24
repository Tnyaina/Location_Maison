<div class="clients-box">
    <div class="clients-header">
        <h2>Gestion des Clients</h2>
        <div class="search-box">
            <form method="GET" action="<?= BASE_URL ?>/admin/clients">
                <input type="text" name="search" 
                       value="<?= htmlspecialchars($search ?? '') ?>" 
                       placeholder="Rechercher un client...">
                <ion-icon name="search-outline"></ion-icon>
            </form>
        </div>
    </div>

    <table class="clients-table">
        <thead>
            <tr>
                <td>Nom</td>
                <td>Email</td>
                <td>Téléphone</td>
                <td>Statut</td>
                <td>Réservations</td>
                <td>Dernière Réservation</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td><?= htmlspecialchars($client['telephone']) ?></td>
                    <td>
                        <span class="client-status <?= $client['active'] ? 'status-active' : 'status-inactive' ?>">
                            <?= $client['active'] ? 'Actif' : 'Inactif' ?>
                        </span>
                    </td>
                    <td><?= $client['total_reservations'] ?></td>
                    <td>
                        <?= $client['derniere_reservation'] ? 
                            date('d/m/Y', strtotime($client['derniere_reservation'])) : 
                            'Aucune' ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/clients/<?= $client['id'] ?>" 
                           class="btn-action btn-view">
                            <ion-icon name="eye-outline"></ion-icon>
                        </a>
                        <button onclick="toggleStatus(<?= $client['id'] ?>, <?= $client['active'] ? 'false' : 'true' ?>)" 
                                class="btn-action btn-toggle">
                            <ion-icon name="<?= $client['active'] ? 'close-outline' : 'checkmark-outline' ?>"></ion-icon>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleStatus(id, active) {
    if(confirm(`Êtes-vous sûr de vouloir ${active ? 'activer' : 'désactiver'} ce client ?`)) {
        fetch(`<?= BASE_URL ?>/admin/clients/toggle-status`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id, active: active})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) location.reload();
            else alert('Erreur lors de la modification du statut');
        })
        .catch(error => {
            console.error(error);
            alert('Erreur lors de la modification du statut');
        });
    }
}
</script>