<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);// exit;
	
	$sql = "UPDATE rego_helpfiles SET 
		th_content = '".$dba->real_escape_string($_REQUEST['th_content'])."',
		en_content = '".$dba->real_escape_string($_REQUEST['en_content'])."' 
		WHERE page = '".$_REQUEST['page']."'";
	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;