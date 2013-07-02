<?php
/**
 * Created by JetBrains PhpStorm.
 * User: guy
 * Date: Thu 27/06/2013
 * Time: 13:23
 * To change this template use File | Settings | File Templates.
 */
class Invoices {


    function getInvoicePDF($f){

        //USES PHANTOMJS TO RENDER THE PDF
        $CI =& get_instance();
        $CI->load->database();
        $s = "select user_name from fuel_users where id = '{$f['user_id']}'";
        $q = $CI->db->query($s);
        $r = $q->result();
        $f['to'] = $r[0]->user_name;
        $f['remittance_advice'] = $CI->fuel->invoices->config('remittance_advice');
        $file = getcwd()."/assets/pdf/".$CI->fuel->invoices->config('invoices_pdf_folder')."/".$f['id']."_".$f['name'].".pdf";
        $filename = $f['id']."_".$f['name'].".pdf";
        $html = str_replace('\n','',json_encode($this->getInvoiceHTML($f)));
        $id = uniqid();
        $script ="
var page = require('webpage').create();
page.content = $html
page.paperSize = {format: 'A4', orientation: 'portrait'}
    page.render('$file');
    phantom.exit();";
        $pa = file_put_contents(getcwd().'/assets/pdf/'.$CI->fuel->invoices->config('invoices_pdf_folder').'/'.$id.'phantomaction.js',$script);
        if($pa){
            $l = escapeshellarg(getcwd()."/assets/pdf/".$CI->fuel->invoices->config('invoices_pdf_folder')."/".$id."phantomaction.js");

            shell_exec("phantomjs ".$l);
            unlink("/assets/pdf/".$CI->fuel->invoices->config('invoices_pdf_folder')."/'.$id.'phantomaction.js");
            return $filename;} else {return "Invoice PDF NOT CREATED!";}
    }

    function getInvoiceHTML($f){
        //change the CSS/HTML to your liking!
        $html = "";
        ob_start(); ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>ChamSoft - Invoice</title>

            <style type="text/css">
                body{
                    background-color:#333333;
                    background-color:#c3c3c3;
                }
                .container{
                    width:99%;
                    padding-top:10px;
                    font-family:Arial, Helvetica, sans-serif;
                    font-size:12px;
                    margin-left:auto;
                    margin-right:auto;
                }
                .header{
                    background-color:#29384B;
                    width:100%;
                    padding-bottom:10px;
                    padding-top:10px;
                    border-top-right-radius: 5px;
                    border-top-left-radius: 5px;
                }
                .img{
                    margin-left:auto;
                    margin-right:auto;
                    width:400px;
                }
                .inner-container{
                    /*width:600px;*/
                    background-color:#ffffff;
                    border-bottom-right-radius: 5px;
                    border-bottom-left-radius: 5px;
                    padding: 10px 20px;


                }
                .clear-both{
                    clear:both;
                }
                .logo{
                    color:#FFFFFF;
                    font-weight:bold;
                    padding:15px;
                    font-family:lato,sans-serif;
                    font-size:28px;
                }
                .logo span{
                    color:#77C62E;}
            </style>
        </head>

        <body>
        <div class="container">
            <div class="header">
                <div class="logo">cham<span>soft</span></div>
            </div><!--end header-->
            <div class="clear-both"></div>
            <div class="inner-container">
                <h1>Invoice</h1>
                <hr>
                <div style="text-align:right"><b>Date:</b> <?=date("jS M Y", strtotime($f['date']))?></div>
                <div style="text-align:right"><b>Invoice Ref:</b> <?=$f['name']?></div>
                <br />
                <div style="text-align:right"><b>Due Date:</b> <?=date("jS M Y", strtotime($f['due_date']))?></div>
                <div style="text-align:left"><b>To:</b> <?=$f['to']?></div>
                <br/>
                <hr>
                <br/>

                <table border="none" style="font-size:12px; width:75%; margin-left:auto; margin-right:auto">
                    <tr><th>Description</th><th>Amount</th></tr>
                    <tr><td><?=$f['content']?></td><td><?=$f['amount']?></td></tr>
                    <tr><td>Tax @ <?=$f['tax']?>%</td><td><?=number_format($f['tax_total'],2)?></td></tr>
                    <tr><td></td><td><hr></td></tr>
                    <tr style="font-weight:bold"><td>Total</td><td>£<?=number_format($f['total'],2)?></td></tr>
                </table>
                <div class="clear_both"></div>
                <br/>
                <hr>
                <br/>
                <div class="remittance">
                    <h3>Remittance Advice</h3>
                    <p><?=$f['remittance_advice']?></p>
                </div><!--end "remittance"-->
                <p>
                </p>
            </div><!--end inner-container-->
        </div><!--end container-->
        </body>
        </html>

        <?php
        $html .= ob_get_clean();
        return $html;
    }
}