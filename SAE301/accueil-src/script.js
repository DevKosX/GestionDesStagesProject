document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('header nav ul li a');

    navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien

            const linkText = this.textContent.trim();

            if (linkText === 'Tableau de bord') {
                window.location.href = 'tableaudebord.html';
            } else if (linkText === 'Gestion des stages') {
                window.location.href = 'gestiondestages.html';
            }
        });
    });
}); // Début du js pour le dernier commit css 

