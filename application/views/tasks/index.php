<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>My Tasks - TaskLog</title>
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
					<h1 class="fw-bold mb-1">My Tasks</h1>
					<p class="text-secondary mb-0">
						View and manage all your tasks.
					</p>
				</div>

				<a href="<?= site_url('tasks/create'); ?>" class="btn btn-info fw-semibold">
					<i class="bi bi-plus-lg me-1"></i>
					New Task
				</a>
			</div>

			<div class="card bg-surface border-tasklog rounded-4">
				<div class="card-body p-4">

					<div class="d-flex justify-content-between align-items-center mb-4">
						<div>
							<h4 class="fw-bold mb-1 text-white">All Tasks</h4>
							<p class="text-secondary mb-0">
								<?= count($tasks); ?> task<?= count($tasks) !== 1 ? 's' : ''; ?> found
							</p>
						</div>
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
										<th class="px-4 py-3 text-nowrap">Task</th>
										<th class="px-4 py-3 text-nowrap">Priority</th>
										<th class="px-4 py-3 text-nowrap">Status</th>
										<th class="px-4 py-3 text-nowrap">Due Date</th>
										<th class="px-4 py-3 text-nowrap text-end">Action</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach ($tasks as $task): ?>
										<tr class="task-row border-bottom border-tasklog">

											<td class="px-4 py-3">
												<div class="fw-semibold text-white">
													<?= html_escape($task->title); ?>
												</div>

												<?php if (!empty($task->description)): ?>
													<small class="text-muted">
														<?= html_escape($task->description); ?>
													</small>
												<?php endif; ?>
											</td>

											<td class="px-4 py-3">
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

											<td class="px-4 py-3">
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

											<td class="px-4 py-3 text-secondary text-nowrap">
												<?= $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No due date'; ?>
											</td>

											<td class="px-4 py-3 text-end">
												<a href="<?= site_url('tasks/view/' . $task->id); ?>" class="btn btn-sm btn-info">
													View
												</a>
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