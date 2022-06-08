<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);// exit;



	$sql = "UPDATE rego_consent_letter SET 
		th_identification= '".$dba->real_escape_string($_REQUEST['th_content1'])."',
		en_identification= '".$dba->real_escape_string($_REQUEST['en_content1'])."',
		th_body_text= '".$dba->real_escape_string($_REQUEST['th_content2'])."',
		en_body_text= '".$dba->real_escape_string($_REQUEST['en_content2'])."',
		th_reference= '".$dba->real_escape_string($_REQUEST['th_content3'])."',
		en_reference= '".$dba->real_escape_string($_REQUEST['en_content3'])."'"; 


	ob_clean();
	if($dba->query($sql)){
		$err_msg = 'success';
	}else{
		$err_msg = mysqli_error($dba);
	}
	echo $err_msg;
	exit;