FUEL-CMS-Invoices-Module
========================

An Invoicing module for the awesome Fuel CMS built on CodeIgniter

This is a basic Invoicing module that can be expanded upon, made more bespoke or used more or less as is.  Enjoy!

Summary
=======

1. It handles invoices with tax.

2. Emailing Invoices with a personalised email template

3. Resending Invoices manually as and when (perhaps the client lost the email)

4. Recurring Invoices (perhaps you invoice clients the same amount every month or every year)

5. Sending email reminders with a personalised email template

6. Displays unpaid and overdue invoices on the dashboard of the admin area. Overdue are coloured red.

7. Simple one-click Cron set up, by integrating with Cronjobs Module, to automate the process nightly (sends recurring invoices and reminders for overdue invoices).

8. Options to include the PDF as an attachment to the emails and/or have a (fairly secure) download link.

9. Invoice Downloads and cron info is logged to fuel_logs and displayed in the dashboard area of the admin area.

Installation
============

There are 2 ways to install this module. If you use GIT then you can use the following method to create a submodule:

Using Git
---------

1. Open up a Terminal window, "cd" to your FUEL CMS installation then type:
    <pre>php index.php fuel/installer/add_git_submodule git://github.com/guywillett/FUEL-CMS-Invoices-Module.git invoices</pre>

2.  Then to install, type in: <pre>php index.php fuel/installer/install invoices</pre>

Manual
------

1. Download the zip file from GitHub: https://github.com/guywillett/FUEL-CMS-Invoices-Module

2. Create an "invoices" folder in fuel/modules/ and place the contents of the blog module folder in there.

3. Then to install, type in: <pre>php index.php fuel/installer/install invoices</pre>

4. Add "invoices" to the the $config['modules_allowed'] in fuel/application/config/MY_fuel.php

Uninstall
---------

To uninstall the module which will remove any permissions and database information:
<pre> php index.php fuel/installer/uninstall invoices</pre>

License
=======

This Invoices Module is licensed under APACHE 2

Other Documentation
=============

Invoice & Thank You Page design:  You should alter the Downloads view and the html/css in getInvoiceHTML() in libraries to how you want .

Change your $config options as you wish in config/invoices.php.

Make sure the folder name for your 'invoices_pdf_folder' is obscure, like a password, as this provides security. It helps prevent guessing the url to an invoice.

Make sure to create the 'invoices_pdf_folder' folder in your main application  /assets/pdf folder and make sure it is writable by everyone.
 eg: /assets/pdf/getsf58kvs4k

You can override the $config values by using the Settings page in the admin area of the CMS.

To take advantage of the integration with the Cronjobs Module, make sure you install the Cronjobs Module :)

The email templates must be named 'invoice' and 'reminder' (they are created for you, but need personalising, during installation).

The Invoices Module uses Phantomjs to render the HTML/CSS for the invoice into a PDF file (all the code is written).  I chose this because it is very easy to install and can be useful for other things.
Make sure you have Phantomjs installed! You do not need to know how to use it.

It is assumed that your customers, whom you want to invoice, will have an account in fuel_users (ie access to the admin area like any other user).
This is where we get the email address and name and other information for populating the email templates and sending the emails.

A permission set is created when you install the Invoices Module, however when clicking a 'download' link in an email to download their invoice pdf, the client only needs to be logged in.  They do not need to have any 'Invoices' permissions.  This is so that if they login to the admin area of the CMS, they will not have access to the Invoices module (or anything else that you have not given permission for).
