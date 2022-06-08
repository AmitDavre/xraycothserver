<?
	if(session_id()==''){session_start();}; ob_start();

	include('../../dbconnect/db_connect.php');

	$id = $_POST['hidden_scaniD'];

	$value = explode(",",$id);

	foreach ($value as $key => $val) 
	{
		$update = $dbc->query("UPDATE ".$cid."_scandata SET checkbox = 0 WHERE id = '".$val."'");
	}


?>
