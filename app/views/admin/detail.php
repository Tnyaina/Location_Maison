<div class="details-wrapper">
    <div class="details-header">
        <h2>Détails de l'habitation</h2>
    </div>

    <div class="details-grid">
        <div class="details-item">
            <div class="details-label">Type</div>
            <div class="details-value"><?= htmlspecialchars($habitation['type_nom']) ?></div>
        </div>
        
        <div class="details-item">
            <div class="details-label">Nombre de chambres</div>
            <div class="details-value"><?= htmlspecialchars($habitation['nb_chambres']) ?></div>
        </div>
        
        <div class="details-item">
            <div class="details-label">Loyer par jour</div>
            <div class="details-value"><?= htmlspecialchars($habitation['loyer_jour']) ?> €</div>
        </div>
        
        <div class="details-item">
            <div class="details-label">Quartier</div>
            <div class="details-value"><?= htmlspecialchars($habitation['quartier']) ?></div>
        </div>
        
        <div class="details-item details-description">
            <div class="details-label">Description</div>
            <div class="details-value"><?= nl2br(htmlspecialchars($habitation['description'])) ?></div>
        </div>
    </div>

    <div class="details-photos">
        <div class="details-header">
            <h2>Photos</h2>
        </div>
        
        <div class="photos-grid">
            <?php foreach($habitation['photos'] as $photo): ?>
                <div class="photo-item">
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($photo['url_photo']) ?>" alt="Photo de l'habitation">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>