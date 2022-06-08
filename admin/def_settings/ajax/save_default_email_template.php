<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit; 
	
	$sql = "INSERT INTO rego_default_email_templates (name, subject_".$lang.", body_".$lang.", description_".$lang.") VALUES (
		'".$dba->real_escape_string($_REQUEST['name'])."', 
		'".$dba->real_escape_string($_REQUEST['subject_'.$lang])."',  
		'".$dba->real_escape_string($_REQUEST['body_'.$lang])."',  
		'".$dba->real_escape_string($_REQUEST['description_'.$lang])."')  
		ON DUPLICATE KEY UPDATE
		subject_".$lang." = VALUES(subject_".$lang."),
		body_".$lang." = VALUES(body_".$lang."),
		description_".$lang." = VALUES(description_".$lang.")";
	//var_dump($sql); exit; 
	//ob_clean();
	if($res = $dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	
?>
