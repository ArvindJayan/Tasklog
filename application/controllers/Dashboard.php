<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Employee_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');

		$data = [
			'role_id' => $role_id,
			'tasks' => [],
			'task_count' => 0
		];

		if ($role_id != 1) {
			$data['tasks'] = $this->Tasks_model->get_tasks_by_user($user_id);
			$data['task_count'] = $this->Tasks_model->get_task_count_by_user($user_id);
		}

		$this->load->view('dashboard/index', $data);
	}
}