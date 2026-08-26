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
			->where('id', $task_id)
			->get('tasks')
			->row();
	}

	public function employee_belongs_to_ra($employee_id, $ra_id)
	{
		return $this->db
			->where('id', $employee_id)
			->where('ra_id', $ra_id)
			->count_all_results('employees') > 0;
	}

	public function can_delete_task($task, $employee_id)
	{
		if (!$task) {
			return false;
		}

		return (int) $task->assigned_by === (int) $employee_id
			|| (
				(int) $task->assigned_to === (int) $employee_id
				&& $task->assigned_by === null
			);
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