<?php
	if(session_id()==''){session_start();}
	$_SESSION['rego']['report_id'] = $_REQUEST['id'];
?>
