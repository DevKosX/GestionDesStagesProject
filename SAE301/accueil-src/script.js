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
});