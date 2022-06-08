<?php
	
	if(session_id()==''){session_start();}
	$_SESSION['rego']['lang'] = $_REQUEST['lng'];
	setcookie('rego_lang', $_REQUEST['lng'], time()+31556926 ,'/');
	//echo $_REQUEST['lng'];
?>
