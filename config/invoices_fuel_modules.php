<?php
// included in the main config/MY_fuel_modules.php


$config['modules']['invoices'] = array(
		'module_name' => 'Invoices',
		'module_uri' => 'invoices',
		'model_name' => 'invoices_model',
		'model_location' => 'invoices',
		'permission' => array('invoices','create','edit','delete','publish'),
		'permission' => 'invoices/invoices',
		'nav_selected' => 'invoices',
		'instructions' => 'Invoice:',
		'js' => array('invoices' => 'invoices_model')
	);
	
$config['modules']['email_templates'] = array(
		'module_name' => 'Email Templates',
		'module_uri' => 'email_templates',
		'model_name' => 'email_templates_model',
		'model_location' => 'invoices',
		'permission' => 'invoices/email_templates',
		'nav_selected' => 'email_templates',
		'instructions' => 'Create and Edit Email Templates here',
	);
