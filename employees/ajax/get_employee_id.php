<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;

	$nr = $sys_settings['id_start'];
	$sql = "SELECT emp_id FROM ".$cid."_employees WHERE emp_id LIKE '".$_REQUEST['prefix']."%' ORDER BY emp_id DESC LIMIT 1";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$tmp = explode('-',$row['emp_id']);
			$nr = (int)$tmp[1]+1;
		}
	}else{
		//echo mysqli_error($dbc);
	}
	$emp_id = $_REQUEST['prefix'].'-'.sprintf('%04d', $nr);
	
	//var_dump($nr); //exit;
	//var_dump($emp_id); exit;
	ob_clean();
	echo $emp_id;
	
?>
