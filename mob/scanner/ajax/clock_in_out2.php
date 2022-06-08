<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); //exit;

	$th_name = '';
	$en_name = '';
	$team = '';
	$clock_in = '';
	$sql = "SELECT th_name, en_name, shiftplan, clock_in FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$th_name = $row['th_name'];
			$en_name = $row['en_name'];
			$team = $row['shiftplan'];
			$clock_in = $row['clock_in'];
		}
	}
	
	//$_REQUEST['date'] = '22-10-2020';	$_REQUEST['time'] = '21:55';
	//$_REQUEST['date'] = '23-10-2020';	$_REQUEST['time'] = '06:05'; //$clock_in = '22-10-2020 21:55';

	//var_dump($_REQUEST['clock']); //exit;
	//var_dump($clock_in); //exit;
	//var_dump($_REQUEST); //exit;
	
	if($_REQUEST['clock'] == 'out' && !empty($clock_in)){
		//if(date('d-m-Y', strtotime($_REQUEST['date'])) != date('d-m-Y', strtotime($clock_in))){
		if((strtotime($_REQUEST['date']) - strtotime(date('d-m-Y', strtotime($clock_in)))) == 86400){
			$time1 = date('H:i', strtotime($clock_in));
			$time2 = date('H:i', strtotime($_REQUEST['time']));
			if($time2 < $time1){
				$_REQUEST['date'] = date('d-m-Y', strtotime($clock_in));
			}
		}
	}
	//var_dump($_REQUEST); exit;
	
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
		echo mysqli_error($dbc);
	}
	
	foreach($data as $k=>$v){
		if(strlen($v) < 3){
			$data[$k] = $time;
			if(!empty($all_scans)){$all_scans .= '|';}
			$all_scans .= $time;
			$sql = "UPDATE ".$cid."_attendance SET scan".$k." =  '".$time."', all_scans = '".$all_scans."', loc".$k." = '".$dbc->real_escape_string($_REQUEST['location'])."' WHERE id = '$id'";
			if($dbc->query($sql)){
				$result['success'] = 'success';
				$result['id'] = $id;
				$result['img'] = 'img'.$k;
				if($_REQUEST['clock'] == 'in'){
					$dbc->query("UPDATE ".$cid."_employees SET clock_in = '".$dbc->real_escape_string($_REQUEST['date'].' '.$_REQUEST['time'])."' WHERE emp_id = '".$_REQUEST['emp_id']."'");
				}
				//var_dump($result); exit;
				ob_clean();
				echo json_encode($result);
				//echo 'img'.$k;
			}else{
				//ob_clean();
				echo mysqli_error($dbc);
			}
			break;
		}
	}
	
	
	
	//var_dump($all_scans); //exit;
	//var_dump($data); exit;
















