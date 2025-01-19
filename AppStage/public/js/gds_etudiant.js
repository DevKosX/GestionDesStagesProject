document.addEventListener('DOMContentLoaded', function () {
    const contentArea = document.getElementById('content-area'); // Zone où les informations sont affichées


        fetch('../api/get_stage.php') // Remplacez par le chemin correct de l'API
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des données.');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    contentArea.innerHTML = `<p>${data.error}</p>`;
                } else {
                    // Afficher les informations du stage dans le contentArea
                    contentArea.innerHTML = `
                         <h2>Tuteur pédagogique : ${data.prenomTuteurPedagogique} ${data.nomTuteurPedagogique}</h2>
                         <br>
                        <h3>Coordonnées de l'entreprise :</h3>
                         <ul>
                              
                                <li><strong>Adresse :</strong> ${data.adresse}, ${data.ville} </li>
                                <li><strong>Téléphone :</strong> ${data.telEntreprise}</li>
                                 <li><strong>Nom du tuteur en entreprise :</strong> ${data.NomTuteurEntreprise}</li>
                                 <li><strong>Téléphone du Tuteur Entreprise :</strong> ${data.TelTuteurEntreprise}</li>
                                 <li><strong>Email :</strong> ${data.EmailTuteurEntreprise}</li>
                        </ul>
                    <br>
                        <h3>Historique de stage :</h3>
                         <ul>
                                <li><strong>Éudiant :</strong> ${data.prenomEtudiant} ${data.nomEtudiant}</li>
                                <li><strong>Période :</strong> ${data.date_debut} – ${data.date_fin}</li>
                                <li><strong>Missions :</strong> ${data.mission}</li>
                                 <br>
                             <li><strong>Avancement :</strong></li>
                                <ul>
                                <li><strong>Soutenance :</strong> Prévue le ${data.date_soutenance} en ${data.salle_Soutenance}</li>
                             </ul>
                         </ul>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données :', error);
                contentArea.innerHTML = `<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>`;
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