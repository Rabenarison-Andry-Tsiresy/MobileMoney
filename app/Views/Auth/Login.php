<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <?php if(session()->has('error')): ?>
        <div style="color: red;"><?= session('error') ?></div>
    <?php endif; ?>
    
    <?php if(session()->has('success')): ?>
        <div style="color: green;"><?= session('success') ?></div>
    <?php endif; ?>
    
    <form action="/Authentification/loginAuth" method="post">
        <?= csrf_field() ?>
        <label>Numéro de téléphone</label>
        <input type="text" name="numero" placeholder="0331234567" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>