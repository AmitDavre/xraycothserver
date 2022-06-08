<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//$cid = $_SESSION['rego']['cid'];
	//var_dump($_REQUEST); exit;
	
	$res = $dbc->query("SELECT att_cols FROM ".$_SESSION['rego']['cid']."_sys_settings");
	$row = $res->fetch_assoc();
	$att_cols = unserialize($row['att_cols']);
	
	$var_allow = getUsedVarAllow($lang);
	$var_deduct = getUsedVarDeduct($lang);
	//var_dump($att_cols);
	
	$cols = array();
		$cols['ot1h'] = $lng['OT 1'];
		$cols['ot15h'] = $lng['OT 1.5'];
		$cols['ot2h'] = $lng['OT 2'];
		$cols['ot3h'] = $lng['OT 3'];
		$cols['ootb'] = $lng['Other OT'].' ('.$lng['THB'].')';
		
		$cols['absence'] = $lng['Absence'];
		$cols['late_early'] = $lng['Late Early'];
		$cols['leave_wop'] = $lng['Leave WOP'];
		
		foreach($var_allow as $k=>$v){
			$cols['var_allow_'.$k] = $v;
		}
		
		$cols['other_income'] = $lng['Other income'];
		$cols['severance'] = $lng['Severance'];
		$cols['remaining_salary'] = $lng['Remaining salary'];
		$cols['notice_payment'] = $lng['Notice payment'];
		$cols['paid_leave'] = $lng['Paid leave'];
		
		foreach($var_deduct as $k=>$v){
			$cols['var_deduct_'.$k] = $v;
		}
		
		$cols['advance'] = $lng['Advance'];
	
	//var_dump($cols);
	//var_dump($att_cols); exit;
	
	$n=0;
	$field = array();
		$field[getNameFromNumber($n)] = array('db'=>'emp_id','name'=>$lng['ID']); $n++;
		$field[getNameFromNumber($n)] = array('db'=>'emp_name_'.$_SESSION['rego']['lang'],'name'=>$lng['Employee name']); $n++;
		//$field[getNameFromNumber($n)] = array('db'=>'actual_days','name'=>$lng['Actual'].' '.$lng['days']); $n++;
		$field[getNameFromNumber($n)] = array('db'=>'paid_days','name'=>$lng['Days paid']); $n++;
		if($att_cols){
			foreach($att_cols as $k=>$v){
				$field[getNameFromNumber($n)] = array('db'=>$k,'name'=>$cols[$k]); $n++;
			}
		}
	end($field);
	$last_col = key($field);
	$msgCol = $last_col;
	if($msgCol < 'K'){$msgCol = 'K';}
	//var_dump($field); exit;
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	$filename = $cid.'_attendance_'.$_SESSION['rego']['cur_year'].'_'.sprintf('%02d',$_SESSION['rego']['cur_month']).'.xlsx';

	$allBorders = array(
		 'borders' => array(
			  'allBorders' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
			  ),
		 ),
	);
	
	$outBorders = array(
		 'borders' => array(
			  'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => array('argb' => '00000000'),
			  ),
		 ),
	);
	
	$sheet->mergeCells('A1:B1');
	$sheet->setCellValue('A1', $lng['Attendance'].' '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]);
	$sheet->getStyle('A1')->getFont()->setSize(11)->setBold(true);
	
	//$sheet->getRowDimension(1)->setCollapsed(true);
	$sheet->getRowDimension(2)->setVisible(false);
	$sheet->getStyle('A2:'.$last_col.'2')->getFont()->setBold(false)->getColor()->setRGB('ffffff');
	
	$commentRichText = $sheet->getComment('A3')->getText()->createTextRun($sheet_txt);
	$sheet->getComment('A3')->setWidth("580px")->setHeight("90px");	

	foreach($field as $k=>$v){
		$sheet->getStyle($k.'3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->setCellValue($k.'2', $v['db']);
		$sheet->setCellValue($k.'3', $v['name']);
		$sheet->getStyle($k.'3')->applyFromArray($allBorders);
		$sheet->getColumnDimension($k)->setWidth('12');
		if($v['db'] == 'emp_id' || $v['db'] == 'ot1h' || $v['db'] == 'ot15h' || $v['db'] == 'ot2h' || $v['db'] == 'ot3h' || $v['db'] == 'absence' || $v['db'] == 'leave_wop' || $v['db'] == 'late_early'){
			$sheet->getStyle($k)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			$sheet->getStyle($k)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}else{
			$sheet->getStyle($k)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
		}
	}
	$sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	$sheet->getStyle('A3:'.$last_col.'3')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setRGB('dae8f6');
		
	$sheet->getStyle('A3:'.$last_col.'3')->applyFromArray($allBorders);

	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".sprintf("%02d", $_SESSION['rego']['cur_month'])."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' ORDER BY emp_id ASC";
	$res = $dbc->query($sql);
	$r=4;
	while($row = $res->fetch_assoc()){
		foreach($field as $k=>$v){
			if($v['db'] == 'emp_id' || $v['db'] == 'ot1h' || $v['db'] == 'ot15h' || $v['db'] == 'ot2h' || $v['db'] == 'ot3h' || $v['db'] == 'absence' || $v['db'] == 'leave_wop' || $v['db'] == 'late_early'){
				$sheet->setCellValueExplicit($k.$r, $row[$v['db']], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
			}else{
				$sheet->setCellValue($k.$r, $row[$v['db']]);
			}
		}
		$r++;
	}
	//exit;
	
	$highestRow = $sheet->getHighestDataRow();
	$sheet->getProtection()->setSheet(true);
   	$sheet->getStyle('C4:'.$last_col.$highestRow)->getProtection()
		->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);	
	//$sheet->getStyle('C4:'.$last_col.$highestRow)->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');	
	$sheet->freezePane('C4');
	$sheet->setTitle($months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]);
	//$sheet->setActiveSheetIndex(0);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>