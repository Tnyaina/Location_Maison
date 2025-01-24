<div class="types-wrapper">
    <div class="types-header">
        <h2>Types d'habitations</h2>
        <button class="btn-add" onclick="openAddModal()">
            <ion-icon name="add-outline"></ion-icon>
            Ajouter un type
        </button>
    </div>

    <div class="types-content">
        <table class="types-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Nombre d'habitations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($types as $type): ?>
                <tr>
                    <td><?= htmlspecialchars($type['id']) ?></td>
                    <td><?= htmlspecialchars($type['nom']) ?></td>
                    <td><?= htmlspecialchars($type['nb_habitations']) ?></td>
                    <td>
                        <button class="btn-action apart" onclick="openEditModal(<?= $type['id'] ?>, '<?= htmlspecialchars($type['nom']) ?>')">
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <?php if($type['nb_habitations'] == 0): ?>
                        <button class="btn-action" onclick="confirmDelete(<?= $type['id'] ?>)">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="typeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Ajouter un type</h3>
            </div>
            <form id="typeForm" method="POST" class="modal-form">
                <div class="form-group">
                    <input type="text" name="nom" id="nom" placeholder="Nom du type" required>
                </div>
                <input type="hidden" name="id" id="type_id">
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Annuler</button>
                    <button type="submit" class="btn-add">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const openAddModal = () => {
    document.getElementById('modalTitle').textContent = 'Ajouter un type';
    document.getElementById('typeForm').reset();
    document.getElementById('typeForm').action = '<?= BASE_URL ?>/admin/types/ajouter';
    document.getElementById('typeModal').style.display = 'block';
}

const openEditModal = (id, nom) => {
    document.getElementById('modalTitle').textContent = 'Modifier le type';
    document.getElementById('type_id').value = id;
    document.getElementById('nom').value = nom;
    document.getElementById('typeForm').action = '<?= BASE_URL ?>/admin/types/modifier';
    document.getElementById('typeModal').style.display = 'block';
}

const closeModal = () => {
    document.getElementById('typeModal').style.display = 'none';
}

const confirmDelete = (id) => {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce type ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/types/supprimer';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

window.onclick = (event) => {
    if (event.target == document.getElementById('typeModal')) {
        closeModal();
    }
}
</script>