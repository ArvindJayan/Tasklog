<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Onboarding extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Employee_model');
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');

		if ($this->Employee_model->profile_exists($user_id)) {
			redirect('dashboard');
		}

		if ($this->input->method() === 'post') {
			$employee_code = trim($this->input->post('employee_code', true));
			$department = trim($this->input->post('department', true));
			$designation = trim($this->input->post('designation', true));

			if ($employee_code === '' || $department === '' || $designation === '') {
				$this->session->set_flashdata('error', 'All fields are required.');
				redirect('onboarding');
			}

			if ($this->Employee_model->create_employee(
				$user_id,
				$employee_code,
				$department,
				$designation
			)) {
				redirect('dashboard');
			}

			$this->session->set_flashdata('error', 'Unable to complete onboarding. Please try again.');
			redirect('onboarding');
		}

		$this->load->view('onboarding/index');
	}
}