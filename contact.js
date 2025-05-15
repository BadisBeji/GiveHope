document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche l'envoi du formulaire s'il y a des erreurs

        let valid = true;

        // Récupération des champs
        let nom = document.querySelector("input[placeholder='Nom']").value.trim();
        let prenom = document.querySelector("input[placeholder='Prenom']").value.trim();
        let cin = document.querySelector("input[placeholder='CIN']").value.trim();
        let email = document.querySelector("input[placeholder='Email']").value.trim();
        let password = document.querySelector("input[placeholder='Mot de passe']").value.trim();

        // Réinitialiser les erreurs précédentes
        document.querySelectorAll(".error").forEach(e => e.textContent = "");

        // Vérification du Nom
        if (nom.length < 3) {
            showError("Nom", "Le nom doit contenir au moins 3 caractères.");
            valid = false;
        }

        // Vérification du Prénom
        if (prenom.length < 3) {
            showError("Prenom", "Le prénom doit contenir au moins 3 caractères.");
            valid = false;
        }

        // Vérification du CIN (8 chiffres attendus)
        if (!/^\d{8}$/.test(cin)) {
            showError("CIN", "Le CIN doit contenir exactement 8 chiffres.");
            valid = false;
        }

        // Vérification de l'Email avec regex
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            showError("Email", "Adresse email invalide.");
            valid = false;
        }

        // Vérification du Mot de Passe (minimum 6 caractères)
        if (password.length < 6) {
            showError("Mot de passe", "Le mot de passe doit contenir au moins 6 caractères.");
            valid = false;
        }

        // Si tout est bon, on peut envoyer le formulaire
        if (valid) {
            alert("Connexion réussie !");
            this.submit();
        }
    });

    // Fonction pour afficher les erreurs sous chaque champ
    function showError(placeholder, message) {
        let input = document.querySelector(`input[placeholder='${placeholder}']`);
        let errorSpan = document.createElement("span");
        errorSpan.className = "error";
        errorSpan.style.color = "red";
        errorSpan.textContent = message;
        input.parentNode.appendChild(errorSpan);
    }
});
