<?php
	if(session_id()==''){session_start();}
	if($_SESSION['agent']['lang'] == 'en'){$_SESSION['agent']['lang'] = 'th';}else{$_SESSION['agent']['lang'] = 'en';}
	setcookie('lang', $_SESSION['agent']['lang'], time()+31556926 ,'/');
	echo $_SESSION['agent']['lang'];