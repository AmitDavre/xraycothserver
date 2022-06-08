<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$employee = getEmployeeInfo($cid, $_SESSION['rego']['report_id']);
	$service_years = getAge($employee['startdate']);
	//var_dump($employee);
	
	$fix = getFixAllowNames();
	$var = getVarAllowNames();
	$dfix = getFixDeductNames();
	$dvar = getVarDeductNames();
	
	$pay = array();
	$array = array();
	$taxes = array();
	$fix_allow = array();
	$var_allow = array();
	$fix_deduct = array();
	$var_deduct = array();
	$other_income = 0;
	$end_contract = 0;

	if(isset($_SESSION['rego']['report_id'])){
		$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$_SESSION['rego']['report_id']."' AND month = '".$_GET['m']."'";
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$pay = $row;
				for($i=1;$i<=10;$i++){
					if($row['fix_allow_'.$i] > 0){$fix_allow[$fix[$i]] = $row['fix_allow_'.$i];}
				}
				for($i=1;$i<=10;$i++){
					if($row['var_allow_'.$i] > 0){$var_allow[$var[$i]] = $row['var_allow_'.$i];}
				}
				
				for($i=1;$i<=5;$i++){
					if($row['fix_deduct_'.$i] > 0){$fix_deduct[$fix[$i]] = $row['fix_deduct_'.$i];}
				}
				for($i=1;$i<=5;$i++){
					if($row['var_deduct_'.$i] > 0){$var_deduct[$var[$i]] = $row['var_deduct_'.$i];}
				}
				
				$other_income = $row['paid_leave'] + $row['other_income'] + $row['remaining_salary'];
				$end_contract = $row['notice_payment'] + $row['severance'];
				
				$array = unserialize($row['tax_calculation']);
				if($array['standard_deduction'] > 0){$taxes[$lng['Standard deduction']] = $array['standard_deduction'];}
				if($array['personal_allowance'] > 0){$taxes[$lng['Personal care']] = $array['personal_allowance'];}
				if($array['spouse_allowance'] > 0){$taxes[$lng['Spouse care']] = $array['spouse_allowance'];}
				if($array['parents_allowance'] > 0){$taxes[$lng['Parents care']] = $array['parents_allowance'];}
				if($array['parents_inlaw_allowance'] > 0){$taxes[$lng['Parents in law care']] = $array['parents_inlaw_allowance'];}
				if($array['disabled_allowance'] > 0){$taxes[$lng['Care disabled person']] = $array['disabled_allowance'];}
				if($array['child_allowance'] > 0){$taxes[$lng['Child care - biological']] = $array['child_allowance'];}
				if($array['child_birth_bonus'] > 0){$taxes[$lng['Child birth (Baby bonus)']] = $array['child_birth_bonus'];}
				if($array['own_health_insurance'] > 0){$taxes[$lng['Own health insurance']] = $array['own_health_insurance'];}
				if($array['own_life_insurance'] > 0){$taxes[$lng['Own life insurance']] = $array['own_life_insurance'];}
				if($array['health_insurance_parent'] > 0){$taxes[$lng['Health insurance parents']] = $array['health_insurance_parent'];}
				if($array['life_insurance_spouse'] > 0){$taxes[$lng['Life insurance spouse']] = $array['life_insurance_spouse'];}
				if($array['pension_fund_allowance'] > 0){$taxes[$lng['Pension fund']] = $array['pension_fund_allowance'];}
				if($array['tot_pvf'] > 0){$taxes[$lng['Provident fund']] = $array['tot_pvf'];}
				if($array['nsf_allowance'] > 0){$taxes[$lng['NSF']] = $array['nsf_allowance'];}
				if($array['rmf_allowance'] > 0){$taxes[$lng['RMF']] = $array['rmf_allowance'];}
				if($array['tot_ssf'] > 0){$taxes[$lng['Social Security Fund']] = $array['tot_ssf'];}
				if($array['ltf_deduction'] > 0){$taxes[$lng['LTF']] = $array['ltf_deduction'];}
				if($array['home_loan_interest'] > 0){$taxes[$lng['Home loan interest']] = $array['home_loan_interest'];}
				if($array['donation_charity'] > 0){$taxes[$lng['Donation charity']] = $array['donation_charity'];}
				if($array['donation_flood'] > 0){$taxes[$lng['Donation flooding']] = $array['donation_flood'];}
				if($array['donation_education'] > 0){$taxes[$lng['Donation education']] = $array['donation_education'];}
				if($array['exemp_disabled_under'] > 0){$taxes[$lng['Exemption disabled person <65 yrs']] = $array['exemp_disabled_under'];}
				if($array['exemp_payer_older'] > 0){$taxes[$lng['Exemption tax payer => 65yrs']] = $array['exemp_payer_older'];}
				if($array['first_home_allowance'] > 0){$taxes[$lng['First home buyer']] = $array['first_home_allowance'];}
				if($array['year_end_shop_allowance'] > 0){$taxes[$lng['Year-end shopping']] = $array['year_end_shop_allowance'];}
				if($array['domestic_tour_allowance'] > 0){$taxes[$lng['Domestic tour']] = $array['domestic_tour_allowance'];}
				if($array['other_allowance'] > 0){$taxes[$lng['Other allowance']] = $array['other_allowance'];}
			}
		}
	}
	//var_dump($taxes);
	//var_dump($array);
	//var_dump($fix_allow);
	//var_dump($var_allow);
	//exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$allBorders = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
			  ),
		 ),
	);
	$fontarray = array(
		'font'  => array(
			'bold'  => true,
			'color' => array('argb' => 'ffffffff'),
			'size'  => 11,
			'name'  => 'Calibri'
		)
	);
	 
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->setCellValue('A1', $lng['Salary overview'].' '.$pay['emp_name_'.$lang].' for '.$months[$_GET['m']].' '.$_SESSION['rego']['year_'.$lang]);
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	$sheet->mergeCells('A1:L1');
	$sheet->getStyle('A1')->getFont()->setSize(14);
	$sheet->getStyle('A1')->getFont()->setBold(true);
	$sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->setCellValue('A2', $lng['Employee'])->mergeCells('A2:F2');
	$sheet->getStyle('A2:F2')->applyFromArray($allBorders);
	$sheet->getStyle('A2:B2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->setCellValue('A3', $lng['Employee ID'])->getColumnDimension('A')->setAutoSize(true);
	$sheet->setCellValue('B3', $employee['emp_id']);
	$sheet->mergeCells('B3:F3');
	
	$sheet->setCellValue('A4', $lng['Name']);
	$sheet->setCellValue('B4', $employee[$lang.'_name']);
	$sheet->mergeCells('B4:F4');
	
	$sheet->setCellValue('A5', $lng['Position']);
	$sheet->setCellValue('B5', $positions[$employee['position']]);
	$sheet->mergeCells('B5:F5');
	
	$sheet->setCellValue('A6', $lng['Joining date']);
	$sheet->setCellValue('B6', $employee['startdate']);
	$sheet->mergeCells('B6:F6');
	
	$sheet->setCellValue('A7', $lng['Service years']);
	$sheet->setCellValue('B7', $service_years);
	$sheet->mergeCells('B7:F7');
	
	$sheet->setCellValue('A8', $lng['Overtime']);
	$sheet->setCellValue('B8', $lng['OT 1']);
	$sheet->setCellValue('C8', $lng['OT 1.5']);
	$sheet->setCellValue('D8', $lng['OT 2']);
	$sheet->setCellValue('E8', $lng['OT 3']);
	$sheet->setCellValue('F8', $lng['Other OT']);
	
	$sheet->getStyle('A8:F8')->applyFromArray($allBorders);
	$sheet->getStyle('A8:F8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('B8:F8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->setCellValue('A9', $lng['Hours']);
	$sheet->setCellValue('B9', $pay['ot1h'])->getStyle('B9')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('C9', $pay['ot15h'])->getStyle('C9')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('D9', $pay['ot2h'])->getStyle('D9')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('E9', $pay['ot3h'])->getStyle('E9')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('F9','');

	$sheet->setCellValue('A10', $lng['Baht'])->getStyle('D4');
	$sheet->setCellValue('B10', $pay['ot1b'])->getStyle('B10')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('C10', $pay['ot15b'])->getStyle('C10')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('D10', $pay['ot2b'])->getStyle('D10')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('E10', $pay['ot3b'])->getStyle('E10')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('F10', $pay['ootb'])->getStyle('F10')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');

	$sheet->setCellValue('A12', $lng['Fixed allowances']);
	$sheet->setCellValue('F12', $lng['Baht']);
	$sheet->mergeCells('A12:E12');
	$sheet->getStyle('F12')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->getStyle('A12:F12')->applyFromArray($allBorders);
	$sheet->getStyle('A12:F12')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row = 13;
	
	foreach($fix_allow as $k=>$v){
		$sheet->setCellValue('A'.$row, $k)->mergeCells('A'.$row.':E'.$row);
		$sheet->setCellValue('F'.$row, $v)->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	}
	$row ++;
	
	$sheet->setCellValue('A'.$row,  $lng['Variable allowances']);
	$sheet->setCellValue('F'.$row,  $lng['Baht']);
	$sheet->mergeCells('A'.$row.':E'.$row);
	$sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($allBorders);
	$sheet->getStyle('A'.$row.':F'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	
	foreach($var_allow as $k=>$v){
		$sheet->setCellValue('A'.$row, $k)->mergeCells('A'.$row.':E'.$row);
		$sheet->setCellValue('F'.$row, $v)->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	}
	$row ++;
	
	$sheet->setCellValue('A'.$row,  $lng['Fixed deductions']);
	$sheet->setCellValue('F'.$row,  $lng['Baht']);
	$sheet->mergeCells('A'.$row.':E'.$row);
	$sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($allBorders);
	$sheet->getStyle('A'.$row.':F'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	
	foreach($fix_deduct as $k=>$v){
		$sheet->setCellValue('A'.$row, $k)->mergeCells('A'.$row.':E'.$row);
		$sheet->setCellValue('F'.$row, $v)->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	}
	$row ++;
	
	$sheet->setCellValue('A'.$row,  $lng['Variable deductions']);
	$sheet->setCellValue('F'.$row,  $lng['Baht']);
	$sheet->mergeCells('A'.$row.':E'.$row);
	$sheet->getStyle('F'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($allBorders);
	$sheet->getStyle('A'.$row.':F'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	
	foreach($var_deduct as $k=>$v){
		$sheet->setCellValue('A'.$row, $k)->mergeCells('A'.$row.':E'.$row);
		$sheet->setCellValue('F'.$row, $v)->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	}
	$row ++;
	
	$sheet->setCellValue('A'.$row, $lng['Deductions'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('E'.$row, $lng['Hours']);
	$sheet->setCellValue('F'.$row, $lng['Baht'])->getColumnDimension('I')->setWidth(12);
	$sheet->getStyle('D'.$row.':F'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('A'.$row.':F'.$row)->applyFromArray($allBorders);
	$sheet->getStyle('A'.$row.':F'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	
	$row ++;
	$sheet->setCellValue('A'.$row, $lng['Absence'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('E'.$row, $pay['absence'])->getStyle('E'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('F'.$row, $pay['absence_b'])->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"'); 
	$row ++;
	$sheet->setCellValue('A'.$row, $lng['Late Early'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('E'.$row, $pay['late_early'])->getStyle('E'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('F'.$row, $pay['late_early_b'])->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('A'.$row, $lng['Leave WOP'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('E'.$row, $pay['leave_wop'])->getStyle('E'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->setCellValue('F'.$row, $pay['leave_wop_b'])->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('A'.$row, $lng['Deductions before'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('F'.$row, $pay['tot_deduct_before'])->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('A'.$row, $lng['Deductions after'])->mergeCells('A'.$row.':D'.$row);
	$sheet->setCellValue('F'.$row, $pay['tot_deduct_after'])->getStyle('F'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	
	
	
	
	$sheet->setCellValue('H8', $lng['Monthly salary information'])->getColumnDimension('H')->setAutoSize(true);
	$sheet->setCellValue('I8', $lng['Baht'])->getColumnDimension('I')->setWidth(13);
	$sheet->getStyle('I8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

	$sheet->getStyle('H8:I8')->applyFromArray($allBorders);
	$sheet->getStyle('H8:I8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row = 9;
	
	$sheet->setCellValue('H'.$row, $lng['Salary']);
	$sheet->setCellValue('I'.$row, $pay['salary'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Overtime']);
	$sheet->setCellValue('I'.$row, $pay['total_otb'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('H'.$row, $lng['Total salary']);
	$sheet->setCellValue('I'.$row, ($pay['salary']+$pay['total_otb']))->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Fixed allowances']);
	$sheet->setCellValue('I'.$row, $pay['total_fix_allow'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Variable allowances']);
	$sheet->setCellValue('I'.$row, $pay['total_var_allow'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Other income']);
	$sheet->setCellValue('I'.$row, $other_income)->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['End contract']);
	$sheet->setCellValue('I'.$row, $end_contract)->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Gross income']);
	$sheet->setCellValue('I'.$row, $pay['gross_income'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->getStyle('H'.$row.':I'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['PVF']);
	$sheet->setCellValue('I'.$row, $pay['pvf_employee'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['SSO']);
	$sheet->setCellValue('I'.$row, $pay['social'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Tax']);
	$sheet->setCellValue('I'.$row, $pay['tax'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Other deductions']);
	$sheet->setCellValue('I'.$row, $pay['tot_deduct'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Net income']);
	$sheet->setCellValue('I'.$row, $pay['net_income'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->getStyle('H'.$row.':I'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	$sheet->setCellValue('H'.$row, $lng['Advance payment']);
	$sheet->setCellValue('I'.$row, $pay['advance'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['Legal deductions']);
	$sheet->setCellValue('I'.$row, $pay['legal_deductions'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	
	$sheet->setCellValue('H'.$row, $lng['NET PAID SALARY']);
	$sheet->setCellValue('I'.$row, $pay['net_income']-$pay['advance']-$pay['legal_deductions'])->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$sheet->getStyle('H'.$row.':I'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	
	
	$sheet->setCellValue('K8', $lng['Tax calculation'])->getColumnDimension('K')->setAutoSize(true);
	$sheet->setCellValue('L8', $lng['Baht'])->getColumnDimension('L')->setWidth(13);
	$sheet->getStyle('L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

	$sheet->getStyle('K8:L8')->applyFromArray($allBorders);
	$sheet->getStyle('K8:L8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row = 9;
	
	$sheet->setCellValue('K'.$row, $lng['Gross year income']);
	$sheet->setCellValue('L'.$row, $array['gross_year'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('K'.$row, $lng['Total tax deductions']);
	$sheet->setCellValue('L'.$row, $array['tax_deductions'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('K'.$row, $lng['Taxable year income']);
	$sheet->setCellValue('L'.$row, $array['taxable_year'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	$sheet->setCellValue('K'.$row, $lng['Tax whole year']);
	$sheet->setCellValue('L'.$row, $array['tax_year'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	$row ++;
	//$sheet->setCellValue('K'.$row, $lng['Tax month']);
	//$sheet->setCellValue('L'.$row, $array['tax_this_month'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
	//$row ++;
	//if($array['tax_modify'] != '0'){
		$sheet->setCellValue('K'.$row, $lng['Modified tax']);
		$sheet->setCellValue('L'.$row, $array['tax_modify'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
		$sheet->setCellValue('K'.$row, $lng['Tax this month']);
		$sheet->setCellValue('L'.$row, $array['tax_this_month'])->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	//}
	//$row ++;

	$sheet->setCellValue('K'.$row, $lng['Tax deductions'])->getColumnDimension('K')->setAutoSize(true);
	$sheet->setCellValue('L'.$row, $lng['Baht']);
	$sheet->getStyle('L'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

	$sheet->getStyle('K'.$row.':L'.$row)->applyFromArray($allBorders);
	$sheet->getStyle('K'.$row.':L'.$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$row ++;
	
	foreach($taxes as $k=>$v){
	$sheet->setCellValue('K'.$row, $k);
	$sheet->setCellValue('L'.$row, $v)->getStyle('L'.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
		$row ++;
	}
	
	$sheet->getColumnDimension('G')->setWidth(3);
	$sheet->getColumnDimension('J')->setWidth(3);
	//$sheet->getColumnDimension('M')->setWidth(3);
	
	$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
	//$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
	$sheet->getPageSetup()->setFitToPage(true);	

	$sheet->freezePane('A2','A2');
	$filename = 'Overview '.$_SESSION['rego']['report_id'].' '.$months[$_GET['m']].' '.$_SESSION['rego']['year_'.$lang];
	$sheet->setTitle($months[$_GET['m']].' '.$_SESSION['rego']['year_'.$lang]);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>