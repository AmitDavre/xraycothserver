<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_default_holidays WHERE id = '".$_REQUEST['id']."'"); 
	if($row = $res->fetch_assoc()){
		$data['id'] = $row['id'];
		$data['date'] = date('D d-m-Y', strtotime($row['date']));
		$data['cdate'] = date('D d-m-Y', strtotime($row['cdate']));
		$data['th'] = $row['th'];
		$data['en'] = $row['en'];
	}else{
		echo 'Error';
	}
	//var_dump($data); exit;
	ob_clean();
	echo json_encode($data);
	exit;
?>


















