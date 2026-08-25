<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - TaskLog</title>
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

		.login-card {
			background-color: var(--tasklog-surface);
			border-color: var(--tasklog-border) !important;
		}

		.text-cyan {
			color: var(--tasklog-cyan);
		}

		.form-control {
			background-color: #0b1120;
			border-color: var(--tasklog-border);
			color: #f8fafc;
		}

		.form-control:focus {
			background-color: #0b1120;
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
	<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
		<div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
			<div class="card login-card border rounded-4 shadow">
				<div class="card-body p-4 p-md-5">
					<div class="text-center mb-4">
						<a href="<?= base_url(); ?>" class="text-decoration-none">
							<h2 class="fw-bold text-light">Task<span class="text-cyan">Log</span></h2>
						</a>
						<p class="text-secondary mb-0">Sign in to your account</p>
					</div>

					<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger" role="alert">
							<?= $this->session->flashdata('error'); ?>
						</div>
					<?php endif; ?>

					<form id="loginForm" action="<?= site_url('auth/login'); ?>" method="POST" novalidate class="text-white">
						<div class="mb-3">
							<label for="email" class="form-label">Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autocomplete="email" required>
						</div>

						<div class="mb-4">
							<label for="password" class="form-label">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
						</div>

						<button type="submit" class="btn btn-info w-100 py-2 fw-semibold">
							Login
						</button>
					</form>

					<div class="text-center mt-4">
						<small class="text-secondary">
							Don't have an account?
							<a href="<?= site_url('register'); ?>" class="text-cyan text-decoration-none">Register</a>
						</small>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url('application/assets/js/validation.js'); ?>"></script>
	<script src="<?= base_url('application/assets/js/login.js'); ?>"></script>
</body>
</html>