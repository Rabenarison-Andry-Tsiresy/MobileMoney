<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Mobile Money</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title {
            font-size: 24px;
            font-weight: bold;
        }
        
        .header-title span {
            font-weight: normal;
            font-size: 14px;
            opacity: 0.8;
        }
        
        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-user .user-info {
            text-align: right;
        }
        
        .header-user .user-name {
            font-weight: bold;
            font-size: 16px;
        }
        
        .header-user .user-phone {
            font-size: 13px;
            opacity: 0.9;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            font-size: 14px;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        /* Alertes */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Solde Card */
        .solde-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .solde-info h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 5px;
        }
        
        .solde-info .montant {
            font-size: 42px;
            font-weight: bold;
            color: #28a745;
        }
        
        .solde-info .devise {
            font-size: 20px;
            color: #666;
        }
        
        .solde-info .operateur {
            font-size: 14px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Menu rapide */
        .menu-rapide {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .menu-item {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .menu-item .icon {
            font-size: 35px;
            display: block;
            margin-bottom: 8px;
        }
        
        .menu-item .label {
            font-size: 14px;
            font-weight: 600;
        }
        
        .menu-item .sub-label {
            font-size: 12px;
            color: #999;
            display: block;
            margin-top: 3px;
        }
        
        .menu-item.depot:hover { background: #e8f5e9; }
        .menu-item.retrait:hover { background: #fff3e0; }
        .menu-item.transfert:hover { background: #e3f2fd; }
        .menu-item.historique:hover { background: #f3e5f5; }
        
        /* Statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-card .stat-label {
            font-size: 13px;
            color: #999;
            margin-top: 5px;
        }
        
        .stat-card .stat-icon {
            font-size: 25px;
            margin-bottom: 5px;
            display: block;
        }
        
        .stat-card.green .stat-number { color: #28a745; }
        .stat-card.orange .stat-number { color: #ff9800; }
        .stat-card.blue .stat-number { color: #2196f3; }
        .stat-card.purple .stat-number { color: #9c27b0; }
        
        /* Dernières transactions */
        .transactions-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .transactions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .transactions-header h3 {
            color: #333;
            font-size: 18px;
        }
        
        .transactions-header a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        
        .transactions-header a:hover {
            text-decoration: underline;
        }
        
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .transaction-item:last-child {
            border-bottom: none;
        }
        
        .transaction-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .transaction-icon.depot { background: #e8f5e9; }
        .transaction-icon.retrait { background: #fff3e0; }
        .transaction-icon.transfert { background: #e3f2fd; }
        
        .transaction-info .type {
            font-weight: 600;
            color: #333;
        }
        
        .transaction-info .details {
            font-size: 13px;
            color: #999;
        }
        
        .transaction-amount {
            text-align: right;
        }
        
        .transaction-amount .montant {
            font-weight: bold;
            font-size: 16px;
        }
        
        .transaction-amount .montant.positif { color: #28a745; }
        .transaction-amount .montant.negatif { color: #dc3545; }
        
        .transaction-amount .date {
            font-size: 12px;
            color: #999;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .menu-rapide {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .solde-card {
                flex-direction: column;
                text-align: center;
            }
            
            .header-content {
                flex-direction: column;
                gap: 10px;
            }
            
            .header-user {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-title">
                💰 Mobile Money
                <span>| Tableau de bord</span>
            </div>
            <div class="header-user">
                <div class="user-info">
                    <div class="user-name"><?= $nom ?></div>
                    <div class="user-phone">📱 <?= $numero ?></div>
                </div>
                <a href="/Authentification/logout" class="btn-logout">🚪 Déconnexion</a>
            </div>
        </div>
    </header>

    <!-- Container -->
    <div class="container">
        
        <!-- Alertes -->
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success">
                ✅ <?= session('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">
                ❌ <?= session('error') ?>
            </div>
        <?php endif; ?>

        <!-- Solde -->
        <div class="solde-card">
            <div class="solde-info">
                <h2>💰 Votre solde</h2>
                <div class="montant">
                    <?= number_format($solde, 0, ',', ' ') ?>
                    <span class="devise">FCFA</span>
                </div>
                <div class="operateur">📶 Opérateur : <?= $operateur ?></div>
            </div>
            <div>
                <a href="/Compte/solde" style="color: #667eea; text-decoration: none; font-weight: 600;">
                    Voir détails →
                </a>
            </div>
        </div>

        <!-- Menu rapide -->
        <div class="menu-rapide">
            <a href="/Transaction/depot" class="menu-item depot">
                <span class="icon">💰</span>
                <span class="label">Dépôt</span>
                <span class="sub-label">Ajouter de l'argent</span>
            </a>
            <a href="/Transaction/retrait" class="menu-item retrait">
                <span class="icon">💳</span>
                <span class="label">Retrait</span>
                <span class="sub-label">Retirer de l'argent</span>
            </a>
            <a href="/Transaction/transfert" class="menu-item transfert">
                <span class="icon">📤</span>
                <span class="label">Transfert</span>
                <span class="sub-label">Envoyer à un proche</span>
            </a>
            <a href="/Compte/historique" class="menu-item historique">
                <span class="icon">📋</span>
                <span class="label">Historique</span>
                <span class="sub-label">Voir mes transactions</span>
            </a>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card green">
                <span class="stat-icon">📊</span>
                <div class="stat-number"><?= $statistiques['total_transactions'] ?></div>
                <div class="stat-label">Total transactions</div>
            </div>
            <div class="stat-card blue">
                <span class="stat-icon">📥</span>
                <div class="stat-number"><?= number_format($statistiques['total_depots'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total dépôts</div>
            </div>
            <div class="stat-card orange">
                <span class="stat-icon">📤</span>
                <div class="stat-number"><?= number_format($statistiques['total_retraits'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total retraits</div>
            </div>
            <div class="stat-card purple">
                <span class="stat-icon">🔄</span>
                <div class="stat-number"><?= number_format($statistiques['total_transferts_envoyes'], 0, ',', ' ') ?></div>
                <div class="stat-label">Total transferts</div>
            </div>
        </div>

        <!-- Dernières transactions -->
        <div class="transactions-section">
            <div class="transactions-header">
                <h3>📋 Dernières transactions</h3>
                <a href="/Compte/historique">Voir tout →</a>
            </div>
            
            <?php if(empty($dernieres_transactions)): ?>
                <div style="text-align: center; padding: 30px; color: #999;">
                    Aucune transaction pour le moment
                </div>
            <?php else: ?>
                <?php foreach($dernieres_transactions as $transaction): ?>
                    <?php
                        $isDepot = $transaction['type_operation'] == 'depot';
                        $isRetrait = $transaction['type_operation'] == 'retrait';
                        $isTransfert = $transaction['type_operation'] == 'transfert';
                        
                        $icon = $isDepot ? '📥' : ($isRetrait ? '📤' : '🔄');
                        $iconClass = $isDepot ? 'depot' : ($isRetrait ? 'retrait' : 'transfert');
                        $typeLabel = ucfirst($transaction['type_operation']);
                        
                        $signe = ($isDepot || $transaction['id_numero_destination'] == session()->get('user_id')) ? '+' : '-';
                        $colorClass = $signe == '+' ? 'positif' : 'negatif';
                        
                        $details = '';
                        if ($isDepot) {
                            $details = 'Dépôt effectué';
                        } elseif ($isRetrait) {
                            $details = 'Retrait effectué';
                        } elseif ($isTransfert) {
                            if ($transaction['id_numero_source'] == session()->get('user_id')) {
                                $details = 'Envoyé à ' . $transaction['numero_destination'];
                            } else {
                                $details = 'Reçu de ' . $transaction['numero_source'];
                            }
                        }
                        
                        $date = date('d/m/Y H:i', strtotime($transaction['date_transaction']));
                        $montantAffiche = $transaction['montant'] + $transaction['montant_frais'];
                    ?>
                    <div class="transaction-item">
                        <div class="transaction-left">
                            <div class="transaction-icon <?= $iconClass ?>">
                                <?= $icon ?>
                            </div>
                            <div class="transaction-info">
                                <div class="type"><?= $typeLabel ?></div>
                                <div class="details"><?= $details ?></div>
                            </div>
                        </div>
                        <div class="transaction-amount">
                            <div class="montant <?= $colorClass ?>">
                                <?= $signe ?> <?= number_format($montantAffiche, 0, ',', ' ') ?> FCFA
                            </div>
                            <div class="date"><?= $date ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
</body>
</html>