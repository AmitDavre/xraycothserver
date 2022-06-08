<?php

	if(session_id()==''){session_start();}
	$_SESSION['rego']['gov_month'] = $_REQUEST['month'];
	echo $_SESSION['rego']['gov_month'];
