<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task_model extends CI_Model
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
}