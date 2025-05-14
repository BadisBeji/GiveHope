// Emplacement: givehope-master/public/js/validation.js

/**
 * Script de validation JavaScript côté client pour les formulaires de l'application GiveHope MVC.
 *
 * Objectifs :
 * 1. Fournir un retour utilisateur immédiat sur la validité des données saisies avant la soumission au serveur.
 * 2. Améliorer l'expérience utilisateur en guidant la saisie.
 * 3. Fonctionner en tandem avec la validation côté serveur (PHP), qui reste la validation de dernier recours et la plus sécurisée.
 * 4. Respecter la contrainte de ne pas utiliser les contrôles de saisie HTML5 natifs pour la validation bloquante
 * (les formulaires HTML doivent avoir l'attribut 'novalidate').
 *
 * Structure :
 * - Un écouteur d'événement 'DOMContentLoaded' pour s'assurer que le DOM est prêt avant d'attacher les écouteurs.
 * - Sélection des éléments de formulaire.
 * - Fonctions de configuration pour attacher les écouteurs d'événements ('blur' pour les champs, 'submit' pour les formulaires).
 * - Fonctions de validation globales pour chaque formulaire (ex: validateDonationForm).
 * - Fonctions de validation individuelles pour chaque type de champ (ex: validateSingleField, validateCheckbox).
 * - Fonction pour le formatage des entrées (ex: numéro de carte).
 */

document.addEventListener("DOMContentLoaded", function() {
    "use strict"; // Activer le mode strict pour de meilleures pratiques JavaScript.

    // --- Sélection des éléments de formulaire principaux ---
    const donationForm = document.getElementById("donationForm");
    const modificationForm = document.getElementById("modificationForm");
    const volunteerForm = document.getElementById("volunteerForm");

    // --- Initialisation de la validation pour chaque formulaire s'il existe sur la page ---

    if (donationForm) {
        setupCommonFormValidationListeners(donationForm, validateDonationForm, true); // true = inclure la validation des champs de carte
        setupInputFormatting(donationForm); // Appliquer le formatage pour les champs de carte
    }

    if (modificationForm) {
        // Pour le formulaire de modification, on ne valide généralement pas les champs de carte ni les termes.
        setupCommonFormValidationListeners(modificationForm, validateModifyForm, false); // false = exclure la validation des champs de carte
    }

    if (volunteerForm) {
        setupVolunteerFormValidationListeners(volunteerForm);
    }

    // === FONCTIONS UTILITAIRES DE CONFIGURATION DES ÉCOUTEURS D'ÉVÉNEMENTS ===

    /**
     * Configure les écouteurs d'événements pour la validation des formulaires communs (Don, Modification).
     * Attache des écouteurs 'blur' aux champs pour une validation en temps réel et un écouteur 'submit' au formulaire.
     * @param {HTMLFormElement} formElement L'élément du formulaire HTML.
     * @param {Function} globalValidationFunction La fonction à appeler pour valider l'ensemble du formulaire lors de la soumission.
     * @param {boolean} includeCardAndTermsFields Booléen indiquant s'il faut inclure les champs de carte bancaire et la case des termes dans la validation.
     */
    function setupCommonFormValidationListeners(formElement, globalValidationFunction, includeCardAndTermsFields) {
        const fieldsToValidate = ["donor-name", "donor-email", "donation-amount", "donation-cause"];
        
        if (includeCardAndTermsFields) {
            fieldsToValidate.push("card-number", "card-expiry", "card-cvc");
        }

        // Validation au "blur" (quand l'utilisateur quitte un champ)
        fieldsToValidate.forEach(fieldId => {
            const element = formElement.querySelector(`#${fieldId}`);
            if (element) {
                element.addEventListener("blur", function() {
                    validateSingleField(this); // 'this' se réfère à l'élément input/select qui a perdu le focus.
                });
            }
        });

        if (includeCardAndTermsFields) {
            const acceptTermsCheckbox = formElement.querySelector("#accept-terms");
            if (acceptTermsCheckbox) {
                acceptTermsCheckbox.addEventListener("change", function() {
                    validateCheckbox(this); // Valider la checkbox quand son état change.
                });
            }
        }

        // Validation globale lors de la soumission du formulaire.
        formElement.addEventListener("submit", function(event) {
            console.log(`Soumission JS interceptée pour le formulaire : ${formElement.id}`); // Pour débogage.
            
            const isFormCompletelyValid = globalValidationFunction(formElement); 

            if (!isFormCompletelyValid) {
                console.log(`Validation JS pour '${formElement.id}' a échoué. Soumission annulée.`);
                event.preventDefault(); // Crucial : Empêche l'envoi du formulaire au serveur si la validation JS échoue.
                
                // Optionnel: Afficher un message d'erreur global sur le formulaire.
                // Vous pourriez avoir un <div class="form-global-error text-danger mb-3"></div> dans votre HTML.
                const globalErrorPlaceholder = formElement.querySelector(".form-global-error"); 
                if (globalErrorPlaceholder) {
                    globalErrorPlaceholder.textContent = "Veuillez corriger les erreurs indiquées dans le formulaire.";
                } else {
                    // Fallback si le placeholder n'existe pas.
                    alert("Veuillez corriger les erreurs indiquées dans le formulaire avant de soumettre.");
                }
            } else {
                console.log(`Validation JS pour '${formElement.id}' réussie. Soumission autorisée vers le serveur PHP.`);
                // Si la validation JS est OK, le formulaire est soumis normalement au serveur (PHP prendra le relais).
                // Nettoyer un éventuel message d'erreur global.
                const globalErrorPlaceholder = formElement.querySelector(".form-global-error");
                if (globalErrorPlaceholder) globalErrorPlaceholder.textContent = "";
            }
        });
    }

    /**
     * Applique le formatage automatique pour certains champs d'entrée (ex: carte bancaire).
     * @param {HTMLFormElement} formElement Le formulaire contenant les champs à formater.
     */
    function setupInputFormatting(formElement) {
        const cardNumberInput = formElement.querySelector("#card-number");
        if (cardNumberInput) {
            cardNumberInput.addEventListener("input", function() {
                let value = this.value.replace(/\D/g, ''); // Enlever non-numériques.
                let formattedValue = '';
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) formattedValue += ' ';
                    formattedValue += value[i];
                }
                this.value = formattedValue.slice(0, 19); // Limite (ex: 16 chiffres + 3 espaces).
            });
        }

        const cardExpiryInput = formElement.querySelector("#card-expiry");
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener("input", function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2);
                let month = parseInt(value.substring(0, 2), 10);
                if (month > 12) value = '12' + value.slice(2);
                if (value.length === 1 && month > 1 && month < 10) value = '0' + month; // ex: 2 -> 02
                this.value = value.slice(0, 5); // MM/AA
            });
        }

        const cardCvcInput = formElement.querySelector("#card-cvc");
        if (cardCvcInput) {
            cardCvcInput.addEventListener("input", function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 4); // Max 4 chiffres.
            });
        }
    }

    /**
     * Configure les écouteurs pour la validation du formulaire de bénévole.
     * @param {HTMLFormElement} formElement L'élément du formulaire bénévole.
     */
    function setupVolunteerFormValidationListeners(formElement) {
        const fieldsToValidate = ["volunteer-name", "volunteer-email", "volunteer-message"];
        fieldsToValidate.forEach(fieldId => {
            const element = formElement.querySelector(`#${fieldId}`);
            if (element) {
                element.addEventListener("blur", function() { validateVolunteerField(this); });
            }
        });

        formElement.addEventListener("submit", function(event) {
            console.log(`Soumission JS interceptée pour : ${formElement.id}`);
            const isFormValid = validateVolunteerForm(formElement);
            if (!isFormValid) {
                event.preventDefault();
                alert("Veuillez corriger les erreurs dans le formulaire de bénévolat.");
            } else {
                // Pour la démo, on simule l'envoi car l'action PHP n'est pas implémentée.
                event.preventDefault(); 
                alert("Merci pour votre intérêt ! (Simulation d'envoi du formulaire bénévole).");
                formElement.reset();
            }
        });
    }

    // === FONCTIONS DE VALIDATION GLOBALES (appelées lors du 'submit') ===

    function validateDonationForm(form) {
        let isOverallValid = true;
        const fields = ["donor-name", "donor-email", "donation-amount", "donation-cause", "card-number", "card-expiry", "card-cvc"];
        fields.forEach(id => { if (form.querySelector(`#${id}`) && !validateSingleField(form.querySelector(`#${id}`))) isOverallValid = false; });
        if (form.querySelector("#accept-terms") && !validateCheckbox(form.querySelector("#accept-terms"))) isOverallValid = false;
        return isOverallValid;
    }

    function validateModifyForm(form) {
        let isOverallValid = true;
        const fields = ["donor-name", "donor-email", "donation-amount", "donation-cause"];
        fields.forEach(id => { if (form.querySelector(`#${id}`) && !validateSingleField(form.querySelector(`#${id}`))) isOverallValid = false; });
        return isOverallValid;
    }

    function validateVolunteerForm(form) {
        let isOverallValid = true;
        const fields = ["volunteer-name", "volunteer-email", "volunteer-message"];
        fields.forEach(id => { if (form.querySelector(`#${id}`) && !validateVolunteerField(form.querySelector(`#${id}`))) isOverallValid = false; });
        return isOverallValid;
    }

    // === FONCTIONS DE VALIDATION INDIVIDUELLES POUR LES CHAMPS ===

    /**
     * Valide un champ de formulaire individuel (pour don et modification).
     * @param {HTMLInputElement|HTMLSelectElement} fieldElement L'élément de champ à valider.
     * @returns {boolean} True si valide, false sinon.
     */
    function validateSingleField(fieldElement) {
        if (!fieldElement) return true; // Si le champ n'existe pas, ne pas bloquer.
        const id = fieldElement.id;
        const value = fieldElement.value.trim();
        const errorElement = fieldElement.form.querySelector(`#${id}-error`);
        let isValid = true;
        let errorMessage = "";

        fieldElement.classList.remove('is-invalid', 'is-valid');
        if (errorElement) errorElement.textContent = '';

        switch (id) {
            case "donor-name":
                if (!value) errorMessage = "Le nom est requis.";
                else if (value.length < 2) errorMessage = "Minimum 2 caractères.";
                else if (!/^[A-Za-zÀ-ÿ\s'-.]+$/u.test(value)) errorMessage = "Nom invalide (lettres, espaces, apostrophes, tirets, points autorisés).";
                break;
            case "donor-email":
                if (!value) errorMessage = "L'email est requis.";
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) errorMessage = "Format d'email invalide.";
                break;
            case "donation-amount":
                const amount = parseFloat(value);
                if (!value) errorMessage = "Montant requis.";
                else if (isNaN(amount)) errorMessage = "Montant invalide.";
                else if (amount < 1.00) errorMessage = "Minimum 1.00 $.";
                else if (amount > 10000.00) errorMessage = "Maximum 10,000.00 $.";
                else if (!/^\d+(\.\d{1,2})?$/.test(value)) errorMessage = "Format de montant invalide (ex: 50 ou 50.75).";
                break;
            case "donation-cause":
                if (!value) errorMessage = "Veuillez sélectionner une cause.";
                break;
            case "card-number":
                const cardNumberRaw = value.replace(/\s/g, '');
                if (!value) errorMessage = "Numéro de carte requis.";
                else if (!/^[0-9]{13,19}$/.test(cardNumberRaw)) errorMessage = "Numéro de carte invalide (13-19 chiffres).";
                break;
            case "card-expiry":
                if (!value) errorMessage = "Date d'expiration requise.";
                else if (!/^(0[1-9]|1[0-2])\/?([0-9]{2})$/.test(value.replace(/\s/g, ''))) errorMessage = "Format MM/AA invalide.";
                else {
                    const [monthStr, yearSuffixStr] = value.replace(/\s/g, '').split('/');
                    const month = parseInt(monthStr, 10);
                    const year = parseInt(`20${yearSuffixStr}`, 10);
                    const now = new Date();
                    const currentYear = now.getFullYear();
                    const currentMonth = now.getMonth() + 1; 
                    if (year < currentYear || (year === currentYear && month < currentMonth)) {
                        errorMessage = "Carte expirée.";
                    }
                }
                break;
            case "card-cvc":
                if (!value) errorMessage = "CVC requis.";
                else if (!/^[0-9]{3,4}$/.test(value)) errorMessage = "CVC invalide (3 ou 4 chiffres).";
                break;
        }

        if (errorMessage) {
            isValid = false;
            if (errorElement) errorElement.textContent = errorMessage;
            fieldElement.classList.add('is-invalid');
        } else {
            if (value) { /* fieldElement.classList.add('is-valid'); // Optionnel */ }
        }
        return isValid;
    }

    /**
     * Valide une case à cocher.
     * @param {HTMLInputElement} checkboxElement L'élément checkbox.
     * @returns {boolean} True si cochée, false sinon.
     */
    function validateCheckbox(checkboxElement) {
        if (!checkboxElement) return true;
        const id = checkboxElement.id;
        const errorElement = checkboxElement.form.querySelector(`#${id}-error`);
        const isValid = checkboxElement.checked;

        checkboxElement.classList.remove('is-invalid');
        if (errorElement) errorElement.textContent = '';

        if (!isValid) {
            if (errorElement) errorElement.textContent = "Vous devez accepter les termes.";
            checkboxElement.classList.add('is-invalid');
        }
        return isValid;
    }

    /**
     * Valide un champ du formulaire de bénévole.
     * @param {HTMLInputElement|HTMLTextAreaElement} fieldElement Le champ à valider.
     * @returns {boolean} True si valide, false sinon.
     */
    function validateVolunteerField(fieldElement) {
        if (!fieldElement) return true;
        const id = fieldElement.id;
        const value = fieldElement.value.trim();
        const errorElement = fieldElement.form.querySelector(`#${id}-error`);
        let isValid = true;
        let errorMessage = "";

        fieldElement.classList.remove('is-invalid', 'is-valid');
        if (errorElement) errorElement.textContent = '';

        switch (id) {
            case "volunteer-name":
                if (!value) errorMessage = "Votre nom est requis.";
                else if (value.length < 2) errorMessage = "Minimum 2 caractères.";
                else if (!/^[A-Za-zÀ-ÿ\s'-.]+$/u.test(value)) errorMessage = "Nom invalide.";
                break;
            case "volunteer-email":
                if (!value) errorMessage = "Votre email est requis.";
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) errorMessage = "Format d'email invalide.";
                break;
            case "volunteer-message":
                if (!value) errorMessage = "Un message est requis.";
                else if (value.length < 10) errorMessage = "Minimum 10 caractères pour le message.";
                break;
        }

        if (errorMessage) {
            isValid = false;
            if (errorElement) errorElement.textContent = errorMessage;
            fieldElement.classList.add('is-invalid');
        } else {
            if (value) { /* fieldElement.classList.add('is-valid'); // Optionnel */ }
        }
        return isValid;
    }

}); // Fin de DOMContentLoaded