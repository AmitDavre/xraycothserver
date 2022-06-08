<?php
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST);
	//var_dump($_FILES);
	//exit;
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
		
	//var_dump($sys_settings);exit;
	
//$_REQUEST['cid'] = 'rego';
//$_REQUEST['dbname'] = 'xhr0102_employees_2018';
	
	$dir = '../../'.$cid.'/employees/';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}

	if(!empty($_FILES)) {
		 $tempFile = $_FILES['file']['tmp_name'];
		 $targetFile =  $dir. $_FILES['file']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}
	//$targetFile = '../../docs/rego01000_employees.xlsx';
	//$emp_id = $_REQUEST['prefix'].'-'.sprintf('%04d', $nr);
	
	$datearray = array('birthdate','joining_date','probation_date','resign_date','drvlicense_exp','idcard_exp','pvf_reg_date');
	//$datearray = array('birthdate');
	//var_dump($prefix); exit;
	
	$sheetData = array();
	$inputFileName = $targetFile; 
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$reader->setReadDataOnly(true); 
	$reader->setReadEmptyCells(false);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, false);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs
	//var_dump($sheetData[1]); // database field names ///////////////////////////
	//var_dump($sheetData[2]); // excel file real headers ////////////////////////
	//exit;
	//var_dump($sheetData[1]); exit;
	$type = $sheetData[0][0]; 
	//var_dump($sys_settings); //exit;
	if($type == 'NEW' && $sys_settings['auto_id']){
		$prefix = explode(',', $sys_settings['id_prefix']);
		$nr = $sys_settings['id_start'];
		foreach($prefix as $v){
			$ids[$v] = (int)$nr;
		}
		foreach($prefix as $v){
			$sql = "SELECT emp_id FROM ".$cid."_employees WHERE emp_id LIKE '".$v."%' ORDER BY emp_id DESC LIMIT 1";
			if($res = $dbc->query($sql)){
				while($row = $res->fetch_assoc()){
					$tmp = explode('-',$row['emp_id']);
					$ids[$v] = (int)$tmp[1]+1;
				}
			}
		}
	}
	//var_dump($ids); //exit;
	
	$field = $sheetData[1];
	$field = array_filter($field);
	//var_dump($field); exit;
	unset($sheetData[0], $sheetData[1], $sheetData[2]);
	$tax_settings = unserialize($rego_settings['tax_settings']);
	//var_dump($sheetData); //exit;
	
	$data = array();
	foreach($sheetData as $key=>$val){
		if(!empty($val[0])){
			if($type == 'NEW' && $sys_settings['auto_id']){
				$pref = $val[0];
				$val[0] .= '-'.sprintf('%04d', $ids[$pref]);
				$ids[$pref] ++;
			}
			foreach($val as $k=>$v){
				if(isset($field[$k])){
					if($type == 'NEW' && $field[$k] == 'emp_id'){
						$data[$key]['emp_id'] = $val[0];
						$data[$key]['emp_group'] = 's';
					}
					if(in_array($field[$k],$datearray) && !empty($v)){
						$str = str_replace('/','-',$v);
						if(is_numeric($v)){
							$v = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($str));
						}else{
							$date = new DateTime($str);
							$v = date_format($date, "Y-m-d");
						}
						$year = date('Y', strtotime($v));
						if($year > (date('Y') + 200)){
							$year -= 543;
							$v =$year.date('-m-d', strtotime($v));
						}
						if($field[$k] == 'joining_date'){
							$v = date('Y-m-d', strtotime($v));
						}
					}
					if($field[$k] == 'idcard_nr' || $field[$k] == 'tax_id'){
						$v = str_replace(' ','', $v);
						$v = str_replace('-','', $v);
						$v = str_replace('/','', $v);
					}
					$data[$key][$field[$k]] = $v;
					if($field[$k] == 'team'){

					
						if($v == '')
						{
							$data[$key]['team'] = 1;
							$data[$key]['teams'] = 'main';
							$data[$key]['team_name'] = 'MAIN';
							$data[$key]['entity'] = 1;
							$data[$key]['branch'] = 1;
							$data[$key]['division'] = 1;
							$data[$key]['department'] = 1;


						}
						else
						{
							$team_name_val =$teams[$v]['code'] ;

							$lowercaseteams = strtolower($team_name_val);

							$stringlowercaseteams = str_replace(' ', '', $lowercaseteams);




							$data[$key]['team'] =$v;
							$data[$key]['teams'] =$stringlowercaseteams;
							$data[$key]['team_name'] =strtoupper($stringlowercaseteams);
							$data[$key]['entity'] = $teams[$v]['entity'];
							$data[$key]['branch'] = $teams[$v]['branch'];
							$data[$key]['division'] = $teams[$v]['division'];
							$data[$key]['department'] = $teams[$v]['department'];
						}
						
					}
					else
					{
						$data[$key]['team'] = 1;
						$data[$key]['teams'] = 'main';
						$data[$key]['team_name'] = 'MAIN';
						$data[$key]['entity'] = 1;
						$data[$key]['branch'] = 1;
						$data[$key]['division'] = 1;
						$data[$key]['department'] = 1;
					}

				}

						
			}
		}
	}
	//var_dump($data); exit;

	// echo '<pre>';
	// print_r($data);
	// echo '</pre>';

	// die('ddd');




	foreach($data as $key => $val){
		$total_deductions = 0;
		if(isset($val['tax_spouse'])){
			if($val['tax_spouse'] == 'Y'){
				$data[$key]['tax_allow_spouse'] = $tax_settings['spouse_allowance'];
				$total_deductions += $tax_settings['spouse_allowance'];
			}else{
				$data[$key]['tax_allow_spouse'] = 0;
			}
		}
		if(isset($val['tax_parents'])){
			if($val['tax_parents'] > 0){
				$data[$key]['tax_allow_parents'] = $tax_settings['parents_allowance'] * $val['tax_parents'];
				$total_deductions += ($tax_settings['parents_allowance'] * $val['tax_parents']);
			}else{
				$data[$key]['tax_allow_parents'] = 0;
			}
		}
		if(isset($val['tax_parents_inlaw'])){
			if($val['tax_parents_inlaw'] > 0){
				$data[$key]['tax_allow_parents_inlaw'] = $tax_settings['parents_inlaw_allowance'] * $val['tax_parents_inlaw'];
				$total_deductions += ($tax_settings['parents_inlaw_allowance'] * $val['tax_parents_inlaw']);
			}else{
				$data[$key]['tax_allow_parents_inlaw'] = 0;
			}
		}
		if(isset($val['tax_disabled_person'])){
			if($val['tax_disabled_person'] > 0){
				$data[$key]['tax_allow_disabled_person'] = $tax_settings['disabled_allowance'] * $val['tax_disabled_person'];
				$total_deductions += ($tax_settings['disabled_allowance'] * $val['tax_disabled_person']);
			}else{
				$data[$key]['tax_allow_disabled_person'] = 0;
			}
		}
		if(isset($val['tax_child_bio'])){
			if($val['tax_child_bio'] > 0){
				$data[$key]['tax_allow_child_bio'] = $tax_settings['child_allowance'] * $val['tax_child_bio'];
				$total_deductions += ($tax_settings['child_allowance'] * $val['tax_child_bio']);
			}else{
				$data[$key]['tax_allow_child_bio'] = 0;
			}
		}
		if(isset($val['tax_child_bio_2018'])){
			if($val['tax_child_bio_2018'] > 0){
				$data[$key]['tax_allow_child_bio_2018'] = $tax_settings['child_allowance_2018'] * $val['tax_child_bio_2018'];
				$total_deductions += ($tax_settings['child_allowance_2018'] * $val['tax_child_bio_2018']);
			}else{
				$data[$key]['tax_allow_child_bio_2018'] = 0;
			}
		}
		if(isset($val['tax_child_adopted'])){
			if($val['tax_child_adopted'] > 0){
				$data[$key]['tax_allow_child_adopted'] = $tax_settings['child_adopt_allowance'] * $val['tax_child_adopted'];
				$total_deductions += ($tax_settings['child_adopt_allowance'] * $val['tax_child_adopted']);
			}else{
				$data[$key]['tax_allow_child_adopted'] = 0;
			}
		}
		if(isset($val['tax_allow_child_birth'])){
			$total_deductions += $val['tax_allow_child_birth'];
		}
		if(isset($val['tax_allow_first_home'])){
			$total_deductions += $val['tax_allow_first_home'];
		}
		if(isset($val['tax_allow_donation_charity'])){
			$total_deductions += $val['tax_allow_donation_charity'];
		}
		if(isset($val['tax_allow_donation_education'])){
			$total_deductions += $val['tax_allow_donation_education'];
		}
		if(isset($val['tax_allow_donation_flood'])){
			$total_deductions += $val['tax_allow_donation_flood'];
		}
		if(isset($val['tax_allow_own_health'])){
			$total_deductions += $val['tax_allow_own_health'];
		}
		if(isset($val['tax_allow_health_parents'])){
			$total_deductions += $val['tax_allow_health_parents'];
		}
		if(isset($val['tax_allow_health_parents'])){
			$total_deductions += $val['tax_allow_health_parents'];
		}
		if(isset($val['tax_allow_own_life_insurance'])){
			$total_deductions += $val['tax_allow_own_life_insurance'];
		}
		if(isset($val['tax_allow_life_insurance_spouse'])){
			$total_deductions += $val['tax_allow_life_insurance_spouse'];
		}
		if(isset($val['tax_allow_pension_fund'])){
			$total_deductions += $val['tax_allow_pension_fund'];
		}
		if(isset($val['tax_allow_rmf'])){
			$total_deductions += $val['tax_allow_rmf'];
		}
		if(isset($val['tax_allow_ltf'])){
			$total_deductions += $val['tax_allow_ltf'];
		}
		if(isset($val['tax_exemp_disabled_under'])){
			if($val['tax_exemp_disabled_under'] == 'Y'){
				$data[$key]['tax_allow_exemp_disabled_under'] = $tax_settings['exemp_disabled_under'];
				$total_deductions += $tax_settings['exemp_disabled_under'];
			}else{
				$data[$key]['tax_allow_exemp_disabled_under'] = 0;
			}
		}
		if(isset($val['tax_exemp_payer_older'])){
			if($val['tax_exemp_payer_older'] == 'Y'){
				$data[$key]['tax_allow_exemp_payer_older'] = $tax_settings['exemp_payer_older'];
				$total_deductions += $tax_settings['exemp_payer_older'];
			}else{
				$data[$key]['tax_allow_exemp_payer_older'] = 0;
			}
		}
		if(isset($val['tax_allow_domestic_tour'])){
			$total_deductions += $val['tax_allow_domestic_tour'];
		}
		if(isset($val['tax_allow_year_end_shopping'])){
			$total_deductions += $val['tax_allow_year_end_shopping'];
		}
		if(isset($val['tax_allow_other'])){
			$total_deductions += $val['tax_allow_other'];
		}




		//$data[$key]['total_tax_deductions'] = $total_deductions;
		//$data[$key]['emp_tax_deductions'] = $total_deductions;
	}
	reset($data);
		

	if($data){	
		$sql = "INSERT INTO ".$_SESSION['rego']['cid']."_employees (";
		foreach($data[key($data)] as $key=>$val){
			$sql .= $key.', ';
		}
		//echo $sql; exit;
		
		$sql = substr($sql,0,-2);
		$sql .= ') VALUES (';
		foreach($data as $key=>$val){
			foreach($val as $k=>$v){
				$sql .= "'".$dbc->real_escape_string($v)."', ";
			}
			$sql = substr($sql,0,-2);
			$sql .= '),(';
		}
		$sql = substr($sql,0,-2);
		//echo $sql;
		//exit;
		
		reset($data);
		$sql .= " ON DUPLICATE KEY UPDATE ";
		foreach($data[key($data)] as $key=>$val){
			$sql .= $key." = VALUES(".$key."), ";
		}
		$sql = substr($sql,0,-2);
		//echo $sql; exit;
		
		if($res = $dbc->query($sql)){
			foreach($data as $key=>$val){
				
				/*if(isset($val['tax_spouse'])){
					if($val['tax_spouse'] == 'Y'){
						$data[$key]['tax_allow_spouse'] = $tax_settings['spouse_allowance'];
					}
				}
				if(isset($val['tax_parents'])){
					if($val['tax_parents'] > 0){
						$data[$key]['tax_allow_parents'] = $tax_settings['parents_allowance'] * $val['tax_parents'];
					}
				}
				if(isset($val['tax_parents_inlaw'])){
					if($val['tax_parents_inlaw'] > 0){
						$data[$key]['tax_allow_parents_inlaw'] = $tax_settings['parents_inlaw_allowance'] * $val['tax_parents_inlaw'];
					}
				}
				if(isset($val['tax_disabled_person'])){
					if($val['tax_disabled_person'] > 0){
						$data[$key]['tax_allow_disabled_person'] = $tax_settings['disabled_allowance'] * $val['tax_disabled_person'];
					}
				}
				if(isset($val['tax_child_bio'])){
					if($val['tax_child_bio'] > 0){
						$data[$key]['tax_allow_child_bio'] = $tax_settings['child_allowance'] * $val['tax_child_bio'];
					}
				}
				if(isset($val['tax_child_bio_2018'])){
					if($val['tax_child_bio_2018'] > 0){
						$data[$key]['tax_allow_child_bio_2018'] = $tax_settings['child_allowance_2018'] * $val['tax_child_bio_2018'];
					}
				}
				if(isset($val['tax_child_adopted'])){
					if($val['tax_child_adopted'] > 0){
						$data[$key]['tax_allow_child_adopted'] = $tax_settings['child_adopt_allowance'] * $val['tax_child_adopted'];
					}
				}
				if(isset($val['tax_exemp_disabled_under'])){
					if($val['tax_exemp_disabled_under'] == 'Y'){
						$data[$key]['tax_allow_exemp_disabled_under'] = $tax_settings['exemp_disabled_under'];
					}
				}
				if(isset($val['tax_exemp_payer_older'])){
					if($val['tax_exemp_payer_older'] == 'Y'){
						$data[$key]['tax_allow_exemp_payer_older'] = $tax_settings['exemp_payer_older'];
					}
				}*/
				
				$joining_date = '';
				$probation_date = '';
				if($sys_settings['joining_date'] != 'empty'){
					$joining_date = date('Y-m-d');
					$probation_date = date('d-m-Y', strtotime(date('Y-m-d').'+ 4 months'));
				}
				$date_position = '';
				if($sys_settings['date_start'] != 'empty'){
					$date_position = date('d-m-Y');
				}
				//var_dump($sys_settings['contract_type']);
				//var_dump($sys_settings['calc_base']);

				
					$newteamVar  = $val['team'] ;
				
				$newteamVar1  = $val['teams'] ;
				$newteamVar2  =  $val['team_name'];

				$teamValArray =  array(

					'teams' => $newteamVar1,
					'team_name' => $newteamVar2,

				);

				$serializeTeam = serialize($teamValArray);



				
				$sql = "UPDATE ".$_SESSION['rego']['cid']."_employees SET 
					th_name = CONCAT(firstname, ' ', lastname), 
					en_name = IF(en_name = '', th_name, en_name), 
					joining_date = IF(joining_date = '', '".$joining_date."', joining_date), 
					probation_date = IF(probation_date = '', '".$probation_date."', probation_date), 
					
					entity = IF(entity = '', '".$teams[$newteamVar]['entity']."', entity), 
					branch = IF(branch = '', '".$teams[$newteamVar]['branch']."', branch), 
					division = IF(division = '', '".$teams[$newteamVar]['division']."', division), 
					department = IF(department = '', '".$teams[$newteamVar]['department']."', department), 
					team = IF(team = '', '".$newteamVar."', team), 
					teams = IF(teams = '', '".$newteamVar1."', teams), 
					team_name = IF(team_name = '', '".$newteamVar2."', team_name), 
					attach4 = IF(attach4 = '', '".$serializeTeam."', attach4), 
					
					emp_group = IF(emp_group = '', '".$sys_settings['emp_group']."', emp_group), 
					emp_type = IF(emp_type = '', '".$sys_settings['emp_type']."', emp_type), 
					emp_status = IF(emp_status = '', '".$sys_settings['emp_status']."', emp_status), 
					account_code = IF(account_code = '', '".$sys_settings['account_code']."', account_code), 
					
					position = IF(position = '', '".$sys_settings['position']."', position), 
					date_position = IF(date_position = '', '".$date_position."', date_position), 
					time_reg = IF(time_reg = '', '".$sys_settings['time_reg']."', time_reg), 
					selfie = IF(selfie = '', '".$sys_settings['selfie']."', selfie), 
					annual_leave = IF(annual_leave = '', '".$sys_settings['leeve']."', annual_leave), 
					pay_type = IF(pay_type = '', '".$sys_settings['pay_type']."', pay_type), 
					
					day_rate = IF(day_rate = 0, base_salary/30, day_rate), 
					hour_rate = IF(hour_rate = 0, base_salary/30/8, hour_rate), 
					
					calc_psf = IF(calc_psf = '', '".$sys_settings['calc_psf']."', calc_psf), 
					psf_rate_emp = IF(psf_rate_emp = '', '".$sys_settings['psf_rate_emp']."', psf_rate_emp), 
					psf_rate_com = IF(psf_rate_com = '', '".$sys_settings['psf_rate_com']."', psf_rate_com), 
					
					calc_pvf = IF(calc_pvf = '', '".$sys_settings['calc_pvf']."', calc_pvf), 
					pvf_rate_emp = IF(pvf_rate_emp = '', '".$sys_settings['pvf_rate_emp']."', pvf_rate_emp), 
					pvf_rate_com = IF(pvf_rate_com = '', '".$sys_settings['pvf_rate_com']."', pvf_rate_com), 
					
					calc_method = IF(calc_method = '', '".$sys_settings['calc_method']."', calc_method), 
					calc_tax = IF(calc_tax = '', '".$sys_settings['calc_tax']."', calc_tax), 
					calc_sso = IF(calc_sso = '', '".$sys_settings['calc_sso']."', calc_sso),
					
					contract_type = IF(contract_type = '', '".$sys_settings['contract_type']."', contract_type), 
					calc_base = IF(calc_base = '', '".$sys_settings['calc_base']."', calc_base), 
					base_ot_rate = IF(base_ot_rate = '', '".$sys_settings['base_ot_rate']."', base_ot_rate), 
					ot_rate = IF(ot_rate = '', '".$sys_settings['ot_rate']."', ot_rate), 
					allow_login = IF(allow_login = '', '0', allow_login), 
					
					tax_spouse = IF(tax_spouse = '', 'N', tax_spouse), 
					tax_exemp_disabled_under = IF(tax_exemp_disabled_under = '', 'N', tax_exemp_disabled_under), 
					tax_exemp_payer_older = IF(tax_exemp_payer_older = '', 'N', tax_exemp_payer_older)  
	
					WHERE emp_id = '".$val['emp_id']."'";
					//echo $sql; exit;
				if($dbc->query($sql)){
					//echo 'success';
				}else{
					var_dump(mysqli_error($dbc));
				}
				//var_dump($sql);
			}
			include('calculate_emp_taxdeductions_wage_rate.php');
			//setEmployeeDefaults();
			ob_clean();
			echo 'success';
			exit;
		}
		
	}
?>
















