<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include("../dbconnect/db_connect.php");
	//var_dump($_REQUEST); exit;
	
	if($sys_settings['demo'] < 2){
		$sql = "UPDATE ".$_SESSION['rego']['cid']."_sys_settings SET 
			pr_startdate = '".$_REQUEST['prMonth']."', 
			cur_month = '".date('n', strtotime($_REQUEST['prMonth']))."', 
			demo = 2";
		if($dbc->query($sql)){
			echo 'success';
		}else{
			echo mysqli_error($dbc);
		}
		//exit;
	}

	$path[] = DIR.$cid.'/approvals/*';
	$path[] = DIR.$cid.'/archive/*';
	$path[] = DIR.$cid.'/documents/*';
	$path[] = DIR.$cid.'/employees/*';
	$path[] = DIR.$cid.'/gov_forms/*';
	$path[] = DIR.$cid.'/leave/*';
	$path[] = DIR.$cid.'/payroll/*';
	$path[] = DIR.$cid.'/QRcode/*';
	$path[] = DIR.$cid.'/reports/*';
	$path[] = DIR.$cid.'/time/*';
	$path[] = DIR.$cid.'/uploads/*';
	foreach($path as $v){
		array_map('unlink', array_filter((array) glob($v)));
	}
	//var_dump($path); exit;

	if(!$dbc->query("TRUNCATE ".$cid."_approvals")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_attendance")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_documents")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_assets")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_career")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_discipline")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_equipment")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_events")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_log")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_medical")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_privileges")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_employees_training")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_historic_data")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_leaves")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_leaves_data")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_monthly_shiftplans_".$_SESSION['rego']['cur_year'])){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$_SESSION['rego']['payroll_dbase'])){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_scanfiles")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_tax_simulation")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_workpermit")){echo mysqli_error($dbc);}
	
	if(!$dbc->query("TRUNCATE ".$cid."_")){echo mysqli_error($dbc);}
	
	/*$sql = "UPDATE ".$_SESSION['rego']['cid']."_sys_settings SET demo = 2";
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}*/
	ob_clean();
	echo 'success';
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
