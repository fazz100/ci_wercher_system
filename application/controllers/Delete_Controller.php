<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_Controller extends CI_Controller {


	public function __construct() {
		parent::__construct();

		$this->load->model('Model_Selects');
		$this->load->model('Model_Inserts');
		$this->load->model('Model_Deletes');
	}
	public function RemoveEmployee()
	{
		$id = $this->input->get('id');
		if (!isset($_GET['id'])) {
			redirect('Employee');
		}
		else
		{
			$Removethis = $this->Model_Deletes->RemoveEmpl($id);
			if ($Removethis == TRUE) {
				$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #45C830;"><h5><i class="fas fa-check"></i> Employee ID ' . $id . ' has been succesfully removed!</h5></div>');
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER']);
				}
				else
				{
					redirect('Employee');
				}
			}
			else
			{
				redirect('Employee');
			}
		}
	}
	public function RemoveAdmin()
	{
		$id = $this->input->get('id');
		if (!isset($_GET['id'])) {
			redirect('Admin_List');
		}
		else
		{
			$Removethis = $this->Model_Deletes->RemoveAdminM($id);
			if ($Removethis == TRUE) {
				$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #45C830;"><h5><i class="fas fa-check"></i> Admin ID ' . $id . ' has been succesfully removed!</h5></div>');
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER']);
				}
				else
				{
					redirect('Admin_List');
				}
			}
			else
			{
				redirect('Admin_List');
			}
		}
	}
	public function RemoveClient()
	{
		$id = $this->input->get('id');
		if (!isset($_GET['id'])) {
			redirect('Clients');
		}
		else
		{
			$Removethis = $this->Model_Deletes->RemoveClientM($id);
			if ($Removethis == TRUE) {
				$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #45C830;"><h5><i class="fas fa-check"></i> Client ID ' . $id . ' has been succesfully removed!</h5></div>');
				if (isset($_SERVER['HTTP_REFERER'])) {
					redirect($_SERVER['HTTP_REFERER']);
				}
				else
				{
					redirect('Clients');
				}
			}
			else
			{
				redirect('Clients');
			}
		}
	}
}
