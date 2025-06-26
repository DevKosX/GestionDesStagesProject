<?php
// Charger les helpers d'URL
require_once __DIR__ . '/../../helpers/url_helper.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($role_display) ?> - Administration</title>
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard-test.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .search-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .search-btn:hover {
            transform: translateY(-2px);
        }
        
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .user-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .user-card:hover {
            transform: translateY(-3px);
        }
        
        .user-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 20px;
            margin-right: 15px;
        }
        
        .user-info h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .user-role {
            color: #666;
            font-size: 14px;
            margin-top: 2px;
        }
        
        .user-details {
            margin-top: 15px;
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .detail-item i {
            width: 20px;
            color: #667eea;
            margin-right: 10px;
        }
        
        .no-users {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-users i {
            font-size: 4em;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .back-button {
            background: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }
        
        .back-button:hover {
            background: #5a6268;
            color: white;
        }
        
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            color: white;
        }
        
        .role-etudiants { background: #28a745; }
        .role-enseignants { background: #007bff; }
        .role-tuteur_entreprise { background: #ffc107; color: #333; }
        .role-administrateurs { background: #dc3545; }
        
        .clear-search {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .clear-search:hover {
            background: #5a6268;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-edit {
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex: 1;
            justify-content: center;
            transition: background 0.3s ease;
        }
        
        .btn-edit:hover {
            background: #218838;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex: 1;
            justify-content: center;
            transition: background 0.3s ease;
        }
        
        .btn-delete:hover {
            background: #c82333;
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
                    <i class="fas fa-users"></i> <?= htmlspecialchars($role_display) ?>
                </h1>
                <p class="page-subtitle">Liste des <?= strtolower($role_display) ?> du système</p>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <a href="<?= url('admin/dashboard') ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i> Retour au Dashboard
                </a>
                <button class="btn-admin" onclick="addNewUser('<?= $role ?>')">
                    <i class="fas fa-plus"></i> Ajouter <?= htmlspecialchars($role_display) ?>
                </button>
            </div>

            <!-- RECHERCHE -->
            <div class="search-container">
                <form method="GET" action="<?= url('admin/users-by-role') ?>" class="search-form">
                    <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
                    <input type="text" 
                           name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Rechercher par nom, prénom ou email..." 
                           class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <?php if ($search): ?>
                        <a href="<?= url('admin/users-by-role') ?>?role=<?= htmlspecialchars($role) ?>" class="clear-search">
                            <i class="fas fa-times"></i> Effacer
                        </a>
                    <?php endif; ?>
                </form>
                
                <?php if ($search): ?>
                    <div style="margin-top: 15px; color: #666;">
                        <i class="fas fa-search"></i> 
                        Résultats pour "<strong><?= htmlspecialchars($search) ?></strong>" : 
                        <strong><?= count($users) ?></strong> utilisateur(s) trouvé(s)
                    </div>
                <?php endif; ?>
            </div>

            <!-- LISTE DES UTILISATEURS -->
            <?php if (!empty($users)): ?>
                <div class="users-grid">
                    <?php foreach ($users as $userItem): ?>
                        <div class="user-card">
                            <div class="user-header">
                                <div class="user-avatar">
                                    <?= strtoupper(substr($userItem['prenom'], 0, 1) . substr($userItem['nom'], 0, 1)) ?>
                                </div>
                                <div class="user-info">
                                    <h3><?= htmlspecialchars($userItem['prenom'] . ' ' . $userItem['nom']) ?></h3>
                                    <div class="user-role">
                                        <span class="role-badge role-<?= $role ?>">
                                            <?= htmlspecialchars($role_display) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="user-details">
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?= htmlspecialchars($userItem['email']) ?></span>
                                </div>
                                
                                <?php if (!empty($userItem['telephone'])): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-phone"></i>
                                        <span><?= htmlspecialchars($userItem['telephone']) ?></span>
                                    </div>
                                <?php endif; ?>
                                

                                
                                <?php if ($role === 'etudiants'): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>Étudiant</span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($role === 'enseignants' && !empty($userItem['Bureau'])): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-door-open"></i>
                                        <span>Bureau: <?= htmlspecialchars($userItem['Bureau']) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($role === 'tuteur_entreprise' && !empty($userItem['Id_Entreprise'])): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-building"></i>
                        
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-actions" style="margin-top: 15px; display: flex; gap: 10px;">
                                <button class="btn-edit" onclick="editUser(<?= $userItem['id'] ?>, '<?= htmlspecialchars($userItem['nom']) ?>', '<?= htmlspecialchars($userItem['prenom']) ?>', '<?= htmlspecialchars($userItem['email']) ?>', '<?= htmlspecialchars($userItem['telephone'] ?? '') ?>', '<?= htmlspecialchars($userItem['Bureau'] ?? '') ?>', '<?= $role ?>')"
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn-delete" onclick="deleteUser(<?= $userItem['id'] ?>, '<?= htmlspecialchars($userItem['nom'] . ' ' . $userItem['prenom']) ?>', '<?= $role ?>')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-users">
                    <i class="fas fa-users"></i>
                    <h3>Aucun <?= strtolower($role_display) ?> trouvé</h3>
                    <?php if ($search): ?>
                        <p>Aucun résultat pour votre recherche "<strong><?= htmlspecialchars($search) ?></strong>"</p>
                        <a href="<?= url('admin/users-by-role') ?>?role=<?= htmlspecialchars($role) ?>" class="btn-admin">
                            <i class="fas fa-list"></i> Voir tous les <?= strtolower($role_display) ?>
                        </a>
                    <?php else: ?>
                        <p>Il n'y a pas encore de <?= strtolower($role_display) ?> dans le système.</p>
                        <a href="<?= url('admin/create-user') ?>" class="btn-admin">
                            <i class="fas fa-plus"></i> Créer un utilisateur
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function addNewUser(role) {
            const roleDisplay = {
                'etudiants': 'étudiant',
                'enseignants': 'enseignant',
                'tuteur_entreprise': 'tuteur entreprise',
                'administrateurs': 'administrateur'
            };
            
            if (confirm(`Voulez-vous ajouter un nouveau ${roleDisplay[role]} ?`)) {
                // Rediriger vers une page de création d'utilisateur avec le rôle prédéfini
                window.location.href = '<?= url("admin/create-user") ?>?role=' + role;
            }
        }
        
        function editUser(id, nom, prenom, email, telephone, bureau, role) {
            // Créer une modal de modification dynamique selon le rôle
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5); z-index: 1000; display: flex;
                align-items: center; justify-content: center;
            `;
            
            // Champs de base
            let formFields = `
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nom :</label>
                    <input type="text" id="edit-nom" value="${nom}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Prénom :</label>
                    <input type="text" id="edit-prenom" value="${prenom}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Email :</label>
                    <input type="email" id="edit-email" value="${email}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 500;">Téléphone :</label>
                    <input type="tel" id="edit-telephone" value="${telephone}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
            `;
            
            // Champs spécifiques selon le rôle
            if (role === 'etudiants') {
                formFields += `
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">N° Étudiant :</label>
                                                    <input type="text" id="edit-numEtudiant" value="" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px; display: none;">
                    </div>
                `;
            } else if (role === 'enseignants') {
                formFields += `
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Bureau :</label>
                        <input type="text" id="edit-bureau" value="${bureau}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    </div>
                `;
            }
            
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; width: 450px; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <h3 style="margin-top: 0; color: #152d65; margin-bottom: 20px;">
                        <i class="fas fa-edit"></i> Modifier l'utilisateur
                    </h3>
                    ${formFields}
                    <div style="text-align: right; margin-top: 20px;">
                        <button onclick="closeModal()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px; cursor: pointer;">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button onclick="saveUser(${id}, '${role}')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.getElementById('edit-nom').focus();
            
            // Fermer modal si clic à l'extérieur
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            
            window.currentModal = modal;
        }
        
        function closeModal() {
            if (window.currentModal) {
                document.body.removeChild(window.currentModal);
                window.currentModal = null;
            }
        }
        
        function saveUser(id, role) {
            const nom = document.getElementById('edit-nom').value.trim();
            const prenom = document.getElementById('edit-prenom').value.trim();
            const email = document.getElementById('edit-email').value.trim();
            const telephone = document.getElementById('edit-telephone').value.trim();
            
            if (!nom || !prenom || !email) {
                alert('Les champs Nom, Prénom et Email sont obligatoires');
                return;
            }
            
            // Construire l'objet de données
            const userData = {
                id: id,
                nom: nom,
                prenom: prenom,
                email: email,
                telephone: telephone,
                role: role
            };
            
            // Ajouter les champs spécifiques selon le rôle
            if (role === 'etudiants') {
                // La table etudiant n'a plus de champ numEtudiant
                // userData.numEtudiant = '';
            } else if (role === 'enseignants') {
                const bureau = document.getElementById('edit-bureau')?.value.trim() || '';
                userData.bureau = bureau;
            }
            
            // Envoyer la requête de modification
            fetch('<?= url("admin/update-user") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    alert('Utilisateur modifié avec succès');
                    location.reload();
                } else {
                    alert('Erreur lors de la modification: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la modification');
            });
        }
        
        function deleteUser(id, name, role) {
            let confirmMessage = `Êtes-vous sûr de vouloir supprimer l'utilisateur "${name}" ?\n\nCette action est irréversible.`;
            
            // Ajouter des informations spécifiques selon le rôle
            confirmMessage += '\n\n💬 INFO : Les messages envoyés par cet utilisateur seront automatiquement supprimés.';
            
            switch(role) {
                case 'etudiants':
                    confirmMessage += '\n\n📝 INFO : Tous les stages, actions et inscriptions de cet étudiant seront automatiquement supprimés.';
                    break;
                case 'enseignants':
                    confirmMessage += '\n\n📝 INFO : Les stages supervisés par cet enseignant seront automatiquement mis à jour (superviseur retiré).';
                    break;
                case 'tuteur_entreprise':
                    confirmMessage += '\n\n📝 INFO : Les stages associés à ce tuteur entreprise seront automatiquement mis à jour (tuteur retiré).';
                    break;
                case 'administrateurs':
                    confirmMessage += '\n\n✅ Cette suppression n\'affectera aucun stage.';
                    break;
            }
            
            if (confirm(confirmMessage)) {
                fetch('<?= url("admin/delete-user") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        role: role
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Utilisateur supprimé avec succès');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression: ' + (data.message || 'Erreur inconnue'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression');
                });
            }
        }
    </script>
</body>
</html> 