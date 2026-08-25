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

        .form-control::placeholder {
            color: #64748b;
        }

        .form-select option {
            background-color: var(--tasklog-surface);
            color: var(--tasklog-text);
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

            <div class=" d-flex justify-content-end mb-4">
                <a href="<?= site_url('dashboard'); ?>" class="btn btn-info fw-semibold">
                    Back to Dashboard
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="card bg-surface border-tasklog rounded-4">
                        <div class="card-body p-4 p-md-5">

                            <div class="mb-4">
                                <h2 class="fw-bold mb-1 text-secondary">Create Task</h2>
                                <p class="text-secondary mb-0">
                                    Create a task and add it to your task list.
                                </p>
                            </div>

                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    <?= html_escape($this->session->flashdata('error')); ?>
                                </div>
                            <?php endif; ?>

                            <form id="createTaskForm" action="<?= site_url('tasks/create'); ?>" method="POST"
                                novalidate>

                                <div class="mb-3">
                                    <label for="title" class="form-label text-secondary">
                                        Task Title
                                    </label>

                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter task title" maxlength="200" required>

                                    <div class="invalid-feedback">
                                        Task title is required.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label text-secondary">
                                        Description 
                                        <span class="text-secondary">(Optional)</span>
                                    </label>

                                    <textarea class="form-control" id="description" name="description" rows="5"
                                        placeholder="Describe the task..."></textarea>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="priority" class="form-label text-secondary">
                                            Priority
                                        </label>

                                        <select class="form-select" id="priority" name="priority">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 text-secondary">
                                        <label for="due_date" class="form-label">
                                            Due Date
                                            <span class="text-secondary">(Optional)</span>
                                        </label>

                                        <input type="date" class="form-control" id="due_date" name="due_date">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= site_url('dashboard'); ?>" class="btn btn-outline-light">
                                        Cancel
                                    </a>

                                    <button type="submit" class="btn btn-info fw-semibold">
                                        <i class="bi bi-plus-lg me-1"></i>
                                        Create Task
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script src="<?= base_url('assets/js/utils.js'); ?>"></script>
    <script src="<?= base_url('assets/js/task.js'); ?>"></script>
</body>

</html>