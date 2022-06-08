<?

	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include('../functions.php');
	//var_dump($_FILES); exit;

	$period = $_SESSION['rego']['curr_month'].'/'.$_SESSION['rego']['cur_year'];
	$time_settings = getTimeSettings();
	//$fixed_break = $time_settings['fixed_break'];
	$scans = $time_settings['scans'];
	$employees = getEmployeesBySID($cid);
	
	//var_dump($scans); exit;

	/*$dir = DIR.$cid.'/uploads/';
	$filename = '?';
	if(!empty($_FILES)) {
		if(strpos($_FILES['timesheet']['type'], 'ms-excel') == false && strpos($_FILES['timesheet']['type'], 'spreadsheetml') == false){
			ob_clean();
			echo 'wrong';
			exit;
		}
		$tempFile = $_FILES['timesheet']['tmp_name'];
		$filename = $_FILES['timesheet']['name'];
		$inputFileName =  $dir.$_FILES['timesheet']['name'];
		move_uploaded_file($tempFile,$inputFileName);
	}*/
	$inputFileName = '../../rego01006/rego01006_time_registration.xlsx';
	//$inputFileName = '../timesheet/september.xlsx';

	$sheetData = array();
	//$inputFileName = $targetFile; 

	require_once '../../PhpSpreadsheet/vendor/autoload.php';
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

	//var_dump($sheetData[1]);
	unset($sheetData[0]);
	//exit;
	echo '<pre>';
	//var_dump($sheetData[2]); exit;

	$data = array();
	foreach($sheetData as $k=>$v){
		//$data[$v[0]]['id'] = $v[0];
		$data[$v[0]]['shiftteam'] = $v[2];
		
		$data[$v[0]]['scan1'] = $v[5];
		$data[$v[0]]['img1'] = $v[6];
		$data[$v[0]]['loc1'] = $v[7];
		
		$data[$v[0]]['scan2'] = $v[8];
		$data[$v[0]]['img2'] = $v[9];
		$data[$v[0]]['loc2'] = $v[10];
		
		$data[$v[0]]['scan3'] = $v[11];
		$data[$v[0]]['img3'] = $v[12];
		$data[$v[0]]['loc3'] = $v[13];
		
		$data[$v[0]]['scan4'] = $v[14];
		$data[$v[0]]['img4'] = $v[15];
		$data[$v[0]]['loc4'] = $v[16];
		
		$data[$v[0]]['scan5'] = $v[17];
		$data[$v[0]]['img5'] = $v[18];
		$data[$v[0]]['loc5'] = $v[19];
		
		$data[$v[0]]['scan6'] = $v[20];
		$data[$v[0]]['img6'] = $v[21];
		$data[$v[0]]['loc6'] = $v[22];
		
		$data[$v[0]]['scan7'] = $v[23];
		$data[$v[0]]['img7'] = $v[24];
		$data[$v[0]]['loc7'] = $v[25];
		
		$data[$v[0]]['scan8'] = $v[26];
		$data[$v[0]]['img8'] = $v[27];
		$data[$v[0]]['loc8'] = $v[28];
		
		$data[$v[0]]['scan9'] = $v[29];
		$data[$v[0]]['img9'] = $v[30];
		$data[$v[0]]['loc9'] = $v[31];

	}
	//var_dump($data); //exit;
	
	foreach($data as $key=>$val){
		$sql = "UPDATE ".$cid."_attendance SET ";
		foreach($val as $k=>$v){
			$sql .= $k."='".$v."',";
		}
		$sql = substr($sql,0, -1)." WHERE id = '".$key."'";
		var_dump($sql);
		if($res = $dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
	}
	exit;
	












