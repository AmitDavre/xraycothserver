<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	if(!isset($_GET['date'])){$_GET['date'] = date('d-m-Y');}
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$banks = array();
	$tmp = unserialize($edata['banks']);
	if($tmp){
		foreach($tmp as $k=>$v){
			$banks[$v['code']]['name'] = $v['name'];
			$banks[$v['code']]['number'] = $v['number'];
		}
	}
	
	
	$_account = str_replace('-', '', $banks['002']['number']);
	$bank_account = $banks['002']['number'];
	
	$tmp = $banks['002']['name'];
	if($lang == 'en'){
		$tmp = preg_replace("/[^a-zA-Z0-9\s]/", "", $tmp);
		$tmp = preg_replace('/\s+/', ' ',$tmp);
	}
	$compname = mb_substr($tmp, 0, 25);
	if(mb_strlen($compname) < 25){
		$compname .= str_repeat(' ', 25 - mb_strlen($compname));
	}

	$date = date('dmy', strtotime($_GET['date']));
	
	$payroll_txt = 'H';
	$payroll_txt .= sprintf("%06d",1);
	$payroll_txt .= '002';
	$payroll_txt .= sprintf("%010d", $_account);
	$payroll_txt .= $compname;
	$payroll_txt .= $date;
	$payroll_txt .= str_repeat('0', 77);
	$payroll_txt .= "\r\n";
	
	$nr = 2;
	$tot_salary = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', '002');
				//var_dump($empinfo);
				if($empinfo){
					if($empinfo['bank_code'] == '002'){
						$tmp = round($row['net_income'],2);
						$salary = $tmp * 100;
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){
							$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);
						}
						$name = preg_replace('!\s+!', ' ', $name);
						$name = mb_substr($name, 0, 35);
						if(mb_strlen($name) < 35){
							$name .= str_repeat(' ', 35 - mb_strlen($name));
						}
						$account = str_replace('-', '', $empinfo['bank_account']);
						$payroll_txt .= 'D'.sprintf("%06d",$nr);
						$payroll_txt .= sprintf("%03d",$empinfo['bank_code']);
						$payroll_txt .= sprintf("%010d", $account);
						
						$payroll_txt .= 'C'.str_repeat('0', (10 - mb_strlen($salary)));
						$payroll_txt .= $salary;
						$payroll_txt .= '029'; // Payroll service
						$payroll_txt .= str_repeat('0', 59);
						$payroll_txt .= $name;
						$payroll_txt .= "\r\n";
						$nr++;
						$tot_salary += $salary;
					}
				}
			}
			$payroll_txt .= 'T'.sprintf("%06d",$nr);
			$payroll_txt .= '002';
			$payroll_txt .= sprintf("%010d", $_account);
			$payroll_txt .= sprintf("%027d",($nr-2));
			$payroll_txt .= str_repeat('0', (13 - mb_strlen($tot_salary)));
			$payroll_txt .= $tot_salary;
			$payroll_txt .= str_repeat('0', 68);
		}
	}
	
	$data = iconv(mb_detect_encoding($payroll_txt), "TIS-620", $payroll_txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' BBL Payroll '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'].'.txt';
	$doc = $filename;
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	//include('../print/save_to_documents.php');
	
	exit;





