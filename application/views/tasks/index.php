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

		.dropdown-item.text-danger:hover {
			background-color: rgba(220, 53, 69, 0.1);
			color: #dc3545 !important;
		}

		.btn-menu {
			color: var(--tasklog-secondary);
			border: none;
			background: transparent;
		}

		.btn-menu:hover {
			color: var(--tasklog-cyan);
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

		.view-label {
			color: var(--tasklog-muted);
			font-size: 0.85rem;
			margin-bottom: 0.25rem;
		}

		.view-value {
			color: var(--tasklog-text);
			font-weight: 500;
		}

		.view-description {
			background-color: #0b1120;
			border: 1px solid var(--tasklog-border);
			border-radius: 0.5rem;
			padding: 1rem;
			color: var(--tasklog-secondary);
			min-height: 80px;
			white-space: pre-wrap;
		}
	</style>
</head>

<body>
	<?php $this->load->view('components/navbar'); ?>

	<main>

		<div class="container py-5">

			<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">

				<div>
					<h1 class="fw-bold mb-1">My Tasks</h1>

					<p class="text-secondary mb-0">
						View and manage all your tasks.
					</p>
				</div>

				<button type="button" class="btn btn-info fw-semibold" data-bs-toggle="modal"
					data-bs-target="#createTaskModal">
					<i class="bi bi-plus-lg me-1"></i>
					New Task
				</button>

			</div>

			<div id="alertContainer"></div>

			<div class="card bg-surface border-tasklog rounded-4">

				<div class="card-body p-4">

					<div class="d-flex justify-content-between align-items-center mb-4">

						<div>
							<h4 class="fw-bold mb-1 text-white">
								All Tasks
							</h4>

							<p class="text-secondary mb-0">
								<?= count($tasks); ?>
								task<?= count($tasks) !== 1 ? 's' : ''; ?>
								found
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

							<button type="button" class="btn btn-info fw-semibold" data-bs-toggle="modal"
								data-bs-target="#createTaskModal">
								<i class="bi bi-plus-lg me-1"></i>
								Create Your First Task
							</button>

						</div>

					<?php else: ?>

						<div>

							<table class="table table-dark table-borderless align-middle mb-0">

								<thead>

									<tr class="border-bottom border-tasklog">

										<th class="px-4 py-3 text-nowrap">
											Task
										</th>

										<th class="px-4 py-3 text-nowrap">
											Priority
										</th>

										<th class="px-4 py-3 text-nowrap">
											Status
										</th>

										<th class="px-4 py-3 text-nowrap">
											Due Date
										</th>

										<th class="px-4 py-3 text-end">
										</th>

									</tr>

								</thead>

								<tbody id="taskTableBody">

									<?php foreach ($tasks as $task): ?>

										<?php
										$employee_id = $this->Employee_model
											->get_employee_by_user_id(
												$this->session->userdata('user_id')
											)->id;

										$can_edit =
											(int) $task->assigned_to === (int) $employee_id
											|| (int) $task->assigned_by === (int) $employee_id;

										$can_delete =
											(int) $task->assigned_to === (int) $employee_id
											|| (int) $task->assigned_by === (int) $employee_id;
										?>

										<tr class="task-row border-bottom border-tasklog" id="task-row-<?= $task->id; ?>">

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

												<span
													class="badge <?= $priority_classes[$task->priority] ?? 'text-bg-secondary'; ?>">
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

												<span
													class="badge <?= $status_classes[$task->status] ?? 'text-bg-secondary'; ?>">
													<?= ucwords(str_replace('_', ' ', $task->status)); ?>
												</span>

											</td>

											<td class="px-4 py-3 text-secondary text-nowrap">
												<?= $task->due_date
													? date('d M Y', strtotime($task->due_date))
													: 'No due date'; ?>
											</td>

											<td class="px-4 py-3 text-end">

												<div class="dropdown">

													<button class="btn btn-menu" type="button" data-bs-toggle="dropdown"
														aria-expanded="false">
														<i class="bi bi-three-dots-vertical fs-5"></i>
													</button>

													<ul class="dropdown-menu dropdown-menu-end">

														<li>

															<button type="button" class="dropdown-item view-task-btn"
																data-task-id="<?= $task->id; ?>">
																View
															</button>

														</li>

														<?php if ($can_edit): ?>

															<li>
																<button type="button" class="dropdown-item edit-task-btn"
																	data-task-id="<?= $task->id; ?>">
																	Edit
																</button>
															</li>

														<?php endif; ?>

														<?php if ($can_delete): ?>

															<li>
																<hr class="dropdown-divider border-tasklog">
															</li>

															<li>
																<button type="button"
																	class="dropdown-item text-danger delete-task-btn"
																	data-task-id="<?= $task->id; ?>">
																	Delete
																</button>
															</li>

														<?php endif; ?>

													</ul>

												</div>

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

	<?php $this->load->view('tasks/modals'); ?>

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