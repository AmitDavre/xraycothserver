<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../dbconnect/db_connect.php");
	
	$sql = "UPDATE rego_purchase_draft SET status = 0 WHERE cid = '".$cid."'";
	if($dbx->query($sql)){
		ob_clean(); echo 'success';
	}else{
		ob_clean(); echo mysqli_error($dbx);
	}
