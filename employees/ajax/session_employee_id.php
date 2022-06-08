<?
	if(session_id()==''){session_start(); ob_start();}
	//include("../../dbconnect/db_connect.php");
	$_SESSION['rego']['empID'] = $_REQUEST['id'];
	//var_dump($_REQUEST); exit;
	
?>
