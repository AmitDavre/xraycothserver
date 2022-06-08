<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	//var_dump($compinfo); exit;
	//$cid = $_SESSION['rego']['cid'];
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	//$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$branch = sprintf("%06d",$sso_codes[$_SESSION['rego']['gov_branch']]['code']);
	
	$period = $_SESSION['rego']['curr_month'].substr($_SESSION['rego']['year_th'],-2);
	
	$sso_act_max = $pr_settings['sso_act_max'];
	$sso_rate = sprintf("%04d",number_format((float)$pr_settings['sso_rate_emp'], 2, '', ''));

	$data = array();
	if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND branch = '".$_SESSION['rego']['gov_branch']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_sso = 1 ORDER by emp_id ASC")){
		$nr=1; $tot_salary = 0; $tot_social = 0;
		while($row = $res->fetch_assoc()){
			$data[$nr]['emp_id'] = $row['emp_id'];
			$empinfo = getEmployeeInfo($cid, $row['emp_id']);
			$fix_allow = 0; 
			for($i=1;$i<=10;$i++){
				$fix_allow += $row['fix_allow_'.$i]; // ????????????????????? from payroll database
			}
			$data[$nr]['title'] = $title[$empinfo['title']];
			$data[$nr]['firstname'] = $empinfo['firstname'];
			$data[$nr]['lastname'] = $empinfo['lastname'];
			$data[$nr]['en_name'] = $empinfo['en_name'];
			$data[$nr]['idcard_nr'] = $empinfo['idcard_nr'];
			$basicsalary = $row['salary'] + $fix_allow;
			if($sso_act_max == 'act'){
				$basic_salary = $basicsalary;
			}else{
				$basic_salary = ($basicsalary > $rego_settings['sso_max_wage'] ? $rego_settings['sso_max_wage'] : $basicsalary);
			}
			$basic_salary = ($basicsalary < $rego_settings['sso_min_wage'] ? $rego_settings['sso_min_wage'] : $basicsalary);
			$data[$nr]['basic_salary'] = $basic_salary;
			$data[$nr]['ssf'] = $row['social'];
			$tot_salary += $basic_salary; 
			$tot_social+= $row['social'];
			$nr++;
		}
	}
	//var_dump($data); exit;
	
	$field = array();
		$field['A'] = 'เลขประจำตัวประชาชน';
		$field['B'] = 'คำนำหน้าชื่อ';
		$field['C'] = 'ชื่อผู้ประกันตน';
		$field['D'] = 'นามสกุลผู้ประกันตน';
		$field['E'] = 'ค่าจ้าง';
		$field['F'] = 'จำนวนเงินสมทบ';
	
	//var_dump($field); exit;
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	//$filename = '../'.$cid.'/'.$cid.'_employees.xlsx';
	
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
	
	foreach($field as $k=>$v){
		$sheet->getStyle($k.'1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValue($k.'1', $v);
		$sheet->getStyle($k.'1')->applyFromArray($allBorders);
		$sheet->getColumnDimension($k)->setAutoSize(true);
	}
	
	$sheet->getStyle('A1:F1')->getFont()->setBold(true)->getColor()->setRGB('000000');
	$sheet->getStyle('A1:F1')->getFont()->setBold(true);
	$sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('E')->getNumberFormat()->setFormatCode('#,##0.00');
	$sheet->getStyle('F')->getNumberFormat()->setFormatCode('#,##0.00');
	$sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);


	$r=2;
	foreach($data as $k=> $v){
		$sheet->getStyle('A'.$r)->getNumberFormat()->setFormatCode('00000');	
		$sheet->setCellValue('A'.$r, $v['idcard_nr']);
		$sheet->setCellValue('B'.$r, $v['title']);
		$sheet->setCellValue('C'.$r, $v['firstname']);
		$sheet->setCellValue('D'.$r, $v['lastname']);
		$sheet->setCellValue('E'.$r, round($v['basic_salary'],2));
		$sheet->setCellValue('F'.$r, round($v['ssf'],2));
		
		/*echo('A'.$r. $v['idcard_nr']);
		echo('B'.$r. $v['title']);
		echo('C'.$r. $v['firstname']);
		echo('D'.$r. $v['lastname']);
		echo('E'.$r. round($v['basic_salary'],2));
		echo('F'.$r. round($v['ssf'],2));
		echo('G'.$r. $r-1);*/
		
		$r++;
	}
	
	//exit;
	
	/*$sheet->setCellValue('H2', 'เดือน');
	$sheet->setCellValue('I2', $months[(int)$_SESSION['rego']['cur_month']]);
	$sheet->setCellValue('J2', $_SESSION['rego']['year_th']);
	
	$sheet->getStyle('H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('I2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);*/
	
	//$sheet->setCellValue('E'.$r, '=SUM(E2:E'.($r-1).')');
	//$sheet->setCellValue('F'.$r, '=SUM(F2:F'.($r-1).')');
	
	//$sheet->getStyle('E'.$r)->applyFromArray($allBorders)->getFont()->setBold(true);
	//$sheet->getStyle('F'.$r)->applyFromArray($allBorders)->getFont()->setBold(true);
	
	//$sheet->getStyle('E'.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	//$sheet->getStyle('F'.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	
	//exit;
	
	$sheet->freezePane('A2');
	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	//$sheet->getHeaderFooter()->setOddHeader('&L&G&C&HPlease treat this document as confidential!');
	//$sheet->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');
	// Set page orientation and size
	//$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	//$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	// Rename worksheet
	$sheet->setTitle($branch);
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	//$objPHPExcel->setActiveSheetIndex(0);
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$cid.'_SSO attachement_'.$_SESSION['rego']['year_th'].'_'.$_SESSION['rego']['cur_month'].'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>