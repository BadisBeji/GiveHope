function submitForm() {
  // Recupertion des valeur de HTML -> JS 
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let description = document.getElementById('description').value;
    let address = document.getElementById('address').value;
    let phone = document.getElementById('phone').value;
    let domain = document.getElementById('domain').value;
    let creationDate = document.getElementById('creation_date').value;
    let country = document.getElementById('country').value;
  
    let isValid = true;   
  
    // Validation du nom
    if (!name || name.length < 3 || name.length > 50) {
      document.getElementById('nameError').style.display = 'block';
      document.getElementById('nameError').textContent = 'Le nom de l\'association doit avoir entre 3 et 50 caractères.';
      isValid = false;
    } else {
      document.getElementById('nameError').style.display = 'none';
    }
  
    // Validation de l'email
    if (!email || !validateEmail(email)) {
      document.getElementById('emailError').style.display = 'block';
      document.getElementById('emailError').textContent = 'Veuillez entrer un email valide.';
      isValid = false;
    } else {
      document.getElementById('emailError').style.display = 'none';
    }
  
    // Validation de la description
    if (!description || description.length < 10 || description.length > 300) {
      document.getElementById('descriptionError').style.display = 'block';
      document.getElementById('descriptionError').textContent = 'La description doit contenir entre 10 et 300 caractères.';
      isValid = false;
    } else {
      document.getElementById('descriptionError').style.display = 'none';
    }
  
    // Validation de l'adresse
    if (!address || address.length > 200) {
      document.getElementById('addressError').style.display = 'block';
      document.getElementById('addressError').textContent = 'L\'adresse ne doit pas dépasser 200 caractères.';
      isValid = false;
    } else {
      document.getElementById('addressError').style.display = 'none';
    }
  
    // Validation du numéro de téléphone
    if (!phone || phone.length > 15) {
      document.getElementById('phoneError').style.display = 'block';
      document.getElementById('phoneError').textContent = 'Le numéro de téléphone ne doit pas dépasser 15 caractères.';
      isValid = false;
    } else {
      document.getElementById('phoneError').style.display = 'none';
    }
  
    // Validation du domaine d'activité
    if (!domain) {
      document.getElementById('domainError').style.display = 'block';
      document.getElementById('domainError').textContent = 'Veuillez sélectionner un domaine d\'activité.';
      isValid = false;
    } else {
      document.getElementById('domainError').style.display = 'none';
    }
  
    // Validation de la date de création
    if (!creationDate) {
      document.getElementById('creationDateError').style.display = 'block';
      document.getElementById('creationDateError').textContent = 'La date de création est requise.';
      isValid = false;
    } else {
      document.getElementById('creationDateError').style.display = 'none';
    }
  
    // Validation du pays d'activité
    if (!country || country.length > 100) {
      document.getElementById('countryError').style.display = 'block';
      document.getElementById('countryError').textContent = 'Le pays d\'activité ne doit pas dépasser 100 caractères.';
      isValid = false;
    } else {
      document.getElementById('countryError').style.display = 'none';
    }
  
    if (isValid) {
      alert('Formulaire soumis avec succès!');
      window.location.href = 'Display-Association.html'; // Redirection vers Display-Association.html
    } else {
      alert('Veuillez remplir tous les champs correctement.');
    }
  }
  
  // Fonction pour valider l'email
  function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return regex.test(email);
  }
  