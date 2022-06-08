<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_FILES); //exit;
	
	/*$res = $dbc->query("SELECT ".$_REQUEST['doc']." FROM ".$cid."_company_settings");
	$row = $res->fetch_assoc();
	$file = $row[$_REQUEST['doc']];*/
	
	$res = $dbc->query("UPDATE ".$cid."_company_settings SET ".$_REQUEST['doc']." = ''");
	
	//var_dump($file); //exit;
	//var_dump($_REQUEST); exit;
