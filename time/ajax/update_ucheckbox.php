<?
	if(session_id()==''){session_start();}; ob_start();

	include('../../dbconnect/db_connect.php');



	$update = $dbc->query("UPDATE ".$cid."_scandata SET checkbox = 0 WHERE id = '".$_POST['hidden_scaniD']."'");


?>
