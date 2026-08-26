let currentPage = 1;

const auditDetailModalElement = document.getElementById("auditDetailModal");

const auditDetailModal = auditDetailModalElement
	? new bootstrap.Modal(auditDetailModalElement)
	: null;

function loadAuditLogs(page = 1) {
	currentPage = page;

	const params = new URLSearchParams({
		page: page,
		user_id: document.getElementById("userFilter").value,
		action: document.getElementById("actionFilter").value,
		entity: document.getElementById("entityFilter").value,
		date_from: document.getElementById("dateFrom").value,
		date_to: document.getElementById("dateTo").value
	});

	const tbody = document.getElementById("auditTableBody");

	tbody.innerHTML = `
		<tr>
			<td colspan="6" class="text-center py-5 text-secondary">
				Loading...
			</td>
		</tr>
	`;

	fetch(`${auditFetchUrl}?${params.toString()}`)
		.then(response => {
			if (!response.ok) {
				throw new Error("Unable to load audit logs.");
			}

			return response.json();
		})
		.then(data => {
			renderAuditLogs(data.logs);
			renderPagination(data.page, data.total_pages);
		})
		.catch(error => {
			console.error("Audit error:", error);

			tbody.innerHTML = `
				<tr>
					<td colspan="6" class="text-center py-5 text-danger">
						Unable to load audit logs.
					</td>
				</tr>
			`;

			document.getElementById("pagination").innerHTML = "";
		});
}

function renderAuditLogs(logs) {
	const tbody = document.getElementById("auditTableBody");

	if (!logs || logs.length === 0) {
		tbody.innerHTML = `
			<tr>
				<td colspan="6" class="text-center py-5 text-secondary">
					No audit logs found.
				</td>
			</tr>
		`;

		return;
	}

	tbody.innerHTML = logs.map(log => {
		return `
			<tr class="audit-row border-bottom border-tasklog">

				<td class="px-4 py-3">
					<span class="fw-semibold text-white">
						${escapeHtml(log.user_name || "Unknown")}
					</span>
				</td>

				<td class="px-4 py-3">
					<span class="badge text-bg-info">
						${escapeHtml(log.action)}
					</span>
				</td>

				<td class="px-4 py-3">

					<div class="text-white">
						${escapeHtml(log.entity)}
					</div>

					<small class="text-secondary">
						ID: ${escapeHtml(String(log.entity_id))}
					</small>

				</td>

				<td class="px-4 py-3 text-secondary">
					${escapeHtml(log.ip_address || "N/A")}
				</td>

				<td class="px-4 py-3 text-secondary text-nowrap">
					${formatDate(log.created_at)}
				</td>

				<td class="px-4 py-3 text-end">

					<button
						type="button"
						class="btn btn-sm btn-info fw-semibold view-audit-btn"
						data-audit-id="${escapeHtml(String(log.id))}"
					>
						View
					</button>

				</td>

			</tr>
		`;
	}).join("");

	document.querySelectorAll(".view-audit-btn").forEach(button => {
		button.addEventListener("click", function () {

			const auditId = this.dataset.auditId;

			const log = logs.find(item =>
				String(item.id) === String(auditId)
			);

			if (!log) {
				return;
			}

			showAuditDetails(log);
		});
	});
}

function showAuditDetails(log) {
	document.getElementById("auditDetailUser").textContent =
		log.user_name || "Unknown";

	document.getElementById("auditDetailAction").textContent =
		log.action || "";

	document.getElementById("auditDetailActionValue").textContent =
		log.action || "N/A";

	document.getElementById("auditDetailEntity").textContent =
		log.entity || "N/A";

	document.getElementById("auditDetailEntityId").textContent =
		log.entity_id || "N/A";

	document.getElementById("auditDetailIp").textContent =
		log.ip_address || "N/A";

	document.getElementById("auditDetailDate").textContent =
		formatDate(log.created_at);

	document.getElementById("auditDetailUserAgent").textContent =
		log.user_agent || "N/A";

	document.getElementById("auditOldValues").textContent =
		formatValues(log.old_values) || "No previous values.";

	document.getElementById("auditNewValues").textContent =
		formatValues(log.new_values) || "No new values.";

	if (auditDetailModal) {
		auditDetailModal.show();
	}
}

function renderPagination(page, totalPages) {
	const container = document.getElementById("pagination");

	if (totalPages <= 1) {
		container.innerHTML = "";
		return;
	}

	let html = `
		<nav>
			<ul class="pagination mb-0">
	`;

	html += `
		<li class="page-item ${page === 1 ? "disabled" : ""}">
			<button
				class="page-link"
				onclick="loadAuditLogs(${page - 1})"
				${page === 1 ? "disabled" : ""}
			>
				Previous
			</button>
		</li>
	`;

	for (let i = 1; i <= totalPages; i++) {

		html += `
			<li class="page-item ${i === page ? "active" : ""}">
				<button
					class="page-link"
					onclick="loadAuditLogs(${i})"
				>
					${i}
				</button>
			</li>
		`;
	}

	html += `
		<li class="page-item ${page === totalPages ? "disabled" : ""}">
			<button
				class="page-link"
				onclick="loadAuditLogs(${page + 1})"
				${page === totalPages ? "disabled" : ""}
			>
				Next
			</button>
		</li>
	`;

	html += `
			</ul>
		</nav>
	`;

	container.innerHTML = html;
}

function formatValues(values) {
	if (!values) {
		return "";
	}

	try {
		const parsed = typeof values === "string"
			? JSON.parse(values)
			: values;

		return JSON.stringify(parsed, null, 2);
	} catch {
		return String(values);
	}
}

function formatDate(dateString) {
	if (!dateString) {
		return "N/A";
	}

	const date = new Date(dateString.replace(" ", "T"));

	if (Number.isNaN(date.getTime())) {
		return dateString;
	}

	return date.toLocaleString();
}

function escapeHtml(value) {
	return String(value)
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
}

document.getElementById("applyFilters").addEventListener("click", () => {
	loadAuditLogs(1);
});

document.getElementById("resetFilters").addEventListener("click", () => {

	document.getElementById("userFilter").value = "";
	document.getElementById("actionFilter").value = "";
	document.getElementById("entityFilter").value = "";
	document.getElementById("dateFrom").value = "";
	document.getElementById("dateTo").value = "";

	loadAuditLogs(1);
});

loadAuditLogs();