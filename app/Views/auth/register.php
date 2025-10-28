<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Takicom V2</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-user-plus"></i>
                <h1>Créer un compte</h1>
                <p>Rejoignez Takicom V2</p>
            </div>

            <?= errorMessage() ?>
            <?= successMessage() ?>

            <form method="POST" action="<?= url('/register') ?>" class="login-form">
                <?= csrf_field() ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom"><i class="fas fa-user"></i> Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="<?= old('nom') ?>" required>
                        <?= error('nom') ?>
                    </div>
                    <div class="form-group">
                        <label for="prenom"><i class="fas fa-user"></i> Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= old('prenom') ?>" required>
                        <?= error('prenom') ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    <?= error('email') ?>
                </div>

                <div class="form-group">
                    <label for="login"><i class="fas fa-user-tag"></i> Nom d'utilisateur</label>
                    <input type="text" id="login" name="login" class="form-control" value="<?= old('login') ?>" required minlength="3">
                    <?= error('login') ?>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                    <?= error('password') ?>
                </div>

                <div class="form-group">
                    <label for="telephone"><i class="fas fa-phone"></i> Téléphone (optionnel)</label>
                    <input type="text" id="telephone" name="telephone" class="form-control" value="<?= old('telephone') ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Créer mon compte
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="<?= url('/login') ?>" class="btn btn-link">
                    <i class="fas fa-arrow-left"></i> Déjà un compte ? Se connecter
                </a>
            </div>

            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> Takicom V2. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>

</html>