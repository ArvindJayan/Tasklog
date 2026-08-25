<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function login($email, $password)
	{
		$user = $this->db
			->select('id, role_id, name, email, password')
			->where('email', $email)
			->get('users')
			->row();

		if (!$user || !password_verify($password, $user->password)) {
			return false;
		}

		unset($user->password);

		return $user;
	}

	public function register($name, $email, $password)
	{
		$employee_role = $this->db
			->select('id')
			->where('name', 'Employee')
			->get('roles')
			->row();

		if (!$employee_role) {
			return false;
		}

		$data = [
			'role_id' => $employee_role->id,
			'name' => trim($name),
			'email' => trim($email),
			'password' => password_hash($password, PASSWORD_DEFAULT)
		];

		if (!$this->db->insert('users', $data)) {
			return false;
		}

		return $this->db->insert_id();
	}

	public function email_exists($email)
	{
		return $this->db
			->where('email', trim($email))
			->count_all_results('users') > 0;
	}
}