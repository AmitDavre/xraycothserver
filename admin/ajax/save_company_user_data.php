<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	$id = $_REQUEST['id'];
	unset($_REQUEST['id']);
	//var_dump($_REQUEST); //exit;
	
	//$password = $_REQUEST['password'];
	//if(isset($_REQUEST['password'])){$_REQUEST['password'] = hash('sha256', $_REQUEST['password']);}
	
	$sql = "UPDATE rego_company_users SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$v."', ";
	}
	$sql = substr($sql,0,-2);
	$sql .= " WHERE id = ".$id;
	//echo $sql;
	//exit;
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
?>














