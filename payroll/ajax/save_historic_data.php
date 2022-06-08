<?
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	//$_REQUEST['emp_id'] = array_unique($_REQUEST['emp_id']);
	//var_dump($_REQUEST); exit;
	
	if(!isset($_REQUEST['month'])){echo 'No data to save'; exit;}
	
	$data = array(); 
	foreach($_REQUEST['month'] as $k=>$v){
		$tot_ot = 0;
		$tot_deduct = 0;
		$tot_fix_allow = 0;
		$tot_var_allow = 0;
		$tot_tax_allow = 0;
		$tot_fix_deduct = 0;
		$tot_var_deduct = 0;
		$tot_fix = 0;
		$tot_var = 0;
		
		$data[$_REQUEST['emp_id'][$k].$v]['id'] = $_REQUEST['emp_id'][$k].$v;
		$data[$_REQUEST['emp_id'][$k].$v]['emp_id'] = $_REQUEST['emp_id'][$k];
		$data[$_REQUEST['emp_id'][$k].$v]['month'] = $v;
		
		if(isset($_REQUEST['salary'])){
			$data[$_REQUEST['emp_id'][$k].$v]['salary'] = $_REQUEST['salary'][$k];
			$tot_fix += $_REQUEST['salary'][$k];
		}
		
		if(isset($_REQUEST['ot1b'])){
			$data[$_REQUEST['emp_id'][$k].$v]['ot1b'] = $_REQUEST['ot1b'][$k];
			$tot_ot += $_REQUEST['ot1b'][$k];
		}
		if(isset($_REQUEST['ot15b'])){
			$data[$_REQUEST['emp_id'][$k].$v]['ot15b'] = $_REQUEST['ot15b'][$k];
			$tot_ot += $_REQUEST['ot15b'][$k];
		}
		
		if(isset($_REQUEST['ot2b'])){
			$data[$_REQUEST['emp_id'][$k].$v]['ot2b'] = $_REQUEST['ot2b'][$k];
			$tot_ot += $_REQUEST['ot2b'][$k];
		}
		
		if(isset($_REQUEST['ot3b'])){
			$data[$_REQUEST['emp_id'][$k].$v]['ot3b'] = $_REQUEST['ot3b'][$k];
			$tot_ot += $_REQUEST['ot3b'][$k];
		}
		
		if(isset($_REQUEST['ootb'])){
			$data[$_REQUEST['emp_id'][$k].$v]['ootb'] = $_REQUEST['ootb'][$k];
			$tot_ot += $_REQUEST['ootb'][$k];
		}
		$tot_var += $tot_ot;
		$data[$_REQUEST['emp_id'][$k].$v]['total_otb'] = $tot_ot;
		
		if(isset($_REQUEST['fix_allow'])){
			foreach($_REQUEST['fix_allow'] as $ak=>$av){
				$data[$_REQUEST['emp_id'][$k].$v]['fix_allow_'.$ak] = $av[$k];
				$tot_fix_allow += $av[$k];
				$tot_tax_allow += $av[$k];
				$tot_fix += $av[$k];
			}
		}
		$data[$_REQUEST['emp_id'][$k].$v]['total_fix_allow'] = $tot_fix_allow;

		if(isset($_REQUEST['var_allow'])){
			foreach($_REQUEST['var_allow'] as $ak=>$av){
				$data[$_REQUEST['emp_id'][$k].$v]['var_allow_'.$ak] = $av[$k];
				$tot_var_allow += $av[$k];
				$tot_tax_allow += $av[$k];
				$tot_var += $av[$k];
			}
		}
		$data[$_REQUEST['emp_id'][$k].$v]['total_var_allow'] = $tot_var_allow;
		
		$data[$_REQUEST['emp_id'][$k].$v]['total_tax_allow'] = $tot_tax_allow;

		if(isset($_REQUEST['tax_by_company'])){
			$data[$_REQUEST['emp_id'][$k].$v]['tax_by_company'] = $_REQUEST['tax_by_company'][$k];
			$tot_var += $_REQUEST['tax_by_company'][$k];
		}

		if(isset($_REQUEST['sso_by_company'])){
			$data[$_REQUEST['emp_id'][$k].$v]['sso_by_company'] = $_REQUEST['sso_by_company'][$k];
			$tot_var += $_REQUEST['sso_by_company'][$k];
		}

		if(isset($_REQUEST['other_income'])){
			$data[$_REQUEST['emp_id'][$k].$v]['other_income'] = $_REQUEST['other_income'][$k];
			$tot_var += $_REQUEST['other_income'][$k];
		}
		
		if(isset($_REQUEST['fix_deduct_before'])){
			$data[$_REQUEST['emp_id'][$k].$v]['fix_deduct_before'] = $_REQUEST['fix_deduct_before'][$k];
			$tot_fix -= $_REQUEST['fix_deduct_before'][$k];
		}
		
		if(isset($_REQUEST['var_deduct_before'])){
			$data[$_REQUEST['emp_id'][$k].$v]['var_deduct_before'] = $_REQUEST['var_deduct_before'][$k];
			$tot_var -= $_REQUEST['var_deduct_before'][$k];
		}
		
		if(isset($_REQUEST['tot_deduct_before'])){
			$data[$_REQUEST['emp_id'][$k].$v]['tot_deduct_before'] = $_REQUEST['tot_deduct_before'][$k];
			$tot_deduct += $_REQUEST['tot_deduct_before'][$k];
		}
		
		if(isset($_REQUEST['tot_deduct_after'])){
			$data[$_REQUEST['emp_id'][$k].$v]['tot_deduct_after'] = $_REQUEST['tot_deduct_after'][$k];
			$tot_deduct += $_REQUEST['tot_deduct_after'][$k];
		}
		
		if(isset($_REQUEST['social'])){
			$data[$_REQUEST['emp_id'][$k].$v]['social'] = $_REQUEST['social'][$k];
			$tot_deduct += $_REQUEST['social'][$k];
		}
		
		if(isset($_REQUEST['social_com'])){
			$data[$_REQUEST['emp_id'][$k].$v]['social_com'] = $_REQUEST['social_com'][$k];
			//$tot_deduct += $_REQUEST['social_com'][$k];
		}
		
		if(isset($_REQUEST['pvf_employee'])){
			$data[$_REQUEST['emp_id'][$k].$v]['pvf_employee'] = $_REQUEST['pvf_employee'][$k];
			$tot_deduct += $_REQUEST['pvf_employee'][$k];
		}
		
		if(isset($_REQUEST['pvf_employer'])){
			$data[$_REQUEST['emp_id'][$k].$v]['pvf_employer'] = $_REQUEST['pvf_employer'][$k];
			//$tot_deduct += $_REQUEST['pvf_employee'][$k];
		}
		
		if(isset($_REQUEST['psf_employee'])){
			$data[$_REQUEST['emp_id'][$k].$v]['psf_employee'] = $_REQUEST['psf_employee'][$k];
			$tot_deduct += $_REQUEST['psf_employee'][$k];
		}
		
		if(isset($_REQUEST['tax'])){
			$data[$_REQUEST['emp_id'][$k].$v]['tax'] = $_REQUEST['tax'][$k];
			//$data[$_REQUEST['emp_id'][$k].$v]['tax_month'] = $_REQUEST['tax'][$k];
			$tot_deduct += $_REQUEST['tax'][$k];
		}
		
		$data[$_REQUEST['emp_id'][$k].$v]['tot_deductions'] = $tot_deduct;
		
		$data[$_REQUEST['emp_id'][$k].$v]['tot_fix_income'] = $tot_fix;
		//$data[$_REQUEST['emp_id'][$k].$v]['tot_ytd_income'] = $tot_fix;
		$data[$_REQUEST['emp_id'][$k].$v]['tot_var_income'] = $tot_var;
		$data[$_REQUEST['emp_id'][$k].$v]['gross_income'] = $tot_fix + $tot_var;
		$data[$_REQUEST['emp_id'][$k].$v]['net_income'] = $tot_fix + $tot_var - $tot_deduct;
		//$data[$_REQUEST['emp_id'][$k].$v]['ytd_income'] = $tot_fix + $tot_var - $tot_deduct;
	}

	//var_dump($data); exit;
	
	$sql = "INSERT INTO ".$cid.'_historic_data (';
	foreach($data[key($data)] as $k=>$v){
		$sql .= $k.',';
	}
	$sql = substr($sql,0,-1).')';
	$sql .= " VALUES (";
	foreach($data as $key=>$val){
		foreach($val as $k=>$v){
			$sql .= "'".$dbc->real_escape_string($v)."',";
		}
		$sql = substr($sql,0,-1).'),(';
	}
	$sql = substr($sql,0,-2)." ON DUPLICATE KEY UPDATE ";
	reset($data);
	foreach($data[key($data)] as $k=>$v){
		if($k != 'emp_id'){
			$sql .= $k."=VALUES(".$k."),";
		}
	}
	$sql = substr($sql,0,-1);
	//var_dump($sql); exit;
	ob_clean();
	if($dbc->query($sql)){
		echo 'success';
	}else{
		echo mysqli_error($dbc);
	}
