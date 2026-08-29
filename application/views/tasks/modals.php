<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-surface border-tasklog text-white">

            <div class="modal-header border-tasklog">

                <h5 class="modal-title fw-bold">
                    Create Task
                </h5>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="createTaskForm">

                <div class="modal-body">

                    <?php if ($this->session->userdata('role_id') == 2): ?>

                        <div class="mb-3">

                            <label class="form-label text-secondary">
                                Assign To
                            </label>

                            <select class="form-select"
                                name="assigned_to"
                                required>

                                <option value="<?= $this->Employee_model
                                    ->get_employee_by_user_id(
                                        $this->session->userdata('user_id')
                                    )->id; ?>">
                                    Myself
                                </option>

                                <?php foreach (
                                    $this->Employee_model->get_employees_by_ra(
                                        $this->Employee_model
                                            ->get_employee_by_user_id(
                                                $this->session->userdata('user_id')
                                            )->id
                                    ) as $assigned_employee
                                ): ?>

                                    <option value="<?= $assigned_employee->id; ?>">

                                        <?= html_escape($assigned_employee->name); ?>

                                        (<?= html_escape($assigned_employee->employee_code); ?>)

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    <?php endif; ?>

                    <div class="mb-3">

                        <label class="form-label text-secondary">
                            Task Title
                        </label>

                        <input type="text"
                            class="form-control"
                            name="title"
                            maxlength="200"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label text-secondary">
                            Description
                            <span class="text-muted">(Optional)</span>
                        </label>

                        <textarea class="form-control"
                            name="description"
                            rows="4"></textarea>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label text-secondary">
                                Priority
                            </label>

                            <select class="form-select"
                                name="priority">

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

                        <div class="col-md-6">

                            <label class="form-label text-secondary">
                                Due Date
                            </label>

                            <input type="date"
                                class="form-control"
                                name="due_date"
                                required>

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-tasklog">

                    <button type="button"
                        class="btn btn-outline-info fw-semibold"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-info fw-semibold">
                        Create Task
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-surface border-tasklog text-white">

            <div class="modal-header border-tasklog">

                <h5 class="modal-title fw-bold">
                    Task Details
                </h5>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="viewTaskLoading"
                    class="text-center py-5">

                    <div class="spinner-border text-info"
                        role="status">
                    </div>

                    <p class="text-secondary mt-3 mb-0">
                        Loading task...
                    </p>

                </div>

                <div id="viewTaskContent" class="d-none">

                    <div class="mb-4">

                        <div class="view-label">
                            Task
                        </div>

                        <div class="view-value fs-4"
                            id="viewTaskTitle">
                        </div>

                    </div>

                    <div class="mb-4">

                        <div class="view-label">
                            Description
                        </div>

                        <div class="view-description"
                            id="viewTaskDescription">
                        </div>

                    </div>

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="view-label">
                                Priority
                            </div>

                            <div id="viewTaskPriority">
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="view-label">
                                Status
                            </div>

                            <div id="viewTaskStatus">
                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="view-label">
                                Due Date
                            </div>

                            <div class="view-value"
                                id="viewTaskDueDate">
                            </div>

                        </div>

                    </div>

                    <hr class="border-tasklog my-4">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="view-label">
                                Assigned To
                            </div>

                            <div class="view-value"
                                id="viewTaskAssignedTo">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="view-label">
                                Assigned By
                            </div>

                            <div class="view-value"
                                id="viewTaskAssignedBy">
                            </div>

                        </div>

                    </div>

                    <hr class="border-tasklog my-4">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="view-label">
                                Created At
                            </div>

                            <div class="view-value"
                                id="viewTaskCreatedAt">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="view-label">
                                Last Updated
                            </div>

                            <div class="view-value"
                                id="viewTaskUpdatedAt">
                            </div>

                        </div>

                    </div>

                </div>

                <div id="viewTaskError"
                    class="alert alert-danger d-none mb-0">
                </div>

            </div>

            <div class="modal-footer border-tasklog">

                <button type="button"
                    class="btn btn-outline-light"
                    data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>


<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content bg-surface border-tasklog text-white">

            <div class="modal-header border-tasklog">

                <h5 class="modal-title fw-bold">
                    Edit Task
                </h5>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="editTaskForm">

                <input type="hidden"
                    name="task_id"
                    id="editTaskId">

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label text-secondary">
                            Task Title
                        </label>

                        <input type="text"
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

                        <textarea class="form-control"
                            name="description"
                            id="editDescription"
                            rows="4"></textarea>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label text-secondary">
                                Priority
                            </label>

                            <select class="form-select"
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

                            <select class="form-select"
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

                            <input type="date"
                                class="form-control"
                                name="due_date"
                                id="editDueDate">

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-tasklog">

                    <button type="button"
                        class="btn btn-outline-info fw-semibold"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        class="btn btn-info fw-semibold">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<div class="modal fade"
    id="deleteTaskModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content bg-surface border-tasklog text-white">

            <div class="modal-header border-tasklog">

                <h5 class="modal-title fw-bold">
                    Delete Task
                </h5>

                <button type="button"
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

                <button type="button"
                    class="btn btn-outline-info fw-semibold"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                    class="btn btn-danger"
                    id="confirmDeleteBtn">
                    Delete Task
                </button>

            </div>

        </div>

    </div>

</div>