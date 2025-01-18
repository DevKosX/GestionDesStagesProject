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
                              <li><strong>Nom de l'entreprise :</strong> Thales</li>
                                <li><strong>Adresse :</strong> ${data.adresse}, ${data.ville} </li>
                                 <li><strong>Télépone :</strong> +33 1 23 45 67 89</li>
                                <li><strong>Email :</strong> contact@techninnov-solutions.com</li>
                                 <li><strong>Nom du tuteur en entreprise :</strong> ${data.NomTuteurEntreprise}</li>
                                <li><strong>Poste :</strong> Responsable des Projets Numériques</li>
                                 <li><strong>Téléphone :</strong> ${data.TelTuteurEntreprise}</li>
                                 <li><strong>Email :</strong> ${data.EmailTuteurEntreprise}</li>
                        </ul>
                    <br>
                        <h3>Historique de stage :</h3>
                         <ul>
                                <li><strong>Éudiant :</strong> ${data.prenomEtudiant} ${data.nomEtudiant}</li>
                                <li><strong>Entreprise :</strong> TechInnov Solutions</li>
                                <li><strong>Période :</strong> ${data.date_debut} – ${data.date_fin}</li>
                                <li><strong>Missions :</strong> ${data.mission}</li>
                                 <br>
                             <li><strong>Avancement :</strong></li>
                                <ul>
                                  <li>  <strong>Compte rendu d'installation :</strong> Réalisé le 05 Mars 2023.</li>
                                    <li> <strong>Prise de contact avec l'entreprise :</strong> Effectuée le 08 Mars 2023.</li>
                                    <li> <strong>Entretien de mi-parcours :</strong> Réalisé en visioconférence le 20 Avril 2023.</li>
                                  <li><strong>Rapport de stage :</strong> Déposé le 10 Juin 2023.</li>
                                <li><strong>Soutenance :</strong> Prévue le ${data.date_soutenance}</li>
                                  <li><strong>Évaluation finale :</strong> Très bon (17/20), bonne intégration dans l'entreprise.</li>
                             </ul>
                         </ul>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données :', error);
                contentArea.innerHTML = `<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>`;
            });
});