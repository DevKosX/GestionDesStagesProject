document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('header nav ul li a');
    const currentPath = window.location.pathname;

    // Marque active le lien actuel
    navLinks.forEach(link => {
         if (currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });

    navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            navLinks.forEach(otherLink => otherLink.classList.remove('active'));
            this.classList.add('active');

            const linkText = this.textContent.trim();

             if (linkText === 'Tableau de bord') {
                window.location.href = '../views/tableaudebord.php';
            } else if (linkText === 'Gestion des stages') {
                window.location.href = '../views/gestiondestages.php';
            } else if (linkText === 'Accueil') {
                window.location.href = '../views/accueilConnect.php';
            }
        });
    });
    const profileLogo = document.getElementById('profile-logo');
    const profileMenu = document.getElementById('profile-menu');
    const logoutBtn = document.getElementById('logout-btn');

     // Afficher/masquer le menu de profil
    profileLogo?.addEventListener('click', function () {
        const profile = this.parentElement;
        profile.classList.toggle('active');
    });

    const logoutButton = document.getElementById('logout-btn');

     // Gérer la déconnexion
    logoutBtn?.addEventListener('click', function (event) {
        event.preventDefault();
        if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
            fetch('/GestionDesStagesProject/AppStage/views/logout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '/GestionDesStagesProject/AppStage/views/connexion.php';
                    } else {
                        alert("Erreur lors de la déconnexion.");
                    }
                })
                .catch(error => {
                    console.error("Erreur API :", error);
                    alert("Une erreur est survenue.");
                });
        }
    });
});