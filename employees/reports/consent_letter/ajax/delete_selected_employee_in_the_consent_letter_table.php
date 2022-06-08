<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');




	$sqlinsertdata = "DELETE FROM  ".$cid."_consent_letter WHERE id= '".$_REQUEST['id']."'";
	$dbc->query($sqlinsertdata);



ob_clean();
echo "success";
exit;

	




?>