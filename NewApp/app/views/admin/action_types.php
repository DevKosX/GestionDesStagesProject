<?php
// Charger les helpers d'URL
require_once __DIR__ . '/../../helpers/url_helper.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types d'Actions - Administration</title>
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard-test.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .actions-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .actions-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .actions-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }
        
        .actions-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .actions-table tr:hover {
            background: #f8f9fa;
        }
        
        .executant-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .delai-info {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            font-size: 0.9em;
        }
        
        .doc-required {
            color: #28a745;
            font-weight: 500;
        }
        
        .doc-not-required {
            color: #6c757d;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .btn-action {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 3px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white !important;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #1e7e34 0%, #17a2b8 100%);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
            color: white !important;
        }
        
        .btn-delete:hover {
            background: linear-gradient(135deg, #bd2130 0%, #d61a7f 100%);
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
                <a href="<?= url('admin/action-types') ?>" class="nav-item active">
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
                    <i class="fas fa-tasks"></i> Types d'Actions
                </h1>
                <p class="page-subtitle">Configuration des types d'actions du système</p>
            </div>

            <!-- MESSAGES -->
            <?php if (isset($_GET['success']) && $_GET['success'] === 'type_created'): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    Type d'action créé avec succès !
                </div>
            <?php endif; ?>

            <!-- ACTIONS RAPIDES -->
            <div style="margin-bottom: 20px;">
                <a href="<?= url('admin/create-action-type') ?>" class="btn-admin">
                    <i class="fas fa-plus"></i> Nouveau Type d'Action
                </a>
            </div>

            <!-- LISTE DES TYPES D'ACTIONS -->
            <div class="actions-container">
                <table class="actions-table">
                    <thead>
                        <tr>
                            <th>Libellé</th>
                            <th>Rôle responsable</th>
                            <th>Date de fin</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($action_types)): ?>
                            <?php foreach ($action_types as $actionType): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($actionType['libelle']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="executant-badge">
                                            <?= htmlspecialchars($actionType['Executant']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="delai-info">
                                            <?php if (!empty($actionType['delaiEnJours'])): ?>
                                                <?php 
                                                // Calculer la date de fin basée sur le délai
                                                $dateFin = new DateTime();
                                                $dateFin->add(new DateInterval('P' . $actionType['delaiEnJours'] . 'D'));
                                                ?>
                                                <strong><?= $dateFin->format('d/m/Y') ?></strong>
                                                <br>
                                                <small style="color: #6c757d;">(dans <?= $actionType['delaiEnJours'] ?> jour<?= $actionType['delaiEnJours'] > 1 ? 's' : '' ?>)</small>
                                            <?php else: ?>
                                                <span style="color: #6c757d;">Non définie</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($actionType['requisDoc']): ?>
                                            <span class="doc-required">
                                                <i class="fas fa-check-circle"></i> Requis
                                            </span>
                                        <?php else: ?>
                                            <span class="doc-not-required">
                                                <i class="fas fa-times-circle"></i> Non requis
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn-action btn-edit" 
                                                title="Modifier" 
                                                data-id="<?= $actionType['Id_TypeAction'] ?>"
                                                data-libelle="<?= htmlspecialchars($actionType['libelle']) ?>"
                                                data-executant="<?= htmlspecialchars($actionType['Executant']) ?>"
            
                                                data-delai="<?= $actionType['delaiEnJours'] ?? 0 ?>"
                                                data-requis-doc="<?= $actionType['requisDoc'] ? 'true' : 'false' ?>"
                                                type="button">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-action btn-delete" 
                                                title="Supprimer" 
                                                data-id="<?= $actionType['Id_TypeAction'] ?>"
                                                data-libelle="<?= htmlspecialchars($actionType['libelle']) ?>"
                                                type="button">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-tasks" style="font-size: 3em; color: #ddd;"></i>
                                    <p>Aucun type d'action configuré</p>
                                    <a href="<?= url('admin/create-action-type') ?>" class="btn-admin">
                                        <i class="fas fa-plus"></i> Créer le premier type
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        console.log('JavaScript Types d\'Actions chargé - Avec modification/suppression');
        
        // Variables globales pour les URLs
        const BASE_URL = '<?= rtrim(url(""), "/") ?>';
        const UPDATE_URL = BASE_URL + '/admin/update-action-type';
        const DELETE_URL = BASE_URL + '/admin/delete-action-type';
        
        console.log('URLs configurées:', { BASE_URL, UPDATE_URL, DELETE_URL });
        
        // Test immédiat des fonctions
        window.testEditFunction = function() {
            console.log('Test de la fonction editActionType');
            editActionType(1, 'Test', 'Etudiant', 10, false);
        };
        
        window.testDeleteFunction = function() {
            console.log('Test de la fonction deleteActionType');
            deleteActionType(1, 'Test');
        };
        
        function editActionType(id, libelle, executant, delaiEnJours, requisDoc) {
            console.log('editActionType appelé avec:', {id, libelle, executant, delaiEnJours, requisDoc});
            
            // Calculer la date de fin à partir du délai
            let dateFin = '';
            if (delaiEnJours > 0) {
                const aujourdhui = new Date();
                aujourdhui.setDate(aujourdhui.getDate() + delaiEnJours);
                dateFin = aujourdhui.toISOString().split('T')[0];
            }
            
            // Créer une modal de modification moderne
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5); z-index: 1000; display: flex;
                align-items: center; justify-content: center;
            `;
            
            // Options pour les listes déroulantes
            const executantOptions = [
                'Etudiant', 'Tuteur pédagogique', 'Tuteur entreprise', 'Secrétaire'
            ];
            
            let executantOptionsHtml = '';
            executantOptions.forEach(option => {
                const selected = option === executant ? 'selected' : '';
                executantOptionsHtml += `<option value="${option}" ${selected}>${option}</option>`;
            });
            
            modal.innerHTML = `
                <div style="background: white; padding: 30px; border-radius: 10px; width: 500px; max-height: 80vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <h3 style="margin-top: 0; color: #152d65; margin-bottom: 20px;">
                        <i class="fas fa-edit"></i> Modifier le type d'action
                    </h3>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Libellé :</label>
                        <input type="text" id="edit-libelle" value="${libelle}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Exécutant (Rôle responsable) :</label>
                        <select id="edit-executant" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                            ${executantOptionsHtml}
                        </select>
                    </div>
                    

                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 500;">Date de fin :</label>
                        <input type="date" id="edit-date-fin" value="${dateFin}" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; font-weight: 500;">
                            <input type="checkbox" id="edit-requis-doc" ${requisDoc ? 'checked' : ''} style="margin-right: 8px;">
                            Document requis
                        </label>
                    </div>
                    
                    <div style="text-align: right;">
                        <button onclick="closeModal()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px; cursor: pointer;">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button onclick="saveActionType(${id})" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            document.getElementById('edit-libelle').focus();
            
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
        
        function saveActionType(id) {
            const libelle = document.getElementById('edit-libelle').value.trim();
            const executant = document.getElementById('edit-executant').value;

            const dateFin = document.getElementById('edit-date-fin').value;
            const requisDoc = document.getElementById('edit-requis-doc').checked;
            
            if (!libelle || !executant) {
                alert('Le libellé et l\'exécutant sont obligatoires');
                return;
            }
            
            // Envoyer la requête de modification
            console.log('Tentative de modification avec URL:', UPDATE_URL);
            
            fetch(UPDATE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id,
                    libelle: libelle,
                    executant: executant,

                    date_fin: dateFin,
                    requis_doc: requisDoc
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    alert('Type d\'action modifié avec succès');
                    location.reload();
                } else {
                    alert('Erreur lors de la modification: ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la modification: ' + error.message);
            });
        }
        
        function deleteActionType(id, libelle) {
            console.log('deleteActionType appelé avec:', {id, libelle});
            const confirmMessage = `Êtes-vous sûr de vouloir supprimer le type d'action "${libelle}" ?\n\n⚠️ ATTENTION : Cette action supprimera également toutes les actions associées à ce type.\n\nCette action est irréversible.`;
            
            if (confirm(confirmMessage)) {
                console.log('Tentative de suppression avec URL:', DELETE_URL);
                
                fetch(DELETE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Type d\'action supprimé avec succès');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression: ' + (data.message || 'Erreur inconnue'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors de la suppression: ' + error.message);
                });
            }
        }
        
        // Ajouter des listeners après le chargement du DOM
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé, ajout des listeners');
            
            // Ajouter des listeners sur tous les boutons de modification
            document.querySelectorAll('.btn-edit').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    console.log('Clic détecté sur bouton modifier');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Récupérer les données depuis les data-attributes
                    const id = parseInt(this.dataset.id);
                    const libelle = this.dataset.libelle;
                    const executant = this.dataset.executant;
                    const delaiEnJours = parseInt(this.dataset.delai) || 0;
                    const requisDoc = this.dataset.requisDoc === 'true';
                    
                    console.log('Données récupérées:', {id, libelle, executant, delaiEnJours, requisDoc});
                    
                    // Appeler la fonction de modification
                    editActionType(id, libelle, executant, delaiEnJours, requisDoc);
                });
            });
            
            // Ajouter des listeners sur tous les boutons de suppression
            document.querySelectorAll('.btn-delete').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    console.log('Clic détecté sur bouton supprimer');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Récupérer les données depuis les data-attributes
                    const id = parseInt(this.dataset.id);
                    const libelle = this.dataset.libelle;
                    
                    console.log('Données récupérées pour suppression:', {id, libelle});
                    
                    // Appeler la fonction de suppression
                    deleteActionType(id, libelle);
                });
            });
            
            console.log('Listeners ajoutés:', {
                edit_buttons: document.querySelectorAll('.btn-edit').length,
                delete_buttons: document.querySelectorAll('.btn-delete').length
            });
        });
    </script>
</body>
</html> 