<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');

	$dbc->query("TRUNCATE TABLE ".$cid."_historic_data");	
	ob_clean();
	echo 'ok';