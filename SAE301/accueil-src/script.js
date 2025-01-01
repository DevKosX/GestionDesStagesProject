document.addEventListener('DOMContentLoaded', function () {
    // Début du JS pour le dernier commit CSS

    const navLinks = document.querySelectorAll('header nav ul li a');

    navLinks.forEach(link => {

      });

        navLinks.forEach(link => {
           link.addEventListener('click', function (event) {
               event.preventDefault();
              // Effet de survol au click
                navLinks.forEach(otherLink => otherLink.classList.remove('active'));
                 this.classList.add('active');

              const linkText = this.textContent.trim();

               if (linkText === 'Tableau de bord') {
                   window.location.href = 'tableaudebord.php';
               } else if (linkText === 'Gestion des stages') {
                   window.location.href = 'gestiondestages.php';
               }
           });
    });

    const profileLogo = document.getElementById('profile-logo');
    const profileMenu = document.getElementById('profile-menu');
    const logoutBtn = document.getElementById('logout-btn');

    // Afficher/masquer le menu de profil
    profileLogo.addEventListener('click', function () {
        const profile = this.parentElement;
        profile.classList.toggle('active');
    });

    // Cacher le menu si on clique en dehors
    document.addEventListener('click', function (event) {
        const profile = document.querySelector('.profile');
        if (!profile.contains(event.target)) {
            profile.classList.remove('active');
        }
    });

    // Gérer la déconnexion
    logoutBtn.addEventListener('click', function (event) {
        event.preventDefault();
        if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
            // Appel API de déconnexion
            fetch('api/logout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = 'connexion.php'; // Redirection après déconnexion
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
