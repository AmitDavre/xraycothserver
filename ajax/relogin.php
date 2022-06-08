<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../dbconnect/db_connect.php');
	//var_dump($_REQUEST);
	
	if(empty($_REQUEST['repassword'])){
		ob_clean(); 
		echo 'empty'; exit;
	}
	
	$sql = "SELECT password FROM rego_company_users WHERE username = '".$_SESSION['rego']['username']."' AND password = '".hash('sha256', $_REQUEST['repassword'])."'";
	$res = $dbx->query($sql);
	
	ob_clean();
	if($res->num_rows > 0){
		$_SESSION['rego']['timestamp'] = time();
		echo 'success';
	}else{
		echo 'wrong';
	}
   exit;
?>