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
						Assigned Tasks
					</h1>

					<p class="text-secondary mb-0">
						View and manage tasks you have assigned to your employees.
					</p>

				</div>

				<button
					type="button"
					class="btn btn-info fw-semibold"
					data-bs-toggle="modal"
					data-bs-target="#createTaskModal">

					<i class="bi bi-plus-lg me-1"></i>
					Assign Task

				</button>

			</div>

			<div id="alertContainer"></div>

			<div class="card bg-surface border-tasklog rounded-4">

				<div class="card-body p-4">

					<div class="d-flex justify-content-between align-items-center mb-4">

						<div>

							<h4 class="fw-bold mb-1 text-white">
								My Assigned Tasks
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
								No assigned tasks
							</h5>

							<p class="text-secondary mb-4">
								You haven't assigned any tasks yet.
							</p>

							<button
								type="button"
								class="btn btn-info fw-semibold"
								data-bs-toggle="modal"
								data-bs-target="#createTaskModal">

								<i class="bi bi-plus-lg me-1"></i>
								Assign Your First Task

							</button>

						</div>

					<?php else: ?>

						<div class="table-responsive rounded-2">

							<table class="table table-dark table-borderless align-middle mb-0">

								<thead>

									<tr class="border-bottom border-tasklog">

										<th class="px-4 py-3 text-nowrap">
											Task
										</th>

										<th class="px-4 py-3 text-nowrap">
											Assigned To
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

										<th class="px-4 py-3 text-end"></th>

									</tr>

								</thead>

								<tbody id="taskTableBody">

									<?php foreach ($tasks as $task): ?>

										<tr
											class="task-row border-bottom border-tasklog"
											id="task-row-<?= $task->id; ?>">

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

												<div class="fw-semibold text-white">

													<?= html_escape(
														$task->assigned_to_name ?? 'Unknown'
													); ?>

												</div>

												<?php if (!empty($task->assigned_to_code)): ?>

													<small class="text-muted">
														<?= html_escape($task->assigned_to_code); ?>
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

												<?= $task->due_date
													? date('d M Y', strtotime($task->due_date))
													: 'No due date'; ?>

											</td>

											<td class="px-4 py-3 text-end">

												<div class="dropdown">

													<button
														class="btn btn-menu"
														type="button"
														data-bs-toggle="dropdown"
														aria-expanded="false">

														<i class="bi bi-three-dots-vertical fs-5"></i>

													</button>

													<ul class="dropdown-menu dropdown-menu-end">

														<li>

															<button
																type="button"
																class="dropdown-item view-task-btn"
																data-task-id="<?= $task->id; ?>">

																View

															</button>

														</li>

														<li>

															<button
																type="button"
																class="dropdown-item edit-task-btn"
																data-task-id="<?= $task->id; ?>">

																Edit

															</button>

														</li>

														<li>

															<hr class="dropdown-divider border-tasklog">

														</li>

														<li>

															<button
																type="button"
																class="dropdown-item text-danger delete-task-btn"
																data-task-id="<?= $task->id; ?>">

																Delete

															</button>

														</li>

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

	<!-- CREATE MODAL -->

	<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content bg-surface border-tasklog text-white">

				<div class="modal-header border-tasklog">

					<h5 class="modal-title fw-bold">
						Assign Task
					</h5>

					<button
						type="button"
						class="btn-close btn-close-white"
						data-bs-dismiss="modal">
					</button>

				</div>

				<form id="createTaskForm">

					<div class="modal-body">

						<div class="mb-3">

							<label class="form-label text-secondary">
								Task Title
							</label>

							<input
								type="text"
								class="form-control"
								name="title"
								maxlength="200"
								required>

						</div>

						<div class="mb-3">

							<label class="form-label text-secondary">
								Description
							</label>

							<textarea
								class="form-control"
								name="description"
								rows="4"></textarea>

						</div>

						<div class="mb-3">

							<label class="form-label text-secondary">
								Assign To
							</label>

							<select
								class="form-select"
								name="assigned_to"
								required>

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

								<input
									type="date"
									class="form-control"
									name="due_date">

							</div>

						</div>

					</div>

					<div class="modal-footer border-tasklog">

						<button
							type="button"
							class="btn btn-outline-light"
							data-bs-dismiss="modal">

							Cancel

						</button>

						<button
							type="submit"
							class="btn btn-info fw-semibold">

							Assign Task

						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

	<!-- VIEW MODAL -->

	<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content bg-surface border-tasklog text-white">

				<div class="modal-header border-tasklog">

					<h5 class="modal-title fw-bold">
						Task Details
					</h5>

					<button
						type="button"
						class="btn-close btn-close-white"
						data-bs-dismiss="modal">
					</button>

				</div>

				<div class="modal-body">

					<div id="viewTaskLoading" class="text-center py-5">

						<div class="spinner-border text-info"></div>

						<p class="text-secondary mt-3 mb-0">
							Loading task...
						</p>

					</div>

					<div id="viewTaskContent" class="d-none">

						<div class="mb-4">

							<div class="view-label">
								Task
							</div>

							<div class="view-value fs-4" id="viewTaskTitle"></div>

						</div>

						<div class="mb-4">

							<div class="view-label">
								Description
							</div>

							<div class="view-description" id="viewTaskDescription"></div>

						</div>

						<div class="row g-4">

							<div class="col-md-4">

								<div class="view-label">
									Priority
								</div>

								<div id="viewTaskPriority"></div>

							</div>

							<div class="col-md-4">

								<div class="view-label">
									Status
								</div>

								<div id="viewTaskStatus"></div>

							</div>

							<div class="col-md-4">

								<div class="view-label">
									Due Date
								</div>

								<div class="view-value" id="viewTaskDueDate"></div>

							</div>

						</div>

						<hr class="border-tasklog my-4">

						<div class="row g-4">

							<div class="col-md-6">

								<div class="view-label">
									Assigned To
								</div>

								<div class="view-value" id="viewTaskAssignedTo"></div>

							</div>

							<div class="col-md-6">

								<div class="view-label">
									Assigned By
								</div>

								<div class="view-value" id="viewTaskAssignedBy"></div>

							</div>

						</div>

						<hr class="border-tasklog my-4">

						<div class="row g-4">

							<div class="col-md-6">

								<div class="view-label">
									Created At
								</div>

								<div class="view-value" id="viewTaskCreatedAt"></div>

							</div>

							<div class="col-md-6">

								<div class="view-label">
									Last Updated
								</div>

								<div class="view-value" id="viewTaskUpdatedAt"></div>

							</div>

						</div>

					</div>

					<div id="viewTaskError" class="alert alert-danger d-none mb-0"></div>

				</div>

				<div class="modal-footer border-tasklog">

					<button
						type="button"
						class="btn btn-outline-light"
						data-bs-dismiss="modal">

						Close

					</button>

				</div>

			</div>

		</div>

	</div>

	<!-- EDIT MODAL -->

	<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content bg-surface border-tasklog text-white">

				<div class="modal-header border-tasklog">

					<h5 class="modal-title fw-bold">
						Edit Task
					</h5>

					<button
						type="button"
						class="btn-close btn-close-white"
						data-bs-dismiss="modal">
					</button>

				</div>

				<form id="editTaskForm">

					<input
						type="hidden"
						name="task_id"
						id="editTaskId">

					<div class="modal-body">

						<div class="mb-3">

							<label class="form-label text-secondary">
								Task Title
							</label>

							<input
								type="text"
								class="form-control"
								name="title"
								id="editTitle"
								maxlength="200"
								required>

						</div>

						<div class="mb-3">

							<label class="form-label text-secondary">
								Description
							</label>

							<textarea
								class="form-control"
								name="description"
								id="editDescription"
								rows="4"></textarea>

						</div>

						<div class="row g-3">

							<div class="col-md-4">

								<label class="form-label text-secondary">
									Priority
								</label>

								<select
									class="form-select"
									name="priority"
									id="editPriority">

									<option value="low">
										Low
									</option>

									<option value="medium">
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

								<select
									class="form-select"
									name="status"
									id="editStatus">

									<option value="pending">
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

								<input
									type="date"
									class="form-control"
									name="due_date"
									id="editDueDate">

							</div>

						</div>

					</div>

					<div class="modal-footer border-tasklog">

						<button
							type="button"
							class="btn btn-outline-info fw-semibold"
							data-bs-dismiss="modal">

							Cancel

						</button>

						<button
							type="submit"
							class="btn btn-info fw-semibold">

							Save Changes

						</button>

					</div>

				</form>

			</div>

		</div>

	</div>

	<!-- DELETE MODAL -->

	<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-hidden="true">

		<div class="modal-dialog modal-dialog-centered">

			<div class="modal-content bg-surface border-tasklog text-white">

				<div class="modal-header border-tasklog">

					<h5 class="modal-title fw-bold">
						Delete Task
					</h5>

					<button
						type="button"
						class="btn-close btn-close-white"
						data-bs-dismiss="modal">
					</button>

				</div>

				<div class="modal-body">

					<p class="text-secondary mb-0">
						Are you sure you want to delete this task?
						This action cannot be undone.
					</p>

				</div>

				<div class="modal-footer border-tasklog">

					<button
						type="button"
						class="btn btn-outline-info fw-semibold"
						data-bs-dismiss="modal">

						Cancel

					</button>

					<button
						type="button"
						class="btn btn-danger"
						id="confirmDeleteBtn">

						Delete Task

					</button>

				</div>

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