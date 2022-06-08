<?php
	if(session_id()==''){session_start();}
	ob_start();
	
	$probation = date('d-m-Y', strtotime($_REQUEST['date'].'+ 4 months'));
	
	//$date = new DateTime($_REQUEST['date']);
	//$date->add(new DateInterval('P119D'));
	//$probation = $date->format('d-m-Y');
	echo $probation;
?>