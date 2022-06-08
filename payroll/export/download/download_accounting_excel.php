<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$accounting = array();
	if($res = $dbc->query("SELECT accounting FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."'")){
		if($row = $res->fetch_assoc()){
			$accounting = unserialize($row['accounting']);
			//var_dump($accounting);
			$debet_data = $accounting['debet_data'];
			$credit_data = $accounting['credit_data'];
			$total_debet = $accounting['total_debet'];
			$total_credit = $accounting['total_credit'];
		}
	}
	//var_dump($accounting); exit;
	
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
	
	$sheet->setCellValue('A1','Accounting Entries : '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]);
	$sheet->setCellValue('A2','Account code');
	$sheet->setCellValue('B2','Debet')->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->setCellValue('C2','Credit')->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	
	$sheet->mergeCells('A1:C1');
	$sheet->getStyle('A1')->getFont()->setSize(18);
	$sheet->getStyle('A1:C2')->getFont()->setBold(true);
	$sheet->getStyle('A2:C2')->applyFromArray($allBorders);
	$sheet->getStyle('A2:C2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	$sheet->getColumnDimension('C')->setAutoSize(true);
	$sheet->getStyle('B')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->getStyle('C')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
		
	$r=3;
	foreach($debet_data as $k=>$v){
		$sheet->setCellValue('A'.$r, $k);
		$sheet->setCellValue('B'.$r, $v);
		$sheet->setCellValue('C'.$r, 0);
		$r++;
	}
	foreach($credit_data as $k=>$v){
		$sheet->setCellValue('A'.$r, $k);
		$sheet->setCellValue('B'.$r, 0);
		$sheet->setCellValue('C'.$r, $v);
		$r++;
	}
	$sheet->setCellValue('A'.$r, 'Totals')->getStyle('A'.$r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->setCellValue('B'.$r, '=SUM(B3:B'.($r-1).')');
	$sheet->setCellValue('C'.$r, '=SUM(C3:C'.($r-1).')');
	$sheet->getStyle('A'.$r.':C'.$r)->getFont()->setBold(true);
	
	$sheet->freezePane('A3','A3');
	$filename = 'Accounting '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang];
	$sheet->setTitle($filename);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>