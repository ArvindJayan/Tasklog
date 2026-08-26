<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Tasks_model');
		$this->load->model('User_model');
		$this->load->model('Employee_model');
		$this->load->library('session');

		if (!$this->session->userdata('logged_in')) {
			redirect('auth/login');
		}

		if (!$this->Employee_model->get_employee_by_user_id($this->session->userdata('user_id'))) {
			redirect('onboarding');
		}
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		$data['tasks'] = $this->Tasks_model->get_tasks_by_employee_id($employee->id);

		$this->load->view('tasks/index', $data);
	}

	public function create()
	{
		$user_id = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		if (!$employee) {
			redirect('onboarding');
		}

		if ($this->input->method() === 'post') {
			$title = trim($this->input->post('title', true));
			$description = trim($this->input->post('description', true));
			$priority = $this->input->post('priority', true);
			$due_date = $this->input->post('due_date', true);

			if ($title === '') {
				$this->session->set_flashdata('error', 'Task title is required.');
				redirect('tasks/create');
			}

			if (!in_array($priority, ['low', 'medium', 'high', 'critical'])) {
				$priority = 'medium';
			}

			$assigned_to = $employee->id;
			$assigned_by = null;

			if ($role_id == 2) {
				$assigned_to = (int) $this->input->post('assigned_to');

				$assigned_employee = $this->Employee_model->get_employee_by_user_id($assigned_to);

				if (!$assigned_employee) {
					$this->session->set_flashdata('error', 'Please select a valid employee.');
					redirect('tasks/create');
				}

				$assigned_by = $employee->id;
			}

			$task_data = [
				'title' => $title,
				'description' => $description,
				'assigned_to' => $assigned_to,
				'assigned_by' => $assigned_by,
				'priority' => $priority,
				'due_date' => $due_date ?: null
			];

			if ($this->Tasks_model->create_task($task_data)) {
				$this->session->set_flashdata('success', 'Task created successfully.');
				redirect('tasks');
			}

			$this->session->set_flashdata('error', 'Unable to create task.');
			redirect('tasks/create');
		}

		$data = [
			'employee' => $employee,
			'role_id' => $role_id,
			'employees' => []
		];

		if ($role_id == 2) {
			$data['employees'] = $this->Employee_model->get_all_employees($employee->id);
		}

		$this->load->view('tasks/create', $data);
	}

	public function view($id)
	{
		// We'll implement this later.
	}
}