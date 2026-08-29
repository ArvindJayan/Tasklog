const createTaskModalElement = document.getElementById("createTaskModal");
const viewTaskModalElement = document.getElementById("viewTaskModal");
const editTaskModalElement = document.getElementById("editTaskModal");
const deleteTaskModalElement = document.getElementById("deleteTaskModal");

const createModal = createTaskModalElement ? new bootstrap.Modal(createTaskModalElement) : null;
const viewModal = viewTaskModalElement ? new bootstrap.Modal(viewTaskModalElement) : null;
const editModal = editTaskModalElement ? new bootstrap.Modal(editTaskModalElement) : null;
const deleteModal = deleteTaskModalElement ? new bootstrap.Modal(deleteTaskModalElement) : null;

let deleteTaskId = null;

function showAlert(message, type = "success") {
	const container = document.getElementById("alertContainer");

	if (!container) {
		return;
	}

	container.innerHTML = `
		<div class="alert alert-${type} alert-dismissible fade show" role="alert">
			${message}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	`;
}

async function parseResponse(response) {
	const text = await response.text();

	if (!text.trim()) {
		throw new Error("Server returned an empty response.");
	}

	let data;

	try {
		data = JSON.parse(text);
	} catch (error) {
		console.error("Invalid JSON response:", text);
		throw new Error("Server returned an invalid response.");
	}

	if (!response.ok) {
		throw new Error(data.message || "Request failed.");
	}

	return data;
}

function getPriorityBadge(priority) {
	const classes = {
		low: "text-bg-secondary",
		medium: "text-bg-info",
		high: "text-bg-warning",
		critical: "text-bg-danger"
	};

	return `
		<span class="badge ${classes[priority] || "text-bg-secondary"}">
			${priority.charAt(0).toUpperCase() + priority.slice(1)}
		</span>
	`;
}

function getStatusBadge(status) {
	const classes = {
		pending: "text-bg-warning",
		in_progress: "text-bg-info",
		completed: "text-bg-success",
		cancelled: "text-bg-secondary"
	};

	const label = status
		.replace("_", " ")
		.replace(/\b\w/g, character => character.toUpperCase());

	return `
		<span class="badge ${classes[status] || "text-bg-secondary"}">
			${label}
		</span>
	`;
}

function formatDate(date) {
	if (!date) {
		return "No due date";
	}

	return new Date(date + "T00:00:00").toLocaleDateString("en-GB", {
		day: "2-digit",
		month: "short",
		year: "numeric"
	});
}

function formatDuration(seconds) {
	if (
		seconds === null ||
		seconds === undefined ||
		seconds === "" ||
		Number(seconds) <= 0
	) {
		return "Not specified";
	}

	const minutes = Math.floor(Number(seconds) / 60);
	const hours = Math.floor(minutes / 60);
	const remainingMinutes = minutes % 60;

	if (hours > 0 && remainingMinutes > 0) {
		return `${hours}h ${remainingMinutes}m`;
	}

	if (hours > 0) {
		return `${hours}h`;
	}

	return `${remainingMinutes}m`;
}

function secondsToMinutes(seconds) {
	if (
		seconds === null ||
		seconds === undefined ||
		seconds === "" ||
		Number(seconds) <= 0
	) {
		return "";
	}

	return Math.round(Number(seconds) / 60);
}

const createTaskForm = document.getElementById("createTaskForm");

if (createTaskForm) {
	createTaskForm.addEventListener("submit", function(event) {
		event.preventDefault();

		const form = this;
		const submitButton = form.querySelector('button[type="submit"]');

		submitButton.disabled = true;

		fetch(taskUrls.create, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest"
			},
			body: new FormData(form)
		})
			.then(parseResponse)
			.then(data => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				if (createModal) {
					createModal.hide();
				}

				form.reset();
				showAlert(data.message);

				setTimeout(() => {
					location.reload();
				}, 500);
			})
			.catch(error => {
				console.error("Create error:", error);
				showAlert(error.message || "Unable to create task.", "danger");
			})
			.finally(() => {
				submitButton.disabled = false;
			});
	});
}

document.querySelectorAll(".view-task-btn").forEach(button => {
	button.addEventListener("click", function() {
		const taskId = this.dataset.taskId;
		const loading = document.getElementById("viewTaskLoading");
		const content = document.getElementById("viewTaskContent");
		const errorElement = document.getElementById("viewTaskError");

		loading.classList.remove("d-none");
		content.classList.add("d-none");
		errorElement.classList.add("d-none");

		if (viewModal) {
			viewModal.show();
		}

		fetch(taskUrls.view + taskId, {
			headers: {
				"X-Requested-With": "XMLHttpRequest"
			}
		})
			.then(parseResponse)
			.then(data => {
				loading.classList.add("d-none");

				if (!data.success) {
					errorElement.textContent = data.message;
					errorElement.classList.remove("d-none");
					return;
				}

				const task = data.task;

				document.getElementById("viewTaskTitle").textContent = task.title || "";
				document.getElementById("viewTaskDescription").textContent = task.description || "No description provided.";
				document.getElementById("viewTaskPriority").innerHTML = getPriorityBadge(task.priority);
				document.getElementById("viewTaskStatus").innerHTML = getStatusBadge(task.status);

				const estimatedDuration = document.getElementById("viewTaskEstimatedDuration");
				const actualDuration = document.getElementById("viewTaskActualDuration");

				if (estimatedDuration) {
					estimatedDuration.textContent = formatDuration(task.estimated_duration);
				}

				if (actualDuration) {
					actualDuration.textContent = formatDuration(task.actual_duration);
				}

				document.getElementById("viewTaskDueDate").textContent = formatDate(task.due_date);

				document.getElementById("viewTaskAssignedTo").textContent =
					task.assigned_to_name
						? `${task.assigned_to_name}${task.assigned_to_code ? ` (${task.assigned_to_code})` : ""}`
						: "Not assigned";

				document.getElementById("viewTaskAssignedBy").textContent =
					task.assigned_by_name
						? `${task.assigned_by_name}${task.assigned_by_code ? ` (${task.assigned_by_code})` : ""}`
						: "Self-created";

				document.getElementById("viewTaskCreatedAt").textContent = task.created_at || "—";
				document.getElementById("viewTaskUpdatedAt").textContent = task.updated_at || "—";

				content.classList.remove("d-none");
			})
			.catch(error => {
				loading.classList.add("d-none");
				errorElement.textContent = error.message || "Unable to load task. Please try again.";
				errorElement.classList.remove("d-none");
			});
	});
});

document.querySelectorAll(".edit-task-btn").forEach(button => {
	button.addEventListener("click", function() {
		const taskId = this.dataset.taskId;

		fetch(taskUrls.view + taskId, {
			headers: {
				"X-Requested-With": "XMLHttpRequest"
			}
		})
			.then(parseResponse)
			.then(data => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				const task = data.task;

				document.getElementById("editTaskId").value = task.id;
				document.getElementById("editTitle").value = task.title || "";
				document.getElementById("editDescription").value = task.description || "";
				document.getElementById("editPriority").value = task.priority;
				document.getElementById("editStatus").value = task.status;
				document.getElementById("editEstimatedDuration").value = secondsToMinutes(task.estimated_duration);
				document.getElementById("editDueDate").value = task.due_date || "";

				if (editModal) {
					editModal.show();
				}
			})
			.catch(error => {
				console.error("Load edit error:", error);
				showAlert(error.message || "Unable to load task.", "danger");
			});
	});
});

const editTaskForm = document.getElementById("editTaskForm");

if (editTaskForm) {
	editTaskForm.addEventListener("submit", function(event) {
		event.preventDefault();

		const form = this;
		const taskId = document.getElementById("editTaskId").value;
		const submitButton = form.querySelector('button[type="submit"]');

		submitButton.disabled = true;

		fetch(taskUrls.edit + taskId, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest"
			},
			body: new FormData(form)
		})
			.then(parseResponse)
			.then(data => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				if (editModal) {
					editModal.hide();
				}

				const row = document.getElementById("task-row-" + taskId);

				if (row) {
					const title = row.querySelector(".task-title");
					const description = row.querySelector(".task-description");
					const priority = row.querySelector(".task-priority-cell");
					const status = row.querySelector(".task-status-cell");
					const estimatedDuration = row.querySelector(".task-estimated-duration");
					const dueDate = row.querySelector(".task-due-date");

					if (title) {
						title.textContent = form.title.value;
					}

					if (form.description.value.trim()) {
						if (description) {
							description.textContent = form.description.value;
						} else {
							const small = document.createElement("small");
							small.className = "text-muted task-description";
							small.textContent = form.description.value;
							row.querySelector(".task-title-cell").appendChild(small);
						}
					} else if (description) {
						description.remove();
					}

					if (priority) {
						priority.innerHTML = getPriorityBadge(form.priority.value);
					}

					if (status) {
						status.innerHTML = getStatusBadge(form.status.value);
					}

					if (estimatedDuration) {
						estimatedDuration.textContent = formatDuration(Number(form.estimated_duration.value) * 60);
					}

					if (dueDate) {
						dueDate.textContent = formatDate(form.due_date.value);
					}
				}

				showAlert(data.message);
			})
			.catch(error => {
				console.error("Edit error:", error);
				showAlert(error.message || "Unable to update task.", "danger");
			})
			.finally(() => {
				submitButton.disabled = false;
			});
	});
}

document.querySelectorAll(".delete-task-btn").forEach(button => {
	button.addEventListener("click", function() {
		deleteTaskId = this.dataset.taskId;

		if (deleteModal) {
			deleteModal.show();
		}
	});
});

const confirmDeleteButton = document.getElementById("confirmDeleteBtn");

if (confirmDeleteButton) {
	confirmDeleteButton.addEventListener("click", function() {
		if (!deleteTaskId) {
			return;
		}

		const button = this;
		const taskId = deleteTaskId;

		button.disabled = true;

		fetch(taskUrls.delete + taskId, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest"
			}
		})
			.then(parseResponse)
			.then(data => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				if (deleteModal) {
					deleteModal.hide();
				}

				document.getElementById("task-row-" + taskId)?.remove();
				showAlert(data.message);
			})
			.catch(error => {
				console.error("Delete error:", error);
				showAlert(error.message || "Unable to delete task.", "danger");
			})
			.finally(() => {
				button.disabled = false;
				deleteTaskId = null;
			});
	});
}