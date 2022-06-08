<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	
	$dir = DIR.$cid.'/payroll/';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	//var_dump($_REQUEST);	
	//var_dump($_FILES);	
	//exit;
	
	if (!empty($_FILES)){
		 $tempFile = $_FILES['file']['tmp_name'];
		 $targetFile =  $dir. $_FILES['file']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}
	$sheetData = array();
	$inputFileName = $targetFile; //'../excel_101/xhr0101_attendance_2016_01.xlsx'; //
	//var_dump($inputFileName);
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$reader->setReadDataOnly(true); 
	$reader->setReadEmptyCells(false);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs
	//var_dump($sheetData[3]);
	//exit;
	$fields = array();
	$fields[] = 'id';
	foreach($sheetData[2] as $k=>$v){
		$fields[$k] = $v;
	}
	unset($sheetData[1],$sheetData[2],$sheetData[3]);
	//var_dump($fields);
	//var_dump($sheetData[4]);
	
	function convertToHoursMins($tim) {
    if($tim < 0.00069444444444444){return;}
		$time = $tim * 24 * 60;
    $hours = floor($time / 60);
    $minutes = round($time,2) % 60;
    return sprintf('%02d:%02d', $hours, $minutes);
	}
	
	//var_dump($sheetData);// exit;

	$sql = "INSERT INTO ".$_SESSION['rego']['payroll_dbase']." (";
	foreach($fields as $v){
		$sql .= $v.',';
	}
	$sql = substr($sql,0,-1);
	$sql .= ") VALUES ("; 
	foreach($sheetData as $val){
		$sql .= "'".$dbc->real_escape_string($val['A'].$_SESSION['rego']['cur_month'])."',";
		foreach($val as $k=>$v){
			if($fields[$k] == 'ot1h' || $fields[$k] == 'ot15h' || $fields[$k] == 'ot2h' || $fields[$k] == 'ot3h' || $fields[$k] == 'absence' || $fields[$k] == 'leave_wop' || $fields[$k] == 'late_early'){
				if(is_numeric($v)){
					$v = (convertToHoursMins($v));
				}
			}
			$sql .= "'".$dbc->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1);
		$sql .= '),(';
	}
	$sql = substr($sql,0,-2);
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($fields as $v){
		$sql .= $v.' = VALUES('.$v.'),';
	}
	$sql = substr($sql,0,-1);
	//echo $sql; exit;
	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;	
?>