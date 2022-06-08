<?
	if(session_id()==''){session_start();}
	ob_start();
	//$cid = $_SESSION['rego']['cid'];
	include('../../dbconnect/db_connect.php');
//$_REQUEST['m'] = 'all';

//$_REQUEST['m'] = 'all';

	$existing_emps = array();
	$where = '';
	if($_REQUEST['m'] != 'all'){
		$where = ' WHERE month = '.$_REQUEST['m'];
	}
	$sql = "SELECT emp_id, month FROM ".$_SESSION['rego']['payroll_dbase']." ".$where." ORDER BY emp_id, month ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$existing_emps[$row['month']][$row['emp_id']] = $row['emp_id'];
		}
	}

	// echo '<pre>';
	// print_r($existing_emps);
	// echo '</pre>';

	// echo 'available_emps<br>';
	
	$available_emps = array();
	$sql = "SELECT emp_id, en_name, th_name FROM ".$cid."_employees WHERE emp_status = '1' AND emp_type != '4' AND emp_type != '5' ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$available_emps[$row['emp_id']] = $row[$lang.'_name'];
		}
	}

	// echo '<pre>';
	// print_r($available_emps);
	// echo '</pre>'; //exit;
	
	$missing_emps = array();
	if($existing_emps){
		foreach($existing_emps as $key=>$val){
			foreach($available_emps as $k=>$v){
				if(!isset($val[$k])){
					//$missing_month[$key][$v] = $v;
					$missing_emps[$k] = $v;
				}
			}
		}
	}else{
		//$missing_emps = $available_emps;
	}

	// echo 'missing aray<br>';

	// echo '<pre>';
	// print_r($missing_emps);
	// echo '</pre>'; exit;
	ksort($missing_emps);
	//var_dump($available_emps); //exit;
	//var_dump($existing_emps); //exit;
	//var_dump($missing_emps); exit;
	
	ob_clean();
	echo json_encode($missing_emps);
	exit;
	
?>