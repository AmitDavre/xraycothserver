<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	$sql = "SELECT * FROM rego_all_users WHERE username = '".$_REQUEST['user']."'";
	if($res = $dba->query($sql)){
		if($res->num_rows > 0){
			ob_clean(); echo 'exist';
		}else{
			ob_clean(); echo 'ok';
		}
	}else{
		ob_clean(); echo mysqli_error($dba);
	}
