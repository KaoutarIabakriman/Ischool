// Sélection des boutons d'inscription et de connexion par leur ID
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

// Sélection de l'élément contenant les vues d'inscription et de connexion par sa classe
const container = document.querySelector(".container");

// Gestionnaire d'événements pour le clic sur le bouton d'inscription
registerBtn.onclick = function () {
    // Ajout de la classe "active" à l'élément contenant les vues
    container.classList.add("active");
};

// Gestionnaire d'événements pour le clic sur le bouton de connexion
loginBtn.onclick = function () {
    // Suppression de la classe "active" de l'élément contenant les vues
    container.classList.remove("active");
};
