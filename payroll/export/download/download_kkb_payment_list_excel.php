<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_th.php');
	//$bank_codes = unserialize($sys_settings['bank_codes']);
	//var_dump($bank_codes); exit;
	
	$nr = 1;
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '004', 'all');
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.$empinfo['th_name'];}
					$data[$nr]['name'] = $name;
					$data[$nr]['account'] = str_replace('-', '', $empinfo['bank_account']);
					$data[$nr]['income'] = round($row['net_income'],2);
					//$data[$nr]['code'] = $empinfo['bank_code'];
					$nr++;
				}
			}
		}
	}
	//var_dump($data); exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$allBorders = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '66666666'),
			  ),
		 ),
	);
	 
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->mergeCells('B1:D1');
	//$sheet->getStyle('B1')->getFont()->setSize(12)->setBold(true);
	
	$sheet->getColumnDimension('B')->setWidth(20);
	$sheet->getColumnDimension('C')->setWidth(35);
	$sheet->getColumnDimension('D')->setWidth(20);
	
	$sheet->getStyle('B2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('cccccc');
	$sheet->getStyle('B2:D2')->getFont()->setBold(true);
	
	$sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('B2:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('B1','Kasikorn Bank Payment list '.$_SESSION['rego']['curr_month'].'/'.$_SESSION['rego']['year_th'])->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B1')->getFont()->setSize(14)->setBold(true);
	
	$sheet->setCellValue('B2','Account Number');
	$sheet->setCellValue('C2','Payee Name');
	$sheet->setCellValue('D2','Amount ');
	
	$r=3;
	if($data){
		foreach($data as $k=>$v){
			$sheet->setCellValue('B'.$r, $v['account'])->getStyle('B'.$r)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			$sheet->setCellValue('C'.$r, $v['name']);
			$sheet->setCellValue('D'.$r, $v['income'])->getStyle('D'.$r)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
			$r++;
		}
	}
	$hRow = $sheet->getHighestRow();
	$sheet->getStyle('B3:D'.$hRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ccffcc');
	$sheet->getStyle('B2:D'.$hRow)->applyFromArray($allBorders);

	$filename = strtoupper($_SESSION['rego']['cid']).' Kasikorn Bank Payment list '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_th'];
	$sheet->setTitle('Kasikorn Bank');
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

