<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2><?= isset($tarif) ? 'Modifier' : 'Ajouter' ?> une Tranche Tarifaire</h2>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url(isset($tarif) ? 'tarif/update/'.$tarif['id'] : 'tarif/create') ?>" method="post">
        <div class="row mb-3">
            <div class="col">
                <label>Opérateur</label>
                <select name="id_operateur" class="form-control" required>
                    <?php foreach ($operateurs as $op) : ?>
                        <option value="<?= $op['id'] ?>" <?= (isset($tarif) && $tarif['id_operateur'] == $op['id']) ? 'selected' : '' ?>><?= $op['libelle'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label>Opération</label>
                <select name="id_operation" class="form-control" required>
                    <?php foreach ($operations as $ope) : ?>
                        <option value="<?= $ope['id'] ?>" <?= (isset($tarif) && $tarif['id_operation'] == $ope['id']) ? 'selected' : '' ?>><?= ucfirst($ope['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col"><label>Montant Min</label><input type="number" name="montant_min" class="form-control" value="<?= isset($tarif) ? $tarif['montant_min'] : '' ?>" required></div>
            <div class="col"><label>Montant Max</label><input type="number" name="montant_max" class="form-control" value="<?= isset($tarif) ? $tarif['montant_max'] : '' ?>" required></div>
            <div class="col"><label>Frais (Gain)</label><input type="number" name="montant_frais" class="form-control" value="<?= isset($tarif) ? $tarif['montant_frais'] : '' ?>" required></div>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
</body>
</html>