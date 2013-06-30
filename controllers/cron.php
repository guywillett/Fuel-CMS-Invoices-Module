<?php
/**
 * Created by JetBrains PhpStorm.
 * User: guy
 * Date: Sat 29/06/2013
 * Time: 09:29
 * To change this template use File | Settings | File Templates.
 */
 class Cron extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->module_library(FUEL_FOLDER, 'fuel');
        $this->load->module_model(INVOICES_FOLDER, 'email_templates_model');
    }

    function _remap($method){
        if(defined('CRON') || defined('STDIN') || php_sapi_name() == 'cli'){
        $this->email_templates_model->send_reminders();
        $this->email_templates_model->send_recurring_invoices();
        }
    }

}
/* End of file cron.php */
/* Location: ./fuel/modules/invoices/controllers/cron.php */