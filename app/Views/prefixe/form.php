<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2><?= isset($prefixe) ? 'Modifier' : 'Ajouter' ?> un Préfixe</h2>

    <form action="<?= base_url(isset($prefixe) ? 'prefixe/update/'.$prefixe['id'] : 'prefixe/create') ?>" method="post">
        <div class="mb-3">
            <label>Opérateur</label>
            <select name="id_operateur" class="form-control" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($operateurs as $op) : ?>
                    <option value="<?= $op['id'] ?>" <?= (isset($prefixe) && $prefixe['id_operateur'] == $op['id']) ? 'selected' : '' ?>>
                        <?= $op['libelle'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Préfixe (ex: 034)</label>
            <input type="text" name="prefixe" class="form-control" value="<?= isset($prefixe) ? $prefixe['prefixe'] : '' ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</body>
</html>