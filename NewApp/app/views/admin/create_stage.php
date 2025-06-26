<?php
$title = 'Nouveau Stage';
$current_page = 'create-stage';
$additional_styles = '
.form-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    max-width: 1000px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #f9f9f9;
}

.form-section h3 {
    margin-top: 0;
    color: #2c3e50;
    border-bottom: 2px solid #667eea;
    padding-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
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
';

$additional_scripts = '
document.addEventListener("DOMContentLoaded", function() {
    const dateDebut = document.getElementById("date_debut");
    const dateFin = document.getElementById("date_fin");
    const dateSoutenance = document.getElementById("date_soutenance");
    
    function validateDates() {
        if (dateDebut.value && dateFin.value) {
            if (new Date(dateFin.value) <= new Date(dateDebut.value)) {
                dateFin.setCustomValidity("La date de fin doit être après la date de début");
            } else {
                dateFin.setCustomValidity("");
            }
        }
        
        if (dateFin.value && dateSoutenance.value) {
            if (new Date(dateSoutenance.value) < new Date(dateFin.value)) {
                dateSoutenance.setCustomValidity("La soutenance doit avoir lieu après la fin du stage");
            } else {
                dateSoutenance.setCustomValidity("");
            }
        }
    }
    
    if (dateDebut) dateDebut.addEventListener("change", validateDates);
    if (dateFin) dateFin.addEventListener("change", validateDates);
    if (dateSoutenance) dateSoutenance.addEventListener("change", validateDates);
    
    // Gérer le toast de succès
    const successToast = document.querySelector(".toast-success");
    if (successToast) {
        // Effacer le formulaire après succès
        resetStageForm();
        
        // Masquer le toast après 4 secondes
        setTimeout(function() {
            if (successToast) {
                successToast.style.display = "none";
            }
        }, 4000);
    }
});

function resetStageForm() {
    document.getElementById("Id_Annee").value = "";
    document.getElementById("Id_Etudiant").value = "";
    document.getElementById("Id_Enseignant").value = "";
    document.getElementById("Id_TuteurEntreprise").value = "";
    document.getElementById("date_debut").value = "";
    document.getElementById("date_fin").value = "";
    document.getElementById("mission").value = "";
    document.getElementById("date_soutenance").value = "";
    document.getElementById("salle_Soutenance").value = "";
}
';

// Inclure le header
include __DIR__ . '/partials/header.php';

// Inclure la sidebar
include __DIR__ . '/partials/sidebar.php';
?>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-briefcase"></i> Nouveau Stage
                </h1>
                <p class="page-subtitle">Créer et attribuer un stage à un étudiant</p>
            </div>

            <!-- MESSAGES -->
            <?php include __DIR__ . '/partials/messages.php'; ?>

            <div class="form-container">
                <form method="POST" action="<?= url('admin/create-stage') ?>">
                    <!-- Informations générales -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Id_Annee">Année universitaire <span class="required">*</span></label>
                                <select id="Id_Annee" name="Id_Annee" required>
                                    <option value="">Sélectionner une année</option>
                                    <?php if (!empty($annees)): ?>
                                        <?php foreach ($annees as $annee): ?>
                                            <option value="<?= $annee['Id_Annee'] ?>" <?= (isset($form_data['Id_Annee']) && $form_data['Id_Annee'] == $annee['Id_Annee']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($annee['libelle']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Id_Etudiant">Étudiant <span class="required">*</span></label>
                                <select id="Id_Etudiant" name="Id_Etudiant" required>
                                    <option value="">Sélectionner un étudiant</option>
                                    <?php if (!empty($etudiants)): ?>
                                        <?php foreach ($etudiants as $etudiant): ?>
                                            <option value="<?= $etudiant['Id_Etudiant'] ?>" <?= (isset($form_data['Id_Etudiant']) && $form_data['Id_Etudiant'] == $etudiant['Id_Etudiant']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Encadrement -->
                    <div class="form-section">
                        <h3><i class="fas fa-users"></i> Encadrement</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="Id_Enseignant">Tuteur pédagogique <span class="required">*</span></label>
                                <select id="Id_Enseignant" name="Id_Enseignant" required>
                                    <option value="">Sélectionner un enseignant</option>
                                    <?php if (!empty($enseignants)): ?>
                                        <?php foreach ($enseignants as $enseignant): ?>
                                            <option value="<?= $enseignant['Id_Enseignant'] ?>" <?= (isset($form_data['Id_Enseignant']) && $form_data['Id_Enseignant'] == $enseignant['Id_Enseignant']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Id_TuteurEntreprise">Tuteur entreprise <span class="required">*</span></label>
                                <select id="Id_TuteurEntreprise" name="Id_TuteurEntreprise" required>
                                    <option value="">Sélectionner un tuteur</option>
                                    <?php if (!empty($tuteurs_entreprise)): ?>
                                        <?php foreach ($tuteurs_entreprise as $tuteur): ?>
                                            <option value="<?= $tuteur['Id_TuteurEntreprise'] ?>" <?= (isset($form_data['Id_TuteurEntreprise']) && $form_data['Id_TuteurEntreprise'] == $tuteur['Id_TuteurEntreprise']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tuteur['prenom'] . ' ' . $tuteur['nom'] . ' (' . $tuteur['ville'] . ')') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dates et mission -->
                    <div class="form-section">
                        <h3><i class="fas fa-calendar"></i> Dates et mission</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_debut">Date de début <span class="required">*</span></label>
                                <input type="date" id="date_debut" name="date_debut" required 
                                       value="<?= htmlspecialchars($form_data['date_debut'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="date_fin">Date de fin <span class="required">*</span></label>
                                <input type="date" id="date_fin" name="date_fin" required 
                                       value="<?= htmlspecialchars($form_data['date_fin'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mission">Mission du stage <span class="required">*</span></label>
                            <textarea id="mission" name="mission" rows="4" required 
                                      placeholder="Décrivez les objectifs et tâches du stage..."><?= htmlspecialchars($form_data['mission'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Soutenance -->
                    <div class="form-section">
                        <h3><i class="fas fa-presentation"></i> Soutenance</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_soutenance">Date de soutenance</label>
                                <input type="date" id="date_soutenance" name="date_soutenance" 
                                       value="<?= htmlspecialchars($form_data['date_soutenance'] ?? '') ?>">
                                <div class="help-text">Laisser vide si pas encore planifiée</div>
                            </div>
                            <div class="form-group">
                                <label for="salle_Soutenance">Salle</label>
                                <input type="text" id="salle_Soutenance" name="salle_Soutenance" 
                                       value="<?= htmlspecialchars($form_data['salle_Soutenance'] ?? '') ?>"
                                       placeholder="Ex: Amphi A, Salle 101...">
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div style="margin-top: 30px; text-align: right;">
                        <a href="<?= url('admin/dashboard') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Créer le stage
                        </button>
                    </div>
                </form>
            </div>
        </div>

<?php include __DIR__ . '/partials/footer.php'; ?> 