<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Task_model');
	}

	public function index()
	{
		$user_id = $this->session->userdata('user_id');

		$data = [
			'tasks' => $this->Task_model->get_tasks_by_user($user_id),
			'task_count' => $this->Task_model->get_task_count_by_user($user_id)
		];

		$this->load->view('dashboard/index', $data);
	}
}