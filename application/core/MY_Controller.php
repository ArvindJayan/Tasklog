<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->model('Employee_model');

		if (!$this->session->userdata('logged_in')) {
			redirect('auth/login');
		}
	}
}

class Employee_Controller extends MY_Controller
{	
	protected $employee;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Employee_model');

		$employee = $this->Employee_model->get_employee_by_user_id(
			$this->session->userdata('user_id')
		);

		if (!$employee) {
			redirect('onboarding');
		}

		$this->employee = $employee;
	}
}