<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	$_REQUEST['price_activities'] = array_values(array_filter($_REQUEST['price_activities']));
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE rego_company_settings SET price_activities = '".$dba->real_escape_string(serialize($_REQUEST['price_activities']))."'";
	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;