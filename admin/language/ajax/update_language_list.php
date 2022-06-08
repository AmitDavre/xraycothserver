<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); //exit;
	
	$sql = "INSERT INTO rego_application_language (code,en,th) VALUES (
		'".$dba->real_escape_string($_REQUEST['code'])."', 
		'".$dba->real_escape_string($_REQUEST['en'])."', 
		'".$dba->real_escape_string($_REQUEST['th'])."') 
		ON DUPLICATE KEY UPDATE 
		code = VALUES(code),
		en = VALUES(en),
		th = VALUES(th)";
	//echo $sql; //exit;
	
	ob_clean();	
	if($dba->query($sql)){
		echo 'ok';
	}else{
		echo mysqli_error($dba);
	}
