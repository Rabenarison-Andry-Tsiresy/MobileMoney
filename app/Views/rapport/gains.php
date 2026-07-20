<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Situation des Gains</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Opérateur</th>
                <th>Type d'opération</th>
                <th>Total des gains (Ar)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gains as $g) : ?>
                <tr>
                    <td><?= $g['operateur_nom'] ?></td>
                    <td><?= ucfirst($g['operation_nom']) ?></td>
                    <td class="text-success fw-bold">+ <?= number_format($g['total_gains'], 0, ',', ' ') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>