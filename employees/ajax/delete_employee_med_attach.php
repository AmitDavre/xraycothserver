<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;

	$dir = DIR.$cid.'/medical/';
	$attachments = array();
	$sql = "SELECT med_attachments FROM ".$cid."_employees WHERE emp_id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
		$attachments = unserialize($row['med_attachments']);
	}
	//var_dump($attachments); exit;

	$filename = $attachments[$_REQUEST['key']];
	var_dump($filename); 
	unlink($dir.$filename);
	unset($attachments[$_REQUEST['key']]);
	if($attachments){
		$attachments = serialize($attachments);
	}else{
		$attachments = '';
	}
	//var_dump($attachments); exit;
	
	$sql = "UPDATE ".$cid."_employees SET med_attachments = '".$dbc->real_escape_string($attachments)."' WHERE emp_id = '".$_REQUEST['id']."'";
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
?>














