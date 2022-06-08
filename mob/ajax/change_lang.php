<?php
	if(session_id()==''){session_start();}
	if($_SESSION['rego']['lang'] == 'en'){$_SESSION['rego']['lang'] = 'th';}else{$_SESSION['rego']['lang'] = 'en';}
	setcookie('lang', $_SESSION['rego']['lang'], time()+31556926 ,'/');
	echo $_SESSION['rego']['lang'];