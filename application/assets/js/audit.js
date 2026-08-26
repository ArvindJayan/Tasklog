let currentPage = 1;

function loadAuditLogs(page = 1) {
	currentPage = page;

	const params = new URLSearchParams({
		page: page,
		user_id: document.getElementById('userFilter').value,
		action: document.getElementById('actionFilter').value,
		entity: document.getElementById('entityFilter').value,
		date_from: document.getElementById('dateFrom').value,
		date_to: document.getElementById('dateTo').value
	});

	const tbody = document.getElementById('auditTableBody');

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
				throw new Error('Unable to load audit logs.');
			}

			return response.json();
		})
		.then(data => {
			renderAuditLogs(data.logs);
			renderPagination(data.page, data.total_pages);
		})
		.catch(() => {
			tbody.innerHTML = `
				<tr>
					<td colspan="6" class="text-center py-5 text-danger">
						Unable to load audit logs.
					</td>
				</tr>
			`;

			document.getElementById('pagination').innerHTML = '';
		});
}

function renderAuditLogs(logs) {
	const tbody = document.getElementById('auditTableBody');

	if (logs.length === 0) {
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

		const oldValues = formatValues(log.old_values);
		const newValues = formatValues(log.new_values);

		return `
			<tr class="audit-row border-bottom border-tasklog">

				<td class="px-4 py-3">
					<span class="fw-semibold text-white">
						${escapeHtml(log.user_name)}
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

				<td class="px-4 py-3 audit-values">

					${oldValues ? `
						<div class="mb-2">
							<small class="text-secondary">
								Old
							</small>

							<div class="text-white">
								${oldValues}
							</div>
						</div>
					` : ''}

					${newValues ? `
						<div>
							<small class="text-secondary">
								New
							</small>

							<div class="text-white">
								${newValues}
							</div>
						</div>
					` : ''}

				</td>

				<td class="px-4 py-3 text-secondary">
					${escapeHtml(log.ip_address || 'N/A')}
				</td>

				<td class="px-4 py-3 text-secondary text-nowrap">
					${formatDate(log.created_at)}
				</td>

			</tr>
		`;
	}).join('');
}

function renderPagination(page, totalPages) {
	const container = document.getElementById('pagination');

	if (totalPages <= 1) {
		container.innerHTML = '';
		return;
	}

	let html = `
		<nav>
			<ul class="pagination mb-0">
	`;

	html += `
		<li class="page-item ${page === 1 ? 'disabled' : ''}">
			<button
				class="page-link"
				onclick="loadAuditLogs(${page - 1})"
				${page === 1 ? 'disabled' : ''}
			>
				Previous
			</button>
		</li>
	`;

	for (let i = 1; i <= totalPages; i++) {
		html += `
			<li class="page-item ${i === page ? 'active' : ''}">
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
		<li class="page-item ${page === totalPages ? 'disabled' : ''}">
			<button
				class="page-link"
				onclick="loadAuditLogs(${page + 1})"
				${page === totalPages ? 'disabled' : ''}
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
		return '';
	}

	try {
		const parsed = typeof values === 'string'
			? JSON.parse(values)
			: values;

		return escapeHtml(JSON.stringify(parsed, null, 2));
	} catch {
		return escapeHtml(String(values));
	}
}

function formatDate(dateString) {
	const date = new Date(dateString.replace(' ', 'T'));

	if (Number.isNaN(date.getTime())) {
		return escapeHtml(dateString);
	}

	return date.toLocaleString();
}

function escapeHtml(value) {
	return String(value)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#039;');
}

document.getElementById('applyFilters').addEventListener('click', () => {
	loadAuditLogs(1);
});

document.getElementById('resetFilters').addEventListener('click', () => {
	document.getElementById('userFilter').value = '';
	document.getElementById('actionFilter').value = '';
	document.getElementById('entityFilter').value = '';
	document.getElementById('dateFrom').value = '';
	document.getElementById('dateTo').value = '';

	loadAuditLogs(1);
});

loadAuditLogs();