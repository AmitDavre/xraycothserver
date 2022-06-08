<?

	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['xhr']['cid'];
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include('../functions.php');
	//var_dump($_FILES); exit;
	
	$time_settings = getTimeSettings();
	//$fixed_break = $time_settings['fixed_break'];
	$scans = $time_settings['scans'];
	$employees = getEmployeesBySID($cid);
	
	//var_dump($scans); exit;
	
	$dir = DIR.$cid.'/uploads/';
	$filename = '?';
	if(!empty($_FILES)) {
		 $tempFile = $_FILES['timesheet']['tmp_name'];
		 $filename = $_FILES['timesheet']['name'];
		 $inputFileName =  $dir.$_FILES['timesheet']['name'];
		 move_uploaded_file($tempFile,$inputFileName);
	}
	$inputFileName = '../timesheet/scan/attendance-07-2020.xls';
	
	$sheetData = array();
	//$inputFileName = $targetFile; 
	
	require_once '../../PhpSpreadsheet/vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\IOFactory;
	
	$inputFileType = IOFactory::identify($inputFileName);
	$reader = IOFactory::createReader($inputFileType);
	$spreadsheet = $reader->load($inputFileName);
	
	$sheetData = $spreadsheet->getActiveSheet()->toArray('', false, false, false);
	//$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
	// 1. Value returned in the array entry if a cell doesn't exist
	// 2. Should formulas be calculated ?
	// 3. Should formatting be applied to cell values ?
	// 4. False - Return a simple array of rows and columns indexed by number counting from zero 
	// 4. True - Return rows and columns indexed by their actual row and column IDs

	//var_dump($sheetData);
	var_dump($sheetData[1]);
	unset($sheetData[0]);
	//exit;
	
	$data = array();
	foreach($sheetData as $k=>$v){
		
		$data[$v[6]]['id'] = $employees[$v[6]]['emp_id'];;
		$data[$v[6]]['name'] = $employees[$v[6]][$lang.'_name'];
		$in = explode(' ', $v[0]);
		$out[1] = '';
		if(!empty($v[1]) && $v[1] != '-'){
			$out = explode(' ', $v[1]);
		}
		$din = date('d-m-Y', strtotime(str_replace('/','-',$in[0])));
		//$dout = date('d-m-Y', strtotime(str_replace('/','-',$out[0])));
		
		
		
		//var_dump($tmp[0]);
		//var_dump($d);
		//$t = date('H:i', strtotime($v[0]));
		if(!isset($data[$v[6]]['time'][$din])){
			$data[$v[6]]['time'][$din] = $in[1];
			$data[$v[6]]['time'][$din] .= '|'.$out[1];
		}/*else{
			if(!contains($t, $data[$v[6]]['time'][$din])){
				$data[$v[6]]['time'][$din] .= '|'.$t;
			}
		}*/
		
		//$data[$v[6]][$k]['in'] = $v[0];
		//$data[$v[6]][$k]['out'] = $v[1];
	}
	var_dump($data); exit;
	
	
	
	/*$period = substr($sheetData[0][0], 0, strpos($sheetData[0][0], " ")); //$sheetData[0][0];
	$tmp = explode(':', strtok($period, "/"));
	$year = $tmp[0];
	$mnth = sprintf('%02d', $tmp[1]);
	$period = $mnth.'/'.$year;
	$date = '-'.$mnth.'-'.$year;*/
	
	//var_dump($mnth); //exit;
	//var_dump($year); //exit;
	//var_dump($period); //exit;
	//var_dump($date); //exit;
	
	
	$counter = count($sheetData);
	unset($sheetData[0]);
	//sort($sheetData);
	$nr=1;
	for($i=1;$i<=$counter;$i+=4){
		$tmp = explode('  ',trim($sheetData[$i][0]));
		//var_dump($tmp);
		$data[$nr]['id'] = substr($tmp[0], strpos($tmp[0], ":") + 1);
		$data[$nr]['name'] = substr($tmp[1], strpos($tmp[1], ":") + 1);
		//$data[$nr]['department'] = substr($tmp[2], strpos($tmp[2], ":") + 1);
		//$data[$nr]['shift'] = substr($tmp[3], strpos($tmp[3], ":") + 1);
		//$data[$nr]['period'] = $period;
		foreach($sheetData[$i+2] as $k=>$v){
			if(!empty($v)){
				$data[$nr]['time'][sprintf('%02d', ($k+1)).$date] = str_replace(' ','|',trim($v));
			}
		}
		$nr++;
	}
	
	var_dump($data); exit;
	//var_dump($sheetData); exit;
	
	
	$sql = "INSERT INTO ".$cid."_scanfiles (date, period, content, filename, import, status) VALUES ";
		$sql .= "('".$dbc->real_escape_string(date('Y-m-d'))."', ";
		$sql .= "'".$dbc->real_escape_string($period)."', ";
		$sql .= "'".$dbc->real_escape_string(serialize($data))."', ";
		$sql .= "'".$dbc->real_escape_string($filename)."', ";
		$sql .= "'".$dbc->real_escape_string(1)."', ";
		$sql .= "'".$dbc->real_escape_string(0)."')";

	/*$sql .= " ON DUPLICATE KEY UPDATE 
		emp_id = VALUES(emp_id), 
		date = VALUES(date), 
		time = VALUES(time)";*/
	//echo $sql;
	//exit;
	ob_clean();
	if(!$res = $dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	exit;
	
	
	
	
	
	$array = explode("\n", file_get_contents($targetFile));
	//var_dump($filename);
	//var_dump($array[0]);
	
	$prescan = array();
	foreach($array as $k=>$v){
		if(!empty($v)){
			$key = preg_replace('~[[:cntrl:]]~', '', $v);
			$dat = substr($v, 11, 8);
			$date = substr($dat, 0, 2).'-'.substr($dat, 2, 2).'-'.substr($dat, 4, 4);
			$xdate = str_replace('/','-',$date);
			$date = date('Y-m-d', strtotime($xdate));
			$id = substr($v, 8, 3);
			$tim = substr($v, 19, 4);
			$time = substr($tim, 0, 2).':'.substr($tim, 2, 2);
			$prescan[$key]['emp_id'] = $id;
			$prescan[$key]['date'] = $date;
			$prescan[$key]['time'] = $time;
			//var_dump($dat);
		}
	}
	//var_dump($prescan); exit;
	
	$sql = "INSERT INTO ".$cid."_scanfiles (id, emp_id, date, time, filename) VALUES ";
	foreach($prescan as $k=>$v){
		$sql .= "('".$dbc->real_escape_string($k)."', ";
		$sql .= "'".$dbc->real_escape_string($v['emp_id'])."', ";
		$sql .= "'".$dbc->real_escape_string($v['date'])."', ";
		$sql .= "'".$dbc->real_escape_string($v['time'])."', ";
		$sql .= "'".$dbc->real_escape_string($filename)."'),";
	}
	$sql = substr($sql,0,-1);
	$sql .= " ON DUPLICATE KEY UPDATE 
		emp_id = VALUES(emp_id), 
		date = VALUES(date), 
		time = VALUES(time)";
	//echo $sql;
	//exit;
	if(!$res = $dbc->query($sql)){
		echo mysqli_error($dbc);
	}
	
	$scan = array();
	$sql = "SELECT * FROM ".$cid."_scanfiles WHERE status = 0";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$date = strtotime($row['date']);
			$id = $row['emp_id'];
			$scan[$date][$id][] = $row['time'];
		}
	}else{
		echo mysqli_error($dbc);
	}
	
	//var_dump($scan); 
	//exit;
	
	$scan = array();
	foreach($array as $k=>$v){
		if(!empty($v)){
			$dat = substr($v, 11, 8);
			$date = substr($dat, 0, 2).'-'.substr($dat, 2, 2).'-'.substr($dat, 4, 4);
			$xdate = str_replace('/','-',$date);
			$date = date('Y-m-d', strtotime($xdate));
			$id = substr($v, 8, 3);
			$tim = substr($v, 19, 4);
			$time = substr($tim, 0, 2).':'.substr($tim, 2, 2);
			$scan[$date][$id][] = $time;
			//var_dump($dat);
		}
	}
	//var_dump($scan); 
	//exit;
	//$employees = getEmployeeNameSid($cid);
	//var_dump($employees); //exit;
	
	/*function getShiftPlan($id, $month, $day){
		global $cid;
		global $dbc;
		$data = array();
		$sql = "SELECT D".$day.", emp_id, en_name, th_name FROM ".$cid."_monthly_shiftplans_".$_SESSION['xhr']['cur_year']." WHERE sid = '".$id."' AND month = '".$month."'";
		//var_dump($sql);
		if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$data['emp_id'] = $row['emp_id'];
				$data['en_name'] = $row['en_name'];
				$data['th_name'] = $row['th_name'];
				$data['plan'] = $row['D'.$day];
			}else{
				return 'not found';
			}
		}
		return $data;
	}*/
	
	$data = array();
	//var_dump($scan); exit;
	/*foreach($scan as $key=>$val){
		foreach($val as $k=>$v){
			$tmp = getShiftPlan($k, date('n', strtotime($key)),  date('j', strtotime($key)));
			//$splan = $tmp['plan'];
			var_dump($tmp);
		}
	}
	exit;*/
	$sdate = '';
	foreach($scan as $key=>$val){
		foreach($val as $k=>$v){
			$sdate = date('Y-m-d', strtotime($key));
			$tmp = getShiftPlan($k, date('n', strtotime($key)),  date('j', strtotime($key)));
			//var_dump($tmp);
			if($tmp != 'not found'){
				$splan = $tmp['plan'];
				//$scan[$key][$k]['plan'] = $tmp;
				//$splan = $employees[$k]['splan'];//'DWW';
				//var_dump($splan);
				//var_dump(strtotime($key)); //exit;
				$hours = xgetShiftHours($cid, $splan, strtotime($key));
				//var_dump($hours);
				$f1 = $hours['f1'];
				$u1 = $hours['u1'];
				$f2 = $hours['f2'];
				$u2 = $hours['u2'];
				$hrs = $hours['hours'];
				$next = $hours['end'];
				//$sdate = date('Y-m-d', strtotime($key));
				$merge = 0;
				if($next){$merge = 1;}
				$uid = $tmp['emp_id'].'_'.strtotime($sdate);
				$data[$sdate][$uid]['id'] = $uid;
				$data[$sdate][$uid]['emp_id'] = $tmp['emp_id'];
				$data[$sdate][$uid]['en_name'] = $tmp['en_name'];
				$data[$sdate][$uid]['th_name'] = $tmp['th_name'];
				$data[$sdate][$uid]['date'] = $sdate;
				$data[$sdate][$uid]['day'] = date('D',strtotime($date));
				$data[$sdate][$uid]['dnr'] = date('w', strtotime($date));
				$data[$sdate][$uid]['shift_plan'] = $hours['code'];
				$data[$sdate][$uid]['plan_ot'] = '0';//$plan_ot;
				$data[$sdate][$uid]['hours'] = $hrs;
				$data[$sdate][$uid]['break'] = $hours['break'];
				$data[$sdate][$uid]['f1'] = $f1;
				$data[$sdate][$uid]['u1'] = $u1;
				$data[$sdate][$uid]['f2'] = $f2;
				$data[$sdate][$uid]['u2'] = $u2;
			}else{
				$merge = 0;
				$uid = $k.'_'.strtotime($sdate);
				$data[$sdate][$uid]['id'] = $uid;
				$data[$sdate][$uid]['emp_id'] = $k;
				$data[$sdate][$uid]['en_name'] = '-';
				$data[$sdate][$uid]['th_name'] = '-';
				$data[$sdate][$uid]['date'] = $sdate;
				$data[$sdate][$uid]['day'] = date('D',strtotime($date));
				$data[$sdate][$uid]['dnr'] = date('w', strtotime($date));
				$data[$sdate][$uid]['shift_plan'] = '-';
				$data[$sdate][$uid]['plan_ot'] = '-';//$plan_ot;
				$data[$sdate][$uid]['hours'] = '-';
				$data[$sdate][$uid]['break'] = '-';
				$data[$sdate][$uid]['f1'] = '-';
				$data[$sdate][$uid]['u1'] = '-';
				$data[$sdate][$uid]['f2'] = '-';
				$data[$sdate][$uid]['u2'] = '-';
			}
				
			//var_dump($sdate);
			
			$ok = 1;
			//var_dump($v);
			//var_dump($scans);
			//var_dump(count($v));
			if(count($v) != $scans){$ok = 0;}
			//var_dump($ok);
			$sc = '';
			for($i=0; $i<=8; $i++){
				if(isset($v[$i])){
					$data[$sdate][$uid]['scan'.($i+1)] = $v[$i];
					$sc .= $v[$i].' - ';
				}else{
					$data[$sdate][$uid]['scan'.($i+1)] = '';
				}
			}
			$data[$sdate][$uid]['all_scans'] = substr($sc,0,-3);
			
			
/*			if($scans == 4){
				
				if(isset($v[0])){
					$data[$sdate][$uid]['scan1'] = $v[0];
					if(strtotime($f1) < strtotime($v[0])){$ok = 0;}
				}else{
					$data[$sdate][$uid]['scan1'] = '-';
					$ok = 0;
				}
				if(isset($v[1])){
					$data[$sdate][$uid]['scan2'] = $v[1];
					if(strtotime($u1) > strtotime($v[1])){$ok = 0;}
				}else{
					$data[$sdate][$uid]['scan2'] = '-';
					$ok = 0;
				}
				if(isset($v[2])){
					$data[$sdate][$uid]['scan3'] = $v[2];
					if(strtotime($f2) < strtotime($v[2])){$ok = 0;}
				}else{
					$data[$sdate][$uid]['scan3'] = '-';
					$ok = 0;
				}
				if(isset($v[3])){
					$data[$sdate][$uid]['scan4'] = $v[3];
					if(strtotime($u2) > strtotime($v[3])){$ok = 0;}
				}else{
					$data[$sdate][$uid]['scan4'] = '-';
					$ok = 0;
				}
				//var_dump($ok);
				/*if(isset($v[4])){
					$data[$sdate][$uid]['scan5'] = $v[4];
					$ok = 0;
				}else{
					$data[$sdate][$uid]['scan5'] = '-';
				}
				//var_dump($ok);
				if(isset($v[4])){$data[$sdate][$uid]['scan5'] = $v[4];}else{$data[$sdate][$uid]['scan5'] = '-';}
				if(isset($v[5])){$data[$sdate][$uid]['scan6'] = $v[5];}else{$data[$sdate][$uid]['scan6'] = '-';}
				if(isset($v[6])){$data[$sdate][$uid]['scan7'] = $v[6];}else{$data[$sdate][$uid]['scan7'] = '-';}
				if(isset($v[7])){$data[$sdate][$uid]['scan8'] = $v[7];}else{$data[$sdate][$uid]['scan8'] = '-';}
				if(isset($v[8])){$data[$sdate][$uid]['scan9'] = $v[8];}else{$data[$sdate][$uid]['scan9'] = '-';}
			}else{
				if(isset($v[0])){
					$data[$sdate][$uid]['scan1'] = $v[0];
					if(strtotime($f1) < strtotime($v[0])){$ok = 0;};
				}else{
					$data[$sdate][$uid]['scan1'] = '-';
					$ok = 0;
				}
				if(isset($v[1])){
					$data[$sdate][$uid]['scan2'] = $v[1];
					if(strtotime($u2) > strtotime($v[1])){$ok = 0;};
				}else{
					$data[$sdate][$uid]['scan2'] = '-';
					$ok = 0;
				}
				if(isset($v[2])){
					$data[$sdate][$uid]['scan3'] = $v[2];
					$ok = 0;
				}else{
					$data[$sdate][$uid]['scan3'] = '-';
				}
				if(isset($v[3])){$data[$sdate][$uid]['scan6'] = $v[5];}else{$data[$sdate][$uid]['scan4'] = '-';}
				if(isset($v[4])){$data[$sdate][$uid]['scan6'] = $v[5];}else{$data[$sdate][$uid]['scan5'] = '-';}
				if(isset($v[5])){$data[$sdate][$uid]['scan6'] = $v[5];}else{$data[$sdate][$uid]['scan6'] = '-';}
				if(isset($v[6])){$data[$sdate][$uid]['scan7'] = $v[6];}else{$data[$sdate][$uid]['scan7'] = '-';}
				if(isset($v[7])){$data[$sdate][$uid]['scan8'] = $v[7];}else{$data[$sdate][$uid]['scan8'] = '-';}
				if(isset($v[8])){$data[$sdate][$uid]['scan9'] = $v[8];}else{$data[$sdate][$uid]['scan9'] = '-';}
			}
*/			
			$data[$sdate][$uid]['status'] = $ok;
			$data[$sdate][$uid]['merge'] = $merge;
		}
	}
	ksort($data[key($data)]);
	//var_dump($scan); 
	//var_dump($data); 
	//exit;
	
	//$dbc->query("TRUNCATE TABLE `".$dbname."`");
	
	$dbname = $cid."_timescan_".$_SESSION['xhr']['cur_year'];
	$sql = "INSERT INTO `".$dbname."` (id, emp_id, en_name, th_name, date, day, dnr, plan, plan_ot, plan_rh, plan_break, f1, u1, f2, u2, scan1, scan2, scan3, scan4, scan5, scan6, scan7, scan8, scan9, all_scans, status, merge) VALUES (";
		
	foreach($data as $key=>$val){
		foreach($val as $kk=>$vv){
			foreach($vv as $k=>$v){
				$sql .= "'".$dbc->real_escape_string($v)."', ";
			}
			$sql = substr($sql,0,-2);
			$sql .= '),(';
		}
	}
	$sql = substr($sql,0,-2);
	$sql .= " ON DUPLICATE KEY UPDATE 
		scan1 = VALUES(scan1),
		scan2 = VALUES(scan2),
		scan3 = VALUES(scan3),
		scan4 = VALUES(scan4),
		scan5 = VALUES(scan5),
		scan6 = VALUES(scan6),
		scan7 = VALUES(scan7),
		scan8 = VALUES(scan8),
		scan9 = VALUES(scan9),
		all_scans = VALUES(all_scans),
		status = VALUES(status)";
	//echo $sql;
	//exit;
	
	if($res = $dbc->query($sql)){
		ob_clean();
		echo 'ok';
		exit;
		
		/*$data = array();	
		$sql = "SELECT * FROM `".$dbname."` WHERE merge = 1";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_assoc()){
				for($i=1;$i<=9;$i++){
					if($row['scan'.$i] != '-'){$data[$row['id']]['scan'][$i] = $row['scan'.$i];}
				}
				$data[$row['id']]['id'] = $row['emp_id'];
				$data[$row['id']]['date'] = $row['date'];
			}
		}
		//var_dump($data); //exit;	
		foreach($data as $k=>$v){
			$tmp = strtotime($v['date'].'+1 days');
			$id = $v['id'].'_'.$tmp;
			//var_dump($id);
			if(isset($data[$id])){
				foreach($data[$id]['scan'] as $v){
					$data[$k]['scan'][] = $v;
				}
				unset($data[$id]);
			}
		
		}*/	
		//var_dump($data); 
	
	}else{
		ob_clean();
		echo '<div class="msg_error" style="margin-bottom:10px">Error: '.mysqli_error($dbc).'</div>';
	}	
	exit;
		
