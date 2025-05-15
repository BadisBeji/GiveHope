document.addEventListener("DOMContentLoaded", function() {
    // === VALIDATION DU FORMULAIRE DE DON ===
    const donationForm = document.getElementById("donationForm");

    if (donationForm) {
        // **Formatage automatique du numéro de carte**
        document.getElementById("card-number").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 16) value = value.slice(0, 16); // Limite à 16 chiffres
            this.value = value;
        });

        // **Formatage automatique de la date d'expiration**
        document.getElementById("card-expiry").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2); // Ajoute un slash après MM
            if (value.length > 5) value = value.slice(0, 5); // Limite à MM/AA
            this.value = value;
        });

        // **Formatage automatique du CVC**
        document.getElementById("card-cvc").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 4) value = value.slice(0, 4); // Limite à 4 chiffres
            this.value = value;
        });

        // **Validation en temps réel des champs**
        const donationFields = [
            "donor-name", "donor-email", "donation-amount",
            "donation-cause", "card-number", "card-expiry", "card-cvc"
        ];

        donationFields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener("blur", function() {
                    validateField(this);
                });
            }
        });

        // **Validation de la case à cocher**
        const acceptTerms = document.getElementById("accept-terms");
        if (acceptTerms) {
            acceptTerms.addEventListener("change", function() {
                validateCheckbox(this);
            });
        }

        // **Soumission du formulaire**
        donationForm.addEventListener("submit", function(event) {
            event.preventDefault();
            if (validateDonationForm()) {
                alert("Merci pour votre don ! Vous allez être redirigé vers la page de confirmation.");
                window.location.href = "donate-display.html"; // Redirection après validation
                // Décommentez la ligne suivante pour soumettre réellement le formulaire
                // this.submit();
            }
        });
    }

    // === VALIDATION DU FORMULAIRE DE BÉNÉVOLE ===
    const volunteerForm = document.getElementById("volunteerForm");

    if (volunteerForm) {
        // **Validation en temps réel des champs**
        const volunteerFields = ["volunteer-name", "volunteer-email", "volunteer-message"];

        volunteerFields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener("blur", function() {
                    validateVolunteerField(this);
                });
            }
        });

        // **Soumission du formulaire**
        volunteerForm.addEventListener("submit", function(event) {
            event.preventDefault();
            if (validateVolunteerForm()) {
                alert("Merci de votre intérêt ! Nous vous contacterons bientôt.");
                // Décommentez la ligne suivante pour soumettre réellement le formulaire
                // this.submit();
            }
        });
    }

    // === VALIDATION DU FORMULAIRE DE MODIFICATION DE DON ===
    const modifyForm = document.querySelector('.donation-form'); // Assurez-vous que votre formulaire a class="donation-form"

    if (modifyForm) {
        // **Formatage automatique du numéro de carte**
        document.getElementById("card-number").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 16) value = value.slice(0, 16); // Limite à 16 chiffres
            this.value = value;
        });

        // **Formatage automatique de la date d'expiration**
        document.getElementById("card-expiry").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2); // Ajoute un slash après MM
            if (value.length > 5) value = value.slice(0, 5); // Limite à MM/AA
            this.value = value;
        });

        // **Formatage automatique du CVC**
        document.getElementById("card-cvc").addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ''); // Supprime les caractères non numériques
            if (value.length > 4) value = value.slice(0, 4); // Limite à 4 chiffres
            this.value = value;
        });

        // **Validation en temps réel des champs**
        const modifyFields = [
            "donor-name", "donor-email", "donation-amount",
            "donation-cause", "card-number", "card-expiry", "card-cvc"
        ];

        modifyFields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.addEventListener("blur", function() {
                    validateField(this);
                });
            }
        });

        // **Soumission du formulaire**
        modifyForm.addEventListener("submit", function(event) {
            event.preventDefault();
            if (validateModifyForm()) {
                alert("Votre don a été modifié avec succès !");
                // Décommentez la ligne suivante pour soumettre réellement le formulaire
                // this.submit();
            }
        });
    }

    // === FONCTIONS DE VALIDATION ===

    // **Validation complète du formulaire de don**
    function validateDonationForm() {
        let isValid = true;
        const fields = [
            "donor-name", "donor-email", "donation-amount",
            "donation-cause", "card-number", "card-expiry", "card-cvc"
        ];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                isValid = validateField(element) && isValid;
            }
        });

        const acceptTerms = document.getElementById("accept-terms");
        if (acceptTerms) {
            isValid = validateCheckbox(acceptTerms) && isValid;
        }

        return isValid;
    }

    // **Validation complète du formulaire de modification**
    function validateModifyForm() {
        let isValid = true;
        const fields = [
            "donor-name", "donor-email", "donation-amount",
            "donation-cause", "card-number", "card-expiry", "card-cvc"
        ];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                isValid = validateField(element) && isValid;
            }
        });

        return isValid;
    }

    // **Validation d'un champ (partagé entre don et modification)**
    function validateField(field) {
        const id = field.id;
        const value = field.value.trim();
        const errorElement = document.getElementById(`${id}-error`);
        let isValid = true;
        let errorMessage = "";

        switch (id) {
            case "donor-name":
                if (!value) {
                    errorMessage = "Le nom est requis";
                    isValid = false;
                } else if (value.length < 2) {
                    errorMessage = "Le nom doit contenir au moins 2 caractères";
                    isValid = false;
                } else if (!/^[A-Za-zÀ-ÿ\s]+$/.test(value)) {
                    errorMessage = "Le nom doit contenir uniquement des lettres";
                    isValid = false;
                }
                break;

            case "donor-email":
                if (!value) {
                    errorMessage = "L'email est requis";
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    errorMessage = "Veuillez entrer une adresse email valide";
                    isValid = false;
                }
                break;

            case "donation-amount":
                const amount = parseFloat(value);
                if (!value) {
                    errorMessage = "Le montant du don est requis";
                    isValid = false;
                } else if (isNaN(amount) || amount < 1) {
                    errorMessage = "Le montant minimum est de 1$";
                    isValid = false;
                } else if (amount > 10000) {
                    errorMessage = "Le montant maximum est de 10,000$";
                    isValid = false;
                }
                break;

            case "donation-cause":
                if (!value) {
                    errorMessage = "Veuillez sélectionner une cause";
                    isValid = false;
                }
                break;

            case "card-number":
                if (!value) {
                    errorMessage = "Le numéro de carte est requis";
                    isValid = false;
                } else if (!/^[0-9]{16}$/.test(value)) {
                    errorMessage = "Le numéro de carte doit contenir 16 chiffres";
                    isValid = false;
                }
                break;

            case "card-expiry":
                if (!value) {
                    errorMessage = "La date d'expiration est requise";
                    isValid = false;
                } else if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(value)) {
                    errorMessage = "Format invalide. Utilisez MM/AA";
                    isValid = false;
                } else {
                    const [month, year] = value.split('/');
                    const expiryDate = new Date(`20${year}`, month - 1);
                    const now = new Date();
                    if (expiryDate < now) {
                        errorMessage = "La carte a expiré";
                        isValid = false;
                    }
                }
                break;

            case "card-cvc":
                if (!value) {
                    errorMessage = "Le CVC est requis";
                    isValid = false;
                } else if (!/^[0-9]{3,4}$/.test(value)) {
                    errorMessage = "Le CVC doit contenir 3 ou 4 chiffres";
                    isValid = false;
                }
                break;
        }

        if (errorElement) {
            errorElement.textContent = errorMessage;
            field.classList.toggle("is-invalid", !isValid);
            field.classList.toggle("is-valid", isValid);
        }

        return isValid;
    }

    // **Validation de la case à cocher (pour le formulaire de don uniquement)**
    function validateCheckbox(checkbox) {
        const id = checkbox.id;
        const checked = checkbox.checked;
        const errorElement = document.getElementById(`${id}-error`);
        const isValid = checked;

        errorElement.textContent = checked ? "" : "Vous devez accepter les termes et conditions";

        return isValid;
    }

    // **Validation complète du formulaire de bénévole**
    function validateVolunteerForm() {
        let isValid = true;
        const fields = ["volunteer-name", "volunteer-email", "volunteer-message"];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                isValid = validateVolunteerField(element) && isValid;
            }
        });

        return isValid;
    }

    // **Validation d'un champ du formulaire de bénévole**
    function validateVolunteerField(field) {
        const id = field.id;
        const value = field.value.trim();
        const errorElement = document.getElementById(`${id}-error`);
        let isValid = true;
        let errorMessage = "";

        switch (id) {
            case "volunteer-name":
                if (!value) {
                    errorMessage = "Le nom est requis";
                    isValid = false;
                } else if (value.length < 2) {
                    errorMessage = "Le nom doit contenir au moins 2 caractères";
                    isValid = false;
                } else if (!/^[A-Za-zÀ-ÿ\s]+$/.test(value)) {
                    errorMessage = "Le nom doit contenir uniquement des lettres";
                    isValid = false;
                }
                break;

            case "volunteer-email":
                if (!value) {
                    errorMessage = "L'email est requis";
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    errorMessage = "Veuillez entrer une adresse email valide";
                    isValid = false;
                }
                break;

            case "volunteer-message":
                if (!value) {
                    errorMessage = "Le message est requis";
                    isValid = false;
                } else if (value.length < 10) {
                    errorMessage = "Le message doit contenir au moins 10 caractères";
                    isValid = false;
                }
                break;
        }

        if (errorElement) {
            errorElement.textContent = errorMessage;
            field.classList.toggle("is-invalid", !isValid);
            field.classList.toggle("is-valid", isValid);
        }

        return isValid;
    }
});