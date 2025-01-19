document.addEventListener('DOMContentLoaded', function () {
    const contentArea = document.getElementById('content-area'); // Zone où les informations sont affichées

        fetch('../api/get_stage_enseignant.php') // Remplacez par le chemin correct de l'API
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
                     const formatDate = (date) => {
                           if(date){
                                const dateObject = new Date(date);
                              const day = String(dateObject.getDate()).padStart(2, '0');
                            const month = String(dateObject.getMonth() + 1).padStart(2, '0');
                            const year = dateObject.getFullYear();
                                return  `${day}/${month}/${year}`;
                           }
                           return "Non définie";
                       }
                    let content = "";
                   data.forEach(stage => {
                       content +=  `
    
                         <br>
                        <h3>Coordonnées de l'entreprise :</h3>
                         <ul>
                                
                                <li><strong>Adresse :</strong> ${stage.adresse}, ${stage.ville} </li>
                                 <li><strong>Téléphone :</strong> ${stage.telEntreprise}</li>
                                <li><strong>Email :</strong> contact@techninnov-solutions.com</li>
                                 <li><strong>Nom du tuteur en entreprise :</strong> ${stage.NomTuteurEntreprise}</li>
                                <li><strong>Poste :</strong> Responsable des Projets Numériques</li>
                                 <li><strong>Téléphone :</strong> ${stage.TelTuteurEntreprise}</li>
                                 <li><strong>Email :</strong> ${stage.EmailTuteurEntreprise}</li>
                        </ul>
                    <br>
                        <h3>Historique de stage :</h3>
                         <ul>
                                <li><strong>Éudiant :</strong> ${stage.prenomEtudiant} ${stage.nomEtudiant}</li>
                                <li><strong>Période :</strong> ${formatDate(stage.date_debut)} – ${formatDate(stage.date_fin)} </li>
                                <li><strong>Missions :</strong> ${stage.mission}</li>
                                 <br>
                             <li><strong>Avancement :</strong></li>
                                <ul>
                                 ${stage.actions.map(action => `<li>  <strong>${action.libelle} :</strong> ${action.date_realisation == null ? 'Non réalisé' : 'Réalisé le ' + formatDate(action.date_realisation)  }.</li>`).join('')}
                                  <li><strong>Soutenance :</strong> Prévue le ${formatDate(stage.date_soutenance)} en ${stage.salle_Soutenance}</li>
                                </ul>
                         </ul><hr>`;
                       });
                         contentArea.innerHTML = content;
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