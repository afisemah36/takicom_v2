<?php
// Test simple du serveur PHP
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Serveur PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="success">
        <h1>✓ Le serveur PHP fonctionne correctement !</h1>
        <p>Si vous voyez ce message, votre serveur PHP est opérationnel.</p>
    </div>
    
    <div class="info">
        <strong>Version PHP :</strong> <?php echo phpversion(); ?>
    </div>
    
    <div class="info">
        <strong>Date et heure du serveur :</strong> <?php echo date('d/m/Y H:i:s'); ?>
    </div>
    
    <div class="info">
        <strong>Système d'exploitation :</strong> <?php echo PHP_OS; ?>
    </div>
    
    <div class="info">
        <strong>Serveur :</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Non disponible'; ?>
    </div>
</body>
</html>