function showError(input, message) {
	input.classList.add('is-invalid');

	let error = input.parentElement.querySelector('.invalid-feedback');

	if (!error) {
		error = document.createElement('div');
		error.className = 'invalid-feedback';
		input.parentElement.appendChild(error);
	}

	error.textContent = message;
}

function clearError(input) {
	input.classList.remove('is-invalid');

	const error = input.parentElement.querySelector('.invalid-feedback');

	if (error) {
		error.remove();
	}
}

function required(input, message) {
	if (input.value.trim() === '') {
		showError(input, message);
		return false;
	}

	clearError(input);
	return true;
}

function email(input, message = 'Please enter a valid email address.') {
	const value = input.value.trim();
	const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	if (!pattern.test(value)) {
		showError(input, message);
		return false;
	}

	clearError(input);
	return true;
}

function minLength(input, min, message) {
	if (input.value.length < min) {
		showError(input, message);
		return false;
	}

	clearError(input);
	return true;
}

function maxLength(input, max, message) {
	if (input.value.length > max) {
		showError(input, message);
		return false;
	}

	clearError(input);
	return true;
}

function debounce(callback, delay = 300) {
	let timeout;

	return function () {
		clearTimeout(timeout);

		timeout = setTimeout(() => {
			callback();
		}, delay);
	};
}