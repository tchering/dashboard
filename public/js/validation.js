function validatePassword() {
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
}
