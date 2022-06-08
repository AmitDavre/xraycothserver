<?php
	
	if(session_id()==''){session_start(); ob_start();}
	include('../../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include(DIR.'files/functions.php');

	$data = array();
	$sql = "SELECT emp_id, emp_name_".$lang.", net_income FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$empinfo = getEmployees($cid, $_SESSION['rego']['gov_entity']);
			//var_dump($empinfo); exit;
			if($empinfo){
				if($empinfo[$row['emp_id']]['pay_type'] == 'cash'){
					$data[$row['emp_id']]['name'] = $row['emp_name_'.$lang];
					$data[$row['emp_id']]['net'] = $row['net_income'];
				}
			}
		}
	}
	foreach($data as $k=>$v){
		$totals['total'] += round($v['net'],2);
		$tmp = explode('.', round($v['net'],2));
		if(empty($tmp[0])){$tmp[1] = 0;}
		if(empty($tmp[1])){$tmp[1] = 0;}
		$amt = $tmp[0];
		$total = floor($amt/1000);
		$data[$k]['1000'] = $total;
		$amt -= $total*1000;
		$total = floor($amt/500);
		$data[$k]['500'] = $total;
		$amt -= $total*500;
		$total = floor($amt/100);
		$data[$k]['100'] = $total;
		$amt -= $total*100;
		$total = floor($amt/50);
		$data[$k]['50'] = $total;
		$amt -= $total*50;
		$total = floor($amt/20);
		$data[$k]['20'] = $total;
		$amt -= $total*20;
		$total = floor($amt/10);
		$data[$k]['10'] = $total;
		$amt -= $total*10;
		$total = floor($amt/5);
		$data[$k]['5'] = $total;
		$amt -= $total*5;
		$total = floor($amt);
		$data[$k]['1'] = $total;
		$amt = $tmp[1]; // decimals
		if($amt == 5){
			$data[$k]['050'] = 1;
			$totals['050'] += 1;
			$amt = 0;
			$total = 0;
		}else{
			$total = floor($amt/50);
			$data[$k]['050'] = $total;
			$totals['050'] += $total;
		}
		$amt -= $total*50;
		$total = floor($amt/25);
		$data[$k]['025'] = $total;
		$totals['025'] += $total;
		$amt -= $total*25;
		$total = floor($amt);
		$data[$k]['001'] = $total;
		$totals['001'] += $total;
	}
	//var_dump($data); 
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
	
	$filename = $lng['Cash payment list'].' : '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang];
	$sheet->setCellValue('A1', $compinfo[$lang.'_compname'].' - '.$filename);
	
	$sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$sheet->setCellValue('A2',$lng['Emp. ID']);
	$sheet->setCellValue('B2',$lng['Employee']);
	$sheet->setCellValue('C2',$lng['Net pay'])->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->setCellValue('D2','1000');
	$sheet->setCellValue('E2','500');
	$sheet->setCellValue('F2','100');
	$sheet->setCellValue('G2','50');
	$sheet->setCellValue('H2','20');
	$sheet->setCellValue('I2','10');
	$sheet->setCellValue('J2','5');
	$sheet->setCellValue('K2','1');
	$sheet->setCellValue('L2','0.50');
	$sheet->setCellValue('M2','0.25');
	$sheet->setCellValue('N2','0.01');
	
	
	$sheet->mergeCells('A1:N1');
	$sheet->getStyle('A1')->getFont()->setSize(16);
	$sheet->getStyle('A1:N1')->getFont()->setBold(true);
	$sheet->getStyle('A2:N2')->getFont()->setBold(true);
	$sheet->getStyle('A2:N2')->applyFromArray($allBorders);
	$sheet->getStyle('A2:N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');
	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setAutoSize(true);
	$sheet->getColumnDimension('C')->setAutoSize(true);
	$sheet->getStyle('C')->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
		
	$r=3;
	foreach($data as $k=>$v){
		$sheet->setCellValue('A'.$r, $k);
		$sheet->setCellValue('B'.$r, $v['name']);
		$sheet->setCellValue('C'.$r, round($v['net'],2));
		$sheet->setCellValue('D'.$r, $v['1000']);
		$sheet->setCellValue('E'.$r, $v['500']);
		$sheet->setCellValue('F'.$r, $v['100']);
		$sheet->setCellValue('G'.$r, $v['50']);
		$sheet->setCellValue('H'.$r, $v['20']);
		$sheet->setCellValue('I'.$r, $v['10']);
		$sheet->setCellValue('J'.$r, $v['5']);
		$sheet->setCellValue('K'.$r, $v['1']);
		$sheet->setCellValue('L'.$r, $v['050']);
		$sheet->setCellValue('M'.$r, $v['025']);
		$sheet->setCellValue('N'.$r, $v['001']);
		$r++;
	}
	
	$sheet->setCellValue('A'.($r+1), $r-3);
	$sheet->setCellValue('B'.($r+1), $lng['Grand total']);
	$sheet->setCellValue('C'.($r+1), '=SUM(C3:C'.$r.')');
	$sheet->setCellValue('D'.($r+1), '=SUM(D3:D'.$r.')');
	$sheet->setCellValue('E'.($r+1), '=SUM(E3:E'.$r.')');
	$sheet->setCellValue('F'.($r+1), '=SUM(F3:F'.$r.')');
	$sheet->setCellValue('G'.($r+1), '=SUM(G3:G'.$r.')');
	$sheet->setCellValue('H'.($r+1), '=SUM(H3:H'.$r.')');
	$sheet->setCellValue('I'.($r+1), '=SUM(I3:I'.$r.')');
	$sheet->setCellValue('J'.($r+1), '=SUM(J3:J'.$r.')');
	$sheet->setCellValue('K'.($r+1), '=SUM(K3:K'.$r.')');
	$sheet->setCellValue('L'.($r+1), '=SUM(L3:L'.$r.')');
	$sheet->setCellValue('M'.($r+1), '=SUM(M3:M'.$r.')');
	$sheet->setCellValue('N'.($r+1), '=SUM(N3:N'.$r.')');
	$sheet->getStyle('A'.($r+1).':N'.($r+1))->getFont()->setBold(true);
	$sheet->getStyle('A'.($r+1).':N'.($r+1))->applyFromArray($allBorders);
	$sheet->getStyle('A'.($r+1).':N'.($r+1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('dae8f6');

	$sheet->setCellValue('C'.($r+2), '=SUM(D'.($r+2).':N'.($r+2).')');
	$sheet->setCellValue('D'.($r+2), '=SUM(D2*D'.($r+1).')')->getStyle('D'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('E'.($r+2), '=SUM(E2*E'.($r+1).')')->getStyle('E'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('F'.($r+2), '=SUM(F2*F'.($r+1).')')->getStyle('F'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('G'.($r+2), '=SUM(G2*G'.($r+1).')')->getStyle('G'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('H'.($r+2), '=SUM(H2*H'.($r+1).')')->getStyle('H'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('I'.($r+2), '=SUM(I2*I'.($r+1).')')->getStyle('I'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('J'.($r+2), '=SUM(J2*J'.($r+1).')')->getStyle('J'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('K'.($r+2), '=SUM(K2*K'.($r+1).')')->getStyle('K'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('L'.($r+2), '=SUM(L2*L'.($r+1).')')->getStyle('L'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('M'.($r+2), '=SUM(M2*M'.($r+1).')')->getStyle('M'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->setCellValue('N'.($r+2), '=SUM(N2*N'.($r+1).')')->getStyle('N'.($r+2))->getNumberFormat()->setFormatCode('[<>0]#,##0.00; [=0]"-  "');
	$sheet->getStyle('C'.($r+2).':N'.($r+2))->getFont()->setSize(9);

	$sheet->freezePane('A3','A3');
	$sheet->setTitle($months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]);
	
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');

?>