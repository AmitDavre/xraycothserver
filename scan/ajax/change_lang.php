<?php
	if(session_id()==''){session_start();}
	if($_SESSION['scan']['lang'] == 'en'){$_SESSION['scan']['lang'] = 'th';}else{$_SESSION['scan']['lang'] = 'en';}
	setcookie('scanlang', $_SESSION['scan']['lang'], time()+31556926 ,'/');
	echo $_SESSION['scan']['lang'];