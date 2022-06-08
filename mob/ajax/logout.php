<?php
	if(session_id()==''){session_start();}
	ob_start();
	unset($_SESSION['mobLogincheck']);

	echo '1'
	
?>