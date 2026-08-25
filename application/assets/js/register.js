document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('registerForm');

	if (!form) {
		return;
	}

	const nameInput = document.getElementById('name');
	const emailInput = document.getElementById('email');
	const passwordInput = document.getElementById('password');
	const confirmPasswordInput = document.getElementById('confirm_password');

	function validateName() {
		if (!required(nameInput, 'Name is required.')) {
			return false;
		}

		return minLength(nameInput, 2, 'Name must be at least 2 characters.');
	}

	function validateEmail() {
		if (!required(emailInput, 'Email address is required.')) {
			return false;
		}

		return email(emailInput);
	}

	function validatePassword() {
		if (!required(passwordInput, 'Password is required.')) {
			return false;
		}

		return minLength(passwordInput, 6, 'Password must be at least 6 characters.');
	}

	function validateConfirmPassword() {
		if (!required(confirmPasswordInput, 'Please confirm your password.')) {
			return false;
		}

		if (confirmPasswordInput.value !== passwordInput.value) {
			showError(confirmPasswordInput, 'Passwords do not match.');
			return false;
		}

		clearError(confirmPasswordInput);
		return true;
	}

	const validateNameAfterTyping = debounce(validateName, 300);
	const validateEmailAfterTyping = debounce(validateEmail, 300);
	const validatePasswordAfterTyping = debounce(validatePassword, 300);
	const validateConfirmPasswordAfterTyping = debounce(validateConfirmPassword, 300);

	nameInput.addEventListener('input', function () {
		validateNameAfterTyping();
	});

	emailInput.addEventListener('input', function () {
		validateEmailAfterTyping();
	});

	passwordInput.addEventListener('input', function () {
		validatePasswordAfterTyping();

		if (confirmPasswordInput.value !== '') {
			validateConfirmPasswordAfterTyping();
		}
	});

	confirmPasswordInput.addEventListener('input', function () {
		validateConfirmPasswordAfterTyping();
	});

	nameInput.addEventListener('blur', validateName);
	emailInput.addEventListener('blur', validateEmail);
	passwordInput.addEventListener('blur', validatePassword);
	confirmPasswordInput.addEventListener('blur', validateConfirmPassword);

	form.addEventListener('submit', function (event) {
		const nameValid = validateName();
		const emailValid = validateEmail();
		const passwordValid = validatePassword();
		const confirmPasswordValid = validateConfirmPassword();

		if (!nameValid || !emailValid || !passwordValid || !confirmPasswordValid) {
			event.preventDefault();

			const firstInvalid = form.querySelector('.is-invalid');

			if (firstInvalid) {
				firstInvalid.focus();
			}
		}
	});
});