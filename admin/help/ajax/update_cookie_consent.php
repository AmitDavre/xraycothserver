<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);// exit;
	
	$sql = "UPDATE rego_cookie_consent SET 
		th_content = '".$dba->real_escape_string($_REQUEST['th_content'])."',
		en_content = '".$dba->real_escape_string($_REQUEST['en_content'])."'"; 
	
	$sqlterms = "UPDATE rego_all_users SET  cookie_consent_change = '1'"; 
	$dba->query($sqlterms);

	$sqlterms1 = "UPDATE rego_users SET  cookie_consent_change = '1'"; 
	$dba->query($sqlterms1);


	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;