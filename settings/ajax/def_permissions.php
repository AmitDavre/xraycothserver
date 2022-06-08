<?php
	//$_SESSION['xhr']['cur_year'] = date('Y');
	//$user_access = unserialize($row['access']);
	//$ltypes = getLeaveTypes($dbc, $_POST['clientID']);
	//var_dump($user_access); exit;

	// EMPLOYEE REGISTER -------------------------------------------------------------------------------------
	$array['employee']['module'] = 0;
	$array['employee']['edit'] = 0;
	$array['employee']['addemp'] = 0;
	$array['employee']['del'] = 0;
	$array['employee']['work'] = 0;
	$array['employee']['financial'] = 0;
	$array['employee']['import'] = 0;
	$array['employee']['export'] = 0;
	$array['employee']['promote'] = 0;

	// LEAVE MODULE -------------------------------------------------------------------------------------
	$array['leave']['module'] = 0;
	$array['leave']['request'] = 0;
	$array['leave']['approve'] = 0;
	$array['leave']['edit'] = 0;
	$array['leave']['sCL'] = 0;
	$array['leave']['sAB'] = 0;
	$array['leave']['eTA'] = 0;
	$array['leave']['export'] = 0;
	/*foreach($ltypes as $k=>$v){
		$array['leave'][$k] = 0;
	}*/
	
	// TIME MODULE ----------------------------------------------------------------------------------------
	$array['time']['module'] = 0;
	$array['time']['request'] = 0;
	$array['time']['approve'] = 0;
	$array['time']['edit'] = 0;
	
	// REPORT CENTER --------------------------------------------------------------------------------------
	$array['report']['module'] = 0;
	
	// APPROVE MODULE ------------------------------------------------------------------------------------
	/*$array['approve']['module'] = 0;
	$array['approve']['leave']['access'] = 0;
	$array['approve']['leave']['approve'] = 0;
	$array['approve']['leave']['request'] = 0;
	$array['approve']['leave']['edit'] = 0;
	$array['approve']['time']['access'] = 0;
	$array['approve']['time']['approve'] = 0;
	$array['approve']['time']['request'] = 0;
	$array['approve']['time']['edit'] = 0;
	$array['approve']['ot']['access'] = 0;
	$array['approve']['ot']['approve'] = 0;
	$array['approve']['ot']['request'] = 0;
	$array['approve']['ot']['edit'] = 0;
	$array['approve']['payroll']['access'] = 0;
	$array['approve']['payroll']['review'] = 0;
	$array['approve']['payroll']['approve'] = 0;
	$array['approve']['payroll']['edit'] = 0;*/
	
	// COMPANY COMMUNICATION PLATFORM ---------------------------------------------------------------------
	//$array['ccplatform']['module'] = 0;
	
	// PAYROLL MODULE -------------------------------------------------------------------------------------
	$array['payroll']['module'] = 0;
	$array['payroll']['review'] = 0;
	$array['payroll']['approve'] = 0;
	$array['payroll']['edit'] = 0;
	$array['payroll']['add'] = 0;
	$array['payroll']['del'] = 0;
	$array['payroll']['lock'] = 0;
	$array['payroll']['unlock'] = 0;
	$array['payroll']['settings'] = 0;
	
	// SETTINGS -------------------------------------------------------------------------------------
	$array['settings']['module'] = 0;
	
	$array['comp_set']['module'] = 0;
	$array['leave_set']['module'] = 0;
	$array['time_set']['module'] = 0;
	$array['pr_set']['module'] = 0;
	$array['sys_set']['module'] = 0;
	//$array['user_set']['module'] = 0;
	$array['newyear']['module'] = 0;

	// COMPANY SETTINGS -----------------------------------------------------------------------------------
	$array['comp_set']['edit'] = 0;
	$array['comp_set']['add'] = 0;
	$array['comp_set']['del'] = 0;
	
	// LEAVE SETTINGS -----------------------------------------------------------------------------------
	$array['leave_set']['edit'] = 0;
	$array['leave_set']['add'] = 0;
	$array['leave_set']['del'] = 0;
	
	// TIME SETTINGS -----------------------------------------------------------------------------------
	$array['time_set']['edit'] = 0;
	$array['time_set']['add'] = 0;
	$array['time_set']['del'] = 0;
	
	// PAYROLL SETTINGS -----------------------------------------------------------------------------------
	$array['pr_set']['edit'] = 0;
	$array['pr_set']['add'] = 0;
	$array['pr_set']['del'] = 0;
	
	// SYSTEM SETTINGS ------------------------------------------------------------------------------------
	$array['sys_set']['edit'] = 0;
	$array['sys_set']['add'] = 0;
	$array['sys_set']['del'] = 0;
	
	// SUPPORT DESK ------------------------------------------------------------------------------------
	$array['support']['general'] = 0;
	$array['support']['confidential'] = 0;
	$array['support']['bug'] = 0;
	
	// USER SETTINGS -------------------------------------------------------------------------------------
	// SYSTEM USERS -------------------------------------------------------------------------------------
	$array['sys_users']['access'] = 0;
	$array['sys_users']['edit'] = 0;
	$array['sys_users']['add'] = 0;
	$array['sys_users']['del'] = 0;
	// COMPANY USERS -------------------------------------------------------------------------------------
	/*$array['com_users']['access'] = 0;
	$array['com_users']['edit'] = 0;
	$array['com_users']['add'] = 0;
	$array['com_users']['del'] = 0;*/
	// EMPLOYEE USERS -------------------------------------------------------------------------------------
	$array['emp_users']['access'] = 0;
	$array['emp_users']['edit'] = 0;
	$array['emp_users']['add'] = 0;
	$array['emp_users']['del'] = 0;

	// OTHER SETTINGS -----------------------------------------------------------------------------------
	//$array['branch'] = 'all';
	//$array['group'] = 'all';
	//$array['department'] = 'all';
	//$array['emp_group'] = 's';
	$def_permissions = serialize($array);
	//var_dump($def_permissions);

?>
