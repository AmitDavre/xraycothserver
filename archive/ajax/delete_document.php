<?php
	if(session_id()==''){session_start();}
	include("../../dbconnect/db_connect.php");
	$path = DIR.$cid.'/archive/';
	
	$sql = "SELECT filename FROM ".$cid."_documents WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		$row = $res->fetch_assoc();
		$filename = $path.$row['filename'];
	}else{
		echo mysqli_error($dbc);
	}
	//var_dump($filename);
	
	$sql = "DELETE FROM ".$cid."_documents WHERE id = '".$_REQUEST['id']."'";
	//echo $sql; exit;
	if($dbc->query($sql)){
		if(file_exists($filename)) {
			unlink($filename);
		}
		echo 'ok';
	}else{
		echo mysqli_error($dbc);
	}
?>
