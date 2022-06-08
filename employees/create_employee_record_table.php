<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');



	$db_name = $cid."_employee_per_year_records";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL  AUTO_INCREMENT,
			  `emp_id` varchar(255) DEFAULT NULL,
			  `annual_leave` varchar(255) DEFAULT NULL,
			  `year` varchar(255) DEFAULT NULL,
			  `other_fields` text,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql) ;	


	$db_name20001 = $cid."_leave_periods";
	$sql20001 = "CREATE TABLE IF NOT EXISTS `$db_name20001` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `leave_period_year` varchar(255) DEFAULT NULL,
					  `leave_period_start` varchar(255) DEFAULT NULL,
					  `leave_period_end` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result20001 = $dbc->query($sql20001) ;





	$db_name20012301 = $cid."_consent_letter";
	$sql254540001 = "CREATE TABLE IF NOT EXISTS `$db_name20012301` (
					  `id` int(11) NOT NULL,
					  `emp_id` varchar(255) DEFAULT NULL,
					  `en_name` varchar(255) DEFAULT NULL,
					  `department` varchar(255) DEFAULT NULL,
					  `position` varchar(255) DEFAULT NULL,
					  `branch` varchar(255) DEFAULT NULL,
					  `division` varchar(255) DEFAULT NULL,
					  `team` varchar(255) DEFAULT NULL
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";

	$result245350001 = $dbc->query($sql254540001) ;




	$db_name20012342342301 = $cid."_consent_letter";
	$sql254540032423401 = "ALTER TABLE `$db_name20012342342301` ADD PRIMARY KEY (`id`)";
	$result243453455350001 = $dbc->query($sql254540032423401) ;


	$db_name20012342323423442301 = $cid."_consent_letter";
	$sql254540032dasd423401 = "ALTER TABLE `$db_name20012342323423442301` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1";
	$result24345345asda5350001 = $dbc->query($sql254540032dasd423401) ;









	// alter columns in entities data table 
	$db_name20dd001 = $cid."_entities_data";
	$sql200dd01 = "ALTER TABLE `$db_name20dd001` ADD `website_status`  VARCHAR(255)  NULL AFTER `attach3`, ADD `website_name` VARCHAR(255) NULL AFTER `website_status`, ADD `contact_info_status` VARCHAR(255) NULL AFTER `website_name`, ADD `c_company` VARCHAR(255) NULL AFTER `contact_info_status`, ADD `c_address` TEXT NOT NULL AFTER `c_company`, ADD `c_function` VARCHAR(55) NULL AFTER `c_address`, ADD `c_telephone` VARCHAR(55) NULL AFTER `c_function`, ADD `c_email` VARCHAR(55) NULL AFTER `c_telephone`, ADD `c_company_th` VARCHAR(255) NULL AFTER `c_email`, ADD `c_address_th` TEXT NOT NULL AFTER `c_company_th`, ADD `c_function_th` VARCHAR(55) NULL AFTER `c_address_th`, ADD `c_telephone_th` VARCHAR(55) NULL AFTER `c_function_th`, ADD `c_email_th` VARCHAR(55) NULL AFTER `c_telephone_th`";

		$result200ddd01 = $dbc->query($sql200dd01) ;

	// alter columns in entities data table 
	$db_name20dd0asda01 = $cid."_sys_settings";
	$asdssadas = "ALTER TABLE `$db_name20dd0asda01` ADD `c_company_temp` VARCHAR(255) NULL AFTER `scan_id`, ADD `c_address_temp` TEXT NULL AFTER `c_company_temp`, ADD `c_function_temp` VARCHAR(255) NULL AFTER `c_address_temp`, ADD `c_telephone_temp` VARCHAR(255) NULL AFTER `c_function_temp`, ADD `c_email_temp` VARCHAR(255) NULL AFTER `c_telephone_temp`";

		$result200ddsadad01 = $dbc->query($asdssadas) ;


?>














