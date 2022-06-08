<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	foreach($_REQUEST['shiftplan'] as $k=>$v){
		$shiftplan[$v['code']] = $v;
	}
	//var_dump($work_hrs); exit;

	$sql = "UPDATE rego_default_leave_time_settings SET shiftplan = '".$dba->real_escape_string(serialize($shiftplan))."'";
	//echo $sql;
	//exit;
	
	ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
	exit;
	
?>














