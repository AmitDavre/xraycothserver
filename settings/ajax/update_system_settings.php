<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_sys_settings SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$dbc->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	//echo($sql); //exit;
	
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update company settings');
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	$periods = $_REQUEST['periods'];
	unset($_REQUEST['periods']);
	
	foreach($periods as $key=>$val){
		foreach($val as $k=>$v){
			$sql = "UPDATE ".$cid."_payroll_months SET ";
			$sql .= $key." = '".$dbc->real_escape_string($v)."',";
			$sql = substr($sql,0,-1)." WHERE month = '$k'";
			$dbc->query($sql);
			//echo '<br>'.$sql; //exit;
		}
	}
	//var_dump($periods); exit;
	
	$workdays = ($pr_settings['days_month'] == 0 ? 30 : $pr_settings['days_month']);
	$dayhours = ($pr_settings['hours_day'] == 0 ? 8 : $pr_settings['hours_day']);
	
	$emps = array();
	if($res = $dbc->query("SELECT * FROM ".$cid."_employees")){
		while($row = $res->fetch_assoc()){
			$wage = $row['base_salary'];
			foreach($_REQUEST['fix_allow'] as $k=>$v){
				if($v['rate'] == 'Y'){
					$wage += $row['fix_allow_'.$k];
				}
			}
			if(strtolower($row['wage_type']) == 'day'){
				$emps[$row['emp_id']]['day_rate'] = round($wage,2);
				$emps[$row['emp_id']]['hour_rate'] = round(($wage/$dayhours),2);
			}else{
				$emps[$row['emp_id']]['day_rate'] = round(($wage/$workdays),2);
				$emps[$row['emp_id']]['hour_rate'] = round(($wage/$workdays/$dayhours),2);
			}
		}
	}
	if($emps){ 
		foreach($emps as $k=>$v){
			$dbc->query("UPDATE ".$cid."_employees SET day_rate = '".$v['day_rate']."', hour_rate = '".$v['hour_rate']."' WHERE emp_id = '".$k."'");
		}
	}
	//var_dump($emps);
	
	$_REQUEST['entities'] = serialize($_REQUEST['entities']);
	$_REQUEST['branches'] = serialize($_REQUEST['branches']);
	$_REQUEST['groups'] = serialize($_REQUEST['groups']);
	$_REQUEST['departments'] = serialize($_REQUEST['departments']);
	$_REQUEST['teams'] = serialize($_REQUEST['teams']);
	$_REQUEST['positions'] = serialize($_REQUEST['positions']);
	
	$_REQUEST['fix_allow'] = serialize($_REQUEST['fix_allow']);
	$_REQUEST['var_allow'] = serialize($_REQUEST['var_allow']);
	
	$_REQUEST['fix_deduct'] = serialize($_REQUEST['fix_deduct']);
	$_REQUEST['var_deduct'] = serialize($_REQUEST['var_deduct']);

	if(isset($_REQUEST['payslip_field'])){
		$_REQUEST['payslip_field'] = serialize($_REQUEST['payslip_field']);
	}	
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE ".$cid."_settings SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$dbc->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	//echo($sql); //exit;
	
	if($dbc->query($sql)){
		ob_clean();
		echo 'success';
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update company settings');
	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
	
	exit;














