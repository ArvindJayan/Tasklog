<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends Employee_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Tasks_model');
		$this->load->model('Employee_model');
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		if (!$employee) {
			redirect('onboarding');
		}

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
				return $this->json_response(false, 'Task title is required.');
			}

			if (!in_array($priority, ['low', 'medium', 'high', 'critical'])) {
				$priority = 'medium';
			}

			$assigned_to = $employee->id;
			$assigned_by = null;

			if ($role_id == 2) {
				$assigned_to = (int) $this->input->post('assigned_to');

				$assigned_employee = $this->Employee_model->get_employee_by_id($assigned_to);

				if (!$assigned_employee) {
					return $this->json_response(false, 'Please select a valid employee.');
				}

				if (!$this->Tasks_model->employee_belongs_to_ra($assigned_to, $employee->id)) {
					return $this->json_response(
						false,
						'You can only assign tasks to employees assigned to you.'
					);
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

			if (!$this->Tasks_model->create_task($task_data)) {
				return $this->json_response(false, 'Unable to create task.');
			}

			return $this->json_response(true, 'Task created successfully.');
		}

		$data = [
			'employee' => $employee,
			'role_id' => $role_id,
			'employees' => []
		];

		if ($role_id == 2) {
			$data['employees'] = $this->Employee_model->get_employees_by_ra($employee->id);
		}

		$this->load->view('tasks/create', $data);
	}

	public function view($id)
	{
		$task = $this->Tasks_model->get_task_by_id((int) $id);

		if (!$task) {
			show_404();
		}

		$user_id = $this->session->userdata('user_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		if (!$employee) {
			show_error('Unauthorized access.', 403);
		}

		if (
			(int) $task->assigned_to !== (int) $employee->id &&
			(int) $task->assigned_by !== (int) $employee->id
		) {
			show_error('You do not have permission to view this task.', 403);
		}

		if ($this->input->is_ajax_request()) {
			return $this->json_response(true, '', 200, [
				'task' => $task
			]);
		}

		$data['task'] = $task;

		$this->load->view('tasks/view', $data);
	}

	public function edit($id)
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Invalid request.', 400);
		}

		$user_id = $this->session->userdata('user_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		if (!$employee) {
			return $this->json_response(false, 'Unauthorized access.', 403);
		}

		$task = $this->Tasks_model->get_task_by_id((int) $id);

		if (!$task) {
			return $this->json_response(false, 'Task not found.', 404);
		}

		if (!$this->Tasks_model->can_delete_task($task, $employee->id)) {
			return $this->json_response(
				false,
				'You do not have permission to edit this task.',
				403
			);
		}

		$title = trim($this->input->post('title', true));
		$description = trim($this->input->post('description', true));
		$priority = $this->input->post('priority', true);
		$status = $this->input->post('status', true);
		$due_date = $this->input->post('due_date', true);

		if ($title === '') {
			return $this->json_response(false, 'Task title is required.');
		}

		if (!in_array($priority, ['low', 'medium', 'high', 'critical'])) {
			return $this->json_response(false, 'Invalid priority.');
		}

		if (!in_array($status, ['pending', 'in_progress', 'completed', 'cancelled'])) {
			return $this->json_response(false, 'Invalid status.');
		}

		$completed_at = $task->completed_at;

		if ($status === 'completed' && $task->status !== 'completed') {
			$completed_at = date('Y-m-d H:i:s');
		}

		if ($status !== 'completed') {
			$completed_at = null;
		}

		$task_data = [
			'title' => $title,
			'description' => $description,
			'priority' => $priority,
			'status' => $status,
			'due_date' => $due_date ?: null,
			'completed_at' => $completed_at
		];

		if (!$this->Tasks_model->update_task((int) $id, $task_data)) {
			return $this->json_response(false, 'Unable to update task.');
		}

		return $this->json_response(true, 'Task updated successfully.');
	}

	public function delete($id)
	{
		if (!$this->input->is_ajax_request()) {
			show_error('Invalid request.', 400);
		}

		$user_id = $this->session->userdata('user_id');

		$employee = $this->Employee_model->get_employee_by_user_id($user_id);

		if (!$employee) {
			return $this->json_response(false, 'Unauthorized access.', 403);
		}

		$task = $this->Tasks_model->get_task_by_id((int) $id);

		if (!$task) {
			return $this->json_response(false, 'Task not found.', 404);
		}

		if (!$this->Tasks_model->can_delete_task($task, $employee->id)) {
			return $this->json_response(
				false,
				'You do not have permission to delete this task.',
				403
			);
		}

		if (!$this->Tasks_model->delete_task((int) $id)) {
			return $this->json_response(false, 'Unable to delete task.');
		}

		return $this->json_response(true, 'Task deleted successfully.');
	}

	private function json_response($success, $message = '', $status_code = 200, $extra = [])
	{
		$response = array_merge([
			'success' => $success,
			'message' => $message
		], $extra);

		$this->output
			->set_status_header($status_code)
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
}