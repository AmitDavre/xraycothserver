<?
	if(session_id()==''){session_start(); ob_start();}
	$_SESSION['RGadmin']['lang'] = $_REQUEST['lng'];
?>
