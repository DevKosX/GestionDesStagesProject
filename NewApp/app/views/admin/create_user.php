<?php
// Charger les helpers d'URL
require_once __DIR__ . '/../../helpers/url_helper.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Utilisateur - Administration</title>
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
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        
        .role-specific {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #667eea;
        }
        
        .role-specific.active {
            display: block;
        }
        
        .required {
            color: #e74c3c;
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
                <a href="<?= url('admin/create-user') ?>" class="nav-item active">
                    <i class="fas fa-user-plus"></i> Nouveau Utilisateur
                </a>
                <a href="<?= url('admin/create-stage') ?>" class="nav-item">
                    <i class="fas fa-briefcase"></i> Nouveau Stage
                </a>
                <a href="<?= url('admin/action-types') ?>" class="nav-item">
                    <i class="fas fa-tasks"></i> Types d'Actions
                </a>
                <a href="<?= url('admin/create-action-type') ?>" class="nav-item">
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
                    <i class="fas fa-user-plus"></i> Nouveau Utilisateur
                </h1>
                <p class="page-subtitle">Créer un nouveau compte utilisateur</p>
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
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- FORMULAIRE -->
            <div class="form-container">
                <form method="POST" action="<?= url('admin/create-user') ?>">
                    <h3><i class="fas fa-info-circle"></i> Informations Générales</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom">Prénom <span class="required">*</span></label>
                            <input type="text" 
                                   id="prenom" 
                                   name="prenom" 
                                   value="<?= htmlspecialchars($form_data['prenom'] ?? '') ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom <span class="required">*</span></label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?= htmlspecialchars($form_data['nom'] ?? '') ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" 
                                   id="telephone" 
                                   name="telephone" 
                                   value="<?= htmlspecialchars($form_data['telephone'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Mot de passe <span class="required">*</span></label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Minimum 6 caractères">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Rôle <span class="required">*</span></label>
                            <select id="role" name="role" required onchange="showRoleFields(this.value)">
                                <option value="">Sélectionner un rôle</option>
                                <?php foreach ($roles_available as $value => $label): ?>
                                    <option value="<?= $value ?>" 
                                            <?= ($form_data['role'] ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- CHAMPS SPÉCIFIQUES ENSEIGNANT -->
                    <div id="role-enseignant" class="role-specific">
                        <h3><i class="fas fa-chalkboard-teacher"></i> Informations Enseignant</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="bureau">Bureau</label>
                                <input type="text" 
                                       id="bureau" 
                                       name="bureau" 
                                       value="<?= htmlspecialchars($form_data['bureau'] ?? '') ?>" 
                                       placeholder="Ex: B101">
                            </div>
                            
                            <div class="form-group">
                                <label for="departement_ens">Département</label>
                                <select id="departement_ens" name="departement">
                                    <option value="">Sélectionner un département</option>
                                    <?php foreach ($departements as $dept): ?>
                                        <option value="<?= $dept['Id_Departement'] ?>" 
                                                <?= ($form_data['departement'] ?? '') == $dept['Id_Departement'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dept['Libelle']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- CHAMPS SPÉCIFIQUES TUTEUR ENTREPRISE -->
                    <div id="role-tuteur_entreprise" class="role-specific">
                        <h3><i class="fas fa-building"></i> Informations Tuteur Entreprise</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ville_entreprise">Ville de l'entreprise <span class="required">*</span></label>
                                <input type="text" 
                                       id="ville_entreprise" 
                                       name="ville_entreprise" 
                                       value="<?= htmlspecialchars($form_data['ville_entreprise'] ?? '') ?>" 
                                       placeholder="Ex: Paris, Lyon, Marseille..."
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="adresse_entreprise">Adresse de l'entreprise <span class="required">*</span></label>
                                <input type="text" 
                                       id="adresse_entreprise" 
                                       name="adresse_entreprise" 
                                       value="<?= htmlspecialchars($form_data['adresse_entreprise'] ?? '') ?>" 
                                       placeholder="Ex: 123 rue de la République..."
                                       required>
                            </div>
                        </div>
                        

                    </div>

                    <!-- BOUTONS -->
                    <div style="margin-top: 30px; text-align: right;">
                        <a href="<?= url('admin/dashboard') ?>" class="btn-cancel">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-plus"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRoleFields(role) {
            // Cacher tous les champs spécifiques
            const roleFields = document.querySelectorAll('.role-specific');
            roleFields.forEach(field => {
                field.classList.remove('active');
                // Retirer l'attribut required des champs cachés
                const inputs = field.querySelectorAll('input[required], select[required]');
                inputs.forEach(input => {
                    input.removeAttribute('required');
                });
            });
            
            // Afficher les champs correspondant au rôle sélectionné
            if (role) {
                const specificField = document.getElementById('role-' + role);
                if (specificField) {
                    specificField.classList.add('active');
                    
                    // Ajouter l'attribut required aux champs nécessaires
                    if (role === 'tuteur_entreprise') {
                        document.getElementById('ville_entreprise').setAttribute('required', 'required');
                        document.getElementById('adresse_entreprise').setAttribute('required', 'required');
                    }
                }
            }
        }
        
        // Validation du formulaire avant soumission
        function validateForm() {
            const role = document.getElementById('role').value;
            
            if (!role) {
                alert('Veuillez sélectionner un rôle');
                return false;
            }
            
            if (role === 'tuteur_entreprise') {
                const ville = document.getElementById('ville_entreprise').value.trim();
                const adresse = document.getElementById('adresse_entreprise').value.trim();
                
                if (!ville || !adresse) {
                    alert('Pour un tuteur entreprise, la ville et l\'adresse de l\'entreprise sont obligatoires');
                    return false;
                }
            }
            
            return true;
        }
        
        // Initialiser l'affichage au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            if (roleSelect.value) {
                showRoleFields(roleSelect.value);
            }
            
            // Ajouter la validation au formulaire
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });
            
            // Gérer le toast de succès
            const successToast = document.querySelector('.toast-success');
            if (successToast) {
                // Effacer le formulaire après succès
                resetForm();
                
                // Masquer le toast après 4 secondes
                setTimeout(function() {
                    if (successToast) {
                        successToast.style.display = 'none';
                    }
                }, 4000);
            }
        });
        
        function resetForm() {
            document.getElementById('prenom').value = '';
            document.getElementById('nom').value = '';
            document.getElementById('email').value = '';
            document.getElementById('telephone').value = '';
            document.getElementById('password').value = '';
            document.getElementById('role').value = '';
            
            // Effacer les champs spécifiques
            const villeEntreprise = document.getElementById('ville_entreprise');
            const adresseEntreprise = document.getElementById('adresse_entreprise');
            const bureau = document.getElementById('bureau');
            const departement = document.getElementById('departement_ens');
            
            if (villeEntreprise) villeEntreprise.value = '';
            if (adresseEntreprise) adresseEntreprise.value = '';
            if (bureau) bureau.value = '';
            if (departement) departement.value = '';
            
            // Masquer tous les champs spécifiques
            const roleFields = document.querySelectorAll('.role-specific');
            roleFields.forEach(field => {
                field.classList.remove('active');
            });
        }
    </script>
</body>
</html>