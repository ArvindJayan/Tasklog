<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{
	public function profile_exists($user_id)
	{
		return $this->db
			->where('user_id', $user_id)
			->count_all_results('employees') > 0;
	}

	public function create_employee($user_id, $employee_code, $department, $designation)
	{
		$data = [
			'user_id' => $user_id,
			'employee_code' => trim($employee_code),
			'department' => trim($department),
			'designation' => trim($designation)
		];

		return $this->db->insert('employees', $data);
	}
	public function get_employee_by_user_id($user_id)
	{
		return $this->db
			->where('user_id', $user_id)
			->get('employees')
			->row();
	}
}