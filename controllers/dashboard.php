<?php
require_once(FUEL_PATH.'libraries/Fuel_base_controller.php');
class Dashboard extends Fuel_base_controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->module_model(INVOICES_FOLDER,'invoices_model');
	}
	
	function index()
	{
		$vars['invoices'] = $this->invoices_model->get_unpaid_invoices();
		$this->load->view('_admin/dashboard', $vars);
	}

}