<?
	if(session_id()==''){session_start();} 
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "UPDATE rego_customers SET status = '".$_REQUEST['status']."' WHERE clientID = '".$_REQUEST['id']."'";
	$dba->query($sql); 
	if($dba->query($sql)){
		/*$my_dbcname = $prefix.strtolower($_REQUEST['id']);	
		$dbc = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
		mysqli_set_charset($dbc,"utf8");
		$dbname = strtolower($_REQUEST['id']).'_users';
		$sql = "UPDATE ".$dbname." SET status = '".$_REQUEST['status']."'";
		$dbc->query($sql);
		$err_msg = '<div class="msg_error" style="margin:0;">Error : '.mysqli_error($dba).'</div>';*/
		//echo 'ok';
	}else{
		//echo mysqli_error($dba);
	}
?>
