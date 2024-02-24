//the js validation should match with regex validation of symfony

function validatePassword() {
  //the function that was called in twig
  var password = document.getElementById(
    "registration_form_plainPassword"
  ).value;

  // Validate length
  if (password.length >= 8) {
    document.getElementById("length").style.display = "none";
  } else {
    document.getElementById("length").style.display = "block";
  }

  // Validate uppercase
  if (/[A-Z]/.test(password)) {
    document.getElementById("uppercase").style.display = "none";
  } else {
    document.getElementById("uppercase").style.display = "block";
  }

  // Validate lowercase
  if (/[a-z]/.test(password)) {
    document.getElementById("lowercase").style.display = "none";
  } else {
    document.getElementById("lowercase").style.display = "block";
  }
  //validate digit
  if (/\d/.test(password)) {
    document.getElementById("digit").style.display = "none";
  } else {
    document.getElementById("digit").style.display = "block";
  }
  // Validate special character
  if (/[@$!%*?&]/.test(password)) {
    document.getElementById("special").style.display = "none";
  } else {
    document.getElementById("special").style.display = "block";
  }
  // Check if any errors are being displayed
  var errors = document.querySelectorAll(
    "#length, #uppercase, #lowercase, #digit, #special"
  );
  var hasError = Array.from(errors).some(function (errorElement) {
    return errorElement.style.display === "block";
  });

  // If no errors, display success message
  if (!hasError) {
    document.getElementById("success").style.display = "block";
  } else {
    document.getElementById("success").style.display = "none";
  }
}
