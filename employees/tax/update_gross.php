<?php
	if(session_id()==''){session_start();}
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	$taxable_gross = $_REQUEST['gross_income_year'] - $_REQUEST['pers_tax_deduct_gross'];
	
	$sql = "INSERT INTO ".$cid."_tax_simulation (emp_id, en_name, th_name, gross_income_year, pers_tax_deduct_gross, taxable_gross) VALUES (
		'".$_REQUEST['emp_id']."', '".$_REQUEST['en_name']."', '".$_REQUEST['th_name']."', '".$_REQUEST['gross_income_year']."', '".$_REQUEST['pers_tax_deduct_gross']."', '".$taxable_gross."')
		ON DUPLICATE KEY UPDATE 
		en_name = VALUES(en_name), 
		th_name = VALUES(th_name), 
		gross_income_year = VALUES(gross_income_year), 
		pers_tax_deduct_gross = VALUES(pers_tax_deduct_gross), 
		taxable_gross = VALUES(taxable_gross)";
		
	if($dbc->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dbc);
		
	}
	echo $err_msg;
	exit;
	
?>