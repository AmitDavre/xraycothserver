<?php
	
	if(session_id()==''){session_start();}
	$_SESSION['rego']['mob_year'] = $_REQUEST['year'];	
	echo $_SESSION['rego']['cur_year'];