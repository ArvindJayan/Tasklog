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

	public function login()
	{
		if ($this->session->userdata('user_id')) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$email = trim($this->input->post('email', true));
			$password = $this->input->post('password');

			if ($email === '' || $password === '') {
				$this->session->set_flashdata('error', 'Email and password are required.');
				redirect('login');
			}

			$user = $this->User_model->login($email, $password);

			if (!$user) {
				$this->session->set_flashdata('error', 'Invalid email or password.');
				redirect('login');
			}

			$this->session->set_userdata([
				'user_id' => $user->id,
				'email' => $user->email,
				'role_id' => $user->role_id,
				'logged_in' => true
			]);

			redirect('dashboard');
		}

		$this->load->view('auth/login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}