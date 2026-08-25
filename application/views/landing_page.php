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
			--tasklog-blue: #3b82f6;
			--tasklog-text: #f8fafc;
			--tasklog-text-secondary: #cbd5e1;
			--tasklog-text-muted: #94a3b8;
		}

		body {
			background-color: var(--tasklog-bg);
			color: var(--tasklog-text);
		}

		.navbar {
			background-color: var(--tasklog-bg);
		}

		.navbar-brand {
			color: var(--tasklog-text);
		}

		.navbar-brand:hover {
			color: var(--tasklog-text);
		}

		.text-cyan {
			color: var(--tasklog-cyan) !important;
		}

		.text-secondary {
			color: var(--tasklog-text-secondary) !important;
		}

		.text-muted {
			color: var(--tasklog-text-muted) !important;
		}

		.bg-surface {
			background-color: var(--tasklog-surface) !important;
		}

		.border-tasklog {
			border-color: var(--tasklog-border) !important;
		}

		.hero-card {
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
		}

		.feature-card {
			transition: border-color 0.2s ease, transform 0.2s ease;
		}

		.feature-card:hover {
			border-color: var(--tasklog-cyan) !important;
			transform: translateY(-3px);
		}

		.feature-icon {
			font-size: 1.75rem;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-dark border-bottom border-tasklog">
		<div class="container py-3">
			<a class="navbar-brand fw-bold fs-2" href="<?= base_url(); ?>">Task<span class="text-cyan">Log</span></a>
			<a href="<?= base_url('login'); ?>" class="btn btn-info fw-semibold">Get Started</a>
		</div>
	</nav>

	<main>
		<section class="min-vh-100 d-flex align-items-center">
			<div class="container py-5">
				<div class="row align-items-center g-5">
					<div class="col-lg-6">
						<span class="badge rounded-pill bg-dark border border-info text-info mb-4 px-3 py-2">Simple task management</span>
						<h1 class="display-2 fw-bold lh-1 mb-4">Work smarter.<br><span class="text-cyan">Stay on track.</span></h1>
						<p class="lead text-secondary mb-4">TaskLog gives employees and reporting authorities a simple way to assign, manage, and track work from one place.</p>
						<a href="<?= base_url('login'); ?>" class="btn btn-tasklog btn-lg px-4">Get Started</a>
					</div>

					<div class="col-lg-6">
						<div class="card bg-surface border-tasklog rounded-4 hero-card">
							<div class="card-body p-4">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<div>
										<h5 class="mb-1 text-light">My Tasks</h5>
										<small class="text-muted">Today's overview</small>
									</div>
									<span class="badge bg-dark text-info border border-info">6 Tasks</span>
								</div>

								<div class="d-flex justify-content-between align-items-center py-3 border-bottom border-tasklog">
									<div>
										<div class="fw-semibold text-light">API Documentation</div>
										<small class="text-muted">Due today</small>
									</div>
									<span class="badge text-bg-info">In Progress</span>
								</div>

								<div class="d-flex justify-content-between align-items-center py-3 border-bottom border-tasklog">
									<div>
										<div class="fw-semibold text-light">Database Review</div>
										<small class="text-muted">Due tomorrow</small>
									</div>
									<span class="badge text-bg-warning">Pending</span>
								</div>

								<div class="d-flex justify-content-between align-items-center py-3">
									<div>
										<div class="fw-semibold text-light">Code Review</div>
										<small class="text-muted">Completed today</small>
									</div>
									<span class="badge text-bg-success">Completed</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="py-5 border-top border-tasklog">
			<div class="container py-5">
				<div class="text-center mb-5">
					<h2 class="fw-bold text-light">Everything in one place</h2>
					<p class="text-secondary">Keep tasks organized and work visible.</p>
				</div>

				<div class="row g-4">
					<div class="col-md-4">
						<div class="card h-100 bg-surface border-tasklog rounded-4 feature-card">
							<div class="card-body p-4">
								<div class="feature-icon text-cyan mb-3"><i class="bi bi-check2-square"></i></div>
								<h5 class="fw-semibold text-light">Task Management</h5>
								<p class="text-secondary mb-0">Create, assign, prioritize, and track tasks from one simple workspace.</p>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="card h-100 bg-surface border-tasklog rounded-4 feature-card">
							<div class="card-body p-4">
								<div class="feature-icon text-cyan mb-3"><i class="bi bi-person-check"></i></div>
								<h5 class="fw-semibold text-light">RA Assignment</h5>
								<p class="text-secondary mb-0">Reporting authorities can assign tasks directly to their employees.</p>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="card h-100 bg-surface border-tasklog rounded-4 feature-card">
							<div class="card-body p-4">
								<div class="feature-icon text-cyan mb-3"><i class="bi bi-clock-history"></i></div>
								<h5 class="fw-semibold text-light">Activity Tracking</h5>
								<p class="text-secondary mb-0">Keep a history of task assignments and updates through audit logging.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<footer class="border-top border-tasklog py-4">
		<div class="container text-center">
			<small class="text-muted">&copy; <?= date('Y'); ?> TaskLog</small>
		</div>
	</footer>
</body>
</html>