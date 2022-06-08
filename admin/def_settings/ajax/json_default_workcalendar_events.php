<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST);	exit;
	
	$json = array();
	$holidays = array();
	
	$res = $dba->query("SELECT * FROM rego_default_holidays");
	while($row = $res->fetch_assoc()){
		$holidays[] = $row;
	}
	//var_dump($holidays); //exit;
	
	foreach($holidays as $k=>$v){
		$json[] = array(
			'id'=>$k,
			'type'=>'hol',
			'start'=>date('Y-m-d', strtotime($v['cdate'])),
			'title'=>$v[$_SESSION['RGadmin']['lang']],
			'icon'=>'fa-times',
			'background'=>'red',
			'color'=>'orange');
	}
	//var_dump($data); exit;
	ob_clean();
 	echo json_encode($json);
	exit;

?>