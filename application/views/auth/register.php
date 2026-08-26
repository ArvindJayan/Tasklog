<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TaskLog</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
	<style>
		:root {
			--tasklog-bg: #0b1120;
			--tasklog-surface: #111827;
			--tasklog-border: #263449;
			--tasklog-cyan: #22d3ee;
		}

		body {
			background-color: var(--tasklog-bg);
		}

		.register-card {
			background-color: var(--tasklog-surface);
			border-color: var(--tasklog-border) !important;
		}

		.text-cyan {
			color: var(--tasklog-cyan);
		}

		.form-control {
			background-color: var(--tasklog-bg);
			border-color: var(--tasklog-border);
			color: #f8fafc;
		}

		.form-control:focus {
			background-color: var(--tasklog-bg);
			border-color: var(--tasklog-cyan);
			color: #f8fafc;
			box-shadow: 0 0 0 0.2rem rgba(34, 211, 238, 0.15);
		}

		.form-control::placeholder {
			color: #64748b;
		}
	</style>
</head>
<body class="text-light">
	<div class="container min-vh-100 d-flex align-items-center justify-content-center">
		<div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
			<div class="card register-card border rounded-4 shadow">
				<div class="card-body p-4">
					<div class="text-center mb-4">
						<a href="<?= base_url(); ?>" class="text-decoration-none">
							<h2 class="fw-bold text-light">Task<span class="text-cyan">Log</span></h2>
						</a>
						<p class="text-secondary mb-0">Create a new account</p>
					</div>

					<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger" role="alert">
							<?= $this->session->flashdata('error'); ?>
						</div>
					<?php endif; ?>

					<?php if ($this->session->flashdata('success')): ?>
						<div class="alert alert-success" role="alert">
							<?= $this->session->flashdata('success'); ?>
						</div>
					<?php endif; ?>

					<form id="registerForm" action="<?= site_url('auth/register'); ?>" method="POST" novalidate class="text-white">
						<div class="mb-3">
							<label for="name" class="form-label">Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" autocomplete="name" required>
						</div>

						<div class="mb-3">
							<label for="email" class="form-label">Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autocomplete="email" required>
						</div>

						<div class="mb-3">
							<label for="password" class="form-label">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" autocomplete="new-password" required>
						</div>

						<div class="mb-4">
							<label for="confirm_password" class="form-label">Confirm Password</label>
							<input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" autocomplete="new-password" required>
						</div>

						<button type="submit" class="btn btn-info w-100 py-2 fw-semibold">
							Register
						</button>
					</form>

					<div class="text-center mt-4">
						<small class="text-secondary">
							Already have an account?
							<a href="<?= site_url('auth/login'); ?>" class="text-cyan text-decoration-none">Login</a>
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url('application/assets/js/validation.js'); ?>"></script>
	<script src="<?= base_url('application/assets/js/register.js'); ?>"></script>
</body>
</html>