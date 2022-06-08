<?php

	if(session_id()==''){session_start(); ob_start();}
	//var_dump($_REQUEST);
	//var_dump($_FILES);
	//exit;
	include('../../dbconnect/db_connect.php');
	
	/*$dir = '../../uploads/';
	$dbname = 'language';//$_REQUEST['dbname'];

	if(!empty($_FILES)) {
		 $tempFile = $_FILES['file']['tmp_name'];
		 $targetFile =  $dir. $_FILES['file']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}*/
	$targetFile = DIR.'docs/REGO language file_13-01-2020.xlsx';
	
	$sheetData = array();
	$inputFileName = $targetFile; 
	
	require_once(DIR.'PhpSpreadsheet/vendor/autoload.php');
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, true);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs

	unset($sheetData[1]);
 
	//var_dump($field);
	//var_dump($sheetData); exit;
	
	foreach($sheetData as $k=>$v){
		$sql = "UPDATE rego_application_language SET th = '".$v['C']."' WHERE code = '".$v['A']."'";
		//var_dump($sql);
		/*if($res = $dba->query($sql)){
			echo 'Ok<br>';
		}else{
			var_dump($v['A'].' - '.mysqli_error($dba));
		}*/
	}
	
	//var_dump($data);
	//var_dump($err);
	exit;
