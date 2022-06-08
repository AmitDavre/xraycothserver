<?php

	if(session_id()==''){session_start();}
	//var_dump($_REQUEST); exit;
	$_SESSION['scan']['cid'] = $_SESSION['tmp']['cid'];
	$_SESSION['scan']['scanloc'] = $_REQUEST['scanloc'];
	unset($_SESSION['tmp']['cid']);
	






