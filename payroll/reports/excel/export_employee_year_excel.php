<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	//$_GET['id'] = 'DEMO-001';
	if(!isset($_SESSION['rego']['report_id'])){exit;}
	
	$employee = getEmployeeInfo($cid, $_SESSION['rego']['report_id']);
	$service_years = getAge($employee['startdate']);
	//var_dump($service_years);
	//var_dump($employee);
	
	include('../inc_employee_year.php');
	//var_dump($pr);
	
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
	
	$sheet->setCellValue('A1', $lng['Salary overview'].' '.$_SESSION['rego']['report_id'].' : '.$employee[$lang.'_name'].' '.$_SESSION['rego']['year_'.$lang]);
	$sheet->mergeCells('A1:N1');
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->getStyle('A1')->getFont()->setSize(14);
	$sheet->getStyle('A1')->getFont()->setBold(true);

	$sheet->setCellValue('A2', $lng['Employee'])->mergeCells('A2:D2');
	$sheet->getStyle('A2:D2')->applyFromArray($allBorders);
	$sheet->getStyle('A2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->setCellValue('A3', $lng['Employee ID'])->getColumnDimension('A')->setAutoSize(true);
	//$sheet->setCellValue('B3', $employee['emp_id'])->getColumnDimension('B')->setAutoSize(true);
	$sheet->mergeCells('B3:D3');

	$sheet->setCellValue('A4', $lng['Name']);
	$sheet->setCellValue('B4', $employee[$lang.'_name']);
	$sheet->mergeCells('B4:D4');

	$sheet->setCellValue('A5', $lng['Position']);
	$sheet->setCellValue('B5', $positions[$employee['position']]);
	$sheet->mergeCells('B5:D5');

	$sheet->setCellValue('A6', $lng['Joining date']);
	$sheet->setCellValue('B6', $employee['startdate']);
	$sheet->mergeCells('B6:D6');

	$sheet->setCellValue('A7', $lng['Service years']);
	$sheet->setCellValue('B7', $service_years);
	$sheet->mergeCells('B7:D7');

	$row = 8;
	$col = 0;
	
	$abc = getNameFromNumber($col);
	$sheet->setCellValue($abc.$row, $lng['Month'])->getColumnDimension($abc)->setAutoSize(true);
	$col ++;
	foreach($months as $v){ 
		$abc = getNameFromNumber($col);
		$sheet->setCellValue($abc.$row, $v)->getColumnDimension($abc)->setWidth(15);
		$col ++;
	}
	$abc = getNameFromNumber($col);
	$sheet->setCellValue($abc.$row, $lng['Total'])->getColumnDimension($abc)->setWidth(15);
	
	$sheet->getStyle('A8:N8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('A8:N8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$row = 9;
	$col = 0;
	
	foreach($pr as $key=>$val){
		foreach($val as $k=>$v){
			$abc = getNameFromNumber($col);
			$sheet->setCellValue($abc.$row, $k);
			$sheet->setCellValue($abc.$row, $v)->getStyle($abc.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');
			$col ++;
		}
		$row ++;
		$col = 0;
	}
	$hRow = $sheet->getHighestDataRow();
	$hCol = $sheet->getHighestColumn();
	
	$sheet->getStyle('A'.($hRow-2).':'.$hCol.($hRow-2))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('A'.$hRow.':'.$hCol.$hRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->getStyle('A8:'.$hCol.$hRow)->applyFromArray($allBorders);
	//$sheet->getColumnDimension('E')->setWidth(3);
		
	$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
	//$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
	$sheet->getPageSetup()->setFitToPage(true);	
	
	$sheet->freezePane('A2','A2');
	$filename = $lng['Overview'].' '.$_SESSION['rego']['report_id'].' '.$_SESSION['rego']['year_'.$lang];
	$sheet->setTitle($lng['Overview'].' '.$_SESSION['rego']['year_'.$lang]);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>