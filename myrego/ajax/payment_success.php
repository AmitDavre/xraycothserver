<?
	
	//if(session_id()==''){session_start();}
	//ob_start();
	
	$sql = "UPDATE rego_invoices SET status = 2 WHERE inv = '".$_REQUEST['invoice']."'";
	//ob_clean();
	if($dbx->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbx);
	}
