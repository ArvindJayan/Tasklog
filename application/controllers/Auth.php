<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->library('session');
	}

	public function is_authenticated()
	{
		return (bool) $this->session->userdata('logged_in');
	}

	public function login()
	{
		if ($this->is_authenticated()) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$email = trim($this->input->post('email', true));
			$password = $this->input->post('password');

			if ($email === '' || $password === '') {
				$this->session->set_flashdata('error', 'Email and password are required.');
				redirect('auth/login');
			}

			$user = $this->User_model->login($email, $password);

			if (!$user) {
				$this->session->set_flashdata('error', 'Invalid email or password.');
				redirect('auth/login');
			}

			$this->session->set_userdata([
				'user_id' => $user->id,
				'role_id' => $user->role_id,
				'name' => $user->name,
				'email' => $user->email,
				'logged_in' => true
			]);

			redirect('dashboard');
		}

		$this->load->view('auth/login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('home');
	}
}