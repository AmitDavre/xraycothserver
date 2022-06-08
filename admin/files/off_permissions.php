<?php
	//$_SESSION['xhr']['cur_year'] = date('Y');
	//$user_access = unserialize($row['access']);
	//$ltypes = getLeaveTypes($dbc, $_POST['clientID']);
	//var_dump($user_access); exit;

	// EMPLOYEE REGISTER -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['employee']['module'] = 0;
	$_SESSION['xhr']['access']['employee']['edit'] = 0;
	$_SESSION['xhr']['access']['employee']['addemp'] = 0;
	$_SESSION['xhr']['access']['employee']['del'] = 0;
	$_SESSION['xhr']['access']['employee']['work'] = 0;
	$_SESSION['xhr']['access']['employee']['financial'] = 0;
	$_SESSION['xhr']['access']['employee']['import'] = 0;
	$_SESSION['xhr']['access']['employee']['export'] = 0;
	$_SESSION['xhr']['access']['employee']['promote'] = 0;

	// LEAVE MODULE -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['leave']['module'] = 0;
	$_SESSION['xhr']['access']['leave']['request'] = 0;
	$_SESSION['xhr']['access']['leave']['approve'] = 0;
	$_SESSION['xhr']['access']['leave']['edit'] = 0;
	$_SESSION['xhr']['access']['leave']['sCL'] = 0;
	$_SESSION['xhr']['access']['leave']['sAB'] = 0;
	$_SESSION['xhr']['access']['leave']['eTA'] = 0;
	$_SESSION['xhr']['access']['leave']['export'] = 0;
	/*foreach($ltypes as $k=>$v){
		$_SESSION['xhr']['access']['leave'][$k] = 0;
	}*/
	
	// TIME MODULE ----------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['time']['module'] = 0;
	$_SESSION['xhr']['access']['time']['request'] = 0;
	$_SESSION['xhr']['access']['time']['approve'] = 0;
	$_SESSION['xhr']['access']['time']['edit'] = 0;
	
	// REPORT CENTER --------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['report']['module'] = 0;
	
	// APPROVE MODULE ------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['approve']['module'] = 0;
	$_SESSION['xhr']['access']['approve']['leave']['access'] = 0;
	$_SESSION['xhr']['access']['approve']['leave']['approve'] = 0;
	$_SESSION['xhr']['access']['approve']['leave']['request'] = 0;
	$_SESSION['xhr']['access']['approve']['leave']['edit'] = 0;
	/*foreach($ltypes as $k=>$v){
		$_SESSION['xhr']['access']['approve']['leave'][$k] = 0;
	}*/
	$_SESSION['xhr']['access']['approve']['time']['access'] = 0;
	$_SESSION['xhr']['access']['approve']['time']['approve'] = 0;
	$_SESSION['xhr']['access']['approve']['time']['request'] = 0;
	$_SESSION['xhr']['access']['approve']['time']['edit'] = 0;
	$_SESSION['xhr']['access']['approve']['ot']['access'] = 0;
	$_SESSION['xhr']['access']['approve']['ot']['approve'] = 0;
	$_SESSION['xhr']['access']['approve']['ot']['request'] = 0;
	$_SESSION['xhr']['access']['approve']['ot']['edit'] = 0;
	$_SESSION['xhr']['access']['approve']['payroll']['access'] = 0;
	$_SESSION['xhr']['access']['approve']['payroll']['review'] = 0;
	$_SESSION['xhr']['access']['approve']['payroll']['approve'] = 0;
	$_SESSION['xhr']['access']['approve']['payroll']['edit'] = 0;
	
	// COMPANY COMMUNICATION PLATFORM ---------------------------------------------------------------------
	$_SESSION['xhr']['access']['ccplatform']['module'] = 0;
	
	// PAYROLL MODULE -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['payroll']['module'] = 0;
	$_SESSION['xhr']['access']['payroll']['review'] = 0;
	$_SESSION['xhr']['access']['payroll']['approve'] = 0;
	$_SESSION['xhr']['access']['payroll']['edit'] = 0;
	$_SESSION['xhr']['access']['payroll']['add'] = 0;
	$_SESSION['xhr']['access']['payroll']['del'] = 0;
	$_SESSION['xhr']['access']['payroll']['lock'] = 0;
	$_SESSION['xhr']['access']['payroll']['unlock'] = 0;
	$_SESSION['xhr']['access']['payroll']['settings'] = 0;
	
	// SETTINGS -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['settings']['module'] = 0;
	
	$_SESSION['xhr']['access']['comp_set']['module'] = 0;
	$_SESSION['xhr']['access']['leave_set']['module'] = 0;
	$_SESSION['xhr']['access']['time_set']['module'] = 0;
	$_SESSION['xhr']['access']['pr_set']['module'] = 0;
	$_SESSION['xhr']['access']['sys_set']['module'] = 0;
	//$_SESSION['xhr']['access']['user_set']['module'] = 0;
	$_SESSION['xhr']['access']['newyear']['module'] = 0;

	// COMPANY SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['comp_set']['edit'] = 0;
	$_SESSION['xhr']['access']['comp_set']['add'] = 0;
	$_SESSION['xhr']['access']['comp_set']['del'] = 0;
	
	// LEAVE SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['leave_set']['edit'] = 0;
	$_SESSION['xhr']['access']['leave_set']['add'] = 0;
	$_SESSION['xhr']['access']['leave_set']['del'] = 0;
	
	// TIME SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['time_set']['edit'] = 0;
	$_SESSION['xhr']['access']['time_set']['add'] = 0;
	$_SESSION['xhr']['access']['time_set']['del'] = 0;
	
	// PAYROLL SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['pr_set']['edit'] = 0;
	$_SESSION['xhr']['access']['pr_set']['add'] = 0;
	$_SESSION['xhr']['access']['pr_set']['del'] = 0;
	
	// SYSTEM SETTINGS ------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['sys_set']['edit'] = 0;
	$_SESSION['xhr']['access']['sys_set']['add'] = 0;
	$_SESSION['xhr']['access']['sys_set']['del'] = 0;
	
	// SUPPORT DESK ------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['support']['general'] = 0;
	$_SESSION['xhr']['access']['support']['confidential'] = 0;
	$_SESSION['xhr']['access']['support']['bug'] = 0;
	
	// USER SETTINGS -------------------------------------------------------------------------------------
	// SYSTEM USERS -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['sys_users']['access'] = 0;
	$_SESSION['xhr']['access']['sys_users']['edit'] = 0;
	$_SESSION['xhr']['access']['sys_users']['add'] = 0;
	$_SESSION['xhr']['access']['sys_users']['del'] = 0;
	// COMPANY USERS -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['com_users']['access'] = 0;
	$_SESSION['xhr']['access']['com_users']['edit'] = 0;
	$_SESSION['xhr']['access']['com_users']['add'] = 0;
	$_SESSION['xhr']['access']['com_users']['del'] = 0;
	// EMPLOYEE USERS -------------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['emp_users']['access'] = 0;
	$_SESSION['xhr']['access']['emp_users']['edit'] = 0;
	$_SESSION['xhr']['access']['emp_users']['add'] = 0;
	$_SESSION['xhr']['access']['emp_users']['del'] = 0;

	// OTHER SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['xhr']['access']['branch'] = 'all';
	$_SESSION['xhr']['access']['group'] = 'all';
	$_SESSION['xhr']['access']['department'] = 'all';
	
	$_SESSION['xhr']['access']['emp_group'] = 'x';

?>
