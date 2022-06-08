<?php

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);

	$emps = 0;
	$income = 0;
	$tax = 0;
	$res = $dbc->query("SELECT SUM(gross_income) as income, SUM(tax_month) as tax FROM ".$_SESSION['rego']['payroll_dbase']." WHERE entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."'");
	if($row = $res->fetch_assoc()){
		$income = $row['income'];
		$tax = $row['tax'];
	}
	$res = $dbc->query("SELECT emp_id FROM ".$_SESSION['rego']['payroll_dbase']." WHERE entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' GROUP BY emp_id");
	while($row = $res->fetch_assoc()){
		$emps ++;
	}
	$pages = 1;
	if($emps > 7){
		$pages = ceil($emps/7);
	}

	$pin = str_replace('-','',$edata['tax_id']);
	if(strlen($pin)!== 13){$pin = '?????????????';}
	$pin = str_split($pin);
	
	$branch = sprintf("%05d",$edata['revenu_branch']);
	$branch = str_split($branch);

	if($address && $address['postal'] == ''){$address['postal'] = '?????';}
	if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
	$post = str_split($address['postal']);

?>