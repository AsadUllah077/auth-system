// Registration file Validation
let registerForm = document.getElementById("registrationForm");
if (registerForm) {
  registerForm.addEventListener("submit", function (e) {
    const password = document.querySelector("#password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const email = document.getElementById("email").value;
    const name = document.getElementById("name").value;
    const phone = document.getElementById("phone").value;
    const gender = document.getElementById("gender").value;

    let isValid = true;

    if (name.trim() === "") {
      isValid = false;
      document.getElementById("name_required").innerText = "Name is required";
      // document.querySelector('label[for="name"] + .error').textContent = 'Name is required.';
    }
    if (email.trim() === "") {
      isValid = false;
      document.querySelector("#email_required").innerText = "Email is required";
      return true;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      isValid = false;
      document.querySelector("#email_required").innerText =
        "Valid email is required";
      return true;

      // document.querySelector('label[for="email"] + .error').textConltent = 'Valid email is required.';
    }

    if (phone.trim() === "") {
      isValid = false;
      document.getElementById("phone_required").innerText = "Phone is required";

      // return true
    }
    if (gender.trim() === "") {
      isValid = false;
      document.getElementById("gender_required").innerText =
        "Gender is required";
      // return true
    }
    if (password.trim() === "") {
      isValid = false;
      document.getElementById("password_required").innerText =
        "password is required";
      return true;
    }

    if (password !== confirmPassword) {
      isValid = false;
      document.getElementById("confirmpassword_required").innerText =
        "Password confirmation is not matched";
      // alert("Passwords do not match!");
    }

    if (!isValid) {
      e.preventDefault();
    }
  });
}


//update password file validation
let updateForm = document.getElementById("updateForm");
if (updateForm) {
  updateForm.addEventListener("submit", function (e) {
    let isValid = true;

    const passwordField = document.querySelector('input[name="password"]');
    const confirmPasswordField = document.getElementById("confirmPassword");

    const password = passwordField.value.trim();
    const confirmPassword = confirmPasswordField.value.trim();

    const passwordError = document.getElementById("password_error");
    const confirmPasswordError = document.getElementById("confirm_password_error");

    // Clear previous error messages
    passwordError.innerText = "";
    confirmPasswordError.innerText = "";

    // Check if password is empty
    if (password === "") {
      isValid = false;
      passwordError.innerText = "Password is required.";
    }

    // Check if passwords match
    if (password !== confirmPassword) {
      isValid = false;
      confirmPasswordError.innerText = "Passwords do not match.";
    }

    // Prevent form submission if validation fails
    if (!isValid) {
      e.preventDefault();
    }
  });
}


//login file validation
let loginForm = document.getElementById("loginForm");
if (loginForm) {
  loginForm.addEventListener("submit", function (e) {
    let isValid = true;

    // Get form values
    const emailOrName = document.getElementById("emailOrName").value.trim();
    const password = document.getElementById("password").value.trim();

    // Clear previous error messages
    document.getElementById("emailOrName_error").innerText = "";
    document.getElementById("password_error").innerText = "";

    // Validate Email or Name
    if (emailOrName === "") {
      isValid = false;
      document.getElementById("emailOrName_error").innerText =
        "Email or Name is required.";
    }

    // Validate Password
    if (password === "") {
      isValid = false;
      document.getElementById("password_error").innerText =
        "Password is required.";
    }

    // Prevent form submission if validation fails
    if (!isValid) {
      e.preventDefault();
    }
  });
}

//reset  password file validation
let resetPasswordForm = document.getElementById("resetPasswordForm");
if (resetPasswordForm) {
  resetPasswordForm.addEventListener("submit", function (e) {
    let isValid = true;

    // Get email value
    const email = document.getElementById("email").value.trim();

    // Clear previous error messages
    document.getElementById("email_error").innerText = "";

    // Validate Email
    if (email === "") {
      isValid = false;
      document.getElementById("email_error").innerText = "Email is required.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      isValid = false;
      document.getElementById("email_error").innerText =
        "Please enter a valid email address.";
    }

    // Prevent form submission if validation fails
    if (!isValid) {
      e.preventDefault();
    }
  });
}


