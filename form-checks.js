const emailInput = document.getElementById('email');
const streetInput = document.getElementById('street');
const streetNrInput = document.getElementById('streetNumber');
const zipcodeInput = document.getElementById('zipcode');

const checkEmail = (event) => {
    if(event.key === "Tab" || event.key === "Enter") {
        const input = event.target;
        const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-]+$/;

        if (!input.value.match(validRegex)) {
            alert("Invalid email address!");
            input.classList.add("invalidInput");
            return false;
        }
        else {
            input.classList.remove("invalidInput");
            return true;
        }
    }
};

const checkStreet = (event) => {
    if(event.key === "Tab" || event.key === "Enter") {
        const input = event.target;

        const validRegex = /^[a-zA-Z-' ]*$/;

        if (!input.value.match(validRegex)) {
            alert("Invalid street!");
            input.classList.add("invalidInput");
            return false;
        }
        else {
            input.classList.remove("invalidInput");
            return true;
        }
    }
};

const checkNumber = (event) => {
    if(event.key === "Tab" || event.key === "Enter") {
        const input = event.target;

        const validRegex = /^[0-9]*$/;

        if (!input.value.match(validRegex)) {
            alert("Not a number!");
            input.classList.add("invalidInput");
            return false;
        }
        else {
            input.classList.remove("invalidInput");
            return true;
        }
    }
};

emailInput.addEventListener('keydown', checkEmail);
streetInput.addEventListener('keydown', checkStreet);
streetNrInput.addEventListener('keydown', checkNumber);
zipcodeInput.addEventListener('keydown', checkNumber);