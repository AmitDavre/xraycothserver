<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	//var_dump($_FILES); exit;
	//var_dump($_REQUEST); exit;

	$dir = 	DIR.$cid.'/documents/';
	if(!empty($_FILES['complogo']['tmp_name'])){
		$ext = pathinfo($_FILES['complogo']['name'], PATHINFO_EXTENSION);		
		$filename = strtolower($_SESSION['rego']['cid']).'_logo.'.$ext;
		if(move_uploaded_file($_FILES['complogo']['tmp_name'],DIR.$cid.'/'.$filename)){
			$_REQUEST['logofile'] = $cid.'/'.$filename;
		}
	}
	
	$sql = "UPDATE ".$cid."_company_settings SET ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k." = '".$dbc->real_escape_string($v)."',";
	}
	$sql = substr($sql,0,-1);
	//echo($sql); //exit;
		
	ob_clean();	
	if($dbc->query($sql)){
		echo 'success';
		//writeToLogfile($_SESSION['rego']['cid'], 'action', 'Update company settings');
	}else{
		echo mysqli_error($dbc);
	}
	
	exit;














