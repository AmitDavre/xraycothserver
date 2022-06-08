<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$sql = "SELECT * FROM rego_users WHERE username = '".$_REQUEST['username']."'";
	
	if($res = $dba->query($sql)){
		if($res->num_rows > 0){
			echo 'exist';
		}else{
			echo 'ok';
		}
	}else{
		echo mysqli_error($dba);
	}
	//echo $sql;
	//ob_clean();
	exit;
?>