<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');

	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	//$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$branch = sprintf("%06d",$sso_codes[$_SESSION['rego']['gov_branch']]['code']);
	
	$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_th'],-2);
	
	$sso_act_max = $pr_settings['sso_act_max'];
	$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate_emp'], 2, '', ''));
	
	$title = array(1=>'003',2=>'004',3=>'005');
	$pd = explode('-',$_SESSION['rego']['paydate']);
	//var_dump($pd);
	$d = sprintf("%02d",$pd[0]);
	$m = sprintf("%02d",$pd[1]);
	$y = substr(($pd[2]+543),-2);
	$paydate = $d.$m.$y;
	
	$txt = '';
	//$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_th'],-2);
	//$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate'], 2, '', ''));
	/*if($cur_month <= 2 && $_SESSION['rego']['cur_year'] == 2020){
		$sso_rate = sprintf("%04d",number_format(5, 2, '', ''));
	}*/
	if(mb_strlen($edata['th_compname']) > 45){
		$compname = mb_substr($edata['th_compname'],0,45);
	}else{
		$compname = $edata['th_compname'].str_repeat(' ', (45 - mb_strlen($edata['th_compname'])));
	}

	//$sso_act_max = $pr_settings['sso_act_max'];
	
	$tot_wages = 0; $tot_sso = 0; $sso = 0; $ssoc = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_sso = 1";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = prGetEmployeeInfo($row['emp_id']);
				$fix_allow = 0; 
				for($i=1;$i<=10;$i++){
					$fix_allow += $row['fix_allow_'.$i];
				}
				$data[$row['emp_id']][] = $empinfo['idcard_nr'];
				$data[$row['emp_id']][] = $title[$empinfo['title']];
				
				$fn = mb_strlen($empinfo['firstname'], 'UTF-8');
				$ln = mb_strlen($empinfo['lastname'], 'UTF-8');
				$data[$row['emp_id']][] = mb_substr($empinfo['firstname'],0,30, 'UTF-8').str_repeat(' ', 30-$fn);
				$data[$row['emp_id']][] = mb_substr($empinfo['lastname'],0,35, 'UTF-8').str_repeat(' ', 35-$ln);
				
				$basicsalary = $row['salary'] + $fix_allow;
				if($sso_act_max == 'act'){
					$basic_salary = $basicsalary;
				}else{
					$basic_salary = ($basicsalary > $pr_settings['sso_max_wage'] ? $pr_settings['sso_max_wage'] : $basicsalary);
				}
				$basic_salary = ($basicsalary < $pr_settings['sso_min_wage'] ? $pr_settings['sso_min_wage'] : $basicsalary);
				$data[$row['emp_id']][] = number_format($basic_salary,2,'','');
				$data[$row['emp_id']][] = number_format($row['social'],2,'','');
				$tot_wages += $basic_salary; 
				
				$sso += $row['social'];
				$ssoc += $row['social_com'];
			}
			$sso = $sso * 100;
			$ssoc = $ssoc * 100;
			$total_sso = $sso + $ssoc;
			$tot_wages = number_format($tot_wages,2,'','');
			$branch = sprintf("%06d",$branch);
		
			$txt 	= '1'
					.str_replace('-','',$edata['sso_account'])
					.$branch
					.$paydate
					.$period
					.$compname
					.$sso_rate
					.sprintf("%06d",count($data))
					.sprintf("%015d",$tot_wages)
					.sprintf("%014d",$total_sso)
					.sprintf("%012d",$sso)
					.sprintf("%012d",$ssoc)
					."\n";
					
			foreach($data as $k=>$v){
				$fn = mb_strlen($v[2]);
				$ln = mb_strlen($v[3]);
				$txt 	.= '2'
						.str_replace('-','',$v[0])
						.$v[1]
						.$v[2]
						.$v[3]
						.sprintf("%014d",$v[4])
						.sprintf("%012d",$v[5])
						.str_repeat(' ', 27)
						."\n";
			}
		}
	}
	//var_dump($data);
	//exit;
	
	$data = iconv(mb_detect_encoding($txt), "TIS-620", $txt);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = strtoupper($_SESSION['rego']['cid']).' SSO textfile '.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_th'].'.txt';
	$doc = 'SSO upload textfile';
	
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=".$filename);
	
	ob_clean();
	echo trim($data);
	file_put_contents($dir.$filename, $data);
	//include('../print/save_to_documents.php');

	exit;











