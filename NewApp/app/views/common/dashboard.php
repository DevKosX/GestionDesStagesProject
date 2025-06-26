<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion des Stages</title>
    <link rel="stylesheet" href="<?= asset('css/dashboard.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/dashboard-test.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/no-animations.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="<?= asset('js/notifications.js') ?>"></script>
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
                <a href="<?= url('change-password') ?>" class="nav-item">
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
                                    <a href="<?= url('documents/download?id=' . $document['id']) ?>" class="btn-download">
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
                    <?php if ($role !== 'admin'): ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <h3 class="card-title">Événements prochains</h3>
                        </div>
                        
                                            <div class="events-list">
                        <?php if (!empty($evenements_prochains)): ?>
                            <?php foreach ($evenements_prochains as $event): ?>
                                    <div class="event-item event-<?php echo $event['type_evenement']; ?>" data-type="<?php echo $event['type_evenement']; ?>">
                                        <div class="event-icon">
                                            <?php
                                            $icon = 'fas fa-calendar';
                                            switch($event['type_evenement']) {
                                                case 'soutenance':
                                                    $icon = 'fas fa-graduation-cap';
                                                    break;
                                                case 'urgent':
                                                    $icon = 'fas fa-exclamation-triangle';
                                                    break;
                                                case 'retard':
                                                    $icon = 'fas fa-clock';
                                                    break;
                                                case 'termine':
                                                    $icon = 'fas fa-check-circle';
                                                    break;
                                                case 'action':
                                                default:
                                                    $icon = 'fas fa-tasks';
                                                    break;
                                            }
                                            ?>
                                            <i class="<?php echo $icon; ?>"></i>
                                        </div>
                                        
                                        <div class="event-content">
                                            <div class="event-date">
                                                <i class="fas fa-calendar-day"></i>
                                                <?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?>
                                                
                                                <?php if (isset($event['jours_restants'])): ?>
                                                    <?php if ($event['jours_restants'] < 0): ?>
                                                        <span class="event-badge badge-retard">
                                                            <i class="fas fa-exclamation"></i>
                                                            <?php echo abs($event['jours_restants']); ?> jour(s) de retard
                                                        </span>
                                                    <?php elseif ($event['jours_restants'] == 0): ?>
                                                        <span class="event-badge badge-urgent">
                                                            <i class="fas fa-clock"></i>
                                                            Aujourd'hui
                                                        </span>
                                                    <?php elseif ($event['jours_restants'] <= 3): ?>
                                                        <span class="event-badge badge-urgent">
                                                            <i class="fas fa-hourglass-half"></i>
                                                            Dans <?php echo $event['jours_restants']; ?> jour(s)
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if (isset($event['est_realise']) && $event['est_realise']): ?>
                                                    <span class="event-badge badge-termine">
                                                        <i class="fas fa-check"></i>
                                                        Terminé
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                    <div class="event-title"><?php echo htmlspecialchars($event['titre']); ?></div>
                                    <div class="event-description"><?php echo htmlspecialchars($event['description']); ?></div>
                                            
                                            <?php if ($role !== 'eleve' && (isset($event['executant']) || isset($event['requisDoc']))): ?>
                                                <div class="event-meta">
                                                    <?php if (isset($event['executant'])): ?>
                                                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($event['executant']); ?></span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isset($event['requisDoc']) && $event['requisDoc']): ?>
                                                        <span class="event-badge badge-doc-requis">
                                                            <i class="fas fa-file-alt"></i>
                                                            Document requis
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (isset($event['lienDocument']) && !empty($event['lienDocument'])): ?>
                                                        <a href="<?php echo htmlspecialchars($event['lienDocument']); ?>" class="event-badge badge-doc-requis" style="text-decoration: none;">
                                                            <i class="fas fa-download"></i>
                                                            Télécharger
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-calendar-check"></i>
                                    <h3>Aucun événement programmé</h3>
                                    <p>Tous vos événements et actions apparaîtront ici</p>
                                </div>
                        <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- STATISTIQUES -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h3 class="card-title">Mes statistiques</h3>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px;">
                            <div style="text-align: center; padding: 15px; background: #f8faff; border-radius: 8px;">
                                <div style="font-size: 20px; font-weight: bold; color: #152d65;"><?php echo $stats['messages_recus'] ?? 0; ?></div>
                                <div style="font-size: 11px; color: #6b7280;">Messages reçus</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #ecfdf5; border-radius: 8px;">
                                <div style="font-size: 20px; font-weight: bold; color: #059669;"><?php echo $stats['messages_envoyes'] ?? 0; ?></div>
                                <div style="font-size: 11px; color: #6b7280;">Messages envoyés</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #f0f9ff; border-radius: 8px;">
                                <div style="font-size: 20px; font-weight: bold; color: #10b981;"><?php echo $stats['documents_recus'] ?? 0; ?></div>
                                <div style="font-size: 11px; color: #6b7280;">Documents reçus</div>
                            </div>
                            <div style="text-align: center; padding: 15px; background: #fef3c7; border-radius: 8px;">
                                <div style="font-size: 20px; font-weight: bold; color: #d97706;"><?php echo $stats['documents_envoyes'] ?? 0; ?></div>
                                <div style="font-size: 11px; color: #6b7280;">Documents envoyés</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION MESSAGERIE -->
            <div id="messagerie-section" class="content-section messagerie-section" style="display: none;">
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

                                            <form action="<?= url('messages/envoyer') ?>" method="POST">
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

                <!-- Onglets pour les messages -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3 class="card-title">Mes messages</h3>
                    </div>
                    
                    <!-- Navigation par onglets -->
                    <div class="tabs-navigation">
                        <button class="tab-btn active" onclick="showMessageTab('recus')">
                            <i class="fas fa-inbox"></i> Messages reçus <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;"><?php echo count($messages_recents); ?></span>
                        </button>
                        <button class="tab-btn" onclick="showMessageTab('envoyes')">
                            <i class="fas fa-paper-plane"></i> Messages envoyés <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;"><?php echo count($messages_envoyes_recents); ?></span>
                        </button>
                    </div>
                    
                    <!-- Contenu des onglets -->
                    <div id="messages-recus-tab" class="tab-content active">
                        <div class="document-list">
                            <?php if (!empty($messages_recents)): ?>
                                <?php foreach ($messages_recents as $message): ?>
                                    <div class="message-item">
                                        <div class="message-header">
                                            <span class="message-sender">De: <?php echo htmlspecialchars($message->expediteur_nom . ' ' . $message->expediteur_prenom); ?></span>
                                            <span class="message-date"><?php echo date('d/m/Y H:i', strtotime($message->date_envoi)); ?></span>
                                        </div>
                                        <div class="message-preview"><?php echo htmlspecialchars($message->contenu); ?></div>
                                        <div class="message-actions">
                                            <button class="btn-reply" onclick="openReplyModal('<?php echo $message->expediteur_email; ?>', '<?php echo htmlspecialchars($message->expediteur_nom . ' ' . $message->expediteur_prenom); ?>')">
                                                <i class="fas fa-reply"></i> Répondre
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-inbox"></i>
                                    <h3>Aucun message reçu</h3>
                                    <p>Vous n'avez pas encore reçu de messages. Les nouveaux messages apparaîtront ici.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="messages-envoyes-tab" class="tab-content" style="display: none;">
                        <div class="document-list">
                            <?php if (!empty($messages_envoyes_recents)): ?>
                                <?php foreach ($messages_envoyes_recents as $message): ?>
                                    <div class="message-item">
                                        <div class="message-header">
                                            <span class="message-sender">À: <?php echo htmlspecialchars($message->destinataire_nom . ' ' . $message->destinataire_prenom); ?></span>
                                            <span class="message-date"><?php echo date('d/m/Y H:i', strtotime($message->date_envoi)); ?></span>
                                        </div>
                                        <div class="message-preview"><?php echo htmlspecialchars($message->contenu); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-paper-plane"></i>
                                    <h3>Aucun message envoyé</h3>
                                    <p>Vous n'avez pas encore envoyé de messages. Utilisez le formulaire ci-dessus pour commencer une conversation.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION DOCUMENTS -->
            <div id="documents-section" class="content-section documents-section" style="display: none;">
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

                                            <form action="<?= url('documents/upload') ?>" method="POST" enctype="multipart/form-data">
                        <div class="upload-zone" onclick="document.getElementById('file-input').click()">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p>Cliquez pour sélectionner un fichier ou glissez-déposez ici</p>
                            <p style="font-size: 12px; color: #6b7280;">PDF, DOC, DOCX, JPG, PNG, ZIP (max 10MB)</p>
                        </div>
                        
                        <input type="file" id="file-input" name="document" style="display: none;" required onchange="updateFileName(this)">
                        <div id="file-name" style="margin-bottom: 15px; font-weight: 600; color: #152d65;"></div>

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

                <!-- Onglets pour les documents -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="card-title">Mes documents</h3>
                    </div>
                    
                    <!-- Navigation par onglets -->
                    <div class="tabs-navigation">
                        <button class="tab-btn active" onclick="showDocumentTab('recus')">
                            <i class="fas fa-download"></i> Documents reçus <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;"><?php echo count($documents_recents); ?></span>
                        </button>
                        <button class="tab-btn" onclick="showDocumentTab('envoyes')">
                            <i class="fas fa-upload"></i> Documents envoyés <span style="background: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 12px; font-size: 11px; margin-left: 8px;"><?php echo count($documents_envoyes_recents); ?></span>
                        </button>
                    </div>
                    
                    <!-- Contenu des onglets -->
                    <div id="documents-recus-tab" class="tab-content active">
                        <div class="document-list">
                            <?php if (!empty($documents_recents)): ?>
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
                                        <a href="<?= url('documents/download?id=' . $document['id']) ?>" class="btn-download">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-file-download"></i>
                                    <h3>Aucun document reçu</h3>
                                    <p>Vous n'avez pas encore reçu de documents. Les documents partagés avec vous apparaîtront ici.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="documents-envoyes-tab" class="tab-content" style="display: none;">
                        <div class="document-list">
                            <?php if (!empty($documents_envoyes_recents)): ?>
                                <?php foreach ($documents_envoyes_recents as $document): ?>
                                    <div class="document-item">
                                        <div class="document-info">
                                            <div class="document-title"><?php echo htmlspecialchars($document['titre']); ?></div>
                                            <div class="document-meta">
                                                À: <?php echo htmlspecialchars($document['destinataire_nom'] . ' ' . $document['destinataire_prenom']); ?> - 
                                                <?php echo date('d/m/Y H:i', strtotime($document['date_upload'])); ?> - 
                                                <?php echo round($document['taille'] / 1024, 1); ?> KB
                                            </div>
                                        </div>
                                        <a href="<?= url('documents/download?id=' . $document['id']) ?>" class="btn-download">
                                            <i class="fas fa-download"></i> Re-télécharger
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-message">
                                    <i class="fas fa-file-upload"></i>
                                    <h3>Aucun document envoyé</h3>
                                    <p>Vous n'avez pas encore partagé de documents. Utilisez le formulaire ci-dessus pour partager vos fichiers.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION CALENDRIER -->
            <div id="calendrier-section" class="content-section" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">Calendrier</h1>
                    <p class="page-subtitle">Actions à effectuer et événements à venir</p>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="card-title">Événements et actions</h3>
                    </div>

                    <!-- Filtres -->
                    <div class="calendar-filters">
                        <button class="filter-btn active" onclick="filterEvents('all')">
                            <i class="fas fa-list"></i> Tout
                        </button>
                        <button class="filter-btn" onclick="filterEvents('soutenance')">
                            <i class="fas fa-graduation-cap"></i> Soutenances
                        </button>
                        <button class="filter-btn" onclick="filterEvents('action')">
                            <i class="fas fa-tasks"></i> Actions
                        </button>
                        <button class="filter-btn" onclick="filterEvents('urgent')">
                            <i class="fas fa-exclamation-triangle"></i> Urgent
                        </button>
                        <button class="filter-btn" onclick="filterEvents('retard')">
                            <i class="fas fa-clock"></i> En retard
                        </button>
                        <button class="filter-btn" onclick="filterEvents('termine')">
                            <i class="fas fa-check-circle"></i> Terminé
                        </button>
                    </div>

                    <div class="events-list">
                        <?php if (!empty($evenements_prochains)): ?>
                        <?php foreach ($evenements_prochains as $event): ?>
                                <div class="event-item event-<?php echo $event['type_evenement']; ?>" data-type="<?php echo $event['type_evenement']; ?>">
                                    <div class="event-icon">
                                        <?php
                                        $icon = 'fas fa-calendar';
                                        switch($event['type_evenement']) {
                                            case 'soutenance':
                                                $icon = 'fas fa-graduation-cap';
                                                break;
                                            case 'urgent':
                                                $icon = 'fas fa-exclamation-triangle';
                                                break;
                                            case 'retard':
                                                $icon = 'fas fa-clock';
                                                break;
                                            case 'termine':
                                                $icon = 'fas fa-check-circle';
                                                break;
                                            case 'action':
                                            default:
                                                $icon = 'fas fa-tasks';
                                                break;
                                        }
                                        ?>
                                        <i class="<?php echo $icon; ?>"></i>
                                    </div>
                                    
                                    <div class="event-content">
                                        <div class="event-date">
                                            <i class="fas fa-calendar-day"></i>
                                            <?php echo date('d/m/Y', strtotime($event['date_evenement'])); ?>
                                            
                                            <?php if (isset($event['jours_restants'])): ?>
                                                <?php if ($event['jours_restants'] < 0): ?>
                                                    <span class="event-badge badge-retard">
                                                        <i class="fas fa-exclamation"></i>
                                                        <?php echo abs($event['jours_restants']); ?> jour(s) de retard
                                                    </span>
                                                <?php elseif ($event['jours_restants'] == 0): ?>
                                                    <span class="event-badge badge-urgent">
                                                        <i class="fas fa-clock"></i>
                                                        Aujourd'hui
                                                    </span>
                                                <?php elseif ($event['jours_restants'] <= 3): ?>
                                                    <span class="event-badge badge-urgent">
                                                        <i class="fas fa-hourglass-half"></i>
                                                        Dans <?php echo $event['jours_restants']; ?> jour(s)
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if (isset($event['est_realise']) && $event['est_realise']): ?>
                                                <span class="event-badge badge-termine">
                                                    <i class="fas fa-check"></i>
                                                    Terminé
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                <div class="event-title"><?php echo htmlspecialchars($event['titre']); ?></div>
                                <div class="event-description"><?php echo htmlspecialchars($event['description']); ?></div>
                                        
                                        <?php if ($role !== 'eleve' && (isset($event['executant']) || isset($event['requisDoc']))): ?>
                                            <div class="event-meta">
                                                <?php if (isset($event['executant'])): ?>
                                                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($event['executant']); ?></span>
                                                <?php endif; ?>
                                                
                                                <?php if (isset($event['requisDoc']) && $event['requisDoc']): ?>
                                                    <span class="event-badge badge-doc-requis">
                                                        <i class="fas fa-file-alt"></i>
                                                        Document requis
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (isset($event['lienDocument']) && !empty($event['lienDocument'])): ?>
                                                    <a href="<?php echo htmlspecialchars($event['lienDocument']); ?>" class="event-badge badge-doc-requis" style="text-decoration: none;">
                                                        <i class="fas fa-download"></i>
                                                        Télécharger
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-check"></i>
                                <h3>Aucun événement programmé</h3>
                                <p>Tous vos événements et actions apparaîtront ici</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION SUIVI DES STAGES -->
            <?php if (in_array($role, ['eleve', 'enseignant', 'tuteur', 'tuteur_entreprise', 'admin'])): ?>
            <div id="suivi-stages-section" class="content-section" style="display: none;">
                <div class="page-header">
                    <h1 class="page-title">
                        <?php 
                        switch($role) {
                            case 'eleve':
                                echo 'Mes stages';
                                break;
                            case 'enseignant':
                            case 'tuteur':
                                echo 'Stages de mes étudiants';
                                break;
                            case 'tuteur_entreprise':
                                echo 'Stages dans mon entreprise';
                                break;
                            case 'admin':
                                echo 'Gestion des stages';
                                break;
                            default:
                                echo 'Suivi des stages';
                        }
                        ?>
                    </h1>
                    <p class="page-subtitle">
                        <?php 
                        switch($role) {
                            case 'eleve':
                                echo 'Consultez vos stages classés par année universitaire';
                                break;
                            case 'enseignant':
                            case 'tuteur':
                                echo 'Suivez les stages des étudiants sous votre supervision';
                                break;
                            case 'tuteur_entreprise':
                                echo 'Gérez les stagiaires accueillis dans votre entreprise';
                                break;
                            case 'admin':
                                echo 'Vue d\'ensemble de tous les stages de l\'établissement';
                                break;
                            default:
                                echo 'Informations sur les stages';
                        }
                        ?>
                    </p>
                </div>

                <?php if ($role === 'eleve'): ?>
                    <!-- Vue Étudiant : Stages classés par année -->
                    <?php 
                    $stages_par_annee = [];
                    if (!empty($mes_stages)) {
                        foreach ($mes_stages as $stage) {
                            // Déterminer l'année BUT selon le semestre (utiliser numSemestre de la table stage)
                            $annee_but = 'BUT1'; // Par défaut
                            if (isset($stage['numSemestre'])) {
                                if (in_array($stage['numSemestre'], [1, 2])) $annee_but = 'BUT1';
                                elseif (in_array($stage['numSemestre'], [3, 4])) $annee_but = 'BUT2';
                                elseif (in_array($stage['numSemestre'], [5, 6])) $annee_but = 'BUT3';
                            }
                            $stages_par_annee[$annee_but][] = $stage;
                        }
                    }
                    ?>
                    
                    <?php if (!empty($stages_par_annee)): ?>
                        <?php foreach (['BUT1', 'BUT2', 'BUT3'] as $annee): ?>
                            <?php if (isset($stages_par_annee[$annee])): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <h3 class="card-title"><?php echo $annee; ?> - <?php echo count($stages_par_annee[$annee]); ?> stage(s)</h3>
                                    </div>
                                    
                                    <div class="stages-list">
                                        <?php foreach ($stages_par_annee[$annee] as $stage): ?>
                                                                        <div class="stage-item">
                                <div class="stage-header">
                                    <h4 class="stage-title"><?php echo htmlspecialchars($stage['mission'] ?? 'Stage non défini'); ?></h4>
                                    <span class="stage-status status-<?php echo $stage['statut'] ?? 'en_cours'; ?>">
                                        <?php 
                                        switch($stage['statut'] ?? 'en_cours') {
                                            case 'termine': echo 'Terminé'; break;
                                            case 'en_cours': echo 'En cours'; break;
                                            case 'planifie': echo 'Planifié'; break;
                                            default: echo 'Non défini';
                                        }
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="stage-details">
                                    <div class="stage-info">
                                        <div class="info-group">
                                            <span class="info-label">Année universitaire :</span>
                                            <span class="info-value"><?php echo htmlspecialchars($stage['annee_libelle'] ?? '2024-2025'); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Entreprise :</span>
                                            <span class="info-value">
                                                <?php if (!empty($stage['entreprise_ville'])): ?>
                                                    <?php echo htmlspecialchars($stage['entreprise_ville']); ?>
                                                    <?php if (!empty($stage['entreprise_adresse'])): ?>
                                                        <br><small><?php echo htmlspecialchars($stage['entreprise_adresse']); ?></small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($stage['entreprise_tel'])): ?>
                                                        <br><small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($stage['entreprise_tel']); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Non définie
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Tuteur universitaire :</span>
                                            <span class="info-value">
                                                <?php if (!empty($stage['tuteur_nom'])): ?>
                                                    <?php echo htmlspecialchars(($stage['tuteur_prenom'] ?? '') . ' ' . ($stage['tuteur_nom'] ?? '')); ?>
                                                    <?php if (!empty($stage['tuteur_email'])): ?>
                                                        <br><small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($stage['tuteur_email']); ?></small>
                                                    <?php endif; ?>
                                                    <?php if (!empty($stage['tuteur_bureau'])): ?>
                                                        <br><small><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($stage['tuteur_bureau']); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Non défini
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Tuteur entreprise :</span>
                                            <span class="info-value">
                                                <?php if (!empty($stage['tuteur_ent_nom'])): ?>
                                                    <?php echo htmlspecialchars(($stage['tuteur_ent_prenom'] ?? '') . ' ' . ($stage['tuteur_ent_nom'] ?? '')); ?>
                                                    <?php if (!empty($stage['tuteur_ent_email'])): ?>
                                                        <br><small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($stage['tuteur_ent_email']); ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Non défini
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Période :</span>
                                            <span class="info-value">
                                                <?php 
                                                if ($stage['date_debut'] && $stage['date_fin']) {
                                                    echo 'Du ' . date('d/m/Y', strtotime($stage['date_debut'])) . ' au ' . date('d/m/Y', strtotime($stage['date_fin']));
                                                } else {
                                                    echo 'Dates non définies';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        <?php if (!empty($stage['date_soutenance'])): ?>
                                        <div class="info-group">
                                            <span class="info-label">Soutenance :</span>
                                            <span class="info-value">
                                                <?php echo date('d/m/Y', strtotime($stage['date_soutenance'])); ?>
                                                <?php if (!empty($stage['salle_Soutenance'])): ?>
                                                    <br><small><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($stage['salle_Soutenance']); ?></small>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card">
                            <div class="empty-state">
                                <i class="fas fa-briefcase"></i>
                                <h3>Aucun stage enregistré</h3>
                                <p>Vos stages apparaîtront ici une fois qu'ils seront créés dans le système</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php elseif (in_array($role, ['enseignant', 'tuteur'])): ?>
                    <!-- Vue Tuteur/Enseignant : Stages des étudiants supervisés -->
                    <?php if (!empty($stages_supervises)): ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="card-title">Étudiants supervisés - <?php echo count($stages_supervises); ?> stage(s)</h3>
                            </div>
                            
                            <div class="stages-list">
                                <?php foreach ($stages_supervises as $stage): ?>
                                    <div class="stage-item">
                                        <div class="stage-header">
                                            <h4 class="stage-title">
                                                <?php echo htmlspecialchars(($stage['etudiant_prenom'] ?? '') . ' ' . ($stage['etudiant_nom'] ?? '')); ?>
                                                - <?php echo htmlspecialchars($stage['mission'] ?? 'Stage non défini'); ?>
                                            </h4>
                                            <span class="stage-status status-<?php echo $stage['statut'] ?? 'en_cours'; ?>">
                                                <?php 
                                                switch($stage['statut'] ?? 'en_cours') {
                                                    case 'termine': echo 'Terminé'; break;
                                                    case 'en_cours': echo 'En cours'; break;
                                                    case 'planifie': echo 'Planifié'; break;
                                                    default: echo 'Non défini';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="stage-details">
                                            <div class="stage-info">
                                                <div class="info-group">
                                                    <span class="info-label">Entreprise :</span>
                                                    <span class="info-value">
                                                        <?php if (!empty($stage['entreprise_ville'])): ?>
                                                            <?php echo htmlspecialchars($stage['entreprise_ville']); ?>
                                                            <?php if (!empty($stage['entreprise_adresse'])): ?>
                                                                <br><small><?php echo htmlspecialchars($stage['entreprise_adresse']); ?></small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            Non définie
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Contact étudiant :</span>
                                                    <span class="info-value"><?php echo htmlspecialchars($stage['etudiant_email'] ?? ''); ?></span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Tuteur entreprise :</span>
                                                    <span class="info-value">
                                                        <?php if (!empty($stage['tuteur_ent_nom'])): ?>
                                                            <?php echo htmlspecialchars(($stage['tuteur_ent_prenom'] ?? '') . ' ' . ($stage['tuteur_ent_nom'] ?? '')); ?>
                                                            <?php if (!empty($stage['tuteur_ent_email'])): ?>
                                                                <br><small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($stage['tuteur_ent_email']); ?></small>
                                                            <?php endif; ?>
                                                            <?php if (!empty($stage['tuteur_ent_telephone'])): ?>
                                                                <br><small><i class="fas fa-phone"></i> <?php echo htmlspecialchars($stage['tuteur_ent_telephone']); ?></small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            Non défini
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Période :</span>
                                                    <span class="info-value">
                                                        <?php 
                                                        if ($stage['date_debut'] && $stage['date_fin']) {
                                                            echo date('d/m/Y', strtotime($stage['date_debut'])) . ' au ' . date('d/m/Y', strtotime($stage['date_fin']));
                                                        } else {
                                                            echo 'Dates non définies';
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <?php if (isset($stage['note_finale']) && $stage['note_finale']): ?>
                                                <div class="info-group">
                                                    <span class="info-label">Note finale :</span>
                                                    <span class="info-value note-finale"><?php echo htmlspecialchars($stage['note_finale']); ?>/20</span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="empty-state">
                                <i class="fas fa-user-graduate"></i>
                                <h3>Aucun étudiant à superviser</h3>
                                <p>Les stages des étudiants sous votre supervision apparaîtront ici</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php elseif ($role === 'tuteur_entreprise'): ?>
                    <!-- Vue Tuteur Entreprise : Stages dans son entreprise -->
                    <?php if (!empty($stages_entreprise)): ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h3 class="card-title">Stagiaires accueillis - <?php echo count($stages_entreprise); ?> stage(s)</h3>
                            </div>
                            
                            <div class="stages-list">
                                <?php foreach ($stages_entreprise as $stage): ?>
                                    <div class="stage-item">
                                        <div class="stage-header">
                                            <h4 class="stage-title">
                                                <?php echo htmlspecialchars(($stage['etudiant_prenom'] ?? '') . ' ' . ($stage['etudiant_nom'] ?? '')); ?>
                                                - <?php echo htmlspecialchars($stage['mission'] ?? 'Stage non défini'); ?>
                                            </h4>
                                            <span class="stage-status status-<?php echo $stage['statut'] ?? 'en_cours'; ?>">
                                                <?php 
                                                switch($stage['statut'] ?? 'en_cours') {
                                                    case 'termine': echo 'Terminé'; break;
                                                    case 'en_cours': echo 'En cours'; break;
                                                    case 'planifie': echo 'Planifié'; break;
                                                    default: echo 'Non défini';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="stage-details">
                                            <div class="stage-info">
                                                <div class="info-group">
                                                    <span class="info-label">Tuteur universitaire :</span>
                                                    <span class="info-value"><?php echo htmlspecialchars(($stage['tuteur_nom'] ?? '') . ' ' . ($stage['tuteur_prenom'] ?? '')); ?></span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Contact étudiant :</span>
                                                    <span class="info-value"><?php echo htmlspecialchars($stage['etudiant_email'] ?? ''); ?></span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Période :</span>
                                                    <span class="info-value">
                                                        <?php 
                                                        if ($stage['date_debut'] && $stage['date_fin']) {
                                                            echo date('d/m/Y', strtotime($stage['date_debut'])) . ' au ' . date('d/m/Y', strtotime($stage['date_fin']));
                                                        } else {
                                                            echo 'Dates non définies';
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="empty-state">
                                <i class="fas fa-building"></i>
                                <h3>Aucun stagiaire actuellement</h3>
                                <p>Les stagiaires accueillis dans votre entreprise apparaîtront ici</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php elseif ($role === 'admin'): ?>
                    <!-- Vue Admin : Tous les stages -->
                    <?php if (!empty($tous_stages)): ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h3 class="card-title">Tous les stages - <?php echo count($tous_stages); ?> stage(s)</h3>
                            </div>
                            
                            <!-- Filtres pour l'admin -->
                            <div class="stage-filters">
                                <button class="filter-btn active" onclick="filterStages('all')">
                                    <i class="fas fa-list"></i> Tous
                                </button>
                                <button class="filter-btn" onclick="filterStages('en_cours')">
                                    <i class="fas fa-play"></i> En cours
                                </button>
                                <button class="filter-btn" onclick="filterStages('termine')">
                                    <i class="fas fa-check"></i> Terminés
                                </button>
                                <button class="filter-btn" onclick="filterStages('planifie')">
                                    <i class="fas fa-calendar"></i> Planifiés
                                </button>
                            </div>
                            
                            <div class="stages-list">
                                <?php foreach ($tous_stages as $stage): ?>
                                    <div class="stage-item" data-status="<?php echo $stage['statut'] ?? 'en_cours'; ?>">
                                        <div class="stage-header">
                                            <h4 class="stage-title">
                                                <?php echo htmlspecialchars(($stage['etudiant_prenom'] ?? '') . ' ' . ($stage['etudiant_nom'] ?? '')); ?>
                                                - <?php echo htmlspecialchars($stage['mission'] ?? 'Stage non défini'); ?>
                                            </h4>
                                            <span class="stage-status status-<?php echo $stage['statut'] ?? 'en_cours'; ?>">
                                                <?php 
                                                switch($stage['statut'] ?? 'en_cours') {
                                                    case 'termine': echo 'Terminé'; break;
                                                    case 'en_cours': echo 'En cours'; break;
                                                    case 'planifie': echo 'Planifié'; break;
                                                    default: echo 'Non défini';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="stage-details">
                                            <div class="stage-info">
                                                <div class="info-group">
                                                    <span class="info-label">Année universitaire :</span>
                                                    <span class="info-value"><?php echo htmlspecialchars($stage['annee_libelle'] ?? '2024-2025'); ?></span>
                                                </div>
                                                <div class="info-group">
                                                    <span class="info-label">Entreprise :</span>
                                                    <span class="info-value">
                                                        <?php if (!empty($stage['entreprise_ville'])): ?>
                                                            <?php echo htmlspecialchars($stage['entreprise_ville']); ?>
                                                            <?php if (!empty($stage['entreprise_adresse'])): ?>
                                                                <br><small><?php echo htmlspecialchars($stage['entreprise_adresse']); ?></small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            Non définie
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <?php if (isset($stage['note_finale']) && $stage['note_finale']): ?>
                                                <div class="info-group">
                                                    <span class="info-label">Note finale :</span>
                                                    <span class="info-value note-finale"><?php echo htmlspecialchars($stage['note_finale']); ?>/20</span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="empty-state">
                                <i class="fas fa-briefcase"></i>
                                <h3>Aucun stage enregistré</h3>
                                <p>Les stages des étudiants apparaîtront ici une fois créés</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
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

        // Fonction de filtrage des événements du calendrier
        function filterEvents(filterType) {
            const eventItems = document.querySelectorAll('.event-item');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Afficher/masquer les événements selon le filtre
            eventItems.forEach(item => {
                const eventType = item.getAttribute('data-type');
                
                if (filterType === 'all') {
                    item.style.display = 'flex';
                } else if (filterType === eventType) {
                    item.style.display = 'flex';
                } else if (filterType === 'action' && ['action', 'urgent', 'retard', 'termine'].includes(eventType)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Vérifier s'il y a des événements visibles
            const visibleEvents = document.querySelectorAll('.event-item[style*="flex"]');
            const emptyState = document.querySelector('.empty-state');
            const eventsList = document.querySelector('.events-list');
            
            if (visibleEvents.length === 0 && !emptyState) {
                // Créer un message temporaire si aucun événement n'est visible
                const tempEmpty = document.createElement('div');
                tempEmpty.className = 'empty-state temp-empty';
                tempEmpty.innerHTML = `
                    <i class="fas fa-filter"></i>
                    <h3>Aucun événement de ce type</h3>
                    <p>Aucun événement ne correspond au filtre sélectionné</p>
                `;
                eventsList.appendChild(tempEmpty);
            } else if (visibleEvents.length > 0) {
                // Supprimer le message temporaire s'il existe
                const tempEmpty = document.querySelector('.temp-empty');
                if (tempEmpty) {
                    tempEmpty.remove();
                }
            }
        }

        // Fonction de filtrage des stages pour l'admin
        function filterStages(filterType) {
            const stageItems = document.querySelectorAll('.stage-item');
            const filterButtons = document.querySelectorAll('.stage-filters .filter-btn');
            
            // Mettre à jour les boutons actifs
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Afficher/masquer les stages selon le filtre
            stageItems.forEach(item => {
                const stageStatus = item.getAttribute('data-status');
                
                if (filterType === 'all') {
                    item.style.display = 'block';
                } else if (filterType === stageStatus) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Vérifier s'il y a des stages visibles
            const visibleStages = document.querySelectorAll('.stage-item[style*="block"], .stage-item:not([style])');
            const stagesList = document.querySelector('.stages-list');
            
            if (visibleStages.length === 0 && stagesList) {
                // Créer un message temporaire si aucun stage n'est visible
                const existingEmpty = stagesList.querySelector('.temp-empty');
                if (!existingEmpty) {
                    const tempEmpty = document.createElement('div');
                    tempEmpty.className = 'empty-state temp-empty';
                    tempEmpty.innerHTML = `
                        <i class="fas fa-filter"></i>
                        <h3>Aucun stage de ce type</h3>
                        <p>Aucun stage ne correspond au filtre sélectionné</p>
                    `;
                    stagesList.appendChild(tempEmpty);
                }
            } else {
                // Supprimer le message temporaire s'il existe
                const tempEmpty = stagesList.querySelector('.temp-empty');
                if (tempEmpty) {
                    tempEmpty.remove();
                }
            }
        }

        // Gérer le chargement initial et les changements de hash
        function handleHashChange() {
            const hash = window.location.hash.substring(1); // Enlever le #
            if (hash && ['dashboard', 'messagerie', 'documents', 'calendrier', 'suivi-stages'].includes(hash)) {
                showSection(hash);
            } else {
                showSection('dashboard'); // Section par défaut
            }
        }

        // Animation d'entrée pour les événements
        function animateEvents() {
            const eventItems = document.querySelectorAll('.event-item');
            eventItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Écouter les changements de hash
        window.addEventListener('hashchange', handleHashChange);

        // Initialiser au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            handleHashChange();
            
            // Animer les événements après un court délai
            setTimeout(animateEvents, 500);
            
            // Ajouter des événements de hover pour les cartes
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                });
            });
        });

        // Afficher les messages de succès/erreur s'il y en a
        <?php if (isset($_SESSION['success_message'])): ?>
            // Créer une notification toast au lieu d'une alerte
            showToast('<?php echo addslashes($_SESSION['success_message']); ?>', 'success');
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            showToast('<?php echo addslashes($_SESSION['error_message']); ?>', 'error');
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        // Fonctions pour gérer les onglets de messagerie
        function showMessageTab(tabType) {
            const messageSection = document.querySelector('.messagerie-section');
            if (!messageSection) return;
            
            const tabButtons = messageSection.querySelectorAll('.tabs-navigation .tab-btn');
            const tabContents = messageSection.querySelectorAll('.tab-content');
            
            // Supprimer la classe active de tous les boutons et contenus de la section messagerie
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            
            // Ajouter la classe active au bouton cliqué
            event.target.classList.add('active');
            
            // Afficher le contenu approprié
            const targetTab = document.getElementById(`messages-${tabType}-tab`);
            if (targetTab) {
                targetTab.classList.add('active');
                targetTab.style.display = 'block';
            }
        }

        // Fonctions pour gérer les onglets de documents
        function showDocumentTab(tabType) {
            const documentSection = document.querySelector('.documents-section');
            if (!documentSection) return;
            
            const tabButtons = documentSection.querySelectorAll('.tabs-navigation .tab-btn');
            const tabContents = documentSection.querySelectorAll('.tab-content');
            
            // Supprimer la classe active de tous les boutons et contenus de la section documents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });
            
            // Ajouter la classe active au bouton cliqué
            event.target.classList.add('active');
            
            // Afficher le contenu approprié
            const targetTab = document.getElementById(`documents-${tabType}-tab`);
            if (targetTab) {
                targetTab.classList.add('active');
                targetTab.style.display = 'block';
            }
        }

        // Fonction pour ouvrir le modal de réponse
        function openReplyModal(recipientEmail, recipientName) {
            // Aller à la section messagerie et pré-remplir le formulaire
            showSection('messagerie');
            
            // Attendre que la section soit affichée puis pré-remplir
            setTimeout(() => {
                const emailSelect = document.querySelector('select[name="email_destinataire"]');
                const messageTextarea = document.querySelector('textarea[name="contenu"]');
                
                if (emailSelect && messageTextarea) {
                    emailSelect.value = recipientEmail;
                    messageTextarea.focus();
                    messageTextarea.placeholder = `Répondre à ${recipientName}...`;
                }
            }, 100);
        }

        // Fonction pour afficher des notifications toast
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#152d65'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                max-width: 300px;
                font-size: 14px;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);
            
            // Animation de sortie et suppression
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html> 