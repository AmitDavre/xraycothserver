<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');

	//var_dump($_REQUEST); //exit;
	
	// CHECK HERE IF THIS MONTH EXIST AND NOT LOCKED BEFORE SEND !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$sql = "SELECT paid FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' LIMIT 1";
	if($res = $dbc->query($sql)){
		if($res->num_rows == 0){
			ob_clean(); echo 'empty'; exit;
		}else{
			$row = $res->fetch_assoc();
			if($row['paid'] == 'Y'){ob_clean(); echo 'locked'; exit;}
		}
	}else{
		echo mysqli_error($dbc);
	}
	//exit;
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_prepayroll WHERE month = '".$cur_month."' AND status = 0 ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$xdata[$row['id']] = $row;
			if(getEmployeeWageType($row['id']) == 'day'){
				$data[$row['id']]['paid_days'] = $row['paid_days'];
			}
			$data[$row['id']]['ot1h'] = $row['ot1h'];
			$data[$row['id']]['ot15h'] = $row['ot15h'];
			$data[$row['id']]['ot2h'] = $row['ot2h'];
			$data[$row['id']]['ot3h'] = $row['ot3h'];
			$data[$row['id']]['absence'] = $row['absence'];
			$data[$row['id']]['late_early'] = $row['late_early_unpaid'];
			for($i=1;$i<=10;$i++){
				if($row['var_allow_'.$i] > 0){
					$data[$row['id']]['var_allow_'.$i] = $row['var_allow_'.$i];
				}
			}
		}
	}
	//var_dump($data); exit;
	
	foreach($data as $key=>$val){
		$sql = "UPDATE ".$_SESSION['rego']['payroll_dbase']." SET "; 
		foreach($val as $k=>$v){
			$sql .= $k." = '".round($v,2)."', ";
		}
		$sql = substr($sql,0,-2);
		$sql .= " WHERE id = '".$key."'";
		if(!$dbc->query($sql)){
			echo mysqli_error($dbc);
		}
	}
	ob_clean(); 
	echo 'success';
	exit;
	
	
	
