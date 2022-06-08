<?
	
	//var_dump('$settings'); exit;

	$settings = array();
	$row = array();
	$res = $dba->query("SELECT * FROM rego_default_settings");
	if($row = $res->fetch_assoc()){
		$settings = $row;
	}

	$shiftplan = array();
	/*$res = $dba->query("SELECT code, name FROM rego_default_shiftplans");
	while($row = $res->fetch_assoc()){
		$shiftplan[$row['code']] = $row['name'];
	}*/
	
	$tax_settings = unserialize($settings['tax_settings']);
	//var_dump($tax_settings); exit;
	$tax_info = unserialize($settings['tax_info_'.$lang]);
	//var_dump($tax_info);
	$tax_err = unserialize($settings['tax_err_'.$lang]);
	//var_dump($tax_err); exit;
	$fix_allow = unserialize($settings['fix_allow']);
	//var_dump($fix_allowances); exit;
	
	$positions = unserialize($settings['positions']);
	$position = $positions[$lang];
	//$var_allow = getUsedVarAllow();
	//var_dump($position); exit;
	
	$fix_deduct = unserialize($settings['fix_deduct']);
	//var_dump($fix_deduct); //exit;
	//var_dump($fix_deductions); exit;

	$day_rate = 0;
	$hour_rate = 0;
	$workdays = ($settings['days_month'] == 0 ? 30 : $settings['days_month']);
	$dayhours = ($settings['hours_day'] == 0 ? 8 : $settings['hours_day']);

	$bank_codes = unserialize($settings['bank_codes']);
	//var_dump($bank_codes);
	
	$standard_deduction = 0;
	$personal_care = 0;
	$sso = 0;
	$pvf = 0;
	$total_deductions = 0;
	
	if(isset($_GET['id'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////////////////////////////
		$res = $dba->query("SELECT * FROM demo_employees WHERE emp_id = '".$_GET['id']."'");
		$row = $res->fetch_assoc();
		foreach($row as $k=>$v){
			$data[$k] = $v;
		}
		$data['tax_parents'] = number_format($data['tax_parents'],1);
		//$data['tax_disabled_person'] = number_format($data['tax_disabled_person'],1);
		$data['tax_child_bio'] = number_format($data['tax_child_bio'],1);
		//$data['tax_child_bio_2018'] = number_format($data['tax_child_bio_2018'],1);
		//$data['tax_child_adopted'] = number_format($data['tax_child_adopted'],1);
		//var_dump($data); //exit;
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

		$update = 1;
		
	}
	//var_dump($data);
	$birthdate = str_replace('/','-',$data['birthdate']);
	//$idcard_exp = str_replace('/','-',$data['idcard_exp']);
	//$drvlicense_exp = str_replace('/','-',$data['drvlicense_exp']);
	//$resign_date = str_replace('/','-',$data['resign_date']);
	$resign_date = '';
	//if(!empty(trim($data['resign_date'])) && $data['resign_date'] != '0000-00-00'){
		//$resign_date = date('d-m-Y', strtotime($data['resign_date']));
	//}
	$joining_date = str_replace('/','-',$data['joining_date']);
	$joining_date = date('d-m-Y', strtotime($data['joining_date']));
	//$pvf_reg_date = str_replace('/','-',$data['pvf_reg_date']);
	
	if($data['contract_type'] == 'day'){
		$wageType = 'Daily wage';
	}else{
		$wageType = $lng['Basic salary'];
	}
	//var_dump($data); exit;
	
?>

<link rel="stylesheet" type="text/css" href="../assets/css/croppie_emp.css?<?=time()?>" />

<style>
	.pannel {
		position:absolute; 
		top:0; 
		bottom:5px; 
		border:0px solid red;
		padding:15px;
		box-size:border-box;
		overflow:hidden;
	}
	.left_pannel {
		left:0; 
		width:205px;
		padding-right:5px;
	}
	.main_pannel {
		left:205px; 
		right:0;
		padding-left:5px;
	}
	b {
		font-weight:600;
	}
	input, select, textarea {
		background:transparent !important;
	}
	textarea{  
		box-sizing: border-box;
		resize: none;
		overflow:hidden;
	}
		.fileBtn {
			display:block;
			margin-top:5px;
		}
		.fileBtn [type="file"]{
			border:0;
			visibility:false;
			position:absolute;
			width:0px;
			height:0px;
		}
		.fileBtn label{
			background:#eee;
			background: linear-gradient(to bottom, #eee, #ddd);
			border-radius: 2px;
			border:1px #ccc solid;
			padding:1px 8px;
			line-height:18px;
			white-space:nowrap;
			color: #000;
			cursor: pointer;
			display: inline-block;
			xfont-family: 'Open Sans', sans-serif;
			font-size:13px;
			font-weight:400;
		}
		.fileBtn label:hover{
			background: linear-gradient(to bottom, #ddd, #eee);
		}
		.fileBtn p {
			padding:0 0 0 5px;
			margin:0;
			display:inline-block;
			xfont-family: Arial, Helvetica, sans-serif;
			font-size:13px;
		}
</style>
	
<?	if(!empty($data['image'])){
		echo '<style>.croppie-container {background:url(../'.$data['image'].'?'.time().') no-repeat center center;}</style>';
	} ?>

	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;<?=$lng['Demo employees']?>&nbsp; <i class="fa fa-arrow-circle-right"></i>&nbsp; <?=$lng['Edit'].' - '.$data['emp_id'].' : '.$data[$lang.'_name']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		
		<div class="pannel left_pannel">
			<table border="0">
				<tr>
					<td valign="top" style="text-align:right; padding:0">
						<div id="upload-demo" style="margin:0;"></div>
					</td>
				</tr>
				<tr>
					<td style="padding:0">
						<input id="selectUserImg" type="file" name="user_img" />
						<button onClick="$('#selectUserImg').click();" style="width:100%; margin-top:2px; border-radius:0" class="btn btn-primary btn-xs" type="button"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="pannel main_pannel">
		
			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link" data-target="#tab_personal" data-toggle="tab"><?=$lng['Personal']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_work" data-toggle="tab"><?=$lng['Work']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_benifits" data-toggle="tab"><?=$lng['Benefits']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_taxinfo" data-toggle="tab"><?=$lng['Tax deductions']?></a></li>
			</ul>
		
			<form id="employeeForm" style="height:100%">
				<input name="emp_id" type="hidden" value="<?=$data['emp_id']?>">
				<input name="pr_calculation" type="hidden" value="Y">
	
				<div class="tab-content" style="height:calc(100% - 30px);">
					<button style="position:absolute; top:15px; right:16px" class="btn btn-primary" id="submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
					<div class="tab-pane" id="tab_personal" style="height:100%; overflow-y:auto">
						<table style="width:100%; height:100%">
						<tr>
						<td style="width:45%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">					
							
							<table class="basicTable editTable" border="0">
								<tbody>
									<tr>
										<th style="width:5%"><i class="man"></i><?=$lng['Employee ID']?></th>
										<td><input maxlength="10" <? if($update){echo 'readonly';}?> style="font-weight:600" type="text" name="emp_id" id="emp_id" placeholder="..." value="<?=$data['emp_id']?>"></td>
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
										<th><?=$lng['ID card']?></th>
										<td><input type="text" name="idcard_nr" placeholder="..." value="<?=$data['idcard_nr']?>"></td>
									</tr>
								<tr>
									<th><?=$lng['Tax ID no.']?></th>
									<td><input class="tax_id_number" type="text" name="tax_id" placeholder="..." value="<?=$data['tax_id']?>"></td>
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
								</tbody>
							</table>
						
						</td>
						<td style="width:55%; padding-left:15px; vertical-align:top">
						
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
									<th><?=$lng['Phone']?></th>
									<td><input type="text" name="personal_phone" id="personal_phone" placeholder="..." value="<?=$data['personal_phone']?>"></td>
								</tr>
							</tbody>
						</table>
						
						</td>
						</tr>
						</table>
					</div>
					
					<div class="tab-pane" id="tab_work" style="height:100%; overflow-y:auto">
						<table style="width:100%; height:100%">
						<tr>
						<td style="width:45%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">
		
							<table class="basicTable editTable" border="0">
							<tbody>
								<tr>
									<th><?=$lng['Position']?></th>
									<td>
										<select name="position" id="position">
											<? foreach($position as $k=>$v){ ?>
												<option <? if($data['position'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Employee group']?></th><td>
										<select name="emp_group">
											<option <? if($data['emp_group'] == 's'){echo 'selected';}?> value="s"><?=$emp_group['s']?></option>
											<option <? if($data['emp_group'] == 'm'){echo 'selected';}?> value="m"><?=$emp_group['m']?></option>
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
								<!--<tr>
									<th><?=$lng['Shift team']?></th>
									<td>
										<select name="shiftplan">
											<? foreach($shiftplan as $k=>$v){ ?>
												<option <? if($data['shiftplan'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$k?> : <?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>-->
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
						
						</td>
						<td style="width:55%; padding-left:15px; vertical-align:top">
		
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
									<th><?=$lng['Payment type']?></th>
									<td>
										<select name="pay_type">
											<option selected value="cash"><?=$lng['Cash']?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
		
						</td>
						</tr>
						</table>
					</div>
					
					<div class="tab-pane" id="tab_benifits" style="height:100%; overflow-y:auto">
						<table style="width:100%; height:100%">
							<tr>
								<td style="width:50%; padding-right:15px; vertical-align:top; border-right:1px solid #ddd">
							
									<table class="basicTable editTable" border="0">
										<tbody>
											<tr>
												<th style="width:5%"><?=$lng['Contract type']?></th>
												<td>
													<select class="calcRate" name="contract_type" id="contract_type">
														<option <? if($data['contract_type'] == 'month'){echo 'selected';}?> value="month"><?=$lng['Monthly wage']?></option>
														<option <? if($data['contract_type'] == 'day'){echo 'selected';}?> value="day"><?=$lng['Daily wage']?></option>
													</select>
												</td>
											</tr>
											<tr>
												<th style="width:5%"><?=$lng['Calculation base']?></th>
												<td>
													<select name="calc_base">
														<option <? if($data['calc_base'] == 'gross'){echo 'selected';}?> value="gross"><?=$lng['Gross amount']?></option>
														<option <? if($data['calc_base'] == 'net'){echo 'selected';}?> value="net"><?=$lng['Net amount']?></option>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Basic salary']?></th>
												<td><input class="calcRate" type="text" id="base_salary" name="base_salary" value="<?=$data['base_salary']?>"></td>
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
												<th><?=$lng['Base OT rate']?></th>
												<td>
													<select name="base_ot_rate">
														<option <? if($data['base_ot_rate'] == 'cal'){echo 'selected';}?> value="cal">Calculated<? //=$lng['Gross amount']?></option>
														<option <? if($data['base_ot_rate'] == 'fix'){echo 'selected';}?> value="fix"><?=$lng['Fixed']?></option>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['OT rate']?></th>
												<td><input class="sel float2" type="text" name="ot_rate" value="<?=$data['ot_rate']?>"></td>
											</tr>
											<tr>
												<th><?=$lng['Calculate SSO']?></th>
												<td>
													<select id="calc_sso" name="calc_sso">
														<? foreach($noyes01 as $k=>$v){ ?>
															<option <? if($data['calc_sso'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
														<? } ?>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['SSO paid by']?></th>
												<td>
													<select name="sso_by">
														<option <? if($data['sso_by'] == '0'){echo 'selected';}?> value="0"><?=$lng['Employee']?></option>
														<option <? if($data['sso_by'] == '1'){echo 'selected';}?> value="1"><?=$lng['Company']?></option>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Calculate Tax']?></th>
												<td>
													<select name="calc_tax">
														<? foreach($calcTax as $k=>$v){ ?>
															<option <? if($data['calc_tax'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
														<? } ?>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Tax calculation method']?></th>
												<td>
													<select name="calc_method">
														<option <? if($data['calc_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['Calculate in Advance Method']?> (CAM)</option>
														<option <? if($data['calc_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['Accumulative Calculation Method']?> (ACM)</option>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Annual leave (days)']?></th>
												<td><input class="sel numeric2" type="text" name="annual_leave" placeholder="__" value="<?=$data['annual_leave']?>"></td>
											</tr>
											<tr><td colspan="2" style="padding:5px"></td></tr>
											
											<tr>
												<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Provident fund']?></th>
											</tr>
											<tr>
												<th><?=$lng['Calculate PVF']?></th>
												<td>
													<select id="calc_pvf" name="calc_pvf">
														<? foreach($noyes01 as $k=>$v){ ?>
															<option <? if($data['calc_pvf'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
														<? } ?>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Rate employee']?></th>
												<td>
													<select name="pvf_rate_emp" id="pvf_rate_emp" class="calcTax">
													<? for($i=0;$i<=15;$i++){ ?>
														<option <? if($data['pvf_rate_emp'] == $i){echo 'selected';}?> value="<?=$i?>"><?=$i?> %</option>
													<? } ?>
													</select>
												</td>
											</tr>
											<tr>
												<th><?=$lng['Rate employer']?></th>
												<td>
													<select name="pvf_rate_com" id="pvf_rate_com" >
													<? for($i=0;$i<=15;$i++){ ?>
														<option <? if($data['pvf_rate_com'] == $i){echo 'selected';}?> value="<?=$i?>"><?=$i?> %</option>
													<? } ?>
													</select>
												</td>
											</tr>
											
											
										</tbody>
									</table>
		
								</td><td style="width:50%; padding-left:15px; vertical-align:top">
							
									<table class="basicTable editTable" border="0" style="margin-bottom:10px">
										<tbody>
											<tr>
												<th colspan="2" style="text-align:left; background:#eee; border-bottom:1px solid #ccc; line-height:110%"><?=$lng['Fixed allowances']?></th>
											</tr>
											<? if($fix_allow){ foreach($fix_allow as $k=>$v){ if($v['apply'] == 1){ ?>
												<tr>
													<th><?=$v[$lang]?></th>
													<td>
														<input style="width:70px" class="numeric8 sel notnull calcRate fixAllow" type="text" name="fix_allow_<?=$k?>" placeholder="..." value="<?=$data['fix_allow_'.$k]?>">
														<? if($v['rate'] == 'Y'){ echo '<b style="color:#b00">'.$lng['Included in Day & Hour Rate'].'</b>';}?>
													</td>
												</tr>
											<? }}}else{ ?>
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
											<? if($fix_deduct){ foreach($fix_deduct as $k=>$v){ if($v['apply'] == 1){ ?>
												<tr>
													<th><?=$v[$lang]?></th>
													<td>
														<input style="width:70px" class="numeric8 sel notnull xcalcRate xfixAllow" type="text" name="fix_deduct_<?=$k?>" placeholder="..." value="<?=$data['fix_deduct_'.$k]?>">
													</td>
												</tr>
											<? }}}else{ ?>
												<tr>
													<td colspan="2" style="padding:4px 10px"><?=$lng['No deductions selected']?></td>
												</tr>
											<? } ?>
										</tbody>
									</table>
									
								</td>
							</tr>
						</table>
					</div>
					
					<div class="tab-pane" id="tab_taxinfo" style="height:100%; overflow-y:auto; overflow-x:hidden">
						<? include('demo_employee_tax_data.php')?>
					</div>

				</div>
			</form>
			
		</div>

	</div>
	

<? include('demo_employees_edit_script.php')?>













