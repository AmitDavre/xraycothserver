<?php
	//$_SESSION['xhr']['cur_year'] = date('Y');
	//$user_access = unserialize($row['access']);
	//$ltypes = getLeaveTypes($dbc, $_POST['clientID']);
	//var_dump($user_access); exit;

	// EMPLOYEE REGISTER -------------------------------------------------------------------------------------
	$array['employee']['module'] = 1;
	$array['employee']['edit'] = 1;
	$array['employee']['addemp'] = 1;
	$array['employee']['del'] = 1;
	$array['employee']['work'] = 1;
	$array['employee']['financial'] = 1;
	$array['employee']['import'] = 1;
	$array['employee']['export'] = 1;
	$array['employee']['promote'] = 1;

	// LEAVE MODULE -------------------------------------------------------------------------------------
	$array['leave']['module'] = 1;
	$array['leave']['request'] = 1;
	$array['leave']['approve'] = 1;
	$array['leave']['edit'] = 1;
	$array['leave']['sCL'] = 1;
	$array['leave']['sAB'] = 1;
	$array['leave']['eTA'] = 1;
	$array['leave']['export'] = 1;
	/*foreach($ltypes as $k=>$v){
		$array['leave'][$k] = 1;
	}*/
	
	// TIME MODULE ----------------------------------------------------------------------------------------
	$array['time']['module'] = 1;
	$array['time']['request'] = 1;
	$array['time']['approve'] = 1;
	$array['time']['edit'] = 1;
	
	// REPORT CENTER --------------------------------------------------------------------------------------
	$array['report']['module'] = 1;
	
	// APPROVE MODULE ------------------------------------------------------------------------------------
	/*$array['approve']['module'] = 1;
	$array['approve']['leave']['access'] = 1;
	$array['approve']['leave']['approve'] = 1;
	$array['approve']['leave']['request'] = 1;
	$array['approve']['leave']['edit'] = 1;
	$array['approve']['time']['access'] = 1;
	$array['approve']['time']['approve'] = 1;
	$array['approve']['time']['request'] = 1;
	$array['approve']['time']['edit'] = 1;
	$array['approve']['ot']['access'] = 1;
	$array['approve']['ot']['approve'] = 1;
	$array['approve']['ot']['request'] = 1;
	$array['approve']['ot']['edit'] = 1;
	$array['approve']['payroll']['access'] = 1;
	$array['approve']['payroll']['review'] = 1;
	$array['approve']['payroll']['approve'] = 1;
	$array['approve']['payroll']['edit'] = 1;*/
	
	// COMPANY COMMUNICATION PLATFORM ---------------------------------------------------------------------
	//$array['ccplatform']['module'] = 1;
	
	// PAYROLL MODULE -------------------------------------------------------------------------------------
	$array['payroll']['module'] = 1;
	$array['payroll']['review'] = 1;
	$array['payroll']['approve'] = 1;
	$array['payroll']['edit'] = 1;
	$array['payroll']['add'] = 1;
	$array['payroll']['del'] = 1;
	$array['payroll']['lock'] = 1;
	$array['payroll']['unlock'] = 1;
	$array['payroll']['settings'] = 1;
	
	// SETTINGS -------------------------------------------------------------------------------------
	$array['settings']['module'] = 1;
	
	$array['comp_set']['module'] = 1;
	$array['leave_set']['module'] = 1;
	$array['time_set']['module'] = 1;
	$array['pr_set']['module'] = 1;
	$array['sys_set']['module'] = 1;
	//$array['user_set']['module'] = 1;
	$array['newyear']['module'] = 1;

	// COMPANY SETTINGS -----------------------------------------------------------------------------------
	$array['comp_set']['edit'] = 1;
	$array['comp_set']['add'] = 1;
	$array['comp_set']['del'] = 1;
	
	// LEAVE SETTINGS -----------------------------------------------------------------------------------
	$array['leave_set']['edit'] = 1;
	$array['leave_set']['add'] = 1;
	$array['leave_set']['del'] = 1;
	
	// TIME SETTINGS -----------------------------------------------------------------------------------
	$array['time_set']['edit'] = 1;
	$array['time_set']['add'] = 1;
	$array['time_set']['del'] = 1;
	
	// PAYROLL SETTINGS -----------------------------------------------------------------------------------
	$array['pr_set']['edit'] = 1;
	$array['pr_set']['add'] = 1;
	$array['pr_set']['del'] = 1;
	
	// SYSTEM SETTINGS ------------------------------------------------------------------------------------
	$array['sys_set']['edit'] = 1;
	$array['sys_set']['add'] = 1;
	$array['sys_set']['del'] = 1;
	
	// SUPPORT DESK ------------------------------------------------------------------------------------
	$array['support']['general'] = 1;
	$array['support']['confidential'] = 1;
	$array['support']['bug'] = 1;
	
	// USER SETTINGS -------------------------------------------------------------------------------------
	// SYSTEM USERS -------------------------------------------------------------------------------------
	$array['sys_users']['access'] = 1;
	$array['sys_users']['edit'] = 1;
	$array['sys_users']['add'] = 1;
	$array['sys_users']['del'] = 1;
	// COMPANY USERS -------------------------------------------------------------------------------------
	/*$array['com_users']['access'] = 1;
	$array['com_users']['edit'] = 1;
	$array['com_users']['add'] = 1;
	$array['com_users']['del'] = 1;*/
	// EMPLOYEE USERS -------------------------------------------------------------------------------------
	$array['emp_users']['access'] = 1;
	$array['emp_users']['edit'] = 1;
	$array['emp_users']['add'] = 1;
	$array['emp_users']['del'] = 1;

	// OTHER SETTINGS -----------------------------------------------------------------------------------
	//$array['branch'] = 'all';
	//$array['group'] = 'all';
	//$array['department'] = 'all';
	//$array['emp_group'] = 's';
	$sys_permissions = serialize($array);
	//var_dump($def_permissions);

?>
