<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks_model extends CI_Model
{
	public function get_tasks_by_user($user_id)
	{
		return $this->db
			->select('tasks.*')
			->from('tasks')
			->join('employees', 'employees.id = tasks.assigned_to')
			->where('employees.user_id', $user_id)
			->order_by('tasks.created_at', 'DESC')
			->get()
			->result();
	}

	public function get_task_count_by_user($user_id)
	{
		return $this->db
			->from('tasks')
			->join('employees', 'employees.id = tasks.assigned_to')
			->where('employees.user_id', $user_id)
			->count_all_results();
	}

	public function create_task($task_data)
	{
		return $this->db->insert('tasks', $task_data);
	}

	public function get_tasks_by_employee_id($employee_id)
	{
		return $this->db
			->where('assigned_to', $employee_id)
			->order_by('created_at', 'DESC')
			->get('tasks')
			->result();
	}

	public function get_task_by_id($task_id)
	{
		return $this->db
			->select('
			tasks.*,
			assigned_user.name AS assigned_to_name,
			assigned_employee.employee_code AS assigned_to_code,
			assigner_user.name AS assigned_by_name,
			assigner_employee.employee_code AS assigned_by_code
		')
			->from('tasks')
			->join(
				'employees AS assigned_employee',
				'assigned_employee.id = tasks.assigned_to'
			)
			->join(
				'users AS assigned_user',
				'assigned_user.id = assigned_employee.user_id'
			)
			->join(
				'employees AS assigner_employee',
				'assigner_employee.id = tasks.assigned_by',
				'left'
			)
			->join(
				'users AS assigner_user',
				'assigner_user.id = assigner_employee.user_id',
				'left'
			)
			->where('tasks.id', $task_id)
			->get()
			->row();
	}

	public function employee_belongs_to_ra($employee_id, $ra_id)
	{
		return $this->db
			->where('id', $employee_id)
			->where('ra_id', $ra_id)
			->count_all_results('employees') > 0;
	}

	public function can_edit_task($task, $employee_id)
	{
		if (!$task) {
			return false;
		}

		return (int) $task->assigned_to === (int) $employee_id;
	}

	public function can_delete_task($task, $employee_id)
	{
		if (!$task) {
			return false;
		}

		return (int) $task->assigned_to === (int) $employee_id;
	}

	public function update_task($task_id, $task_data)
	{
		return $this->db
			->where('id', $task_id)
			->update('tasks', $task_data);
	}

	public function delete_task($task_id)
	{
		return $this->db
			->where('id', $task_id)
			->delete('tasks');
	}
}