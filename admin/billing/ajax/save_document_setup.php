<?

	if(session_id()==''){session_start();}
	ob_start();
	
	include("../../dbconnect/db_connect.php");
	
	$dir = DIR.'admin/uploads/';
	if(!empty($_FILES['logo']['tmp_name'])){
		$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.'inv_logo.'.$ext;
		if(move_uploaded_file($_FILES['logo']['tmp_name'],$filename)){
			$_REQUEST['logo'] = AROOT.'uploads/inv_logo.'.$ext;
		}
	}
	if(!empty($_FILES['stamp']['tmp_name'])){
		$ext = pathinfo($_FILES['stamp']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.'inv_stamp.'.$ext;
		if(move_uploaded_file($_FILES['stamp']['tmp_name'],$filename)){
			$_REQUEST['stamp'] = AROOT.'uploads/inv_stamp.'.$ext;
		}
	}
	if(!empty($_FILES['signature']['tmp_name'])){
		$ext = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.'inv_signature.'.$ext;
		if(move_uploaded_file($_FILES['signature']['tmp_name'],$filename)){
			$_REQUEST['signature'] = AROOT.'uploads/inv_signature.'.$ext;
		}
	}
	//if(isset($_REQUEST['en_description'])){$_REQUEST['en_description'] = serialize($_REQUEST['en_description']);}
	//if(isset($_REQUEST['th_description'])){$_REQUEST['th_description'] = serialize($_REQUEST['th_description']);}
	//var_dump($_FILES); exit;
	//var_dump($_REQUEST); exit;
	
	
	$sql = "UPDATE rego_documents SET ";
		foreach($_REQUEST as $k=>$v){
			$sql .= $k." = '".$dba->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1)." WHERE id = 1";
	
	//var_dump($sql); exit;	
	//ob_clean();
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}
