<!DOCTYPE html>
<html>
<head>
    <title>Commissions</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-warning { background: #ff9800; }
        .btn-warning:hover { background: #e68900; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        tr:hover { background: #f5f5f5; }
        .actions { display: flex; gap: 5px; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 Commissions</h1>
        
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">❌ <?= session('error') ?></div>
        <?php endif; ?>
        
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success">✅ <?= session('success') ?></div>
        <?php endif; ?>
        
        <a href="/Commission/create" class="btn btn-success">➕ Ajouter une commission</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Opérateur départ</th>
                    <th>Opérateur arrivée</th>
                    <th>Pourcentage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($commissions)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #999;">Aucune commission</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($commissions as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= $c['operateur_depart'] ?></td>
                            <td><?= $c['operateur_arrivee'] ?></td>
                            <td><?= number_format($c['pourcentage'], 2, ',', ' ') ?>%</td>
                            <td>
                                <div class="actions">
                                    <a href="/Commission/edit/<?= $c['id'] ?>" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">✏️ Modifier</a>
                                    <a href="/Commission/delete/<?= $c['id'] ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;" onclick="return confirm('Êtes-vous sûr ?')">🗑️ Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <a href="/dashboard" class="btn">📊 Retour au dashboard</a>
    </div>
</body>
</html>
