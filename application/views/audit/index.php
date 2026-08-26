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

		.bg-surface {
			background-color: var(--tasklog-surface) !important;
		}

		.border-tasklog {
			border-color: var(--tasklog-border) !important;
		}

		.form-control,
		.form-select {
			background-color: var(--tasklog-bg);
			border-color: var(--tasklog-border);
			color: var(--tasklog-text);
		}

		.form-control:focus,
		.form-select:focus {
			background-color: var(--tasklog-bg);
			border-color: var(--tasklog-cyan);
			color: var(--tasklog-text);
			box-shadow: 0 0 0 0.2rem rgba(34, 211, 238, 0.15);
		}

		.form-select option {
			background-color: var(--tasklog-surface);
			color: var(--tasklog-text);
		}

		.audit-row:hover {
			background-color: rgba(34, 211, 238, 0.04);
		}

		.audit-values {
			max-width: 350px;
			white-space: pre-wrap;
			word-break: break-word;
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

			<div class="d-flex justify-content-between align-items-center mb-4">

				<div>
					<h1 class="fw-bold mb-1">
						Audit Logs
					</h1>

					<p class="text-secondary mb-0">
						Track activity and changes across TaskLog.
					</p>
				</div>

				<a href="<?= site_url('dashboard'); ?>" class="btn btn-outline-info fw-semibold">
					Go Back
				</a>

			</div>

			<div class="card bg-surface border-tasklog rounded-4 mb-4">

				<div class="card-body p-4">

					<div class="row g-3">

						<div class="col-md-3">

							<label class="form-label text-secondary">
								User
							</label>

							<select id="userFilter" class="form-select">

								<option value="">
									All Users
								</option>

								<?php foreach ($users as $user): ?>

									<option value="<?= $user->id; ?>">
										<?= html_escape($user->name); ?>
									</option>

								<?php endforeach; ?>

							</select>

						</div>

						<div class="col-md-3">

							<label class="form-label text-secondary">
								Action
							</label>

							<select id="actionFilter" class="form-select">

								<option value="">
									All Actions
								</option>

								<?php foreach ($actions as $action): ?>

									<option value="<?= html_escape($action->action); ?>">
										<?= html_escape($action->action); ?>
									</option>

								<?php endforeach; ?>

							</select>

						</div>

						<div class="col-md-2">

							<label class="form-label text-secondary">
								Entity
							</label>

							<select id="entityFilter" class="form-select">

								<option value="">
									All Entities
								</option>

								<?php foreach ($entities as $entity): ?>

									<option value="<?= html_escape($entity->entity); ?>">
										<?= html_escape($entity->entity); ?>
									</option>

								<?php endforeach; ?>

							</select>

						</div>

						<div class="col-md-2">

							<label class="form-label text-secondary">
								From
							</label>

							<input
								type="date"
								id="dateFrom"
								class="form-control"
							>

						</div>

						<div class="col-md-2">

							<label class="form-label text-secondary">
								To
							</label>

							<input
								type="date"
								id="dateTo"
								class="form-control"
							>

						</div>

					</div>

					<div class="d-flex justify-content-end gap-2 mt-4">

						<button
							type="button"
							id="resetFilters"
							class="btn btn-outline-light"
						>
							Reset
						</button>

						<button
							type="button"
							id="applyFilters"
							class="btn btn-info fw-semibold"
						>
							<i class="bi bi-funnel me-1"></i>
							Apply Filters
						</button>

					</div>

				</div>

			</div>

			<div class="card bg-surface border-tasklog rounded-4">

				<div class="card-body p-0">

					<div class="table-responsive">

						<table class="table table-dark table-borderless align-middle mb-0">

							<thead>

								<tr class="border-bottom border-tasklog">

									<th class="px-4 py-3">
										User
									</th>

									<th class="px-4 py-3">
										Action
									</th>

									<th class="px-4 py-3">
										Entity
									</th>

									<th class="px-4 py-3">
										Changes
									</th>

									<th class="px-4 py-3">
										IP Address
									</th>

									<th class="px-4 py-3 text-nowrap">
										Date
									</th>

								</tr>

							</thead>

							<tbody id="auditTableBody">

								<tr>

									<td colspan="6" class="text-center py-5 text-secondary">
										Loading...
									</td>

								</tr>

							</tbody>

						</table>

					</div>

					<div
						id="pagination"
						class="d-flex justify-content-center py-4"
					></div>

				</div>

			</div>

		</div>

	</main>

	<script>
		const auditFetchUrl = '<?= site_url('audit/fetch'); ?>';
	</script>

	<script src="<?= base_url('application/assets/js/audit.js'); ?>"></script>

</body>

</html>