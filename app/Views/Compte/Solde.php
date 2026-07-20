<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon solde</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .solde-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 450px; width: 100%; text-align: center; }
        .solde-title { font-size: 24px; color: #333; margin-bottom: 20px; }
        .solde-icon { font-size: 60px; margin-bottom: 15px; }
        .solde-montant { font-size: 48px; font-weight: bold; color: #28a745; margin: 20px 0; }
        .solde-devise { font-size: 20px; color: #666; }
        .info-client { background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: left; }
        .info-client p { margin: 8px 0; color: #555; }
        .info-client strong { color: #333; }
        .btn { display: inline-block; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; transition: 0.3s; border: none; cursor: pointer; text-align: center; }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0056b3; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #1e7e34; }
        .btn-warning { background: #ffc107; color: #333; }
        .btn-warning:hover { background: #e0a800; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-group { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .btn-group-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #999; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="solde-container">
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>
        
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
        <?php endif; ?>
        
        <div class="solde-icon">💰</div>
        <h2 class="solde-title">Mon solde</h2>
        
        <!-- Affichage du solde -->
        <div class="solde-montant">
            <?= number_format($solde, 0, ',', ' ') ?>
            <span class="solde-devise">FCFA</span>
        </div>
        
        <!-- Informations client -->
        <div class="info-client">
            <p><strong>👤 Client :</strong> <?= $nom ?></p>
            <p><strong>📱 Numéro :</strong> <?= $numero ?></p>
            <p><strong>📶 Opérateur :</strong> <?= $operateur ?></p>
        </div>
        
        <!-- Boutons d'actions -->
        <div class="btn-group">
            <div class="btn-group-row">
                <a href="/depot" class="btn btn-success">💰 Dépôt</a>
                <a href="/retrait" class="btn btn-warning">💳 Retrait</a>
            </div>
            <div class="btn-group-row">
                <a href="/transfert" class="btn btn-primary">📤 Transfert</a>
                <a href="/historique" class="btn btn-primary">📋 Historique</a>
            </div>
            <a href="/dashboard" class="btn btn-primary">📊 Tableau de bord</a>
            <a href="/Authentification/logout" class="btn btn-danger">🚪 Déconnexion</a>
        </div>
        
        <div class="footer">Mobile Money - Version 1.0</div>
    </div>
</body>
</html>