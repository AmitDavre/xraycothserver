<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	
	if($_COOKIE['admincheck'] =='login')
	{
		echo '1'; // logged in
	}
	else
	{
		echo '2'; // logged out
	}
	
?>