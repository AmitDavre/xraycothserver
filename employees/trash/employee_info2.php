<?php
	
	//var_dump($_SESSION['rego']['empID']);
	if(!$_SESSION['rego']['employee_info']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}

	if(isset($_SESSION['rego']['empID']) && $_SESSION['rego']['empID'] != '0'){ // EDIT EMPLOYEE //////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
		$data['emergency_contacts'] = unserialize($data['emergency_contacts']);
		$update = 1;
	}else{ // NEW EMPLOYEE /////////////////////////////////////////////////////////////////////////////////
		$empID = 0;
		$button_txt = $lng['Save new employee'];
		$update = 0;

		$sql = "SHOW COLUMNS FROM ".$cid."_employees";
		$res = $dbc->query($sql);
		while($row = $res->fetch_assoc()){
			 $data[$row['Field']] = '';
		}
		
		$data['emp_id'] = '';//getNewEmployeeID(); 
		$data['image'] = '';
		$data['emergency_contacts'] = array();
		$data['joining_date'] = '';
		if($sys_settings['position'] != 'empty'){
			$data['joining_date'] = date('d-m-Y');
			$data['probation_date'] = date('d-m-Y', strtotime(date('d-m-Y').'+ 4 months'));
		}
		$data['branch'] = $sys_settings['branch'];
		$data['department'] = $sys_settings['department'];
		$data['team'] = $sys_settings['team'];
		$data['emp_group'] = $sys_settings['emp_group'];
		$data['emp_type'] = $sys_settings['emp_type'];
		$data['emp_status'] = $sys_settings['emp_status'];
		$data['account_code'] = $sys_settings['account_code'];
		$data['position'] = $sys_settings['position'];
		$data['date_position'] = '';
		if($sys_settings['date_start'] != 'empty'){
			$data['date_position'] = date('d-m-Y');
		}
		$data['time_reg'] = $sys_settings['time_reg'];
		$data['selfie'] = $sys_settings['selfie'];
		$data['annual_leave'] = $sys_settings['leeve'];
	}
	$prefix = explode(',', $sys_settings['id_prefix']);
	
	//var_dump($sys_settings);
	if(!isset($data['emergency_contacts'][1])){
		$data['emergency_contacts'][1]['name'] = '';
		$data['emergency_contacts'][1]['relation'] = '';
		$data['emergency_contacts'][1]['mobile'] = '';
		$data['emergency_contacts'][1]['work'] = '';
	}
	if(!isset($data['emergency_contacts'][2])){
		$data['emergency_contacts'][2]['name'] = '';
		$data['emergency_contacts'][2]['relation'] = '';
		$data['emergency_contacts'][2]['mobile'] = '';
		$data['emergency_contacts'][2]['work'] = '';
	}
	if(!isset($data['emergency_contacts'][3])){
		$data['emergency_contacts'][3]['name'] = '';
		$data['emergency_contacts'][3]['relation'] = '';
		$data['emergency_contacts'][3]['mobile'] = '';
		$data['emergency_contacts'][3]['work'] = '';
	}
	//var_dump($sys_settings); exit;

	if(empty($data['att_idcard'])){$att_idcard = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_idcard = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_idcard'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['att_housebook'])){$att_housebook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_housebook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_housebook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach1'])){$attach1 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach1 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach1'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach2'])){$attach2 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach2 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach2'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach3'])){$attach3 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach3 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach3'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach4'])){$attach4 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach4 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach4'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	$employees = getJsonUserEmployees($cid, $lang);
	//$emps = getEmployees($cid,0);

?>
   <h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> Employee info<? //=$lng['xxx']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i></span>
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

		<div class="tab-content" style="height:calc(100% - 30px)">
				
			<button id="submitBtn" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			<!--<form id="infoForm" enctype="multipart/form-data" style="height:100%">-->
			<!--<fieldset <? if(!$_SESSION['rego']['employee_info']['edit']){echo 'disabled';} ?>>-->
			
<!--			<? if($update == 0){ ?>
			<input type="hidden" name="bank_transfer" value="<?=$sys_settings['bank_transfer']?>">
			<input type="hidden" name="calc_pvf" value="<?=$sys_settings['calc_pvf']?>">
			<input type="hidden" name="pvf_rate_employee" value="<?=$sys_settings['pvf_rate_emp']?>">
			<input type="hidden" name="pvf_rate_employer" value="<?=$sys_settings['pvf_rate_com']?>">
			<input type="hidden" name="calc_method" value="<?=$sys_settings['tax_method']?>">
			<input type="hidden" name="calc_tax" value="<?=$sys_settings['calc_tax']?>">
			<input type="hidden" name="pnd" value="<?=$sys_settings['pnd']?>">
			<input type="hidden" name="calc_sso" value="<?=$sys_settings['calc_sso']?>">
			<input type="hidden" name="salary_type" value="<?=$sys_settings['contract_type']?>">
			<input type="hidden" name="wage_type" value="<?=$sys_settings['calc_base']?>">
			<input type="hidden" name="ot_rate" value="<?=$sys_settings['ot_rate']?>">
		<? } ?>
-->				
				
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
									<input maxlength="10" <? if($update){echo 'readonly';}?> style="font-weight:600" type="text" name="emp_id" id="emp_id" placeholder="..." value="<?=$data['emp_id']?>">
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
							<tr style="background:#fcfcfc">
								<th><?=$lng['Registered address']?></th>
								<td><input type="text" name="reg_address" placeholder="..." value="<?=$data['reg_address']?>"></td>
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
								<th colspan="2" style="border:0; text-align:left; height:30px; vertical-align:bottom"><?=strtoupper($lng['Emergency contacts'])?></th>
							</tr>
						<tbody>
							<tr style="border:0">
								<td colspan="2" style="padding:0">
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
											<td><input type="text" name="emergency_contacts[1][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][1]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[1][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][1]['mobile']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="emergency_contacts[2][name]" placeholder="..." value="<?=$data['emergency_contacts'][2]['name']?>"></td>
											<td><input type="text" name="emergency_contacts[2][relation]" placeholder="..." value="<?=$data['emergency_contacts'][2]['relation']?>"></td>
											<td><input type="text" name="emergency_contacts[2][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][2]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[2][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][2]['mobile']?>"></td>
										</tr>
										<tr>
											<td><input type="text" name="emergency_contacts[3][name]" placeholder="..." value="<?=$data['emergency_contacts'][2]['name']?>"></td>
											<td><input type="text" name="emergency_contacts[3][relation]" placeholder="..." value="<?=$data['emergency_contacts'][2]['relation']?>"></td>
											<td><input type="text" name="emergency_contacts[3][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][2]['mobile']?>"></td>
											<td><input type="text" name="emergency_contacts[3][mobile]" placeholder="..." value="<?=$data['emergency_contacts'][2]['mobile']?>"></td>
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
								<tr>
									<th colspan="2"><?=strtoupper('Work data')?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Joining date']?></th>
									<td><input readonly style="cursor:pointer" class="datepick" type="text" name="joining_date" id="joining_date" placeholder="..." value="<?=date('d-m-Y', strtotime($data['joining_date']))?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Probation due date']?></th>
									<td><input type="text" readonly style="cursor:pointer" class="datepick" name="probation_date" id="probation_date" placeholder="..." value="<?=$data['probation_date']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Service years']?></th>
									<td class="pad410" id="serv_years"></td>
								</tr>
								<tr>
									<th><?=$lng['Branch']?></th>
									<td>
										<select name="branch">
											<option value=""><?=$lng['Select']?></option>
											<? foreach($branches as $k=>$v){ ?>
												<option <? if($data['branch'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Department']?></th>
									<td>
										<select name="department">
											<option value=""><?=$lng['Select']?></option>
											<? foreach($departments as $k=>$v){ ?>
												<option <? if($data['department'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Team<? //=$lng['Team']?></th>
									<td>
										<select name="team">
											<option value=""><?=$lng['Select']?></option>
											<? foreach($teams as $k=>$v){ ?>
												<option <? if($data['team'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Employee group']?></th>
									<td>
										<select name="emp_group">
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
									<td><input type="text" readonly style="cursor:pointer" class="datepick" name="resign_date" placeholder="..." value="<?=$data['resign_date']?>"></td>
								</tr>
								<tr>
									<th><?=$lng['Resign reason']?></th>
									<td><input type="text" name="resign_reason" placeholder="..." value="<?=$data['resign_reason']?>"></td>
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
									<th><?=$lng['Accounting code']?></th>
									<td>
										<select name="account_code">
											<option <? if($data['account_code'] == 0){echo 'selected';}?> value="0"><?=$lng['Direct']?></option>
											<option <? if($data['account_code'] == 1){echo 'selected';}?> value="1"><?=$lng['Indirect']?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tab-content-right">
						<table class="basicTable editTable" border="0">
							<thead>
								<tr>
									<th colspan="2"><?=strtoupper('Responsibilities Section')?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Position']?></th>
									<td>
										<select name="position" id="position">
											<? foreach($positions as $k=>$v){ ?>
												<option <? if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Head of Branch<? //=$lng['Head of Branch']?></th>
									<td>
										<input type="text" name="head_branch" placeholder="<?=$lng['Type for hints']?>" id="headBranch" value="<?=$data['head_branch']?>">
								</tr>
								<tr>
									<th><?=$lng['Head of department']?></th>
									<td>
										<input type="text" name="head_department" placeholder="<?=$lng['Type for hints']?>" id="headDepartment" value="<?=$data['head_department']?>">
									</td>
								</tr>
								<tr>
									<th><?=$lng['Line manager']?></th>
									<td>
										<input type="text" name="line_manager" placeholder="<?=$lng['Type for hints']?>" id="lineManager" value="<?=$data['line_manager']?>">
								</tr>
								<tr>
									<th>Team supervisor<? //=$lng['Team supervisor']?></th>
									<td>
										<input type="text" name="team_suppervisor" placeholder="<?=$lng['Type for hints']?>" id="teamSupervisor" value="<?=$data['team_suppervisor']?>">
								</tr>
								<tr>
									<th>Date start Position<? //=$lng['Team supervisor']?></th>
									<td><input readonly type="text" name="date_position" style="cursor:pointer" class="datepick" placeholder="..." value="<?=$data['date_position']?>"></td>
								</tr>
								<tr>
									<td colspan="2" style="height:10px"></td>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th colspan="2"><?=strtoupper('Time data')?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Shift team']?></th>
									<td>
										<select name="shift_team">
											<option value=""><?=$lng['Select']?></option>
											<? //foreach($shiftplan as $k=>$v){ ?>
												<!--<option <? //if($data['shift_team'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>-->
											<? //} ?>
										</select>
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
								<tr>
									<th colspan="2"><?=strtoupper('Leave data')?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Annual leave (days)']?></th>
									<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<?=$data['annual_leave']?>"></td>
								</tr>
								<tr>
									<th>Leave approved by<? //=$lng['xxx']?></th>
									<td>
										<input type="text" name="leave_approve" placeholder="<?=$lng['Type for hints']?>" id="leaveApprove" value="<?=$data['leave_approve']?>">
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
								<th colspan="2"><?=strtoupper($lng['Documents'])?></th>
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
								<th><?=$lng['Additional file']?></th>
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
							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach3_name" style="color:#999; font-style:italic"><?=$data['attach3']?></td>
								<td><a href="#" onClick="$('#attach3').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach3?></td>
								<td><a href="#" data-id="attach3" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
							<tr>
								<th><?=$lng['Additional file']?></th>
								<td id="attach4_name" style="color:#999; font-style:italic"><?=$data['attach4']?></td>
								<td><a href="#" onClick="$('#attach4').click();"><i class="fa fa-upload fa-lg"></i></a></td>
								<td class="tac"><?=$attach4?></td>
								<td><a href="#" data-id="attach4" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>	
						</tbody>
					</table>
				</div>
			
			<!--</fieldset>-->
			<!--</form>-->
			
			</div>
			
	</div>
		
	<? include('employee_new_edit_script.php')?>

	<script>
		
	$(document).ready(function() {
		
		var update = <?=json_encode($update)?>;
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
		var employees = <?=json_encode($employees)?>;
		
		$('#headBranch').devbridgeAutocomplete({
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
		});	
		
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
						if(!update){
							setTimeout(function(){location.reload();},1000);
						}
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

		$(".calcRate").on('change', function(){
			var wage = parseFloat($('#base_salary').val());
			$.each(fix_allow, function(i, v){
				if(v.rate == 'Y'){
					wage += parseFloat($('input[name="fix_allow_'+i+'"]').val());
				}
			})
			//alert(wage);
			if($('#wage_type').val() == 'day'){
				var day_rate = parseInt(wage);
				var hour_rate = (parseInt(wage) / parseInt(dayhours));
			}else{
				var day_rate = (parseInt(wage) / parseInt(workdays));
				var hour_rate = (parseInt(wage) / parseInt(workdays) / parseInt(dayhours));
			}
			//alert(day_rate)
			//alert(hour_rate)
			$('input[name="day_rate"]').val(day_rate)
			$('input[name="hour_rate"]').val(hour_rate)
			$('#day_rate').val(parseFloat(day_rate).format(2))
			$('#hour_rate').val(parseFloat(hour_rate).format(2))
			
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
		
	</script>

















