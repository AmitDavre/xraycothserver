<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	include('../inc_cost_company.php');
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
	
	$sheet->setCellValue('A1', $lng['Cost to company per year'].' : '.$_SESSION['rego']['year_'.$lang]);
	$sheet->mergeCells('A1:N1');
	$sheet->getStyle('A1')->getFont()->setSize(14);
	$sheet->getStyle('A1')->getFont()->setBold(true);

	$row = 2;
	$col = 0;
	$abc = getNameFromNumber($col);
	$sheet->setCellValue($abc.$row, '')->getColumnDimension($abc)->setAutoSize(true);
	$col ++;
	foreach($months as $v){ 
		$abc = getNameFromNumber($col);
		$sheet->setCellValue($abc.$row, $v)->getColumnDimension($abc)->setWidth(15);
		$col ++;
	}
	$abc = getNameFromNumber($col);
	$sheet->setCellValue($abc.$row, $lng['Total'])->getColumnDimension($abc)->setWidth(15);
	
	$sheet->getStyle('A2:N2')->applyFromArray($allBorders);
	$sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('A2:N2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$row = 3;
	$col = 0;
	
	foreach($data as $key=>$val){
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
	$sheet->setCellValue('A'.($hRow+1), $lng['Subtotal']);
	
	for($i=1;$i<=13;$i++){
		$abc = getNameFromNumber($i);
		$sheet->setCellValue($abc.($hRow+1), '=SUM('.$abc.'3:'.$abc.$hRow.')')->getStyle($abc.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');;
	}
	//$sheet->getStyle('A'.$hRow+1.':N'.$hRow+1)->applyFromArray($allBorders);
	$sheet->getStyle('A'.($hRow+1).':N'.($hRow+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('A'.($hRow+1).':N'.($hRow+1))->getFont()->setBold(true);

	$col = 0;
	$row = $hRow+2;
	foreach($xdata as $key=>$val){
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
	$sheet->setCellValue('A'.($hRow+1), $lng['Total']);
	
	for($i=1;$i<=13;$i++){
		$abc = getNameFromNumber($i);
		$sheet->setCellValue($abc.($hRow+1), '=SUM('.$abc.($hRow-2).':'.$abc.$hRow.')')->getStyle($abc.$row)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-"');;
	}
	//$sheet->getStyle('A'.$hRow+1.':N'.$hRow+1)->applyFromArray($allBorders);
	$sheet->getStyle('A'.($hRow+1).':N'.($hRow+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getStyle('A'.($hRow+1).':N'.($hRow+1))->getFont()->setBold(true);
	
	$sheet->freezePane('A2','A2');
	$filename = $lng['Cost to company'].' '.$_SESSION['rego']['year_'.$lang];
	$sheet->setTitle($lng['Year'].' '.$_SESSION['rego']['year_'.$lang]);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>