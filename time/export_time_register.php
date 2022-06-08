<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	
	var_dump($cid);
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_attendance ORDER BY emp_id ASC, date ASC";	
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$xdata[$row['id']]['date'] = $row;
			$data[$row['id']]['id'] = $row['id'];
			$data[$row['id']]['emp_id'] = $row['emp_id'];
			$data[$row['id']]['team'] = $row['shiftteam'];
			//$data[$row['id']]['en_name'] = $row['en_name'];
			$data[$row['id']]['day'] = $row['day'];
			$data[$row['id']]['date'] = $row['date'];
			for($i=1;$i<=9;$i++){
				$data[$row['id']]['scan'.$i] = $row['scan'.$i];
				$data[$row['id']]['img'.$i] = $row['img'.$i];
				$data[$row['id']]['loc'.$i] = $row['loc'.$i];
			}
		}
	}
	var_dump($data);
	//exit;
	
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
	
	reset($data);
	$r=1; $c=0;
	foreach($data[key($data)] as $k=>$v){
		$sheet->setCellValue(getNameFromNumber($c).$r, $k); 
		$sheet->getColumnDimension(getNameFromNumber($c))->setAutoSize(true);
		$c++;
	}
	
	//$sheet->getDefaultStyle()->getFont()->setSize(11);
	//$sheet->getColumnDimension('A')->setWidth(25);
	//$sheet->getColumnDimension('B')->setWidth(15);
	//$sheet->getColumnDimension('B')->setAutoSize(true);
	//$sheet->getStyle('A1:'.$lastCol.'1')->getFont()->setBold(true);
	//$sheet->getStyle('A1:'.$lastCol.'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	//$sheet->getStyle('A1:'.$lastCol.'1')->getFill()->getStartColor()->setRGB('fde9d9');
	
	$r=2; $c=0;
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			$sheet->setCellValue(getNameFromNumber($c).$r, $v); $c++;
		}
		$r++; $c=0;
	}

	//var_dump($xdata); exit;
	$sheet->getStyle('A1:AD1')->getFont()->setBold(true);
	$sheet->getStyle('A1:AD1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
	$sheet->getStyle('A1:AD1')->getFill()->getStartColor()->setRGB('eeeeee');
	
	//$highestRow = $sheet->getHighestDataRow();
	
	//$sheet->getStyle('C2:'.$lastCol.$highestRow)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');	
	
	$sheet->freezePane('A2');
	$sheet->setTitle($cid);
	
	ob_end_clean();
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$cid.'_time_registration.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');


?>