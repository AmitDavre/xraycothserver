<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	//var_dump($compinfo);
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	//$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$branch = sprintf("%06d",$sso_codes[$_SESSION['rego']['gov_branch']]['code']);
	
	$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_th'],-2);
	
	$sso_act_max = $pr_settings['sso_act_max'];
	$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate_emp'], 2, '', ''));

	$paydate = str_replace('/','',$_SESSION['rego']['paydate']);
	$paydate = str_replace('-','',$_SESSION['rego']['paydate']);
	$year = substr($paydate,-4)+543;
	$paydate = substr($paydate,0,-4).$year;
	
	//$branch = sprintf("%04d",$compinfo['branch']);
	
	
	
	
	$txt = '';
	$sql = "SELECT emp_id, gross_income, tax_month, calc_tax, total_non_allow, tot_absence FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER by emp_id ASC";
	//var_dump($sql);
	$nr = 1;
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				if($row['calc_tax'] == 1){
					$gross = $row['gross_income'] - $row['total_non_allow'] - $row['tot_absence'];
					//$gross = $row['gross_income'];// - $row['total_non_allow'] - $row['tot_absence'];
					$txt 	.= sprintf("%03d",$nr).'|'
							.str_replace('-','',$edata['tax_id']).'|'
							.$empinfo['tax_id'].'|'
							.trim($title[$empinfo['title']]).'|'
							.trim($empinfo['firstname']).'|'
							.trim($empinfo['lastname']).'|'
							.'1|'
							.$paydate.'|'
							.number_format($gross,2,'.','').'|'
							.number_format($row['tax_month'],2,'.','').'|'
							.'1'
							."\r\n";
							$nr++;
				}
			}
		}
	}
	//var_dump($txt); exit;
	
	$data = iconv(mb_detect_encoding($txt), "TIS-620", $txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	//$filename = strtoupper($_SESSION['rego']['cid']).' PND1 textfile '.$_SESSION['rego']['year_th'].' '.$_SESSION['rego']['curr_month'].'.txt';
	$filename = str_replace('-','',$edata['tax_id']).'OZZZZZP01'.$_SESSION['rego']['year_th'].$_SESSION['rego']['curr_month'].'00.txt';
	$doc = 'PND1 upload textfile';

	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	
	//include('../print/save_to_documents.php');

	exit;

















