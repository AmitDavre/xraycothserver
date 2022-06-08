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
	//var_dump($fix_allow);
	//var_dump($departments); exit;
	
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
	
	$ncol['gross_income'] = $lng['Total earnings']; 

	$ncol['absence_b'] = $lng['Absence']; 
	$ncol['late_early_b'] = $lng['Late Early']; 
	$ncol['leave_wop_b'] = $lng['Leave WOP']; 
	$ncol['tot_deduct_before'] = $lng['Before tax']; 
	$ncol['tot_deduct_after'] = $lng['After tax']; 
	$ncol['pvf_employee'] = $lng['PVF']; 
	$ncol['psf_employee'] = $lng['PSF']; 
	$ncol['social'] = $lng['SSO']; 
	$ncol['tax'] = $lng['Tax']; 
	
	$ncol['tot_deductions'] = $lng['Total deductions']; 
	
	$ncol['advance'] = $lng['Advance']; 
	$ncol['legal_deductions'] = $lng['Legal deductions']; 
	$ncol['net_income'] = $lng['Net pay']; 
	
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
	$data = getPayrollDataDep();
	//var_dump($data); exit;
	
	foreach($data as $d=>$dep){
		foreach($dep as $key=>$val){
			foreach($eCols as $k=>$v){
				//var_dump($data[$key][$k]);
				unset($data[$d][$key][$k], $ncol[$k]);
			}
		}
	}
	//var_dump($data); exit;

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

	
	//$sheet->getDefaultStyle()->getFont()->setSize(11);
	$sheet->getColumnDimension('A')->setWidth(13);
	//$sheet->getColumnDimension('B')->setWidth(33);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	
	$r=1; $c=0; $tot_emps = 0;
	foreach($data as $d=>$dep){
		$sheet->mergeCells('A'.$r.':'.$lastCol.$r);
		$sheet->getStyle('A'.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		$sheet->setCellValue('A'.$r, $departments[$d][$lang]); $r++;
		foreach($field as $k=>$v){
			if($k!=='A' && $k!=='B'){
				$sheet->getStyle($k.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				//$sheet->getStyle($k)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
			}
			$sheet->setCellValue($k.$r, $v);
			$sheet->getColumnDimension($k)->setWidth(14);
			//$sheet->getColumnDimension($k)->setAutoSize(true);
			$sheet->getStyle($k.($r-1))->applyFromArray($oooOutline);
			$sheet->getStyle($k.$r)->applyFromArray($oooOutline);
		}
		$sheet->getStyle('A'.($r-1).':'.$lastCol.$r)->getFont()->setBold(true);
		$sheet->getStyle('A'.($r-1).':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A'.($r-1).':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('fde9d9');
		$r++;
		$rs = $r;
		$emps = 0;
		foreach($dep as $key=>$val){
			foreach($val as $k=>$v){
				$sheet->setCellValue(getNameFromNumber($c).$r, $v); $c++;
			}
			$r++; $c=0;
			$emps ++;
			$tot_emps ++;
		}
		foreach($field as $k=>$v){
			if($k=='A'){
				$sheet->setCellValue($k.$r, $emps.' '.$lng['Employees']);
			}
			if($k!=='A' && $k!=='B'){
				$sheet->setCellValue($k.$r, '=SUM('.$k.$rs.':'.$k.($r-1).')');
			}
			$sheet->getStyle('A'.$r.':'.$lastCol.$r)->applyFromArray($cccOutline);
			$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFont()->setBold(true);
			$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
			$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('eeeeee');
		}
		$r += 2;
	}
	//var_dump($xdata); exit;
	
	/*foreach($field as $k=>$v){
		if($k=='A'){
			$sheet->setCellValue($k.$r, $tot_emps.' '.$lng['Employees']);
		}elseif($k=='B'){
			$sheet->setCellValue($k.$r, 'Grand Total');
		}else{
			$sheet->setCellValue($k.$r, '=SUM('.$k.$rs.':'.$k.($r-1).')');
		}
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->applyFromArray($cccOutline);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFont()->setBold(true);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFill()->getStartColor()->setRGB('333333');
		$sheet->getStyle('A'.$r.':'.$lastCol.$r)->getFont()->getColor()->setRGB('ffffff');
		//->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
	}*/

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