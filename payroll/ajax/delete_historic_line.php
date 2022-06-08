<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$dbname = $cid.'_historic_data';
	$res = $dbc->query("DELETE FROM ".$dbname." WHERE id = '".$_REQUEST['id']."'" );
	//echo mysqli_error($dbc);