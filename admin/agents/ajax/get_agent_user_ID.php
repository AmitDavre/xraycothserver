<?
	if(session_id()==''){session_start(); ob_start();}
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	//$_REQUEST['id'] = '100';
	$id = 1000;
	$data = array();
	if($res = $dba->query("SELECT * FROM rego_agents ORDER by agent_id DESC LIMIT 1")){
		if($row = $res->fetch_assoc()){
			if(!empty($row['agent_id'])){$id = 'AG0'.(substr($row['agent_id'],2)+1);}
		}
	}
	//var_dump($id); exit;
	ob_clean();
	echo $id;
	exit;
?>


















