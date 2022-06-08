<?
	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");

	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';
	
	$dir = '../uploads/';
	
	$_REQUEST['logo'] = '';
	if(!empty($_FILES['logo']['tmp_name'])){
		$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.'doc_logo_'.date(his).'.'.$ext;
		if(move_uploaded_file($_FILES['logo']['tmp_name'],'../../'.$filename)){
			$_REQUEST['logo'] = $filename;
		}
	}

	if($_REQUEST['id'] != 'n'){

		if($_REQUEST['logo'] != ''){
			$logoup = "logo = '".$_REQUEST['logo']."',";
		}else{ $logoup = '';}

		$sql = "UPDATE ".$cid."_document_templates SET `company`= '".$_REQUEST['company']."', `address`= '".$_REQUEST['address']."', ".$logoup." `remark`= '".$_REQUEST['remark']."' WHERE id='".$_REQUEST['id']."'";
	}else{

		$sql = "INSERT INTO ".$cid."_document_templates (`company`, `address`, `logo`, `remark`) VALUES ('".$_REQUEST['company']."', '".$_REQUEST['address']."', '".$_REQUEST['logo']."', '".$_REQUEST['remark']."')";
	}

	//echo $sql; exit;

	ob_clean();
	if($dbc->query($sql)){
		//ob_clean();
		echo 'success';
	}else{
		//ob_clean();
		echo mysqli_error($dbc);
	}
?>














