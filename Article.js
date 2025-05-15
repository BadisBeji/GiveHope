document.addEventListener('DOMContentLoaded', function() {
  // Sélection du formulaire
  const articleForm = document.getElementById('article-form');
  
  // Sélection des champs
  const titleInput = document.getElementById('title');
  const contentInput = document.getElementById('content');
  const categorySelect = document.getElementById('category');
  const authorInput = document.getElementById('author');
  const publicationDateInput = document.getElementById('publication_date');
  const statusSelect = document.getElementById('status');
  
  // Définir la date minimale comme aujourd'hui pour le champ de date
  const today = new Date();
  const yyyy = today.getFullYear();
  let mm = today.getMonth() + 1; // Les mois commencent à 0
  let dd = today.getDate();
  
  if (dd < 10) dd = '0' + dd;
  if (mm < 10) mm = '0' + mm;
  
  const formattedToday = yyyy + '-' + mm + '-' + dd;
  publicationDateInput.setAttribute('min', formattedToday);
  publicationDateInput.value = formattedToday;
  
  // Sélection des éléments d'erreur
  const titleError = document.getElementById('titleError');
  const contentError = document.getElementById('contentError');
  const categoryError = document.getElementById('categoryError');
  const authorError = document.getElementById('authorError');
  const publicationDateError = document.getElementById('publicationDateError');
  const statusError = document.getElementById('statusError');
  
  // Ajout de l'événement de soumission du formulaire
  articleForm.addEventListener('submit', function(event) {
    // Empêcher la soumission par défaut du formulaire
    event.preventDefault();
    
    // Réinitialiser tous les messages d'erreur
    resetErrors();
    
    // Vérifier si le formulaire est valide
    const isValid = validateForm();
    
    // Si le formulaire est valide, soumettre via AJAX
    if (isValid) {
      // Création d'un objet FormData
      const formData = new FormData(articleForm);
      
      // Création d'une requête AJAX
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'save_article.php', true);
      
      // Gestion de la réponse
      xhr.onload = function() {
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
              alert('Article ajouté avec succès!');
              // Redirection vers la page d'affichage des articles
              window.location.href = 'Display_Articles.php';
            } else {
              alert('Erreur lors de l\'ajout de l\'article: ' + response.message);
            }
          } catch (e) {
            console.error('Erreur de parsing JSON:', e);
            alert('Erreur lors du traitement de la réponse du serveur');
          }
        } else {
          alert('Erreur de connexion au serveur');
        }
      };
      
      // Gestion des erreurs de réseau
      xhr.onerror = function() {
        alert('Erreur réseau. Veuillez vérifier votre connexion.');
      };
      
      // Envoi de la requête
      xhr.send(formData);
    }
  });
  
  // Fonction pour valider le formulaire
  function validateForm() {
    let isValid = true;
    
    // Validation du titre
    if (!titleInput.value.trim()) {
      displayError(titleError, 'Le titre est obligatoire');
      isValid = false;
    } else if (titleInput.value.trim().length < 5) {
      displayError(titleError, 'Le titre doit contenir au moins 5 caractères');
      isValid = false;
    } else if (titleInput.value.trim().length > 100) {
      displayError(titleError, 'Le titre ne doit pas dépasser 100 caractères');
      isValid = false;
    }
    
    // Validation du contenu
    if (!contentInput.value.trim()) {
      displayError(contentError, 'Le contenu est obligatoire');
      isValid = false;
    } else if (contentInput.value.trim().length < 50) {
      displayError(contentError, 'Le contenu doit contenir au moins 50 caractères');
      isValid = false;
    }
    
    // Validation de la catégorie
    if (categorySelect.value === '' || categorySelect.selectedIndex === 0) {
      displayError(categoryError, 'Veuillez sélectionner une catégorie');
      isValid = false;
    }
    
    // Validation de l'auteur
    if (!authorInput.value.trim()) {
      displayError(authorError, 'Le nom de l\'auteur est obligatoire');
      isValid = false;
    } else if (!/^[a-zA-ZÀ-ÿ\s-]+$/.test(authorInput.value.trim())) {
      displayError(authorError, 'Le nom de l\'auteur ne doit contenir que des lettres, espaces et tirets');
      isValid = false;
    }
    
    // Validation de la date de publication
    if (!publicationDateInput.value) {
      displayError(publicationDateError, 'La date de publication est obligatoire');
      isValid = false;
    } else {
      const selectedDate = new Date(publicationDateInput.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (selectedDate < today) {
        displayError(publicationDateError, 'La date de publication ne peut pas être antérieure à aujourd\'hui');
        isValid = false;
      }
    }
    
    // Validation du statut
    if (statusSelect.value === '' || statusSelect.selectedIndex === 0) {
      displayError(statusError, 'Veuillez sélectionner un statut');
      isValid = false;
    }
    
    return isValid;
  }
  
  // Fonction pour afficher un message d'erreur
  function displayError(element, message) {
    element.textContent = message;
    element.style.display = 'block';
  }
  
  // Fonction pour réinitialiser tous les messages d'erreur
  function resetErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(function(element) {
      element.textContent = '';
      element.style.display = 'none';
    });
  }
  
  // Ajouter des événements de validation en temps réel pour une meilleure expérience utilisateur
  titleInput.addEventListener('input', function() {
    titleError.style.display = 'none';
  });
  
  contentInput.addEventListener('input', function() {
    contentError.style.display = 'none';
  });
  
  categorySelect.addEventListener('change', function() {
    categoryError.style.display = 'none';
  });
  
  authorInput.addEventListener('input', function() {
    authorError.style.display = 'none';
  });
  
  publicationDateInput.addEventListener('input', function() {
    publicationDateError.style.display = 'none';
  });
  
  statusSelect.addEventListener('change', function() {
    statusError.style.display = 'none';
  });
});