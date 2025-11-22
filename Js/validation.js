function validateSignUpForm() {
    const signUpName = document.getElementById('signUpName').value;
    const signUpEmail = document.getElementById('signUpEmail').value;
    const signUpPassword = document.getElementById('signUpPassword').value;
    const signUpConfirmPassword = document.getElementById('signUpConfirmPassword').value;

    if (signUpName === '' || signUpEmail === '' || signUpPassword === '' || signUpConfirmPassword === '') {
        alert('All fields are required.');
        return false;
    }

    if (signUpPassword !== signUpConfirmPassword) {
        alert('Passwords do not match.');
        return false;
    }

    return true;
}

function validateAdditionalForm() {
    const addressLine1 = document.getElementById('addressLine1').value;
    const city = document.getElementById('city').value;
    const state = document.getElementById('state').value;
    const contact = document.getElementById('contact').value;

    if (addressLine1 === '' || city === '' || state === '' || contact === '') {
        alert('All fields are required.');
        return false;
    }

    return true;
}