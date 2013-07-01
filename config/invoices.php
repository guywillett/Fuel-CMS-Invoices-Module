<?php 
/*
|--------------------------------------------------------------------------
| FUEL NAVIGATION: An array of navigation items for the left menu
|--------------------------------------------------------------------------
*/
$config['nav']['Invoices'] = array(
'invoices' => 'Invoices',
'email_templates' => 'Email Templates',
);
$config['invoices']['tax'] = 17.5;//default tax %
$config['invoices']['attach_pdf_to_email'] = TRUE;//default is to attach pdf to emails
$config['invoices']['due_date_default'] = 7;
$config['invoices']['first_reminder'] = 2;
$config['invoices']['reminder_days'] = 14;
$config['invoices']['cron_backup_command'] = $cron_command = 'php '. FCPATH . SELF .' invoices/cron'; //the command line is used  which means minimal config in the Cronjobs module.
$config['invoices']['remittance_advice'] = "Please pay as soon as you can!";
$config['invoices']['invoices_pdf_folder'] = "sub27ksyveo";//something hard to guess, like a password effectively - make sure it is writable and exists in your main application  /assets/pdf folder
$config['invoices']['domain'] = "www.chamsoft.co.uk/";//must end in a slash "/". Used in links to invoice downloads

// create configurable settings from with the Settings module. You can add/remove field that you don't want configured
$config['invoices']['settings'] = array();
$config['invoices']['settings']['attach_pdf_to_email'] = array('type'=>'checkbox', 'value'=>'1','checked'=>'checked');
$config['invoices']['settings']['tax'] = array('size'=> '3', 'value' => '17.5', 'after_html' => ' %', 'label' => 'Tax Default');
$config['invoices']['settings']['due_date_default'] = array('size' => '3', 'value' => '7', 'after_html' => ' days after Invoice Date');
$config['invoices']['settings']['first_reminder'] = array('size' => '3', 'value' => '2', 'after_html' => ' days after Due Date');
$config['invoices']['settings']['reminder_days'] = array('size' => '3', 'value' => '14', 'after_html' => ' days. A reminder is sent every "?" days until it is marked as Paid or Unpublished. Set to "0" to not send reminders.');
$config['invoices']['settings']['cron_backup_command'] = array('value' => $config['invoices']['cron_backup_command'], 'size' => 100,
'after_html' => "<br /><br />
<div class='buttonbar'><ul><li class='unattached'><a href=".fuel_url("tools/cronjobs?command=".urlencode($cron_command))." class='ico ico_invoices'>add an Invoices cronjob</a></li></ul></div>
"
);//The button for one-click Cron job adding
$config['invoices']['settings']['remittance_advice'] = array('type' => 'textarea', 'value' => $config['invoices']['remittance_advice']);