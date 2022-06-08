<?php
	//var_dump($ltypes); exit;
	
	
	

	// EMPLOYEE REGISTER -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['employee']['module'] = 1;
	$_SESSION['rego']['access']['employee']['edit'] = 1;
	$_SESSION['rego']['access']['employee']['addemp'] = 1;
	$_SESSION['rego']['access']['employee']['del'] = 1;
	$_SESSION['rego']['access']['employee']['work'] = 1;
	$_SESSION['rego']['access']['employee']['financial'] = 1;
	$_SESSION['rego']['access']['employee']['import'] = 1;
	$_SESSION['rego']['access']['employee']['export'] = 1;
	$_SESSION['rego']['access']['employee']['promote'] = 1;

	// LEAVE MODULE -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['leave']['module'] = 1;
	$_SESSION['rego']['access']['leave']['request'] = 1;
	$_SESSION['rego']['access']['leave']['approve'] = 1;
	$_SESSION['rego']['access']['leave']['edit'] = 1;
	$_SESSION['rego']['access']['leave']['aCL'] = 1;
	$_SESSION['rego']['access']['leave']['aAB'] = 1;
	$_SESSION['rego']['access']['leave']['eTA'] = 1;
	$_SESSION['rego']['access']['leave']['export'] = 1;
	/*foreach($ltypes as $k=>$v){
		$_SESSION['rego']['access']['leave'][$k] = 1;
	}*/
	
	// TIME MODULE ----------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['time']['module'] = 1;
	$_SESSION['rego']['access']['time']['request'] = 1;
	$_SESSION['rego']['access']['time']['approve'] = 1;
	$_SESSION['rego']['access']['time']['edit'] = 1;
	
	// REPORT CENTER --------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['report']['module'] = 1;
	
	// APPROVE MODULE ------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['approve']['module'] = 1;
	$_SESSION['rego']['access']['approve']['leave']['access'] = 1;
	$_SESSION['rego']['access']['approve']['leave']['approve'] = 1;
	$_SESSION['rego']['access']['approve']['leave']['request'] = 1;
	$_SESSION['rego']['access']['approve']['leave']['edit'] = 1;
	/*foreach($ltypes as $k=>$v){
		$_SESSION['rego']['access']['approve']['leave'][$k] = 1;
	}*/
	$_SESSION['rego']['access']['approve']['time']['access'] = 1;
	$_SESSION['rego']['access']['approve']['time']['approve'] = 1;
	$_SESSION['rego']['access']['approve']['time']['request'] = 1;
	$_SESSION['rego']['access']['approve']['time']['edit'] = 1;
	$_SESSION['rego']['access']['approve']['ot']['access'] = 1;
	$_SESSION['rego']['access']['approve']['ot']['approve'] = 1;
	$_SESSION['rego']['access']['approve']['ot']['request'] = 1;
	$_SESSION['rego']['access']['approve']['ot']['edit'] = 1;
	$_SESSION['rego']['access']['approve']['payroll']['access'] = 1;
	$_SESSION['rego']['access']['approve']['payroll']['review'] = 1;
	$_SESSION['rego']['access']['approve']['payroll']['approve'] = 1;
	$_SESSION['rego']['access']['approve']['payroll']['edit'] = 1;
	
	// COMPANY COMMUNICATION PLATFORM ---------------------------------------------------------------------
	$_SESSION['rego']['access']['ccplatform']['module'] = 1;
	
	// PAYROLL MODULE -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['payroll']['module'] = 1;
	$_SESSION['rego']['access']['payroll']['review'] = 1;
	$_SESSION['rego']['access']['payroll']['approve'] = 1;
	$_SESSION['rego']['access']['payroll']['edit'] = 1;
	$_SESSION['rego']['access']['payroll']['add'] = 1;
	$_SESSION['rego']['access']['payroll']['del'] = 1;
	$_SESSION['rego']['access']['payroll']['lock'] = 1;
	$_SESSION['rego']['access']['payroll']['unlock'] = 1;
	$_SESSION['rego']['access']['payroll']['settings'] = 1;
	
	// SETTINGS -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['settings']['module'] = 1;
	
	$_SESSION['rego']['access']['comp_set']['module'] = 1;
	$_SESSION['rego']['access']['leave_set']['module'] = 1;
	$_SESSION['rego']['access']['time_set']['module'] = 1;
	$_SESSION['rego']['access']['pr_set']['module'] = 1;
	$_SESSION['rego']['access']['sys_set']['module'] = 1;
	//$_SESSION['rego']['access']['user_set']['module'] = 1;
	$_SESSION['rego']['access']['newyear']['module'] = 1;

	// COMPANY SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['rego']['access']['comp_set']['edit'] = 1;
	$_SESSION['rego']['access']['comp_set']['add'] = 1;
	$_SESSION['rego']['access']['comp_set']['del'] = 1;
	
	// LEAVE SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['rego']['access']['leave_set']['edit'] = 1;
	$_SESSION['rego']['access']['leave_set']['add'] = 1;
	$_SESSION['rego']['access']['leave_set']['del'] = 1;
	
	// TIME SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['rego']['access']['time_set']['edit'] = 1;
	$_SESSION['rego']['access']['time_set']['add'] = 1;
	$_SESSION['rego']['access']['time_set']['del'] = 1;
	
	// PAYROLL SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['rego']['access']['pr_set']['edit'] = 1;
	$_SESSION['rego']['access']['pr_set']['add'] = 1;
	$_SESSION['rego']['access']['pr_set']['del'] = 1;
	
	// SYSTEM SETTINGS ------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['sys_set']['edit'] = 1;
	$_SESSION['rego']['access']['sys_set']['add'] = 1;
	$_SESSION['rego']['access']['sys_set']['del'] = 1;
	
	// SUPPORT DESK ------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['support']['general'] = 1;
	$_SESSION['rego']['access']['support']['confidential'] = 1;
	$_SESSION['rego']['access']['support']['bug'] = 1;
	
	// USER SETTINGS -------------------------------------------------------------------------------------
	// SYSTEM USERS -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['sys_users']['access'] = 1;
	$_SESSION['rego']['access']['sys_users']['edit'] = 1;
	$_SESSION['rego']['access']['sys_users']['add'] = 1;
	$_SESSION['rego']['access']['sys_users']['del'] = 1;
	// COMPANY USERS -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['com_users']['access'] = 1;
	$_SESSION['rego']['access']['com_users']['edit'] = 1;
	$_SESSION['rego']['access']['com_users']['add'] = 1;
	$_SESSION['rego']['access']['com_users']['del'] = 1;
	// EMPLOYEE USERS -------------------------------------------------------------------------------------
	$_SESSION['rego']['access']['emp_users']['access'] = 1;
	$_SESSION['rego']['access']['emp_users']['edit'] = 1;
	$_SESSION['rego']['access']['emp_users']['add'] = 1;
	$_SESSION['rego']['access']['emp_users']['del'] = 1;
	
	// OTHER SETTINGS -----------------------------------------------------------------------------------
	$_SESSION['rego']['access']['branch'] = 'all';
	$_SESSION['rego']['access']['group'] = 'all';
	$_SESSION['rego']['access']['department'] = 'all';
	
	$_SESSION['rego']['access']['emp_group'] = 'x';

?>
