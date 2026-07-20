<!DOCTYPE html>
<html>
<head>
    <title>Mon solde</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 450px; width: 100%; text-align: center; }
        .montant { font-size: 48px; font-weight: bold; color: #28a745; margin: 20px 0; }
        .info { background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: left; }
        .btn { display: inline-block; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 5px; }
        .btn-primary { background: #007bff; color: white; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>💰 Mon solde</h2>
        <div class="montant"><?= number_format($solde, 0, ',', ' ') ?> <span style="font-size:20px;color:#666;">Ar</span></div>
        <div class="info">
            <p><strong>👤 Client :</strong> <?= $nom ?></p>
            <p><strong>📱 Numéro :</strong> <?= $numero ?></p>
            <p><strong>📶 Opérateur :</strong> <?= $operateur ?></p>
        </div>
        <a href="/dashboard" class="btn btn-primary">📊 Retour</a>
        <a href="/Authentification/logout" class="btn btn-danger">🚪 Déconnexion</a>
    </div>
</body>
</html>