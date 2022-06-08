<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include('../files/admin_functions.php');
	
	include('receipt_pdf.php');
	
	$mpdf->Output($dir,'F');
	$dba->query("UPDATE rego_invoices SET pdf_receipt = '".$dba->real_escape_string($root)."' WHERE id = '".$_REQUEST['nr']."'");
	$mpdf->Output($filename,'I');	






