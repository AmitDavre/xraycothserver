<?

	//var_dump($data); exit;
	//var_dump($sys_settings);
	$fix_allow = array();
	//$fix_allow = getUsedFixAllow($lang);
	$fix_allow = getFixAllowances($pr_settings);
	//var_dump($fix_allow);
	$fix_deductions = unserialize($sys_settings['fix_deduct']);
	$fix_deduct = getUsedFixDeduct($lang);

	if(empty($data['att_bankbook'])){$att_bankbook = 'No document uploaded';}else{$att_bankbook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_bankbook'].'"></a>';}
	
	if(empty($data['att_contract'])){$att_contract = 'No document uploaded';}else{$att_contract = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_contract'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach5'])){$attach5 = 'No document uploaded';}else{$attach5 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach5'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach6'])){$attach6 = 'No document uploaded';}else{$attach6 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach6'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach7'])){$attach7 = 'No document uploaded';}else{$attach7 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach7'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	if(empty($data['attach8'])){$attach8 = 'No document uploaded';}else{$attach8 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach8'].'"><i class="fa fa-download fa-lg"></i></a>';}
	
	$data['emergency_contacts'] = unserialize($data['emergency_contacts']);
	//var_dump($data['emergency_contacts']); exit;
	
	$account_code[0] = $lng['Direct'];
	$account_code[1] = $lng['Indirect'];
	$bank_codes = unserialize($rego_settings['bank_codes']);
	//var_dump($bank_codes); exit;
	
	$emp_tax_deductions = $data['emp_tax_deductions'];
	$emp_tax_deductions += $data['tax_standard_deduction'];
	$emp_tax_deductions += $data['tax_personal_allowance'];
	$emp_tax_deductions += $data['tax_allow_pvf'];
	$emp_tax_deductions += $data['tax_allow_sso'];
	
	//var_dump($entity_banks); exit;
	$gender[''] = '-';
	$maritial[''] = '-';
	$religion[''] = '-';
	$military_status[''] = '-';
	$bank_codes['']['en'] = '-';
	$bank_codes['']['th'] = '-';
	$pensionfund[''] = '-';
	$entity_banks = array();
	
	$edata = getEntityData($data['entity']);
	if(!empty($edata['banks'])){$entity_banks = $edata['banks'];}


?>

<style>
	.pannel {
		position:absolute; 
		top:130px; 
		bottom:10px; 
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

<div style="width:100%">
	
	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Personal data']?></h2>

	<div class="pannel left_pannel">
		<? include('emp_picture.php'); ?>
	</div>
	
	<div class="pannel main_pannel">

		<ul class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" data-target="#personal" data-toggle="tab"><?=$lng['Personal']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#contact" data-toggle="tab"><?=$lng['Contact']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#work" data-toggle="tab"><?=$lng['Work']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#financial" data-toggle="tab"><?=$lng['Financial']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#benifits" data-toggle="tab"><?=$lng['Benefits']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#taxinfo" data-toggle="tab"><?=$lng['Tax']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#attachments" data-toggle="tab"><?=$lng['Documents']?></a></li>
			<? if(!empty($data['resign_date'])){ ?>
			<li class="nav-item"><a class="nav-link" data-target="#tab_resign" data-toggle="tab"><?=$lng['End contract']?></a></li>
			<? } ?>
		</ul>
		
		<div class="tab-content" style="height:calc(100% - 25px)">
			
			<div class="tab-pane" id="personal" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0">
					<tbody>
					<tr>
						<th style="width:5%"><?=$lng['Employee ID']?></th>
						<td><?=$data['emp_id']?></td>
					</tr>
					<tr>
						<th><?=$lng['Scan ID']?></th>
						<td><?=$data['sid']?></td>
					</tr>
					<tr>
						<th><?=$lng['Title']?></th>
						<td><?=$title[$data['title']]?></td>
					</tr>
					<tr>
						<th><?=$lng['First name']?></th>
						<td><?=$data['firstname']?></td>
					</tr>
					<tr>
						<th><?=$lng['Last name']?></th>
						<td><?=$data['lastname']?></td>
					</tr>
					<tr>
						<th><?=$lng['Name in English']?></th>
						<td><?=$data['en_name']?></td>
					</tr>
					<tr>
						<th><?=$lng['Birthdate']?></th>
						<td><?=$data['birthdate']?></td>
					</tr>
					<tr>
						<th><?=$lng['Age']?></th>
						<td><?=getAge($data['birthdate'])?></td>
					</tr>
					<tr>
						<th><?=$lng['Nationality']?></th>
						<td><?=$data['nationality']?></td>
					</tr>
					<tr>
						<th><?=$lng['Gender']?></th>
						<td><?=$gender[$data['gender']]?></td>
					</tr>
					<tr>
						<th><?=$lng['Maritial status']?></th>
						<td><?=$maritial[$data['maritial']]?></td>
					</tr>
					<tr>
						<th><?=$lng['Religion']?></th>
						<td><?=$religion[$data['religion']]?></td>
					</tr>
					<tr>
						<th><?=$lng['Military status']?></th>
						<td><?=$military_status[$data['military_status']]?></td>
					</tr>
					<tr>
						<th><?=$lng['Height']?> (cm)<? //=$lng['cm']?></th>
						<td><?=$data['height']?></td>
					</tr>
					<tr>
						<th><?=$lng['Weight']?> (kg)</th>
						<td><?=$data['weight']?></td>
					</tr>
					<tr>
						<th><?=$lng['Blood group']?></th>
						<td><?=$data['bloodtype']?></td>
					</tr>
					<tr>
						<th><?=$lng['Driving license No.']?></th>
						<td><?=$data['drvlicense_nr']?></td>
					</tr>
					<tr>
						<th><?=$lng['License expiry date']?></th>
						<td><?=$data['drvlicense_exp']?></td>
					</tr>
					<tr>
						<th><?=$lng['ID card']?></th>
						<td><?=$data['idcard_nr']?></td>
					</tr>
					<tr>
						<th><?=$lng['ID card expiry date']?></th>
						<td><?=$data['idcard_exp']?></td>
					</tr>
					<tr>
						<th><?=$lng['Tax ID no.']?></th>
						<td><?=$data['tax_id']?></td>
					</tr>
					</tbody>
				</table>
			</div>
			
			<div class="tab-pane" id="contact" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0" style="margin-bottom:6px">
					<tbody>
					<tr style="background:#f9fff9">
						<th><?=$lng['Registered address']?></th>
						<td><?=$data['reg_address']?></td>
					</tr>
					<tr style="background:#f9fff9">
						<th><?=$lng['Sub district']?></th>
						<td><?=$data['sub_district']?></td>
					</tr>
					<tr style="background:#f9fff9">
						<th><?=$lng['District']?></th>
						<td><?=$data['district']?></td>
					</tr>
					<tr style="background:#f9fff9">
						<th><?=$lng['Province']?></th>
						<td><?=$data['province']?></td>
					</tr>
					<tr style="background:#f9fff9">
						<th><?=$lng['Postal code']?></th>
						<td><?=$data['postnr']?></td>
					</tr>
					<tr style="background:#f9fff9">
						<th><?=$lng['Country']?></th>
						<td><?=$data['country']?></td>
					</tr>
					<tr>
						<th><?=$lng['Current address']?></th>
						<td><?=$data['cur_address']?></td>
					</tr>
					<tr>
						<th><?=$lng['Personal phone']?></th>
						<td><?=$data['personal_phone']?></td>
					</tr>
					<tr>
						<th><?=$lng['Personal email']?></th>
						<td><?=$data['personal_email']?></td>
					</tr>
					<tr>
						<th><?=$lng['Work phone']?></th>
						<td><?=$data['work_phone']?></td>
					</tr>
					<tr>
						<th><?=$lng['Work email']?></th>
						<td><?=$data['work_email']?></td>
					</tr>
					</tbody>
				</table>

				<table class="basicTable" border="0">
					<thead>
						<tr style="border-bottom:1px #ccc solid; line-height:100%">
							<th colspan="4"><?=$lng['Emergency contacts']?></th>
						</tr>
						<tr style="border-bottom:1px #ccc solid; line-height:100%">
							<th><?=$lng['Name']?></th>
							<th><?=$lng['Relationship']?></th>
							<th><?=$lng['Mobile phone']?></th>
							<th><?=$lng['Work phone']?></th>
						</tr>
					</thead>
					<tbody>
					<? if(isset($data['Emergency contacts'][1])){ ?>
					<tr>
						<td><?=$data['emergency_contacts'][1]['name']?>&nbsp;</td>
						<td><?=$data['emergency_contacts'][1]['relation']?></td>
						<td><?=$data['emergency_contacts'][1]['home']?></td>
						<td><?=$data['emergency_contacts'][1]['mobile']?></td>
					</tr>
					<? } if(isset($data['Emergency contacts'][2])){ ?>
					<tr>
						<td><?=$data['emergency_contacts'][2]['name']?>&nbsp;</td>
						<td><?=$data['emergency_contacts'][2]['relation']?></td>
						<td><?=$data['emergency_contacts'][2]['home']?></td>
						<td><?=$data['emergency_contacts'][2]['mobile']?></td>
					</tr>
					<? } if(!isset($data['Emergency contacts'])){ ?>
					<tr>
						<td colspan="4"><?=$lng['No data available']?></td>
					</tr>
					<? } ?>
					</tbody>
				</table>
			</div>
			
			<div class="tab-pane" id="work" style="height:100%; overflow-Y:auto">
				<table border="0" style="width:100%; height:100%">
					<tr>
						<td style="width:50%; vertical-align:top; padding-right:20px; border-right:2px solid #eee">
							<table class="basicTable" border="0">
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['WORK DATA']?></th>
									</tr>
								</thead>
								<tbody>
								<tr>
									<th><?=$lng['Joining date']?></th>
									<td><? if(!empty($data['joining_date'])){echo date('d-m-Y', strtotime($data['joining_date']));}?></td>
								</tr>
								<tr>
									<th><?=$lng['Probation due date']?></th>
									<td><? if(!empty($data['probation_date'])){echo date('d-m-Y', strtotime($data['probation_date']));}?></td>
								</tr>
								<tr>
									<th><?=$lng['Service years']?></th>
									<td><?=getAge($data['joining_date'])?></td>
								</tr>
								<tr>
									<th><?=$lng['Entity']?></th>
									<td><?=$entities[$data['entity']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Branch']?></th>
									<td><?=$branches[$data['branch']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Division']?></th>
									<td><?=$divisions[$data['division']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Department']?></th>
									<td><?=$departments[$data['department']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Team']?></th>
									<td><?=$teams[$data['team']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Employee group']?></th>
									<td><?=$emp_groep[$data['emp_group']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Employee type']?></th>
									<td><?=$emp_type[$data['emp_type']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Resign date']?></th>
									<td><? if(!empty($data['resign_date'])){echo date('d-m-Y', strtotime($data['resign_date']));}?></td>
								</tr>
								<tr>
									<th><?=$lng['Resign reason']?></th>
									<td><?=$data['resign_reason']?></td>
								</tr>
								<tr>
									<th><?=$lng['Employee status']?></th>
									<td><?=$emp_status[$data['emp_status']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Accounting code']?></th>
									<td><?=$account_code[$data['account_code']]?></td>
								</tr>
								</tbody>
							</table>
							
							</td><td style="width:50%; vertical-align:top; padding-left:20px">
							
							<table class="basicTable" border="0">
								<thead>
									<tr style="line-height:100%">
										<th colspan="2"><?=$lng['RESPONSIBILITIES SECTION']?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th><?=$lng['Position']?></th>
										<td><?=$positions[$data['position']][$lang]?></td>
									</tr>
									<tr>
										<th><?=$lng['Head of branch']?></th>
										<td><? if(!empty($data['head_branch'])){echo $branches[$data['head_branch']][$lang];}?></td>
									</tr>
									<tr>
										<th><?=$lng['Head of division']?></th>
										<td><? if(!empty($data['head_division'])){echo $divisions[$data['head_division']][$lang];}?></td>
									</tr>
									<tr>
										<th><?=$lng['Head of department']?></th>
										<td><? if(!empty($data['head_department'])){echo $departments[$data['head_department']][$lang];}?></td>
									</tr>
									<tr>
										<th><?=$lng['Team supervisor']?></th>
										<td><? if(!empty($data['team_supervisor'])){echo $teams[$data['team_supervisor']][$lang];}?></td>
									</tr>
									<tr>
										<th><?=$lng['Date start Position']?></th>
										<td><?=$data['date_position']?></td>
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
									<tr>
										<th><?=$lng['Shift team']?></th>
										<td><?=$data['shift_team']?></td>
									</tr>
									<tr>
										<th><?=$lng['Time registration']?></th>
										<td>
												<? if($data['time_reg'] == 0){echo $lng['No'];}?>
												<? if($data['time_reg'] == 1){echo $lng['Yes'];}?>
										</td>
									</tr>
									<tr>
										<th><?=$lng['Take selfie']?></th>
										<td>
											<? if($data['selfie'] == 0){echo $lng['No'];}?>
											<? if($data['selfie'] == 1){echo $lng['Yes'];}?>
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
									<tr>
										<th><?=$lng['Annual leave (days)']?></th>
										<td><?=$data['annual_leave']?></td>
									</tr>
									<tr>
										<th><?=$lng['Leave approved by']?></th>
										<td><?=$data['leave_approve']?></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>		
		
			<div class="tab-pane" id="financial" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['FINANCIAL DATA']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Bank code']?></th>
							<td><?=$data['bank_code']?></td>
						</tr>
						<tr>
							<th><?=$lng['Bank name']?></th>
							<td><?=$bank_codes[$data['bank_code']][$lang]?></td>
						</tr>
						<tr>
							<th><?=$lng['Bank branch']?></th>
							<td><?=$data['bank_branch']?></td>
						</tr>
						<tr>
							<th><?=$lng['Bank account no.']?></th>
							<td><?=$data['bank_account']?></td>
						</tr>
						<tr>
							<th><?=$lng['Bank account name']?></th>
							<td><?=$data['bank_account_name']?></td>
						</tr>
						<tr>
							<th><?=$lng['Payment type']?></th>
							<td>
								<? if($data['pay_type'] == 'cash'){
											echo $lng['Cash'];
										}elseif($data['pay_type'] == 'cheque'){
											echo $lng['Cheque'];
										}else{
											if($entity_banks){
												echo $entity_banks[$data['pay_type']][$lang];
											}
										}
								?>
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="basicTable">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2" style="width:25%"><?=$lng['PENSION FUND PSF']?></th>
							<th colspan="2" style="width:25%"><?=$lng['PROVIDENT FUND PVF']?></th>
							<th style="width:50%"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th style="background:#ffe"><?=$lng['Calculate PSF']?></th>
							<td style="background:#ffe"><? if($data['calc_psf']){echo $lng['Yes'];}else{echo $lng['No'];}?></td>
							<th style="background:#efe"><?=$lng['Calculate PVF']?></th>
							<td style="background:#efe"><? if($data['calc_pvf']){echo $lng['Yes'];}else{echo $lng['No'];}?></td>
							<td></td>
						</tr>
						<tr>
							<th style="background:#ffe"><?=$lng['Rate employee']?> %</th>
							<td style="background:#ffe"><?=$data['psf_rate_emp']?></td>
							<th style="background:#efe"><?=$lng['Rate employee']?> %</th>
							<td style="background:#efe"><?=$data['pvf_rate_emp']?></td></td>
							<td></td>
						</tr>
						
						<tr>
							<th style="background:#ffe"><?=$lng['Rate employer']?> %</th>
							<td style="background:#ffe"><?=$data['psf_rate_com']?></td>
							<th style="background:#efe"><?=$lng['Rate employer']?> %</th>
							<td style="background:#efe"><?=$data['pvf_rate_com']?></td></td>
							<td></td>
						</tr>
						<tr>
							<th style="background:#ffe"><?=$lng['Previous years employee']?> <?=$lng['THB']?></th>
							<td style="background:#ffe"><?=$data['psf_prev_years_emp']?></td>
							<th style="background:#efe"><?=$lng['Previous years employee']?> <?=$lng['THB']?></th>
							<td style="background:#efe"><?=$data['pvf_prev_years_emp']?></td>
							<td></td>
						</tr>
						<tr>
							<th style="background:#ffe"><?=$lng['Previous years employer']?> <?=$lng['THB']?></th>
							<td style="background:#ffe"><?=$data['psf_prev_years_com']?></td>
							<th style="background:#efe"><?=$lng['Previous years employer']?> <?=$lng['THB']?></th>
							<td style="background:#efe"><?=$data['pvf_prev_years_com']?></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				
				<table class="basicTable" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['REVENU DEPARTMENT']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Tax calculation method']?></th>
							<td>
								<? if($data['calc_method'] == 'cam'){echo $lng['Calculate in Advance Method'].'(CAM)';}?>
								<? if($data['calc_method'] == 'acm'){echo $lng['Accumulative Calculation Method'].'(ACM)';}?>
								<? if($data['calc_method'] == 'ytd'){echo $lng['Year To Date'].'(YTD)';}?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Calculate Tax']?></th>
							<td>
								<? if($data['calc_tax'] == '1'){echo $lng['PND'].'1';}?>
								<? if($data['calc_tax'] == '3'){echo $lng['PND'].'3';}?>
								<? if($data['calc_tax'] == '0'){echo $lng['no Tax'];}?>
							</td>
						</tr>
						<tr>
							<th style="width:5%"><?=$lng['Modify Tax amount']?></th>
							<td><?=$data['modify_tax']?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:10px"></td>
						</tr>
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['SOCIAL SECURITY OFFICE']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Calculate SSO']?></th>
							<td><? if($data['calc_sso']){echo $lng['Yes'];}else{echo $lng['No'];}?></td>
						</tr>
						<tr>
							<th><?=$lng['SSO paid by']?></th>
							<td>
								<? if($data['sso_by']){echo $lng['Company'];}else{echo $lng['Employee'];}?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>		
		
			<div class="tab-pane" id="benifits" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['BASIC SALARY']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th style="width:5%"><?=$lng['Contract type']?></th>
							<td>
								<? if($data['contract_type'] == 'month'){echo $lng['Monthly wage'];}?>
								<? if($data['contract_type'] == 'day'){echo $lng['Daily wage'];}?>
							</td>
						</tr>
						<tr>
							<th style="width:5%"><?=$lng['Calculation base']?></th>
							<td>
								<? if($data['calc_base'] == 'gross'){echo $lng['Gross amount'];}?>
								<? if($data['calc_base'] == 'net'){echo $lng['Net amount'];}?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Basic salary']?></th>
							<td><?=$data['base_salary']?></td>
						</tr>
						<tr>
							<th><?=$lng['Day rate']?></th>
							<td><?=number_format($data['day_rate'],2)?></td>
						</tr>
						<tr>
							<th><?=$lng['Hour rate']?></th>
							<td><?=number_format($data['hour_rate'],2)?></td>
						</tr>
						<tr>
							<th><?=$lng['Base OT rate']?></th>
							<td>
								<? if($data['base_ot_rate'] == 'cal'){echo $lng['Calculated'];}?>
								<? if($data['base_ot_rate'] == 'fix'){echo $lng['Fixed'];}?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT rate']?></th>
							<td><?=$data['ot_rate']?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:10px"></td>
						</tr>
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['MONTHLY LEGAL DEDUCTIONS FROM NET SALARY']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Government house banking']?></th>
							<td><?=$data['gov_house_banking']?></td>
						</tr>
						<tr>
							<th><?=$lng['Savings']?></th>
							<td><?=$data['savings']?></td>
						</tr>
						<tr>
							<th><?=$lng['Legal execution deduction']?></th>
							<td><?=$data['legal_execution']?></td>
						</tr>
						<tr>
							<th><?=$lng['Kor.Yor.Sor (Student loan)']?></th>
							<td><?=$data['kor_yor_sor']?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:10px"></td>
						</tr>
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['FIXED ALLOWANCES']?></th>
						</tr>
					</thead>
					<tbody>
						<? if($fix_allow){ foreach($fix_allow as $k=>$v){ ?>
						<tr>
							<th><?=$v[$lang]?></th>
							<td>
								<?=$data['fix_allow_'.$k]?>
								<? if($v['rate'] == 'Y'){ echo '<b style="color:#b00">'.$lng['Included in Day & Hour Rate'].'</b>';}?>
							</td>
						</tr>
						<? }}else{ ?>
						<tr>
							<td colspan="2" style="padding:4px 10px"><?=$lng['No allowances selected']?></td>
						</tr>
						<? } ?>
						<tr>
							<td colspan="2" style="height:10px"></td>
						</tr>
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['FIXED DEDUCTIONS']?></th>
						</tr>
					</thead>
					<tbody>
						<? if($fix_deduct){ foreach($fix_deduct as $k=>$v){ ?>
							<tr>
								<th><?=$fix_deductions[$k][$lang]?></th>
								<td><?=$data['fix_deduct_'.$k]?></td>
							</tr>
						<? }}else{ ?>
							<tr>
								<td colspan="2" style="padding:4px 10px"><?=$lng['No deductions selected']?></td>
							</tr>
						<? } ?>
					</tbody>
				</table>
			</div>		

			<div class="tab-pane" id="taxinfo" style="height:100%; overflow-Y:auto">
				<table border="0" style="width:100%; table-layout:fixed; height:100%">
					<tr>
						<td style="padding-right:20px; border-right:2px solid #eee; width:50%; vertical-align:top">
							<table class="basicTable" border="0">
								<tbody>
								<tr>
									<th style="width:5%"><?=$lng['Standard deduction']?></th>
									<td style="width:110px" class="tar"><?=number_format($data['tax_standard_deduction'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th style="width:5%"><?=$lng['Personal care']?></th>
									<td class="tar"><?=number_format($data['tax_personal_allowance'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th style="width:5%"><?=$lng['Spouse care']?></th>
									<td class="tar"><?=number_format($data['tax_allow_spouse'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Parents care']?></th>
									<td class="tar"><?=number_format($data['tax_allow_parents'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Parents in law care']?></th>
									<td class="tar"><?=number_format($data['tax_allow_parents_inlaw'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Care disabled person']?></th>
									<td class="tar"><?=number_format($data['tax_allow_disabled_person'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Child care - biological']?></th>
									<td class="tar"><?=number_format($data['tax_allow_child_bio'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Child care - biological 2018/19/20']?></th>
									<td class="tar"><?=number_format($data['tax_allow_child_bio_2018'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Child care - adopted']?></th>
									<td class="tar"><?=number_format($data['tax_allow_child_adopted'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Child birth (Baby bonus)']?></th>
									<td class="tar"><?=number_format($data['tax_allow_child_birth'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Own health insurance']?></th>
									<td class="tar"><?=number_format($data['tax_allow_own_health'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Own life insurance']?></th>
									<td class="tar"><?=number_format($data['tax_allow_own_life_insurance'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Health insurance parents']?></th>
									<td class="tar"><?=number_format($data['tax_allow_health_parents'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Life insurance spouse']?></th>
									<td class="tar"><?=number_format($data['tax_allow_life_insurance_spouse'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Pension fund']?></th>
									<td class="tar"><?=number_format($data['tax_allow_pension_fund'],2)?></td>
									<td></td>
								</tr>
								</tbody>
							</table>	
						</td><td style="padding-left:20px; vertical-align:top; width:50%">
							<table class="basicTable" border="0">
								<tbody>
								<? if($data['tax_allow_pvf'] != 0){ ?>
								<tr>
									<th><?=$lng['Provident fund']?></th>
									<td class="tar"><?=number_format($data['tax_allow_pvf'],2)?></td>
									<td></td>
								</tr>
								<? } ?>
								<tr>
									<th><?=$lng['NSF']?></th>
									<td style="width:110px" class="tar"><?=number_format($data['tax_allow_nsf'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['RMF']?></th>
									<td class="tar"><?=number_format($data['tax_allow_rmf'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Social Security Fund']?></th>
									<td class="tar"><?=number_format($data['tax_allow_sso'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['LTF']?></th>
									<td class="tar"><?=number_format($data['tax_allow_ltf'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Home loan interest']?></th>
									<td class="tar"><?=number_format($data['tax_allow_home_loan_interest'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Donation charity']?></th>
									<td class="tar"><?=number_format($data['tax_allow_donation_charity'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Donation flooding']?></th>
									<td class="tar"><?=number_format($data['tax_allow_donation_flood'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Donation education']?></th>
									<td class="tar"><?=number_format($data['tax_allow_donation_education'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Exemption disabled person <65 yrs']?></th>
									<td class="tar"><?=number_format($data['tax_allow_exemp_disabled_under'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Exemption tax payer => 65yrs']?></th>
									<td class="tar"><?=number_format($data['tax_allow_exemp_payer_older'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['First home buyer']?></th>
									<td class="tar"><?=number_format($data['tax_allow_first_home'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Year-end shopping']?></th>
									<td class="tar"><?=number_format($data['tax_allow_year_end_shopping'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Domestic tour']?></th>
									<td class="tar"><?=number_format($data['tax_allow_domestic_tour'],2)?></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Other allowance']?></th>
									<td class="tar"><?=number_format($data['tax_allow_other'],2)?></td>
									<td></td>
								</tr>
								<tr style="background:#eee">
									<th><?=$lng['Total deductions']?></th>
									<td style="font-weight:600" class="tar"><?=number_format($emp_tax_deductions,2)?></td>
									<td></td>
								</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>
			</div>		
		
			<div class="tab-pane" id="attachments" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['DOCUMENTS']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Bankbook']?></th>
							<td><?=$att_bankbook?></td>
						</tr>	
						<tr>
							<th><?=$lng['Contract']?></th>
							<td><?=$att_contract?></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td><?=$attach5?></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td><?=$attach6?></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td><?=$attach7?></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td><?=$attach8?></td>
						</tr>	
					</tbody>
				</table>
			</div>
		
			<div class="tab-pane" id="tab_resign" style="height:100%; overflow-Y:auto">
				<table class="basicTable" border="0">
					<thead>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Joining date']?></th>
							<td><?=date('d-m-Y', strtotime($data['joining_date']))?></td>
						</tr>
						<tr>
							<th><?=$lng['Notice date']?></th>
							<td><?=$data['notice_date']?></td>
						</tr>
						<tr>
							<th><?=$lng['End date']?></th>
							<td><?=$data['resign_date']?>&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#b00"><?=$lng['Last working day']?></b></td>
						</tr>
						<tr>
							<th><?=$lng['End reason']?></th>
							<td><?=$data['resign_reason']?></td>
						</tr>
						<tr>
							<th><?=$lng['Employee status']?></th>
							<td><?=$emp_status[$data['emp_status']]?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:10px"></td>
						</tr>
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['ADDITIONAL COMPENSATIONS AT END OF EMPLOYEMENT']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Remaining salary']?></th>
							<td><?=$data['remaining_salary']?></td>
						</tr>
						<tr>
							<th><?=$lng['Notice payment']?></th>
							<td><?=$data['notice_payment']?></td>
						</tr>
						<tr>
							<th><?=$lng['Paid leave']?></th>
							<td><?=$data['paid_leave']?></td>
						</tr>
						<tr>
							<th><?=$lng['Severance']?></th>
							<td><?=$data['severance']?></td>
						</tr>
						<tr>
							<th><?=$lng['Other income']?></th>
							<td><?=$data['other_income']?></td>
						</tr>
						<tr>
							<th><?=$lng['Remarks']?></th>
							<td><?=$data['remarks']?></td>
						</tr>
					</tbody>
				</table>
			</div>
		
		</div>
		
	</div>	
			
</div>

<script type="text/javascript">
		
	$(document).ready(function() {
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabPD', $(e.target).data('target'));
		});
		var activeTab = localStorage.getItem('activeTabPD');
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#personal"]').tab('show');
		}
	})

</script>




















