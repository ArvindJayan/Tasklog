const createModal = new bootstrap.Modal(
	document.getElementById("createTaskModal"),
);

const viewModal = new bootstrap.Modal(document.getElementById("viewTaskModal"));

const editModal = new bootstrap.Modal(document.getElementById("editTaskModal"));

const deleteModal = new bootstrap.Modal(
	document.getElementById("deleteTaskModal"),
);

let deleteTaskId = null;

function showAlert(message, type = "success") {
	document.getElementById("alertContainer").innerHTML = `
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
		critical: "text-bg-danger",
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
		cancelled: "text-bg-secondary",
	};

	const label = status
		.replace("_", " ")
		.replace(/\b\w/g, (character) => character.toUpperCase());

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

	return new Date(date).toLocaleDateString("en-GB", {
		day: "2-digit",
		month: "short",
		year: "numeric",
	});
}


document
	.getElementById("createTaskForm")
	.addEventListener("submit", function (event) {
		event.preventDefault();

		const form = this;
		const submitButton = form.querySelector('button[type="submit"]');

		submitButton.disabled = true;

		fetch(taskUrls.create, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: new FormData(form),
		})
			.then(parseResponse)
			.then((data) => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				createModal.hide();
				form.reset();

				showAlert(data.message);

				setTimeout(() => {
					location.reload();
				}, 500);
			})
			.catch((error) => {
				console.error("Create error:", error);
				showAlert(error.message || "Unable to create task.", "danger");
			})
			.finally(() => {
				submitButton.disabled = false;
			});
	});


document.querySelectorAll(".view-task-btn").forEach((button) => {
	button.addEventListener("click", function () {
		const taskId = this.dataset.taskId;

		document.getElementById("viewTaskLoading").classList.remove("d-none");
		document.getElementById("viewTaskContent").classList.add("d-none");
		document.getElementById("viewTaskError").classList.add("d-none");

		viewModal.show();

		fetch(taskUrls.view + taskId, {
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
		})
			.then(parseResponse)
			.then((data) => {
				document.getElementById("viewTaskLoading").classList.add("d-none");

				if (!data.success) {
					document.getElementById("viewTaskError").textContent = data.message;

					document.getElementById("viewTaskError").classList.remove("d-none");

					return;
				}

				const task = data.task;

				document.getElementById("viewTaskTitle").textContent = task.title || "";

				document.getElementById("viewTaskDescription").textContent =
					task.description || "No description provided.";

				document.getElementById("viewTaskPriority").innerHTML =
					getPriorityBadge(task.priority);

				document.getElementById("viewTaskStatus").innerHTML = getStatusBadge(
					task.status,
				);

				document.getElementById("viewTaskDueDate").textContent = formatDate(
					task.due_date,
				);

				document.getElementById("viewTaskAssignedTo").textContent =
					task.assigned_to_name || "Not assigned";

				document.getElementById("viewTaskAssignedBy").textContent =
					task.assigned_by_name || "Self-created";

				document.getElementById("viewTaskCreatedAt").textContent =
					task.created_at || "—";

				document.getElementById("viewTaskUpdatedAt").textContent =
					task.updated_at || "—";

				document.getElementById("viewTaskContent").classList.remove("d-none");
			})
			.catch((error) => {
				document.getElementById("viewTaskLoading").classList.add("d-none");

				document.getElementById("viewTaskError").textContent =
					error.message || "Unable to load task. Please try again.";

				document.getElementById("viewTaskError").classList.remove("d-none");
			});
	});
});

document.querySelectorAll(".edit-task-btn").forEach((button) => {
	button.addEventListener("click", function () {
		const taskId = this.dataset.taskId;

		fetch(taskUrls.view + taskId, {
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
		})
			.then(parseResponse)
			.then((data) => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				const task = data.task;

				document.getElementById("editTaskId").value = task.id;
				document.getElementById("editTitle").value = task.title;
				document.getElementById("editDescription").value =
					task.description || "";
				document.getElementById("editPriority").value = task.priority;
				document.getElementById("editStatus").value = task.status;
				document.getElementById("editDueDate").value = task.due_date || "";

				editModal.show();
			})
			.catch((error) => {
				console.error("Load edit error:", error);
				showAlert(error.message || "Unable to load task.", "danger");
			});
	});
});

document
	.getElementById("editTaskForm")
	.addEventListener("submit", function (event) {
		event.preventDefault();

		const form = this;
		const taskId = document.getElementById("editTaskId").value;
		const submitButton = form.querySelector('button[type="submit"]');

		submitButton.disabled = true;

		fetch(taskUrls.edit + taskId, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			body: new FormData(form),
		})
			.then(parseResponse)
			.then((data) => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				editModal.hide();

				const row = document.getElementById("task-row-" + taskId);

				if (row) {
					const cells = row.querySelectorAll("td");

					cells[0].querySelector(".fw-semibold").textContent = form.title.value;

					const description = cells[0].querySelector("small");

					if (form.description.value.trim()) {
						if (description) {
							description.textContent = form.description.value;
						} else {
							const small = document.createElement("small");

							small.className = "text-muted";
							small.textContent = form.description.value;

							cells[0].appendChild(small);
						}
					} else if (description) {
						description.remove();
					}

					cells[1].querySelector(".badge").outerHTML = getPriorityBadge(
						form.priority.value,
					);

					cells[2].querySelector(".badge").outerHTML = getStatusBadge(
						form.status.value,
					);

					cells[3].textContent = formatDate(form.due_date.value);
				}

				showAlert(data.message);
			})
			.catch((error) => {
				console.error("Edit error:", error);

				showAlert(error.message || "Unable to update task.", "danger");
			})
			.finally(() => {
				submitButton.disabled = false;
			});
	});

document.querySelectorAll(".delete-task-btn").forEach((button) => {
	button.addEventListener("click", function () {
		deleteTaskId = this.dataset.taskId;

		deleteModal.show();
	});
});

document
	.getElementById("confirmDeleteBtn")
	.addEventListener("click", function () {
		if (!deleteTaskId) {
			return;
		}

		const button = this;
		const taskId = deleteTaskId;

		button.disabled = true;

		fetch(taskUrls.delete + taskId, {
			method: "POST",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
		})
			.then(parseResponse)
			.then((data) => {
				if (!data.success) {
					showAlert(data.message, "danger");
					return;
				}

				deleteModal.hide();

				document.getElementById("task-row-" + taskId)?.remove();

				showAlert(data.message);
			})
			.catch((error) => {
				console.error("Delete error:", error);

				showAlert(error.message || "Unable to delete task.", "danger");
			})
			.finally(() => {
				button.disabled = false;
				deleteTaskId = null;
			});
	});
