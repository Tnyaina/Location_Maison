<div class="cardBox">
    <div class="actions-container">
        <button class="btn-add" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Ajouter une habitation
        </button>
    </div>

    <div class="details">
        <div class="recentOrders">
            <div class="cardHeader">
                <h2>Liste des Habitations</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Type</th>
                        <th>Chambres</th>
                        <th>Loyer/Jour</th>
                        <th>Quartier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($habitations as $habitation): ?>
                        <tr>
                            <td>
                                <?php if ($habitation['photo_principale']): ?>
                                    <img src="<?= BASE_URL . '/' . htmlspecialchars($habitation['photo_principale']) ?>"
                                        alt="Photo principale"
                                        class="habitation-img">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($habitation['type_nom']) ?></td>
                            <td><?= htmlspecialchars($habitation['nb_chambres']) ?></td>
                            <td><?= htmlspecialchars($habitation['loyer_jour']) ?> €</td>
                            <td><?= htmlspecialchars($habitation['quartier']) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>/admin/detail/<?= $habitation['id'] ?>" class="btn-action view">
                                    <ion-icon name="eye-outline"></ion-icon>
                                </a>
                                <button class="btn-action edit" onclick="openEditModal(<?= $habitation['id'] ?>)">
                                    <ion-icon name="create-outline"></ion-icon>
                                </button>
                                <button class="btn-action delete" onclick="confirmDelete(<?= $habitation['id'] ?>)">
                                    <ion-icon name="trash-outline"></ion-icon>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajout/Modification -->
<div id="habitationModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Ajouter une habitation</h2>
        <form id="habitationForm" method="POST" action="<?= BASE_URL ?>/admin/ajouter-habitation" enctype="multipart/form-data">
            <div class="form-group">
                <label for="type_id">Type d'habitation</label>
                <select name="type_id" id="type_id" required>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nb_chambres">Nombre de chambres</label>
                <input type="number" name="nb_chambres" id="nb_chambres" required min="0">
            </div>
            <div class="form-group">
                <label for="loyer_jour">Loyer par jour (€)</label>
                <input type="number" name="loyer_jour" id="loyer_jour" required step="0.01" min="0">
            </div>
            <div class="form-group">
                <label for="quartier">Quartier</label>
                <input type="text" name="quartier" id="quartier" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="photos">Photos</label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/*">
            </div>
            <input type="hidden" name="id" id="habitation_id">
            <div class="form-actions">
                <button type="submit" class="btn">Enregistrer</button>
                <button type="button" onclick="closeModal()" class="btn cancel">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
// Garder le même JavaScript mais déplacer dans un fichier séparé
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Ajouter une habitation';
    document.getElementById('habitationForm').reset();
    document.getElementById('habitationForm').action = '<?= BASE_URL ?>/admin/ajouter-habitation';
    document.getElementById('habitationModal').style.display = 'block';
}

function openEditModal(id) {
    document.getElementById('modalTitle').textContent = 'Modifier l\'habitation';
    document.getElementById('habitation_id').value = id;
    document.getElementById('habitationForm').action = '<?= BASE_URL ?>/admin/modifier-habitation';

    fetch(`<?= BASE_URL ?>/admin/habitation/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('type_id').value = data.type_id;
            document.getElementById('nb_chambres').value = data.nb_chambres;
            document.getElementById('loyer_jour').value = data.loyer_jour;
            document.getElementById('quartier').value = data.quartier;
            document.getElementById('description').value = data.description;
            document.getElementById('habitationModal').style.display = 'block';
        })
        .catch(error => {
            alert('Erreur lors du chargement des données');
            console.error(error);
        });
}

function closeModal() {
    document.getElementById('habitationModal').style.display = 'none';
}

function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette habitation ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/supprimer-habitation';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

window.onclick = function(event) {
    const modal = document.getElementById('habitationModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>