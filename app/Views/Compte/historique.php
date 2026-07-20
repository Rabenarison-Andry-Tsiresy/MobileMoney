<!DOCTYPE html>
<html>
<head>
    <title>Mon historique</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .transaction { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .positif { color: #28a745; }
        .negatif { color: #dc3545; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Historique des transactions</h2>
        <p><strong>Numéro :</strong> <?= $numero ?></p>
        <hr>
        <?php if(empty($historique)): ?>
            <p>Aucune transaction</p>
        <?php else: ?>
            <?php foreach($historique as $t): 
                $isDepot = $t['type_operation'] == 'depot';
                $signe = ($isDepot || $t['id_numero_destination'] == $numero_id) ? '+' : '-';
                $color = $signe == '+' ? 'positif' : 'negatif';
                $montantAffiche = $t['montant'];
            ?>
                <div class="transaction">
                    <div>
                        <strong><?= ucfirst($t['type_operation']) ?></strong>
                        <div style="font-size:12px;color:#999;"><?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?></div>
                        <?php if($t['montant_frais'] > 0): ?>
                            <div style="font-size:12px;color:#999;">Frais : <?= number_format($t['montant_frais'], 0, ',', ' ') ?> Ar</div>
                        <?php endif; ?>
                    </div>
                    <div class="<?= $color ?>">
                        <strong><?= $signe ?> <?= number_format($montantAffiche, 0, ',', ' ') ?> Ar</strong>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <a href="/dashboard" class="btn">📊 Retour</a>
    </div>
</body>
</html>