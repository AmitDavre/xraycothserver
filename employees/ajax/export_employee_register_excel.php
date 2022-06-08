<?php
	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'time/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//var_dump($teams); exit;
	
	//$shiftteams = getShifTeams();
	//var_dump($teams); exit;
	
	$export_fields = unserialize($sys_settings['emp_export_fields']);
	//$entity_banks = getEntityBanks($_SESSION['rego']['entity']);
	//var_dump($entity_banks); exit;
	/*if(isset($export_fields['base_salary'])){
		$export_fields['eff_date'] = 'Effective date';
	}*/
	//var_dump($sys_settings); exit;
	
	$empType = $export_fields['empType'];
	unset($export_fields['empType']);
	$prefix = explode(',', $sys_settings['id_prefix']);

	//$where = '';
	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);
	$where = "WHERE emp_group = '".$_SESSION['rego']['emp_group']."'";
	$where .= " AND branch IN ('".$sbranches."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";
	if($empType != 'all'){
		$where .= " AND emp_status = '".$empType."'";
	}

	$pref = '';
	foreach($prefix as $v){$pref .= $v.',';}
	$pref = substr($pref,0,-1);
	
	$pos = '';
	foreach($positions as $k=>$v){$pos .= $k.',';}
	$pos = substr($pos,0,-1);
	
	$steam = '';
	//foreach($shiftteams as $k=>$v){$steam .= $k.',';}
	//$steam = substr($steam,0,-1);
	
	$team = '';
	foreach($teams as $k=>$v){$team .= $k.',';}
	$team = substr($team,0,-1);
	
	$bra = '';
	foreach($branches as $k=>$v){$bra .= $k.',';}
	$bra = substr($bra,0,-1);
	
	$div = '';
	foreach($divisions as $k=>$v){$div .= $k.',';}
	$div = substr($div,0,-1);
	
	$dep = '';
	foreach($departments as $k=>$v){$dep .= $k.',';}
	$dep = substr($dep,0,-1);
	
	$tea = '';
	foreach($teams as $k=>$v){$tea .= $k.',';}
	$tea = substr($tea,0,-1);
	
	$ptype = '';
	foreach($pay_type as $k=>$v){$ptype .= $k.',';}
	$ptype = substr($ptype,0,-1);
	//var_dump($ptype); exit;

	//var_dump($team); exit;
	/*$payfr = '';
	foreach($pay_frequency as $k=>$v){
		$payfr .= $k.',';
	}
	$payfr = substr($payfr,0,-1);*/
	//var_dump($payfr); //exit;
	
	$bank_codes = unserialize($rego_settings['bank_codes']);
	$bank = '';
	$bankCode = array();
	foreach($bank_codes as $k=>$v){
		if($v['apply'] == 1){
			$bank .= $k.',';
			$bankCode[$k] = $v[$lang];
		}
	}
	$bank = substr($bank,0,-1);
	$ebank = 'cash,cheque,'.$bank;
	//var_dump($ebank); exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$allBorders = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
			  ),
		 ),
	);
	
	$outBorders = array(
		 'borders' => array(
			  'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
			  ),
		 ),
	);
	
	$sheet->mergeCells('B1:G1');
	$sheet->setCellValue('B1', $sheet_txt);
	$sheet->getRowDimension(1)->setRowHeight(60); 
	$sheet->getStyle('B1')->getAlignment()->setWrapText(true);	
	$sheet->getStyle('B1')->getFont()->setSize(11);
	$sheet->getStyle('A1:G1')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('FFccffcc');
	$sheet->getStyle('A1:G1')->applyFromArray($allBorders);
	
	$rows = 50;
	//$hor_center = array();
	$nr=0;
	foreach($export_fields as $k=>$v){
		$abc = getNameFromNumber($nr);
		
		// PERSONAL /////////////////////////////////////
		if(!isset($_REQUEST['register'])){
			if($sys_settings['auto_id'] && $k == 'emp_id'){$prefix_col = $abc;}
		}
		if($k == 'title'){$title_col = $abc;}
		if($k == 'birthdate'){$birth_col = $abc;}
		if($k == 'gender'){$gender_col = $abc;}
		if($k == 'maritial'){$maritial_col = $abc;}
		if($k == 'religion'){$religion_col = $abc;}
		if($k == 'military_status'){$military_col = $abc;}
		if($k == 'drvlicense_exp'){$drvlicense_exp_col = $abc;}
		if($k == 'idcard_nr'){
			$idcard_col = $abc; 
			$sheet->getStyle($abc)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		}
		if($k == 'idcard_exp'){$idcard_exp_col = $abc;}
		if($k == 'tax_id'){
			$tax_id_col = $abc; 
			$sheet->getStyle($abc)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		}
		// WORK ////////////////////////////////////////////////
		if($k == 'joining_date'){$joining_col = $abc;}
		if($k == 'probation_date'){$probation_col = $abc;}
		if($k == 'team'){$team_col = $abc;}
		if($k == 'emp_group'){$emp_group_col = $abc;}
		if($k == 'emp_type'){$emp_type_col = $abc;}
		if($k == 'resign_date'){$resign_col = $abc;}
		if($k == 'emp_status'){$emp_status_col = $abc;}
		if($k == 'account_code'){$acc_code_col = $abc;}
		if($k == 'position'){$position_col = $abc;}
		if($k == 'head_branch'){$head_branch_col = $abc;}
		if($k == 'head_division'){$head_division_col = $abc;}
		if($k == 'head_department'){$head_department_col = $abc;}
		if($k == 'team_supervisor'){$team_supervisor_col = $abc;}
		if($k == 'date_position'){$date_position_col = $abc;}
		if($k == 'shift_team'){$shiftteam_col = $abc;}
		if($k == 'time_reg'){$time_reg_col = $abc;}
		if($k == 'selfie'){$selfie_col = $abc;}
		// FINANCIAL ////////////////////////////////////////////////
		if($k == 'bank_code'){
			$bank_code_col = $abc; 
			$sheet->getStyle($abc)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		}
		if($k == 'pay_type'){
			$pay_type_col = $abc;
			$sheet->getStyle($abc)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		}
		if($k == 'calc_psf'){$psf_col = $abc;}
		if($k == 'calc_pvf'){$pvf_col = $abc;}
		if($k == 'calc_method'){$calc_method_col = $abc;}
		if($k == 'calc_tax'){$tax_col = $abc;}
		if($k == 'pnd'){$pnd_col = $abc;}
		if($k == 'calc_sso'){$sso_col = $abc;}
		if($k == 'sso_by'){$sso_by_col = $abc;}
		// BENEFITS ////////////////////////////////////////////////
		if($k == 'contract_type'){$contract_type_col = $abc;}
		if($k == 'calc_base'){$calc_base_col = $abc;}
		if($k == 'base_ot_rate'){$base_ot_rate_col = $abc;}
		// TAX ////////////////////////////////////////////////
		if($k == 'tax_exemp_disabled_under'){$tax_exemp_disabled_under_col = $abc;}
		if($k == 'tax_exemp_payer_older'){$tax_exemp_payer_older_col = $abc;}
		if($k == 'tax_spouse'){$tax_spouse_col = $abc;}
		
		$nr++;
	}
	
	// DROPDOWN //////////////////////////////////////////////
	if(isset($prefix_col)){
		$xprefix = $sheet->getCell($prefix_col.'4')->getDataValidation();
		$xprefix->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xprefix->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xprefix->setAllowBlank(false);
		$xprefix->setShowInputMessage(true);
		$xprefix->setShowErrorMessage(true);
		$xprefix->setShowDropDown(true);
		$xprefix->setErrorTitle($lng['Input error']);
		$xprefix->setError($lng['Value is not in list']);
		$xprefix->setFormula1('"'.$pref.'"');	
		$sheet->setDataValidation($prefix_col.'4:'.$prefix_col.$rows, $xprefix);	
	}
	if(isset($title_col)){
		$xtitle = $sheet->getCell($title_col.'4')->getDataValidation();
		$xtitle->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xtitle->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xtitle->setAllowBlank(false);
		$xtitle->setShowInputMessage(true);
		$xtitle->setShowErrorMessage(true);
		$xtitle->setShowDropDown(true);
		$xtitle->setErrorTitle($lng['Input error']);
		$xtitle->setError($lng['Value is not in list']);
		$xtitle->setFormula1('"1,2,3"');	
		$sheet->setDataValidation($title_col.'4:'.$title_col.$rows, $xtitle);	
	}
	if(isset($gender_col)){
		$xgender = $sheet->getCell($gender_col.'4')->getDataValidation();
		$xgender->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xgender->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xgender->setAllowBlank(false);
		$xgender->setShowInputMessage(true);
		$xgender->setShowErrorMessage(true);
		$xgender->setShowDropDown(true);
		$xgender->setErrorTitle($lng['Input error']);
		$xgender->setError($lng['Value is not in list']);
		$xgender->setFormula1('"1,2"');	
		$sheet->setDataValidation($gender_col.'4:'.$gender_col.$rows, $xgender);	
	}
	if(isset($maritial_col)){
		$xmaritial = $sheet->getCell($maritial_col.'4')->getDataValidation();
		$xmaritial->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xmaritial->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xmaritial->setAllowBlank(false);
		$xmaritial->setShowInputMessage(true);
		$xmaritial->setShowErrorMessage(true);
		$xmaritial->setShowDropDown(true);
		$xmaritial->setErrorTitle($lng['Input error']);
		$xmaritial->setError($lng['Value is not in list']);
		$xmaritial->setFormula1('"1,2,3,4,5,6,7"');	
		$sheet->setDataValidation($maritial_col.'4:'.$maritial_col.$rows, $xmaritial);	
	}
	if(isset($religion_col)){
		$xreligion = $sheet->getCell($religion_col.'4')->getDataValidation();
		$xreligion->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xreligion->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xreligion->setAllowBlank(false);
		$xreligion->setShowInputMessage(true);
		$xreligion->setShowErrorMessage(true);
		$xreligion->setShowDropDown(true);
		$xreligion->setErrorTitle($lng['Input error']);
		$xreligion->setError($lng['Value is not in list']);
		$xreligion->setFormula1('"1,2,3,4,5"');	
		$sheet->setDataValidation($religion_col.'4:'.$religion_col.$rows, $xreligion);	
	}
	if(isset($military_col)){
		$xmilitary = $sheet->getCell($military_col.'4')->getDataValidation();
		$xmilitary->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xmilitary->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xmilitary->setAllowBlank(false);
		$xmilitary->setShowInputMessage(true);
		$xmilitary->setShowErrorMessage(true);
		$xmilitary->setShowDropDown(true);
		$xmilitary->setErrorTitle($lng['Input error']);
		$xmilitary->setError($lng['Value is not in list']);
		$xmilitary->setFormula1('"1,2,3,4"');	
		$sheet->setDataValidation($military_col.'4:'.$military_col.$rows, $xmilitary);	
	}
	if(isset($team_col)){
		$xteam = $sheet->getCell($team_col.'4')->getDataValidation();
		$xteam->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xteam->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xteam->setAllowBlank(false);
		$xteam->setShowInputMessage(true);
		$xteam->setShowErrorMessage(true);
		$xteam->setShowDropDown(true);
		$xteam->setErrorTitle($lng['Input error']);
		$xteam->setError($lng['Value is not in list']);
		$xteam->setFormula1('"'.$team.'"');	
		$sheet->setDataValidation($team_col.'4:'.$team_col.$rows, $xteam);	
	}
	if(isset($emp_group_col)){
		$emp_group = $sheet->getCell($emp_group_col.'4')->getDataValidation();
		$emp_group->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$emp_group->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$emp_group->setAllowBlank(false);
		$emp_group->setShowInputMessage(true);
		$emp_group->setShowErrorMessage(true);
		$emp_group->setShowDropDown(true);
		$emp_group->setErrorTitle($lng['Input error']);
		$emp_group->setError($lng['Value is not in list']);
		$emp_group->setFormula1('"s,m"');	
		$sheet->setDataValidation($emp_group_col.'4:'.$emp_group_col.$rows, $emp_group);	
	}
	if(isset($emp_type_col)){
		$xemp_type = $sheet->getCell($emp_type_col.'4')->getDataValidation();
		$xemp_type->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xemp_type->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xemp_type->setAllowBlank(true);
		$xemp_type->setShowInputMessage(true);
		$xemp_type->setShowErrorMessage(true);
		$xemp_type->setShowDropDown(true);
		//$emp_status->setInputTitle('Enter value 0-15');
		$xemp_type->setErrorTitle($lng[$lng['Input error']]);
		$xemp_type->setError($lng['Value is not in list']);
		$xemp_type->setFormula1('"1,2,3,4,5"');	
		$sheet->setDataValidation($emp_type_col.'4:'.$emp_type_col.$rows, $xemp_type);	
	}
	if(isset($emp_status_col)){
		$xemp_status = $sheet->getCell($emp_status_col.'4')->getDataValidation();
		$xemp_status->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xemp_status->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xemp_status->setAllowBlank(true);
		$xemp_status->setShowInputMessage(true);
		$xemp_status->setShowErrorMessage(true);
		$xemp_status->setShowDropDown(true);
		//$emp_status->setInputTitle('Enter value 0-15');
		$xemp_status->setErrorTitle($lng['Input error']);
		$xemp_status->setError($lng['Value is not in list']);
		$xemp_status->setFormula1('"1,2,3,4,5"');	
		$sheet->setDataValidation($emp_status_col.'4:'.$emp_status_col.$rows, $xemp_status);	
	}
	if(isset($acc_code_col)){
		$acc_code = $sheet->getCell($acc_code_col.'4')->getDataValidation();
		$acc_code->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$acc_code->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$acc_code->setAllowBlank(true);
		$acc_code->setShowInputMessage(true);
		$acc_code->setShowErrorMessage(true);
		$acc_code->setShowDropDown(true);
		$acc_code->setErrorTitle($lng['Input error']);
		$acc_code->setError($lng['Value is not in list']);
		$acc_code->setFormula1('"0,1"');	
		$sheet->setDataValidation($acc_code_col.'4:'.$acc_code_col.$rows, $acc_code);	
	}
	if(isset($position_col)){
		$xposition = $sheet->getCell($position_col.'4')->getDataValidation();
		$xposition->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xposition->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xposition->setAllowBlank(false);
		$xposition->setShowInputMessage(true);
		$xposition->setShowErrorMessage(true);
		$xposition->setShowDropDown(true);
		$xposition->setErrorTitle($lng['Input error']);
		$xposition->setError($lng['Value is not in list']);
		$xposition->setFormula1('"'.$pos.'"');	
		$sheet->setDataValidation($position_col.'4:'.$position_col.$rows, $xposition);	
	}
	if(isset($head_branch_col)){
		$xbranches = $sheet->getCell($head_branch_col.'4')->getDataValidation();
		$xbranches->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xbranches->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xbranches->setAllowBlank(false);
		$xbranches->setShowInputMessage(true);
		$xbranches->setShowErrorMessage(true);
		$xbranches->setShowDropDown(true);
		$xbranches->setErrorTitle($lng['Input error']);
		$xbranches->setError($lng['Value is not in list']);
		$xbranches->setFormula1('"'.$bra.'"');	
		$sheet->setDataValidation($head_branch_col.'4:'.$head_branch_col.$rows, $xbranches);	
	}
	if(isset($head_division_col)){
		$xdivisions = $sheet->getCell($head_division_col.'4')->getDataValidation();
		$xdivisions->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xdivisions->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xdivisions->setAllowBlank(false);
		$xdivisions->setShowInputMessage(true);
		$xdivisions->setShowErrorMessage(true);
		$xdivisions->setShowDropDown(true);
		$xdivisions->setErrorTitle($lng['Input error']);
		$xdivisions->setError($lng['Value is not in list']);
		$xdivisions->setFormula1('"'.$div.'"');	
		$sheet->setDataValidation($head_division_col.'4:'.$head_division_col.$rows, $xdivisions);	
	}
	if(isset($head_department_col)){
		$xdepartments = $sheet->getCell($head_department_col.'4')->getDataValidation();
		$xdepartments->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xdepartments->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xdepartments->setAllowBlank(false);
		$xdepartments->setShowInputMessage(true);
		$xdepartments->setShowErrorMessage(true);
		$xdepartments->setShowDropDown(true);
		$xdepartments->setErrorTitle($lng['Input error']);
		$xdepartments->setError($lng['Value is not in list']);
		$xdepartments->setFormula1('"'.$dep.'"');	
		$sheet->setDataValidation($head_department_col.'4:'.$head_department_col.$rows, $xdepartments);	
	}
	if(isset($team_supervisor_col)){
		$xteams = $sheet->getCell($team_supervisor_col.'4')->getDataValidation();
		$xteams->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xteams->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xteams->setAllowBlank(false);
		$xteams->setShowInputMessage(true);
		$xteams->setShowErrorMessage(true);
		$xteams->setShowDropDown(true);
		$xteams->setErrorTitle($lng['Input error']);
		$xteams->setError($lng['Value is not in list']);
		$xteams->setFormula1('"'.$tea.'"');	
		$sheet->setDataValidation($team_supervisor_col.'4:'.$team_supervisor_col.$rows, $xteams);	
	}
	/*if(isset($shiftteam_col)){
		$xshiftteam = $sheet->getCell($shiftteam_col.'4')->getDataValidation();
		$xshiftteam->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xshiftteam->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xshiftteam->setAllowBlank(false);
		$xshiftteam->setShowInputMessage(true);
		$xshiftteam->setShowErrorMessage(true);
		$xshiftteam->setShowDropDown(true);
		$xshiftteam->setErrorTitle($lng['Input error']);
		$xshiftteam->setError($lng['Value is not in list']);
		$xshiftteam->setFormula1('"'.$steam.'"');	
		$sheet->setDataValidation($shiftteam_col.'4:'.$shiftteam_col.$rows, $xshiftteam);	
	}*/
	if(isset($time_reg_col)){
		$yesno = $sheet->getCell($time_reg_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($time_reg_col.'4:'.$time_reg_col.$rows, $yesno);	
	}
	if(isset($selfie_col)){
		$xselfie_col = $sheet->getCell($selfie_col.'4')->getDataValidation();
		$xselfie_col->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xselfie_col->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xselfie_col->setAllowBlank(false);
		$xselfie_col->setShowInputMessage(true);
		$xselfie_col->setShowErrorMessage(true);
		$xselfie_col->setShowDropDown(true);
		$xselfie_col->setErrorTitle($lng['Input error']);
		$xselfie_col->setError($lng['Value is not in list']);
		$xselfie_col->setFormula1('"0,1"');	
		$sheet->setDataValidation($selfie_col.'4:'.$selfie_col.$rows, $xselfie_col);	
	}
	if(isset($bank_code_col)){
		$xbank = $sheet->getCell($bank_code_col.'4')->getDataValidation();
		$xbank->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xbank->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xbank->setAllowBlank(false);
		$xbank->setShowInputMessage(true);
		$xbank->setShowErrorMessage(true);
		$xbank->setShowDropDown(true);
		$xbank->setErrorTitle($lng['Input error']);
		$xbank->setError($lng['Value is not in list']);
		$xbank->setFormula1('"'.$bank.'"');	
		$sheet->setDataValidation($bank_code_col.'4:'.$bank_code_col.$rows, $xbank);	
	}
	if(isset($pay_type_col)){
		$xbank = $sheet->getCell($pay_type_col.'4')->getDataValidation();
		$xbank->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$xbank->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$xbank->setAllowBlank(false);
		$xbank->setShowInputMessage(true);
		$xbank->setShowErrorMessage(true);
		$xbank->setShowDropDown(true);
		$xbank->setErrorTitle($lng['Input error']);
		$xbank->setError($lng['Value is not in list']);
		$xbank->setFormula1('"'.$ebank.'"');	
		$sheet->setDataValidation($pay_type_col.'4:'.$pay_type_col.$rows, $xbank);	
	}
	if(isset($psf_col)){
		$psf = $sheet->getCell($psf_col.'4')->getDataValidation();
		$psf->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$psf->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$psf->setAllowBlank(false);
		$psf->setShowInputMessage(true);
		$psf->setShowErrorMessage(true);
		$psf->setShowDropDown(true);
		$psf->setErrorTitle($lng['Input error']);
		$psf->setError($lng['Value is not in list']);
		$psf->setFormula1('"0,1"');	
		$sheet->setDataValidation($psf_col.'4:'.$psf_col.$rows, $psf);	
	}
	if(isset($pvf_col)){
		$pvf = $sheet->getCell($pvf_col.'4')->getDataValidation();
		$pvf->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$pvf->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$pvf->setAllowBlank(false);
		$pvf->setShowInputMessage(true);
		$pvf->setShowErrorMessage(true);
		$pvf->setShowDropDown(true);
		$pvf->setErrorTitle($lng['Input error']);
		$pvf->setError($lng['Value is not in list']);
		$pvf->setFormula1('"0,1"');	
		$sheet->setDataValidation($pvf_col.'4:'.$pvf_col.$rows, $pvf);	
	}
	if(isset($calc_method_col)){
		$calc_method = $sheet->getCell($calc_method_col.'4')->getDataValidation();
		$calc_method->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$calc_method->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$calc_method->setAllowBlank(true);
		$calc_method->setShowInputMessage(true);
		$calc_method->setShowErrorMessage(true);
		$calc_method->setShowDropDown(true);
		//$pvf_employee->setInputTitle('Enter value 0-15');
		$calc_method->setErrorTitle($lng['Input error']);
		$calc_method->setError($lng['Value is not in list']);
		$calc_method->setFormula1('"cam,acm,ytd"');	
		$sheet->setDataValidation($calc_method_col.'4:'.$calc_method_col.$rows, $calc_method);	
	}
	if(isset($tax_col)){
		$yesno = $sheet->getCell($tax_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1,3"');	
		$sheet->setDataValidation($tax_col.'4:'.$tax_col.$rows, $yesno);	
	}
	if(isset($pnd_col)){
		$pnd = $sheet->getCell($pnd_col.'4')->getDataValidation();
		$pnd->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$pnd->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$pnd->setAllowBlank(false);
		$pnd->setShowInputMessage(true);
		$pnd->setShowErrorMessage(true);
		$pnd->setShowDropDown(true);
		$pnd->setErrorTitle($lng['Input error']);
		$pnd->setError($lng['Value is not in list']);
		$pnd->setFormula1('"1,3"');	
		$sheet->setDataValidation($pnd_col.'4:'.$pnd_col.$rows, $pnd);	
	}
	if(isset($sso_col)){
		$yesno = $sheet->getCell($sso_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($sso_col.'4:'.$sso_col.$rows, $yesno);	
	}
	if(isset($sso_by_col)){
		$yesno = $sheet->getCell($sso_by_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($sso_by_col.'4:'.$sso_by_col.$rows, $yesno);	
	}
	if(isset($contract_type_col)){
		$contract = $sheet->getCell($contract_type_col.'4')->getDataValidation();
		$contract->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$contract->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$contract->setAllowBlank(false);
		$contract->setShowInputMessage(true);
		$contract->setShowErrorMessage(true);
		$contract->setShowDropDown(true);
		$contract->setErrorTitle($lng['Input error']);
		$contract->setError($lng['Value is not in list']);
		$contract->setFormula1('"month,day"');	
		$sheet->setDataValidation($contract_type_col.'4:'.$contract_type_col.$rows, $contract);	
	}
	if(isset($calc_base_col)){
		$calc_base = $sheet->getCell($calc_base_col.'4')->getDataValidation();
		$calc_base->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$calc_base->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$calc_base->setAllowBlank(false);
		$calc_base->setShowInputMessage(true);
		$calc_base->setShowErrorMessage(true);
		$calc_base->setShowDropDown(true);
		$calc_base->setErrorTitle($lng['Input error']);
		$calc_base->setError($lng['Value is not in list']);
		$calc_base->setFormula1('"gross,net"');	
		$sheet->setDataValidation($calc_base_col.'4:'.$calc_base_col.$rows, $calc_base);	
	}
	if(isset($base_ot_rate_col)){
		$base_ot_rate = $sheet->getCell($base_ot_rate_col.'4')->getDataValidation();
		$base_ot_rate->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$base_ot_rate->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$base_ot_rate->setAllowBlank(false);
		$base_ot_rate->setShowInputMessage(true);
		$base_ot_rate->setShowErrorMessage(true);
		$base_ot_rate->setShowDropDown(true);
		$base_ot_rate->setErrorTitle($lng['Input error']);
		$base_ot_rate->setError($lng['Value is not in list']);
		$base_ot_rate->setFormula1('"calc,fix"');	
		$sheet->setDataValidation($base_ot_rate_col.'4:'.$base_ot_rate_col.$rows, $base_ot_rate);	
	}
	if(isset($tax_spouse_col)){
		$yesno = $sheet->getCell($tax_spouse_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($tax_spouse_col.'4:'.$tax_spouse_col.$rows, $yesno);	
	}
	if(isset($tax_exemp_disabled_under_col)){
		$yesno = $sheet->getCell($tax_exemp_disabled_under_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($tax_exemp_disabled_under_col.'4:'.$tax_exemp_disabled_under_col.$rows, $yesno);	
	}
	if(isset($tax_exemp_payer_older_col)){
		$yesno = $sheet->getCell($tax_exemp_payer_older_col.'4')->getDataValidation();
		$yesno->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
		$yesno->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP );
		$yesno->setAllowBlank(false);
		$yesno->setShowInputMessage(true);
		$yesno->setShowErrorMessage(true);
		$yesno->setShowDropDown(true);
		$yesno->setErrorTitle($lng['Input error']);
		$yesno->setError($lng['Value is not in list']);
		$yesno->setFormula1('"0,1"');	
		$sheet->setDataValidation($tax_exemp_payer_older_col.'4:'.$tax_exemp_payer_older_col.$rows, $yesno);	
	}
	
	$nr=0;
	$rw = 3;
	foreach($export_fields as $k=>$v){
		$abc = getNameFromNumber($nr);
		$sheet->setCellValue($abc.'2', $k);
		$sheet->setCellValue($abc.'3', $v);
		$nr++;
	}
	
	// MESSAGE BOX ///////////////////////////////////////////////////////////////
	if(isset($prefix_col)){
		$commentRichText = $sheet->getComment($prefix_col.$rw)->getText()->createTextRun('ID Prefix'.':')->getFont()->setBold(true);
		$sheet->getComment($prefix_col.$rw)->getText()->createTextRun("\r\nPlease select only Prefix\nAuto numbering on Import");
		$sheet->getComment($prefix_col.$rw)->setWidth("300px")->setHeight("60px");		
	}
	if(isset($title_col)){
		$commentRichText = $sheet->getComment($title_col.$rw)->getText()->createTextRun($lng['Title'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($title as $k=>$v){
			$sheet->getComment($title_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($title_col.$rw)->setWidth("100px")->setHeight($h."px");		
	}
	if(isset($birth_col)){
		$commentRichText = $sheet->getComment($birth_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($birth_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($birth_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($birth_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($gender_col)){
		$commentRichText = $sheet->getComment($gender_col.$rw)->getText()->createTextRun($lng['Gender'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($gender as $k=>$v){
			$sheet->getComment($gender_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($gender_col.$rw)->setWidth("150px")->setHeight($h."px");		
	}
	if(isset($maritial_col)){
		$commentRichText = $sheet->getComment($maritial_col.$rw)->getText()->createTextRun($lng['Maritial'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($maritial as $k=>$v){
			$sheet->getComment($maritial_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($maritial_col.$rw)->setWidth("200px")->setHeight($h."px");		
	}
	if(isset($religion_col)){
		$commentRichText = $sheet->getComment($religion_col.$rw)->getText()->createTextRun($lng['Religion'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($religion as $k=>$v){
			$sheet->getComment($religion_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($religion_col.$rw)->setWidth("150px")->setHeight($h."px");		
	}
	if(isset($military_col)){
		$commentRichText = $sheet->getComment($military_col.$rw)->getText()->createTextRun($lng['Military status'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($military_status as $k=>$v){
			$sheet->getComment($military_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($military_col.$rw)->setWidth("200px")->setHeight($h."px");		
	}
	if(isset($drvlicense_exp_col)){
		$commentRichText = $sheet->getComment($drvlicense_exp_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($drvlicense_exp_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($drvlicense_exp_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($drvlicense_exp_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($idcard_col)){
		$commentRichText = $sheet->getComment($idcard_col.$rw)->getText()->createTextRun($lng['ID card'].':')->getFont()->setBold(true);	
		$sheet->getComment($idcard_col.$rw)->getText()->createTextRun("\r\n".$lng['Fill numbers only']);	
		$sheet->getComment($idcard_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": 0123456789");	
		$sheet->getComment($idcard_col.$rw)->setWidth("180px")->setHeight("65px");
	}
	if(isset($idcard_exp_col)){
		$commentRichText = $sheet->getComment($idcard_exp_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($idcard_exp_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($idcard_exp_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($idcard_exp_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($tax_id_col)){
		$commentRichText = $sheet->getComment($tax_id_col.$rw)->getText()->createTextRun($lng['Tax ID no.'].':')->getFont()->setBold(true);	
		$sheet->getComment($tax_id_col.$rw)->getText()->createTextRun("\r\n".$lng['Fill numbers only']);	
		$sheet->getComment($tax_id_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": 0123456789");	
		$sheet->getComment($tax_id_col.$rw)->setWidth("180px")->setHeight("65px");
	}
	if(isset($joining_col)){
		$commentRichText = $sheet->getComment($joining_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($joining_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($joining_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($joining_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($probation_col)){
		$commentRichText = $sheet->getComment($probation_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($probation_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($probation_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($probation_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($team_col)){
		$commentRichText = $sheet->getComment($team_col.$rw)->getText()->createTextRun('Team :')->getFont()->setBold(true);		
		$h = 25;
		foreach($teams as $k=>$v){
			$sheet->getComment($team_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($team_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($emp_group_col)){
		$commentRichText = $sheet->getComment($emp_group_col.$rw)->getText()->createTextRun($lng['Employee group'].':')->getFont()->setBold(true);
		$sheet->getComment($emp_group_col.$rw)->getText()->createTextRun("\r\ns = ".$lng['Staff']);
		$sheet->getComment($emp_group_col.$rw)->getText()->createTextRun("\r\nm = ".$lng['Management']);
		$sheet->getComment($emp_group_col.$rw)->setWidth("200px")->setHeight("65px");		
	}
	if(isset($emp_type_col)){
		$commentRichText = $sheet->getComment($emp_type_col.$rw)->getText()->createTextRun($lng['Employee type'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($emp_type as $k=>$v){
			$sheet->getComment($emp_type_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($emp_type_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($resign_col)){
		$commentRichText = $sheet->getComment($resign_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($resign_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($resign_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($resign_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($emp_status_col)){
		$commentRichText = $sheet->getComment($emp_status_col.$rw)->getText()->createTextRun($lng['Employee status'].':')->getFont()->setBold(true);
		$h = 25;
		foreach($emp_status as $k=>$v){
			$sheet->getComment($emp_status_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($emp_status_col.$rw)->setWidth("200px")->setHeight($h."px");		
	}
	if(isset($acc_code_col)){
		$commentRichText = $sheet->getComment($acc_code_col.$rw)->getText()->createTextRun($lng['Accounting code'].':')->getFont()->setBold(true);
		$sheet->getComment($acc_code_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['Direct']);
		$sheet->getComment($acc_code_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Indirect']);
		$sheet->getComment($acc_code_col.$rw)->setWidth("200px")->setHeight("65px");		
	}
	if(isset($position_col)){
		$commentRichText = $sheet->getComment($position_col.$rw)->getText()->createTextRun($lng['Positions'].':')->getFont()->setBold(true);		
		$h = 25;
		foreach($positions as $k=>$v){
			$sheet->getComment($position_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($position_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($head_branch_col)){
		$commentRichText = $sheet->getComment($head_branch_col.$rw)->getText()->createTextRun('Head of branch'.':')->getFont()->setBold(true);		
		$h = 25;
		foreach($branches as $k=>$v){
			$sheet->getComment($head_branch_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($head_branch_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($head_division_col)){
		$commentRichText = $sheet->getComment($head_division_col.$rw)->getText()->createTextRun('Head of division'.':')->getFont()->setBold(true);		
		$h = 25;
		foreach($divisions as $k=>$v){
			$sheet->getComment($head_division_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($head_division_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($head_department_col)){
		$commentRichText = $sheet->getComment($head_department_col.$rw)->getText()->createTextRun('Head of division'.':')->getFont()->setBold(true);		
		$h = 25;
		foreach($departments as $k=>$v){
			$sheet->getComment($head_department_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($head_department_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($team_supervisor_col)){
		$commentRichText = $sheet->getComment($team_supervisor_col.$rw)->getText()->createTextRun('Team supervisor'.':')->getFont()->setBold(true);		
		$h = 25;
		foreach($teams as $k=>$v){
			$sheet->getComment($team_supervisor_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v[$lang]); 
			$h += 20;
		}
		$sheet->getComment($team_supervisor_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($date_position_col)){
		$commentRichText = $sheet->getComment($date_position_col.$rw)->getText()->createTextRun($lng['Date format'].':')->getFont()->setBold(true);	
		$sheet->getComment($date_position_col.$rw)->getText()->createTextRun("\r\n".$lng['dd-mm-yyyy']."");	
		$sheet->getComment($date_position_col.$rw)->getText()->createTextRun("\r\n".$lng['Example'].": ".date('d-m-Y'));	
		$sheet->getComment($date_position_col.$rw)->setWidth("160px")->setHeight("65px");
	}
	if(isset($shiftteam_col)){
		$commentRichText = $sheet->getComment($shiftteam_col.$rw)->getText()->createTextRun('Shiftteams'.':')->getFont()->setBold(true);
		$h = 25;
		foreach($shiftteams as $k=>$v){
			$sheet->getComment($shiftteam_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v);
			$h += 20;
		}
		$sheet->getComment($shiftteam_col.$rw)->setWidth("350px")->setHeight($h."px");		
	}
	if(isset($time_reg_col)){
		$commentRichText = $sheet->getComment($time_reg_col.$rw)->getText()->createTextRun('Time registration :')->getFont()->setBold(true);
			$sheet->getComment($time_reg_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['No']);
			$sheet->getComment($time_reg_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Yes']);
	}
	if(isset($selfie_col)){
		$commentRichText = $sheet->getComment($selfie_col.$rw)->getText()->createTextRun('Take selfie'.':')->getFont()->setBold(true);
			$sheet->getComment($selfie_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['No']);
			$sheet->getComment($selfie_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Yes']);
	}
	if(isset($bank_code_col)){
		$commentRichText = $sheet->getComment($bank_code_col.$rw)->getText()->createTextRun('Bank codes'.':')->getFont()->setBold(true);		
		$h = 25;
		foreach($bankCode as $k=>$v){
			$sheet->getComment($bank_code_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v); 
			$h += 20;
		}
		$sheet->getComment($bank_code_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($pay_type_col)){
		$commentRichText = $sheet->getComment($pay_type_col.$rw)->getText()->createTextRun('Bank codes'.':')->getFont()->setBold(true);		
		$h = 50;
		$sheet->getComment($pay_type_col.$rw)->getText()->createTextRun("\r\ncash = ".$lng['Cash']);
		$sheet->getComment($pay_type_col.$rw)->getText()->createTextRun("\r\ncheque = ".$lng['Cheque']);
		foreach($bankCode as $k=>$v){
			$sheet->getComment($pay_type_col.$rw)->getText()->createTextRun("\r\n".$k.' = '.$v); 
			$h += 20;
		}
		$sheet->getComment($pay_type_col.$rw)->setWidth("300px")->setHeight($h."px");		
	}
	if(isset($psf_col)){
		$commentRichText = $sheet->getComment($psf_col.$rw)->getText()->createTextRun('Pension fund'.':')->getFont()->setBold(true);
			$sheet->getComment($psf_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['No']);
			$sheet->getComment($psf_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Yes']);
	}
	if(isset($pvf_col)){
		$commentRichText = $sheet->getComment($pvf_col.$rw)->getText()->createTextRun('Provident fund'.':')->getFont()->setBold(true);
			$sheet->getComment($pvf_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['No']);
			$sheet->getComment($pvf_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Yes']);
	}
	if(isset($calc_method_col)){
		$commentRichText = $sheet->getComment($calc_method_col.$rw)->getText()->createTextRun($lng['Tax calculation method'].':')->getFont()->setBold(true);	
		$sheet->getComment($calc_method_col.$rw)->getText()->createTextRun("\r\ncam = ".$lng['Calculate in Advance Method']);	
		$sheet->getComment($calc_method_col.$rw)->getText()->createTextRun("\r\nacm = ".$lng['Accumulative Calculation Method']);	
		$sheet->getComment($calc_method_col.$rw)->getText()->createTextRun("\r\nytd = ".$lng['Year To Date Method']);	
		$sheet->getComment($calc_method_col.$rw)->setWidth("250px")->setHeight("80px");
	}
	if(isset($tax_col)){
		$commentRichText = $sheet->getComment($tax_col.$rw)->getText()->createTextRun('Calculate Tax'.':')->getFont()->setBold(true);
			$sheet->getComment($tax_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['no Tax']);
			$sheet->getComment($tax_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['PND'].' 1');
			$sheet->getComment($tax_col.$rw)->getText()->createTextRun("\r\n3 = ".$lng['PND'].' 3');
	}
	if(isset($pnd_col)){
		$commentRichText = $sheet->getComment($pnd_col.$rw)->getText()->createTextRun('PND'.':')->getFont()->setBold(true);
			$sheet->getComment($pnd_col.$rw)->getText()->createTextRun("\r\n1 = PND1");
			$sheet->getComment($pnd_col.$rw)->getText()->createTextRun("\r\n3 = PND3");
	}
	if(isset($sso_col)){
		$commentRichText = $sheet->getComment($sso_col.$rw)->getText()->createTextRun('Calculate SSO'.':')->getFont()->setBold(true);
			$sheet->getComment($sso_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['No']);
			$sheet->getComment($sso_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Yes']);
	}
	if(isset($sso_by_col)){
		$commentRichText = $sheet->getComment($sso_by_col.$rw)->getText()->createTextRun('Calculate SSO'.':')->getFont()->setBold(true);
			$sheet->getComment($sso_by_col.$rw)->getText()->createTextRun("\r\n0 = ".$lng['Employee']);
			$sheet->getComment($sso_by_col.$rw)->getText()->createTextRun("\r\n1 = ".$lng['Company']);
	}
	if(isset($contract_type_col)){
		$commentRichText = $sheet->getComment($contract_type_col.$rw)->getText()->createTextRun('Contract type :')->getFont()->setBold(true);
			$sheet->getComment($contract_type_col.$rw)->getText()->createTextRun("\r\nmonth = ".$lng['Monthly wage']);
			$sheet->getComment($contract_type_col.$rw)->getText()->createTextRun("\r\nday = ".$lng['Daily wage']);
			$sheet->getComment($contract_type_col.$rw)->setWidth("200px");		
	}
	if(isset($calc_base_col)){
		$commentRichText = $sheet->getComment($calc_base_col.$rw)->getText()->createTextRun('Contract type :')->getFont()->setBold(true);
			$sheet->getComment($calc_base_col.$rw)->getText()->createTextRun("\r\ngross = ".$lng['Gross amount']);
			$sheet->getComment($calc_base_col.$rw)->getText()->createTextRun("\r\nnet = ".$lng['Net amount']);
			$sheet->getComment($calc_base_col.$rw)->setWidth("200px");		
	}
	if(isset($base_ot_rate_col)){
		$commentRichText = $sheet->getComment($base_ot_rate_col.$rw)->getText()->createTextRun('Contract type :')->getFont()->setBold(true);
			$sheet->getComment($base_ot_rate_col.$rw)->getText()->createTextRun("\r\ncalc = Calculated");
			$sheet->getComment($base_ot_rate_col.$rw)->getText()->createTextRun("\r\nfix = Fixed");
			//$sheet->getComment($calc_base_col.$rw)->setWidth("200px");		
	}
	
	
	if(isset($_REQUEST['register'])){
		$sheet->setCellValue('A1', 'UPDATE');
		$sql = "SELECT * FROM ".$cid."_employees ".$where." ORDER BY emp_id ASC";
		//var_dump($sql);
		if($res = $dbc->query($sql)){
			$r=4; $c=0;
			while($row = $res->fetch_assoc()){
				foreach($export_fields as $k => $v){
					$abc = getNameFromNumber($c);
					if($k == 'idcard_nr' || $k == 'tax_id'){
						$sheet->setCellValueExplicit($abc.$r, $row[$k], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); $c++;
					}elseif($k == 'joining_date'){
						$sheet->setCellValue($abc.$r, date('d-m-Y', strtotime($row[$k]))); $c++;
					}else{
						$sheet->setCellValue($abc.$r, $row[$k]); $c++;
					}
					$sheet->getColumnDimension($abc)->setAutoSize(true);
					$data[$r][$k] = $row[$k];
				}
				$r++; $c=0;
			}
		}
	}else{
		$sheet->setCellValue('A1', 'NEW');
	}
	//var_dump($data); exit;
	
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

	$hCol = $sheet->getHighestColumn();
	$hRow = $r;
	$sheet->getStyle('A3:'.$hCol.$rw)->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('FFccffcc');
	$sheet->getStyle('A3:'.$hCol.$rw)->applyFromArray($allBorders);
	
	if(isset($_REQUEST['register'])){
		$sheet->getProtection()->setSheet(true);
		$sheet->getStyle('B4:'.$hCol.$hRow)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
		//$sheet->getStyle('A'.$hRow.':'.$hCol.($hRow+100))->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED); 
	}  	
	
	/*foreach($hor_center as $v){
		$sheet->getStyle($v.'3:'.$v.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	}*/
	
	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	$sheet->getColumnDimension('C')->setAutoSize(true);
	$sheet->getColumnDimension('D')->setAutoSize(true);
	$sheet->setTitle($lng['Employees']);
	$sheet->getRowDimension(2)->setVisible(false);
	$sheet->freezePane('B4','B4');
	
//------------------------------------------------------------------------------------------------- NEW SHEET
	
/*	$spreadsheet->createSheet(1);
	$sheet2 = $spreadsheet->setActiveSheetIndex(1);
	$sheet2->setTitle($lng['Code information']);

	$sheet2->mergeCells('B2:C2');
	$sheet2->mergeCells('D2:E2');
	$sheet2->mergeCells('F2:G2');
	$sheet2->mergeCells('H2:I2');
	$sheet2->mergeCells('J2:K2');
	$sheet2->mergeCells('L2:M2');
	$sheet2->mergeCells('N2:O2');
	$sheet2->mergeCells('P2:Q2');
	$sheet2->mergeCells('R2:S2');
	$sheet2->mergeCells('T2:U2');
	$sheet2->mergeCells('V2:W2');
	
	$sheet2->mergeCells('C1:'.$last_col.'1');
	$sheet2->setCellValue('B2', $lng['Positions']);
	$sheet2->setCellValue('D2', $lng['Gender']);
	$sheet2->setCellValue('F2', $lng['Maritial status']);
	$sheet2->setCellValue('H2', $lng['Religion']);
	$sheet2->setCellValue('J2', $lng['Military status']);
	$sheet2->setCellValue('L2', $lng['Employee status']);
	$sheet2->setCellValue('N2', $lng['Employee type']);
	$sheet2->setCellValue('P2', $lng['Bank codes']);
	$sheet2->setCellValue('R2', $lng['Pay frequency']);
	$sheet2->setCellValue('T2', $lng['Pay type']);
	$sheet2->setCellValue('V2', $lng['Accounting code']);
	
	$sheet2->setCellValue('B3', $lng['Code']);
	$sheet2->setCellValue('D3', $lng['Code']);
	$sheet2->setCellValue('F3', $lng['Code']);
	$sheet2->setCellValue('H3', $lng['Code']);
	$sheet2->setCellValue('J3', $lng['Code']);
	$sheet2->setCellValue('L3', $lng['Code']);
	$sheet2->setCellValue('N3', $lng['Code']);
	$sheet2->setCellValue('P3', $lng['Code']);
	$sheet2->setCellValue('R3', $lng['Code']);
	$sheet2->setCellValue('T3', $lng['Code']);
	$sheet2->setCellValue('V3', $lng['Code']);
	
	$sheet2->setCellValue('C3', $lng['Name']);
	$sheet2->setCellValue('E3', $lng['Name']);
	$sheet2->setCellValue('G3', $lng['Name']);
	$sheet2->setCellValue('I3', $lng['Name']);
	$sheet2->setCellValue('K3', $lng['Name']);
	$sheet2->setCellValue('M3', $lng['Name']);
	$sheet2->setCellValue('O3', $lng['Name']);
	$sheet2->setCellValue('Q3', $lng['Name']);
	$sheet2->setCellValue('S3', $lng['Name']);
	$sheet2->setCellValue('U3', $lng['Name']);
	$sheet2->setCellValue('W3', $lng['Name']);
	
	$hCol = $sheet2->getHighestColumn();

	$sheet2->getStyle('B2:'.$hCol.'2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF494429');
	$sheet2->getStyle('B2:'.$hCol.'2')->getFont()->setBold(true)->getColor()->setRGB('ffffff');
	
	$sheet2->getStyle('B3:'.$hCol.'3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFc4bd97');
	$sheet2->getStyle('B2:'.$hCol.'3')->getFont()->setBold(true);
	
	$sheet2->getStyle('B2:'.$hCol.'3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	$r=4;
	foreach($positions as $k=>$v){
		$sheet2->setCellValueExplicit('B'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('C'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($gender as $k=>$v){
		$sheet2->setCellValueExplicit('D'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('E'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($maritial as $k=>$v){
		$sheet2->setCellValueExplicit('F'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('G'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($religion as $k=>$v){
		$sheet2->setCellValueExplicit('H'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('I'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($military_status as $k=>$v){
		$sheet2->setCellValueExplicit('J'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('K'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($emp_status as $k=>$v){
		$sheet2->setCellValueExplicit('L'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('M'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($emp_type as $k=>$v){
		$sheet2->setCellValueExplicit('N'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('O'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($bankCode as $k=>$v){
		$sheet2->setCellValueExplicit('P'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('Q'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($pay_frequency as $k=>$v){
		$sheet2->setCellValueExplicit('R'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('S'.$r, $v);
		$r++;
	}
	$r=4;
	foreach($pay_type as $k=>$v){
		$sheet2->setCellValueExplicit('T'.$r, $k, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('U'.$r, $v);
		$r++;
	}
	$r=4;
		$sheet2->setCellValueExplicit('V'.$r, '0', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('W'.$r, $lng['Direct']); $r++;
		$sheet2->setCellValueExplicit('V'.$r, '1', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet2->setCellValue('W'.$r, $lng['Indirect']);
		

	$sheet2->getColumnDimension('B')->setAutoSize(true);
	$sheet2->getColumnDimension('C')->setAutoSize(true);
	$sheet2->getColumnDimension('D')->setAutoSize(true);
	$sheet2->getColumnDimension('E')->setAutoSize(true);
	$sheet2->getColumnDimension('F')->setAutoSize(true);
	$sheet2->getColumnDimension('G')->setAutoSize(true);
	$sheet2->getColumnDimension('H')->setAutoSize(true);
	$sheet2->getColumnDimension('I')->setAutoSize(true);
	$sheet2->getColumnDimension('J')->setAutoSize(true);
	$sheet2->getColumnDimension('K')->setAutoSize(true);
	$sheet2->getColumnDimension('L')->setAutoSize(true);
	$sheet2->getColumnDimension('M')->setAutoSize(true);
	$sheet2->getColumnDimension('N')->setAutoSize(true);
	$sheet2->getColumnDimension('O')->setAutoSize(true);
	$sheet2->getColumnDimension('P')->setAutoSize(true);
	$sheet2->getColumnDimension('Q')->setAutoSize(true);
	$sheet2->getColumnDimension('R')->setAutoSize(true);
	$sheet2->getColumnDimension('S')->setAutoSize(true);
	$sheet2->getColumnDimension('T')->setAutoSize(true);
	$sheet2->getColumnDimension('U')->setAutoSize(true);
	$sheet2->getColumnDimension('V')->setAutoSize(true);
	$sheet2->getColumnDimension('W')->setAutoSize(true);

	$hRow = $sheet2->getHighestRow();
	$sheet2->getStyle('B2:'.$hCol.'3')->applyFromArray($allBorders);
	$sheet2->getStyle('B3:C'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('D3:E'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('F3:G'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('H3:I'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('J3:K'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('L3:M'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('N3:O'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('P3:Q'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('R3:S'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('T3:U'.$hRow)->applyFromArray($outBorders);
	$sheet2->getStyle('V3:W'.$hRow)->applyFromArray($outBorders);

	
	$sheet2->getStyle('B2:B'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('D2:D'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('F2:F'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('H2:H'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('J2:J'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('L2:L'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('N2:N'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('P2:P'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('R2:R'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('T2:T'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet2->getStyle('V2:V'.$hRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$sheet2->getProtection()->setSheet(true);
*/	
	$spreadsheet->setActiveSheetIndex(0);
	$filename = $_SESSION['rego']['cid'].'_employees.xlsx';
	
	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>