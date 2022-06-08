<a href="destroy.php?m">Destroy session</a><br />
<a href="destroy.php?">Refresh page</a>

<?php
	session_start();
	ob_start();
		ini_set('xdebug.var_display_max_depth', -1);
		ini_set('xdebug.var_display_max_children', -1);
		ini_set('xdebug.var_display_max_data', -1);
	

	if(isset($_GET['m'])){
		session_destroy();
		session_start();
	}
	//var_dump($_SESSION); exit;
	
	echo '<pre>' . print_r($_SESSION, true) . '</pre>';

?>

