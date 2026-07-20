<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header .logo {
            font-size: 48px;
            display: block;
            margin-bottom: 8px;
        }
        
        .login-header h1 {
            font-size: 26px;
            color: #333;
        }
        
        .login-header h1 span {
            color: #667eea;
        }
        
        .login-header p {
            color: #888;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            outline: none;
        }
        
        .form-group input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-group input::placeholder {
            color: #aaa;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .demo-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 12px 15px;
            margin-top: 15px;
            text-align: center;
        }
        
        .demo-box p {
            font-size: 13px;
            color: #16a34a;
            font-weight: 500;
        }
        
        .demo-box code {
            display: inline-block;
            background: #dcfce7;
            padding: 5px 15px;
            border-radius: 8px;
            font-size: 16px;
            color: #166534;
            font-weight: 700;
            cursor: pointer;
            margin-top: 5px;
            transition: 0.2s;
        }
        
        .demo-box code:hover {
            background: #bbf7d0;
            transform: scale(1.05);
        }
        
        .operators {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #999;
        }
        
        .operators span {
            display: inline-block;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <span class="logo">💰</span>
            <h1>Mobile <span>Money</span></h1>
            <p>Connectez-vous avec votre numéro</p>
        </div>
        
        <!-- Alertes -->
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">❌ <?= session('error') ?></div>
        <?php endif; ?>
        
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success">✅ <?= session('success') ?></div>
        <?php endif; ?>
        
        <!-- Formulaire -->
        <form action="/Authentification/loginAuth" method="post">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>📱 Numéro de téléphone</label>
                <input 
                    type="tel" 
                    name="numero" 
                    id="numero"
                    placeholder="Ex: 0321234567" 
                    required 
                    autofocus
                    maxlength="10"
                    pattern="[0-9]{10}"
                >
            </div>
            
            <button type="submit" class="btn-login">🔐 Se connecter</button>
        </form>
        
        <!-- Test -->
        <div class="demo-box">
            <p>🧪 Compte de test</p>
            <code onclick="document.getElementById('numero').value='0321234567'">0321234567</code>
            <p style="font-size: 11px; color: #6b7280; margin-top: 5px;">Cliquez pour remplir automatiquement</p>
        </div>
        
        <div class="operators">
            <span>🟠 Orange</span>
            <span>🔴 Airtel</span>
            <span>🔵 Telma</span>
        </div>
    </div>
</body>
</html>