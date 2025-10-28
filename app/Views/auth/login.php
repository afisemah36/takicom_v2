<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Takicom V2</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-store"></i>
                <h1>Takicom V2</h1>
                <p>Gestion Commerciale</p>
            </div>

            <?= errorMessage() ?>

            <form method="POST" action="<?= url('/login') ?>" class="login-form">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="login">
                        <i class="fas fa-user"></i> Nom d'utilisateur
                    </label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        class="form-control"
                        value="<?= old('login') ?>"
                        required
                        autofocus
                        placeholder="Entrez votre login">
                    <?= error('login') ?>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mot de passe
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                        placeholder="Entrez votre mot de passe">
                    <?= error('password') ?>
                </div>

                <div class="form-group remember-me">
                    <label>
                        <input type="checkbox" name="remember"> Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>
            <div class="text-center mt-3">
                <a href="<?= url('/register') ?>" class="btn btn-link">
                    <i class="fas fa-user-plus"></i> Créer un compte
                </a>
            </div>
            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> Takicom V2. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</body>

</html>