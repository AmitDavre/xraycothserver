<?

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	
	$dir = DIR.$cid.'/uploads/';
	
	$filename = '';
	$sql = "SELECT filename FROM ".$cid."_scanfiles WHERE id = '".$_REQUEST['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$filename = $row['filename'];
		}
	}
	//var_dump($filename); 
	if($filename){
		unlink($dir.$filename);
	}
	
	$sql = "DELETE FROM ".$cid."_scanfiles WHERE id = '".$_REQUEST['id']."'";
	$deleteSql2 = "DELETE FROM ".$cid."_scandata WHERE filename = '".$filename."'";
	$deleteSql1 = "DELETE FROM ".$cid."_metascandata WHERE filename = '".$filename."'";
	$deleteSql3 = "DELETE FROM ".$cid."_attendance WHERE filename = '".$filename."'";
	$res3 = $dbc->query($deleteSql1);
	$res4 = $dbc->query($deleteSql2);
	$res5 = $dbc->query($deleteSql3);
	ob_clean();
	if($res = $dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
	exit;
