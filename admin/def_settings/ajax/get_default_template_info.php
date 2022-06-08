<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$res = $dba->query("SELECT * FROM rego_default_email_templates WHERE name = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['name'] = $row['name'];
		$data['subject'] = $row['subject_'.$lang];
		$data['body'] = $row['body_'.$lang];
		$data['description'] = $row['description_'.$lang];
	}else{
		$data = array();
		echo mysqli_error($dba);
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
