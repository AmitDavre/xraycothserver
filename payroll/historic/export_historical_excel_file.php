<?php
	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//var_dump($_REQUEST); exit;
	
	$ncol['emp_id'] = 'Employee ID';
	$ncol['emp_name_en'] = 'Employee name';
	$ncol['month'] = 'Month';
	$res = $dbc->query("SELECT his_cols FROM ".$_SESSION['rego']['cid']."_sys_settings");
	$row = $res->fetch_assoc();
	$ncol += unserialize($row['his_cols']);
	
	$n=0;
	foreach($ncol as $k=>$v){
		$field[getNameFromNumber($n)][] = $k;
		$field[getNameFromNumber($n)][] = $v;
		$n++;
	}
	$lastCol = getNameFromNumber($n-1);
	//var_dump($field); exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$filename = $cid.'_historic-data_'.$_SESSION['rego']['cur_year'].'.xlsx';
		
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
	 
	$txt = 'This sheet is protected.  This file can be used to upload historical employee data to the payroll module.'.PHP_EOL.'Please note that you can use formulas to assemble your data.'.PHP_EOL.'BUT you do need to replace the formulas by values for having the data uploaded correctly.';
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->mergeCells('A1:C1');
	$sheet->mergeCells('D1:'.$lastCol.'1');
	$sheet->setCellValue('A1', 'Historical data '.strtoupper($_SESSION['rego']['cid']));
	$sheet->setCellValue('D1', $txt);
	$sheet->getRowDimension(1)->setRowHeight(47); 
	$sheet->getStyle('D1')->getAlignment()->setWrapText(true);	
	$sheet->getStyle('A1')->getFont()->setSize(12);
	$sheet->getStyle('A1:'.$lastCol.'1')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('FFccffcc');
	$sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray($allBorders);

	foreach($field as $k=>$v){
		$sheet->setCellValue($k.'2', $v[0]);
		$sheet->setCellValue($k.'3', $v[1]);
		$sheet->getStyle($k.'3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle($k.'3')->applyFromArray($allBorders);
		$sheet->getColumnDimension($k)->setWidth('15');
	}
	
	$sheet->getRowDimension('2')->setVisible(false);
	$sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	$sheet->getColumnDimension('B')->setWidth('30');
	$sheet->getColumnDimension('C')->setWidth('10');
	$sheet->getStyle('A3:'.$lastCol.'3')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setRGB('dae8f6');
		
	$sql = "SELECT * FROM ".$cid."_historic_data WHERE entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER BY month ASC, emp_id ASC";
	if($res = $dbc->query($sql)){
		$r=4;
		while($row = $res->fetch_assoc()){
			foreach($field as $k=>$v){
				$sheet->setCellValue($k.$r, $row[$v[0]]);
			}
			$r++;
		}
	}
	//var_dump($field); exit;
	
	$hRow = $sheet->getHighestDataRow();
	$sheet->getProtection()->setSheet(true);
   $sheet->getStyle('D4:'.$lastCol.$hRow)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
	$sheet->getStyle('D4:'.$lastCol.$hRow)->getNumberFormat()->setFormatCode('[<>0]#,##0.00');	
	$sheet->freezePane('D4','D4');
	$sheet->setTitle($_SESSION['rego']['cid']);
	
	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>