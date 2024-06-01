const registerBtn = document.getElementById("register"); // Récupérer le bouton d'inscription
const loginBtn = document.getElementById("login"); // Récupérer le bouton de connexion
const container = document.querySelector(".container"); // Sélectionner le conteneur

// Ajouter un gestionnaire d'événement au clic sur le bouton d'inscription
registerBtn.onclick = function () {
    container.classList.add("active"); // Ajouter la classe "active" au conteneur
};

// Ajouter un gestionnaire d'événement au clic sur le bouton de connexion
loginBtn.onclick = function () {
    container.classList.remove("active"); // Supprimer la classe "active" du conteneur
};
