<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$bank_codes = unserialize($rego_settings['bank_codes']);
	//var_dump($bank_codes); exit;

	$nr = 1;
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '014', $_GET['bank']);
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.$empinfo['en_name'];}
					$data[$nr]['name'] = $name;
					$data[$nr]['account'] = str_replace('-', '', $empinfo['bank_account']);
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
	//$spreadsheet->getDefaultStyle()->getFont()->setName('Cordia New');
	$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

	$sheet = $spreadsheet->getActiveSheet();
	//$set_newdefaultrowheight=$sheet->getDefaultRowDimension()->setRowHeight(22);
	
	$sheet->mergeCells('B1:F1');
	$sheet->mergeCells('B2:F2');
	$sheet->mergeCells('B3:F3');
	$sheet->getStyle('B1')->getFont()->setSize(13);
	$sheet->getStyle('B2')->getFont()->setSize(16);//->setBold(true);
	
	$sheet->getColumnDimension('A')->setWidth(8);
	$sheet->getColumnDimension('B')->setWidth(8);
	$sheet->getColumnDimension('C')->setWidth(35);
	$sheet->getColumnDimension('D')->setWidth(20);
	$sheet->getColumnDimension('E')->setWidth(20);
	$sheet->getColumnDimension('F')->setWidth(40);
	$sheet->getColumnDimension('G')->setWidth(8);
	
	$sheet->getStyle('B4:F4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('99ccff');
	$sheet->getStyle('A1:A200')->applyFromArray($noBorders);
	$sheet->getStyle('G1:G200')->applyFromArray($noBorders);
	$sheet->getStyle('A1:F3')->applyFromArray($noBorders);
	$sheet->getStyle('B4:F4')->applyFromArray($header);
	
	$sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	//$sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle('B4:F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B1:F200')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
	//$sheet->getStyle('E')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	
	$sheet->setCellValue('B1','SCB PAYMENT LIST')->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('B2','ข้อมูลการจ่ายเงินเดือนพนักงาน ')->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	//$sheet->getStyle('B2')->getFont()->setSize(18)->setBold(true);
	
	$sheet->setCellValue("B4","ลำดับ \rNo.")->getStyle('B4')->getAlignment()->setWrapText(true);
	$sheet->setCellValue("C4","ชื่อผู้รับเงิน \r\n(Account Name)")->getStyle('C4')->getAlignment()->setWrapText(true);
	$sheet->setCellValue("D4","เลขที่บัญชี \r(Account No.)")->getStyle('D4')->getAlignment()->setWrapText(true);
	$sheet->setCellValue("E4","จำนวนเงิน \r (Amount)")->getStyle('E4')->getAlignment()->setWrapText(true);
	$sheet->setCellValue("F4","ธนาคาร \r(Bank Name)")->getStyle('F4')->getAlignment()->setWrapText(true);
	
	$r=5;
	if($data){
		foreach($data as $k=>$v){
			$sheet->setCellValue('B'.$r, $r-4);
			$sheet->setCellValue('C'.$r, $v['name']);
			$sheet->setCellValue('D'.$r, $v['account'])->getStyle('D'.$r)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			$sheet->setCellValue('E'.$r, $v['income'])->getStyle('E'.$r)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
			$sheet->setCellValue('F'.$r, $v['code'].': '.$bank_codes[$v['code']][$lang]);
			$r++;
		}
	}
	
	$filename = strtoupper($_SESSION['rego']['cid']).' SCB Payment List '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_th'];
	$sheet->setTitle('SCB BANK');
	
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