<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	if(!isset($_GET['date'])){$_GET['date'] = date('d-m-Y');}
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$banks = array();
	$tmp = unserialize($edata['banks']);
	if($tmp){
		foreach($tmp as $k=>$v){
			$banks[$v['code']]['name'] = $v['name'];
			$banks[$v['code']]['number'] = $v['number'];
		}
	}

	$_account = str_replace('-', '', $banks['011']['number']);
	$compname = substr($banks['011']['name'], 0, 50);
	
	
	
	$tot_salary = 0;
	$prdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '004', 'all');
				//var_dump($empinfo);
				if($empinfo){
					//if($empinfo['bank_code'] == '002'){
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){
							$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);
						}
						$name = preg_replace('!\s+!', ' ', $name);
						$prdata[$row['emp_id']]['name'] = $name;
						$prdata[$row['emp_id']]['account'] = str_replace('-', '', $empinfo['bank_account']);
						$prdata[$row['emp_id']]['salary'] = number_format($row['net_income'],2);
						$tmp = round($row['net_income'],2);
						$salary = $tmp * 100;
						$prdata[$row['emp_id']]['salary'] = $salary;
						$tot_salary += $salary;
					//}
				}
			}
		}
	}
	
	$payroll_txt = "HDCT".str_repeat(' ', 16);
	$payroll_txt .= "000000".str_repeat(' ', 15);
	$payroll_txt .= str_replace('-', '', $_account).' ';
	$payroll_txt .= sprintf("%015d",$tot_salary);
	$payroll_txt .= " 201030".str_repeat(' ', 25);
	$payroll_txt .= $compname.str_repeat(' ', (50 - mb_strlen($compname)));
	$payroll_txt .= date('ymd').sprintf("%018d", count($prdata)).'N'; // N = Fee Payer  - Y = Fee Beneficiary
	$payroll_txt .= "\r\n";


	$nr = 1;
	foreach($prdata as $v){
		$payroll_txt .= "D".sprintf("%06d", $nr).str_repeat(' ', 14);
		$payroll_txt .= $v['account'].' ';
		$payroll_txt .= sprintf("%015d",$v['salary']).' 201030'.str_repeat(' ', 25);
		$payroll_txt .= $v['name'].str_repeat(' ', (50 - mb_strlen($v['name'])));
		$payroll_txt .= date('ymd').str_repeat('0', 4).'1234';
		$payroll_txt .= "\r\n";
		$nr++;
	}
	
	
	
	/*var_dump(count($data)); //exit;
	var_dump(number_format($tot_salary,2)); //exit;
	var_dump($data); exit;*/
	
	$data = iconv(mb_detect_encoding($payroll_txt), "TIS-620", $payroll_txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' Kasikorn Payroll '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'].'.txt';
	$doc = $filename;
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	//include('../print/save_to_documents.php');
	
	exit;





