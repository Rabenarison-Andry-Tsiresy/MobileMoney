<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Back-Office | Mobile Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; }
        .navbar { border-bottom: 1px solid #333; }
    </style>
</head>
<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand text-primary fw-bold" href="#">Admin Money</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('operateur') ?>">Opérateurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('prefixe') ?>">Préfixes</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('operation') ?>">Opérations</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('tarif') ?>">Tarifs</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Rapports</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('rapport/gains') ?>">Gains</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('rapport/compte') ?>">Comptes Clients</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="<?= base_url('admin/logout') ?>" class="btn btn-outline-danger btn-sm">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- C'est ici que tes pages vont s'afficher -->
    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Script pour le menu déroulant (dropdown) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>