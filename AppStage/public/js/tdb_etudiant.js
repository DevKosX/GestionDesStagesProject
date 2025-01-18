document.addEventListener('DOMContentLoaded', function () {
    // Gestion des notifications
    const notificationDiv = document.getElementById('notifications');
    const notificationList = document.getElementById('notification-list');

    function loadNotifications() {
        console.log('Chargement des notifications...'); // Debug
        fetch('/GestionDesStagesProject/AppStage/api/get_notifications.php')
            .then(response => {
                console.log('Réponse API reçue :', response); // Debug
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des notifications.');
                }
                return response.json();
            })
            .then(data => {
                console.log('Données des notifications :', data); // Debug
                if (data.error) {
                    console.error('Erreur dans les données des notifications :', data.error);
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
                    console.log('Aucune notification trouvée.');
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

});
