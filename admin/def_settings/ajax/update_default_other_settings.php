<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	if(!isset($_REQUEST['bank_codes'])){$_REQUEST['bank_codes'] = array();}
	if(!isset($_REQUEST['account_codes'])){$_REQUEST['account_codes'] = array();}
	if(!isset($_REQUEST['allocations'])){$_REQUEST['allocations'] = array();}
	
	$sql = "UPDATE rego_default_settings SET 
		support_email = '".$dba->real_escape_string($_REQUEST['support_email'])."', 
		account_codes = '".$dba->real_escape_string(serialize($_REQUEST['account_codes']))."', 
		allocations = '".$dba->real_escape_string(serialize($_REQUEST['allocations']))."', 
		bank_codes = '".$dba->real_escape_string(serialize($_REQUEST['bank_codes']))."'"; 
		//echo $sql; exit;
	
	ob_clean();
	if($dba->query($sql)){
			echo 'success';
	}else{
			echo mysqli_error($dba);
	}
	//exit;
		
	
?>