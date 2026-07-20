<?php 
$numeroSource = session()->get('numero');
$prefixeSource = '';
if ($numeroSource) {
    $numeroModel = new \App\Models\NumeroModel();
    $sourceData = $numeroModel->findByNumero($numeroSource);
    if ($sourceData) {
        $prefixeSource = substr($sourceData['numero'], 0, 3);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transfert</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 600px; width: 100%; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-back { display: block; text-align: center; margin-top: 10px; color: #007bff; text-decoration: none; }
        .recipient-group { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 10px; 
            margin: 10px 0;
            border: 1px solid #e9ecef;
        }
        .recipient-group .remove-btn {
            float: right;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 16px;
        }
        .recipient-group .remove-btn:hover {
            background: #c82333;
        }
        .total-info {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            text-align: center;
            font-weight: bold;
        }
        .alert { padding: 12px; border-radius: 8px; margin-bottom: 15px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .mode-toggle {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }
        .mode-toggle button {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background: white;
            cursor: pointer;
            font-weight: bold;
        }
        .mode-toggle button.active {
            border-color: #007bff;
            background: #e3f2fd;
        }
        .info-operator {
            font-size: 12px;
            color: #999;
            margin-top: -5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📤 Effectuer un transfert</h2>
        
        <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">❌ <?= session('error') ?></div>
        <?php endif; ?>
        
        <?php if(session()->has('success')): ?>
            <div class="alert alert-success">✅ <?= session('success') ?></div>
        <?php endif; ?>

        <!-- Mode de transfert -->
        <div class="mode-toggle">
            <button type="button" id="modeSimple" class="active" onclick="setMode('simple')">📤 Simple</button>
            <button type="button" id="modeMultiple" onclick="setMode('multiple')">📤 Multiple</button>
        </div>

        <form action="/Transaction/transfert" method="post" id="transferForm">
            <?= csrf_field() ?>
            <input type="hidden" name="mode" id="mode" value="simple">
            
            <!-- Zone de transfert simple -->
            <div id="simpleZone">
                <label>Numéro du destinataire</label>
                <input type="text" name="numero_destinataire" placeholder="0337654321" required>
                
                <label>Montant (Ar)</label>
                <input type="number" name="montant" placeholder="Ex: 5000" required min="1">
            </div>
            
            <!-- Zone de transfert multiple -->
            <div id="multipleZone" style="display:none;">
                <div id="recipientsContainer">
                    <div class="recipient-group" data-index="0">
                        <button type="button" class="remove-btn" onclick="removeRecipient(this)" style="display:none;">✕</button>
                        <label>Destinataire 1</label>
                        <input type="text" name="destinataires[0][numero]" placeholder="0337654321" required>
                        <label>Montant (Ar)</label>
                        <input type="number" name="destinataires[0][montant]" placeholder="Ex: 5000" required min="1" onchange="updateTotal()" onkeyup="updateTotal()">
                    </div>
                </div>
                <button type="button" class="btn btn-success" onclick="addRecipient()" style="margin-top: 10px;">➕ Ajouter un destinataire</button>
                <div class="info-operator">⚠️ Tous les destinataires doivent être du MÊME opérateur que vous</div>
            </div>

            <!-- Montant total (pour multiple) -->
            <div id="totalZone" style="display:none;">
                <div class="total-info">
                    Total à transférer : <span id="totalMontant">0</span> Ar
                </div>
            </div>

            <button type="submit" class="btn" style="margin-top: 20px;">📤 Valider le transfert</button>
        </form>
        <a href="/dashboard" class="btn-back">📊 Retour</a>
    </div>

    <script>
        let recipientCount = 1;

        function setMode(mode) {
            document.getElementById('mode').value = mode;
            
            document.getElementById('modeSimple').className = mode === 'simple' ? 'active' : '';
            document.getElementById('modeMultiple').className = mode === 'multiple' ? 'active' : '';
            
            document.getElementById('simpleZone').style.display = mode === 'simple' ? 'block' : 'none';
            document.getElementById('multipleZone').style.display = mode === 'multiple' ? 'block' : 'none';
            document.getElementById('totalZone').style.display = mode === 'multiple' ? 'block' : 'none';
            
            document.querySelectorAll('#simpleZone input').forEach(input => {
                input.required = (mode === 'simple');
            });
            document.querySelectorAll('#multipleZone input').forEach(input => {
                input.required = (mode === 'multiple');
            });
        }

        function addRecipient() {
            recipientCount++;
            const container = document.getElementById('recipientsContainer');
            const div = document.createElement('div');
            div.className = 'recipient-group';
            div.setAttribute('data-index', recipientCount - 1);
            div.innerHTML = `
                <button type="button" class="remove-btn" onclick="removeRecipient(this)">✕</button>
                <label>Destinataire ${recipientCount}</label>
                <input type="text" name="destinataires[${recipientCount - 1}][numero]" placeholder="0337654321" required>
                <label>Montant (Ar)</label>
                <input type="number" name="destinataires[${recipientCount - 1}][montant]" placeholder="Ex: 5000" required min="1" onchange="updateTotal()" onkeyup="updateTotal()">
            `;
            container.appendChild(div);
            updateTotal();
        }

        function removeRecipient(btn) {
            const group = btn.parentElement;
            if (document.querySelectorAll('.recipient-group').length > 1) {
                group.remove();
                updateTotal();
                document.querySelectorAll('.recipient-group').forEach((el, index) => {
                    const label = el.querySelector('label');
                    if (label) label.textContent = `Destinataire ${index + 1}`;
                    const inputs = el.querySelectorAll('input');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                        }
                    });
                });
            } else {
                alert('Vous devez avoir au moins un destinataire');
            }
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('input[name$="[montant]"]').forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) total += val;
            });
            document.getElementById('totalMontant').textContent = total.toLocaleString();
        }

        const prefixeSource = '<?= $prefixeSource ?? "" ?>';

        document.getElementById('transferForm').addEventListener('submit', function(e) {
            const mode = document.getElementById('mode').value;
            
            if (mode === 'simple') {
                const numero = document.querySelector('input[name="numero_destinataire"]').value.trim();
                const montant = document.querySelector('input[name="montant"]').value.trim();
                
                if (!numero) {
                    alert('Veuillez saisir le numéro du destinataire');
                    e.preventDefault();
                    return;
                }
                if (!montant || parseFloat(montant) <= 0) {
                    alert('Veuillez saisir un montant valide');
                    e.preventDefault();
                    return;
                }
            }
            
            if (mode === 'multiple') {
                if (!prefixeSource) {
                    alert('Impossible de vérifier votre opérateur');
                    e.preventDefault();
                    return;
                }

                const numeros = document.querySelectorAll('input[name$="[numero]"]');
                for (let input of numeros) {
                    const val = input.value.trim();
                    if (val.length < 3) {
                        alert('Veuillez saisir des numéros complets');
                        e.preventDefault();
                        return;
                    }
                    const prefix = val.substring(0, 3);
                    if (prefix !== prefixeSource) {
                        alert('⚠️ Tous les destinataires doivent être du MÊME opérateur que vous (préfixe ' + prefixeSource + ')');
                        e.preventDefault();
                        return;
                    }
                }
            }
        });
    </script>
</body>
</html>