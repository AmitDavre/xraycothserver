<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');

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
	//echo 'Test'; exit;

	$sheet->setCellValue('A1', 'Code');
	$sheet->setCellValue('B1', 'English');
	$sheet->setCellValue('C1', 'Thai');

	$sheet->getStyle('A1:C1')->getFont()->setSize(12);
	$sheet->getStyle('A1:C1')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('FFccffcc');
	$sheet->getStyle('A1:C1')->applyFromArray($allBorders);
	$sheet->getColumnDimension('A')->setWidth(60);
	$sheet->getColumnDimension('B')->setWidth(70);
	$sheet->getColumnDimension('C')->setWidth(70);

	$sql = "SELECT * FROM rego_application_language ORDER BY en ASC";
	$res = $dba->query($sql);
	$r=2;
	while($row = $res->fetch_assoc()){
		$sheet->setCellValue('A'.$r, $row['code']);
		$sheet->setCellValue('B'.$r, $row['en']);
		$sheet->setCellValue('C'.$r, $row['th']);
		$r++;
	}

	$sheet->freezePane('A2');
	$sheet->setTitle('REGO Language');

	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="REGO language file.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');