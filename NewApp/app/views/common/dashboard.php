<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Stages</title>
    <link rel="stylesheet" href="/GestionDesStagesProject/NewApp/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <a href="#dashboard" class="nav-item active" onclick="showSection('dashboard')">
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
                </a>
                <a href="#calendrier" class="nav-item" onclick="showSection('calendrier')">
                    <i class="fas fa-calendar"></i> Calendrier
                </a>
                <a href="/GestionDesStagesProject/NewApp/public/index.php/logout" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </nav>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- SECTION DASHBOARD -->
            <div id="dashboard-section" class="content-section">
                <div class="page-header">
                    <h1 class="page-title">Bienvenue, <?php echo htmlspecialchars($user->prenom ?? 'Utilisateur'); ?> !</h1>
                    <p class="page-subtitle">Voici un aperçu de vos activités récentes</p>
                </div>

                <div class="dashboard-grid">
                    <!-- APERÇU MESSAGERIE -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3 class="card-title">Messages récents</h3>
                        </div>
                        
                        <?php if (!empty($messages_recents)): ?>
                            <?php foreach (array_slice($messages_recents, 0, 3) as $message): ?>
                                <div class="message-item">
                                    <div class="message-header">
                                        <span class="message-sender"><?php echo htmlspecialchars($message->expediteur_nom . ' ' . $message->expediteur_prenom); ?></span>
                                        <span class="message-date"><?php echo date('d/m/Y', strtotime($message->date_envoi)); ?></span>
                                    </div>
                                    <div class="message-preview"><?php echo htmlspecialchars(substr($message->contenu, 0, 80) . '...'); ?></div>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn-compose" onclick="showSection('messagerie')">Voir tous les messages</button>
                        <?php else: ?>
                            <p>Aucun message récent</p>
                        <?php endif; ?>
                    </div>

                    <!-- APERÇU DOCUMENTS -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="card-title">Documents récents</h3>
                        </div>
                        
                        <?php if (!empty($documents_recents)): ?>
                            <?php foreach (array_slice($documents_recents, 0, 3) as $document): ?>
                                <div class="document-item">
                                    <div class="document-info">
                                        <div class="document-title"><?php echo htmlspecialchars($document['titre']); ?></div>
                                        <div class="document-meta">
                                            De: <?php echo htmlspecialchars($document['expediteur_nom'] . ' ' . $document['expediteur_prenom']); ?> - 
                                            <?php echo date('d/m/Y', strtotime($document['date_upload'])); ?>
                                        </div>
                                    </div>
                                    <a href="/GestionDesStagesProject/NewApp/public/index.php/documents/download?id=<?php echo $document['id']; ?>" class="btn-download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn-compose" onclick="showSection('documents')">Voir tous les documents</button>
                        <?php else: ?>
                            <p>Aucun document récent</p>
                        <?php endif; ?>
                    </div>

                    <!-- ÉVÉNEMENTS PROCHAINS -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <h3 class="card-title">Événements prochains</h3>
                        </div>
                        
                        <?php if (!empty($evenements_prochains)): ?>
                            <?php foreach ($evenements_prochains as $event): ?>
                                <div class="event-item <?php echo $event['type_evenement'] === 'urgent' ? 'event-urgent' : ''; ?>">
                                    <div class="event-date"><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></div>
                                    <div class="event-title"><?php echo htmlspecialchars($event['titre']); ?></div>
                                    <div class="event-description"><?php echo htmlspecialchars($event['description']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun événement à venir</p>
                        <?php endif; ?>
                    </div>

                    <!-- STATISTIQUES -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h3 class="card-title">Mes statistiques</h3>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div style="text-align: center; padding: 15px; background: #f8faff; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: bold; color: #3b82f6;"><?php echo $stats['messages_recus'] ?? 0; ?></div>
                                <div style="font-size: 12px; color: #6b7280;">Messages reçus</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f0f9ff; border-radius: 8px;">
                                <div style="font-size: 24px; font-weight: bold; color: #10b981;"><?php echo $stats['documents_recus'] ?? 0; ?></div>
                                <div style="font-size: 12px; color: #6b7280;">Documents reçus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION MESSAGERIE -->
            <div id="messagerie-section" class="content-section" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">Messagerie</h1>
                    <p class="page-subtitle">Gérez vos messages et conversations</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h3 class="card-title">Nouveau message</h3>
                    </div>

                    <form action="/GestionDesStagesProject/NewApp/public/index.php/messages/envoyer" method="POST">
                        <div class="document-form">
                            <div class="form-group">
                                <label class="form-label">Destinataire</label>
                                <select name="email_destinataire" class="form-select" required>
                                    <option value="">Sélectionner un destinataire</option>
                                    <?php foreach ($tous_les_users as $user_option): ?>
                                        <?php if ($user_option->Id != $user->Id): ?>
                                            <option value="<?php echo htmlspecialchars($user_option->email); ?>">
                                                <?php echo htmlspecialchars($user_option->prenom . ' ' . $user_option->nom . ' (' . $user_option->email . ')'); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Message</label>
                                <textarea name="contenu" class="form-input" rows="4" placeholder="Tapez votre message..." required></textarea>
                            </div>
                            <button type="submit" class="btn-upload">Envoyer le message</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 class="card-title">Messages reçus</h3>
                    </div>
                    
                    <div class="document-list">
                        <?php if (!empty($messages_recents)): ?>
                            <?php foreach ($messages_recents as $message): ?>
                                <div class="message-item">
                                    <div class="message-header">
                                        <span class="message-sender"><?php echo htmlspecialchars($message->expediteur_nom . ' ' . $message->expediteur_prenom); ?></span>
                                        <span class="message-date"><?php echo date('d/m/Y H:i', strtotime($message->date_envoi)); ?></span>
                                    </div>
                                    <div class="message-preview"><?php echo htmlspecialchars($message->contenu); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: #6b7280; padding: 40px;">Aucun message reçu</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION DOCUMENTS -->
            <div id="documents-section" class="content-section" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">Gestion des documents</h1>
                    <p class="page-subtitle">Uploadez et téléchargez vos documents</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <h3 class="card-title">Envoyer un document</h3>
                    </div>

                    <form action="/GestionDesStagesProject/NewApp/public/index.php/documents/upload" method="POST" enctype="multipart/form-data">
                        <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p>Cliquez pour sélectionner un fichier ou glissez-déposez ici</p>
                            <p style="font-size: 12px; color: #6b7280;">PDF, DOC, DOCX, JPG, PNG, ZIP (max 10MB)</p>
                        </div>
                        
                        <input type="file" id="file-input" name="document" style="display: none;" required onchange="updateFileName(this)">
                        <div id="file-name" style="margin-bottom: 15px; font-weight: 600; color: #3b82f6;"></div>

                        <div class="document-form">
                            <div class="form-group">
                                <label class="form-label">Destinataire</label>
                                <select name="destinataire_id" class="form-select" required>
                                    <option value="">Sélectionner un destinataire</option>
                                    <?php foreach ($tous_les_users as $user_option): ?>
                                        <?php if ($user_option->Id != $user->Id): ?>
                                            <option value="<?php echo $user_option->Id; ?>">
                                                <?php echo htmlspecialchars($user_option->prenom . ' ' . $user_option->nom); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Titre du document</label>
                                <input type="text" name="titre" class="form-input" placeholder="Ex: Convention de stage" required>
                            </div>
                            <button type="submit" class="btn-upload">Envoyer le document</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h3 class="card-title">Documents reçus</h3>
                    </div>
                    
                    <div class="document-list">
                        <?php foreach ($documents_recents as $document): ?>
                            <div class="document-item">
                                <div class="document-info">
                                    <div class="document-title"><?php echo htmlspecialchars($document['titre']); ?></div>
                                    <div class="document-meta">
                                        De: <?php echo htmlspecialchars($document['expediteur_nom'] . ' ' . $document['expediteur_prenom']); ?> - 
                                        <?php echo date('d/m/Y H:i', strtotime($document['date_upload'])); ?> - 
                                        <?php echo round($document['taille'] / 1024, 1); ?> KB
                                    </div>
                                </div>
                                <a href="/GestionDesStagesProject/NewApp/public/index.php/documents/download?id=<?php echo $document['id']; ?>" class="btn-download">
                                    <i class="fas fa-download"></i> Télécharger
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION CALENDRIER -->
            <div id="calendrier-section" class="content-section" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">Calendrier</h1>
                    <p class="page-subtitle">Événements et dates importantes</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="card-title">Événements à venir</h3>
                    </div>

                    <div class="events-list">
                        <?php foreach ($evenements_prochains as $event): ?>
                            <div class="event-item <?php echo $event['type_evenement'] === 'urgent' ? 'event-urgent' : ''; ?>">
                                <div class="event-date"><?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?></div>
                                <div class="event-title"><?php echo htmlspecialchars($event['titre']); ?></div>
                                <div class="event-description"><?php echo htmlspecialchars($event['description']); ?></div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($evenements_prochains)): ?>
                            <p style="text-align: center; color: #6b7280; padding: 40px;">Aucun événement programmé</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionName) {
            // Masquer toutes les sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Afficher la section sélectionnée
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // Mettre à jour la navigation
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => item.classList.remove('active'));
            
            // Ajouter la classe active au bon élément
            const activeLink = document.querySelector(`a[href="#${sectionName}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
            
            // Mettre à jour l'URL
            window.location.hash = sectionName;
        }

        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : '';
            const fileNameElement = document.getElementById('file-name');
            if (fileNameElement) {
                fileNameElement.textContent = fileName ? 'Fichier sélectionné: ' + fileName : '';
            }
        }

        // Gérer le chargement initial et les changements de hash
        function handleHashChange() {
            const hash = window.location.hash.substring(1); // Enlever le #
            if (hash && ['dashboard', 'messagerie', 'documents', 'calendrier'].includes(hash)) {
                showSection(hash);
            } else {
                showSection('dashboard'); // Section par défaut
            }
        }

        // Écouter les changements de hash
        window.addEventListener('hashchange', handleHashChange);

        // Initialiser au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            handleHashChange();
        });

        // Afficher les messages de succès/erreur s'il y en a
        <?php if (isset($_SESSION['success_message'])): ?>
            alert('<?php echo addslashes($_SESSION['success_message']); ?>');
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            alert('<?php echo addslashes($_SESSION['error_message']); ?>');
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html> 