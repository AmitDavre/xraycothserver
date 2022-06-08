<?

	if(session_id()==''){session_start();}
	ob_start();
	include("../../../dbconnect/db_connect.php");
	include(DIR.'files/functions.php');
	//var_dump($_FILES); //exit;
	//var_dump($_REQUEST); exit;

	$_REQUEST['th_addr_detail'] = serialize($_REQUEST['th_addr_detail']);
	$_REQUEST['en_addr_detail'] = serialize($_REQUEST['en_addr_detail']);
	if(isset($_REQUEST['banks'])){
		$_REQUEST['banks'] = serialize($_REQUEST['banks']);
	}else{
		$_REQUEST['banks'] = '';
	}
	if(isset($_REQUEST['sso_codes'])){
		$_REQUEST['sso_codes'] = serialize($_REQUEST['sso_codes']);
	}else{
		$_REQUEST['sso_codes'] = '';
	}

	$dir = 	DIR.$cid.'/documents/';
	if(!empty($_FILES['complogo']['tmp_name'])){
		$ext = pathinfo($_FILES['complogo']['name'], PATHINFO_EXTENSION);		
		$filename = strtolower($_SESSION['rego']['cid']).'_logo.'.$ext;
		if(move_uploaded_file($_FILES['complogo']['tmp_name'],DIR.$cid.'/'.$filename)){
			$_REQUEST['logofile'] = $cid.'/'.$filename;
		}
	}
	//var_dump($filename); exit;
	if(!empty($_FILES['dig_stamp']['tmp_name'])){
		$ext = pathinfo($_FILES['dig_stamp']['name'], PATHINFO_EXTENSION);		
		$filename = strtolower($_SESSION['rego']['cid']).'_stamp.'.$ext;
		if(move_uploaded_file($_FILES['dig_stamp']['tmp_name'],DIR.$cid.'/'.$filename)){
			$_REQUEST['dig_stamp'] = $cid.'/'.$filename;
		}
	}
	if(!empty($_FILES['dig_signature']['tmp_name'])){
		$ext = pathinfo($_FILES['dig_signature']['name'], PATHINFO_EXTENSION);		
		$filename = strtolower($_SESSION['rego']['cid']).'_signature.'.$ext;
		if(move_uploaded_file($_FILES['dig_signature']['tmp_name'],DIR.$cid.'/'.$filename)){
			$_REQUEST['dig_signature'] = $cid.'/'.$filename;
		}
	}
	if(!empty($_FILES['bus_reg']['tmp_name'])){
		$ext = pathinfo($_FILES['bus_reg']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_business_registration.'.$ext;	
		if(move_uploaded_file($_FILES['bus_reg']['tmp_name'],$dir.$filename)){
			$_REQUEST['bus_reg'] = $filename;
		}
	}
	if(!empty($_FILES['comp_affi']['tmp_name'])){
		$ext = pathinfo($_FILES['comp_affi']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_company_affidavit.'.$ext;	
		if(move_uploaded_file($_FILES['comp_affi']['tmp_name'],$dir.$filename)){
			$_REQUEST['comp_affi'] = $filename;
		}
	}
	if(!empty($_FILES['house_reg']['tmp_name'])){
		$ext = pathinfo($_FILES['house_reg']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_house_registration.'.$ext;	
		if(move_uploaded_file($_FILES['house_reg']['tmp_name'],$dir.$filename)){
			$_REQUEST['house_reg'] = $filename;
		}
	}
	if(!empty($_FILES['vat_reg']['tmp_name'])){
		$ext = pathinfo($_FILES['vat_reg']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_vat_registration.'.$ext;	
		if(move_uploaded_file($_FILES['vat_reg']['tmp_name'],$dir.$filename)){
			$_REQUEST['vat_reg'] = $filename;
		}
	}
	if(!empty($_FILES['socsec_fund']['tmp_name'])){
		$ext = pathinfo($_FILES['socsec_fund']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_social_security_fund.'.$ext;	
		if(move_uploaded_file($_FILES['socsec_fund']['tmp_name'],$dir.$filename)){
			$_REQUEST['socsec_fund'] = $filename;
		}
	}
	if(!empty($_FILES['bankbook']['tmp_name'])){
		$ext = pathinfo($_FILES['bankbook']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_copy_bankbook.'.$ext;	
		if(move_uploaded_file($_FILES['bankbook']['tmp_name'],$dir.$filename)){
			$_REQUEST['bankbook'] = $filename;
		}
	}
	if(!empty($_FILES['passfs']['tmp_name'])){
		$ext = pathinfo($_FILES['passfs']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_power_attorney_ss_fund.'.$ext;	
		if(move_uploaded_file($_FILES['passfs']['tmp_name'],$dir.$filename)){
			$_REQUEST['passfs'] = $filename;
		}
	}
	if(!empty($_FILES['paw_tax']['tmp_name'])){
		$ext = pathinfo($_FILES['paw_tax']['name'], PATHINFO_EXTENSION);
		$filename = strtolower($_SESSION['rego']['cid']).'_power_attorney_withholding_tax.'.$ext;	
		if(move_uploaded_file($_FILES['paw_tax']['tmp_name'],$dir.$filename)){
			$_REQUEST['paw_tax'] = $filename;
		}
	}
	if(!empty($_FILES['attach1']['tmp_name'])){
		$filename = strtolower($_SESSION['rego']['cid'].'_'.$_FILES['attach1']['name']);
		if(move_uploaded_file($_FILES['attach1']['tmp_name'],$dir.$filename)){
			$_REQUEST['attach1'] = $filename;
		}
	}
	if(!empty($_FILES['attach2']['tmp_name'])){
		$filename = strtolower($_SESSION['rego']['cid'].'_'.$_FILES['attach2']['name']);
		if(move_uploaded_file($_FILES['attach2']['tmp_name'],$dir.$filename)){
			$_REQUEST['attach2'] = $filename;
		}
	}
	if(!empty($_FILES['attach3']['tmp_name'])){
		$filename = strtolower($_SESSION['rego']['cid'].'_'.$_FILES['attach3']['name']);
		if(move_uploaded_file($_FILES['attach3']['tmp_name'],$dir.$filename)){
			$_REQUEST['attach3'] = $filename;
		}
	}
	//var_dump($_REQUEST); //exit;

	if(isset($_REQUEST['website_status']))
	{
		$_REQUEST['website_status'] = '1';
	}
	else
	{
		$_REQUEST['website_status'] = '0';
	}	

	if(isset($_REQUEST['contact_info_status']))
	{
		$_REQUEST['contact_info_status'] = '1';
	}
	else
	{
		$_REQUEST['contact_info_status'] = '0';
	}
	// echo '<pre>';
	// print_r($_REQUEST);
	// echo '</pre>';

	// die();
	
	$sql = "INSERT INTO ".$cid."_entities_data (";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k.', ';
	}
	$sql = substr($sql,0,-2).") VALUES (";
	foreach($_REQUEST as $k=>$v){
		$sql .= "'".$dbc->real_escape_string($v)."', ";
	}
	$sql = substr($sql,0,-2).") ON DUPLICATE KEY UPDATE ";
	foreach($_REQUEST as $k=>$v){
		$sql .= $k."=VALUES(".$k."), ";
	}
	$sql = substr($sql,0,-2);
	//echo($sql); exit;
	
	if($dbc->query($sql)){
		ob_clean();	
		echo 'success';
	}else{
		ob_clean();	
		echo mysqli_error($dbc);
	}
	
	
	
	
	
	
	
	
	
	
	
	












