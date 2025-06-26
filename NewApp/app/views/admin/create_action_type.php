<?php
// Charger les helpers d'URL
require_once __DIR__ . '/../../helpers/url_helper.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Type d'Action - Administration</title>
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard-test.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            color: white;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .info-box {
            background: #e3f2fd;
            color: #1565c0;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #2196f3;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .help-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .toast-success {
            animation: slideIn 0.5s ease-out, slideOut 0.5s ease-in 3s forwards;
            position: relative;
            border-left: 5px solid #28a745;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="user-profile">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user->prenom ?? 'A', 0, 1) . substr($user->nom ?? 'D', 0, 1)); ?>
                </div>
                <div class="user-name"><?php echo htmlspecialchars(($user->prenom ?? '') . ' ' . ($user->nom ?? '')); ?></div>
                <div class="user-role">Administrateur</div>
            </div>
            
            <nav class="nav-menu">
                <a href="<?= url('dashboard') ?>" class="nav-item">
                    <i class="fas fa-home"></i> Dashboard Principal
                </a>
                <a href="<?= url('admin/dashboard') ?>" class="nav-item">
                    <i class="fas fa-users"></i> Gestion des utilisateurs
                </a>
                <a href="<?= url('admin/create-user') ?>" class="nav-item">
                    <i class="fas fa-user-plus"></i> Nouveau Utilisateur
                </a>
                <a href="<?= url('admin/create-stage') ?>" class="nav-item">
                    <i class="fas fa-briefcase"></i> Nouveau Stage
                </a>
                <a href="<?= url('admin/action-types') ?>" class="nav-item">
                    <i class="fas fa-tasks"></i> Types d'Actions
                </a>
                <a href="<?= url('admin/create-action-type') ?>" class="nav-item active">
                    <i class="fas fa-plus"></i> Nouveau Type
                </a>
                <hr style="margin: 15px 0; border-color: #ddd;">
                <a href="<?= url('logout') ?>" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-plus"></i> Nouveau Type d'Action
                </h1>
                <p class="page-subtitle">Créer un nouveau type d'action pour le système</p>
            </div>

            <!-- MESSAGES -->
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="success-message toast-success">
                    <i class="fas fa-check-circle"></i>
                    Type d'action créé avec succès !
                </div>
            <?php endif; ?>

            <!-- INFORMATIONS -->
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <strong>Aide :</strong> Les types d'actions définissent les tâches que les utilisateurs doivent accomplir. 
                Chaque type a un exécutant, un délai et peut nécessiter des documents.
            </div>

            <!-- FORMULAIRE -->
            <div class="form-container">
                <form method="POST" action="<?= url('admin/create-action-type') ?>">
                    <h3><i class="fas fa-info-circle"></i> Informations de base</h3>
                    
                    <div class="form-group">
                        <label for="libelle">Libellé de l'action <span class="required">*</span></label>
                        <input type="text" 
                               id="libelle" 
                               name="libelle" 
                               value="<?= htmlspecialchars($form_data['libelle'] ?? '') ?>" 
                               required
                               placeholder="Ex: Remettre le rapport de stage">
                        <div class="help-text">Description claire de l'action à effectuer</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="executant">Exécutant <span class="required">*</span></label>
                            <select id="executant" name="executant" required>
                                <option value="">Qui doit effectuer cette action ?</option>
                                <?php foreach ($executants_available as $value => $label): ?>
                                    <option value="<?= $value ?>" 
                                            <?= ($form_data['executant'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="help-text">Qui doit réaliser cette action</div>
                        </div>
                        

                    </div>

                    <h3 style="margin-top: 30px;"><i class="fas fa-calendar"></i> Date de fin</h3>
                    
                    <div class="form-group">
                        <label for="date_fin">Date de fin de l'action <span class="required">*</span></label>
                        <input type="date" 
                               id="date_fin" 
                               name="date_fin" 
                               value="<?= htmlspecialchars($form_data['date_fin'] ?? '') ?>" 
                               required>
                        <div class="help-text">Date limite pour réaliser cette action</div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" 
                                   id="requis_doc" 
                                   name="requis_doc" 
                                   value="1"
                                   <?= isset($form_data['requis_doc']) && $form_data['requis_doc'] ? 'checked' : '' ?>>
                            <label for="requis_doc">Document requis</label>
                        </div>
                        <div class="help-text">Cette action nécessite-t-elle un document ?</div>
                    </div>

                    <!-- APERÇU -->
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0;">
                        <h4><i class="fas fa-eye"></i> Aperçu de l'action</h4>
                        <div id="preview">
                            <p><strong>Action :</strong> <span id="preview-libelle">-</span></p>
                            <p><strong>Exécutant :</strong> <span id="preview-executant">-</span></p>
                            <p><strong>Date de fin :</strong> <span id="preview-date-fin">-</span></p>
                            <p><strong>Document requis :</strong> <span id="preview-doc">Non</span></p>
                            <p><strong>Assignation automatique :</strong> <span id="preview-assignation" style="color: #1565c0; font-weight: 500;">Sera assignée automatiquement aux utilisateurs du rôle sélectionné</span></p>
                        </div>
                    </div>

                    <!-- BOUTONS -->
                    <div style="margin-top: 30px; text-align: right;">
                        <a href="<?= url('admin/action-types') ?>" class="btn-cancel">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-plus"></i> Créer le type d'action
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Mettre à jour l'aperçu en temps réel
        function updatePreview() {
            const libelle = document.getElementById('libelle').value || '-';
            const executant = document.getElementById('executant').selectedOptions[0]?.text || '-';
            const dateFin = document.getElementById('date_fin').value || '-';
            const docRequis = document.getElementById('requis_doc').checked ? 'Oui' : 'Non';
            
            document.getElementById('preview-libelle').textContent = libelle;
            document.getElementById('preview-executant').textContent = executant;
            document.getElementById('preview-date-fin').textContent = dateFin;
            document.getElementById('preview-doc').textContent = docRequis;
        }
        
        // Attacher les événements
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = ['libelle', 'executant', 'date_fin', 'requis_doc'];
            inputs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', updatePreview);
                    element.addEventListener('change', updatePreview);
                }
            });
            
            // Mise à jour initiale
            updatePreview();
            
            // Si success toast, vider le formulaire après 2 secondes
            <?php if (isset($success)): ?>
            setTimeout(() => {
                resetActionTypeForm();
            }, 2000);
            <?php endif; ?>
        });
        
        function resetActionTypeForm() {
            document.getElementById('libelle').value = '';
            document.getElementById('executant').value = '';

            document.getElementById('date_fin').value = '';
            document.getElementById('requis_doc').checked = false;
            updatePreview();
        }
    </script>
</body>
</html>