<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump(strtolower($_REQUEST['emp_id'])); exit;
	
	$exist = 0;
	$sql = "SELECT emp_id FROM ".$cid."_employees";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if(strtolower($row['emp_id']) == strtolower($_REQUEST['emp_id'])){$exist = 1; break;}
		}
	}else{
		//echo mysqli_error($dbc);
	}
	ob_clean();
	echo $exist;
	//var_dump($exist); exit;
	exit;
	
?>
