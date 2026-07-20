<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Situation des Comptes Clients</h2>

    <!-- Formulaire de recherche -->
    <form action="<?= base_url('rapport/comptes') ?>" method="get" class="mb-4 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un nom ou un numéro..." value="<?= isset($recherche) ? $recherche : '' ?>">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <a href="<?= base_url('rapport/comptes') ?>" class="btn btn-secondary ms-2">Réinitialiser</a>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>Nom Client</th>
            <th>Numéro</th>
            <th>Opérateur</th>
            <th>Solde Actuel (Ar)</th>
            <th>Date création</th>
        </tr>
        <?php foreach ($comptes as $c) : ?>
            <tr>
                <td><?= $c['client_nom'] ?></td>
                <td><strong><?= $c['numero'] ?></strong></td>
                <td><?= $c['operateur_nom'] ?></td>
                <td class="<?= $c['solde'] > 0 ? 'text-primary' : 'text-danger' ?> fw-bold">
                    <?= number_format($c['solde'], 0, ',', ' ') ?>
                </td>
                <td><?= date('d/m/Y H:i', strtotime($c['date_creation'])) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if(empty($comptes)): ?>
            <tr><td colspan="5" class="text-center">Aucun compte trouvé.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>