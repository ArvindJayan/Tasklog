<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function login($email, $password)
	{
		$user = $this->db
			->where('email', $email)
			->get('users')
			->row();

		if (!$user || !password_verify($password, $user->password)) {
			return false;
		}

		return $user;
	}
}