const auditDetailsModalElement =
	document.getElementById("auditDetailsModal");

const auditDetailsModal = auditDetailsModalElement
	? new bootstrap.Modal(auditDetailsModalElement)
	: null;

function formatAuditValues(values) {
	if (!values) {
		return "No values.";
	}

	try {
		const parsed =
			typeof values === "string"
				? JSON.parse(values)
				: values;

		return JSON.stringify(parsed, null, 4);
	} catch (error) {
		return String(values);
	}
}

function showAuditDetails(audit) {
	document.getElementById("auditDetailUser").textContent =
		audit.user_name || "Unknown";

	document.getElementById("auditDetailAction").textContent =
		audit.action || "—";

	document.getElementById("auditDetailEntity").textContent =
		audit.entity || "—";

	document.getElementById("auditDetailsSubtitle").textContent =
		audit.created_at || "";

	document.getElementById("auditOldValues").textContent =
		formatAuditValues(audit.old_values);

	document.getElementById("auditNewValues").textContent =
		formatAuditValues(audit.new_values);

	if (auditDetailsModal) {
		auditDetailsModal.show();
	}
}