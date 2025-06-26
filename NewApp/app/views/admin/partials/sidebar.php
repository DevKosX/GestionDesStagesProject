        <!-- SIDEBAR ADMIN -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar"><?php echo strtoupper(substr($user->prenom ?? 'A', 0, 1) . substr($user->nom ?? 'D', 0, 1)); ?></div>
                <div class="user-name"><?php echo htmlspecialchars(($user->prenom ?? '') . ' ' . ($user->nom ?? '')); ?></div>
                <div class="user-role">Administrateur</div>
            </div>
            <nav class="nav-menu">
                <a href="<?= url('dashboard') ?>" class="nav-item <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard Principal
                </a>
                <a href="<?= url('admin/dashboard') ?>" class="nav-item <?= ($current_page ?? '') === 'admin-dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Gestion des utilisateurs
                </a>
                <a href="<?= url('admin/create-user') ?>" class="nav-item <?= ($current_page ?? '') === 'create-user' ? 'active' : '' ?>">
                    <i class="fas fa-user-plus"></i> Nouveau Utilisateur
                </a>
                <a href="<?= url('admin/create-stage') ?>" class="nav-item <?= ($current_page ?? '') === 'create-stage' ? 'active' : '' ?>">
                    <i class="fas fa-briefcase"></i> Nouveau Stage
                </a>
                <a href="<?= url('admin/action-types') ?>" class="nav-item <?= ($current_page ?? '') === 'action-types' ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i> Types d'Actions
                </a>
                <a href="<?= url('admin/create-action-type') ?>" class="nav-item <?= ($current_page ?? '') === 'create-action-type' ? 'active' : '' ?>">
                    <i class="fas fa-plus"></i> Nouveau Type
                </a>
                <hr style="margin: 15px 0; border-color: #ddd;">
                <a href="<?= url('change-password') ?>" class="nav-item">
                    <i class="fas fa-key"></i> Changer mot de passe
                </a>
                <a href="<?= url('logout') ?>" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </div> 