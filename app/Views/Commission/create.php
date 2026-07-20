<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une commission</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #218838; }
        .btn-back { display: block; text-align: center; margin-top: 10px; color: #007bff; text-decoration: none; }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h2>➕ Ajouter une commission</h2>
        
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">❌ <?= session('error') ?></div>
        <?php endif; ?>
        
        <form action="/Commission/store" method="post">
            <label>Opérateur de départ</label>
            <select name="id_operateur_depart" required>
                <option value="">Sélectionner</option>
                <?php foreach($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>"><?= $op['libelle'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Opérateur d'arrivée</label>
            <select name="id_operateur_arrivee" required>
                <option value="">Sélectionner</option>
                <?php foreach($operateurs as $op): ?>
                    <option value="<?= $op['id'] ?>"><?= $op['libelle'] ?></option>
                <?php endforeach; ?>
            </select>
            
            <label>Pourcentage (%)</label>
            <input type="number" name="pourcentage" step="0.01" placeholder="Ex: 2.5" required min="0.01" max="100">
            
            <button type="submit" class="btn">💾 Enregistrer</button>
        </form>
        <a href="/Commission" class="btn-back">📋 Retour à la liste</a>
    </div>
</body>
</html>
