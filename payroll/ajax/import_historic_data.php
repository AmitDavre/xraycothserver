<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$dir = DIR.$cid.'/payroll/';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	if (!empty($_FILES)){
		 $tempFile = $_FILES['file']['tmp_name'];
		 $targetFile =  $dir. $_FILES['file']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}
	$sheetData = array();
	$inputFileName = $targetFile; //'../excel_101/xhr0101_attendance_2016_01.xlsx'; //
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$spreadsheet = $reader->load($inputFileName);
	$reader->setReadEmptyCells(false);
	$spreadsheet = $reader->load($inputFileName);

	$sheetData = $spreadsheet->getActiveSheet()->toArray(null, false, false, false);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs
	//var_dump($sheetData[3]);
	//exit;
	$fields = array();
	foreach($sheetData[1] as $v){
		$fields[] = $v;
	}
	unset($sheetData[0],$sheetData[1],$sheetData[2]);
	//var_dump($fields);
	//var_dump($sheetData); exit;
	
	foreach($sheetData as $key=>$val){
		foreach($val as $k=>$v){
			$data[$val[0].$val[2]]['id'] = $val[0].$val[2];
			$data[$val[0].$val[2]][$fields[$k]] = $v;
		}
	}
	
	foreach($data as $key=>$val){
		$fix_income = $val['salary'];
		$tot_ot = 0;
		$tot_fix_allow = 0;
		$tot_var_allow = 0;
		$tot_deduct = 0;
		$fix_deduct = 0;
		$var_deduct = 0;
		$tot_deductions = 0;
		for($i=1;$i<=10;$i++){
			if(isset($val['fix_allow_'.$i])){
				$fix_income += $val['fix_allow_'.$i];
				$tot_fix_allow += $val['fix_allow_'.$i];
			}
		}
		$var_income = 0;
		for($i=1;$i<=10;$i++){
			if(isset($val['var_allow_'.$i])){
				$var_income += $val['var_allow_'.$i];
				$tot_var_allow += $val['var_allow_'.$i];
			}
		}
		if(isset($val['ot1b'])){
			$var_income += $val['ot1b']; 
			$tot_ot += $val['ot1b'];
		}
		if(isset($val['ot15b'])){
			$var_income += $val['ot15b']; 
			$tot_ot += $val['ot15b'];
		}
		if(isset($val['ot2b'])){
			$var_income += $val['ot2b']; 
			$tot_ot += $val['ot2b'];
		}
		if(isset($val['ot3b'])){
			$var_income += $val['ot3b']; 
			$tot_ot += $val['ot3b'];
		}
		if(isset($val['ootb'])){
			$var_income += $val['ootb']; 
			$tot_ot += $val['ootb'];
		}
		if(isset($val['other_income'])){
			$var_income += $val['other_income']; 
		}
		
		/*if(isset($val['tot_deduct'])){
			$tot_deduct += $val['tot_deduct'];
			$tot_deductions += $val['tot_deduct'];
		}*/
		
		if(isset($val['fix_deduct_before'])){
			$fix_income -= $val['fix_deduct_before'];
		}
		
		if(isset($val['var_deduct_before'])){
			$var_income -= $val['var_deduct_before'];
		}
		
		if(isset($val['social'])){
			$tot_deductions += $val['social'];
		}
		if(isset($val['pvf_employee'])){
			$tot_deductions += $val['pvf_employee'];
		}
		if(isset($val['tax'])){
			$tot_deductions += $val['tax'];
		}
		$data[$key]['total_otb'] = $tot_ot;
		$data[$key]['tot_fix_income'] = $fix_income;
		$data[$key]['tot_var_income'] = $var_income;
		//$data[$key]['tot_deduct'] = $tot_deduct;
		$data[$key]['tot_deductions'] = $tot_deductions;
		$data[$key]['gross_income'] = $fix_income + $var_income;
		$data[$key]['net_income'] = $fix_income + $var_income - $tot_deductions;
		$data[$key]['total_fix_allow'] = $tot_fix_allow;
		$data[$key]['total_var_allow'] = $tot_var_allow;
		$data[$key]['total_tax_allow'] = $tot_fix_allow + $tot_var_allow;

	}			
	//var_dump($data); exit;
	//$dbc->query("TRUNCATE TABLE ".$cid."_historic_data");	
	reset($data);
	$sql = "INSERT INTO ".$cid.'_historic_data (';
	foreach($data[key($data)] as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1).')';
	$sql .= " VALUES (";
	reset($data);
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			$sql .= "'".$dbc->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1).'),(';
	}
	$sql = substr($sql,0,-2)." ON DUPLICATE KEY UPDATE ";
	reset($data);
	foreach($data[key($data)] as $k=>$v){
		$sql .= $k."=VALUES(".$k."),";
	}
	$sql = substr($sql,0,-1);
	//var_dump($sql); //exit;
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}	
	exit;	
?>