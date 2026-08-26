<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employees - TaskLog</title>

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

        .form-select {
            background-color: var(--tasklog-bg);
            border-color: var(--tasklog-border);
            color: var(--tasklog-text);
        }

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

        .employee-row {
            transition: background-color 0.2s ease;
        }

        .employee-row:hover {
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
                        Employees
                    </h1>

                    <p class="text-secondary mb-0">
                        Manage employee reporting assignments.
                    </p>
                </div>

                <a href="<?= site_url('dashboard'); ?>" class="btn btn-outline-light">
                    Back to Dashboard
                </a>

            </div>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= html_escape($this->session->flashdata('success')); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?= html_escape($this->session->flashdata('error')); ?>
                </div>
            <?php endif; ?>

            <div class="card bg-surface border-tasklog rounded-4">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div>
                            <h4 class="fw-bold mb-1 text-white">
                                Employee Reporting
                            </h4>

                            <p class="text-secondary mb-0">
                                <?= count($employees); ?>
                                employee<?= count($employees) !== 1 ? 's' : ''; ?>
                                found
                            </p>
                        </div>

                    </div>

                    <?php if (empty($employees)): ?>

                        <div class="text-center py-5">

                            <div class="fs-1 text-muted mb-3">
                                <i class="bi bi-people"></i>
                            </div>

                            <h5 class="fw-semibold text-white">
                                No employees found
                            </h5>

                            <p class="text-secondary mb-0">
                                There are currently no employees to manage.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="table-responsive rounded-3 overflow-hidden">

                            <table class="table table-dark table-borderless align-middle mb-0">

                                <thead>
                                    <tr class="border-bottom border-tasklog">

                                        <th class="px-4 py-3 text-nowrap">
                                            Employee
                                        </th>

                                        <th class="px-4 py-3 text-nowrap">
                                            Department
                                        </th>

                                        <th class="px-4 py-3 text-nowrap">
                                            Designation
                                        </th>

                                        <th class="px-4 py-3 text-nowrap">
                                            Reporting RA
                                        </th>

                                        <th class="px-4 py-3 text-nowrap text-end">
                                            Action
                                        </th>

                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($employees as $employee): ?>

                                        <tr class="employee-row border-bottom border-tasklog">

                                            <td class="px-4 py-3">

                                                <div class="fw-semibold text-white">
                                                    <?= html_escape($employee->name); ?>
                                                </div>

                                                <small class="text-muted">
                                                    <?= html_escape($employee->employee_code); ?>
                                                </small>

                                            </td>

                                            <td class="px-4 py-3 text-secondary">
                                                <?= !empty($employee->department)
                                                    ? html_escape($employee->department)
                                                    : '—'; ?>
                                            </td>

                                            <td class="px-4 py-3 text-secondary">
                                                <?= !empty($employee->designation)
                                                    ? html_escape($employee->designation)
                                                    : '—'; ?>
                                            </td>

                                            <td class="px-4 py-3">

                                                <?php if (!empty($employee->ra_name)): ?>

                                                    <div class="text-white">
                                                        <?= html_escape($employee->ra_name); ?>
                                                    </div>

                                                    <?php if (!empty($employee->ra_employee_code)): ?>
                                                        <small class="text-muted">
                                                            <?= html_escape($employee->ra_employee_code); ?>
                                                        </small>
                                                    <?php endif; ?>

                                                <?php else: ?>

                                                    <span class="text-muted">
                                                        Not assigned
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <td class="px-4 py-3 text-end">

                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#assignRaModal" data-employee-id="<?= $employee->id; ?>"
                                                    data-employee-name="<?= html_escape($employee->name); ?>"
                                                    data-current-ra="<?= !empty($employee->ra_id) ? $employee->ra_id : ''; ?>">
                                                    <i class="bi bi-person-gear me-1"></i>
                                                    Assign RA
                                                </button>

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

    <div class="modal fade" id="assignRaModal" tabindex="-1" aria-labelledby="assignRaModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <form action="<?= site_url('admin/assign_ra'); ?>" method="POST">

                    <div class="modal-header">

                        <div>
                            <h5 class="modal-title fw-bold" id="assignRaModalLabel">
                                Assign RA
                            </h5>

                            <small class="text-secondary" id="employeeName">
                            </small>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="employee_id" id="employeeId">

                        <div class="mb-3">

                            <label for="ra_id" class="form-label text-secondary">
                                Reporting Authority
                            </label>

                            <select class="form-select" name="ra_id" id="ra_id" required>

                                <option value="">
                                    Select RA
                                </option>

                                <?php foreach ($ras as $ra): ?>

                                    <option value="<?= $ra->id; ?>">
                                        <?= html_escape($ra->name); ?>

                                        <?php if (!empty($ra->employee_code)): ?>
                                            (<?= html_escape($ra->employee_code); ?>)
                                        <?php endif; ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <div class="invalid-feedback">
                                Please select an RA.
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-info fw-semibold">
                            <i class="bi bi-check-lg me-1"></i>
                            Save Assignment
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const assignRaModal = document.getElementById('assignRaModal');

        assignRaModal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            const employeeId = button.getAttribute('data-employee-id');
            const employeeName = button.getAttribute('data-employee-name');
            const currentRa = button.getAttribute('data-current-ra');

            document.getElementById('employeeId').value = employeeId;
            document.getElementById('employeeName').textContent = employeeName;

            const raSelect = document.getElementById('ra_id');

            raSelect.value = currentRa || '';
        });
    </script>

</body>

</html>