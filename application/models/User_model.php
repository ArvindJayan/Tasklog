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
}