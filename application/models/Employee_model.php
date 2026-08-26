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

	public function get_all_employees_with_ra()
	{
		return $this->db
			->select('
				employees.id,
				employees.employee_code,
				employees.department,
				employees.designation,
				users.name,
				users.email,
				ra_user.name AS ra_name,
				ra.employee_code AS ra_employee_code
			')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->join('employees AS ra', 'ra.id = employees.ra_id', 'left')
			->join('users AS ra_user', 'ra_user.id = ra.user_id', 'left')
			->where('users.role_id', 3)
			->order_by('users.name', 'ASC')
			->get()
			->result();
	}

	public function get_all_ras()
	{
		return $this->db
			->select('
				employees.id,
				employees.employee_code,
				employees.department,
				employees.designation,
				users.name,
				users.email
			')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->where('users.role_id', 2)
			->order_by('users.name', 'ASC')
			->get()
			->result();
	}

	public function assign_ra($employee_id, $ra_id, $admin_user_id)
	{
		$employee = $this->db
			->select('employees.id, employees.ra_id')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->where('employees.id', $employee_id)
			->where('users.role_id', 3)
			->get()
			->row();

		if (!$employee) {
			return false;
		}

		$ra = $this->db
			->select('employees.id')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->where('employees.id', $ra_id)
			->where('users.role_id', 2)
			->get()
			->row();

		if (!$ra) {
			return false;
		}

		$old_ra_id = $employee->ra_id;

		if ((int) $old_ra_id === (int) $ra_id) {
			return true;
		}

		$this->db->trans_start();

		$this->db
			->where('id', $employee_id)
			->update('employees', [
				'ra_id' => $ra_id
			]);

		$this->Audit_model->log(
			$admin_user_id,
			'ASSIGN_RA',
			'employee',
			$employee_id,
			[
				'ra_id' => $old_ra_id
			],
			[
				'ra_id' => $ra_id
			]
		);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}