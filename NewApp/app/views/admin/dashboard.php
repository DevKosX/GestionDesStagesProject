<?php
$title = 'Gestion des utilisateurs';
$current_page = 'admin-dashboard';
$additional_styles = '
.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    color: #333;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.stat-card.clickable:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

.stat-card.success {
    background: #e6f7ff;
    border-color: #40a9ff;
}

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    margin: 10px 0;
}

.stat-label {
    font-size: 0.9em;
    opacity: 0.9;
}

.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    border: 1px solid;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}
';

$additional_scripts = '
function showRoleUsers(role) {
    window.location.href = "' . url("admin/users-by-role") . '?role=" + role;
}

// Ajouter des effets de survol pour les cartes cliquables
document.addEventListener("DOMContentLoaded", function() {
    const clickableCards = document.querySelectorAll(".stat-card.clickable");
    
    clickableCards.forEach(card => {
        card.addEventListener("mouseenter", function() {
            this.style.transform = "translateY(-5px)";
            this.style.boxShadow = "0 8px 25px rgba(0,0,0,0.15)";
        });
        
        card.addEventListener("mouseleave", function() {
            this.style.transform = "translateY(0)";
            this.style.boxShadow = "0 4px 15px rgba(0,0,0,0.1)";
        });
    });
});
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
                    <i class="fas fa-users"></i> Gestion des utilisateurs
                </h1>
                <p class="page-subtitle">Administration des utilisateurs et gestion des stages</p>
            </div>

            <!-- MESSAGES -->
            <?php include __DIR__ . '/partials/messages.php'; ?>

            <!-- STATISTIQUES ADMIN -->
            <div class="admin-stats">
                <div class="stat-card">
                    <i class="fas fa-users" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <div class="stat-number"><?= $stats['total_users'] ?? 0 ?></div>
                    <div class="stat-label">Utilisateurs Total</div>
                </div>
                
                <div class="stat-card success clickable" onclick="showRoleUsers('etudiants')" style="cursor: pointer;">
                    <i class="fas fa-user-graduate" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <div class="stat-number"><?= $stats['roles']['etudiants'] ?? 0 ?></div>
                    <div class="stat-label">Étudiants</div>
                </div>
                
                <div class="stat-card clickable" onclick="showRoleUsers('enseignants')" style="cursor: pointer;">
                    <i class="fas fa-chalkboard-teacher" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <div class="stat-number"><?= $stats['roles']['enseignants'] ?? 0 ?></div>
                    <div class="stat-label">Enseignants</div>
                </div>
                
                <div class="stat-card info clickable" onclick="showRoleUsers('tuteur_entreprise')" style="cursor: pointer;">
                    <i class="fas fa-building" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <div class="stat-number"><?= $stats['roles']['tuteur_entreprise'] ?? 0 ?></div>
                    <div class="stat-label">Tuteurs Entreprise</div>
                </div>
                
                <div class="stat-card warning clickable" onclick="showRoleUsers('administrateurs')" style="cursor: pointer;">
                    <i class="fas fa-user-shield" style="font-size: 2em; margin-bottom: 10px;"></i>
                    <div class="stat-number"><?= $stats['roles']['administrateurs'] ?? 0 ?></div>
                    <div class="stat-label">Administrateurs</div>
                </div>
            </div>

<?php
// Inclure le footer
include __DIR__ . '/partials/footer.php';
?> 