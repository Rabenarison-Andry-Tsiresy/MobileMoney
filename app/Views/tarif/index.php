<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Barèmes des Frais</h2>
    <a href="<?= base_url('tarif/new') ?>" class="btn btn-primary mb-3">Ajouter une tranche</a>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <tr>
            <th>Opérateur</th>
            <th>Opération</th>
            <th>Montant Min (Ar)</th>
            <th>Montant Max (Ar)</th>
            <th>Frais (Ar)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($tarifs as $t) : ?>
            <tr>
                <td><?= $t['operateur_libelle'] ?></td>
                <td><?= ucfirst($t['operation_libelle']) ?></td>
                <td><?= number_format($t['montant_min'], 0, ',', ' ') ?></td>
                <td><?= number_format($t['montant_max'], 0, ',', ' ') ?></td>
                <td><strong><?= number_format($t['montant_frais'], 0, ',', ' ') ?></strong></td>
                <td>
                    <a href="<?= base_url('tarif/edit/'.$t['id']) ?>" class="btn btn-warning btn-sm">Modif</a>
                    <a href="<?= base_url('tarif/delete/'.$t['id']) ?>" class="btn btn-danger btn-sm">Suppr</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>