<?php
	
	if(isset($_SESSION['rego']['empID'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = '../images/profile_image.jpg';}
		$update = 1;
		
	}else{ // NEW EMPLOYEE /////////////////////////////////////////////////////////////////////////////////
		$empID = 0;
		
		$button_txt = $lng['Save new employee'];
		$update = 0;

		/*$sql = "SHOW COLUMNS FROM ".$cid."_employees";
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
		$data['image'] = '../images/profile_image.jpg';*/
	}
	//var_dump($data); exit;
	
	if(empty($data['att_idcard'])){$att_idcard = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_idcard = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_idcard'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_housebook'])){$att_housebook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_housebook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_housebook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_bankbook'])){$att_bankbook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_bankbook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_bankbook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_contract'])){$att_contract = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_contract = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_contract'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_employment'])){$att_employment = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_employment = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_employment'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach1'])){$attach1 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach1 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach1'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach2'])){$attach2 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach2 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach2'].'"><i class="fa fa-download fa-lg"></i></a>';}

?>
	<link rel="stylesheet" type="text/css" href="../assets/css/croppie_emp.css?<?=time()?>" />

<?	if(!empty($data['image'])){
		echo '<style>.croppie-container {background:url(../'.$data['image'].'?'.time().') no-repeat center center;}</style>';
	} ?>

   <h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Employee register']?>&nbsp; <i class="fa fa-arrow-circle-right"></i> </span>
		<? if($update){ echo '<span>'.$data['emp_id'].' : '.$data[$lang.'_name'].'</span>';}else{echo $lng['Add employee'];}?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel employee-profile">
			
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_personal" data-toggle="tab"><?=$lng['Personal']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_contcact" data-toggle="tab"><?=$lng['Contact']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_work" data-toggle="tab"><?=$lng['Work']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_documents" data-toggle="tab"><?=$lng['Documents']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 30px)">
			<button id="submitBtn" class="btn btn-primary" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			
			<div class="tab-pane" id="tab_personal">
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
							<td><input readonly style="cursor:pointer" class="date_year"  type="text" name="birthdate" id="birthdate" placeholder="..." value="<? //=$birthdate?>"></td>
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
									<option value="" selected disabled><?=$lng['Select']?></option>
									<? foreach($military_status as $k=>$v){ ?>
										<option <? if($data['military_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Height']?> (cm)<? //=$lng['cm']?></th>
							<td><input type="text" name="height" placeholder="..." value="<? //=$data['height']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Weight']?> (kg)</th>
							<td><input type="text" name="weight" placeholder="..." value="<? //=$data['weight']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Blood group']?></th>
							<td><input type="text" name="bloodgroup" placeholder="..." value="<? //=$data['bloodgroup']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Driving license No.']?></th>
							<td><input type="text" name="drvlicense_nr" placeholder="..." value="<?=$data['drvlicense_nr']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['License expiry date']?></th>
							<td><input type="text" readonly style="cursor:pointer" class="date_year" name="drvlicense_exp" placeholder="..." value="<? //=$drvlicense_exp?>"></td>
						</tr>
						<tr>
							<th><?=$lng['ID card']?></th>
							<td><input class="xtax_id_number" type="text" name="idcard_nr" placeholder="..." value="<?=$data['idcard_nr']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['ID card expiry date']?></th>
							<td><input style="cursor:pointer" class="date_year" name="idcard_exp"  type="text" placeholder="..." value="<? //=$idcard_exp?>"></td>
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
							<th><?=$lng['Personal phone']?></th>
							<td><input type="text" name="personal_phone" id="personal_phone" placeholder="..." value="<?=$data['personal_phone']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Personal email']?></th>
							<td><input  type="text" name="personal_email" id="personal_email" placeholder="..." value="<?=$data['personal_email']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Work phone']?></th>
							<td><input type="text" name="personal_phone" id="work_phone" placeholder="..." value="<?=$data['personal_phone']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Work email']?></th>
							<td><input  type="text" name="personal_email" id="work_email" placeholder="..." value="<?=$data['personal_email']?>"></td>
						</tr>
						<tr>
							<th colspan="2" style="border:0; text-align:left; height:30px; vertical-align:bo"><?=$lng['Emergency contacts']?></th>
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
										<td><input type="text" name="emergency_contacts[1][name]" placeholder="..." value="<? //=$data['emergency_contacts'][1]['name']?>"></td>
										<td><input type="text" name="emergency_contacts[1][relation]" placeholder="..." value="<? //=$data['emergency_contacts'][1]['relation']?>"></td>
										<td><input type="text" name="emergency_contacts[1][home]" placeholder="..." value="<? //=$data['emergency_contacts'][1]['home']?>"></td>
										<td><input type="text" name="emergency_contacts[1][mobile]" placeholder="..." value="<? //=$data['emergency_contacts'][1]['mobile']?>"></td>
									</tr>
									<tr>
										<td><input type="text" name="emergency_contacts[2][name]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['name']?>"></td>
										<td><input type="text" name="emergency_contacts[2][relation]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['relation']?>"></td>
										<td><input type="text" name="emergency_contacts[2][home]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['home']?>"></td>
										<td><input type="text" name="emergency_contacts[2][mobile]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['mobile']?>"></td>
									</tr>
									<tr>
										<td><input type="text" name="emergency_contacts[3][name]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['name']?>"></td>
										<td><input type="text" name="emergency_contacts[3][relation]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['relation']?>"></td>
										<td><input type="text" name="emergency_contacts[3][home]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['home']?>"></td>
										<td><input type="text" name="emergency_contacts[3][mobile]" placeholder="..." value="<? //=$data['emergency_contacts'][2]['mobile']?>"></td>
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
								<th colspan="2">Work data</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Joining date']?></th>
								<td><input readonly style="cursor:pointer" class="datepick" type="text" name="startdate" id="startdate" placeholder="..." value="<? //=$startdate?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Probation due date']?></th>
								<td><input type="text" readonly style="cursor:pointer" class="datepick" name="probation_date" id="probation_date" placeholder="..." value="<? //=$probation_date?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Service years']?></th>
								<td class="pad410" id="serv_years"></td>
							</tr>
							<tr>
								<th><?=$lng['Branch']?></th>
								<td><input type="text" name="branch" placeholder="..." value="<? //=$branch?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Department']?></th>
								<td><input type="text" name="department" placeholder="..." value="<? //=$department?>"></td>
							</tr>
							<tr>
								<th>Team<? //=$lng['Team']?></th>
								<td><input type="text" name="team" placeholder="..." value="<? //=$team?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Employee group']?></th>
								<td>
									<select name="position" id="position">
										<? foreach($emp_groep as $k=>$v){ ?>
											<option <? //if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Employee type']?></th>
								<td>
									<select name="position" id="position">
										<? foreach($emp_type as $k=>$v){ ?>
											<option <? //if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Resign date']?></th>
								<td><input type="text" readonly style="cursor:pointer" class="datepick" name="xxx" placeholder="..." value="<? //=$probation_date?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Resign reason']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Employee status']?></th><td>
									<select id="empstatus" onChange="$('#emp_status').val(this.value)">
										<? foreach($emp_status as $k=>$v){ ?>
											<option <? //if($data['emp_status'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
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
								<th colspan="2">Responsibilities Section</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Position']?></th>
								<td>
									<select name="position" id="position">
										<? foreach($positions as $k=>$v){ ?>
											<option <? //if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v['en']?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>Head of Branch<? //=$lng['Head of Branch']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Head of department']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Line manager']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<th>Team supervisor<? //=$lng['Team supervisor']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<th>Date start Position<? //=$lng['Team supervisor']?></th>
								<td><input type="text" name="" placeholder="..." value="<? //=$xxx?>"></td>
							</tr>
							<tr>
								<td colspan="2" style="height:10px"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="2">Time data</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Shift team']?></th>
								<td>
									<select name="shiftplan">
										<option disabled selected value=""><?=$lng['Select']?></option>
										<? //foreach($shiftplan as $k=>$v){ ?>
											<!--<option <? //if($data['shiftplan'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>-->
										<? //} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Time registration']?></th>
								<td>
									<select name="selfie">
										<option <? if($data['selfie'] == 0){echo 'selected';}?> value="0"><?=$lng['No']?></option>
										<option <? if($data['selfie'] == 1){echo 'selected';}?> value="1"><?=$lng['Yes']?></option>
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
								<th colspan="2">Leave data</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Annual leave (days)']?></th>
								<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<?=$data['annual_leave']?>"></td>
							</tr>
							<tr>
								<th>Leave approved by<? //=$lng['Annual leave (days)']?></th>
								<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<? //=$data['annual_leave']?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
			
			</div>
			
			<div class="tab-pane" id="tab_documents">
				<form id="attachForm" enctype="multipart/form-data" style="width:0; height:0">
					 <input type="hidden" name="emp_id" value="<?=$data['emp_id']?>" />
					 <!--<input style="width:0; height:0; visibility:0" name="att_idcard" id="att_idcard" type="file" />
					 <input style="width:0; height:0; visibility:0" name="att_housebook" id="att_housebook" type="file" />
					 <input style="width:0; height:0; visibility:0" name="att_bankbook" id="att_bankbook" type="file" />
					 <input style="width:0; height:0; visibility:0" name="att_contract" id="att_contract" type="file" />
					 <input style="width:0; height:0; visibility:0" name="att_employment" id="att_employment" type="file" />
					 <input style="width:0; height:0; visibility:0" name="attach1" id="attach1" type="file" />
					 <input style="width:0; height:0; visibility:0" name="attach2" id="attach2" type="file" />-->
				</form>
				
				<table class="basicTable" border="0">
					<thead>
						<tr>
							<th colspan="2"><?=$lng['Documents']?></th>
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
							<td id="bankbook_name" style="color:#999; font-style:italic"><?=$data['att_bankbook']?></td>
							<td><a href="#" onClick="$('#att_bankbook').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_bankbook?></td>
							<td><a href="#" data-id="att_bankbook" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="contract_name" style="color:#999; font-style:italic"><?=$data['att_contract']?></td>
							<td><a href="#" onClick="$('#att_contract').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_contract?></td>
							<td><a href="#" data-id="att_contract" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="employment_name" style="color:#999; font-style:italic"><?=$data['att_employment']?></td>
							<td><a href="#" onClick="$('#att_employment').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_employment?></td>
							<td><a href="#" data-id="att_employment" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach1_name" style="color:#999; font-style:italic"><?=$data['attach1']?></td>
							<td><a href="#" onClick="$('#attach1').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach1?></td>
							<td><a href="#" data-id="attach1" class="delDoc"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
					</tbody>
				</table>
			</div>
			
		</div>
		
	</div>
		
	<? include('employee_new_edit_script.php')?>



















