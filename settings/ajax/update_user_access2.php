<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	if(!isset($_REQUEST['access'])){$_REQUEST['access'] = '';}
	if($_REQUEST['field'] == 'shiftteams'){
		if(!empty($_REQUEST['access'])){
			$_REQUEST['access'] = implode(',',$_REQUEST['access']);
		}
	}
	//var_dump($_REQUEST); exit;
	
	if($res = $dbx->query("UPDATE rego_company_users SET 
		".$_REQUEST['field']." = '".$_REQUEST['access']."' WHERE id = '".$_REQUEST['id']."'")){
	}else{
		echo mysqli_error($dbx);
	}












