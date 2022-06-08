<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	
	$dbc->query("UPDATE ".$cid."_settings SET history = 1");
