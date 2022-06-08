<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;
	
	$_REQUEST['emp_id'] = 'DEMO-001';
	$_REQUEST['date'] = '16-12-2020';
	$_REQUEST['time'] = '12:14';
	$fileName = date('d-m-Y_His').'.png';
	
	$th_name = '';
	$en_name = '';
	$sql = "SELECT th_name, en_name FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$th_name = $row['th_name'];
			$en_name = $row['en_name'];
		}
	}
	//var_dump($employee); exit;
	
	
	/*$dir = DIR.$cid.'/time/selfies/';
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
		echo 'error';
	}*/
	
	$data = array();
	$time = substr($_REQUEST['time'],0,5);
	$all_scans = '';
	$id = $_REQUEST['emp_id'].'_'.strtotime($_REQUEST['date']);
	
	if($res = $dbc->query("SELECT id FROM ".$cid."_attendance WHERE id = '$id'")){
		if($res->num_rows == 0){
			var_dump($res->num_rows);
			$date = date('Y-m-d', strtotime($_REQUEST['date']));
			$month = date('n', strtotime($date));
			$day = date('D', strtotime($date));
			$dnr = date('w', strtotime($date));
			$dbc->query("INSERT INTO ".$cid."_attendance (id, month, emp_id, th_name, en_name, date, day, dnr) VALUES ('".$id."', '".$month."', '".$_REQUEST['emp_id']."', '".$th_name."', '".$en_name."', '".$date."', '".$day."', '".$dnr."')");
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
		}else{
			echo 'not exist';
		}
	}else{
		echo mysqli_error($dbc);
	}
	
	
	//var_dump($data); exit;
	
	foreach($data as $k=>$v){
		if(strlen($v) < 3){
			$data[$k] = $time;
			if(!empty($all_scans)){$all_scans .= '|';}
			$all_scans .= $time;
			$sql = "UPDATE ".$cid."_attendance SET scan".$k." =  '".$time."', all_scans = '".$all_scans."', img".$k." =  '".$fileName."' WHERE id = '$id'";
			//$sql = "INSERT INTO ".$cid."_attendance_2020 (scan".$k.", all_scans, img".$k.") VALUES ('".$time."', '".$all_scans."', '".$fileName."') ON DUPLICATE KEY UPDATE scan".$k." = VALUES(scan".$k."), all_scans = VALUES(all_scans), img".$k." = VALUES(img".$k.")";
			//echo $sql.'<br>';
			if($dbc->query($sql)){
				echo 'success';
			}else{
				echo mysqli_error($dbc);
			}
			break;
		}
	}






