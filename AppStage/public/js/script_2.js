document.addEventListener('DOMContentLoaded', function () {
    // Gestion des notifications
    const notificationDiv = document.getElementById('notifications');
    const notificationList = document.getElementById('notification-list');

    function loadNotifications() {
        fetch('/GestionDesStagesProject/AppStage/api/get_notifications.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des notifications.');
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Debugging
                if (data.error) {
                    console.error(data.error);
                } else if (data.length > 0) {
                    // Afficher les notifications
                    notificationDiv.style.display = 'block';
                    notificationList.innerHTML = ''; // Efface les anciennes notifications
                    data.forEach(notification => {
                        const li = document.createElement('li');
                        li.textContent = `${notification.action} à réaliser avant le ${notification.date_echeance}`;
                        notificationList.appendChild(li);
                    });
                } else {
                    // Cacher les notifications s'il n'y en a pas
                    notificationDiv.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des notifications :', error);
            });
    }

    // Charger les notifications lors du chargement de la page
    loadNotifications();

    // Rafraîchir les notifications toutes les 30 secondes
    setInterval(loadNotifications, 30000);

    // Gestion des liens de navigation
    const navLinks = document.querySelectorAll('header nav ul li a');
    const currentPath = window.location.pathname;

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

    // Gestion du menu profil
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
                    window.location.href = '/GestionDesStagesProject/AppStage/index.php';
                } else {
                    alert("Erreur lors de la déconnexion.");
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert("Erreur lors de la déconnexion.");
            });
        }
    });
});