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
			--tasklog-text: #f8fafc;
			--tasklog-secondary: #cbd5e1;
			--tasklog-muted: #94a3b8;
		}

		body {
			background-color: var(--tasklog-bg);
			color: var(--tasklog-text);
		}

		.navbar {
			background-color: var(--tasklog-surface);
			border-color: var(--tasklog-border) !important;
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
			color: var(--tasklog-secondary) !important;
		}

		.text-muted {
			color: var(--tasklog-muted) !important;
		}

		.bg-surface {
			background-color: var(--tasklog-surface) !important;
		}

		.border-tasklog {
			border-color: var(--tasklog-border) !important;
		}

		.stat-card {
			transition: transform 0.2s ease, border-color 0.2s ease;
		}

		.stat-card:hover {
			transform: translateY(-3px);
			border-color: var(--tasklog-cyan) !important;
		}

		.task-row {
			transition: background-color 0.2s ease;
		}

		.task-row:hover {
			background-color: rgba(34, 211, 238, 0.04);
		}
	</style>
</head>
<body>

	<nav class="navbar navbar-dark border-bottom border-tasklog">
		<div class="container py-2">
			<a class="navbar-brand fw-bold fs-3" href="<?= site_url('dashboard'); ?>">
				Task<span class="text-cyan">Log</span>
			</a>

			<div class="d-flex align-items-center gap-3">
				<span class="text-secondary d-none d-md-block">
					<?= html_escape($this->session->userdata('name')); ?>
				</span>

				<a href="<?= site_url('auth/logout'); ?>" class="btn btn-info fw-semibold">
					Logout
				</a>
			</div>
		</div>
	</nav>

	<main>
		<div class="container py-5">

			<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
				<div>
					<h1 class="fw-bold mb-1">
						Welcome back, <?= html_escape($this->session->userdata('name')); ?>
					</h1>
					<p class="text-secondary mb-0">
						Here's an overview of your tasks.
					</p>
				</div>

				<a href="<?= site_url('tasks/create'); ?>" class="btn btn-info fw-semibold">
					<i class="bi bi-plus-lg me-1"></i>
					New Task
				</a>
			</div>

			<div class="row g-4 mb-5">
				<div class="col-md-4">
					<div class="card bg-surface border-tasklog rounded-4 stat-card h-100">
						<div class="card-body p-4">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<p class="text-secondary mb-1">Total Tasks</p>
									<h2 class="fw-bold mb-0 text-white"><?= $task_count; ?></h2>
								</div>

								<div class="fs-2 text-cyan">
									<i class="bi bi-list-task"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card bg-surface border-tasklog rounded-4 stat-card h-100">
						<div class="card-body p-4">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<p class="text-secondary mb-1">In Progress</p>
									<h2 class="fw-bold mb-0 text-white">
										<?= count(array_filter($tasks, function ($task) {
											return $task->status === 'in_progress';
										})); ?>
									</h2>
								</div>

								<div class="fs-2 text-info">
									<i class="bi bi-hourglass-split"></i>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="card bg-surface border-tasklog rounded-4 stat-card h-100">
						<div class="card-body p-4">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<p class="text-secondary mb-1">Completed</p>
									<h2 class="fw-bold mb-0 text-white">
										<?= count(array_filter($tasks, function ($task) {
											return $task->status === 'completed';
										})); ?>
									</h2>
								</div>

								<div class="fs-2 text-success">
									<i class="bi bi-check2-circle"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card bg-surface border-tasklog rounded-4">
				<div class="card-body p-4">

					<div class="d-flex justify-content-between align-items-center mb-4">
						<div>
							<h4 class="fw-bold mb-1 text-white">My Tasks</h4>
							<p class="text-secondary mb-0">
								Your recently assigned tasks.
							</p>
						</div>

						<a href="<?= site_url('tasks'); ?>" class="text-cyan text-decoration-none">
							View All
						</a>
					</div>

					<?php if (empty($tasks)): ?>

						<div class="text-center py-5">
							<div class="fs-1 text-muted mb-3">
								<i class="bi bi-clipboard-x"></i>
							</div>

							<h5 class="fw-semibold text-white">
								No tasks yet
							</h5>

							<p class="text-secondary mb-4">
								You don't have any tasks assigned to you.
							</p>

							<a href="<?= site_url('tasks/create'); ?>" class="btn btn-info fw-semibold">
								<i class="bi bi-plus-lg me-1"></i>
								Create Your First Task
							</a>
						</div>

					<?php else: ?>

						<div class="table-responsive rounded-2 overflow-hidden">
							<table class="table table-dark table-borderless align-middle mb-0">
								<thead>
									<tr class="border-bottom border-tasklog">
										<th>Task</th>
										<th>Priority</th>
										<th>Status</th>
										<th>Due Date</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach (array_slice($tasks, 0, 5) as $task): ?>
										<tr class="task-row border-bottom border-tasklog">
											<td class="py-3">
												<div class="fw-semibold">
													<?= html_escape($task->title); ?>
												</div>

												<?php if (!empty($task->description)): ?>
													<small class="text-muted">
														<?= html_escape($task->description); ?>
													</small>
												<?php endif; ?>
											</td>

											<td>
												<?php
												$priority_classes = [
													'low' => 'text-bg-secondary',
													'medium' => 'text-bg-info',
													'high' => 'text-bg-warning',
													'critical' => 'text-bg-danger'
												];
												?>

												<span class="badge <?= $priority_classes[$task->priority] ?? 'text-bg-secondary'; ?>">
													<?= ucfirst($task->priority); ?>
												</span>
											</td>

											<td>
												<?php
												$status_classes = [
													'pending' => 'text-bg-warning',
													'in_progress' => 'text-bg-info',
													'completed' => 'text-bg-success',
													'cancelled' => 'text-bg-secondary'
												];
												?>

												<span class="badge <?= $status_classes[$task->status] ?? 'text-bg-secondary'; ?>">
													<?= ucwords(str_replace('_', ' ', $task->status)); ?>
												</span>
											</td>

											<td class="text-secondary">
												<?= $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No due date'; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

					<?php endif; ?>

				</div>
			</div>

		</div>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>