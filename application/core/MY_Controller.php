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

		$user_id = $this->session->userdata('user_id');

		if (!$this->Employee_model->profile_exists($user_id)) {
			redirect('onboarding');
		}
	}
}