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

		.stat-card,
		.action-card {
			transition: transform 0.2s ease, border-color 0.2s ease;
		}

		.stat-card:hover,
		.action-card:hover {
			transform: translateY(-3px);
			border-color: var(--tasklog-cyan) !important;
		}

		.task-row {
			transition: background-color 0.2s ease;
		}

		.task-row:hover {
			background-color: rgba(34, 211, 238, 0.04);
		}

		.dropdown-menu {
			background-color: var(--tasklog-surface);
			border-color: var(--tasklog-border);
		}

		.dropdown-item {
			color: var(--tasklog-secondary);
		}

		.dropdown-item:hover {
			background-color: rgba(34, 211, 238, 0.08);
			color: var(--tasklog-text);
		}

		.form-control,
		.form-select {
			background-color: #0b1120;
			border-color: var(--tasklog-border);
			color: var(--tasklog-text);
		}

		.form-control:focus,
		.form-select:focus {
			background-color: #0b1120;
			border-color: var(--tasklog-cyan);
			color: var(--tasklog-text);
			box-shadow: 0 0 0 0.2rem rgba(34, 211, 238, 0.15);
		}

		.form-control::placeholder {
			color: var(--tasklog-muted);
		}

		.form-select option {
			background-color: var(--tasklog-surface);
			color: var(--tasklog-text);
		}

		.form-control[type="date"]::-webkit-calendar-picker-indicator {
			filter: invert(1);
		}

		.modal-content {
			box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.4);
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
						<?php if ($role_id == 1): ?>
							Manage your organization.
						<?php else: ?>
							Here's an overview of your tasks.
						<?php endif; ?>
					</p>

				</div>
				<?php if ($role_id != 1): ?>
					<a href="<?= site_url('tasks'); ?>" class="btn btn-info fw-semibold">
						Go to Tasks
					</a>
				<?php endif; ?>
			</div>

			<div id="alertContainer"></div>

			<?php if ($role_id == 1): ?>

				<div class="row g-4">

					<div class="col-md-4">

						<a href="<?= site_url('admin/employees'); ?>" class="text-decoration-none">

							<div class="card bg-surface border-tasklog rounded-4 action-card h-100">

								<div class="card-body p-4">

									<div class="fs-2 text-cyan mb-3">
										<i class="bi bi-people"></i>
									</div>

									<h4 class="fw-bold text-white">
										Employees
									</h4>

									<p class="text-secondary mb-0">
										View employees and manage their assigned RAs.
									</p>

								</div>

							</div>

						</a>

					</div>

					<div class="col-md-4">

						<a href="<?= site_url('audit'); ?>" class="text-decoration-none">

							<div class="card bg-surface border-tasklog rounded-4 action-card h-100">

								<div class="card-body p-4">

									<div class="fs-2 text-info mb-3">
										<i class="bi bi-journal-text"></i>
									</div>

									<h4 class="fw-bold text-white">
										Audit Logs
									</h4>

									<p class="text-secondary mb-0">
										View system activity and track important changes.
									</p>

								</div>

							</div>

						</a>

					</div>


				</div>

			<?php else: ?>

				<div class="row g-4 mb-5">

					<div class="col-md-4">

						<div class="card bg-surface border-tasklog rounded-4 stat-card h-100">

							<div class="card-body p-4">

								<div class="d-flex justify-content-between align-items-center">

									<div>

										<p class="text-secondary mb-1">
											Total Tasks
										</p>

										<h2 class="fw-bold mb-0 text-white">
											<?= $task_count; ?>
										</h2>

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

										<p class="text-secondary mb-1">
											In Progress
										</p>

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

										<p class="text-secondary mb-1">
											Completed
										</p>

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

								<h4 class="fw-bold mb-1 text-white">
									My Tasks
								</h4>

								<p class="text-secondary mb-0">
									Tasked assigned to you.
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

								<button type="button" class="btn btn-info fw-semibold" data-bs-toggle="modal"
									data-bs-target="#createTaskModal">

									<i class="bi bi-plus-lg me-1"></i>
									Create Your First Task

								</button>

							</div>

						<?php else: ?>

							<div class="table-responsive rounded-3 overflow-hidden">

								<table class="table table-dark table-borderless align-middle mb-0">

									<thead>

										<tr class="border-bottom border-tasklog">

											<th class="px-4">
												Task
											</th>

											<th class="px-4">
												Priority
											</th>

											<th class="px-4">
												Status
											</th>

											<th class="px-4 text-nowrap">
												Due Date
											</th>

										</tr>

									</thead>

									<tbody>

										<?php foreach (array_slice($tasks, 0, 5) as $task): ?>

											<tr class="task-row border-bottom border-tasklog">

												<td class="px-4">

													<div class="fw-semibold">
														<?= html_escape($task->title); ?>
													</div>

													<?php if (!empty($task->description)): ?>

														<small class="text-muted">
															<?= html_escape($task->description); ?>
														</small>

													<?php endif; ?>

												</td>

												<td class="px-4">

													<?php
													$priority_classes = [
														'low' => 'text-bg-secondary',
														'medium' => 'text-bg-info',
														'high' => 'text-bg-warning',
														'critical' => 'text-bg-danger'
													];
													?>

													<span
														class="badge <?= $priority_classes[$task->priority] ?? 'text-bg-secondary'; ?>">
														<?= ucfirst($task->priority); ?>
													</span>

												</td>

												<td class="px-4">

													<?php
													$status_classes = [
														'pending' => 'text-bg-warning',
														'in_progress' => 'text-bg-info',
														'completed' => 'text-bg-success',
														'cancelled' => 'text-bg-secondary'
													];
													?>

													<span
														class="badge <?= $status_classes[$task->status] ?? 'text-bg-secondary'; ?>">
														<?= ucwords(str_replace('_', ' ', $task->status)); ?>
													</span>

												</td>

												<td class="px-4 text-secondary text-nowrap">

													<?= $task->due_date
														? date('d M Y', strtotime($task->due_date))
														: 'No due date'; ?>

												</td>

											</tr>

										<?php endforeach; ?>

									</tbody>

								</table>

							</div>

						<?php endif; ?>

					</div>

				</div>

				<?php if ($role_id == 2): ?>

					<div class="card bg-surface border-tasklog rounded-4 mt-4">

						<div class="card-body p-4">

							<div class="d-flex justify-content-between align-items-center mb-4">

								<div>
									<h4 class="fw-bold mb-1 text-white">
										Tasks I've Assigned
									</h4>

									<p class="text-secondary mb-0">
										Tasks assigned to employees under your supervision.
									</p>
								</div>

								<a href="<?= site_url('tasks/assigned'); ?>" class="text-cyan text-decoration-none">
									View All
								</a>

							</div>

							<?php if (empty($assigned_tasks)): ?>

								<div class="text-center py-5">

									<div class="fs-1 text-muted mb-3">
										<i class="bi bi-clipboard-x"></i>
									</div>

									<h5 class="fw-semibold text-white">
										No assigned tasks
									</h5>

									<p class="text-secondary mb-0">
										You haven't assigned any tasks to other employees.
									</p>

								</div>

							<?php else: ?>

								<div class="table-responsive rounded-3 overflow-hidden">

									<table class="table table-dark table-borderless align-middle mb-0">

										<thead>
											<tr class="border-bottom border-tasklog">

												<th class="px-4">
													Task
												</th>

												<th class="px-4">
													Assigned To
												</th>

												<th class="px-4">
													Priority
												</th>

												<th class="px-4">
													Status
												</th>

												<th class="px-4 text-nowrap">
													Due Date
												</th>

											</tr>
										</thead>

										<tbody>

											<?php foreach (array_slice($assigned_tasks, 0, 5) as $task): ?>

												<tr class="task-row border-bottom border-tasklog">

													<td class="px-4">

														<div class="fw-semibold">
															<?= html_escape($task->title); ?>
														</div>

														<?php if (!empty($task->description)): ?>

															<small class="text-muted">
																<?= html_escape($task->description); ?>
															</small>

														<?php endif; ?>

													</td>

													<td class="px-4">

														<div class="fw-semibold">
															<?= html_escape($task->assigned_to_name); ?>
														</div>

														<?php if (!empty($task->assigned_to_code)): ?>

															<small class="text-muted">
																<?= html_escape($task->assigned_to_code); ?>
															</small>

														<?php endif; ?>

													</td>

													<td class="px-4">

														<?php
														$priority_classes = [
															'low' => 'text-bg-secondary',
															'medium' => 'text-bg-info',
															'high' => 'text-bg-warning',
															'critical' => 'text-bg-danger'
														];
														?>

														<span
															class="badge <?= $priority_classes[$task->priority] ?? 'text-bg-secondary'; ?>">
															<?= ucfirst($task->priority); ?>
														</span>

													</td>

													<td class="px-4">

														<?php
														$status_classes = [
															'pending' => 'text-bg-warning',
															'in_progress' => 'text-bg-info',
															'completed' => 'text-bg-success',
															'cancelled' => 'text-bg-secondary'
														];
														?>

														<span
															class="badge <?= $status_classes[$task->status] ?? 'text-bg-secondary'; ?>">
															<?= ucwords(str_replace('_', ' ', $task->status)); ?>
														</span>

													</td>

													<td class="px-4 text-secondary text-nowrap">

														<?= $task->due_date
															? date('d M Y', strtotime($task->due_date))
															: 'No due date'; ?>

													</td>

												</tr>

											<?php endforeach; ?>

										</tbody>

									</table>

								</div>

							<?php endif; ?>

						</div>

					</div>

				<?php endif; ?>
			<?php endif; ?>

		</div>

	</main>


	<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content bg-surface border-tasklog text-white">

				<div class="modal-header border-tasklog">

					<h5 class="modal-title fw-bold">
						Assign Task
					</h5>

					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

				</div>

				<form id="createTaskForm">

					<div class="modal-body">

						<div class="mb-3">

							<label class="form-label text-secondary">
								Task Title
							</label>

							<input type="text" class="form-control" name="title" maxlength="200" required>

						</div>

						<div class="mb-3">

							<label class="form-label text-secondary">
								Description
							</label>

							<textarea class="form-control" name="description" rows="4"></textarea>

						</div>

						<div class="mb-3">

							<label class="form-label text-secondary">
								Assign To
							</label>

							<select class="form-select" name="assigned_to" required>

								<option value="">
									Select employee
								</option>

								<?php if (!empty($employees)): ?>

									<?php foreach ($employees as $employee): ?>

										<option value="<?= $employee->id; ?>">
											<?= html_escape($employee->name); ?>
											<?php if (!empty($employee->employee_code)): ?>
												(<?= html_escape($employee->employee_code); ?>)
											<?php endif; ?>
										</option>

									<?php endforeach; ?>

								<?php endif; ?>

							</select>

						</div>

						<div class="row g-3">

							<div class="col-md-4">

								<label class="form-label text-secondary">
									Priority
								</label>

								<select class="form-select" name="priority">

									<option value="low">
										Low
									</option>

									<option value="medium" selected>
										Medium
									</option>

									<option value="high">
										High
									</option>

									<option value="critical">
										Critical
									</option>

								</select>

							</div>

							<div class="col-md-4">

								<label class="form-label text-secondary">
									Status
								</label>

								<select class="form-select" name="status">

									<option value="pending" selected>
										Pending
									</option>

									<option value="in_progress">
										In Progress
									</option>

									<option value="completed">
										Completed
									</option>

									<option value="cancelled">
										Cancelled
									</option>

								</select>

							</div>

							<div class="col-md-4">

								<label class="form-label text-secondary">
									Due Date
								</label>

								<input type="date" class="form-control" name="due_date">

							</div>

						</div>

					</div>

					<div class="modal-footer border-tasklog">

						<button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">

							Cancel

						</button>

						<button type="submit" class="btn btn-info fw-semibold">

							Assign Task

						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		const taskUrls = {
			create: '<?= site_url('tasks/create'); ?>',
			view: '<?= site_url('tasks/view/'); ?>',
			edit: '<?= site_url('tasks/edit/'); ?>',
			delete: '<?= site_url('tasks/delete/'); ?>'
		};
	</script>

	<script src="<?= base_url('application/assets/js/tasks.js'); ?>"></script>

</body>

</html>