<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	
	//var_dump($_REQUEST); exit;
	
	$sql = "UPDATE rego_company_settings SET standard = '".$dba->real_escape_string(serialize($_REQUEST['rego']))."'";
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
