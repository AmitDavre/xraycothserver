<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	//include('inc/arrays_'.$_SESSION['admin']['lang'].'.php');
	//$cid = $_SESSION['rego']['cid'];
	$fix_allow = getUsedFixAllow($_SESSION['rego']['lang']);
	$var_allow = getUsedVarAllow($_SESSION['rego']['lang']);
	$costomernameandID = getCustomersforpayroll($_SESSION['rego']['cid']);
	//var_dump($fix_allow);
	//var_dump($departments); exit;

	// echo $costomernameandID[$_SESSION['rego']['cid']];

	$fix_deduct = getUsedFixDeduction($_SESSION['rego']['lang']);
	$var_deduct = getUsedVarDeduction($_SESSION['rego']['lang']);
	
	
	
	$ncol['emp_id'] = $lng['Emp. ID']; 
	$ncol['emp_name_'.$_SESSION['rego']['lang']] = $lng['Employee']; 
	//$ncol['basic_salary'] = 'Actual days'; 
	$ncol['paid_days'] = $lng['Days paid']; 
	$ncol['salary'] = $lng['Salary']; 
	//$ncol['actual_days'] = 'Actual days'; 
	$ncol['ot1b'] = $lng['OT 1']; 
	$ncol['ot15b'] = $lng['OT 1.5']; 
	$ncol['ot2b'] = $lng['OT 2']; 
	$ncol['ot3b'] = $lng['OT 3']; 
	$ncol['ootb'] = $lng['Other OT']; 
	foreach($fix_allow as $k=>$v){
		$ncol['fix_allow_'.$k] = $v;
	}
	foreach($var_allow as $k=>$v){
		$ncol['var_allow_'.$k] = $v;
	}
	
	$ncol['tax_by_company'] = $lng['Tax by company'];
	$ncol['sso_by_company'] = $lng['SSO by company'];

	$ncol['other_income'] = $lng['Other income'];
	$ncol['severance'] = $lng['Severance'];
	$ncol['remaining_salary'] = $lng['Remaining salary'];
	$ncol['notice_payment'] = $lng['Notice payment'];
	$ncol['paid_leave'] = $lng['Paid leave'];

	$ncol['gross_income'] = $lng['Total income'];

	foreach($fix_deduct as $k=>$v){
		$ncol['fix_deduct_'.$k] = $v;
	}
	foreach($var_deduct as $k=>$v){
		$ncol['var_deduct_'.$k] = $v;
	}
	
	$ncol['absence_b'] = $lng['Absence']; 
	$ncol['late_early_b'] = $lng['Late Early']; 
	$ncol['leave_wop_b'] = $lng['Leave WOP']; 
	
	//$ncol['tot_deduct_before'] = $lng['Before tax']; 
	//$ncol['tot_deduct_after'] = $lng['After tax']; 
	$ncol['pvf_employee'] = $lng['PVF']; 
	//$ncol['psf_employee'] = $lng['PSF']; 
	$ncol['social'] = $lng['SSO']; 
	$ncol['tax'] = $lng['Tax']; 
	$ncol['gov_house_banking'] = $lng['Government house banking']; 
	$ncol['savings'] = $lng['Savings'];
	$ncol['legal_execution'] = $lng['Legal execution deduction']; 
	$ncol['kor_yor_sor'] = $lng['Kor.Yor.Sor (Student loan)'];    
	$ncol['advance'] = $lng['Advance']; 

	//$ncol['legal_deductions'] = $lng['Legal deductions']; 
	 
	$ncol['tot_deductions'] = $lng['Total deductions']; 
	$ncol['net_income'] = $lng['Net income']; 
	
	if(!isset($_GET['f'])){$_GET['f'] = $_SESSION['rego']['cur_month'];}
	if(!isset($_GET['t'])){$_GET['t'] = $_SESSION['rego']['cur_month'];}
	if($_GET['f'] == $_GET['t']){
		$fromto = sprintf('%02d', $_GET['f']).'_'.$_SESSION['rego']['year_'.$_SESSION['rego']['lang']];
	}else{
		$fromto = sprintf('%02d', $_GET['f']).'-'.sprintf('%02d', $_GET['t']).'_'.$_SESSION['rego']['year_'.$_SESSION['rego']['lang']];
	}
	$eCols = getEmptyPayrollCols($_GET['f'], $_GET['t']);
	//var_dump($ncol); exit;
	
	$data = array();
	$data = getPayrollDataWithoutDep();
	//var_dump($data); exit;

	//echo '<pre>';
	//print_r($data);
	//print_r($data);
	//echo '</pre>'; //exit;
	
	foreach($data as $d=>$dep){
		//foreach($dep as $key=>$val){
			foreach($eCols as $k=>$v){
				//var_dump($data[$key][$k]);
				unset($data[$d][$k], $ncol[$k]);
			}
		//}
	}
	//var_dump($data); exit;

	// echo '<pre>';
	// //print_r($data);
	// print_r($data);
	// echo '</pre>';

	$n=0;
	foreach($ncol as $k=>$v){
		$field[getNameFromNumber($n)] = $ncol[$k];
		$n++;
	}
	$lastCol = getNameFromNumber($n-1);
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$oooOutline = array(
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => array('rgb' => '999999'),
			),
		),
	);
	$cccOutline = array(
		'borders' => array(
			'allBorders' => array(
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => array('rgb' => 'cccccc'),
			),
		),
	);

	// echo '<pre>';
	// print_r($field);
	// print_r($data);
	// echo '</pre>'; exit;


	
	//$sheet->getDefaultStyle()->getFont()->setSize(11);
	$sheet->getColumnDimension('A')->setWidth(13);
	//$sheet->getColumnDimension('B')->setWidth(33);
	$sheet->getColumnDimension('B')->setAutoSize(true);

	$GIkey = $TDkey = $NIkey = '';
	foreach($field as $ksd=>$vsd){
		if($vsd == $lng['Total income']){
			$GIkey = $ksd;
		}if($vsd == $lng['Total deductions']){
			$TDkey = $ksd;
		}if($vsd == $lng['Net income']){
			$NIkey = $ksd;
		}
	}
	
	$r=1; $c=0; 

	$sheet->mergeCells('A'.$r.':'.$lastCol.$r);
	$sheet->getStyle('A'.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	$sheet->setCellValue('A'.$r, $costomernameandID[$_SESSION['rego']['cid']]);
	$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFont()->setBold(true);
	$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('eeeeee');

	$sheet->getStyle($GIkey.$r)->getFill()->getStartColor()->setRGB('a9d08e');
	$sheet->getStyle($TDkey.$r)->getFill()->getStartColor()->setRGB('f8cbad');
	$sheet->getStyle($NIkey.$r)->getFill()->getStartColor()->setRGB('bdd7ee');

	$r++;
	foreach($field as $k=>$v){

		if($k!=='A' && $k!=='B'){
			$sheet->getStyle($k.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}
		$sheet->setCellValue($k.$r, $v);
		$sheet->getColumnDimension($k)->setWidth(14);
		//$sheet->getColumnDimension($k)->setAutoSize(true);
		$sheet->getStyle($k.($r-1))->applyFromArray($oooOutline);
		$sheet->getStyle($k.$r)->applyFromArray($oooOutline);

		$sheet->getStyle($k.$r.':'.$lastCol.$r)->getFont()->setBold(true);
		$sheet->getStyle($k.$r.':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle($k.$r.':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('eeeeee');

		$sheet->getStyle($GIkey.$r)->getFill()->getStartColor()->setRGB('a9d08e');
		$sheet->getStyle($TDkey.$r)->getFill()->getStartColor()->setRGB('f8cbad');
		$sheet->getStyle($NIkey.$r)->getFill()->getStartColor()->setRGB('bdd7ee');
	}

	foreach($data as $d=>$dep){

		$r++;
		$rs = $r;
		$emps = 0;
		
		foreach($dep as $k=>$v){
			$sheet->setCellValue(getNameFromNumber($c).$r, $v); 

			$c++;	
		}

		$emps ++;

		$sheet->getStyle($GIkey.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('a9d08e');
		$sheet->getStyle($TDkey.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f8cbad');
		$sheet->getStyle($NIkey.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('bdd7ee');
		
		$c=0;		

	}

	$r += 1;
	foreach($field as $k=>$v){
		if($k=='A'){
			$sheet->setCellValue($k.$r, ($rs-2).' '.$lng['Employees']);
		}
		if($k!=='A' && $k!=='B'){
			$sheet->setCellValue($k.$r, '=SUM('.$k.'3:'.$k.$rs.')');
		}
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->applyFromArray($cccOutline);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFont()->setBold(true);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('eeeeee');
	}
		


	//var_dump($gRow); exit;
	
	$highestRow = $sheet->getHighestDataRow();
	$sheet->getStyle('C3:'.$lastCol.$highestRow)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');	
	
	//$sheet->freezePane('B2');
	$sheet->setTitle('Payroll_'.$fromto);
	
	ob_end_clean();
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$cid.'_payroll_report_'.$fromto.'.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');


?>