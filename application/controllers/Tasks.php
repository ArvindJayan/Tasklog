<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Task_model');
		$this->load->model('Employee_model');
		$this->load->library('session');
	}

	private function is_authenticated()
	{
		return (bool) $this->session->userdata('logged_in');
	}

	public function create()
	{
		if (!$this->is_authenticated()) {
			redirect('auth/login');
		}

		$user_id = $this->session->userdata('user_id');

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

			$task_data = [
				'title' => $title,
				'description' => $description,
				'assigned_to' => $employee->id,
				'assigned_by' => null,
				'priority' => $priority ?: 'medium',
				'due_date' => $due_date ?: null
			];

			if ($this->Task_model->create_task($task_data)) {
				$this->session->set_flashdata('success', 'Task created successfully.');
				redirect('dashboard');
			}

			$this->session->set_flashdata('error', 'Unable to create task.');
			redirect('tasks/create');
		}

		$data['employee'] = $employee;

		$this->load->view('tasks/create', $data);
	}
}