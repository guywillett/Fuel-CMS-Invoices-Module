<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');

class Email_templates_model extends Base_module_model {
	
	public $required = array('name','subject','content');
	public $unique_fields = array('name');
	
	function __construct()
	{
		parent::__construct('email_templates'); // table name
	}
	
	function list_items($limit = NULL, $offset = NULL, $col = 'name', $order = 'asc'){
	$this->db->select('email_templates.id, name, email_templates.from, copied_to, active', FALSE);
	$data = parent::list_items($limit, $offset, $col, $order);
	return $data;
	}
	
	function form_fields($values){
		$html = "<br /><p>You may use the following placeholders:<br/><br />
		{first_name}, {last_name}, {invoice_ref}, {invoice_description}, {link}, {days_overdue}
		</p>";
		
		$fields = parent::form_fields($values);
		$fields['from']['comment'] = "The 'from' email address";
		$fields['copied_to']['comment'] = "An email address you would like a copy sent to";
		$fields['content']['after_html'] = $html;
		return $fields;
		}
		
	function getLink($report){
        //NOTE IT IS A SECURITY ISSUE TO USE THIS LINK
        //site_url() gives 'localhost' during cron, but chamsoft.co.uk when done manually via CMS...
        $link = $this->fuel->invoices->config('domain').FUEL_ROUTE."invoices/download/".$report['file'];
		return $link;
		}
		
	function getAttachmentPath($report){
		$path = getcwd()."/assets/pdf/".$this->fuel->invoices->config('invoices_pdf_folder')."/";
        $link = $path.$report['file'];
		return $link;
		}
		
	function parse_template($report, $user,$template){
		$days = round((time() - strtotime($report['due_date'])) / (60*60*24));
		$contents = "<!DOCTYPE html><html><body>";
		$content = $template['content'];
		$content = str_replace('{first_name}', smart_ucwords($user['first_name']),$content);
		$content = str_replace('{last_name}', smart_ucwords($user['last_name']),$content);
		$content = str_replace('{invoice_ref}', $report['name'],$content);
		$content = str_replace('{invoice_description}', $report['content'],$content);
		$content = str_replace('{link}', $this->getLink($report),$content);
		$content = str_replace('{days_overdue}', $days,$content);
		$contents .= $content;
		$contents .= "</body></html>";
		return $contents;
		}

    function getSettings(){
        /*$settings = $this->fuel->settings->get('invoices');
        if(empty($settings)){
            $settings = $this->fuel->invoices->config();
        }*/
        //I fixed a bug in fuel_advanced_module class so it now auto checks for settings aswell as config
        $settings = $this->fuel->invoices->config();
        return $settings;
    }

	function send_email($invoice = array(), $template_name = NULL){
		$users = array();
		$templat = $this->db->get_where('email_templates', array('active' => 'yes', 'name' => $template_name))->result_array();
		$template = $templat[0];
		$users = $this->db->get_where('fuel_users', array('id' => $invoice['user_id']))->result_array();
        $user = $users[0];

			$this->load->module_library('invoices','my_fuel_notification');
        if($user['id'] == $invoice['user_id']){
			$params = array();
			$params['html'] = TRUE;
			$params['to'] = $user['email'];
			$params['from'] = $template['from'];
			$params['cc'] = '';
			$params['bcc'] = $template['copied_to'];
			$params['subject'] = $template['subject'];
			$params['message'] = $this->parse_template($invoice, $user,$template);
            $params['attachments'] = '';
            if($this->fuel->invoices->config('attach_pdf_to_email')){
			    $params['attachments'] = $this->getAttachmentPath($invoice);
            }
			$params['use_dev_mode'] = FALSE;

			$this->my_fuel_notification->send($params);
            $e = $this->my_fuel_notification->errors();
            if( ! empty($e)){
                echo $e;
            }

			$this->db->where('id',$invoice['id']);
			if($invoice['note_sent'] = 'no'){
				$this->db->update('invoices',array('note_sent' => 'yes'));
				}
        }
		}
		
	function send_recurring_invoices(){
        $log = FALSE;
        $s = $this->getSettings();
		$recurs = $this->db->get_where('invoices', array('published'=>'yes', 'recur'=>'yes', 'recur_sent' => 'no'))->result_array();
		foreach($recurs as $r){
			$date = empty($r['date_last_sent']) ? $r['date'] : $r['date_last_sent'];//unnecessary now we use recur_sent, just use date

			if(date("Y-m-d",strtotime($date." + ".$r['recur_days']." days")) <= date("Y-m-d")){//<= in case we change the recur_days causing us to miss the day...

                //create new invoice
                $name1 = explode('-', $r['name']);
                $name = $name1[0]."-".date("dmY")."-".$r['id'];
                $r2 = array(
                    'date' => date("Y-m-d"),
                    'due_date' => date("Y-m-d", strtotime("+ ".$s['due_date_default']." days")),
                    'tax' => $r['tax'],
                    'tax_total' => $r['tax_total'],
                    'amount' => $r['amount'],
                    'total' => $r['total'],
                    'paid' => 'no',
                    'recur' => 'yes',
                    'recur_days' => $r['recur_days'],
                    'recur_sent' => 'no',
                    'user_id' => $r['user_id'],
                    'content' => $r['content'],
                    'name' => $name
                );
                $this->db->insert('invoices', $r2);
                $r2['id'] = $this->db->insert_id();


                //create pdf after invoice record because we need the id...
                $this->load->module_library(INVOICES_FOLDER, 'invoices');
                $r2['file'] = $file = $this->invoices->getInvoicePDF($r2);//returns filename getInvoicePDF

                $this->db->where(array('id' => $r2['id']));//now update the new invoice with its pdf filename
                $this->db->update('invoices',array('file' => $file));

                //update old invoice as recur_sent = yes
                $this->db->where('id',$r['id']);
                $this->db->update('invoices',array('recur_sent'=>'yes', 'note_sent' => 'yes', 'date_last_sent' => date("Y-m-d")));
                //send new invoice
			$this->send_email($r2, 'invoice');
                $log = TRUE;
			}
        }
            if($log){
            $this->fuel->logs->write('recurring invoices sent');}
            else {

                $this->fuel->logs->write('no recurring invoices');

			}
		}
	function send_reminders(){
        $log = FALSE;
        $s = $this->getSettings();
		$now = date("Y-m-d");
		$unpaids = $this->db->get_where('invoices', array('paid'=>'no', 'published' => 'yes'))->result_array();
		foreach($unpaids as $u){
			if(date("Y-m-d", strtotime($u['due_date']." + ".$s['first_reminder']." days")) == $now || (strtotime($u['due_date']) < strtotime($now) && $s['reminder_days'] > 0 && is_int((strtotime($now) - strtotime($u['due_date']))/(60*60*24*$s['reminder_days'])) )){//if divisible cleanly by ,say,14 then send reminder (ie sends every 14 days)

                $this->send_email($u,'reminder');
                $log = TRUE;
				}
			}
        if($log){
            $this->fuel->logs->write('reminder invoices sent');}
        else {

            $this->fuel->logs->write('no reminder invoices');

        }
		}
}
class Email_template_model extends Data_record {
	
}