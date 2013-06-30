<?php
$config['name'] = 'Invoices Module';
$config['version'] = INVOICES_VERSION;
$config['author'] = 'Guy Willett';
$config['company'] = 'Chamsoft';
$config['license'] = 'Apache 2';
$config['copyright'] = '2013';
$config['author_url'] = 'http://www.chamsoft.co.uk';
$config['description'] = 'The FUEL Invoices Module can be used to create and send invoices, integrate with Cronjobs and send reminders and handle recurring invoices';
$config['compatibility'] = '1.0';
$config['instructions'] = '';
$config['permissions'] = array('invoices','invoices/create','invoices/delete','invoices/publish', 'invoices/edit');
$config['migration_version'] = 0;
$config['install_sql'] = 'invoices_install.sql';
$config['uninstall_sql'] = 'invoices_uninstall.sql';
$config['repo'] = 'git://github.com/guywillett/FUEL-CMS-Invoices-Module.git';