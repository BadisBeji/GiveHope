function navigateTo(section) {
  const content = document.getElementById('content-area');
  fetch(`views/${section}.php`)
    .then(r => {
      if (!r.ok) throw new Error('Erreur de chargement de la section');
      return r.text();
    })
    .then(html => content.innerHTML = html)
    .catch(err => content.innerHTML = `<p style="color:red;">Erreur : ${err.message}</p>`);
}
