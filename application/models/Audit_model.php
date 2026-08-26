<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model
{
	public function log($action, $entity, $entity_id, $old_values = null, $new_values = null)
	{
		$data = [
			'user_id' => $this->session->userdata('user_id'),
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

	public function get_audit_logs($filters = [], $limit = 30, $offset = 0)
	{
		$this->db
			->select('audit_logs.*, users.name AS user_name')
			->from('audit_logs')
			->join('users', 'users.id = audit_logs.user_id');

		$this->apply_filters($filters);

		return $this->db
			->order_by('audit_logs.created_at', 'DESC')
			->limit($limit, $offset)
			->get()
			->result();
	}

	public function get_audit_log_count($filters = [])
	{
		$this->db
			->from('audit_logs')
			->join('users', 'users.id = audit_logs.user_id');

		$this->apply_filters($filters);

		return $this->db->count_all_results();
	}

	public function get_users()
	{
		return $this->db
			->select('id, name')
			->order_by('name', 'ASC')
			->get('users')
			->result();
	}

	public function get_actions()
	{
		return $this->db
			->distinct()
			->select('action')
			->order_by('action', 'ASC')
			->get('audit_logs')
			->result();
	}

	public function get_entities()
	{
		return $this->db
			->distinct()
			->select('entity')
			->order_by('entity', 'ASC')
			->get('audit_logs')
			->result();
	}

	private function apply_filters($filters)
	{
		if (!empty($filters['user_id'])) {
			$this->db->where('audit_logs.user_id', (int) $filters['user_id']);
		}

		if (!empty($filters['action'])) {
			$this->db->where('audit_logs.action', $filters['action']);
		}

		if (!empty($filters['entity'])) {
			$this->db->where('audit_logs.entity', $filters['entity']);
		}

		if (!empty($filters['date_from'])) {
			$this->db->where(
				'audit_logs.created_at >=',
				$filters['date_from'] . ' 00:00:00'
			);
		}

		if (!empty($filters['date_to'])) {
			$this->db->where(
				'audit_logs.created_at <=',
				$filters['date_to'] . ' 23:59:59'
			);
		}
	}
}