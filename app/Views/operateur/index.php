<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Opérateurs</title>
</head>
<body class="container mt-4">
    <h2>Liste des Opérateurs</h2>
    <a href="<?= base_url('operateur/new') ?>" class="btn btn-primary mb-3">Ajouter un Opérateur</a>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Libellé</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($operateurs as $op) : ?>
            <tr>
                <td><?= $op['id'] ?></td>
                <td><?= $op['libelle'] ?></td>
                <td>
                    <a href="<?= base_url('operateur/edit/'.$op['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="<?= base_url('operateur/delete/'.$op['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sûr ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>