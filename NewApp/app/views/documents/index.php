<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Documents</title>
    <link rel="stylesheet" href="/GestionDesStagesProject/NewApp/public/assets/css/dashboard.css">
    <style>
        .documents-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .upload-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .upload-form {
            display: grid;
            gap: 20px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .form-group input, .form-group select {
            padding: 12px;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .file-upload-area {
            border: 2px dashed #3498db;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            background: #f8f9fa;
            border-color: #2980b9;
        }
        
        .documents-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .documents-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .documents-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .document-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .document-info {
            flex: 1;
        }
        
        .document-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .document-meta {
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .document-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-download {
            background: #27ae60;
            color: white;
        }
        
        .btn-download:hover {
            background: #229954;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .documents-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="documents-container">
        <h1>📄 Gestion des Documents</h1>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <!-- Section d'upload -->
        <div class="upload-section">
            <h2>📤 Partager un Document</h2>
            <form class="upload-form" action="/GestionDesStagesProject/NewApp/public/index.php/documents/upload" method="post" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label for="titre">Titre du document</label>
                        <input type="text" id="titre" name="titre" required placeholder="Ex: Rapport de stage">
                    </div>
                    
                    <div class="form-group">
                        <label for="destinataire_id">Destinataire</label>
                        <select id="destinataire_id" name="destinataire_id" required>
                            <option value="">Choisir un destinataire</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user->Id ?>"><?= htmlspecialchars($user->nom . ' ' . $user->prenom . ' (' . $user->email . ')') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="document">Fichier</label>
                    <div class="file-upload-area" onclick="document.getElementById('document').click()">
                        <input type="file" id="document" name="document" required style="display: none;" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar">
                        <div>
                            <i>📁</i>
                            <p>Cliquez pour sélectionner un fichier</p>
                            <small>Formats acceptés: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR (max 10MB)</small>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">📤 Partager le Document</button>
            </form>
        </div>
        
        <!-- Documents partagés et reçus -->
        <div class="documents-grid">
            <!-- Documents envoyés -->
            <div class="documents-section">
                <h3>📤 Documents Partagés</h3>
                <?php if (!empty($documents_envoyes)): ?>
                    <?php foreach ($documents_envoyes as $doc): ?>
                        <div class="document-item">
                            <div class="document-info">
                                <div class="document-title"><?= htmlspecialchars($doc['titre']) ?></div>
                                <div class="document-meta">
                                    À: <?= htmlspecialchars($doc['destinataire_nom'] . ' ' . $doc['destinataire_prenom']) ?><br>
                                    Date: <?= date('d/m/Y à H:i', strtotime($doc['date_upload'])) ?><br>
                                    Taille: <?= number_format($doc['taille'] / 1024, 1) ?> KB
                                </div>
                            </div>
                            <div class="document-actions">
                                <a href="/GestionDesStagesProject/NewApp/public/index.php/documents/download?id=<?= $doc['id'] ?>" class="btn btn-download">⬇️ Télécharger</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">Aucun document partagé</p>
                <?php endif; ?>
            </div>
            
            <!-- Documents reçus -->
            <div class="documents-section">
                <h3>📥 Documents Reçus</h3>
                <?php if (!empty($documents_recus)): ?>
                    <?php foreach ($documents_recus as $doc): ?>
                        <div class="document-item">
                            <div class="document-info">
                                <div class="document-title"><?= htmlspecialchars($doc['titre']) ?></div>
                                <div class="document-meta">
                                    De: <?= htmlspecialchars($doc['expediteur_nom'] . ' ' . $doc['expediteur_prenom']) ?><br>
                                    Date: <?= date('d/m/Y à H:i', strtotime($doc['date_upload'])) ?><br>
                                    Taille: <?= number_format($doc['taille'] / 1024, 1) ?> KB
                                </div>
                            </div>
                            <div class="document-actions">
                                <a href="/GestionDesStagesProject/NewApp/public/index.php/documents/download?id=<?= $doc['id'] ?>" class="btn btn-download">⬇️ Télécharger</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #7f8c8d; padding: 40px;">Aucun document reçu</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="/GestionDesStagesProject/NewApp/public/index.php/dashboard" class="btn btn-primary">🏠 Retour au Dashboard</a>
        </div>
    </div>
    
    <script>
        // Script pour améliorer l'upload de fichiers
        document.getElementById('document').addEventListener('change', function(e) {
            const fileArea = document.querySelector('.file-upload-area');
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Aucun fichier sélectionné';
            fileArea.innerHTML = `
                <div>
                    <i>📄</i>
                    <p>Fichier sélectionné: <strong>${fileName}</strong></p>
                    <small>Cliquez pour changer de fichier</small>
                </div>
            `;
        });
    </script>
</body>
</html> 