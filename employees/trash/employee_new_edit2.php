<?php
	
	$tax_settings = unserialize($pr_settings['tax_settings']);
	//var_dump($tax_settings); exit;
	$tax_info = unserialize($pr_settings['tax_info_'.$lang]);
	//var_dump($tax_info);
	$tax_err = unserialize($pr_settings['tax_err_'.$lang]);
	//var_dump($tax_err); exit;
	$fix_allow = getFixAllowances($pr_settings);
	//$var_allow = getVariableAllowances();
	//var_dump($fix_allow);
	$fix_deductions = unserialize($pr_settings['fix_deduct']);
	$fix_deduct = getUsedFixDeduct($lang);
	//var_dump($fix_deduct); //exit;
	//var_dump($fix_deductions); exit;

	$day_rate = 0;
	$hour_rate = 0;
	$workdays = ($pr_settings['days_month'] == 0 ? 30 : $pr_settings['days_month']);
	$dayhours = ($pr_settings['hours_day'] == 0 ? 8 : $pr_settings['hours_day']);

	$bank_codes = unserialize($sys_settings['bank_codes']);
	//var_dump($bank_codes);
	
	$standard_deduction = 0;
	$personal_care = 0;
	$sso = 0;
	$pvf = 0;
	$total_deductions = 0;

	if(isset($_GET['id'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////////////////////////////
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_GET['id']."'");
		$row = $res->fetch_assoc();
		foreach($row as $k=>$v){
			$data[$k] = $v;
		}
		$data['tax_parents'] = number_format($data['tax_parents'],1);
		$data['tax_disabled_person'] = number_format($data['tax_disabled_person'],1);
		$data['tax_child_bio'] = number_format($data['tax_child_bio'],1);
		$data['tax_child_bio_2018'] = number_format($data['tax_child_bio_2018'],1);
		$data['tax_child_adopted'] = number_format($data['tax_child_adopted'],1);
		//var_dump($data); exit;
		$tot_fixed = 0;
		$wage = $data['base_salary'];
		foreach($fix_allow as $k=>$v){
			if($v['rate'] == 'Y'){
				$wage += $data['fix_allow_'.$k];
			}
			$tot_fixed += $data['fix_allow_'.$k];
		}
		//var_dump($wage);
		//var_dump($day_rate);
		//var_dump($hour_rate);
		if(empty($data['image'])){$data['image'] = '../images/profile_image.jpg';}
		$update = 1;
		
	}else{ // NEW EMPLOYEE ////////////////////////////////////////////////////////////////////////////////////////
		
		$_GET['id'] = 0;
		
		$button_txt = $lng['Save new employee'];
		$update = 0;

		$sql = "SHOW COLUMNS FROM ".$cid."_employees";
		$res = $dbc->query($sql);
		while($row = $res->fetch_assoc()){
			 $data[$row['Field']] = '';
		}
		$data['contribute'] = 'Y';
		$data['modify_tax'] = 0;
		$data['calc_method'] = $pr_settings['tax_calc_method'];//'def';
		$data['emp_id'] = '';//getNewEmployeeID(); 
		$data['position'] = 1;
		$data['startdate'] = date('d-m-Y');
		$data['emp_status'] = $sys_settings['emp_status'];
		$data['emp_type'] = $sys_settings['emp_type'];
		$data['shiftplan'] = 'DT';
		$data['remaining_salary'] = 0;
		$data['notice_payment'] = 0;
		$data['selfie'] = 0;
		$data['paid_leave'] = 0;
		$data['severance'] = 0;
		$data['other_income'] = 0;
		$data['day_rate'] = 0;
		$data['hour_rate'] = 0;
		$data['calc_sso'] = $sys_settings['calc_sso'];
		$data['calc_pvf'] = $sys_settings['calc_pvf'];
		$data['calc_tax'] = $sys_settings['calc_tax'];
		$data['pr_calculation'] = 'Y';
		for($i=1;$i<=10;$i++){
			$data['fix_allow_'.$i] = 0;
		}
		$data['image'] = '../images/profile_image.jpg';
	}

	$birthdate = str_replace('/','-',$data['birthdate']);
	$idcard_exp = str_replace('/','-',$data['idcard_exp']);
	$drvlicense_exp = str_replace('/','-',$data['drvlicense_exp']);
	//$resign_date = str_replace('/','-',$data['resign_date']);
	$resign_date = '';
	if(!empty(trim($data['resign_date'])) && $data['resign_date'] != '0000-00-00'){
		$resign_date = date('d-m-Y', strtotime($data['resign_date']));
	}
	$startdate = str_replace('/','-',$data['startdate']);
	$startdate = date('d-m-Y', strtotime($data['startdate']));
	$probation_date = str_replace('/','-',$data['probation_date']);
	$pvf_reg_date = str_replace('/','-',$data['pvf_reg_date']);
	
	//$allowances = getAllowances($pr_settings);
	//var_dump($fix_allow);
	
	if(empty($data['att_idcard'])){$att_idcard = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_idcard = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_idcard'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_housebook'])){$att_housebook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_housebook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_housebook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_bankbook'])){$att_bankbook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_bankbook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_bankbook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_contract'])){$att_contract = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_contract = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_contract'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_employment'])){$att_employment = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_employment = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_employment'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach1'])){$attach1 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach1 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach1'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach2'])){$attach2 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach2 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach2'].'"><i class="fa fa-download fa-lg"></i></a>';}

	$data['emergency_contacts'] = unserialize($data['emergency_contacts']);
	if(!isset($data['emergency_contacts'][1])){
		$data['emergency_contacts'][1]['name'] = '';
		$data['emergency_contacts'][1]['relation'] = '';
		$data['emergency_contacts'][1]['home'] = '';
		$data['emergency_contacts'][1]['mobile'] = '';
		$data['emergency_contacts'][1]['work'] = '';
	}
	if(!isset($data['emergency_contacts'][2])){
		$data['emergency_contacts'][2]['name'] = '';
		$data['emergency_contacts'][2]['relation'] = '';
		$data['emergency_contacts'][2]['home'] = '';
		$data['emergency_contacts'][2]['mobile'] = '';
		$data['emergency_contacts'][2]['work'] = '';
	}
	//var_dump($data['emergency_contacts']);
	
	if($data['wage_type'] == 'day'){
		$wageType = 'Daily wage';
	}else{
		$wageType = $lng['Basic salary'];
	}

	$shiftplan = getShifTeams();
	if(!$shiftplan){$shiftplan = array();}
	//var_dump($shiftplan); exit;
	
	//$permitPath = ROOT.$cid.'/employees/workpermit/';
	/*$task['en']['expat'] = 'Invitation Expat';
	$task['en']['family'] = 'Invitation Family';
	$task['en']['boi'] = 'Application BOI';
	$task['en']['90days'] = 'Immigration 90 days';
	$task['en']['visa'] = 'Immigration VISA';
	$task['en']['tm30'] = 'TM30 application';
	$task['en']['permit'] = 'Workpermit';
	$task['en']['passport'] = 'Change Passport';
	$task['en']['resign'] = 'Resignation';
	
	$per_data = array();
	$per_data['family'] = array();*/
	/*$sql = "SELECT * FROM ".$cid."_workpermit WHERE emp_id = '".$_GET['id']."'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$per_data = $row;
			$per_data['family'] = unserialize($row['family']);
			//$data['selEmployee'] = $row['emp_id'].' - '.$row['name_'.$lang];
		}else{
			//$perData = 
		}
	}else{
		echo mysqli_error($dbc);
	}*/
	//var_dump($per_data); exit;
	
	/*if(!$per_data){
		$per_data['title'] = 0;
		$per_data['name_en'] = '';
		$per_data['name_th'] = '';
		$per_data['image'] = '';
		$per_data['nationality'] = '';
		$per_data['maritial'] = '';
		$per_data['blood_group'] = '';
		$per_data['birthdate'] = '';
		$per_data['address'] = '';
		$per_data['position'] = '';
		$per_data['job_en'] = '';
		$per_data['job_th'] = '';
		$per_data['family'] = '';
		$per_data['attach_passport'] = '';
		$per_data['attach_medical'] = '';
		$per_data['attach_job_en'] = '';
	}
	
	if(empty($per_data['family'])){
		$per_data['family'][1]['en'] = '';
		$per_data['family'][1]['th'] = '';
		$per_data['family'][1]['birthdate'] = '';
		$per_data['family'][1]['live'] = '';
		$per_data['family'][1]['relation'] = '';
	}*/
	
	//var_dump($data['image']); exit;
	
	/*$perData['attach_passport'] = '';
	<td class="tac" id="download_attach_passport"><i style="color:#ccc" class="fa fa-download fa-lg"></i></td>*/

?>
	<link rel="stylesheet" type="text/css" href="../assets/css/croppie_emp.css?<?=time()?>" />

<?	if(!empty($data['image'])){
		echo '<style>.croppie-container {background:url(../'.$data['image'].'?'.time().') no-repeat center center;}</style>';
	} ?>

   <h2 style="position:relative">
		<span class="hide-sm"><i class="fa fa-table"></i>&nbsp; <?=$lng['Employee register']?>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp;</span>
		<? if($update){ echo '<span>'.$data['emp_id'].' : '.$data[$lang.'_name'].'</span>';}else{echo $lng['Add employee'];}?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<div class="pannel left_pannel">
		<table border="0" class="employee-image">
			<tr>
				<td>
					<div id="upload-demo"></div>
					<input id="selectUserImg" type="file" name="user_img" />
					<button onClick="$('#selectUserImg').click();" style="width:100%; margin-top:2px" class="btn btn-primary" type="button"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
				</td>
			</tr>
		</table>
		
		<div style="padding-right:5px">	
		<table border="0" class="employee-info">
			<? if($update == 1){ ?>
			<? if(!empty($data['personal_phone'])){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['Phone']?> :</span>
					<span id="per_phone" style="padding-left:15px"><?=$data['personal_phone']?></span>
				</td>
			</tr>
			<? } if(!empty($data['personal_email'])){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['email']?> :</span>
					<span style="padding-left:15px"><a id="per_email" href="mailto:<?=$data['personal_email']?>"><?=$data['personal_email']?></a></span>
				</td>
			</tr>
			<? } if(!empty($startdate)){ ?>
			<tr>
				<td>
					<span style="color:#900; font-weight:600; display:block; padding-left:5px"><?=$lng['Joining date']?> :</span>
					<span style="padding-left:15px; line-height:120%; display:block">
						<span id="per_startdate"><?=$startdate?></span><br>
						<span id="per_serv_years" style="font-size:11px"></span>
					</span>
				</td>
			</tr>
			<? }} ?>
		</table>
		</div>
		
	</div>
	
	<div class="pannel main_pannel employee-profile">
			
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_personal" data-toggle="tab"><?=$lng['Personal']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_work" data-toggle="tab"><?=$lng['Work']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_benifits" data-toggle="tab"><?=$lng['Benefits']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_taxinfo" data-toggle="tab"><?=$lng['Tax']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_attachments" data-toggle="tab"><?=$lng['Documents']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_resign" data-toggle="tab"><?=$lng['End contract']?></a></li>
		</ul>
		<!--<form id="attachForm" enctype="multipart/form-data" style="width:0; height:0">
			 <input style="width:0; height:0" name="attach" id="attachFile" type="file" />
			 <input type="hidden" name="emp_id" value="<?=$data['emp_id']?>" />
			 <input type="hidden" id="attachField" name="field" />
		</form>-->
			
		<input name="pr_calculation" type="hidden" value="<?=$data['pr_calculation']?>">
		<input name="update" type="hidden" value="<?=$update?>">
			
		
		<div class="tab-content" style="height:calc(100% - 30px)">
			<button id="submitBtn" class="btn btn-primary" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

			<div class="tab-pane" id="tab_personal">
				<form id="personalForm" style="height:100%">
					<input name="update" type="hidden" value="<?=$update?>">
					<input name="emp_id" type="hidden" value="<?=$data['emp_id']?>">
					<div class="tab-content-left">
						<table class="basicTable editTable" border="0">
							<tbody>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Employee ID']?></th>
									<td><input maxlength="10" <? if($update){echo 'readonly';}?> style="font-weight:600" type="text" name="emp_id" id="emp_id" placeholder="..." value="<?=$data['emp_id']?>"></td>
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
									<td><input readonly style="cursor:pointer" class="date_year"  type="text" name="birthdate" id="birthdate" placeholder="..." value="<?=$birthdate?>"></td>
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
											<option value="x" selected disabled><?=$lng['Select']?></option>
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
											<option value="x" selected disabled><?=$lng['Select']?></option>
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
											<option value="x" selected disabled><?=$lng['Select']?></option>
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
											<option value="x" selected disabled><?=$lng['Select']?></option>
											<? foreach($military_status as $k=>$v){ ?>
												<option <? if($data['military_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Driving license No.']?></th>
									<td><input type="text" name="drvlicense_nr" placeholder="..." value="<?=$data['drvlicense_nr']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['License expiry date']?></th>
									<td><input type="text" readonly style="cursor:pointer" class="date_year" name="drvlicense_exp" placeholder="..." value="<?=$drvlicense_exp?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tab-content-right">
						<table class="basicTable editTable" border="0">
							<tbody>
								<tr style="background:#fcfcfc">
									<th><?=$lng['Registered address']?></th>
									<td><input type="text" name="address1" placeholder="..." value="<?=$data['address1']?>"></td>
								</tr>
								<tr style="background:#fcfcfc">
									<th><?=$lng['Sub district']?></th>
									<td><input type="text" name="sub_district" placeholder="..." value="<?=$data['sub_district']?>"></td>
								</tr>
								<tr style="background:#fcfcfc">
									<th><?=$lng['District']?></th>
									<td><input type="text" name="district" placeholder="..." value="<?=$data['district']?>"></td>
								</tr>
								<tr style="background:#fcfcfc">
									<th><?=$lng['Province']?></th>
									<td><input type="text" name="province" placeholder="..." value="<?=$data['province']?>"></td>
								</tr>
								<tr style="background:#fcfcfc">
									<th><?=$lng['Postal code']?></th>
									<td><input type="text" name="postnr" placeholder="..." value="<?=$data['postnr']?>"></td>
								</tr>
								<tr style="background:#fcfcfc; border-bottom:1px solid #ddd">
									<th><?=$lng['Country']?></th>
									<td><input type="text" name="country" placeholder="..." value="<?=$data['country']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Current address']?></th>
									<td><input type="text" name="address2" placeholder="..." value="<?=$data['address2']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Phone']?></th>
									<td><input type="text" name="personal_phone" id="personal_phone" placeholder="..." value="<?=$data['personal_phone']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['email']?></th>
									<td><input  type="text" name="personal_email" id="personal_email" placeholder="..." value="<?=$data['personal_email']?>"></td>
								</tr>
								<tr style="border:0">
									<th colspan="2" style="padding:5px 10px 0 10px; text-align:left"><?=$lng['Emergency contacts']?></th>
								</tr>
								<tr style="border:0">
									<td colspan="2" style="padding:2px 0 5px 5px">
										<? //=$lng['Emergency contacts']?>
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
												<td><input type="text" name="emergency_contacts[1][name]" placeholder="..." value="<?=$data['emergency_contacts'][1]['name']?>"></td>
												<td><input type="text" name="emergency_contacts[1][relation]" placeholder="..." value="<?=$data['emergency_contacts'][1]['relation']?>"></td>
												<td><input type="text" name="emergency_contacts[1][home]" placeholder="..." value="<?=$data['emergency_contacts'][1]['home']?>"></td>
												<td><input type="text" name="emergency_contacts[1][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][1]['mobile']?>"></td>
											</tr>
											<tr>
												<td><input type="text" name="emergency_contacts[2][name]" placeholder="..." value="<?=$data['emergency_contacts'][2]['name']?>"></td>
												<td><input type="text" name="emergency_contacts[2][relation]" placeholder="..." value="<?=$data['emergency_contacts'][2]['relation']?>"></td>
												<td><input type="text" name="emergency_contacts[2][home]" placeholder="..." value="<?=$data['emergency_contacts'][2]['home']?>"></td>
												<td><input type="text" name="emergency_contacts[2][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][2]['mobile']?>"></td>
											</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				<div style="clear:both"></div>
			</div>
			
			<div class="tab-pane" id="tab_work">
				<form id="workForm" style="height:100%">
					<div class="tab-content-left">
						<table class="basicTable editTable" border="0">
							<tbody>
								<tr>
									<th><?=$lng['Joining date']?></th>
									<td><input readonly style="cursor:pointer" class="datepick" type="text" name="startdate" id="startdate" placeholder="..." value="<?=$startdate?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Probation due date']?></th>
									<td><input type="text" readonly style="cursor:pointer" class="datepick" name="probation_date" id="probation_date" placeholder="..." value="<?=$probation_date?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Service years']?></th>
									<td class="pad410" id="serv_years"></td>
								</tr>
								<tr>
									<th><?=$lng['Position']?></th>
									<td>
										<select name="position" id="position">
											<? foreach($positions as $k=>$v){ ?>
												<option <? if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Employee status']?></th><td>
										<select id="empstatus" onChange="$('#emp_status').val(this.value)">
											<? foreach($emp_status as $k=>$v){ ?>
												<option <? if($data['emp_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Shift team']?></th>
									<td>
										<select name="shiftplan">
											<option disabled selected value=""><?=$lng['Select']?></option>
											<? foreach($shiftplan as $k=>$v){ ?>
												<option <? if($data['shiftplan'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<!--<tr>
									<th>Compensation level<? //=$lng['Shift team']?></th>
									<td>
										<select name="">
											<option disabled selected value=""><?=$lng['Select']?></option>
												<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="1">Level 1</option>
												<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="2">Level 2</option>
												<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="2">Level 3</option>
												<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="2">Level 4</option>
												<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="2">Level 5</option>
										</select>
									</td>
								</tr>-->
								<tr>
									<th><?=$lng['ID card']?></th>
									<td><input class="xtax_id_number" type="text" name="idcard_nr" placeholder="..." value="<?=$data['idcard_nr']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['ID card expiry date']?></th>
									<td><input style="cursor:pointer" class="date_year" name="idcard_exp"  type="text" placeholder="..." value="<?=$idcard_exp?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Tax ID no.']?></th>
									<td><input class="xtax_id_number" type="text" name="tax_id" placeholder="..." value="<?=$data['tax_id']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Place issued']?></th>
									<td><input type="text" name="issued" placeholder="..." value="<?=$data['issued']?>"></td>
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
								<tr>
									<th><?=$lng['Annual leave (days)']?></th>
									<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<?=$data['annual_leave']?>"></td>
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
							</tbody>
						</table>
					</div>
					<div class="tab-content-right">
						<table class="basicTable editTable" border="0">
							<tbody>
								<tr>
									<th><?=$lng['Bank code']?></th>
									<td><input readonly class="nofocus" type="text" name="bank_code" id="bank_code" placeholder="..." value="<?=$data['bank_code']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Bank name']?></th>
									<td>
										<select onChange="$('#bank_code').val(this.value)" name="bank_name" id="bank_name">
											<option selected value=""><?=$lng['Select']?></option>
											<? foreach($bank_codes as $k=>$v){ if($v['apply'] == 1){ ?>
												<option <? if($data['bank_code'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Bank branch']?></th>
									<td><input maxlength="4" type="text" name="bank_branch" placeholder="..." value="<?=$data['bank_branch']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Bank account no.']?></th>
									<td><input type="text" name="bank_account" placeholder="..." value="<?=$data['bank_account']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Bank account name']?></th>
									<td><input type="text" name="bank_account_name" placeholder="..." value="<?=$data['bank_account_name']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Automatic bank tranfer']?></th>
									<td>								
										<select name="bank_transfer">
											<? foreach($yesno as $k=>$v){ ?>
												<option <? if($data['bank_transfer'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<!--<tr>
									<th><?=$lng['Payment frequency']?></th>
									<td>
										<select name="frequency_pay">
											<? foreach($pay_frequency as $k=>$v){ ?>
												<option <? if($data['frequency_pay'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>-->
								<tr>
									<th><?=$lng['Payment type']?></th>
									<td>
										<select name="pay_type">
											<? foreach($pay_type as $k=>$v){ ?>
												<option <? if($data['pay_type'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Provident fund no.']?></th>
									<td><input type="text" name="pvf_nr" placeholder="..." value="<?=$data['pvf_nr']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['PVF registration date']?></th>
									<td><input type="text" style="cursor:pointer" class="date_year" name="pvf_reg_date" placeholder="..." value="<?=$pvf_reg_date?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				<div style="clear:both"></div>
			</div>
			
			<div class="tab-pane" id="tab_benifits">
				<form id="benefitsForm" style="height:100%">
					<div class="tab-content-left">
						<table class="basicTable editTable" border="0" style="margin-bottom:10px">
							<tbody>
								<tr>
									<th style="width:5%" id="wageType"><?=$wageType?></th>
									<td>
										<input style="width:70px" class="float72 sel calcRate" type="text" name="base_salary" id="base_salary" value="<?=$data['base_salary']?>">
										<!--<select name="salary_type" style="width:auto; float:right">
											<option <? if($data['salary_type'] == 'gross'){echo 'selected';}?> value="gross"><?=$lng['Gross amount']?></option>
											<option <? if($data['salary_type'] == 'net'){echo 'selected';}?> value="net"><?=$lng['Net amount']?></option>
										</select>-->
										<select class="calcRate" name="wage_type" id="wage_type" style="width:auto; float:right">
											<option <? if($data['wage_type'] == 'month'){echo 'selected';}?> value="month"><?=$lng['Monthly wage']?></option>
											<option <? if($data['wage_type'] == 'day'){echo 'selected';}?> value="day"><?=$lng['Daily wage']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Day rate']?></th>
									<td>
										<input readonly type="text" id="day_rate" value="<?=number_format($data['day_rate'],2)?>">
										<input type="hidden" name="day_rate" value="<?=$data['day_rate']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Hour rate']?></th>
									<td>
										<input readonly type="text" id="hour_rate" value="<?=number_format($data['hour_rate'],2)?>">
										<input type="hidden" name="hour_rate" value="<?=$data['hour_rate']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculate SSO']?></th>
									<td>
										<select id="calc_sso" name="calc_sso">
											<? foreach($noyes as $k=>$v){ ?>
												<option <? if($data['calc_sso'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculate Tax']?></th>
									<td>
										<select name="calc_tax">
											<? foreach($noyes as $k=>$v){ ?>
												<option <? if($data['calc_tax'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><?=$lng['Modify Tax amount']?></th>
									<td><input class="sel neg_numeric" type="text" name="modify_tax" placeholder="..." value="<?=$data['modify_tax']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Tax calculation method']?></th>
									<td>
										<select name="calc_method">
											<option <? if($data['calc_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['Calculate in Advance Method']?> (CAM)</option>
											<option <? if($data['calc_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['Accumulative Calculation Method']?> (ACM)</option>
											<option <? if($data['calc_method'] == 'ytd'){echo "selected";} ?> value="ytd">Year To Date<? //=$lng['Accumulative Calculation Method']?> (YTD)</option>
										</select>
									</td>
								</tr>
								
								<tr><td colspan="2" style="padding:5px"></td></tr>
								
								<tr>
									<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Pension fund']?> / <?=$lng['Provident fund']?></th>
								</tr>
								<tr>
									<th><?=$lng['Select fund']?></th>
									<td>
										<select id="calc_pvf" name="calc_pvf">
											<? foreach($pensionfund as $k=>$v){ ?>
												<option <? if($data['calc_pvf'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Rate employee']?></th>
									<td>
										<select name="pvf_rate_employee" id="pvf_rate_employee" class="calcTax">
										<? for($i=0;$i<=15;$i++){ ?>
											<option <? if($data['pvf_rate_employee'] == $i){echo 'selected';}?> value="<?=$i?>"><?=$i?> %</option>
										<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Rate employer']?></th>
									<td>
										<select name="pvf_rate_employer" id="pvf_rate_employer" >
										<? for($i=0;$i<=15;$i++){ ?>
											<option <? if($data['pvf_rate_employer'] == $i){echo 'selected';}?> value="<?=$i?>"><?=$i?> %</option>
										<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['PVF previous years employee']?></th>
									<td>
										<input name="pvf_prev_years_employee" class="sel numeric" type="text" value="<?=$data['pvf_prev_years_employee']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['PVF previous years employer']?></th>
									<td>
										<input name="pvf_prev_years_employer" class="sel numeric" type="text" value="<?=$data['pvf_prev_years_employer']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['PSF previous years employee']?></th>
									<td>
										<input name="psf_prev_years_employee" class="sel numeric" type="text" value="<?=$data['psf_prev_years_employee']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['PSF previous years employer']?></th>
									<td>
										<input name="psf_prev_years_employer" class="sel numeric" type="text" value="<?=$data['psf_prev_years_employer']?>">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tab-content-right">
						<table class="basicTable editTable" border="0" style="margin-bottom:10px">
							<tbody>
								<tr>
									<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Fixed allowances']?></th>
								</tr>
								<? if($fix_allow){ foreach($fix_allow as $k=>$v){ ?>
									<tr>
										<th><?=$v[$lang]?></th>
										<td>
											<input style="width:70px" class="numeric8 sel notnull calcRate fixAllow" type="text" name="fix_allow_<?=$k?>" placeholder="..." value="<?=$data['fix_allow_'.$k]?>">
											<? if($v['rate'] == 'Y'){ echo '<b style="color:#b00">'.$lng['Included in Day & Hour Rate'].'</b>';}?>
										</td>
									</tr>
								<? }}else{ ?>
									<tr>
										<td colspan="2" style="padding:4px 10px"><?=$lng['No allowances selected']?></td>
									</tr>
								<? } ?>
							</tbody>
						</table>
						
						<table class="basicTable editTable" border="0" style="margin-bottom:10px">
							<tbody>
								<tr>
									<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Fixed deductions']?></th>
								</tr>
								<? if($fix_deduct){ foreach($fix_deduct as $k=>$v){ ?>
									<tr>
										<th><?=$fix_deductions[$k][$lang]?></th>
										<td>
											<input style="width:70px" class="numeric8 sel notnull xcalcRate xfixAllow" type="text" name="fix_deduct_<?=$k?>" placeholder="..." value="<?=$data['fix_deduct_'.$k]?>">
										</td>
									</tr>
								<? }}else{ ?>
									<tr>
										<td colspan="2" style="padding:4px 10px"><?=$lng['No deductions selected']?></td>
									</tr>
								<? } ?>
							</tbody>
						</table>
						
						<table class="basicTable editTable" border="0">
							<tbody>
								<tr>
									<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Monthly recurring deductions from net salary']?></th>
								</tr>
								<tr>
									<th><?=$lng['Government house banking']?></th>
									<td><input class="sel float72" type="text" name="gov_house_banking" placeholder="..." value="<?=$data['gov_house_banking']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Savings']?></th>
									<td><input class="sel float72" type="text" name="savings" placeholder="..." value="<?=$data['savings']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Legal execution deduction']?></th>
									<td><input class="sel float72" type="text" name="legal_execution" placeholder="..." value="<?=$data['legal_execution']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Kor.Yor.Sor (Student loan)']?></th>
									<td><input class="sel float72" type="text" name="kor_yor_sor" placeholder="..." value="<?=$data['kor_yor_sor']?>">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				<div style="clear:both"></div>
			</div>
			
			<div class="tab-pane" id="tab_taxinfo">
				<form id="taxForm" style="height:100%">
				<? include('employee_tax_data.inc.php')?>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_attachments">
				<form id="attachForm" enctype="multipart/form-data" style="width:0; height:0">
					 <input type="hidden" name="emp_id" value="<?=$data['emp_id']?>" />
					 <input style="width:0; height:0" name="att_idcard" id="att_idcard" type="file" />
					 <input style="width:0; height:0" name="att_housebook" id="att_housebook" type="file" />
					 <input style="width:0; height:0" name="att_bankbook" id="att_bankbook" type="file" />
					 <input style="width:0; height:0" name="att_contract" id="att_contract" type="file" />
					 <input style="width:0; height:0" name="att_employment" id="att_employment" type="file" />
					 <input style="width:0; height:0" name="attach1" id="attach1" type="file" />
					 <input style="width:0; height:0" name="attach2" id="attach2" type="file" />
				</form>
				
				<table class="basicTable" border="0">
					<tbody>
					<thead>
						<tr>
							<th colspan="2" style="width:95%"><?=$lng['Documents']?></th>
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
							<td><a href="#" data-id="att_idcard" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						
						<tr>
							<th><?=$lng['Housebook']?></th>
							<td id="housebook_name" style="color:#999; font-style:italic"><?=$data['att_housebook']?></td>
							<td><a href="#" onClick="$('#att_housebook').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_housebook?></td>
							<td><a href="#" data-id="att_housebook" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Bankbook']?></th>
							<td id="bankbook_name" style="color:#999; font-style:italic"><?=$data['att_bankbook']?></td>
							<td><a href="#" onClick="$('#att_bankbook').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_bankbook?></td>
							<td><a href="#" data-id="att_bankbook" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Contract']?></th>
							<td id="contract_name" style="color:#999; font-style:italic"><?=$data['att_contract']?></td>
							<td><a href="#" onClick="$('#att_contract').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_contract?></td>
							<td><a href="#" data-id="att_contract" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Certificate of employment']?></th>
							<td id="employment_name" style="color:#999; font-style:italic"><?=$data['att_employment']?></td>
							<td><a href="#" onClick="$('#att_employment').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_employment?></td>
							<td><a href="#" data-id="att_employment" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['End contract']?></th>
							<td id="attach1_name" style="color:#999; font-style:italic"><?=$data['attach1']?></td>
							<td><a href="#" onClick="$('#attach1').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach1?></td>
							<td><a href="#" data-id="attach1" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach2_name" style="color:#999; font-style:italic"><?=$data['attach2']?></td>
							<td><a href="#" onClick="$('#attach2').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach2?></td>
							<td><a href="#" data-id="attach2" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
					</tbody>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_resign">
				<form id="resignForm" style="height:100%">
					<table class="basicTable editTable" border="0">
						<tbody>
							<tr>
								<th><?=$lng['Joining date']?></th>
								<td><input id="startdat" readonly type="text" value="<?=$startdate?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Notice date']?></th>
								<td><input type="text" readonly style="cursor:pointer" class="datepick" name="notice_date" placeholder="..." value="<?=$data['notice_date']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Reason of end contract']?></th>
								<td><input type="text" name="resign_reason" placeholder="..." value="<?=$data['resign_reason']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Resign date']?></th>
								<td><input type="text" style="cursor:pointer; width:109px" class="datepick" name="resign_date" placeholder="..." value="<?=$resign_date?>"><b style="color:#b00"><?=$lng['Last working day']?></b></td>
							</tr>
							<tr>
								<th><?=$lng['Employee status']?></th><td>
									<select name="emp_status" id="emp_status" onChange="$('#empstatus').val(this.value)" style="width:auto">
										<? foreach($emp_status as $k=>$v){ ?>
											<option <? if($data['emp_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
									<b style="color:#b00"><?=$lng['When resign date filled in...']?></b></td>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="height:10px"></td>
							</tr>
							<tr>
								<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Additional compensations at end of employement']?></th>
							</tr>
							<tr>
								<th><?=$lng['Remaining salary']?></th>
								<td><input class="float72 sel notnull" type="text" name="remaining_salary" placeholder="..." value="<?=$data['remaining_salary']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Notice payment']?></th>
								<td><input class="float72 sel notnull" type="text" name="notice_payment" placeholder="..." value="<?=$data['notice_payment']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Paid leave']?></th>
								<td><input class="float72 sel notnull" type="text" name="paid_leave" placeholder="..." value="<?=$data['paid_leave']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Severance']?></th>
								<td><input class="float72 sel notnull" type="text" name="severance" placeholder="..." value="<?=$data['severance']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Other income']?></th>
								<td><input class="float72 sel notnull" type="text" name="other_income" placeholder="..." value="<?=$data['other_income']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Remarks']?></th>
								<td><textarea placeholder="..." rows="4" name="remarks"><?=$data['remarks']?></textarea></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_permit">
				<div class="tab-content-left">

				</div>
				<div class="tab-content-right">

				</div>
				<div style="clear:both"></div>
			</div>
		
		</div>
		
	</div>
	
	<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
	<div id="help">
		<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
		<div class="innerHelp">
			<? //include('../helpfiles/add_edit_employee_'.$lang.'.htm');?>
			<?=$helpfile?>
		</div>
	</div>

	<? include('employee_new_edit_script.php')?>



















