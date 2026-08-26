<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model
{
	public function log($user_id, $action, $entity, $entity_id, $old_values = null, $new_values = null)
	{
		$data = [
			'user_id' => $user_id,
			'action' => $action,
			'entity' => $entity,
			'entity_id' => $entity_id,
			'old_values' => $old_values !== null ? json_encode($old_values) : null,
			'new_values' => $new_values !== null ? json_encode($new_values) : null,
			'ip_address' => $this->input->ip_address(),
			'user_agent' => $this->input->user_agent()
		];

		return $this->db->insert('audit_logs', $data);
	}
}