<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	include('../../../dbconnect/db_connect.php');
	include(DIR.'payroll/inc/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	
	$paydate = str_replace('/','',$_SESSION['rego']['paydate']);
	
	$branch = sprintf("%04d",$compinfo['branch']);
	$txt = '';
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' ORDER by emp_id ASC";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$gross = $row['gross_income'] - $row['total_non_allow'] - $row['tot_absence'];
				
				$txt 	.= '00|'
						.str_replace('-','',$compinfo['tax_id']).'|'
						.'0000000000|'
						.$branch.'|'
						.$empinfo['idcard_nr'].'|'
						.'0000000000|'
						.$row['title'].'|'
						.$row['firstname'].'|'
						.$row['lastname'].'|'
						.$row['address'].'|'
						.$row['province'].'|'
						.$empinfo['postnr'].'|'
						.$_SESSION['rego']['curr_month']'|'
						.$_SESSION['rego']['th_year']'|'
						.'1|'
						.$paydate.'|'
						.'0|'
						.number_format($gross,2,'.','').'|'
						.number_format($row['tax'],2,'.','').'|'
						.'1'
						."\r\n";
			}
		}
	}
	
	$data = iconv(mb_detect_encoding($txt), "TIS-620", $txt);
	
	$filename = 'pnd1.txt';

	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($filename, $data);
	

















