<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		if ((int) $this->session->userdata('role_id') !== 1) {
			show_error('Access denied.', 403);
		}

		$this->load->model('Employee_model');
	}

	public function employees()
	{
		$data = [
			'employees' => $this->Employee_model->get_all_employees_with_ra(),
			'ras' => $this->Employee_model->get_all_ras()
		];

		$this->load->view('admin/employees', $data);
	}

	public function assign_ra()
	{
		if ($this->input->method() !== 'post') {
			show_error('Invalid request.', 405);
		}

		$employee_id = (int) $this->input->post('employee_id');
		$ra_id = (int) $this->input->post('ra_id');

		if (!$employee_id || !$ra_id) {
			$this->session->set_flashdata('error', 'Employee and RA are required.');
			redirect('admin/employees');
		}

		$admin_user_id = $this->session->userdata('user_id');

		if ($this->Employee_model->assign_ra($employee_id, $ra_id, $admin_user_id)) {
			$this->session->set_flashdata('success', 'RA assigned successfully.');
		} else {
			$this->session->set_flashdata('error', 'Unable to assign RA.');
		}

		redirect('admin/employees');
	}
}