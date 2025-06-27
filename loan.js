function calculate() { /* */
    var principal = document.loandata.principal.value;// Get the principal amount
    if (principal <= 0 || isNaN(principal)) {
        alert("Please enter a valid loan amount.");
        document.loandata.principal.focus();
        return false;
    }
    if (principal > 1000000) {
        alert("Please enter a loan amount less than or equal to Ksh. 1,000,000.");
        document.loandata.principal.focus();
        return false;
    }
    var years = document.loandata.years.value; // Get the number of years
    if (years <= 0 || isNaN(years)) {
        alert("Please enter a valid number of years.");
        document.loandata.years.focus();
        return false;
    }
    if (years > 30) {
        alert("Please enter a number of years less than or equal to 30.");
        document.loandata.years.focus();
        return false;
    }
    if (years < 1) {
        alert("Please enter a number of years greater than or equal to 1.");
        document.loandata.years.focus();
        return false;
    }
    var interest = document.loandata.interest.value / 100 / 12; // Get the monthly interest rate
    if (interest <= 0 || isNaN(interest)) {
        alert("Please enter a valid interest rate.");
        document.loandata.interest.focus();
        return false;
    }
    if (interest > 1) {
        alert("Please enter an interest rate less than or equal to 100.");
        document.loandata.interest.focus();
        return false;
    }
    var payments = document.loandata.years.value * 12;// Calculate the number of payments
    if (payments <= 0 || isNaN(payments)) {
        alert("Please enter a valid number of payments.");
        document.loandata.years.focus();
        return false;
    }
    if (payments > 360) {
        alert("Please enter a number of payments less than or equal to 360.");
        document.loandata.years.focus();
        return false;
    }
    
    var x = Math.pow(1 + interest, payments); // Calculate (1 + interest) ^ payments
    if (isNaN(x) || x <= 0) {
        alert("Error in calculation. Please check your inputs.");
        return false;
    }

    // Calculate the monthly payment using the formula
    // M = P * r * (1 + r)^n / ((1 + r)^n - 1)
    // where M is the monthly payment, P is the principal, r is the monthly interest
    
    var monthly = (principal*x*interest)/(x-1);
    if (isNaN(monthly) || monthly <= 0) {
        alert("Error in calculation. Please check your inputs.");
        return false;
    } 
    var total = monthly * payments; // Calculate the total payment
    if (isNaN(total) || total <= 0) {
        alert("Error in calculation. Please check your inputs.");
        return false;
    }
    var totalinterest = total - principal; // Calculate the total interest paid over the life of the loan
    if (isNaN(totalinterest) || totalinterest < 0) {
        alert("Error in calculation. Please check your inputs.");
        return false;
    }

    document.loandata.payment.value = monthly; // Display the monthly payment
    if (isNaN(document.loandata.payment.value) ||
        document.loandata.payment.value <= 0) {
        alert("Error in calculation. Please check your inputs.");
        return false;
    }

    document.loandata.total.value = total;
    document.loandata.totalinterest.value = totalinterest;

    if(!isNaN(monthly) && 
    (monthly != Number.POSITIVE_INFINITY) &&
    (monthly != Number.NEGATIVE_INFINITY)) {
        document.loandata.payment.value = round(monthly, 2);
        document.loandata.total.value = round(monthly * payments);
        document.loandata.totalinterest.value =
        round((monthly * payments) - principal);
    }
    else {
        document.loandata.payment.value = "0.00";
        document.loandata.total.value = "0.00";
        document.loandata.totalinterest.value = "0.00";
    }
    function round(x) {
        return Math.round(x * 100) / 100;
    }
    document.loandata.payment.value = round(monthly, 2);
    document.loandata.total.value = round(monthly * payments);
    document.loandata.totalinterest.value =
        round((monthly * payments) - principal);
    return false;    
}

function clearForm() {
    document.loandata.principal.value = "";
    document.loandata.years.value = "";
    document.loandata.interest.value = "";
    document.loandata.payment.value = "";
    document.loandata.total.value = "";
    document.loandata.totalinterest.value = "";
    document.loandata.principal.focus();
}
function validateForm() {
    if (document.loandata.principal.value === "") {
        alert("Please enter a loan amount.");
        document.loandata.principal.focus();
        return false;
    }
    if (document.loandata.years.value === "") {
        alert("Please enter the number of years.");
        document.loandata.years.focus();
        return false;
    }
    if (document.loandata.interest.value === "") {
        alert("Please enter the interest rate.");
        document.loandata.interest.focus();
        return false;
    }
    return true;
}
function init() {
    document.loandata.principal.focus();
    document.loandata.calculate.onclick = calculate;
    document.loandata.clear.onclick = clearForm;
    document.loandata.validate.onclick = validateForm;
    document.loandata.principal.onkeyup = function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
    };
    document.loandata.years.onkeyup = function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    };
    document.loandata.interest.onkeyup = function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
    };
}
window.onload = init; // Initialize the form when the window loads
window.onunload = function() {
    clearForm();
};
// This function clears the form when the window is closed or refreshed
// It ensures that the form is reset to its initial state, ready for a new calculation.
// This is useful for preventing accidental submissions or retaining old data.
// The function is called when the window is unloaded, which happens when the user navigates away

        const errorMessage = document.getElementById('error-message');
        const resultMessage = document.getElementById('result-message');
        const loadingMessage = document.getElementById('loading-message');
        const successMessage = document.getElementById('success-message');
        const failureMessage = document.getElementById('failure-message');

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }

        function showResult(message) {
            resultMessage.textContent = message;
            resultMessage.style.display = 'block';
        }

        function showLoading() {
            loadingMessage.style.display = 'block';
        }

        function hideLoading() {
            loadingMessage.style.display = 'none';
        }

        function showSuccess(message) {
            successMessage.textContent = message;
            successMessage.style.display = 'block';
        }

        function showFailure(message) {
            failureMessage.textContent = message;
            failureMessage.style.display = 'block';
        }
        function calculate() {
            showLoading();
            hideMessages();

            const principal = parseFloat(document.loandata.principal.value);
            const interest = parseFloat(document.loandata.interest.value) / 100 / 12; // Convert annual interest to monthly
            const years = parseFloat(document.loandata.years.value);
            const months = years * 12;

            if (isNaN(principal) || isNaN(interest) || isNaN(years) || principal <= 0 || interest < 0 || years <= 0) {
                showError("Please enter valid loan information.");
                hideLoading();
                return;
            }

            const monthlyPayment = (principal * interest) / (1 - Math.pow(1 + interest, -months));
            const totalPayment = monthlyPayment * months;
            const totalInterest = totalPayment - principal;

            document.loandata.payment.value = monthlyPayment.toFixed(2);
            document.loandata.total.value = totalPayment.toFixed(2);
            document.loandata.totalinterest.value = totalInterest.toFixed(2);

            showResult("Calculation successful!");
            showSuccess("Your monthly payment is: KSH." + document.loandata.payment.value);
            hideLoading();
        }
        function hideMessages() {
            errorMessage.style.display = 'none';
            resultMessage.style.display = 'none';
            loadingMessage.style.display = 'none';
            successMessage.style.display = 'none';
            failureMessage.style.display = 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            hideMessages(); // Hide messages on initial load
        });
        document.loandata.calculate.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission
            if (validateForm()) {
                calculate();
            }
        });