window.addEventListener("load", function () {
  const form = document.querySelector("form");

  if (!form) {
    console.warn("Login form not found.");
    return;
  }

  form.addEventListener("submit", function (event) {
    let valid = true;

    // Clear old errors
    document.querySelectorAll(".error").forEach(e => e.remove());

    const emailInput = form.querySelector("input[name='email']");
    const passwordInput = form.querySelector("input[name='password']");

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    // Email validation
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
      showError(emailInput, "Adresse email invalide.");
      valid = false;
    }

    // Password validation
    if (password.length < 6) {
      showError(passwordInput, "Mot de passe trop court (min. 6 caractères).");
      valid = false;
    }

    if (!valid) {
      event.preventDefault(); // stop form if invalid
    }
  });

  function showError(input, message) {
    const error = document.createElement("div");
    error.className = "error";
    error.style.color = "red";
    error.style.fontSize = "14px";
    error.textContent = message;
    input.parentNode.appendChild(error);
  }
});
