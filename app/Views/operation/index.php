<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Types d'opérations (Lecture seule)</h2>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Libellé</th></tr>
        <?php foreach ($operations as $o) : ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= ucfirst($o['libelle']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>