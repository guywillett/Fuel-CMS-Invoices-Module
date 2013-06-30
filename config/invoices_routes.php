<?php 
//link the controller to the nav link

$route[FUEL_ROUTE.'invoices/download/(.*)'] = 'invoices/download/$1';
$route[FUEL_ROUTE.'invoices'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'invoices/(.*)'] = FUEL_FOLDER.'/module/$1';
$route[FUEL_ROUTE.'email_templates'] = FUEL_FOLDER.'/module';
$route[FUEL_ROUTE.'email_templates/(.*)'] = FUEL_FOLDER.'/module/$1';