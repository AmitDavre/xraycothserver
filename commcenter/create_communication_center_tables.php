<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

	$db_name = $cid."_commCenters_logs";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `cc_id` int(11) DEFAULT NULL,
			  `field` varchar(255) DEFAULT NULL,
			  `prev` text DEFAULT NULL,
			  `new` text DEFAULT NULL,
			  `user` varchar(50) DEFAULT NULL,
			  `date` timestamp NOT NULL DEFAULT current_timestamp,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);

	
	$db_name = $cid."_comm_centers";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `anno_id` varchar(255) DEFAULT NULL,
			  `username` int(11) DEFAULT NULL,
			  `approval` int(11) DEFAULT NULL,
			  `approver` int(11) DEFAULT NULL,
			  `remark_approver` varchar(300) DEFAULT NULL,
			  `request_result` int(11) DEFAULT NULL,
			  `submit_status` int(11) DEFAULT NULL,
			  `publish_on` varchar(255) DEFAULT NULL,
			  `sent` varchar(11) DEFAULT NULL,
			  `month` int(11) DEFAULT NULL,
			  `date` date DEFAULT NULL,
			  `anno_type` int(11) DEFAULT NULL,
			  `appr_required` int(11) DEFAULT NULL,
			  `fromss` text DEFAULT NULL,
			  `tooss` text DEFAULT NULL,
			  `anno_mode` int(11) DEFAULT NULL,
			  `anno_category` int(11) DEFAULT NULL,
			  `description` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
			  `sent_to` text DEFAULT NULL,
			  `sel_emp_ids` text DEFAULT NULL,
			  `status` int(11) DEFAULT NULL,
			  `settings` text DEFAULT NULL,
			  `headerval` varchar(11) DEFAULT NULL,
			  `sectionVal` varchar(255) DEFAULT NULL,
			  `areas` longtext CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
			  `footerval` varchar(11) DEFAULT NULL,
			  `attachments` text DEFAULT NULL,
			  `pdflink` text DEFAULT NULL,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);



	$db_name = $cid."_document_templates";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `company` varchar(50) COLLATE utf8_bin DEFAULT NULL,
			  `address` text COLLATE utf8_bin DEFAULT NULL,
			  `logo` text COLLATE utf8_bin DEFAULT NULL,
			  `remark` text COLLATE utf8_bin DEFAULT NULL,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);


	$db_name = $cid."_document_textblocks";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(50) COLLATE utf8_bin NOT NULL,
			  `text` mediumtext COLLATE utf8_bin NOT NULL,
			  `status` int(11) NOT NULL,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);


	$db_name = $cid."_footer_templates";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
			  `fax` varchar(20) COLLATE utf8_bin NOT NULL,
			  `email` varchar(50) COLLATE utf8_bin NOT NULL,
			  `website` varchar(50) COLLATE utf8_bin NOT NULL,
			  `vat` varchar(20) COLLATE utf8_bin NOT NULL,
			  `bank1` varchar(50) COLLATE utf8_bin NOT NULL,
			  `acc1` varchar(20) COLLATE utf8_bin NOT NULL,
			  `bic1` varchar(20) COLLATE utf8_bin NOT NULL,
			  `bank2` varchar(50) COLLATE utf8_bin NOT NULL,
			  `acc2` varchar(20) COLLATE utf8_bin NOT NULL,
			  `bic2` varchar(20) COLLATE utf8_bin NOT NULL,
			  `note` text COLLATE utf8_bin NOT NULL,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);


	$db_name = $cid."_textblock_fields";
	$sql = "CREATE TABLE IF NOT EXISTS `$db_name`  (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `txtblock_id` int(11) DEFAULT NULL,
			  `name` varchar(255) DEFAULT NULL,
			  	PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

	$result = $dbc->query($sql);

?>
