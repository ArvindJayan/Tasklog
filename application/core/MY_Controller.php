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

		if (!$this->Employee_model->get_employee_by_user_id($this->session->userdata('user_id'))) {
			redirect('onboarding');
		}
	}
}