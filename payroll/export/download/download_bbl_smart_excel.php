<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$nr = 1;
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', $_GET['bank']);
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.$empinfo['en_name'];}
					$data[$nr]['name'] = $name;
					$data[$nr]['account'] = $empinfo['bank_account'];
					$data[$nr]['branch'] = $empinfo['bank_branch'];
					$data[$nr]['code'] = $empinfo['bank_code'];
					$data[$nr]['income'] = round($row['net_income'],2);
					$nr++;
				}
			}
		}
	}
	//var_dump($data); exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$noBorders = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00ffffff'),
			  ),
		 ),
	);
	$header = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00cccccc'),
			  ),
		 ),
		'font'  => array(
			'bold'  => true,
		)
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
	$spreadsheet->getDefaultStyle()->getFont()->setName('Cordia New');
	$spreadsheet->getDefaultStyle()->getFont()->setSize(14);

	$sheet = $spreadsheet->getActiveSheet();
	$set_newdefaultrowheight=$sheet->getDefaultRowDimension()->setRowHeight(22);
	
	$sheet->mergeCells('B1:G1');
	$sheet->mergeCells('B2:G2');
	$sheet->mergeCells('B3:G3');
	$sheet->getStyle('B2')->getFont()->setName('AngsanaUPC')->setSize(18)->setBold(true);
	
	$sheet->getColumnDimension('A')->setWidth(8);
	$sheet->getColumnDimension('B')->setWidth(8);
	$sheet->getColumnDimension('C')->setWidth(35);
	$sheet->getColumnDimension('D')->setWidth(20);
	$sheet->getColumnDimension('E')->setWidth(15);
	$sheet->getColumnDimension('F')->setWidth(15);
	$sheet->getColumnDimension('G')->setWidth(20);
	$sheet->getColumnDimension('H')->setWidth(8);
	
	$sheet->getStyle('B4:G4'.$r)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('99ccff');
	$sheet->getStyle('A1:A200')->applyFromArray($noBorders);
	$sheet->getStyle('H1:H200')->applyFromArray($noBorders);
	$sheet->getStyle('A1:H3')->applyFromArray($noBorders);
	$sheet->getStyle('B4:G4')->applyFromArray($header);
	
	$sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	$sheet->getStyle('B1:G200')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	//$sheet->getStyle('E')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	
	$sheet->setCellValue('B1','BANGKOK BANK PAYROLL LIST')->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('B2','ข้อมูลการโอนเงินรายย่อยระหว่างธนาคาร (SMART)')->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B2')->getFont()->setSize(18)->setBold(true);
	
	$sheet->setCellValue('B4','ลำดับที่ ');
	$sheet->setCellValue('C4','ชื่อพนักงาน');
	$sheet->setCellValue('D4','จำนวนเงิน  ');
	$sheet->setCellValue('E4','รหัสธนาคาร');
	$sheet->setCellValue('F4','รหัสสาขา');
	$sheet->setCellValue('G4','เลขที่บัญชี');
	
	$r=5;
	foreach($data as $k=>$v){
		$sheet->setCellValue('B'.$r, $r-4);
		$sheet->setCellValue('C'.$r, $v['name']);
		$sheet->setCellValue('D'.$r, $v['income'])->getStyle('D'.$r)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
		$sheet->setCellValue('E'.$r, $v['code'])->getStyle('E'.$r)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		$sheet->setCellValue('F'.$r, $v['branch'])->getStyle('F'.$r)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		$sheet->setCellValue('G'.$r, $v['account'])->getStyle('G'.$r)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
		$r++;
	}
	
	$filename = strtoupper($_SESSION['rego']['cid']).' BBL Smart '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_th'];
	$sheet->setTitle('BBL Smart');
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

	
	/*$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = $_SESSION['rego']['cid'].'_'.$banks[$compinfo['bank_name']][$lang].'_'.$lng['paymentlist'].'_'.$_SESSION['rego']['cur_month'].'_'.$_SESSION['rego']['cur_year'].'.pdf';
	$doc = $banks[$compinfo['bank_name']][$lang].' - '.$lng['Payment list wages'];

	$mpdf->Output($dir.$filename,'F');
	$mpdf->Output($filename,'I');
	
	include('save_to_documents.php');*/







?>