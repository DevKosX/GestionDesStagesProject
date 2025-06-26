<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe - Gestion des Stages</title>
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/no-animations.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= asset('js/notifications.js') ?>"></script>
    <style>
        .change-password-container {
            max-width: 650px;
            margin: 30px auto;
            padding: 40px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1), 0 1px 8px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
        }
        
        .form-title {
            text-align: center;
            margin-bottom: 35px;
            color: #2c3e50;
            font-size: 32px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-title i {
            color: #3498db;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #34495e;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            color: #3498db;
            width: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #ffffff;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-1px);
        }

        .form-group input:valid {
            border-color: #27ae60;
        }

        .btn-change {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-change:hover {
            background: linear-gradient(135deg, #2980b9 0%, #21618c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
        }

        .btn-change:active {
            transform: translateY(0);
        }

        .btn-change i {
            margin-right: 10px;
        }

        .alert {
            padding: 18px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert i {
            font-size: 18px;
            min-width: 18px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .password-requirements {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            position: relative;
        }

        .password-requirements::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3498db, #9b59b6);
            border-radius: 12px 12px 0 0;
        }

        .password-requirements h4 {
            margin: 0 0 15px 0;
            color: #495057;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-requirements h4 i {
            color: #3498db;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .password-requirements li {
            margin-bottom: 8px;
            color: #6c757d;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .password-requirements li::before {
            content: '✓';
            color: #27ae60;
            font-weight: bold;
            font-size: 16px;
        }

        /* Animation pour l'input actif */
        .form-group input:focus + .input-icon {
            color: #3498db;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .change-password-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .form-title {
                font-size: 28px;
            }
        }

        /* Animation de succès */
        @keyframes success-pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .alert-success {
            animation: success-pulse 0.6s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user->prenom ?? 'U', 0, 1) . substr($user->nom ?? 'U', 0, 1)); ?>
                </div>
                <div class="user-name"><?php echo htmlspecialchars(($user->prenom ?? '') . ' ' . ($user->nom ?? '')); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($role ?? 'Utilisateur'); ?></div>
            </div>
            
            <nav class="nav-menu">
                <a href="<?= url('dashboard') ?>" class="nav-item">
                    <i class="fas fa-home"></i> Tableau de bord
                </a>
                <a href="#messagerie" class="nav-item" onclick="showSection('messagerie')">
                    <i class="fas fa-envelope"></i> Messagerie
                    <?php if (($stats['messages_recus'] ?? 0) > 0): ?>
                        <span class="badge"><?php echo $stats['messages_recus']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="#documents" class="nav-item" onclick="showSection('documents')">
                    <i class="fas fa-file-alt"></i> Documents
                    <?php if (($stats['documents_recus'] ?? 0) > 0): ?>
                        <span class="badge"><?php echo $stats['documents_recus']; ?></span>
                    <?php endif; ?>
                </a>
                <?php if (in_array($role, ['eleve', 'enseignant', 'tuteur', 'tuteur_entreprise', 'admin'])): ?>
                <a href="#suivi-stages" class="nav-item" onclick="showSection('suivi-stages')">
                    <i class="fas fa-briefcase"></i> Suivi des stages
                    <?php if (isset($stats['stages_actifs']) && $stats['stages_actifs'] > 0): ?>
                        <span class="badge"><?php echo $stats['stages_actifs']; ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <?php if ($role === 'admin'): ?>
                <a href="<?= url('admin/dashboard') ?>" class="nav-item">
                    <i class="fas fa-cogs"></i> Administration
                </a>
                <?php endif; ?>
                <?php if ($role !== 'admin'): ?>
                <a href="#calendrier" class="nav-item" onclick="showSection('calendrier')">
                    <i class="fas fa-calendar"></i> Calendrier
                    <?php if (($stats['evenements_urgents'] ?? 0) > 0): ?>
                        <span class="badge"><?php echo $stats['evenements_urgents']; ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <a href="<?= url('change-password') ?>" class="nav-item active">
                    <i class="fas fa-key"></i> Changer mot de passe
                </a>
                <a href="<?= url('logout') ?>" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
                <div class="uspn-logo-container">
                <a href="https://www.univ-spn.fr/" target="_blank">
                    <img src="public/assets/images/uspn.png" alt="Logo USPN">
                </a>
                </div>
            </nav>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="change-password-container">
                <h1 class="form-title">
                    <i class="fas fa-key"></i>
                    Changer le mot de passe
                </h1>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="changePasswordForm">
                    <div class="form-group">
                        <label for="current_password">
                            <i class="fas fa-lock"></i>
                            Mot de passe actuel
                        </label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="password-requirements">
                        <h4><i class="fas fa-info-circle"></i> Exigences du nouveau mot de passe :</h4>
                        <ul>
                            <li>Au moins 6 caractères</li>
                            <li>Différent du mot de passe actuel</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="new_password">
                            <i class="fas fa-key"></i>
                            Nouveau mot de passe
                        </label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-check"></i>
                            Confirmer le nouveau mot de passe
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>

                    <button type="submit" class="btn-change">
                        <i class="fas fa-save"></i>
                        Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas !');
                document.getElementById('confirm_password').focus();
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Le nouveau mot de passe doit contenir au moins 6 caractères !');
                document.getElementById('new_password').focus();
                return false;
            }
        });

        // Vérification en temps réel de la confirmation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && newPassword !== confirmPassword) {
                this.style.borderColor = '#e74c3c';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });

        // Fonction pour afficher les sections (pour compatibilité avec la sidebar)
        function showSection(sectionName) {
            // Rediriger vers le dashboard avec la section appropriée
            window.location.href = '<?= url('dashboard') ?>#' + sectionName;
        }
    </script>
</body>
</html> 