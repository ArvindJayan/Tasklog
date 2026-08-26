<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ((int) $this->session->userdata('role_id') !== 1) {
			show_error('You do not have permission to access this page.', 403);
		}

		$this->load->model('Audit_model');
	}

	public function index()
	{
		$data = [
			'users' => $this->Audit_model->get_users(),
			'actions' => $this->Audit_model->get_actions(),
			'entities' => $this->Audit_model->get_entities()
		];

		$this->load->view('audit/index', $data);
	}

	public function fetch()
	{
		$page = max(1, (int) $this->input->get('page'));
		$limit = 30;
		$offset = ($page - 1) * $limit;

		$filters = [
			'user_id' => $this->input->get('user_id', true),
			'action' => $this->input->get('action', true),
			'entity' => $this->input->get('entity', true),
			'date_from' => $this->input->get('date_from', true),
			'date_to' => $this->input->get('date_to', true)
		];

		$logs = $this->Audit_model->get_audit_logs(
			$filters,
			$limit,
			$offset
		);

		$total = $this->Audit_model->get_audit_log_count($filters);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'logs' => $logs,
				'total' => $total,
				'page' => $page,
				'limit' => $limit,
				'total_pages' => (int) ceil($total / $limit)
			]));
	}
}