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

	public function register()
	{
		if ($this->is_authenticated()) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$name = trim($this->input->post('name', true));
			$email = trim($this->input->post('email', true));
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('confirm_password');

			if ($name === '' || $email === '' || $password === '' || $confirm_password === '') {
				$this->session->set_flashdata('error', 'All fields are required.');
				redirect('auth/register');
			}

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$this->session->set_flashdata('error', 'Please enter a valid email address.');
				redirect('auth/register');
			}

			if ($password !== $confirm_password) {
				$this->session->set_flashdata('error', 'Passwords do not match.');
				redirect('auth/register');
			}

			if ($this->User_model->email_exists($email)) {
				$this->session->set_flashdata('error', 'An account with this email already exists.');
				redirect('auth/register');
			}

			if ($this->User_model->register($name, $email, $password)) {
				$this->session->set_flashdata('success', 'Registration successful. You can now log in.');
				redirect('auth/login');
			}

			$this->session->set_flashdata('error', 'Registration failed. Please try again.');
			redirect('auth/register');
		}

		$this->load->view('auth/register');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('home');
	}
}