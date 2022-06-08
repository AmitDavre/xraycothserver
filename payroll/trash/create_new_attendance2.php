<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	//include(DIR.'files/functions.php');
	//include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$som = date($_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-01');
	$eom = date($_SESSION['rego']['cur_year'].'-'.$_SESSION['rego']['curr_month'].'-t');
	
	$sql = "SELECT * FROM ".$_SESSION['rego']['emp_dbase']." WHERE 
		pr_status = '0' 
		AND startdate < '".$eom."' 
		AND (resign_date >= '".$som."' AND resign_date <= '".$eom."' OR emp_status = '1') 
		ORDER by resign_date DESC, emp_id ASC ";
		//LIMIT ".$_SESSION['rego']['max'];
		
	//echo $sql; //exit;
	/*if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			var_dump($row['emp_id'].' - '.$row['startdate']); //exit;
		}
	}else{
		var_dump(mysqli_error($dbc));
	}
	exit;*/

	if($res = $dbc->query($sql)){
		
		$sql = "INSERT INTO ".$_SESSION['rego']['payroll_dbase']." 
		(id, emp_id, month, bank, account, emp_name_th, emp_name_en, position, basic_salary, paid_days, ";
		for($i=1;$i<=10;$i++){
			$sql .= "fix_allow_".$i.",";
		}
		for($i=1;$i<=5;$i++){
			$sql .= "fix_deduct_".$i.",";
		}
		$sql .= "remaining_salary, notice_payment, paid_leave, severance, legal_deductions, other_income, paid) VALUES ";
		$counter = 1;
		while($row = $res->fetch_assoc()){
			$workdays = ($pr_settings['days_month'] == 0 ? 30 : $pr_settings['days_month']);
			$tmp = getEmployeeWorkedDays($row['startdate'], $row['resign_date'], $workdays);
			if(!$tmp['started'] && !$tmp['resigned']){
				$calendar_days = $tmp['calendar_days'];
			}else{
				$calendar_days = $tmp['worked_days'];
			}
			
			//var_dump($row['emp_id'].' - '.$row['startdate']); //exit;
			$legal_deductions = $row['gov_house_banking'] + $row['savings'] + $row['legal_execution'] + $row['kor_yor_sor'];
			$sql .= "(
			'".$dbc->real_escape_string($row['emp_id'].$_SESSION['rego']['cur_month'])."',
			'".$dbc->real_escape_string($row['emp_id'])."',
			'".$dbc->real_escape_string($_SESSION['rego']['cur_month'])."',
			'".$dbc->real_escape_string($row['bank_name'])."',
			'".$dbc->real_escape_string($row['bank_account'])."',
			'".$dbc->real_escape_string($row['firstname'].' '.$row['lastname'])."',
			'".$dbc->real_escape_string($row['en_name'])."',
			'".$dbc->real_escape_string($row['position'])."',
			'".$dbc->real_escape_string($row['base_salary'])."',
			'".$dbc->real_escape_string($calendar_days)."',
			'".$dbc->real_escape_string($row['fix_allow_1'])."',
			'".$dbc->real_escape_string($row['fix_allow_2'])."',
			'".$dbc->real_escape_string($row['fix_allow_3'])."',
			'".$dbc->real_escape_string($row['fix_allow_4'])."',
			'".$dbc->real_escape_string($row['fix_allow_5'])."',
			'".$dbc->real_escape_string($row['fix_allow_6'])."',
			'".$dbc->real_escape_string($row['fix_allow_7'])."',
			'".$dbc->real_escape_string($row['fix_allow_8'])."',
			'".$dbc->real_escape_string($row['fix_allow_9'])."',
			'".$dbc->real_escape_string($row['fix_allow_10'])."',
			'".$dbc->real_escape_string($row['fix_deduct_1'])."',
			'".$dbc->real_escape_string($row['fix_deduct_2'])."',
			'".$dbc->real_escape_string($row['fix_deduct_3'])."',
			'".$dbc->real_escape_string($row['fix_deduct_4'])."',
			'".$dbc->real_escape_string($row['fix_deduct_5'])."',
			'".$dbc->real_escape_string($row['remaining_salary'])."',
			'".$dbc->real_escape_string($row['notice_payment'])."',
			'".$dbc->real_escape_string($row['paid_leave'])."',
			'".$dbc->real_escape_string($row['severance'])."',
			'".$dbc->real_escape_string($legal_deductions)."',
			'".$dbc->real_escape_string($row['other_income'])."',
			'".$dbc->real_escape_string('C')."'),";
			if($row['resign_date'] < $som){
				$counter ++;
			}
			if($counter > $_SESSION['rego']['max']){break;}
			
		}
		$sql = substr($sql,0,-1);
		//echo $sql;
		//exit;
		ob_clean();
		if($dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
	}
?>