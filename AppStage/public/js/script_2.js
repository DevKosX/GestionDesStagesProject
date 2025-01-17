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
                window.location.href = '../views/gestiondesstages.php';
            } else if (linkText === 'Accueil') {
                window.location.href = '../views/accueilConnect.php';
            }
        });
    });
    const profileLogo = document.getElementById('profile-logo');
    const profileMenu = document.getElementById('profile-menu');

     if (profileLogo && profileMenu) {
         profileLogo.addEventListener('click', function () {
            profileMenu.classList.toggle('show');
         });

         document.addEventListener('click', function(event) {
            if (!profileLogo.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });
    }
    const logoutButton = document.getElementById('logout-btn');

     if (logoutButton){
         logoutButton.addEventListener('click', function (event) {
            event.preventDefault();
             if (confirm("Voulez-vous vraiment vous déconnecter ?")) {
                 window.location.href = '../logout.php';
            }
         });
     }
});