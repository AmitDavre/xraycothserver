<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	$idValeachss = strtolower($_REQUEST['value']);

	$sqlFteamsaa = "SELECT * FROM ".$cid."_teams WHERE LOWER(code) = '".$idValeachss."'";
	if($resFteamsaaa = $dbc->query($sqlFteamsaa))
	{
		if($rowaa = $resFteamsaaa->fetch_assoc())
		{
			echo 'exists'; exit;
		}
	}

?>


















