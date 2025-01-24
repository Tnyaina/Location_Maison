<div class="container-xxl py-5">
    <div class="container">
        <!-- Search Section -->
        <div class="bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
            <form method="GET" action="">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="description" class="form-control border-0 py-3"
                                    placeholder="La description" value="<?= $filters['description'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="type_id" class="form-select border-0 py-3">
                                    <option value="">Type de propriété</option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?= $type['id'] ?>" <?= ($filters['type_id'] == $type['id'] ? 'selected' : '') ?>>
                                            <?= $type['nom'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="prix_min" class="form-control border-0 py-3"
                                    placeholder="Prix min" value="<?= $filters['prix_min'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="prix_max" class="form-control border-0 py-3"
                                    placeholder="Prix max" value="<?= $filters['prix_max'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="nb_chambres" class="form-select border-0 py-3">
                                    <option value="">Nombre de chambres</option>
                                    <option value="1" <?= ($filters['nb_chambres'] == '1' ? 'selected' : '') ?>>1 chambre</option>
                                    <option value="2" <?= ($filters['nb_chambres'] == '2' ? 'selected' : '') ?>>2 chambres</option>
                                    <option value="3+" <?= ($filters['nb_chambres'] == '3+' ? 'selected' : '') ?>>3+ chambres</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark border-0 w-100 py-3">Rechercher</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Property List Section -->
        <div class="row g-4">
            <?php if (empty($properties)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        Aucune propriété ne correspond à vos critères de recherche.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($properties as $property): ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-item rounded overflow-hidden">
                            <div class="position-relative overflow-hidden" style="height: 250px;">
                                <?php if (!empty($property['photo_principale'])): ?>
                                    <img class="img-fluid w-100 h-100"
                                        src="<?= htmlspecialchars($property['photo_principale']) ?>"
                                        alt="Vue de la propriété"
                                        style="object-fit: cover;">
                                <?php else: ?>
                                    <img class="img-fluid w-100 h-100"
                                        src="img/no-image.jpg"
                                        alt="Image non disponible"
                                        style="object-fit: cover;">
                                <?php endif; ?>

                                <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                    À Louer
                                </div>
                                <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                                    <?= htmlspecialchars($property['type_nom']) ?>
                                </div>
                            </div>
                            <div class="p-4 pb-0">
                                <h5 class="text-primary mb-3"><?= number_format($property['loyer_jour'], 0, ',', ' ') ?>€/jour</h5>
                                <a href="<?= BASE_URL ?>/property/<?= $property['id'] ?>" class="property-link">
                                    <?= htmlspecialchars($property['type_nom']) ?> - <?= htmlspecialchars($property['quartier']) ?>
                                </a>
                                <p>
                                    <strong class="text-truncate">
                                        <?= htmlspecialchars(substr($property['description'], 0, 100)) . (strlen($property['description']) > 100 ? '...' : '') ?>
                                    </strong>
                                </p>
                                <p>
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    <?= htmlspecialchars($property['quartier']) ?>
                                </p>
                            </div>
                            <div class="d-flex border-top">
                                <small class="flex-fill text-center py-2">
                                    <i class="fa fa-bed text-primary me-2"></i>
                                    <?= htmlspecialchars($property['nb_chambres']) ?> Chambres
                                </small>
                                <small class="flex-fill text-center border-start py-2">
                                    <i class="fa fa-info-circle text-primary me-2"></i>
                                    <a href="<?= BASE_URL ?>/property/<?= $property['id'] ?>" class="text-decoration-none">Plus d'infos</a>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>