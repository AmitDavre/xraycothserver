<?php

	$size = filesize($dir.$filename);
	$dbc->query("INSERT INTO ".$cid."_documents (name, filename, month, year, size, type, date, user_name, link) VALUES (
		'".$dbc->real_escape_string($doc)."', 
		'".$dbc->real_escape_string($filename)."', 
		'".$dbc->real_escape_string($_SESSION['rego']['gov_month'])."', 
		'".$dbc->real_escape_string($_SESSION['rego']['cur_year'])."', 
		'".$dbc->real_escape_string(round(($size/1024),2))."', 
		'".$dbc->real_escape_string('pdf')."', 
		'".$dbc->real_escape_string(date('d-m-Y H:i'))."', 
		'".$dbc->real_escape_string($_SESSION['rego']['name'])."', 
		'".$dbc->real_escape_string($root.$filename)."')
		ON DUPLICATE KEY UPDATE 
		date=VALUES(date), 
		user_name=VALUES(user_name)");
