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

	public function get_all_employees($exclude_id = null)
	{
		$this->db
			->select('employees.*, users.name')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->order_by('users.name', 'ASC');

		if ($exclude_id !== null) {
			$this->db->where('employees.id !=', $exclude_id);
		}

		return $this->db->get()->result();
	}

	public function get_employee_by_user_id($user_id)
	{
		return $this->db
			->where('user_id', $user_id)
			->get('employees')
			->row();
	}

	public function get_employees_by_ra_id($ra_id)
	{
		return $this->db
			->where('ra_id', $ra_id)
			->order_by('employee_code', 'ASC')
			->get('employees')
			->result();
	}
}