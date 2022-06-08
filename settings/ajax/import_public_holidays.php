<?php
	if(session_id()==''){session_start();}
	ob_start();
	//var_dump($_REQUEST);
	//var_dump($_FILES);
	//exit;
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
		
	
	
	$dir = '../../'.$cid.'/uploads/';
	if (!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}

	if(!empty($_FILES)) {
		 $tempFile = $_FILES['file']['tmp_name'];
		 $targetFile =  $dir. $_FILES['file']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}

	
	$sheetData = array();
	$inputFileName = $targetFile; 
	
	require_once DIR.'PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$reader->setReadDataOnly(true); 
	$reader->setReadEmptyCells(false);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, false);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs
	//var_dump($sheetData[1]); // database field names ///////////////////////////
	//var_dump($sheetData[2]); // excel file real headers ////////////////////////
	//exit;

	$type = $sheetData[0][0]; 

	$field = $sheetData[1];
	$field = array_filter($field);
	unset($sheetData[0]);


	foreach ($sheetData as $key => $value) 
	{
		// insert into public holidays table 

		if($value['0']) // year
		{
			$year = $value['0'];
		}
		else
		{
			$year = '';
		}

		if($value['1']) // date
		{
			$date = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value['1']));
		}	
		else
		{
			 $date  = '';
		}	

		if($value['2']) //th
		{
			$thalang = $value['2'];
		}	
		else
		{
			$thalang = '';
		}	

		if($value['3']) //en
		{
			$englang = $value['3'];
		}	
		else
		{
			$englang = '';
		}	

		if($value['4']) //cdate
		{
			$cdate = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value['4']));
		}
		else
		{
			$cdate = '';
		}


		$sql = "SELECT * FROM ".$cid."_holidays WHERE date = '".$date."'";
		if($res = $dbc->query($sql))
		{
			if($row = $res->fetch_assoc())
			{
			}
			else
			{
				$sql2 = "INSERT INTO ".$cid."_holidays (year, date,th,en,cdate) VALUES ";
				$sql2 .= "('".$dbc->real_escape_string($year)."', ";
				$sql2 .= "'".$dbc->real_escape_string($date)."', ";
				$sql2 .= "'".$dbc->real_escape_string($thalang)."', ";
				$sql2 .= "'".$dbc->real_escape_string($englang)."', ";
				$sql2 .= "'".$dbc->real_escape_string($cdate)."')";

				$res2 = $dbc->query($sql2);


			}
		}


		


	}

	echo 'success';
	exit;


?>
















