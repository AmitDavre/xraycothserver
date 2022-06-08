<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	//$_GET['bank'] = 'other';
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$tot_salary = 0;
	
	$account = str_replace('-', '', $compinfo['bank_account']);
	$txt = 'H'.sprintf("%06d",1);
	$txt .= sprintf("%03d",$compinfo['bank_name']);
	$txt .= sprintf("%010d", $account);
	
	$tmp = $compinfo[$lang.'_compname'];
	if($lang == 'en'){
		$tmp = preg_replace("/[^a-zA-Z0-9\s]/", "", $compinfo[$lang.'_compname']);
		$tmp = preg_replace('/\s+/', ' ',$tmp);
	}
	$compname = mb_substr($tmp, 0, 25);
	$txt .= $compname;
	if(mb_strlen($compname) < 25){
		$txt .= str_repeat(' ', 25 - mb_strlen($compname));
	}
	$txt .= date('dmy');
	$txt .= str_repeat('0', 77);
	$txt .= "\r\n";
	
	$nr = 2; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$tmp = round($row['net_income'],2);
				$salary = $tmp * 100;
				$empinfo = getEmployeesByBank($cid, $row['emp_id']);
				//var_dump($empinfo);
				if(($_GET['bank'] == '002' && $empinfo['bank_code'] == '002') || $_GET['bank'] == 'all'){
					$name = '';//trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$name = preg_replace('!\s+!', ' ', $name);
					$len = mb_strlen($name);
					$txt .= 'D'.sprintf("%06d",$nr);
					$txt .= sprintf("%03d",$empinfo['bank_code']);
					$txt .= sprintf("%010d", $empinfo['bank_account']);
					$txt .= 'C'.sprintf("%010d",$salary).'029'; // Payroll service
					$txt .= str_repeat('0', 59);
					$txt .= $name.str_repeat(' ', (35-$len));
					$txt .= "\r\n";
					$nr++;
					$tot_salary += round($row['net_income'],2);
				}elseif($_GET['bank'] == 'other' && $empinfo['bank_code'] != '002'){
					$name = '';//trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
					$name = preg_replace('!\s+!', ' ', $name);
					$len = mb_strlen($name);
					$txt .= 'D'.sprintf("%06d",$nr);
					$txt .= sprintf("%03d",$empinfo['bank_code']);
					$txt .= sprintf("%010d", $empinfo['bank_account']);
					$txt .= 'C'.sprintf("%010d",$salary).'029'; // Payroll service
					$txt .= str_repeat('0', 59);
					$txt .= $name.str_repeat(' ', (35-$len));
					$txt .= "\r\n";
					$nr++;
					$tot_salary += round($row['net_income'],2);
				}
			}
			$tot_salary = str_replace('.','',$tot_salary);
			$txt .= 'T'.sprintf("%06d",$nr);
			$txt .= sprintf("%03d",$compinfo['bank_name']);
			$txt .= sprintf("%010d", $account);
			$txt .= sprintf("%027d",($nr-2));
			$txt .= sprintf("%013d",$tot_salary);
			$txt .= str_repeat('0', 68);
		}
	}
	
	$data = iconv(mb_detect_encoding($txt), "TIS-620", $txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' BKB Textfile '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'].'.txt';
	$doc = $filename;
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	//include('../print/save_to_documents.php');
	
	exit;
	
	
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' Bankok Bank textfile '.$_SESSION['rego']['year_th'].' '.$_SESSION['rego']['curr_month'].'.txt';
	$doc = 'SSO upload textfile';
	
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($txt);
	
	$fp = fopen($dir.$filename, 'wb');
	fwrite($fp,$txt);
	fclose($fp);	
	
	//include('../print/save_to_documents.php');

	exit;






