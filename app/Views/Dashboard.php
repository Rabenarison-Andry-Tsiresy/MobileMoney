<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; min-height: 100vh; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .header-title { font-size: 24px; font-weight: bold; }
        .header-user { display: flex; align-items: center; gap: 15px; }
        .header-user .user-name { font-weight: bold; }
        .header-user .user-phone { font-size: 13px; opacity: 0.9; }
        .btn-logout { background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 20px; border-radius: 20px; cursor: pointer; text-decoration: none; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .alert { padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .solde-card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center; }
        .solde-info .montant { font-size: 42px; font-weight: bold; color: #28a745; }
        .solde-info .devise { font-size: 20px; color: #666; }
        .menu-rapide { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 25px; }
        .menu-item { background: white; padding: 20px; border-radius: 15px; text-align: center; text-decoration: none; color: #333; transition: 0.3s; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .menu-item:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .menu-item .icon { font-size: 35px; display: block; margin-bottom: 8px; }
        .menu-item .label { font-size: 14px; font-weight: 600; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: center; }
        .stat-card .stat-number { font-size: 28px; font-weight: bold; color: #333; }
        .stat-card .stat-label { font-size: 13px; color: #999; margin-top: 5px; }
        .stat-card.green .stat-number { color: #28a745; }
        .stat-card.orange .stat-number { color: #ff9800; }
        .stat-card.blue .stat-number { color: #2196f3; }
        .stat-card.purple .stat-number { color: #9c27b0; }
        .transactions-section { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .transactions-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .transaction-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0; }
        .transaction-left { display: flex; align-items: center; gap: 15px; }
        .transaction-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .transaction-icon.depot { background: #e8f5e9; }
        .transaction-icon.retrait { background: #fff3e0; }
        .transaction-icon.transfert { background: #e3f2fd; }
        .transaction-amount .montant.positif { color: #28a745; }
        .transaction-amount .montant.negatif { color: #dc3545; }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="header-title">💰 Mobile Money</div>
            <div class="header-user">
                <div>
                    <div class="user-name"><?= $nom ?></div>
                    <div class="user-phone">📱 <?= $numero ?></div>
                </div>
                <a href="/Authentification/logout" class="btn-logout">🚪 Déconnexion</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success">✅ <?= session('success') ?></div>
        <?php endif; ?>
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">❌ <?= session('error') ?></div>
        <?php endif; ?>

        <div class="solde-card">
            <div class="solde-info">
                <h2>💰 Votre solde</h2>
                <div class="montant">
                    <?= number_format($solde, 0, ',', ' ') ?>
                    <span class="devise">Ar</span>
                </div>
                <div>📶 Opérateur : <?= $operateur ?></div>
            </div>
            <a href="/Compte/solde" style="color: #667eea;">Voir détails →</a>
        </div>

        <div class="menu-rapide">
            <a href="/Transaction/depot" class="menu-item"><span class="icon">💰</span><span class="label">Dépôt</span></a>
            <a href="/Transaction/retrait" class="menu-item"><span class="icon">💳</span><span class="label">Retrait</span></a>
            <a href="/Transaction/transfert" class="menu-item"><span class="icon">📤</span><span class="label">Transfert</span></a>
            <a href="/Commission" class="menu-item"><span class="icon">💸</span><span class="label">Commissions</span></a>
            <a href="/Compte/historique" class="menu-item"><span class="icon">📋</span><span class="label">Historique</span></a>
        </div>

        <div class="stats-grid">
            <div class="stat-card green"><div class="stat-number"><?= $statistiques['total_transactions'] ?></div><div class="stat-label">Total transactions</div></div>
            <div class="stat-card blue"><div class="stat-number"><?= number_format($statistiques['total_depots'], 0, ',', ' ') ?></div><div class="stat-label">Total dépôts</div></div>
            <div class="stat-card orange"><div class="stat-number"><?= number_format($statistiques['total_retraits'], 0, ',', ' ') ?></div><div class="stat-label">Total retraits</div></div>
            <div class="stat-card purple"><div class="stat-number"><?= number_format($statistiques['total_transferts_envoyes'], 0, ',', ' ') ?></div><div class="stat-label">Total transferts</div></div>
        </div>

        <div class="transactions-section">
            <div class="transactions-header"><h3>📋 Dernières transactions</h3><a href="/Compte/historique">Voir tout →</a></div>
            <?php if(empty($dernieres_transactions)): ?>
                <div style="text-align:center;padding:30px;color:#999;">Aucune transaction</div>
            <?php else: ?>
                <?php foreach($dernieres_transactions as $t): 
                    $isDepot = $t['type_operation'] == 'depot';
                    $isRetrait = $t['type_operation'] == 'retrait';
                    $icon = $isDepot ? '📥' : ($isRetrait ? '📤' : '🔄');
                    $iconClass = $isDepot ? 'depot' : ($isRetrait ? 'retrait' : 'transfert');
                    $signe = ($isDepot || $t['id_numero_destination'] == session()->get('user_id')) ? '+' : '-';
                    $color = $signe == '+' ? 'positif' : 'negatif';
                    $montantAffiche = $t['montant'] + $t['montant_frais'];
                ?>
                    <div class="transaction-item">
                        <div class="transaction-left">
                            <div class="transaction-icon <?= $iconClass ?>"><?= $icon ?></div>
                            <div><div class="type"><?= ucfirst($t['type_operation']) ?></div></div>
                        </div>
                        <div class="transaction-amount">
                            <div class="montant <?= $color ?>"><?= $signe ?> <?= number_format($montantAffiche, 0, ',', ' ') ?> Ar</div>
                            <div class="date"><?= date('d/m/Y H:i', strtotime($t['date_transaction'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>