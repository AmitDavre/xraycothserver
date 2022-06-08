<?php
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	//include(DIR.'files/functions.php');
	//writeToLogfile($_SESSION['rego']['cid'], 'log', 'Log-OUT');

	// update in regoallusers table browser name and log out value 

	$userID = $_SESSION['rego']['ref'];

	$my_dbaname = $prefix.'admin';


	$dba = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dba,"utf8");
	if($dba->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dba->connect_errno.') '.$dba->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}


	// $sql1111 = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
	// if($res111 = $dba->query($sql1111)){
	// 	if($all_userssss = $res111->fetch_assoc()){


	// 		$usernameCheckDAta = $all_userssss['rego'];
	// 		$passwordCheckDAta = $all_userssss['rego_lang'];
	// 	}
	// }

	// if($usernameCheckDAta != '1')
	// {
	// 	unset($_COOKIE['username']); 
	// }	
	// if($passwordCheckDAta != '1')
	// {
	// 	unset($_COOKIE['password']); 
	// }

	$sql102 = "UPDATE rego_all_users SET browser_name = '".strtolower($_REQUEST['browsername'])."' , logged_in = 'system' , logged_out = 'session' WHERE id = '".$userID."'";

	$res102 = $dba->query($sql102);

	$sql103 = "UPDATE rego_all_users SET logged_in_status = NULL, authenticated = NULL , authenticated_code= NULL , mob_show = NULL WHERE username = '".$_SESSION['rego']['username']."'";

	$res103 = $dba->query($sql103);

	// echo '<pre>';
	// print_r($_SESSION['rego']['ref']);
	// echo '</pre>';


	unset($_SESSION['rego']);
	unset($_SESSION['userLogincheck']);
	// setcookie("usercheck", "logout", time() + (86400 * 30) , '/', '.pkfpeople.com' );
	
?>
