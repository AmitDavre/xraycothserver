<?
	if(session_id()==''){session_start(); ob_start();}
	include("../dbconnect/db_connect.php");
	
	$dir = DIR.'admin/uploads/';
	if(!empty($_FILES['complogo']['tmp_name'])){
		$ext = pathinfo($_FILES['complogo']['name'], PATHINFO_EXTENSION);		
		$filename = $dir.'rego_logo.'.$ext;
		$dbfile = 'uploads/rego_logo.'.$ext;
		if(move_uploaded_file($_FILES['complogo']['tmp_name'],$filename)){
			$dba->query("UPDATE rego_company_settings SET complogo = '".$dbfile."'"); 
		}
	}
	//var_dump($_FILES); exit;

	$sql = "UPDATE rego_company_settings SET 
			compname_th = '".$dba->real_escape_string($_REQUEST['compname_th'])."', 
			compname_en = '".$dba->real_escape_string($_REQUEST['compname_en'])."', 
			phone = '".$dba->real_escape_string($_REQUEST['phone'])."', 
			info_mail = '".$dba->real_escape_string($_REQUEST['info_mail'])."', 
			admin_mail = '".$dba->real_escape_string($_REQUEST['admin_mail'])."', 
			support_mail = '".$dba->real_escape_string($_REQUEST['support_mail'])."', 
			agents_mail = '".$dba->real_escape_string($_REQUEST['agents_mail'])."', 
			regno = '".$dba->real_escape_string($_REQUEST['regno'])."',
			max_employees = '".$dba->real_escape_string($_REQUEST['maxEmp'])."', 
			language = '".$dba->real_escape_string($_REQUEST['preflang'])."', 
			logtime = '".$dba->real_escape_string($_REQUEST['logtime'])."', 
			address_th = '".$dba->real_escape_string($_REQUEST['address_th'])."', 
			address_en = '".$dba->real_escape_string($_REQUEST['address_en'])."', 
			latitude = '".$dba->real_escape_string($_REQUEST['latitude'])."', 
			longitude = '".$dba->real_escape_string($_REQUEST['longitude'])."', 
			contacts = '".$dba->real_escape_string(serialize($_REQUEST['contacts']))."'";
	//ob_clean();	
	if($dba->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dba);
	}





