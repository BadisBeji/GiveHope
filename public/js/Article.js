document.getElementById('article-form').addEventListener('submit', function(e) {
  e.preventDefault(); // Empêche la soumission par défaut du formulaire
  
  let isValid = true;

  // Récupérer les éléments du formulaire
  const title = document.getElementById('title').value;
  const content = document.getElementById('content').value;
  const category = document.getElementById('category').value;
  const author = document.getElementById('author').value;
  const publicationDate = document.getElementById('publication_date').value;
  const status = document.getElementById('status').value;

  // Validation du titre
  if (!title || title.length < 3 || title.length > 100) {
    document.getElementById('titleError').style.display = 'block';
    document.getElementById('titleError').textContent = 'Le titre doit avoir entre 3 et 100 caractères.';
    isValid = false;
  } 

  // Validation du contenu
  if (!content || content.length < 10) {
    document.getElementById('contentError').style.display = 'block';
    document.getElementById('contentError').textContent = 'Le contenu doit contenir au moins 10 caractères.';
    isValid = false;
  } else {
    document.getElementById('contentError').style.display = 'none';
  }

  // Validation de la catégorie
  if (!category) {
    document.getElementById('categoryError').style.display = 'block';
    document.getElementById('categoryError').textContent = 'Veuillez sélectionner une catégorie.';
    isValid = false;
  } else {
    document.getElementById('categoryError').style.display = 'none';
  }

  // Validation de l'auteur
  if (!author || author.length < 3 || author.length > 50) {
    document.getElementById('authorError').style.display = 'block';
    document.getElementById('authorError').textContent = 'Le nom de l\'auteur doit avoir entre 3 et 50 caractères.';
    isValid = false;
  } else {
    document.getElementById('authorError').style.display = 'none';
  }

  // Validation de la date de publication
  if (!publicationDate) {
    document.getElementById('publicationDateError').style.display = 'block';
    document.getElementById('publicationDateError').textContent = 'La date de publication est requise.';
    isValid = false;
  } else {
    document.getElementById('publicationDateError').style.display = 'none';
  }

  // Validation du statut
  if (!status) {
    document.getElementById('statusError').style.display = 'block';
    document.getElementById('statusError').textContent = 'Veuillez sélectionner un statut.';
    isValid = false;
  } else {
    document.getElementById('statusError').style.display = 'none';
  }

  // Soumission du formulaire si tout est valide
  if (isValid) {
    alert('Formulaire soumis avec succès!');
    window.location.href = 'Display-Articles.html'; // Redirection vers la page de visualisation de l'article
  } else {
    alert('Veuillez remplir tous les champs correctement.');
  }
});

// Fonction pour valider l'email (ajoutée pour une validation générique si nécessaire)
function validateEmail(email) {
  const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  return regex.test(email);
}
