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

		.audit-table {
			width: 100%;
			table-layout: fixed;
		}

		.audit-table th,
		.audit-table td {
			vertical-align: middle;
		}

		.audit-col-user {
			width: 18%;
		}

		.audit-col-action {
			width: 22%;
		}

		.audit-col-entity {
			width: 17%;
		}

		.audit-col-ip {
			width: 15%;
		}

		.audit-col-date {
			width: 18%;
		}

		.audit-col-view {
			width: 10%;
		}

		.audit-row:hover {
			background-color: rgba(34, 211, 238, 0.04);
		}

		.modal-content {
			background-color: var(--tasklog-surface);
			border-color: var(--tasklog-border);
		}

		.modal-header,
		.modal-footer {
			border-color: var(--tasklog-border);
		}

		.btn-close {
			filter: invert(1);
		}

		.audit-detail {
			background-color: var(--tasklog-bg);
			border: 1px solid var(--tasklog-border);
			border-radius: 0.5rem;
			padding: 1rem;
		}

		.audit-detail-label {
			font-size: 0.8rem;
			color: var(--tasklog-muted);
			margin-bottom: 0.25rem;
		}

		.audit-detail-value {
			color: var(--tasklog-text);
			word-break: break-word;
		}

		.audit-json {
			background-color: #080d18;
			border: 1px solid var(--tasklog-border);
			border-radius: 0.5rem;
			padding: 1rem;
			white-space: pre-wrap;
			word-break: break-word;
			font-family: monospace;
			font-size: 0.85rem;
			color: var(--tasklog-secondary);
			max-height: 300px;
			overflow-y: auto;
		}
	</style>
</head>

<body>
	<?php $this->load->view('components/navbar'); ?>
	
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

							<input type="date" id="dateFrom" class="form-control">

						</div>

						<div class="col-md-2">

							<label class="form-label text-secondary">
								To
							</label>

							<input type="date" id="dateTo" class="form-control">

						</div>

					</div>

					<div class="d-flex justify-content-end gap-2 mt-4">

						<button type="button" id="resetFilters" class="btn btn-outline-light">
							Reset
						</button>

						<button type="button" id="applyFilters" class="btn btn-info fw-semibold">
							<i class="bi bi-funnel me-1"></i>
							Apply Filters
						</button>

					</div>

				</div>

			</div>

			<div class="card bg-surface border-tasklog rounded-4">

				<div class="card-body p-0">

					<div class="table-responsive">

						<table class="table table-dark table-borderless align-middle mb-0 audit-table">

							<colgroup>
								<col class="audit-col-user">
								<col class="audit-col-action">
								<col class="audit-col-entity">
								<col class="audit-col-ip">
								<col class="audit-col-date">
								<col class="audit-col-view">
							</colgroup>

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
										IP Address
									</th>

									<th class="px-4 py-3 text-nowrap">
										Date
									</th>

									<th class="px-4 py-3 text-end">
										Action
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

					<div id="pagination" class="d-flex justify-content-center py-4"></div>

				</div>

			</div>

		</div>

	</main>

	<div class="modal fade" id="auditDetailModal" tabindex="-1" aria-labelledby="auditDetailModalLabel"
		aria-hidden="true">

		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content">

				<div class="modal-header">

					<div>
						<h5 class="modal-title fw-bold" id="auditDetailModalLabel">
							Audit Log Details
						</h5>

						<small class="text-secondary" id="auditDetailAction"></small>
					</div>

					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

				</div>

				<div class="modal-body">

					<div class="row g-3 mb-4">

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									User
								</div>

								<div class="audit-detail-value fw-semibold" id="auditDetailUser"></div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									Action
								</div>

								<div class="audit-detail-value" id="auditDetailActionValue"></div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									Entity
								</div>

								<div class="audit-detail-value" id="auditDetailEntity"></div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									Entity ID
								</div>

								<div class="audit-detail-value" id="auditDetailEntityId"></div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									IP Address
								</div>

								<div class="audit-detail-value" id="auditDetailIp"></div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="audit-detail">

								<div class="audit-detail-label">
									Date
								</div>

								<div class="audit-detail-value" id="auditDetailDate"></div>

							</div>

						</div>

						<div class="col-12">

							<div class="audit-detail">

								<div class="audit-detail-label">
									User Agent
								</div>

								<div class="audit-detail-value" id="auditDetailUserAgent"></div>

							</div>

						</div>

					</div>

					<div class="mb-4">

						<h6 class="fw-bold mb-2">
							Old Values
						</h6>

						<div id="auditOldValues" class="audit-json"></div>

					</div>

					<div>

						<h6 class="fw-bold mb-2">
							New Values
						</h6>

						<div id="auditNewValues" class="audit-json"></div>

					</div>

				</div>

				<div class="modal-footer">

					<button type="button" class="btn btn-info fw-semibold" data-bs-dismiss="modal">
						Close
					</button>

				</div>

			</div>

		</div>

	</div>

	<script>
		const auditFetchUrl = '<?= site_url('audit/fetch'); ?>';
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<script src="<?= base_url('application/assets/js/audit.js?v=2'); ?>"></script>

</body>

</html>