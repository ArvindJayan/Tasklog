<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Audit_model');
	}

	public function profile_exists($user_id)
	{
		return $this->db
			->where('user_id', $user_id)
			->count_all_results('employees') > 0;
	}

	public function create_employee(
		$user_id,
		$employee_code,
		$department,
		$designation
	) {
		$data = [
			'user_id' => $user_id,
			'employee_code' => trim($employee_code),
			'department' => trim($department),
			'designation' => trim($designation)
		];

		return $this->db->insert('employees', $data);
	}

	public function get_employees_by_ra($ra_id)
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
			->where('users.role_id', 3)
			->where('employees.ra_id', $ra_id)
			->order_by('users.name', 'ASC')
			->get()
			->result();
	}

	public function get_employee_by_user_id($user_id)
	{
		return $this->db
			->where('user_id', $user_id)
			->get('employees')
			->row();
	}

	public function get_employee_by_id($employee_id)
	{
		return $this->db
			->where('id', $employee_id)
			->get('employees')
			->row();
	}

	public function get_all_employees()
	{
		return $this->db
			->select('
				employees.id,
				employees.user_id,
				employees.employee_code,
				employees.department,
				employees.designation,
				employees.ra_id,
				users.name,
				users.email,
				users.role_id,
				roles.name AS role_name,
				ra_user.name AS ra_name,
				ra.employee_code AS ra_employee_code
			')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->join('roles', 'roles.id = users.role_id')
			->join(
				'employees AS ra',
				'ra.id = employees.ra_id',
				'left'
			)
			->join(
				'users AS ra_user',
				'ra_user.id = ra.user_id',
				'left'
			)
			->where_in('users.role_id', [2, 3])
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

	public function update_employee_role(
		$employee_id,
		$role_id,
		$ra_id,
		$admin_user_id
	) {
		$employee = $this->db
			->select('
				employees.id,
				employees.user_id,
				employees.ra_id,
				users.role_id
			')
			->from('employees')
			->join('users', 'users.id = employees.user_id')
			->where('employees.id', $employee_id)
			->where_in('users.role_id', [2, 3])
			->get()
			->row();

		if (!$employee) {
			return false;
		}

		if ((int) $employee->user_id === (int) $admin_user_id) {
			return false;
		}

		if (!in_array($role_id, [2, 3], true)) {
			return false;
		}

		$old_role_id = (int) $employee->role_id;
		$old_ra_id = $employee->ra_id !== null
			? (int) $employee->ra_id
			: null;

		if ($role_id === 2) {
			$ra_id = null;
		}

		if ($role_id === 3) {
			if (!$ra_id) {
				return false;
			}

			if ((int) $ra_id === (int) $employee_id) {
				return false;
			}

			$ra = $this->db
				->select('employees.id')
				->from('employees')
				->join(
					'users',
					'users.id = employees.user_id'
				)
				->where('employees.id', $ra_id)
				->where('users.role_id', 2)
				->get()
				->row();

			if (!$ra) {
				return false;
			}
		}

		if (
			$old_role_id === $role_id &&
			$old_ra_id === $ra_id
		) {
			return true;
		}

		$this->db->trans_start();

		$this->db
			->where('id', $employee->user_id)
			->update('users', [
				'role_id' => $role_id
			]);

		$this->db
			->where('id', $employee_id)
			->update('employees', [
				'ra_id' => $ra_id
			]);

		$this->Audit_model->log(
			'UPDATE_EMPLOYEE_ROLE',
			'employee',
			$employee_id,
			[
				'role_id' => $old_role_id,
				'ra_id' => $old_ra_id
			],
			[
				'role_id' => $role_id,
				'ra_id' => $ra_id
			]
		);

		$this->db->trans_complete();

		return $this->db->trans_status();
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

		if ((int) $employee_id === (int) $ra_id) {
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