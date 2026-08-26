<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Employee_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Tasks_model');
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');

		$data = [
			'role_id' => $role_id,
			'tasks' => [],
			'assigned_tasks' => [],
			'task_count' => 0
		];

		if ($role_id != 1) {
			$employee = $this->Employee_model->get_employee_by_user_id($user_id);

			if (!$employee) {
				redirect('onboarding');
			}

			$data['tasks'] = $this->Tasks_model->get_tasks_by_user($user_id);
			$data['task_count'] = $this->Tasks_model->get_task_count_by_user($user_id);

			if ($role_id == 2) {
				$data['assigned_tasks'] = $this->Tasks_model
					->get_tasks_assigned_by_employee_id($employee->id);
			}
		}

		$this->load->view('dashboard/index', $data);
	}
}