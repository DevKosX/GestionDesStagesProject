document.addEventListener('DOMContentLoaded', function () {
    const contentArea = document.getElementById('content-area'); // Zone où les informations sont affichées
    const studentSelect = document.getElementById('student-select'); // Menu déroulant pour sélectionner les élèves

    // Charger la liste des élèves
    fetch('../api/get_students.php') // Remplacez par le chemin correct de l'API
        .then(response => {
            console.log('API get_students response status:', response.status);
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des données.');
            }
            return response.json();
        })
        .then(data => {
            console.log('API get_students response data:', data);
            if (data.error) {
                contentArea.innerHTML = `<p>${data.error}</p>`;
            } else {
                // Remplir le menu déroulant avec les élèves
                data.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.Id;
                    option.textContent = `${student.prenom} ${student.nom}`;
                    studentSelect.appendChild(option);
                });
            }
        })
        /*.catch(error => {
            console.error('Erreur lors du chargement des données :', error);
            contentArea.innerHTML = `<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>`;
        });*/

    // Charger les informations de stage de l'élève sélectionné
    studentSelect.addEventListener('change', function () {
        const studentId = this.value;
        console.log(`Selected student ID: ${studentId}`);

        // Vérifiez si un étudiant est sélectionné
        if (!studentId) {
            contentArea.innerHTML = `<p>Veuillez sélectionner un étudiant pour voir les informations de stage.</p>`;
            return;
        }

        fetch(`../api/get_stage.php?Id_Etudiant=${studentId}`) // Remplacez par le chemin correct de l'API
            .then(response => {
                console.log('API response status:', response.status);
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des données.');
                }
                return response.json();
            })
            .then(data => {
                console.log('API response data:', data);
                if (data.error) {
                    contentArea.innerHTML = `<p>${data.error}</p>`;
                } else {
                    // Afficher les informations du stage dans le contentArea
                    contentArea.innerHTML = `
                        <h2>Tuteur pédagogique : ${data.prenomTuteurPedagogique} ${data.nomTuteurPedagogique}</h2>
                        <br>
                        <h3>Coordonnées de l'entreprise :</h3>
                        <ul>
                            <li><strong>Adresse :</strong> ${data.adresse}, ${data.ville}</li>
                            <li><strong>Téléphone :</strong> ${data.telEntreprise}</li>
                            <li><strong>Nom du tuteur en entreprise :</strong> ${data.NomTuteurEntreprise}</li>
                            <li><strong>Téléphone du Tuteur Entreprise :</strong> ${data.TelTuteurEntreprise}</li>
                            <li><strong>Email :</strong> ${data.EmailTuteurEntreprise}</li>
                        </ul>
                        <br>
                        <h3>Historique de stage :</h3>
                        <ul>
                            <li><strong>Étudiant :</strong> ${data.prenomEtudiant} ${data.nomEtudiant}</li>
                            <li><strong>Période :</strong> ${data.date_debut} - ${data.date_fin}</li>
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
                    window.location.href = '/GestionDesStagesProject/AppStage/views/connexion.php';
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