<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> un Opérateur</title>
</head>
<body class="container mt-4">
    <h2><?= isset($operateur) ? 'Modifier' : 'Ajouter' ?> un Opérateur</h2>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error) echo $error . '<br>'; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url(isset($operateur) ? 'operateur/update/'.$operateur['id'] : 'operateur/create') ?>" method="post">
        <div class="mb-3">
            <label>Nom de l'opérateur</label>
            <input type="text" name="libelle" class="form-control" value="<?= isset($operateur) ? $operateur['libelle'] : set_value('libelle') ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="<?= base_url('operateur') ?>" class="btn btn-secondary">Annuler</a>
    </form>
</body>
</html>