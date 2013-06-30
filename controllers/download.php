<?php
require_once(FUEL_PATH.'libraries/Fuel_base_controller.php');
class Download extends Fuel_base_controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->helper('download');
    }

    function _remap($file)
    {
        if($this->input->get('download') == 'true'){//will fail first pass so can load the Download view

        $filepath = site_url('assets/pdf/'.$this->fuel->invoices->config('invoices_pdf_folder')."/".$file);
        $invoice = $this->db->get_where('invoices', array('file' => $file))->result_array();

            if($this->fuel->auth->is_logged_in()){//must be logged in to download...

                $user = $this->fuel->auth->valid_user();

                if($user['id'] = $invoice[0]['user_id']){//can only download an invoice if it is one of the clients!

                    $this->fuel->logs->write('user '.$user['id'].' downloaded invoice: '.$file);
                    $data = file_get_contents($filepath);
                    force_download($file, $data);

                } else {
                    redirect('404');
                }
            } else {
            redirect('404');
            }

        } else {
            //load view first so user doesn't just see a blank screen...
            //JavaScript will then trigger the download
            $this->load->view('download');
        }
    }

}