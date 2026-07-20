<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Liste des Préfixes</h2>
    <a href="<?= base_url('prefixe/new') ?>" class="btn btn-primary mb-3">Ajouter un Préfixe</a>

    <table class="table table-bordered">
        <tr>
            <th>Opérateur</th>
            <th>Préfixe</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($prefixes as $p) : ?>
            <tr>
                <td><?= $p['operateur_libelle'] ?></td>
                <td><?= $p['prefixe'] ?></td>
                <td>
                    <a href="<?= base_url('prefixe/edit/'.$p['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="<?= base_url('prefixe/delete/'.$p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sûr ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>