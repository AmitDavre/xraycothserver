<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_invoices WHERE id = '".$_REQUEST['id']."'");
	if($row = $res->fetch_assoc()){
		$data = $row;
		$data['body'] = unserialize($data['body']);
	}
	//var_dump($data);
	echo json_encode($data);
