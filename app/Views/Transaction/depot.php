<!DOCTYPE html>
<html>
<head>
    <title>Dépôt</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 400px; width: 100%; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #218838; }
        .btn-back { display: block; text-align: center; margin-top: 10px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>💰 Effectuer un dépôt</h2>
        <form action="/Transaction/depot" method="post">
            <?= csrf_field() ?>
            <label>Montant (Ar)</label>
            <input type="number" name="montant" placeholder="Ex: 5000" required min="1">
            <button type="submit" class="btn">💰 Valider le dépôt</button>
        </form>
        <a href="/dashboard" class="btn-back">📊 Retour</a>
    </div>
</body>
</html>