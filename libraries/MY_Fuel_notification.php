<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(FUEL_PATH.'libraries/Fuel_notification.php');

/**
 * MY_FUEL notification object
 *
 * @package		Invoices Module for FUEL CMS
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Guy Willett @Chamsoft
 * @changes     added params for cc, bcc and html to pass through to CI email class
 */

// --------------------------------------------------------------------

class MY_Fuel_notification extends Fuel_notification {

    public $to = ''; // the to address to send the notification
    public $from = ''; // the from address of the sender
    public $from_name = ''; // the from name of the sender
    public $subject = ''; // the subject line of the notification
    public $message = ''; // the message
    public $attachments = array(); // attachments
    public $use_dev_mode = TRUE; // whether to use dev mode or not which means it will send to the dev_email address in the config
    public $cc = ''; //Just like the "to", can be a single email, a comma-delimited list or an array.
    public $bcc = ''; //Just like the "to", can be a single email, a comma-delimited list or an array.
    public $html = TRUE;//is it an html email?

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * Accepts an associative array as input, containing preferences (optional)
     *
     * @access	public
     * @param	array	config preferences
     * @return	void
     */
    function __construct($params = array())
    {
        parent::__construct($params);
        $this->initialize($params);
    }

    // --------------------------------------------------------------------

    /**
     * Sends an email message
     *
     * @access	public
     * @param	array	Email preferences (optional)
     * @return	boolean
     */
    function send($params = array())
    {
        // set defaults for from and from name
        if (empty($params['from']))
        {
            $params['from'] = $this->fuel->config('from_email');
        }

        if (empty($params['from_name']))
        {
            $params['from_name'] = $this->fuel->config('site_name');
        }

        // set any parameters passed
        $this->set_params($params);

        // load email and set notification properties
        if($this->html == true){//GUY
            $this->CI->load->library('email', array('mailtype' => 'html'));
        } else {
            $this->CI->load->library('email');
        }
        $this->CI->email->set_wordwrap(TRUE);
        $this->CI->email->from($this->from, $this->from_name);
        $this->CI->email->subject($this->subject);
        $this->CI->email->message($this->message);
        //GUY
        $this->CI->email->cc($this->cc);
        $this->CI->email->bcc($this->bcc);
        //END GUY
        if (!empty($this->attachments))
        {
            if (is_array($this->attachments))
            {
                foreach($this->attachments as $attachment)
                {
                    $this->CI->email->attach($attachment);
                }
            }
            else
            {
                $this->CI->email->attach($this->attachments);
            }
        }

        // if in dev mode then we send it to the dev email if specified
        if ($this->is_dev_mode())
        {
            $this->CI->email->to($this->CI->config->item('dev_email'));
        }
        else
        {
            $this->CI->email->to($this->to);
        }

        if (!$this->CI->email->send())
        {
            $this->_errors[] = $this->CI->email->print_debugger();
            return FALSE;
        }
        return TRUE;

    }

    // --------------------------------------------------------------------

}


/* End of file MY_Fuel_notification.php */
/* Location: ./modules/invoices/libraries/MY_Fuel_notification.php */