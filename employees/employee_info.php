<?php
	
	//var_dump(getSystemUsers(1)); exit;
	// IF FORWARD LEAVE BALANCE IS YES THEN GET THIS BALANCE IN NEXT YEAR IF IT IS NO THEN GET ANNUAL LEAVE BALANCE FROM THIS FIELD annual_leave
	
	
	//var_dump(explode(',',$_SESSION['rego']['sel_branches']));exit;
	if(!$_SESSION['rego']['employee_info']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}
	if(isset($_GET['id'])){$_SESSION['rego']['empID'] = $_GET['id'];}
	
	$delDoc = 'delColor';
	if($_SESSION['rego']['employee_info']['del']){$delDoc = 'delDoc';}

	if(isset($_SESSION['rego']['empID']) && $_SESSION['rego']['empID'] != '0'){ // EDIT EMPLOYEE //////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
		$emergency_contacts = unserialize($data['emergency_contacts']);
		$hospitals = unserialize($data['hospitals']);
		$update = 1;

		if($data['annual_leave'] ==''){
			$data['annual_leave'] = $sys_settings['leeve'];
		}

		if($data['sid'] ==''){
			if($sys_settings['scan_id'] == 1){
				$data['sid'] = $empID;
			}
		}


	}else{ // NEW EMPLOYEE /////////////////////////////////////////////////////////////////////////////////
		$empID = 0;
		$button_txt = '';//$lng['Save new employee'];
		$update = 0;

		$sql = "SHOW COLUMNS FROM ".$cid."_employees";
		$res = $dbc->query($sql);
		while($row = $res->fetch_assoc()){
			 $data[$row['Field']] = '';
		}
		if($sys_settings['joining_date'] != 'empty'){
			$data['joining_date'] = date('d-m-Y');
			$data['probation_date'] = date('d-m-Y', strtotime(date('d-m-Y').'+ 4 months'));
		}
		$data['entity'] = $teams[$sys_settings['team']]['entity'];
		$data['branch'] = $teams[$sys_settings['team']]['branch'];
		$data['division'] = $teams[$sys_settings['team']]['division'];
		$data['department'] = $teams[$sys_settings['team']]['department'];
		$data['team'] = $sys_settings['team'];
		$data['teams'] = $sys_settings['team'];
		$data['team_name'] = $sys_settings['teams_name'];
		$data['shiftplan'] = $sys_settings['shiftplan_schedule'];
		$data['entity'] = '1';
		$data['branch'] = '1';
		$data['division'] = '1';
		$data['department'] = '1';
		$data['emp_group'] = $sys_settings['emp_group'];
		$data['emp_type'] = $sys_settings['emp_type'];
		$data['emp_status'] = $sys_settings['emp_status'];
		$data['account_code'] = $sys_settings['account_code'];
		$data['position'] = $sys_settings['position'];
		if($sys_settings['date_start'] != 'empty'){
			$data['date_position'] = date('d-m-Y');
		}
		$data['shift_team'] = '';
		$data['time_reg'] = $sys_settings['time_reg'];
		$data['selfie'] = $sys_settings['selfie'];
		$data['annual_leave'] = $sys_settings['leeve'];
		
		// FINANCIAL ///////////////////////////////////////////////
		$data['pay_type'] = $sys_settings['pay_type'];
		$data['calc_psf'] = $sys_settings['calc_psf'];
		$data['psf_rate_emp'] = $sys_settings['psf_rate_emp'];
		$data['psf_rate_com'] = $sys_settings['psf_rate_com'];
		$data['calc_pvf'] = $sys_settings['calc_pvf'];
		$data['pvf_rate_emp'] = $sys_settings['pvf_rate_emp'];
		$data['pvf_rate_com'] = $sys_settings['pvf_rate_com'];
		$data['calc_method'] = $sys_settings['calc_method'];
		$data['calc_tax'] = $sys_settings['calc_tax'];
		$data['pnd'] = $sys_settings['pnd'];
		$data['calc_sso'] = $sys_settings['calc_sso'];
		$data['sso_by'] = '0';
		$data['contract_type'] = $sys_settings['contract_type'];
		$data['calc_base'] = $sys_settings['calc_base'];
		$data['base_ot_rate'] = $sys_settings['base_ot_rate'];
		$data['ot_rate'] = $sys_settings['ot_rate'];
	}
	$prefix = explode(',', $sys_settings['id_prefix']);

	if($data['emergency_contacts'] == ''){
		$emergency_contacts[1]['name'] = '';
		$emergency_contacts[1]['relation'] = '';
		$emergency_contacts[1]['mobile'] = '';
		$emergency_contacts[1]['work'] = '';
		$emergency_contacts[2]['name'] = '';
		$emergency_contacts[2]['relation'] = '';
		$emergency_contacts[2]['mobile'] = '';
		$emergency_contacts[2]['work'] = '';
		$emergency_contacts[3]['name'] = '';
		$emergency_contacts[3]['relation'] = '';
		$emergency_contacts[3]['mobile'] = '';
		$emergency_contacts[3]['work'] = '';
	}

	if($data['hospitals'] == ''){
		$hospitals[1]['name'] = '';
		$hospitals[1]['phone'] = '';
		$hospitals[1]['contact'] = '';
		$hospitals[1]['address'] = '';
		$hospitals[2]['name'] = '';
		$hospitals[2]['phone'] = '';
		$hospitals[2]['contact'] = '';
		$hospitals[2]['address'] = '';
		$hospitals[3]['name'] = '';
		$hospitals[3]['phone'] = '';
		$hospitals[3]['contact'] = '';
		$hospitals[3]['address'] = '';
	}
	//var_dump($data); exit;

	if(empty($data['att_idcard'])){$att_idcard = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_idcard = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_idcard'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['att_housebook'])){$att_housebook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_housebook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_housebook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach1'])){$attach1 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach1 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach1'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach2'])){$attach2 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach2 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach2'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach3'])){$attach3 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach3 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach3'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach4'])){$attach4 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach4 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach4'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	$employees = getJsonUserEmployees($cid, $lang);
	$emps = getEmployees($cid, 0);
	//echo '<pre>';
	//var_dump($data['emp_status']); exit;


	$sql11 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year;
	if($res11 = $dbc->query($sql11)){
		while($row11 = $res11->fetch_assoc()){

				$shifttest[] =$row11;
				array_unshift($shifttest,"");
				unset($shifttest[0]);

		}
	}

	$approve_by = getSystemUsers($data['team']);


	foreach ($shifttest as $key_1000 => $value_1000) {
		# code...

		$sql11aa = "SELECT * FROM ".$cid."_teams WHERE LOWER(code) = '".$value_1000['id']."'";
		if($res11aa = $dbc->query($sql11aa)){
			if($row11aa = $res11aa->fetch_assoc()){

				$shifttest_new[$row11aa['id']] = $value_1000;
					
			}
		}


	}



	// Unserialze attach8 option  


	  $unserializeAttach8 = unserialize($data['attach8']);

	  // if leave_forward_next_year == '1' then get the old annual leave balance  if == '2' then get from leave settings 

	  	if($unserializeAttach8['leave_forward_next_year'] == '2')
	  	{
	  		$sqlgetleave= "SELECT * FROM ".$cid."_leave_time_settings WHERE id = '1'";
			if($resgetleave = $dbc->query($sqlgetleave)){
				if($rowgetleave = $resgetleave->fetch_assoc()){

					// print_r($rowgetleave); 
					$annualLeavesVal2 = unserialize($rowgetleave['leave_types']);
					$annualLeavesVal = $annualLeavesVal2['AL']['max']['s'];
						
				}
			}

	  	}
	  	else if($unserializeAttach8['leave_forward_next_year'] == '1')
	  	{
	  		$annualLeavesVal = $data['annual_leave'];
	  	}



	  	// if in new year and setting the annual leave 

	  	// $sys_settings['years'] if it has 2 years then need to show if has one year then no need to show if has 3 years then also need to show 

	  	// 
	  	// echo $annualLeavesVal;



	  	// get annual leave from employee per year table if value doesn't exists than use $data['annual_leave '] which is default value 

	  	$sql511 = "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$data['emp_id']."' AND year = '".$cur_year."'";

		if($res511 = $dbc->query($sql511)){
			if($row511 = $res511->fetch_assoc()){
				$annual_leave_data = $row511['annual_leave'];
				$annual_leave_dataYesNO = unserialize($row511['other_fields']);
				$annual_leave_dataYesNOs = $annual_leave_dataYesNO['leaveForwardorNot'];
			}
			else
			{
				$annual_leave_data = $data['annual_leave'];
				$annual_leave_dataYesNOs = '0';

			}

		}
	// echo '<pre>';
	// print_r($unserializeAttach8);
	// echo '</pre>';

	// die();



?>
   <h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Employee info']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></span>
		<? if($update){ echo '<span>'.$data['emp_id'].' : '.$data[$lang.'_name'].'</span>';}else{echo $lng['Add employee'];}?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel">
		<div style="padding:0 0 0 20px" id="dump"></div>	
		
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_personal" data-toggle="tab"><?=$lng['Personal']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_contcact" data-toggle="tab"><?=$lng['Contact']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_work" data-toggle="tab"><?=$lng['Work']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_documents" data-toggle="tab"><?=$lng['Documents']?></a></li>
		</ul>
			
		<form id="infoForm" enctype="multipart/form-data" style="height:calc(100% - 30px)">
		
		<? if(!$update){ ?>
			<input type="hidden" name="pay_type" value="<?=$data['pay_type']?>">
			<input type="hidden" name="calc_psf" value="<?=$data['calc_psf']?>">
			<input type="hidden" name="psf_rate_emp" value="<?=$data['psf_rate_emp']?>">
			<input type="hidden" name="psf_rate_com" value="<?=$data['psf_rate_com']?>">
			<input type="hidden" name="calc_pvf" value="<?=$data['calc_pvf']?>">
			<input type="hidden" name="pvf_rate_emp" value="<?=$data['pvf_rate_emp']?>">
			<input type="hidden" name="pvf_rate_com" value="<?=$data['pvf_rate_com']?>">
			<input type="hidden" name="calc_method" value="<?=$data['calc_method']?>">
			<input type="hidden" name="calc_tax" value="<?=$data['calc_tax']?>">
			<input type="hidden" name="pnd" value="<?=$data['pnd']?>">
			<input type="hidden" name="calc_sso" value="<?=$data['calc_sso']?>">
			<input type="hidden" name="contract_type" value="<?=$data['contract_type']?>">
			<input type="hidden" name="calc_base" value="<?=$data['calc_base']?>">
			<input type="hidden" name="base_ot_rate" value="<?=$data['base_ot_rate']?>">
			<input type="hidden" name="ot_rate" value="<?=$data['ot_rate']?>">
			<input type="hidden" name="allow_login" value="0">
		<? } ?>
		
		<fieldset style="height:100%" <? if(!$_SESSION['rego']['employee_info']['edit']){echo 'disabled';} ?>>
		<div class="tab-content" style="height:100%">
			<button id="submitBtn" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
				<div class="tab-pane" id="tab_personal">
					<table class="basicTable editTable" border="0">
						<tbody>
							<tr>
								<th style="width:5%"><i class="man"></i><?=$lng['Employee ID']?></th>
								<td>
								<? if($sys_settings['auto_id'] && $update == 0){ ?>
									<select id="emp_prefix" style="width:auto">
										<option value="0" selected disabled>...<? //=$lng['Select']?></option>
										<? foreach($prefix as $v){ ?>
											<option <? //if($data['title'] == $k){echo 'selected';}?> value="<?=$v?>"><?=$v?></option>
										<? } ?>
									</select>
									<span id="empID" style="font-weight:600; color:#c00"></span>
									<input type="hidden" name="emp_id" id="emp_id" value="<?=$data['emp_id']?>">
								<? }else{ ?>
									<input maxlength="10" minlength="3" <? if($update){echo 'readonly';}?> style="font-weight:600" type="text" name="emp_id" id="emp_id" placeholder="..." value="<?=$data['emp_id']?>">
								<? } ?>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Scan ID']?></th>
								<td><input type="text" name="sid" placeholder="..." value="<?=$data['sid']?>"></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Title']?></th><td>
								<select name="title">
									<option value="0" selected disabled><?=$lng['Select']?></option>
									<? foreach($title as $k=>$v){ ?>
										<option <? if($data['title'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['First name']?></th>
								<td><input type="text" name="firstname" placeholder="..." value="<?=$data['firstname']?>"></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Last name']?></th>
								<td><input type="text" name="lastname" placeholder="..." value="<?=$data['lastname']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Name in English']?></th>
								<td><input type="text" name="en_name" placeholder="..." value="<?=$data['en_name']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Birthdate']?></th>
								<td><input readonly style="cursor:pointer" class="date_year"  type="text" name="birthdate" id="birthdate" placeholder="..." value="<?=$data['birthdate']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Age']?></th>
								<td class="pad410" id="emp_age"></td>
							</tr>
							<tr>
								<th><?=$lng['Nationality']?></th>
								<td><input type="text" name="nationality" placeholder="..." value="<?=$data['nationality']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Gender']?></th>
								<td>
									<select name="gender">
										<option value="" selected disabled><?=$lng['Select']?></option>
										<? foreach($gender as $k=>$v){ ?>
											<option <? if($data['gender'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Maritial status']?></th>
								<td>
									<select name="maritial">
										<option value="" selected disabled><?=$lng['Select']?></option>
										<? foreach($maritial as $k=>$v){ ?>
											<option <? if($data['maritial'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Religion']?></th>
								<td>
									<select name="religion">
										<option value="" selected disabled><?=$lng['Select']?></option>
										<? foreach($religion as $k=>$v){ ?>
											<option <? if($data['religion'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Military status']?></th>
								<td>
									<select name="military_status">
										<option value="" selected disabled><?=$lng['Select']?></option>
										<? foreach($military_status as $k=>$v){ ?>
											<option <? if($data['military_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Height']?> (cm)<? //=$lng['cm']?></th>
								<td><input class="sel numeric3" type="text" name="height" placeholder="..." value="<?=$data['height']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Weight']?> (kg)</th>
								<td><input class="sel numeric3" type="text" name="weight" placeholder="..." value="<?=$data['weight']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Blood group']?></th>
								<td><input type="text" name="bloodtype" placeholder="..." value="<?=$data['bloodtype']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Driving license No.']?></th>
								<td><input type="text" name="drvlicense_nr" placeholder="..." value="<?=$data['drvlicense_nr']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['License expiry date']?></th>
								<td><input type="text" readonly style="cursor:pointer" class="date_year" name="drvlicense_exp" placeholder="..." value="<?=$data['drvlicense_exp']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['ID card']?></th>
								<td><input class="xtax_id_number" type="text" name="idcard_nr" placeholder="..." value="<?=$data['idcard_nr']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['ID card expiry date']?></th>
								<td><input style="cursor:pointer" class="date_year" name="idcard_exp"  type="text" placeholder="..." value="<?=$data['idcard_exp']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Tax ID no.']?></th>
								<td><input class="xtax_id_number" type="text" name="tax_id" placeholder="..." value="<?=$data['tax_id']?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane" id="tab_contcact">
					<table class="basicTable editTable" border="0">
						<tbody>
							<tr style="background:#f9fff9">
								<th><?=$lng['Registered address']?></th>
								<td><input style="background:transparent" type="text" name="reg_address" placeholder="..." value="<?=$data['reg_address']?>"></td>
								<td rowspan="8">
									
									<h6 style="background:#eee; padding:6px 10px; margin:0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$compinfo[$lang.'_compname']?></span></h6>
									<div style="height:202px;" id="map-canvas"></div>
								</td>
							</tr>

							<tr style="background:#f9fff9">
								<th><?=$lng['Sub district']?></th>
								<td><input style="background:transparent" type="text" name="sub_district" placeholder="..." value="<?=$data['sub_district']?>"></td>
							</tr>
							<tr style="background:#f9fff9">
								<th><?=$lng['District']?></th>
								<td><input style="background:transparent" type="text" name="district" placeholder="..." value="<?=$data['district']?>"></td>
							</tr>
							<tr style="background:#f9fff9">
								<th><?=$lng['Province']?></th>
								<td><input style="background:transparent" type="text" name="province" placeholder="..." value="<?=$data['province']?>"></td>
							</tr>
							<tr style="background:#f9fff9">
								<th><?=$lng['Postal code']?></th>
								<td><input style="background:transparent" type="text" name="postnr" placeholder="..." value="<?=$data['postnr']?>"></td>
							</tr>
							<tr style="background:#f9fff9; ">
								<th><?=$lng['Country']?></th>
								<td><input style="background:transparent" type="text" name="country" placeholder="..." value="<?=$data['country']?>"></td>
							</tr>

							<tr style="background:#f9fff9;">
								<th><?=$lng['Latitude']?></th>
								<td><input style="background:transparent" type="text" name="latitude" placeholder="..." value="<?=$data['latitude']?>"></td>
							</tr>						
							<tr style="background:#f9fff9; border-bottom:1px solid #ddd">
								<th><?=$lng['Longitude']?></th>
								<td><input style="background:transparent" type="text" name="longitude" placeholder="..." value="<?=$data['longitude']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Current address']?></th>
								<td><input type="text" name="cur_address" placeholder="..." value="<?=$data['cur_address']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Personal phone']?></th>
								<td><input type="text" name="personal_phone" placeholder="..." value="<?=$data['personal_phone']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Personal email']?></th>
								<td><input  type="text" name="personal_email" placeholder="..." value="<?=$data['personal_email']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Work phone']?></th>
								<td><input type="text" name="work_phone" placeholder="..." value="<?=$data['work_phone']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Work email']?></th>
								<td><input  type="text" name="work_email" placeholder="..." value="<?=$data['work_email']?>"></td>
							</tr>
							<tr>
								<th colspan="2" style="border:0; text-align:left; height:30px; vertical-align:bottom"><?=$lng['EMERGENCY CONTACTS']?></th>
							</tr>
							<tr style="border:0">
								<td colspan="3" style="padding:0">
									<table class="basicTable editTable" id="emTable" border="0">
										<thead>
											<tr style="border-bottom:1px #ccc solid; line-height:100%">
												<th><?=$lng['Name']?></th>
												<th><?=$lng['Relationship']?></th>
												<th><?=$lng['Mobile phone']?></th>
												<th><?=$lng['Work phone']?></th>
											</tr>
										</thead>
										<tbody>
										<tr>
											<td><input type="text" name="emergency_contacts[1][name]" placeholder="..." value="<?=$emergency_contacts[1]['name']?>"></td>
											<td><input type="text" name="emergency_contacts[1][relation]" placeholder="..." value="<?=$emergency_contacts[1]['relation']?>"></td>
											<td><input type="text" name="emergency_contacts[1][mobile]" placeholder="..." value="<?=$emergency_contacts[1]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[1][work]" placeholder="..." value="<?=$emergency_contacts[1]['work']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="emergency_contacts[2][name]" placeholder="..." value="<?=$emergency_contacts[2]['name']?>"></td>
											<td><input type="text" name="emergency_contacts[2][relation]" placeholder="..." value="<?=$emergency_contacts[2]['relation']?>"></td>
											<td><input type="text" name="emergency_contacts[2][mobile]" placeholder="..." value="<?=$emergency_contacts[2]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[2][work]" placeholder="..." value="<?=$emergency_contacts[2]['work']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="emergency_contacts[3][name]" placeholder="..." value="<?=$emergency_contacts[3]['name']?>"></td>
											<td><input type="text" name="emergency_contacts[3][relation]" placeholder="..." value="<?=$emergency_contacts[2]['relation']?>"></td>
											<td><input type="text" name="emergency_contacts[3][mobile]" placeholder="..." value="<?=$emergency_contacts[3]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[3][work]" placeholder="..." value="<?=$emergency_contacts[3]['work']?>"></td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<th colspan="3" style="border:0; text-align:left; height:30px; vertical-align:bottom"><?=$lng['AVAILABLE HOSPITALS']?></th>
							</tr>
							<tr style="border:0">
								<td colspan="3" style="padding:0">
									<table class="basicTable editTable" id="emTable" border="0">
										<thead>
											<tr style="border-bottom:1px #ccc solid; line-height:100%">
												<th style="min-width:250px"><?=$lng['Name']?></th>
												<th style="min-width:150px"><?=$lng['Phone']?></th>
												<th style="min-width:200px"><?=$lng['Contact person']?></th>
												<th style="width:80%"><?=$lng['Address']?></th>
											</tr>
										</thead>
										<tbody>
										<tr>
											<td><input type="text" name="hospitals[1][name]" placeholder="..." value="<?=$hospitals[1]['name']?>"></td>
											<td><input type="text" name="hospitals[1][phone]" placeholder="..." value="<?=$hospitals[1]['phone']?>"></td>
											<td><input type="text" name="hospitals[1][contact]" placeholder="..." value="<?=$hospitals[1]['contact']?>"></td>
											<td><input type="text" name="hospitals[1][address]" placeholder="..." value="<?=$hospitals[1]['address']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="hospitals[2][name]" placeholder="..." value="<?=$hospitals[2]['name']?>"></td>
											<td><input type="text" name="hospitals[2][phone]" placeholder="..." value="<?=$hospitals[2]['phone']?>"></td>
											<td><input type="text" name="hospitals[2][contact]" placeholder="..." value="<?=$hospitals[2]['contact']?>"></td>
											<td><input type="text" name="hospitals[2][address]" placeholder="..." value="<?=$hospitals[2]['address']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="hospitals[3][name]" placeholder="..." value="<?=$hospitals[3]['name']?>"></td>
											<td><input type="text" name="hospitals[3][phone]" placeholder="..." value="<?=$hospitals[3]['phone']?>"></td>
											<td><input type="text" name="hospitals[3][contact]" placeholder="..." value="<?=$hospitals[3]['contact']?>"></td>
											<td><input type="text" name="hospitals[3][address]" placeholder="..." value="<?=$hospitals[3]['address']?>"></td>
										</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane" id="tab_work">
					<div class="tab-content-left">
						<table class="basicTable editTable" border="0">
							<thead>
								<tr style="line-height:100%">
									<th colspan="2"><?=$lng['WORK DATA']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Joining date']?></th>
									<td><input readonly style="cursor:pointer" class="datepick" type="text" name="joining_date" id="joining_date" placeholder="..." value="<? if(!empty($data['joining_date'])){echo date('d-m-Y', strtotime($data['joining_date']));}?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Probation due date']?></th>
									<td><input type="text" readonly style="cursor:pointer" class="datepick" name="probation_date" id="probation_date" placeholder="..." value="<? if(!empty($data['probation_date'])){echo date('d-m-Y', strtotime($data['probation_date']));}?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Service years']?></th>
									<td class="pad410" id="serv_years"></td>
								</tr>
								<tr>
									<th><?=$lng['Entity']?></th>
									<td style="color:#999; padding:4px 10px"><span id="txtEntity"><?=$entities[$data['entity']][$lang]?></span>
										<input type="hidden" id="empEntity" name="entity" value="<?=$data['entity']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Branch']?></th>
									<td style="color:#999; padding:4px 10px"><span id="txtBranch"><?=$branches[$data['branch']][$lang]?></span>
										<input type="hidden" id="empBranch" name="branch" value="<?=$data['branch']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Division']?></th>
									<td style="color:#999; padding:4px 10px"><span id="txtDivision"><?=$divisions[$data['division']][$lang]?></span>
										<input type="hidden" id="empDivision" name="division" value="<?=$data['division']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Department']?></th>
									<td style="color:#999; padding:4px 10px"><span id="txtDepartment"><?=$departments[$data['department']][$lang]?></span>
										<input type="hidden" id="empDepartment" name="department" value="<?=$data['department']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Team']?></th>
									<td>
										<input type="hidden" name="teams" id="teams" value="<?php echo $data['teams'];?>">
										<input type="hidden" name="team_name" id="team_name" value="<?php echo $data['team_name'];?>" />


										<select name="team" id="empTeam" onchange="getShiftSchedule();getTeamName();">
											<!-- <option value=""><?=$lng['Select']?></option>
											<? foreach($teams as $k=>$v){ ?>
												<option <? if($data['team'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v['code']?></option>
											<? } ?> -->


											<?php  
												if(isset($shifttest))
												{

													$count = 1;
													foreach($shifttest as $k=>$v){ 
														?>

													<option <? if($data['team'] == $k){echo 'selected';}?> value="<?=$k?>"><?php echo strtoupper($v['id'])?>
														
													</option>
														
										<?php }} ?>




										</select>
									</td>
								</tr>

				<!-- 				<tr>
									<th><?=$lng['Shift schedule']?></th>
									<td>
										<?php 
											if(isset($_SESSION['RGadmin']) )
											{ ?> 
												<input type="text" name="shiftplan" id="shiftplan" value="<?=$data['shiftplan']?>" readonly="readonly" >
											<?php }
											else
											{ ?>
												<input type="text" name="shiftplan" id="shiftplan" value="" readonly="readonly" >

											<?php }
										?>
		
									</td>
								</tr> -->	

								<tr>
									<th><?=$lng['Employee group']?></th>
									<td>
										<select name="emp_group" style="pointer-events: none;opacity: 0.4;">
											<? foreach($emp_groep as $k=>$v){ ?>
												<option <? if($data['emp_group'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Employee type']?></th>
									<td>
										<select name="emp_type">
											<? foreach($emp_type as $k=>$v){ ?>
												<option <? if($data['emp_type'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Resign date']?></th>
									<td>
										<input type="text" style="cursor:pointer;width:140px;" name="resign_date" placeholder="..." value="<? if(!empty($data['resign_date'])){echo date('d-m-Y', strtotime($data['resign_date']));}?>" readonly="readonly">
										<b style="color:#b00"><?=$lng['Please change this from End Contract tab']?></b>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Resign reason']?></th>
									<td>
										<input type="text" style="cursor:pointer;width:140px;" name="resign_reason" readonly="readonly" placeholder="..." value="<?=$data['resign_reason']?>">
										<b style="color:#b00"><?=$lng['Please change this from End Contract tab']?></b>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Employee status']?></th><td>
										<select name="emp_status" style="pointer-events: none;width:140px;">
											<? foreach($emp_status as $k=>$v){ ?>
												<option <? if($data['emp_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
										<b style="color:#b00"><?=$lng['Please change this from End Contract tab']?></b>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Accounting code']?></th>
									<td>
										<select name="account_code">
											<option <? if($data['account_code'] == 0){echo 'selected';}?> value="0"><?=$lng['Direct']?></option>
											<option <? if($data['account_code'] == 1){echo 'selected';}?> value="1"><?=$lng['Indirect']?></option>
										</select>
									</td>
								</tr>

							</tbody>
							<thead>
								<tr style="line-height:100%">
									<th colspan="2"><?=$lng['Location Data']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Work From Home']?></th>
									<td>
										<select required name="workFromHome" id="workFromHome">
											 	
											  <option <? if($data['workFromHome'] == 0){echo 'selected';}?>  value="0">No</option>
											  <option <? if($data['workFromHome'] == 1){echo 'selected';}?> value="1">Yes</option>
										</select>
									</td>
								</tr>								
								<tr>
									<th><?=$lng['Ping Location']?></th>
									<td>
										<button onclick="pingEmployeeLocation(this);"style="padding-top: 1px;padding-bottom: 1px;padding-left: 9px;padding-right:9px;margin-left: 10px;"id="exportPlanning" type="button" class="btn btn-primary"><i class="fa fa-map-marker"></i>&nbsp; Ping Employee</button>
									</td>
								</tr>

							</tbody>
						</table>
					</div>
					<div class="tab-content-right">
						<table class="basicTable editTable" border="0">
							<thead>
								<tr style="line-height:100%">
									<th colspan="2"><?=$lng['RESPONSIBILITIES SECTION']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Position']?></th>
									<td>
										<select required name="position" id="position">
											<option value="">...</option>
											<? foreach($positions as $k=>$v){ ?>
												<option <? if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Head of branch']?></th>
									<td>
										<select name="head_branch">
											<option value="">...</option>
											<? foreach($branches as $k=>$v){ ?>
												<option <? if($data['head_branch'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Head of division']?></th>
									<td>
										<select name="head_division">
											<option value="">...</option>
											<? foreach($divisions as $k=>$v){ ?>
												<option <? if($data['head_division'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Head of department']?></th>
									<td>
										<select name="head_department">
											<option value="">...</option>
											<? foreach($departments as $k=>$v){ ?>
												<option <? if($data['head_department'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Team supervisor']?></th>
									<td>
										<select name="team_supervisor">
											<option value="">...</option>
											<? foreach($teams as $k=>$v){ ?>
												<option <? if($data['team_supervisor'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Date start Position']?></th>
									<td><input readonly type="text" name="date_position" style="cursor:pointer" class="datepick" placeholder="..." value="<?=$data['date_position']?>"></td>
								</tr>
								<tr>
									<td colspan="2" style="height:10px"></td>
								</tr>
							</tbody>
							<thead>
								<tr style="line-height:100%">
									<th colspan="2"><?=$lng['TIME DATA']?></th>
								</tr>
							</thead>
							<tbody>
						<!-- 		<tr>
									<th><?=$lng['Shift team']?></th>
									<td>
										<input placeholder="..." type="text" name="shift_team" value="<?=$data['shift_team']?>">
									</td>
								</tr> -->

								<tr>
									<th><?=$lng['Shift schedule']?></th>
									<td>
										<?php 
											if(isset($_SESSION['RGadmin']) )
											{ ?> 
												<input type="text" name="shiftplan" id="shiftplan" value="<?=$data['shiftplan']?>" readonly="readonly" >
											<?php }
											else
											{ ?>
												<input type="text" name="shiftplan" id="shiftplan" value="" readonly="readonly" >

											<?php }
										?>
		
									</td>
								</tr>
								<tr>
									<th><?=$lng['Time registration']?></th>
									<td>
										<select name="time_reg">
											<option <? if($data['time_reg'] == 0){echo 'selected';}?> value="0"><?=$lng['No']?></option>
											<option <? if($data['time_reg'] == 1){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Take selfie']?></th>
									<td>
										<select name="selfie">
											<option <? if($data['selfie'] == 0){echo 'selected';}?> value="0"><?=$lng['No']?></option>
											<option <? if($data['selfie'] == 1){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="height:10px"></td>
								</tr>
							</tbody>
							<thead>
								<tr style="line-height:100%">
									<th colspan="2"><?=$lng['LEAVE DATA']?></th>
								</tr>
							</thead>
							<tbody>
					

					<!-- 			<tr>
									<th>Forward previous </br>year  AL to this year</th>
									<td>
										<select id="nextyearleavebalance" name="nextyearleavebalance" style="width:auto" >
										<option value="0" <?php if($annual_leave_dataYesNOs == '0') {echo 'selected';}?>  >Please Select</option>
										<option value="2" <?php if($annual_leave_dataYesNOs == '2') {echo 'selected';}?>>No</option>
										<option value="1" <?php if($annual_leave_dataYesNOs == '1') {echo 'selected';}?>>Yes</option>
										
							
										</select>
									</td>
								</tr>	 -->



								


							

								<tr>
									<th><?=$lng['Annual leave (days)']?></th>
									<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<?=$annual_leave_data?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Leave approved by']?> </th>
									<td>
							<!-- 			<select name="leave_approve">
											<option value="">...</option>
											<? foreach($approve_by as $k=>$v){ ?>
												<option <? if($data['leave_approve'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select> -->


											<select  name="leave_approve[]" class="selectpicker" multiple data-live-search="true">
											<? 
												$leave_approve_explod = explode(',', $data['leave_approve']);
											foreach($approve_by as $k=>$v){ ?>
												<option <?php if(in_array($k, $leave_approve_explod)){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>


								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="tab-pane" id="tab_documents">
					<div style="width:0; height:0; overflow:hidden" >
					<input name="att_idcard" id="att_idcard" type="file" />
					<input name="att_housebook" id="att_housebook" type="file" />
					<input name="attach1" id="attach1" type="file" />
					<input name="attach2" id="attach2" type="file" />
					<input name="attach3" id="attach3" type="file" />
					<input name="attach4" id="attach4" type="file" />
					</div>
					<table class="basicTable" border="0">
						<thead>
							<tr>
								<th colspan="2"><?=$lng['DOCUMENTS']?></th>
								<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Upload']?>"><i class="fa fa-upload fa-lg"></i></th>
								<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Download']?>"><i class="fa fa-download fa-lg"></i></th>
								<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Delete']?>"><i class="fa fa-trash fa-lg"></i></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['ID card']?></th>
								<td id="idcard_name" style="width:95%; color:#999; font-style:italic"><?=$data['att_idcard']?></td>
								<td><a href="#" onClick="$('#att_idcard').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$att_idcard?></td>
								<td><a href="#" data-id="att_idcard" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
							<tr>
								<th><?=$lng['Housebook']?></th>
								<td id="housebook_name" style="color:#999; font-style:italic"><?=$data['att_housebook']?></td>
								<td><a href="#" onClick="$('#att_housebook').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$att_housebook?></td>
								<td><a href="#" data-id="att_housebook" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach1_name" style="color:#999; font-style:italic"><?=$data['attach1']?></td>
								<td><a href="#" onClick="$('#attach1').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach1?></td>
								<td><a href="#" data-id="attach1" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach2_name" style="color:#999; font-style:italic"><?=$data['attach2']?></td>
								<td><a href="#" onClick="$('#attach2').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach2?></td>
								<td><a href="#" data-id="attach2" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach3_name" style="color:#999; font-style:italic"><?=$data['attach3']?></td>
								<td><a href="#" onClick="$('#attach3').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach3?></td>
								<td><a href="#" data-id="attach3" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
<!-- 							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach4_name" style="color:#999; font-style:italic"><?=$data['attach4']?></td>
								<td><a href="#" onClick="$('#attach4').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach4?></td>
								<td><a href="#" data-id="attach4" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr> -->	
						</tbody>
					</table>
				</div>
			
			</div>
			</fieldset>
			</form>

	</div>

	<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
	<div id="help">
		<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
		<div class="innerHelp">
			<?=$helpfile?>
		</div>
	</div>


		<!-- CHOOSE WORK EMAIL OR OTHER EMAIL POPUP -->

	<div class="modal fade" id="chooseEmail" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="top:110px;">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-user"></i>&nbsp; <?=strtoupper($lng['Choose Email'])?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span onclick="closeEmailModal();" aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body modal-tabs" style="padding:10px 0">
					
						<table class="basicTable inputs" border="0" style="width: 100%;">
							<tbody>
								<tr>
									<td>
										<th><?=$lng['Select']?></th>
									</td>
									<td>
										<select style="width: 92%;" id="chooseEmailSelect" name="chooseEmailSelect"></select>
									</td>
								</tr>
	
							</tbody>
						</table>

						<div class="clear" style="height:15px"></div>

						<button id="pingEmployee" class="btn btn-primary btn-fr mr-4" type="button" onclick="pingEmployeeAjax();"><i class="fa fa-map-marker"></i>&nbsp; <?=$lng['Ping Employee']?></button>
						
						<button class="btn btn-primary mr-1 btn-fr" type="button" data-dismiss="modal" onclick="closeEmailModal()"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>


						
				</div>
			</div>
		</div>
	</div>


		
	<? include('employee_new_edit_script.php')?>

	<script>
		
	$(document).ready(function() {

		// getBalance();
		
		var update = <?=json_encode($update)?>;
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
		var employees = <?=json_encode($employees)?>;
		var teams = <?=json_encode($teams)?>;
		var entities = <?=json_encode($entities)?>;
		var branches = <?=json_encode($branches)?>;
		var divisions = <?=json_encode($divisions)?>;
		var departments = <?=json_encode($departments)?>;
		
		/*$('#headBranch').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
		});	
		$('#headDepartment').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
		});	
		$('#lineManager').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
		});	
		$('#teamSupervisor').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
		});	
		$('#leaveApprove').devbridgeAutocomplete({
			 lookup: employees,
			 minChars: 0,
		});*/	
		
		$('#empTeam').on('change', function(e){
			if($(this).val() == ''){
				$('#empEntity').val('')
				$('#empBranch').val('')
				$('#empDivision').val('')
				$('#empDepartment').val('')
				$('#txtEntity').html('...')
				$('#txtBranch').html('...')
				$('#txtDivision').html('...')
				$('#txtDepartment').html('...')
			}else{
				$('#empEntity').val(teams[$(this).val()]['entity'])
				$('#empBranch').val(teams[$(this).val()]['branch'])
				$('#empDivision').val(teams[$(this).val()]['division'])
				$('#empDepartment').val(teams[$(this).val()]['department'])
				$('#txtEntity').html(entities[teams[$(this).val()]['entity']][lang])
				$('#txtBranch').html(branches[teams[$(this).val()]['branch']][lang])
				$('#txtDivision').html(divisions[teams[$(this).val()]['division']][lang])
				$('#txtDepartment').html(departments[teams[$(this).val()]['department']][lang])
			}
			//$('#sAlert').fadeIn(200);
			//$("#submitBtn").addClass('flash');
		})

		$('#emp_prefix').on('change', function(e){
			$.ajax({
				url: "ajax/get_employee_id.php",
				data: {prefix: this.value},
				success: function(result){
					//$('#dump').html(result); return false;
					$('#emp_id').val(result);
					$('#empID').html(result);
				}
			})
			//$('#sAlert').fadeIn(200);
			//$("#submitBtn").addClass('flash');
		})

		$("#infoForm").on('submit', function(e){ // SUBMIT EMPLOYEE FORM ///////////////////////////////////
			e.preventDefault();
			var err = 0;
			if($('input[name="emp_id"]').val() == ''){err = 1;}
			if($('select[name="title"]').val() == null){err = 1;}
			if($('input[name="firstname"]').val() == ''){err = 1;}
			if($('input[name="lastname"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			var data = new FormData(this);
			$.ajax({
				url: "ajax/update_employees.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					//$('#dump').html(result); return false;
					$("#submitBtn").removeClass('flash');
					$("#sAlert").fadeOut(200);
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						// if(!update){
							setTimeout(function(){location.reload();},1000);
						// }
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
	// PERSONAL FORM /////////////////////////////////////////////////////////////////////////////
		$('input, textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
		$('select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})

		$('input[name="firstname"],input[name="lastname"]').on('change', function(){
			$('input[name="bank_account_name"]').val($('input[name="firstname"]').val()+' '+$('input[name="lastname"]').val());
		})

	// DOCUMENTS ///////////////////////////////////////////////////////////////////////////////
		$("#att_idcard").change(function(){
			readAttURL(this,'#idcard_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_housebook").change(function(){
			readAttURL(this,'#housebook_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach1").change(function(){
			readAttURL(this,'#attach1_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach2").change(function(){
			readAttURL(this,'#attach2_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach3").change(function(){
			readAttURL(this,'#attach3_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach4").change(function(){
			readAttURL(this,'#attach4_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		
		$('.delDoc').confirmation({
			container: 'body',
			rootSelector: '.delDoc',
			singleton: true,
			animated: 'fade',
			placement: 'left',
			popout: true,
			html: true,
			title: '<?=$lng['Are you sure']?>',
			btnOkClass: 'btn btn-danger',
			btnOkLabel: '<?=$lng['Delete']?>',
			btnCancelClass: 'btn btn-success',
			btnCancelLabel: '<?=$lng['Cancel']?>',
			onConfirm: function() { 
				$.ajax({
					url: "ajax/delete_document.php",
					data:{emp_id: emp_id, doc: $(this).data('id')},
					success: function(result){
						//$('#dump').html(result); return false;
						location.reload();
					}
				});
			}
		});

		$("#emp_id").on('change', function(e){
			$.ajax({
				url: "ajax/check_employee_id.php",
				data: {emp_id: this.value},
				success: function(data){
					if(data == 1){
						$("#emp_id").focus().select();
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['ID exist already']?>',
							duration: 4,
						})
					}
				}
			});
		})
		
		
		var activeTabEmpInfo = localStorage.getItem('activeTabEmpInfo');
		if(activeTabEmpInfo){
			$('.nav-link[href="' + activeTabEmpInfo + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_personal"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabEmpInfo', $(e.target).attr('href'));
		});

	})


		function getShiftSchedule()
		{
			var teamVal = $('#empTeam').val();
			var cid= "<?php echo $cid?>";
			var cur_year= "<?php echo $cur_year?>";

			// alert(teamVal);
			// alert(cid);
			// alert(cur_year);


		    $.ajax({
				url: "ajax/get_shuedule_description.php",
				data: {'teamVal': teamVal,'cid':cid,'cur_year':cur_year},
				type: 'POST',
				success: function(response){

					var data = JSON.parse(response);
					$('#shiftplan').val(data.shift_schedule_desc);
					$('#teams').val(data.team_code);
				},
			});
		}

		function getTeamName()
		{
			var teams = $("#empTeam option:selected").text();

			var teamVal = $('#empTeam').val();
			var cid= "<?php echo $cid?>";
			var cur_year= "<?php echo $cur_year?>";

		    $.ajax({
				url: "ajax/getTeamNameVal.php",
				data: {'teamVal': teamVal,'cid':cid,'cur_year':cur_year},
				type: 'POST',
				success: function(response){

					var data = JSON.parse(response);
					var test3434 = $.trim(response);	

					var aade = test3434.replace(/\"/g, "");
					console.log(aade);
					var teamName = $('#team_name').val(aade);
					
				},
			});





			
		}

		// GET selected team on load 

		$( document ).ready(function() {

			var teamVal = $('#empTeam').val();
			var cid= "<?php echo $cid?>";
			var cur_year= "<?php echo $cur_year?>";

		    $.ajax({
				url: "ajax/get_shuedule_description.php",
				data: {'teamVal': teamVal,'cid':cid,'cur_year':cur_year},
				type: 'POST',
				success: function(response){

					var data = JSON.parse(response);
					$('#shiftplan').val(data.shift_schedule_desc);
					$('#teams').val(data.team_code);
					$('#team_name').val(data.team_name);
				},
			});
		});
		



	function getBalance(){

		var emp_id = '<?php echo $empID ?>';

		
		$.ajax({
			url: ROOT+"leave/ajax/get_leave_balance_employee_info.php",
			data: {emp_id: emp_id},
			success: function(result){
				$("input[name='annual_leave']").val(result);
			}

		});
	}





	$(document).ready(function() {

		// 30.71251244769549, 76.70800153316321


		var latVal = $('input[name="latitude"]').val();
		var longVal = $('input[name="longitude"]').val();


		// var latVal = 30.71251244769549;
		// var longVal = 76.70800153316321;
		
		function addInfoWindow(marker, message) {
			var infoWindow = new google.maps.InfoWindow({
					content: message
			});
			google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
			});
		}		
		function initialize() {
			var myLatlng = new google.maps.LatLng(latVal, longVal);
			var mapOptions = {
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				zoom: 19,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: myLatlng
			}
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var marker, i, myinfo;
			
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(latVal, longVal),
						map: map,
						title: 'Wartiz'
					});
					var infowindow = new google.maps.InfoWindow()
					google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
							infowindow.setContent(content);
							infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 
			
					
			$(window).resize(function() {
				 google.maps.event.trigger(map, "resize");
			});
			google.maps.event.addListener(map, "idle", function(){
				google.maps.event.trigger(map, 'resize'); 
			});			
		}
		initialize();


	});






		function closeEmailModal()
		{
			// empty the select field for email type 
			$('#chooseEmailSelect').empty();
		}
		function pingEmployeeLocation(that)
		{
			// get employee email 

			// show popup to select work email or other email 

			// send generate location link in email


			// var employeeEmail = $('#').val();
			// run ajax and get employee all emails 

			var regoID = $('#emp_id').val(); // never empty 

			$.ajax({
					url: "ajax/get_employee_email.php",
					data: {regoID: regoID},
					success: function(result){
						if(result)
						{
							var data = JSON.parse(result);
							$.each(data, function(key, value) {   

								if(key == 'personal_email') {
									var selectField = 'Personal Email';
								}else if(key == 'work_email'){
									var selectField = 'Work Email';
								}

							     $('#chooseEmailSelect').append($("<option></option>").attr("value", value).text(selectField));
								 $('#chooseEmail').modal('show');

							});
										
						}
					}
			})






		}

		function pingEmployeeAjax()
		{
			// get email 
			// check email if empty 
			// send to ajax 

			var selectedEmail  =  $('#chooseEmailSelect').val();
			var employee_id = $('#emp_id').val();

			if(selectedEmail == ''){
				// give error 
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i> Please select a valid email',
					duration: 2,
				})

			}
			else{
				// run ajax


				$.ajax({
						url: "ajax/send_location_employee_email.php",
						data: {selectedEmail: selectedEmail,employee_id:employee_id},
						success: function(result){
							if($.trim(result) == 'success')
							{
								$("body").overhang({
									type: "success",
									message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Email sent successfully']?>',
									duration: 2,
								})

								$("#submitBtn").removeClass('flash');
								$('#sAlert').fadeOut();
								closeEmailModal();	
								$('#chooseEmail').modal('hide');
							}
						}
				})

			}
		}


	$('#nextyearleavebalance').on('mousedown', function(e) {

		var annual_leave_dataYesNOss = '<?php echo $annual_leave_dataYesNOs ?>';
		if(annual_leave_dataYesNOss == '1' || annual_leave_dataYesNOss == '2')
		{
			e.preventDefault();
		   this.blur();
		   window.focus();
		}
	  
	})

	</script>

















