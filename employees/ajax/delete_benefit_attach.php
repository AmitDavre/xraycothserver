<?
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//var_dump($_REQUEST); exit;

	$dir = DIR.$cid.'/'.$_REQUEST['field'].'/';
	$attachments = array();
	$sql = "SELECT attachments FROM ".$cid."_employee_".$_REQUEST['field']." WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
		$attachments = unserialize($row['attachments']);
	}
	//var_dump($attachments); 

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
	
	$sql = "UPDATE ".$cid."_employee_".$_REQUEST['field']." SET attachments = '".$dbc->real_escape_string($attachments)."' WHERE id = '".$_REQUEST['id']."'";
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
?>














