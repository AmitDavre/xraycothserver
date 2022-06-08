<?php

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//var_dump($_REQUEST); exit;
	//var_dump($_FILES); //exit;
		
	// create table to save annual leaves if not exists 


	$teamsValue = $_REQUEST['teams'];
	$teamsNameValue = $_REQUEST['team_name'];


	$teamValArray =  array(

		'teams' => $teamsValue,
		'team_name' => $teamsNameValue,

	);


	$nextYearLeaveForwardBalance = $_REQUEST['nextyearleavebalance'];

	unset($_REQUEST['nextyearleavebalance']);
	// add logic here to only save the current year  when user selects yes or not 
	$allextrafields = array(

		'leave_forward_next_year' =>  $nextYearLeaveForwardBalance,
		'current_year' => $cur_year,
	);




	$serializeTeam = serialize($teamValArray);
	$serializeALLArray = serialize($allextrafields);

	$_REQUEST['attach4'] = $serializeTeam;
	$_REQUEST['attach8'] = $serializeALLArray;



	if(empty($_REQUEST['emp_id'])){echo 'empty'; exit;}
	
	$_REQUEST['emp_id'] = str_replace(' ', '', $_REQUEST['emp_id']);
	$_SESSION['rego']['empID'] = $_REQUEST['emp_id'];
	
	//$_REQUEST['personal_email'] = strtolower($_REQUEST['personal_email']);
	//$_REQUEST['work_email'] = strtolower($_REQUEST['work_email']);
	
	if(isset($_REQUEST['firstname'])){$_REQUEST['th_name'] = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];}
	
	if(isset($_REQUEST['idcard_nr'])){$_REQUEST['idcard_nr'] = str_replace('-','',$_REQUEST['idcard_nr']);}
	
	if(isset($_REQUEST['joining_date'])){
		if(empty($_REQUEST['joining_date'])){
			unset($_REQUEST['joining_date']);
		}else{
			$_REQUEST['joining_date'] = date('Y-m-d', strtotime($_REQUEST['joining_date']));
		}
	}
	if(isset($_REQUEST['resign_date'])){
		if(empty($_REQUEST['resign_date'])){
			unset($_REQUEST['resign_date']);
		}else{
			$_REQUEST['resign_date'] = date('Y-m-d', strtotime($_REQUEST['resign_date']));
		}
	}
	$_REQUEST['emergency_contacts'] = serialize($_REQUEST['emergency_contacts']);
	$_REQUEST['hospitals'] = serialize($_REQUEST['hospitals']);
	
	/*$history = array();
	$olddata = array();
	$sql = "SELECT joining_date, probation_date, branch, department, team, emp_group, emp_type, resign_date, resign_reason, emp_status, account_code, position, head_branch, head_division, head_department, line_manager, team_supervisor, date_position, shift_team, time_reg, selfie, annual_leave, leave_approve FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['emp_id']."'";
	if($res = $dbc->query($sql)){
		$olddata = $res->fetch_assoc();
	}else{
		echo mysqli_error($dbc);
	}
	foreach($olddata as $k=>$v){
		if(isset($_REQUEST[$k]) && $_REQUEST[$k] != $v){
			$history[] = array('field'=>$k, 'prev'=>$v, 'new'=>$_REQUEST[$k], 'user'=>$_SESSION['rego']['name']);
		}
	}
	var_dump($history); //exit;
	var_dump($olddata); exit;*/
	
	/*if(!empty($_REQUEST['resign_date'])){
		$_REQUEST['resign_date'] = date('Y-m-d', strtotime($_REQUEST['resign_date']));
	}else{
		$_REQUEST['resign_date'] = '';
	}*/
	
	//var_dump($_REQUEST); exit;
	
	$uploadmap = '../../'.$cid.'/employees/';
  if(!file_exists($uploadmap)){
   	mkdir($uploadmap);
	}
	if(!empty($_FILES['att_idcard']['tmp_name'])){
		$ext = pathinfo($_FILES['att_idcard']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_idcard.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_idcard']['tmp_name'],$filename)){
			$_REQUEST['att_idcard'] = $file;
		}
	}
	if(!empty($_FILES['att_housebook']['tmp_name'])){
		$ext = pathinfo($_FILES['att_housebook']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_housebook.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_housebook']['tmp_name'],$filename)){
			$_REQUEST['att_housebook'] = $file;
		}
	}
	if(!empty($_FILES['attach1']['tmp_name'])){
		$ext = pathinfo($_FILES['attach1']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach1']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach1']['tmp_name'],$filename)){
			$_REQUEST['attach1'] = $file;
		}
	}
	if(!empty($_FILES['attach2']['tmp_name'])){
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach2']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach2']['tmp_name'],$filename)){
			$_REQUEST['attach2'] = $file;
		}
	}
	if(!empty($_FILES['attach3']['tmp_name'])){
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach3']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach3']['tmp_name'],$filename)){
			$_REQUEST['attach3'] = $file;
		}
	}
	// if(!empty($_FILES['attach4']['tmp_name'])){
	// 	$file = $_REQUEST['emp_id'].'_'.$_FILES['attach4']['name'];		
	// 	$filename = $uploadmap.$file;
	// 	if(move_uploaded_file($_FILES['attach4']['tmp_name'],$filename)){
	// 		$_REQUEST['attach4'] = $file;
	// 	}
	// }
	
	if(!empty($_FILES['att_bankbook']['tmp_name'])){
		$ext = pathinfo($_FILES['att_bankbook']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_bankbook.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_bankbook']['tmp_name'],$filename)){
			$_REQUEST['att_bankbook'] = $file;
		}
	}
	if(!empty($_FILES['att_contract']['tmp_name'])){
		$ext = pathinfo($_FILES['att_contract']['name'], PATHINFO_EXTENSION);		
		$file = $_REQUEST['emp_id'].'_contract.'.$ext;
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['att_contract']['tmp_name'],$filename)){
			$_REQUEST['att_contract'] = $file;
		}
	}
	if(!empty($_FILES['attach5']['tmp_name'])){
		$ext = pathinfo($_FILES['attach5']['name'], PATHINFO_EXTENSION);
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach5']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach5']['tmp_name'],$filename)){
			$_REQUEST['attach5'] = $file;
		}
	}
	if(!empty($_FILES['attach6']['tmp_name'])){
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach6']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach6']['tmp_name'],$filename)){
			$_REQUEST['attach6'] = $file;
		}
	}
	if(!empty($_FILES['attach7']['tmp_name'])){
		$file = $_REQUEST['emp_id'].'_'.$_FILES['attach7']['name'];		
		$filename = $uploadmap.$file;
		if(move_uploaded_file($_FILES['attach7']['tmp_name'],$filename)){
			$_REQUEST['attach7'] = $file;
		}
	}
	// if(!empty($_FILES['attach8']['tmp_name'])){
	// 	$file = $_REQUEST['emp_id'].'_'.$_FILES['attach8']['name'];		
	// 	$filename = $uploadmap.$file;
	// 	if(move_uploaded_file($_FILES['attach8']['tmp_name'],$filename)){
	// 		$_REQUEST['attach8'] = $file;
	// 	}
	// }



	if(!empty($_REQUEST['leave_approve']))
	{
		$leave_approveimpl= implode(',', $_REQUEST['leave_approve']);
		$_REQUEST['leave_approve'] = $leave_approveimpl;
	}


	if($_REQUEST['emp_group'] == ''){
		$_REQUEST['emp_group'] = 's';
	}


	// echo  '<pre>';
	// print_r($_REQUEST);
	// echo  '</pre>';

	// die();
	
	$employeeIdValue = $_REQUEST['emp_id'];

	$sql = "INSERT INTO ".$cid."_employees (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.', ';
	}
	$sql = substr($sql,0,-2);
	$sql .= ") VALUES ("; 
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".mysqli_real_escape_string($dbc,$v)."', ";
	}
	$sql = substr($sql,0,-2).')';
	unset($_REQUEST['emp_id']);
	$sql .= " ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k."=VALUES(".$k."),";
	}
	$sql = substr($sql,0,-1);
	
	var_dump($sql);
	
	
	if($dbc->query($sql)){
		//updateEmployeesForPayroll($cid);
		ob_clean();
		echo 'success';


		// get current running period id here 

		$getCurrentDateForQuery = date('Y-m-d');

		$res20002 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 
		if($row20002 = $res20002->fetch_assoc()){

			$periodID = $row20002['id'];
		}

		$searlizedOtherFields = array();
		$searlizedOtherFields['leaveForwardorNot'] = $nextYearLeaveForwardBalance;
		$searlizedOtherFields['period'] = $periodID;
		$newsearlizeArrayOther = serialize($searlizedOtherFields);

		$selectedValue = array (

			'annual_leave' => $_REQUEST['annual_leave'],
			'year' => $cur_year,
			'emP_id' => $employeeIdValue,
			'other_fields' => $newsearlizeArrayOther,

		);



		// check if same employee id and same year already exists 


		$sql511 = "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$employeeIdValue."' ";

		if($res511 = $dbc->query($sql511)){
			while($row511 = $res511->fetch_assoc()){

				$requestRowid = $row511['id'];

				$update11 = $dbc->query("UPDATE ".$cid."_employee_per_year_records SET annual_leave = '".$_REQUEST['annual_leave']."' , year = '".$cur_year."', other_fields = '".$newsearlizeArrayOther."' WHERE id = '".$requestRowid."'");

			}
		}
		else
		{
			
			// insert into employee per year record table 

			$sql111 = "INSERT INTO ".$cid."_employee_per_year_records (";
			foreach($selectedValue as $k=>$v){
				$sql111 .= $k.', ';
			}
			$sql111 = substr($sql111,0,-2);
			$sql111 .= ") VALUES ("; 
			foreach($selectedValue as $k=>$v){
				$sql111 .= "'".mysqli_real_escape_string($dbc,$v)."', ";
			}
			$sql111 = substr($sql111,0,-2).')';


			$dbc->query($sql111);

		}


		// run query to check if system user is already presnet in the database then update the emp id column for that user 

		$sql5 = "SELECT * FROM ".$cid."_employees WHERE personal_email = '".$_REQUEST['personal_email']."'";


		if($res5 = $dbc->query($sql5)){
			if($row5 = $res5->fetch_assoc()){
				$requestEmpid = $row5['emp_id'];

	

				$update11 = $dbc->query("UPDATE ".$cid."_users SET emp_id = '".$requestEmpid."' WHERE username = '".$_REQUEST['personal_email']."'");

			}
		}




	}else{
		ob_clean();
		echo mysqli_error($dbc);
	}
