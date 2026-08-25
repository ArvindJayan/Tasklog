document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('loginForm');

	if (!form) {
		return;
	}

	const emailInput = document.getElementById('email');
	const passwordInput = document.getElementById('password');

	function validateEmail() {
		if (!required(emailInput, 'Email address is required.')) {
			return false;
		}

		return email(emailInput);
	}

	function validatePassword() {
		return required(passwordInput, 'Password is required.');
	}

	const validateEmailAfterTyping = debounce(validateEmail, 300);
	const validatePasswordAfterTyping = debounce(validatePassword, 300);

	emailInput.addEventListener('input', function () {
		validateEmailAfterTyping();
	});

	passwordInput.addEventListener('input', function () {
		validatePasswordAfterTyping();
	});

	emailInput.addEventListener('blur', validateEmail);
	passwordInput.addEventListener('blur', validatePassword);

	form.addEventListener('submit', function (event) {
		const emailValid = validateEmail();
		const passwordValid = validatePassword();

		if (!emailValid || !passwordValid) {
			event.preventDefault();

			const firstInvalid = form.querySelector('.is-invalid');

			if (firstInvalid) {
				firstInvalid.focus();
			}
		}
	});
});