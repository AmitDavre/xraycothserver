<?
	if(session_id()==''){session_start(); ob_start();}

	// setcookie("admincheck", "logout", time() + (86400 * 30) , '/', '.pkfpeople.com' );

	include('../dbconnect/db_connect.php');

	$sql103 = "UPDATE rego_users SET logged_in_status = NULL , authenticated = NULL  , authenticated_code= NULL WHERE username = '".$_SESSION['RGadmin']['username']."'";

	$res103 = $dba->query($sql103);

	unset($_SESSION['RGadmin']);
	unset($_SESSION['adminLogincheck']);



	echo '../admin/index.php?mn=1';
   exit;
?>
