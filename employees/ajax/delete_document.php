<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	/*$res = $dbc->query("SELECT ".$_REQUEST['doc']." FROM ".$cid."_company_settings");
	$row = $res->fetch_assoc();
	$file = $row[$_REQUEST['doc']];*/
	
	$res = $dbc->query("UPDATE ".$cid."_employees SET ".$_REQUEST['doc']." = '' WHERE emp_id = '".$_REQUEST['emp_id']."'");
	
	//var_dump($file); //exit;
