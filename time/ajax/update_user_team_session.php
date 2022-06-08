<?
	if(session_id()==''){session_start();}; ob_start();

	include('../../dbconnect/db_connect.php');

	$cid 		= $_POST['cid'];
	$username 	= $_POST['username'];
	$id 		= $cid.'_'.$username;
	

	$update = $dbc->query("UPDATE ".$cid."_user_permissions SET session_team = '".$_POST['teams']."' WHERE id = '".$id."'");

?>


