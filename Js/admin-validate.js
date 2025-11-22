function validateAdminLogin() {
    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("pass").value.trim();
    const errorMessage = document.getElementById("error-message");

    // Clear previous error messages
    errorMessage.innerHTML = "";

    // Email validation
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!email.match(emailPattern)) {
        errorMessage.innerHTML = "Please enter a valid email address.";
        return false;
    }

    // Password validation
    if (password.length < 6) {
        errorMessage.innerHTML = "Password must be at least 6 characters long.";
        return false;
    }

    return true; 
}