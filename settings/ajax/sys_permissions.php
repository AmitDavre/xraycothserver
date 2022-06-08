<?php

	// EMPLOYEE REGISTER -------------------------------------------------------------------------------------
	$array['employee']['access'] = 1;
	$array['employee']['add'] = 1;
	$array['employee']['import'] = 1;
	$array['employee']['export'] = 1;
	$array['employee']['del'] = 1;
	$array['employee']['report'] = 1;
	$array['employee']['archive'] = 1;
	$array['employee']['settings'] = 1;

	$array['employee_info']['view'] = 1;
	$array['employee_info']['edit'] = 1;
	$array['employee_info']['del'] = 1;
	
	$array['employee_finance']['view'] = 1;
	$array['employee_finance']['edit'] = 1;
	$array['employee_finance']['del'] = 1;
	
	$array['employee_benefit']['view'] = 1;
	$array['employee_benefit']['edit'] = 1;
	$array['employee_benefit']['add'] = 1;
	
	$array['employee_history']['view'] = 1;
	$array['employee_history']['edit'] = 1;
	$array['employee_history']['add'] = 1;
	
	$array['employee_permit']['view'] = 1;
	$array['employee_permit']['edit'] = 1;
	$array['employee_permit']['add'] = 1;
	
	// LEAVE MODULE ----------------------------------------------------------------------------------------
	$array['leave']['access'] = 1;
	$array['leave']['report'] = 1;
	$array['leave']['archive'] = 1;
	$array['leave']['settings'] = 1;

	$array['leave_application']['view'] = 1;
	$array['leave_application']['edit'] = 1;
	$array['leave_application']['del'] = 1;
	$array['leave_application']['request'] = 1;
	$array['leave_application']['review'] = 1;
	$array['leave_application']['approve'] = 1;
	
	$array['leave_calendar']['view'] = 1;
	$array['leave_calendar']['edit'] = 1;
	
	$array['leave_approve']['view'] = 1;
	$array['leave_approve']['edit'] = 1;
	$array['leave_approve']['del'] = 1;
	$array['leave_approve']['request'] = 1;
	$array['leave_approve']['review'] = 1;
	$array['leave_approve']['approve'] = 1;
	$array['leave_approve']['lock'] = 1;
	
	// TIME MODULE ----------------------------------------------------------------------------------------
	$array['time']['access'] = 1;
	$array['time']['report'] = 1;
	$array['time']['archive'] = 1;
	$array['time']['settings'] = 1;
	
	$array['time_import']['view'] = 1;
	$array['time_import']['edit'] = 1;
	
	$array['time_attendance']['view'] = 1;
	$array['time_attendance']['edit'] = 1;
	$array['time_attendance']['del'] = 1;
	$array['time_attendance']['approve'] = 1;
	
	$array['time_monthly']['view'] = 1;
	$array['time_monthly']['edit'] = 1;
	$array['time_monthly']['review'] = 1;
	$array['time_monthly']['approve'] = 1;
	$array['time_monthly']['lock'] = 1;
	
	$array['time_planning']['view'] = 1;
	$array['time_planning']['edit'] = 1;
	$array['time_planning']['del'] = 1;
	
	$array['time_shift']['view'] = 1;
	$array['time_shift']['edit'] = 1;
	$array['time_shift']['del'] = 1;
	$array['time_shift']['approve'] = 1;
	
	// PAYROLL --------------------------------------------------------------------------------------
	$array['payroll']['access'] = 1;
	$array['payroll']['report'] = 1;
	$array['payroll']['archive'] = 1;
	$array['payroll']['settings'] = 1;
	
	$array['payroll_attendance']['view'] = 1;
	$array['payroll_attendance']['edit'] = 1;
	
	$array['payroll_result']['view'] = 1;
	$array['payroll_result']['edit'] = 1;
	
	$array['payroll_forms']['view'] = 1;
	$array['payroll_forms']['edit'] = 1;
	
	$array['payroll_export']['view'] = 1;
	$array['payroll_export']['edit'] = 1;
	
	$array['payroll_benefits']['view'] = 1;
	$array['payroll_benefits']['edit'] = 1;
	
	$array['payroll_calculations']['view'] = 1;
	$array['payroll_calculations']['edit'] = 1;
	
	$array['payroll_historical']['view'] = 1;
	$array['payroll_historical']['edit'] = 1;
	
	// EXPENCES --------------------------------------------------------------------------------------
	$array['expences']['access'] = 1;
	$array['expences']['view'] = 1;
	$array['expences']['edit'] = 1;
	$array['expences']['report'] = 1;
	$array['expences']['archive'] = 1;
	$array['expences']['settings'] = 1;
	
	// PROJECTS --------------------------------------------------------------------------------------
	$array['project']['access'] = 1;
	$array['project']['view'] = 1;
	$array['project']['edit'] = 1;
	$array['project']['report'] = 1;
	$array['project']['archive'] = 1;
	$array['project']['settings'] = 1;
	
	// SETTINGS --------------------------------------------------------------------------------------
	$array['settings']['access'] = 1;
	$array['settings']['view'] = 1;
	$array['settings']['edit'] = 1;
	
	// SUPPORT --------------------------------------------------------------------------------------
	$array['support']['access'] = 1;
	$array['support']['view'] = 1;
	$array['support']['edit'] = 1;
	
	$sys_permissions = serialize($array);
	//var_dump($def_permissions);

?>
