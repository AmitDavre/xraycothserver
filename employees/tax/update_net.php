<?php
	if(session_id()==''){session_start();}
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	$taxable_net = $_REQUEST['net_income_year'] - $_REQUEST['pers_tax_deduct_net'];

	$sql = "INSERT INTO ".$cid."_tax_simulation (emp_id, en_name, th_name, net_income_year, pers_tax_deduct_net, taxable_net) VALUES (
		'".$_REQUEST['emp_id']."', '".$_REQUEST['en_name']."', '".$_REQUEST['th_name']."', '".$_REQUEST['net_income_year']."', '".$_REQUEST['pers_tax_deduct_net']."', '".$taxable_net."')
		ON DUPLICATE KEY UPDATE 
		en_name = VALUES(en_name), 
		th_name = VALUES(th_name), 
		net_income_year = VALUES(net_income_year), 
		pers_tax_deduct_net = VALUES(pers_tax_deduct_net), 
		taxable_net = VALUES(taxable_net)";
		
	if($dbc->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dbc);
		
	}
	echo $err_msg;
	exit;
	
?>