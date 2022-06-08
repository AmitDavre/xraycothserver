<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//ob_clean();
	//echo($_REQUEST['id']); exit;

	$dbc->query("UPDATE ".$cid."_ot_employees SET ot_confirmed = 1 WHERE id = '".$_REQUEST['id']."'");
	








