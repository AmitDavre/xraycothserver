<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;

	$dbc->query("DELETE FROM ".$cid."_leaves WHERE id = ".$_REQUEST['id']);
	$dbc->query("DELETE FROM ".$cid."_leaves_data WHERE leave_id = ".$_REQUEST['id']);









