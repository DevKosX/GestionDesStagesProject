document.addEventListener('DOMContentLoaded', function () {
    const contentArea = document.getElementById('content-area'); // Zone où les informations sont affichées
    const monStageBtn = document.getElementById('mon-stage-btn'); // Bouton pour afficher "Votre stage"
    const deposerRapportBtn = document.getElementById('deposer-rapport-btn'); // Bouton pour afficher "Déposer le rapport"

    // Fonction pour afficher les informations du stage
    monStageBtn.addEventListener('click', function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien
        fetch('../views/api_stage.php') // Remplacez par le chemin correct de l'API
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
                        <h2>Votre stage</h2>
                        <p><strong>Mission :</strong> ${data.mission}</p>
                        <p><strong>Date de début :</strong> ${data.date_debut}</p>
                        <p><strong>Date de fin :</strong> ${data.date_fin}</p>
                        <p><strong>Adresse :</strong> ${data.adresse}, ${data.ville}</p>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données :', error);
                contentArea.innerHTML = `<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>`;
            });
    });

    // Fonction pour afficher le formulaire de dépôt de rapport
    deposerRapportBtn.addEventListener('click', function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien
        contentArea.innerHTML = `
            <h2>Déposer votre rapport</h2>
            <form action="../views/deposer_rapport.php" method="POST" enctype="multipart/form-data">
                <label for="rapport">Télécharger votre rapport :</label>
                <input type="file" id="rapport" name="rapport" required>
                <br><br>
                <button type="submit">Déposer</button>
            </form>
        `;
    });
});
