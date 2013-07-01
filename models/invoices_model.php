<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');

class Invoices_model extends Base_module_model {
	
	public $required = array('user_id');
	public $boolean_fields = array('paid');
	public $unique_fields = array('name');
	
	public $foreign_keys = array('user_id' => array('fuel' =>'fuel_users_model'));
	public $filters = array('user_id');
	
	function __construct()
	{
		parent::__construct('invoices'); // table name
		
	}

	
	function list_items($limit = NULL, $offset = NULL, $col = 'date', $order = 'asc'){
		
	$this->db->join('fuel_users', 'fuel_users.id = invoices.user_id', 'left');
	$this->db->select('invoices.id, invoices.date as date, invoices.name as reference,fuel_users.user_name AS client, invoices.status as status, invoices.total as total, invoices.paid as paid, invoices.due_date',FALSE);
	
	$data = parent::list_items($limit, $offset, $col, $order);
	
	return $data;
	}
	
	function form_fields($values=array()){
		$fields = parent::form_fields($values);
		$upload_path = assets_server_path('pdf')."/".$this->fuel->invoices->config('invoices_pdf_folder');
		$today = date("Y-m-d");
		$one_week = date("Y-m-d", strtotime($today." + ".$this->fuel->invoices->config('due_date_default')." days"));
		$status_options = array('status1','status2','status 3');//change to whatever you want
		
		$fields['date']['order'] = "5";
		$fields['date']['default'] = $today;
		$fields['name']['order'] = "10";
		$fields['name']['label'] = 'Reference';
		$fields['copy5'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 26);//just a spacer really
		$fields['user_id']['order'] = '22';
		$fields['copy6'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 24);//just a spacer really
		$fields['due_date']['order'] = "25";
		$fields['paid']['order'] = '27';
		$fields['copy29'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 29);//just a spacer really
		$fields['content']['order'] = "30";
		$fields['copy4'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 32);//just a spacer really
		$fields['amount']['order'] = '33';
		$fields['tax']['order'] = '34';
		$fields['tax_total']['order'] = '35';
		$fields['total']['order'] = '36';
		$fields['copy38'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 38);//just a spacer really
		$fields['copy39'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 39);//just a spacer really
		$fields['set1'] = array('type' => 'fieldset', 'class' => 'collapsible', 'label' => 'Document', 'order' => 40);
	$fields['file_upload'] = array('type' => 'file', 'upload_path' => $upload_path, 'display_overwrite' => FALSE, 'overwrite' => TRUE, 'order' => 50);
	$fields['file'] = array( 'order' => 60, 'type' => 'asset', 'upload' => FALSE, 'folder' => 'pdf/'.$this->fuel->invoices->config('invoices_pdf_folder'), 'is_image' => FALSE, 'create_thumb' => FALSE, 'hide_options' => TRUE, 'multiple' => FALSE , 'readonly' => TRUE );
	$fields['unix']['type'] = 'hidden';//not used
	
	$fields['set2'] = array('type' => 'fieldset', 'class' => 'collapsible', 'label' => 'Email Notification', 'order' => 63);
	$fields['send_note']['order'] = 65;
	$fields['send_note']['label'] = 'Send/Re-send Invoice';
    $fields['send_note']['after_html'] = " (click 'yes' again to resend)";
	$fields['copy7'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 66);
	$fields['note_sent']['order'] = 67;
	$fields['note_sent']['displayonly'] = TRUE;
	$fields['note_sent']['label'] = "Invoice Sent";
	$fields['copy10'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 92);
	$fields['copy11'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 93);
	$fields['status'] = array('type' => 'select', 'options' => $status_options);
	$fields['status']['order'] = '28';
	$fields['tax']['default'] = $this->fuel->invoices->config('tax');
	$fields['tax']['after_html'] = ' %';
	$fields['tax']['size'] = 5;
	$fields['recur_days']['default'] = 365;
	$fields['recur_days']['size'] = 4;
	$fields['amount']['size'] = 10;
	$fields['due_date']['default'] = $one_week;
	$fields['total']['displayonly'] = true;
	$fields['tax_total']['displayonly'] = true;
	$fields['description']['type'] = 'hidden';//not used
	$fields['recur']['order'] = '97';
	$fields['recur_days']['order'] = '98';
	$fields['copy99'] = array('type' => 'copy', 'tag' => 'p', 'value' => ' ', 'order' => 99);
	$fields['published'];
	
	$fields['date_last_sent']['type'] = 'hidden';
    $fields['recur_sent']['type'] = 'hidden';
	
		return $fields;
		}
		
function get_unpaid_invoices(){
	$reports = array();
	$s = "select * from invoices where paid = 'no' and published = 'yes' order by date asc";
	$report = $this->db->query($s);
	$reports = $report->result_array();
	return $reports;
	}


function on_before_save($fields){
    $this->load->module_library(INVOICES_FOLDER, 'invoices');
	$fields['tax_total'] = ($fields['tax']/100) * $fields['amount'];
	$fields['total'] = $fields['amount'] + $fields['tax_total'];
	$fields['file'] = $this->invoices->getInvoicePDF($fields);
	return $fields;
	}

function on_after_save($report){
	parent::on_after_save($report);
	if($report['published'] == 'yes' && $report['note_sent'] == 'no' && $report['send_note'] == 'yes'){
	$this->load->module_model(INVOICES_FOLDER,'email_templates_model');
	$this->email_templates_model->send_email($report, 'invoice') ;
		}
	}

}

class Invoice_model extends Data_record {
	
}