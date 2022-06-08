<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	foreach($_REQUEST['shiftplan'] as $k=>$v){
		$shiftplan[$v['code']] = $v;
	}


	// echo '<pre>';
	// print_r($_REQUEST['shiftplan']);
	// echo '</pre>';

	// die();
	//var_dump($shiftplan); exit;

	$sql = "UPDATE ".$cid."_leave_time_settings SET shiftplan = '".$dbc->real_escape_string(serialize($shiftplan))."'";
	//echo $sql;
	//exit;
	
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
	
?>














