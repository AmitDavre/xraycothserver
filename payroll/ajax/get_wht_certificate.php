<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	include(DIR.'files/payroll_functions.php');


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();
	
	$name_position = getFormNamePosition($cid);
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	
	$tin = str_replace('-','',$edata['tax_id']);
	if(strlen($tin)!== 13){$tin = '?????????????';}
	$tin = str_split($tin);
	
	$comma = '';
	if($lang == 'en'){$comma = ',';}
	$comp_address = $address['number'];
	if(!empty($address['moo'])){$comp_address .= $comma.'  ม.'.$address['moo'];}
	if(!empty($address['lane'])){$comp_address .= $comma.' '.$address['lane'];}
	if(!empty($address['road'])){$comp_address .= $comma.' '.$address['road'];}
	if(!empty($address['subdistrict'])){$comp_address .= $comma.' '.$address['subdistrict'];}
	if(!empty($address['district'])){$comp_address .= $comma.' '.$address['district'];}
	if(!empty($address['province'])){$comp_address .= $comma.' '.$address['province'];}
	if(!empty($address['postal'])){$comp_address .= ' '.$address['postal'];}
	
	$emp_id = $_REQUEST['emp_id'];
	$fromdate = $_REQUEST['fromdate'];
	$todate = $_REQUEST['todate'];
	
	$empinfo = getEmployeeInfo($cid, $emp_id);
	//var_dump($empinfo);
	$pid = str_replace('-','',$empinfo['tax_id']);
	if(strlen($pid)!== 13){$pid = '?????????????';}
	$pid = str_split($pid);
	
	$emp_name = $title[$empinfo['title']]. ' '.$empinfo[$_SESSION['rego']['lang'].'_name'];
	$emp_address = '';
	if(!empty($empinfo['reg_address'])){$emp_address .= $empinfo['reg_address'];}
	//if(!empty($empinfo['address2'])){$emp_address .= ' '.$empinfo['address2'];}
	if(!empty($empinfo['sub_district'])){$emp_address .= $comma.' '.$empinfo['sub_district'];}
	if(!empty($empinfo['district'])){$emp_address .= $comma.' '.$empinfo['district'];}
	if(!empty($empinfo['province'])){$emp_address .= $comma.' '.$empinfo['province'];}
	if(!empty($empinfo['postnr'])){$emp_address .= ' '.$empinfo['postnr'];}
	
	$tot_income = 0; 
	$tot_tax = 0; 
	$tot_pvf = 0; 
	$tot_ssf = 0;
	
	/*if($res = $dbc->query("SELECT SUM(gross_income) as gross, SUM(tax_month) as tax, SUM(pvf_employee + psf_employee) as pvf, SUM(social) as sso FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$emp_id."'")){
		if($row = $res->fetch_assoc()){
			$tot_income = $row['gross'];
			$tot_tax = $row['tax'];
			$tot_pvf = $row['pvf'];
			$tot_ssf = $row['sso'];
		}
	}*/

	//$_SESSION['rego']['formdate']['endate'];
	$joining_date = $empinfo['joining_date'];
	$resign_date = $empinfo['resign_date'];

	$currentYearfirstday = '01-01-'.$_SESSION['rego']['cur_year'];
	$currentYearlastday = '31-12-'.$_SESSION['rego']['cur_year'];

	if(strtotime($joining_date) < strtotime($currentYearfirstday)){
		$seldate = $joining_date;
	}else{
		$seldate = $currentYearfirstday;
	}

	if($resign_date == ''){
		$sellastdate = $currentYearlastday;
	}else{
		$sellastdate = $resign_date;
	}

	if($fromdate && $todate !=''){
		$getfromM = (int)date('m', strtotime($fromdate));
		$gettoM = (int)date('m', strtotime($todate));
		$addCondition = "AND month >= '".$getfromM."' AND month <= '".$gettoM."'";
		$sellastdate = $todate;
	}else{
		$addCondition = "";
	}

	$getsellastdateD = date('d', strtotime($sellastdate));
	$getsellastdateM = date('m', strtotime($sellastdate));
	$getsellastdateY = date('Y', strtotime($sellastdate));

	if($lang == 'th'){
		$getsellastdateY = (int)$getsellastdateY + 543;
	}

	//echo "SELECT gross_income, ytd_income, tax_month, pvf_employee, psf_employee, social FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$emp_id."' ".$addCondition." AND paid IN ('Y','H')";

	if($res = $dbc->query("SELECT gross_income, ytd_income, tax_month, pvf_employee, psf_employee, social FROM ".$_SESSION['rego']['payroll_dbase']." WHERE emp_id = '".$emp_id."' ".$addCondition." AND paid IN ('Y','H') ")){
		while($row = $res->fetch_assoc()){

			if($row['ytd_income'] > 0) { 
				$ytd = $row['ytd_income'];
			}else{
				$ytd = $row['gross_income'];
			}

			$tot_income += $ytd;
			$tot_tax += $row['tax_month'];
			$tot_pvf += $row['pvf_employee'] + $row['psf_employee'];
			$tot_ssf += $row['social'];
		}
	}

	$s = number_format($tot_tax, 2, '.', '');
	$chars = getThaiCharNumber($s);
	
	$data = '
		<div class="field" style="top:10.2mm;left:205.6mm">1</div>
		<div class="field" style="top:18.2mm;left:143.6mm">'.$tin[0].'</div>
		<div class="field" style="top:18.2mm;left:150.8mm">'.$tin[1].'</div>
		<div class="field" style="top:18.2mm;left:155.6mm">'.$tin[2].'</div>
		<div class="field" style="top:18.2mm;left:160.4mm">'.$tin[3].'</div>
		<div class="field" style="top:18.2mm;left:165.4mm">'.$tin[4].'</div>
		<div class="field" style="top:18.2mm;left:172.6mm">'.$tin[5].'</div>
		<div class="field" style="top:18.2mm;left:177.4mm">'.$tin[6].'</div>
		<div class="field" style="top:18.2mm;left:182.2mm">'.$tin[7].'</div>
		<div class="field" style="top:18.2mm;left:187.0mm">'.$tin[8].'</div>
		<div class="field" style="top:18.2mm;left:191.8mm">'.$tin[9].'</div>
		<div class="field" style="top:18.2mm;left:199.2mm">'.$tin[10].'</div>
		<div class="field" style="top:18.2mm;left:204.2mm">'.$tin[11].'</div>
		<div class="field" style="top:18.2mm;left:211.6mm">'.$tin[12].'</div>
	
		<div class="field12" style="top:25.2mm;left:14mm">'.$edata[$lang.'_compname'].'</div>
		
		<div class="field12" style="top:34.2mm;left:17mm">'.$comp_address.'</div>
		
		<div class="field" style="top:45.8mm;left:143.8mm">'.$pid[0].'</div>
		<div class="field" style="top:45.8mm;left:151.2mm">'.$pid[1].'</div>
		<div class="field" style="top:45.8mm;left:155.8mm">'.$pid[2].'</div>
		<div class="field" style="top:45.8mm;left:160.6mm">'.$pid[3].'</div>
		<div class="field" style="top:45.8mm;left:165.6mm">'.$pid[4].'</div>
		<div class="field" style="top:45.8mm;left:173.0mm">'.$pid[5].'</div>
		<div class="field" style="top:45.8mm;left:177.6mm">'.$pid[6].'</div>
		<div class="field" style="top:45.8mm;left:182.6mm">'.$pid[7].'</div>
		<div class="field" style="top:45.8mm;left:187.4mm">'.$pid[8].'</div>
		<div class="field" style="top:45.8mm;left:192.2mm">'.$pid[9].'</div>
		<div class="field" style="top:45.8mm;left:199.4mm">'.$pid[10].'</div>
		<div class="field" style="top:45.8mm;left:204.4mm">'.$pid[11].'</div>
		<div class="field" style="top:45.8mm;left:211.8mm">'.$pid[12].'</div>
	
		<div class="field12" style="top:54.2mm;left:14mm">'.$emp_name.'</div>
		
		<div class="field12" style="top:64.8mm;left:17mm">'.$emp_address.'</div>
		
		<div class="field12" style="top:74.4mm;left:77mm"><img src="../images/forms/check.png"></div>';
		
		// <div class="field13n" style="top:103.2mm;left:122mm">31/12/'.$_SESSION['rego']['year_'.$_SESSION['rego']['lang']].'</div>
	$data .= '<div class="field13n" style="top:103.2mm;left:122mm">'.$getsellastdateD.'/'.$getsellastdateM.'/'.$getsellastdateY.'</div>
		<div class="field13n" style="top:103.2mm;left:160.8mm">'.number_format($tot_income,2).'</div>
		<div class="field13n" style="top:103.2mm;left:189.4mm">'.number_format($tot_tax,2).'</div>
		
		<div class="field13n" style="top:244.4mm;left:160.8mm">'.number_format($tot_income,2).'</div>
		<div class="field13n" style="top:244.4mm;left:189.4mm">'.number_format($tot_tax,2).'</div>
		
		<div class="field13" style="top:252.6mm;left:67mm">'.$chars.'</div>
		
		<div class="field13n" style="top:259.6mm;left:126mm">'.number_format($tot_ssf).'</div>
		<div class="field13n" style="top:259.6mm;left:181mm">'.number_format($tot_pvf).'</div>
		
		<div class="field12" style="top:267.6mm;left:26.2mm"><img src="../images/forms/check.png"></div>';
		
		if($edata['digi_stamp'] && !empty($edata['dig_stamp'])){
			$data .= '<div class="stamp" style="top:280mm;right:4mm"><img width="90px" src="'.ROOT.'/'.$edata['dig_stamp'].'?'.time().'" /></div>';
		}
		
	/*$data .='
		<div class="field13" style="top:282.8mm;left:135mm">'.$name_position['name'].'</div>
		<div class="field13c" style="top:287.4mm;left:127mm;width:30px">'.$_SESSION['rego']['formdate']['d'].'</div>
		<div class="field13c" style="top:287.4mm;left:138.4mm;width:90px">'.$_SESSION['rego']['formdate']['m'].'</div>
		<div class="field13c" style="top:287.4mm;left:162mm;width:60px">'.$_SESSION['rego']['formdate'][$lang.'y'].'</div>
	
	';*/

	$data .='
		<div class="field13" style="top:282.8mm;left:135mm">'.$name_position['name'].'</div>
		<div class="field13c" style="top:287.4mm;left:127mm;width:30px">'.$getsellastdateD.'</div>
		<div class="field13c" style="top:287.4mm;left:138.4mm;width:90px">'.$months[(int)$getsellastdateM].'</div>
		<div class="field13c" style="top:287.4mm;left:162mm;width:60px">'.$getsellastdateY.'</div>
	
	';
	
	echo $data; 
