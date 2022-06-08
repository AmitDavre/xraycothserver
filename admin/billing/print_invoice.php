<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include('../files/admin_functions.php');
	
	include('invoice_pdf.php');
	
	$mpdf->Output($dir,'F');
	$dba->query("UPDATE rego_invoices SET pdf_invoice = '".$dba->real_escape_string($root)."' WHERE id = '".$_REQUEST['nr']."'");
	$mpdf->Output($filename,'I');