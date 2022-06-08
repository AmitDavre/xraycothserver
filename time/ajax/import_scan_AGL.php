<?

	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'time/functions.php');
	//var_dump($_FILES); //exit;
	
	$time_settings = getTimeSettings();
	//$fixed_break = $time_settings['fixed_break'];
	$scans = $time_settings['scans'];
	//var_dump($scans); exit;
	//$period = date('m/Y');
	$period = $_SESSION['rego']['curr_month'].'/'.$_SESSION['rego']['cur_year'];
	
	
	$dir = DIR.$cid.'/time/';
	if(!file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
	$filename = '?';
	if(!empty($_FILES)) {
		 $tempFile = $_FILES['timesheet']['tmp_name'];
		 $filename = $_FILES['timesheet']['name'];
		 $targetFile =  $dir.$_FILES['timesheet']['name'];
		 move_uploaded_file($tempFile,$targetFile);
	}
	//$targetFile =  '../timesheet/AGL_002.TXT';
	
	$scan = array();
	$delimiter = "\t";
	$fp = fopen($targetFile, 'r');
	while(!feof($fp)){
		 $line = fgets($fp, 2048);
		 if(!empty($line)){
		 	$scan[] = str_getcsv($line, $delimiter);
		}
	}
	fclose($fp);
	unset($scan[0]);	
	//var_dump($scan[2]); //exit;                              
	$employees = getEmployeesBySID($cid);
	
	$prescan = array();
	foreach($scan as $k=>$v){
		
		//$xprescan[$v[2]][date('d-m-Y', strtotime($v[6]))][] = date('H:i:s', strtotime($v[6]));
		
if($v[2] <= 10){ // TEMPORARY /////////////////////////////////////////////////////

		$prescan[$v[2]]['id'] = $employees[$v[2]]['emp_id'];;
		$prescan[$v[2]]['name'] = $employees[$v[2]][$lang.'_name'];
		$d = date('d-m-Y', strtotime($v[6]));
		$t = date('H:i', strtotime($v[6]));
		if(!isset($prescan[$v[2]]['time'][$d])){
			$prescan[$v[2]]['time'][$d] = $t;
		}else{
			if(!contains($t, $prescan[$v[2]]['time'][$d])){
				$prescan[$v[2]]['time'][$d] .= '|'.$t;
			}
		}
}
	}
	ksort($prescan);
	//var_dump($xprescan[2]); //exit;
	//var_dump($prescan); exit;
	$scan_system = $_POST['scan_system'];
	
	
	$sql = "INSERT INTO ".$cid."_scanfiles (date, period, content, filename, import, status,scansystem) VALUES ";
		$sql .= "('".$dbc->real_escape_string(date('Y-m-d'))."', ";
		$sql .= "'".$dbc->real_escape_string($period)."', ";
		$sql .= "'".$dbc->real_escape_string(serialize($prescan))."', ";
		$sql .= "'".$dbc->real_escape_string($filename)."', ";
		$sql .= "'".$dbc->real_escape_string(1)."', ";
		$sql .= "'".$dbc->real_escape_string(0)."', ";
		$sql .= "'".$dbc->real_escape_string($scan_system)."')";
	
	ob_clean();
	if($res = $dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
