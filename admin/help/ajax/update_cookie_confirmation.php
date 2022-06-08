<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);// exit;

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// die();
	
	$sql = "UPDATE rego_cookie_confirmation SET 
		th_content = '".$dba->real_escape_string($_REQUEST['body_th'])."',
		en_content = '".$dba->real_escape_string($_REQUEST['body_en'])."'"; 
	
	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;