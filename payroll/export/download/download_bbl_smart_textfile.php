<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	if(!isset($_GET['date'])){$_GET['date'] = date('d-m-Y');}
	//$_GET['bank'] = 'other';
	
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

	$smart_txt = 'H';
	$smart_txt .= sprintf("%06d",1);
	$smart_txt .= '002';
	$smart_txt .= '0000';
	$smart_txt .= sprintf("%011d", $_account);
	$smart_txt .= $compname;
	$smart_txt .= $date;
	$smart_txt .= 'UN1 ';
	$smart_txt .= str_repeat('0', 68);
	$smart_txt .= "\r\n";;
	
	$nr = 2;
	$tot_salary = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', $_GET['bank']);
				//var_dump($empinfo);
				if($empinfo){
					$tmp = round($row['net_income'],2);
					$salary = $tmp * 100;
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){
						$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);
					}
					$name = preg_replace('!\s+!', ' ', $name);
					$name = mb_substr($name, 0, 30);
					if(mb_strlen($name) < 30){
						$name .= str_repeat(' ', 30 - mb_strlen($name));
					}
					$account = str_replace('-', '', $empinfo['bank_account']);
					$smart_txt .= 'D';
					$smart_txt .= sprintf("%06d",$nr);
					$smart_txt .= sprintf("%03d",$empinfo['bank_code']);
					$smart_txt .= sprintf("%04d", substr($account,0,3));
					$smart_txt .= sprintf("%011d", $account);
					$smart_txt .= 'C'.str_repeat('0', (12 - mb_strlen($salary)));
					$smart_txt .= $salary;
					$smart_txt .= '01'; // Payroll service
					$smart_txt .= $name;
					$smart_txt .= str_repeat(' ', 28).'00';
					$smart_txt .= str_repeat(' ', 27).'0';
					$smart_txt .= "\r\n";;
					$nr++;
					$tot_salary += $salary;
				}
			}
			$smart_txt .= 'T'.sprintf("%06d",$nr);
			$smart_txt .= '002';
			$smart_txt .= sprintf("%011d", $_account);
			$smart_txt .= sprintf("%07d",'0');
			$smart_txt .= sprintf("%013d",'0');
			$smart_txt .= sprintf("%07d",($nr-2));
			$smart_txt .= str_repeat('0', (13 - mb_strlen($tot_salary)));
			$smart_txt .= $tot_salary;
			$smart_txt .= str_repeat('0', 67);
		}
	}
	
	$data = iconv(mb_detect_encoding($smart_txt), "TIS-620", $smart_txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' BBL Smartpay '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'].'.txt';
	$doc = $filename;
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	//include('../print/save_to_documents.php');
	
	exit;





