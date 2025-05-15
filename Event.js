document.addEventListener("DOMContentLoaded", function () {
    // Sélection du formulaire et du bouton d'ajout
    const form = document.querySelector('form');
    const addEventButton = document.getElementById("ajouterEvenement");

    // Fonction de création d'un message d'erreur
    function createErrorElement(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-danger small mt-1';
        errorDiv.textContent = message;
        return errorDiv;
    }

    // Validation du nom de l'événement
    function validateEventName(input) {
        const value = input.value.trim();
        const errorContainer = input.parentElement.querySelector('.error-message');

        if (errorContainer) errorContainer.remove(); // Suppression de l'erreur précédente

        if (value === '' || value.length < 5 || value.length > 30 || /^\d+$/.test(value) || /[<>\/{}$]/.test(value)) {
            const errorElement = createErrorElement('Le nom de l\'événement doit contenir entre 5 et 30 caractères, ne pas être uniquement des chiffres, et ne pas contenir de caractères spéciaux.');
            input.classList.add('is-invalid');
            input.parentElement.appendChild(errorElement);
            return false;
        }

        input.classList.remove('is-invalid');
        return true;
    }

    // Validation des autres champs obligatoires
    function validateRequiredFields() {
        let isValid = true;
        const requiredFields = ['event_description', 'start_datetime', 'end_datetime', 
                                'event_location', 'event_category', 'event_organizer', 'event_status'];

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorContainer = field.parentElement.querySelector('.error-message');

            if (errorContainer) errorContainer.remove(); // Supprimer le message précédent

            if (field.value.trim() === '') {
                const errorElement = createErrorElement("Ce champ est obligatoire.");
                field.classList.add('is-invalid');
                field.parentElement.appendChild(errorElement);
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    // Validation des dates
    function validateDates() {
        const startDatetime = document.getElementById('start_datetime');
        const endDatetime = document.getElementById('end_datetime');
        const startDate = new Date(startDatetime.value);
        const endDate = new Date(endDatetime.value);
        const errorContainer = endDatetime.parentElement.querySelector('.error-message');

        if (errorContainer) errorContainer.remove();

        if (startDate >= endDate) {
            const errorElement = createErrorElement('La date de début doit être antérieure à la date de fin.');
            endDatetime.classList.add('is-invalid');
            endDatetime.parentElement.appendChild(errorElement);
            return false;
        }

        endDatetime.classList.remove('is-invalid');
        return true;
    }

    // Validation générale du formulaire
    function validateForm() {
        let isValid = true;

        // Vérification du nom
        const eventNameInput = document.getElementById('event_name');
        if (!validateEventName(eventNameInput)) isValid = false;

        // Vérification des champs requis
        if (!validateRequiredFields()) isValid = false;

        // Vérification des dates
        if (!validateDates()) isValid = false;

        return isValid;
    }

    // Écouteur pour la validation en temps réel du nom de l'événement
    document.getElementById('event_name').addEventListener('input', function() {
        validateEventName(this);
    });

    // Suppression dynamique des erreurs au remplissage des champs
    ['event_description', 'start_datetime', 'end_datetime', 
    'event_location', 'event_category', 'event_organizer', 'event_status']
    .forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('input', function() {
            const errorContainer = this.parentElement.querySelector('.error-message');
            if (errorContainer) errorContainer.remove();
            this.classList.remove('is-invalid');
        });
    });

    // Gestion du bouton d'ajout d'événement
    if (addEventButton) {
        addEventButton.addEventListener("click", function (event) {
            event.preventDefault();

            if (validateForm()) {
                let eventData = {
                    eventName: document.getElementById("event_name").value.trim(),
                    eventDescription: document.getElementById("event_description").value.trim(),
                    startDatetime: document.getElementById("start_datetime").value,
                    endDatetime: document.getElementById("end_datetime").value,
                    eventLocation: document.getElementById("event_location").value.trim(),
                    eventCategory: document.getElementById("event_category").value,
                    eventOrganizer: document.getElementById("event_organizer").value.trim(),
                    eventStatus: document.getElementById("event_status").value
                };

                console.log("Nouvel événement ajouté:", eventData);

                // Simuler un enregistrement sans Fetch
                setTimeout(() => {
                    alert("L'événement a été enregistré avec succès !");
                    window.location.href = "display-events.html";
                }, 200);
            }
        });
    }
});
