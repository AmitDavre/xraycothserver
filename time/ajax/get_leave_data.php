<?

	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); //exit;
	
	//$sdate = date('Y-m-d', strtotime($_REQUEST['sdate']));//'2018-01-01';
	//$edate = date('Y-m-d', strtotime($_REQUEST['edate']));//'2018-01-31';
	//$edate = '2018-01-31';
	$leave_period = getTimePeriod();
	$start = date('Y-m-d', strtotime($leave_period['start']));
	$end = date('Y-m-d', strtotime($leave_period['end']));
	
	
	$leaves = array();
	$sql = "SELECT * FROM ".$cid."_leaves_data WHERE (date BETWEEN '$start' AND '$end') AND status = 'TA'"; // and status = taken ???
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			/*if(isset($leaves[$row['emp_id'].'_'.strtotime($row['date'])])){
				$leaves[$row['emp_id'].'_'.strtotime($row['date'])] += array($row['leave_type']=>$row['days']);
			}else{
				$leaves[$row['emp_id'].'_'.strtotime($row['date'])] = array($row['leave_type']=>$row['days']);
			}*/
			$leaves[$row['emp_id'].'_'.strtotime($row['date'])][$row['leave_type']] = array('days'=>$row['days'], 'day'=>$row['day'], 'hours'=>$row['hours'], 'paid'=>$row['paid']);
		}
	}
	//var_dump($leaves); exit;
	
	if($leaves){
		foreach($leaves as $key=>$val){
			$sum = 0;
			$day = array();
			$paid = array();
			foreach($val as $k=>$v){
				$sum += $v['days'];
				$type = implode('|',array_keys($val));
				
				if(strpos($v['day'], ':') !== false){
					//var_dump($v['hours']);
					$day[] = $v['hours'];
					$paid[] = $v['paid'];
				}else{
					$day[] = $v['day'];
					$paid[] = $v['paid'];
				}
			}	
			//var_dump($sum);
			//var_dump($type);
			//var_dump($day);
			//var_dump($paid);
			$sql = "UPDATE `".$cid."_attendance` SET 
				`leave_type` = '".$dbc->real_escape_string($type)."',  
				`leave_days` = '".$dbc->real_escape_string($sum)."',  
				`leave_day` = '".$dbc->real_escape_string(serialize($day))."',  
				`leave_paid` = '".$dbc->real_escape_string(serialize($paid))."'   
				WHERE `id` = '".$key."'";
			$dbc->query($sql);
			//echo $sql; //exit;
			echo 'success';
		}
	}else{
		echo 'empty';
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

