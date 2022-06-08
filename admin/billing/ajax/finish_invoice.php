<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	$cid = strtolower(str_replace(' ', '', $_REQUEST['cid']));
	//var_dump($_REQUEST); //exit;
	
	$sql = "UPDATE rego_invoices SET status = 1 WHERE id = ".$_REQUEST['id'];
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}

	$res = $dba->query("SELECT * FROM rego_customers WHERE clientID = '".$cid."'");
	$row = $res->fetch_assoc();
	$start = $row['period_end'];
	$end = date('d-m-Y', strtotime('+12 months', strtotime($row['period_end'])));
	
	$sql = "UPDATE rego_customers SET 
		version = '".$_REQUEST['version']."',
		period_start = '".$start."',
		period_end = '".$end."',
		status = '1' 
		WHERE clientID = '".$cid."'";
		
	//var_dump($sql); exit;
	ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





