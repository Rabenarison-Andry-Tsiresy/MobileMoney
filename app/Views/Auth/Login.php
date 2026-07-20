<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <?php if(session()->has('error')): ?>
        <div style="color: red; margin-bottom: 10px;">
            <?= session('error') ?>
        </div>
    <?php endif; ?>
    
    <!-- Vérifier que l'action est correcte -->
    <form action="/Authentification/loginAuth" method="post">
        
        <label>Numéro de téléphone :</label>
        <input type="text" name="numero" placeholder="0331234567" required>        
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>