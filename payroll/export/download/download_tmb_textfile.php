<?php
  
	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	
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

	//$_account = str_replace('-', '', $banks['011']['number']);
	//$compname = substr($banks['011']['name'], 0, 25);
	/*if(mb_strlen($compname) < 25){
		$compname .= str_repeat(' ', 25 - mb_strlen($compname));
	}*/

	$account = str_replace('-', '', $compinfo['bank_account']);
	$compname = substr($compinfo['en_compname'], 0, 60);
	
	$txt = "";
	$tot_salary = 0;
	$nr = 1;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '011', '011');
				//var_dump($empinfo);
				if($empinfo){

					$tmp = number_format($row['net_income'],2);
					$tmp = str_replace(',','',$tmp);
					$salary = str_replace('.','',$tmp);
					$tot_salary += $salary;
					$empinfo = getEmployeeInfo($cid, $row['emp_id']);
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
					$name = preg_replace('!\s+!', ' ', $name);
					$name = mb_substr($name, 0, 60);
					if(mb_strlen($name) < 60){
						$name .= str_repeat(' ', 60 - mb_strlen($name));
					}

					$txt .= '102100001';
					$txt .= sprintf("%03d",$empinfo['bank_code']);
					$txt .= sprintf("%04d", substr($empinfo['bank_account'],0,3));
					$txt .= sprintf("%011d", $empinfo['bank_account']);
					$txt .= sprintf("%03d",$compinfo['bank_name']);
					$txt .= sprintf("%04d", substr($account,0,3));
					$txt .= sprintf("%011d", $account);
					$txt .= date('dmY', strtotime($_SESSION['payroll']['paydate']));
					$txt .= '01';
					$txt .= sprintf("%014d",$salary);
					$txt .= $name;
					
					$txt .= $compname;
					if(strlen($compname) < 60){
						$txt .= str_repeat(' ', (60 - strlen($compname)));
					}
					$txt .= '0000000000';
					$txt .= str_repeat(' ', 90);
					$txt .= sprintf("%06d",$nr);
					$txt .= 'GI';
					$txt .= str_repeat(' ', 85);
					$txt .= "\r\n";

					$nr++;
				}
			}
		}
	}
					
	
	//var_dump($txt); exit;
	$txt = iconv(mb_detect_encoding($txt), "TIS-620", $txt);

	$dir = DIR.$cid.'/documents/';
	$root = ROOT.$cid.'/documents/';
	$filename = strtoupper($cid).' TMB Bank textfile '.$_SESSION['rego']['year_th'].' '.$_SESSION['rego']['curr_month'].'.txt';
	$doc = $filename;
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo $txt;
	file_put_contents($dir.$filename, $txt);
	//include('../print/save_to_documents.php');
	
	exit;


