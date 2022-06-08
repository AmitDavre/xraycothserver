<?

	$clock[1] = '20-10-2020 21:55';
	$clock[] = '21-10-2020 06:08';
	$clock[] = '21-10-2020 21:51';
	$clock[] = '22-10-2020 06:07';
	$clock[] = '22-10-2020 21:46';
	$clock[] = '23-10-2020 06:18';
	$clock[] = '23-10-2020 21:59';
	$clock[] = '24-10-2020 06:03';
	$clock[] = '24-10-2020 21:53';
	var_dump($clock);

	$memory = $clock[1];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[1]));
	//var_dump($memory);
	$new = $clock[2];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[2];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[2]));
	//var_dump($memory);
	$new = $clock[3];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[3];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[3]));
	//var_dump($memory);
	$new = $clock[4];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[4];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[4]));
	//var_dump($memory);
	$new = $clock[4];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[5];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[5]));
	//var_dump($memory);
	$new = $clock[6];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[6];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[6]));
	//var_dump($memory);
	$new = $clock[7];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[7];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[7]));
	//var_dump($memory);
	$new = $clock[8];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	$memory = $clock[8];
	$scan[date('d-m-Y', strtotime($memory))] = date('H:i', strtotime($clock[8]));
	//var_dump($memory);
	$new = $clock[9];
	//var_dump(date('d-m-Y', strtotime($new)));

	if(date('d-m-Y', strtotime($new)) == date('d-m-Y', strtotime($memory))){
		$scan[date('d-m-Y', strtotime($new))] = date('H:i', strtotime($new));
	}else{
		$time1 = date('H:i', strtotime($memory));
		$time2 = date('H:i', strtotime($new));
		//var_dump($time1);
		//var_dump($time2);
		if($time2 < $time1){
			$scan[date('d-m-Y', strtotime($memory))] .= ' - '.date('H:i', strtotime($new));
		}
	}
	
	var_dump($scan);
	
	
	
	exit;
	
	$time1 = date('H:i', strtotime($clock[0]));
	$time2 = date('H:i', strtotime($clock[1]));
	
	var_dump($time1);
	var_dump($time2);
	
	if($time2 < $time1){
		$scan[0] .= ' - '.date('H:i', strtotime($clock[2]));
	}
	
	$memory = date('d-m-Y', strtotime($clock[1]));
	$scan[1] = date('H:i', strtotime($clock[1]));
	
	$time1 = date('H:i', strtotime($clock[1]));
	$time2 = date('H:i', strtotime($clock[2]));
	
	var_dump($memory);
	var_dump($time1);
	var_dump($time2);
	
	if($time2 < $time1){
		$scan[1] .= ' - '.date('H:i', strtotime($clock[2]));
	}else{
		$scan[1] .= ' - '.date('H:i', strtotime($clock[2]));
	}
	
	var_dump($scan);
	
	
	
	
	
	
	exit;
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../db_connect.php');
	//var_dump($_REQUEST); exit;
	
	//$_REQUEST['emp_id'] = 'DEMO-001';
	//$_REQUEST['date'] = '14-08-2020';
	//$_REQUEST['time'] = '12:14';
	
	$dir = DIR.$cid.'/time/selfies/';
  if(!file_exists($dir)){
   	mkdir($dir);
	}
	$img = $_REQUEST['image'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$fileData = base64_decode($img);
	//saving
	$fileName = date('d-m-Y_His').'.png';
	if(!file_put_contents($dir.$fileName, $fileData)){
		ob_clean();
		echo 'error';
	}
	
	$th_name = '';
	$en_name = '';
	$team = '';
	$sql = "SELECT th_name, en_name, shiftplan FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$th_name = $row['th_name'];
			$en_name = $row['en_name'];
			$team = $row['shiftplan'];
		}
	}
	
	$data = array();
	$time = substr($_REQUEST['time'],0,5);
	$all_scans = '';
	$id = $_REQUEST['emp_id'].'_'.strtotime($_REQUEST['date']);
	
	if($res = $dbc->query("SELECT id FROM ".$cid."_attendance WHERE id = '$id'")){
		if($res->num_rows == 0){
			//var_dump($res->num_rows);
			$date = date('Y-m-d', strtotime($_REQUEST['date']));
			$month = date('n', strtotime($date));
			$day = date('D', strtotime($date));
			$dnr = date('w', strtotime($date));
			$dbc->query("INSERT INTO ".$cid."_attendance (id, month, emp_id, th_name, en_name, date, day, dnr, shiftteam) VALUES ('".$id."', '".$month."', '".$_REQUEST['emp_id']."', '".$th_name."', '".$en_name."', '".$date."', '".$day."', '".$dnr."', '".$team."')");
		}
	}

	if($res = $dbc->query("SELECT * FROM ".$cid."_attendance WHERE id = '$id'")){
		if($row = $res->fetch_assoc()){
			$data[1] = $row['scan1'];
			$data[] = $row['scan2'];
			$data[] = $row['scan3'];
			$data[] = $row['scan4'];
			$data[] = $row['scan5'];
			$data[] = $row['scan6'];
			$data[] = $row['scan7'];
			$data[] = $row['scan8'];
			$data[] = $row['scan9'];
			$all_scans = $row['all_scans'];
		}
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	//var_dump($data); exit;
	
	foreach($data as $k=>$v){
		if(strlen($v) < 3){
			$data[$k] = $time;
			if(!empty($all_scans)){$all_scans .= '|';}
			$all_scans .= $time;
			$sql = "UPDATE ".$cid."_attendance SET scan".$k." =  '".$time."', all_scans = '".$all_scans."', img".$k." =  '".$dbc->real_escape_string($fileName)."', loc".$k." = '".$dbc->real_escape_string($_SESSION['scan']['scanloc'])."' WHERE id = '$id'";
			if($dbc->query($sql)){
				ob_clean();
				echo 'success';
			}else{
				ob_clean();
				echo mysqli_error($dbc);
			}
			break;
		}
	}






