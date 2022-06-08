<?php

	if(session_id()==''){session_start();}
	$_SESSION['rego']['emp_group'] = $_REQUEST['group'];
