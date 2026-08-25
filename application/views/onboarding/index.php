<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Onboarding - TaskLog</title>
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

		.onboarding-card {
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
	<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
		<div class="col-12 col-sm-10 col-md-7 col-lg-5">
			<div class="card onboarding-card border rounded-4 shadow">
				<div class="card-body p-4 p-md-5">
					<div class="text-center mb-4 text-white">
						<div class="text-cyan fs-1 mb-2">
							<i class="bi bi-person-badge"></i>
						</div>
						<h2 class="fw-bold">Complete Your Profile</h2>
						<p class="text-secondary mb-0">
							Just a few details before you get started.
						</p>
					</div>

					<?php if ($this->session->flashdata('error')): ?>
						<div class="alert alert-danger" role="alert">
							<?= $this->session->flashdata('error'); ?>
						</div>
					<?php endif; ?>

					<form id="onboardingForm" action="<?= site_url('onboarding'); ?>" method="POST" novalidate class="text-white">
						<div class="mb-3">
							<label for="employee_code" class="form-label">Employee Code</label>
							<input type="text" class="form-control" id="employee_code" name="employee_code" placeholder="Enter your employee code" required>
						</div>

						<div class="mb-3">
							<label for="department" class="form-label">Department</label>
							<input type="text" class="form-control" id="department" name="department" placeholder="Enter your department" required>
						</div>

						<div class="mb-4">
							<label for="designation" class="form-label">Designation</label>
							<input type="text" class="form-control" id="designation" name="designation" placeholder="Enter your designation" required>
						</div>

						<button type="submit" class="btn btn-info w-100 py-2 fw-semibold">
							Continue
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url('application/assets/js/validation.js'); ?>"></script>
</body>
</html>