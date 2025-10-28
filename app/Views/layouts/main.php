<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Takicom V2' ?> - Gestion Commerciale</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">

    <?php if (isset($extra_css)): ?>
        <?= $extra_css ?>
    <?php endif; ?>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-store"></i> Takicom V2</h2>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= url('/dashboard') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section">Ventes</div>
            <a href="<?= url('/clients') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'clients') !== false ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Clients</span>
            </a>
            <a href="<?= url('/devis') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'devis') !== false ? 'active' : '' ?>">
                <i class="fas fa-file-invoice"></i>
                <span>Devis</span>
            </a>
            <a href="<?= url('/factures') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'factures') !== false && strpos($_SERVER['REQUEST_URI'], 'fournisseur') === false ? 'active' : '' ?>">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Factures</span>
            </a>

            <div class="nav-section">Achats</div>
            <a href="<?= url('/fournisseurs') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'fournisseurs') !== false ? 'active' : '' ?>">
                <i class="fas fa-truck"></i>
                <span>Fournisseurs</span>
            </a>
            <a href="<?= url('/factures-fournisseur') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'factures-fournisseur') !== false ? 'active' : '' ?>">
                <i class="fas fa-receipt"></i>
                <span>Factures Fournisseur</span>
            </a>

            <div class="nav-section">Stock</div>
            <a href="<?= url('/articles') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'articles') !== false ? 'active' : '' ?>">
                <i class="fas fa-boxes"></i>
                <span>Articles</span>
            </a>
            <a href="<?= url('/categories') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : '' ?>">
                <i class="fas fa-tags"></i>
                <span>Catégories</span>
            </a>
            <a href="<?= url('/stock') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'stock') !== false ? 'active' : '' ?>">
                <i class="fas fa-warehouse"></i>
                <span>Stock</span>
            </a>

            <div class="nav-section">Paramètres</div>
            <a href="<?= url('/parametres') ?>" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'parametres') !== false ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span>Configuration</span>
            </a>
        </nav>

      
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="content-header">
            <?php
            $date = new IntlDateFormatter(
                'fr_FR',
                IntlDateFormatter::FULL,
                IntlDateFormatter::NONE
            );
            ?>
            <button class="btn-toggle-sidebar" id="toggleSidebar">
                <?php echo ucfirst($date->format(new DateTime())); ?>
            </button>

            <div class="header-right">
                <div class="notifications">
                    <button class="btn-icon" id="btnNotifications">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                </div>

                <div class="user-menu dropdown">
    <button class="btn btn-light d-flex align-items-center gap-2" id="btnUserMenu" data-bs-toggle="dropdown">
        <i class="fas fa-user-circle fs-4"></i>
        <div class="text-start" style="line-height: 1;">
            <strong><?= e(auth()->nom . ' ' . auth()->prenom) ?></strong><br>
            <small class="text-muted"><?= e(auth()->role_libelle) ?></small>
        </div>
        <i class="fas fa-chevron-down ms-2"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="<?= url('/parametres') ?>"><i class="fas fa-cog"></i> Paramètres</a></li>
        <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
    </ul>
</div>

            </div>
        </header>

        <!-- Messages Flash -->
        <div class="flash-messages">
            <?= successMessage() ?>
            <?= errorMessage() ?>
        </div>

        <!-- Page Content -->
        <div class="content-body">
            <?= $content ?? '' ?>
        </div>
    </main>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= asset('js/app.js') ?>"></script>

    <?php if (isset($extra_js)): ?>
        <?= $extra_js ?>
    <?php endif; ?>

    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.main-content').classList.toggle('expanded');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>

</html>