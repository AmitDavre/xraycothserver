<?php
	
	$banks = unserialize($rego_settings['bank_codes']);
	
	$fix_allow = array();
	$fix_allow = getUsedFixAllow($lang);
	
	if(empty($data['att_idcard'])){
		$data['att_idcard'] = $lng['Document not available'];
	}else{
		$data['att_idcard'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['att_idcard'].'">'.$data['att_idcard'].'</a>';
	}
	if(empty($data['att_housebook'])){
		$data['att_housebook'] = $lng['Document not available'];
	}else{
		$data['att_housebook'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['att_housebook'].'">'.$data['att_housebook'].'</a>';
	}
	if(empty($data['att_bankbook'])){
		$data['att_bankbook'] = $lng['Document not available'];
	}else{
		$data['att_bankbook'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['att_bankbook'].'">'.$data['att_bankbook'].'</a>';
	}
	if(empty($data['att_contract'])){
		$data['att_contract'] = $lng['Document not available'];
	}else{
		$data['att_contract'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['att_contract'].'">'.$data['att_contract'].'</a>';
	}
	if(empty($data['attach1'])){
		$data['attach1'] = $lng['Document not available'];
	}else{
		$data['attach1'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['attach1'].'">'.$data['attach1'].'</a>';
	}
	if(empty($data['attach2'])){
		$data['attach2'] = $lng['Document not available'];
	}else{
		$data['attach2'] = '<a target="_blank" href="'.ROOT.$cid.'/employees/'.$data['attach2'].'">'.$data['attach2'].'</a>';
	}
	
	$data['emergency_contacts'] = unserialize($data['emergency_contacts']);
	if(empty($data['emergency_contacts'][0]['name'])){$data['emergency_contacts'] = array();}
	//var_dump($data['emergency_contacts']);
	
	$emp_tax_deductions = $data['emp_tax_deductions'];
	$emp_tax_deductions += $data['tax_standard_deduction'];
	$emp_tax_deductions += $data['tax_personal_allowance'];
	$emp_tax_deductions += $data['tax_allow_pvf'];
	$emp_tax_deductions += $data['tax_allow_sso'];

?>
	<div class="container-fluid" style="padding:0">
            
		<div class="accordion" id="accordionExample1">
			
			<div class="item">
				<div class="accordion-header">
					<button class="btn" type="button" data-toggle="collapse" data-target="#personal">
						<?=$lng['Personal data']?>
					</button>
				</div>
				<div id="personal" class="accordion-body show" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['Employee ID']?></th>
									<td><?=$data['emp_id']?></td>
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
									<td><? if(!empty($data['gender'])){echo $gender[$data['gender']];}?></td>
								</tr>
								<tr>
									<th><?=$lng['Maritial status']?></th>
									<td><? if(!empty($data['maritial'])){echo $maritial[$data['maritial']];}?></td>
								</tr>
								<tr>
									<th><?=$lng['Religion']?></th>
									<td><? if(!empty($data['religion'])){echo $religion[$data['religion']];}?></td>
								</tr>
								<tr>
									<th><?=$lng['Military status']?></th>
									<td><? if(!empty($data['military_status'])){echo $military_status[$data['military_status']];}?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#contact">
						<?=$lng['Contact data']?>
					</button>
				</div>
				<div id="contact" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['Current address']?></th>
									<td><?=$data['cur_address']?></td>
								</tr>
								<tr>
									<th><?=$lng['Regular address']?></th>
									<td><?=$data['reg_address']?></td>
								</tr>
								<tr>
									<th><?=$lng['District']?></th>
									<td><?=$data['district']?></td>
								</tr>
								<tr>
									<th><?=$lng['Sub district']?></th>
									<td><?=$data['sub_district']?></td>
								</tr>
								<tr>
									<th><?=$lng['Province']?></th>
									<td><?=$data['province']?></td>
								</tr>
								<tr>
									<th><?=$lng['Postal code']?></th>
									<td><?=$data['postnr']?></td>
								</tr>
								<tr>
									<th><?=$lng['Country']?></th>
									<td><?=$data['country']?></td>
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
									<th style="vertical-align:baseline"><?=$lng['Emergency contacts']?></th>
									<td style="padding:5px 0 5px 5px !important">
										<table class="basicTable" border="0" <? if(empty($data['emergency_contacts'])){echo 'style="display:none"';}?>>
											<thead>
											<tr>
												<th><?=$lng['Name']?></th>
												<th><?=$lng['Relationship']?></th>
												<th><?=$lng['Home phone']?></th>
												<th><?=$lng['Mobile phone']?></th>
												<th><?=$lng['Work phone']?></th>
											<tr>
											</thead>
											<tbody>
											<? if(!$data['emergency_contacts']){ foreach($data['emergency_contacts'] as $k=>$v){ ?>
											<tr>
												<td><?=$v['name']?></td>
												<td><?=$v['relation']?></td>
												<td><?=$v['home']?></td>
												<td><?=$v['mobile']?></td>
												<td><?=$v['work']?></td>
											</tr>
											<? }} ?>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#financial">
						<?=$lng['Financial data']?>
					</button>
				</div>
				<div id="financial" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['ID Card Tax ID']?></th>
									<td class="tax_id_number"><?=$data['idcard_nr']?></td>
								</tr>
								<tr>
									<th><?=$lng['ID card expiry date']?></th>
									<td><?=$data['idcard_exp']?></td>
								</tr>
								<tr>
									<th><?=$lng['Place issued']?></th>
									<td><?=$data['issued']?></td>
								</tr>
								<tr>
									<th><?=$lng['Bank code']?></th>
									<td><?=$data['bank_code']?></td>
								</tr>
								<tr>
									<th><?=$lng['Bank name']?></th>
									<td><? //if(!empty($data['bank_code'])){echo $banks[$data['bank_code']][$lang]?> - <b><? //=$lng['Branch']?></b> <? //=$data['bank_branch'];}?></td>
								</tr>
								<tr>
									<th><?=$lng['Bank account no.']?></th>
									<td><?=$data['bank_account']?></td>
								</tr>
								<tr>
									<th><?=$lng['Payment type']?></th>
									<td><? //=$pay_type[$data['pay_type']]?></td>
								</tr>
								<? if(!empty($data['pvf_nr'])){ ?>
								<tr>
									<th><?=$lng['Provident fund no.']?></th>
									<td><?=$data['pvf_nr']?></td>
								</tr>
								<tr>
									<th><?=$lng['PVF registration date']?></th>
									<td><?=$data['pvf_reg_date']?></td>
								</tr>
								<? } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#work">
						<?=$lng['Work data']?>
					</button>
				</div>
				<div id="work" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['Position']?></th>
									<td><?=$positions[$data['position']][$lang]?></td>
								</tr>
								<tr>
									<th><?=$lng['Employee type']?></th>
									<td><?=$emp_type[$data['emp_type']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Joining date']?></th>
									<td><?=$data['joining_date']?></td>
								</tr>
								<tr>
									<th><?=$lng['Probation due date']?></th>
									<td><?=$data['probation_date']?></td>
								</tr>
								<? if(!empty($data['resign_date'])){ ?>
								<tr>
									<th><?=$lng['Resign date']?></th>
									<td><?=$data['resign_date']?></td>
								</tr>
								<tr>
									<th><?=$lng['Resign reason']?></th>
									<td><?=$data['resign_reason']?></td>
								</tr>
								<? } ?>
								<tr>
									<th><?=$lng['Service years']?></th>
									<td><?=getAge($data['joining_date'])?></td>
								</tr>
								<tr>
									<th><?=$lng['Employee status']?></th>
									<td><?=$emp_status[$data['emp_status']]?></td>
								</tr>
								<tr>
									<th><?=$lng['Driving license No.']?></th>
									<td><?=$data['drvlicense_nr']?></td>
								</tr>
								<tr>
									<th><?=$lng['License expiry date']?></th>
									<td><?=$data['drvlicense_exp']?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#benefits">
						<?=$lng['Benefits data']?>
					</button>
				</div>
				<div id="benefits" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['Basic salary']?></th>
									<td><?=number_format($data['base_salary'],2)?></td>
								</tr>
								<? foreach($fix_allow as $k=>$v){ ?>
									<tr>
										<th><?=$v?> (<?=$lng['allowance']?>)</th>
										<td><?=number_format($data['fix_allow_'.$k],2)?></td>
									</tr>
								<? } ?>
								<tr>
									<th><?=$lng['Calculate SSO']?></th>
									<td><?=$noyes01[$data['calc_sso']]?></td>
								</tr>
								<? if($data['calc_pvf']){ ?>
								<? if($data['pvf_rate_emp']){ ?>
								<tr>
									<th><?=$lng['PVF rate employee']?></th>
									<td><?=$data['pvf_rate_emp']?> %</td>
								</tr>
								<? } if($data['pvf_rate_com']){ ?>
								<tr>
									<th><?=$lng['PVF rate employer']?></th>
									<td><?=$data['pvf_rate_com']?> %</td>
								</tr>
								<? } ?>
								
								<tr>
									<th><?=$lng['Calculate PVF']?></th>
									<td><?=$noyes01[$data['calc_pvf']]?></td>
								</tr>
								<? } ?>
								<tr>
									<th><?=$lng['Calculate Tax']?></th>
									<td><?=$noyes01[$data['calc_tax']]?></td>
								</tr>
								<tr>
									<th style="width:5%"><?=$lng['Modify Tax amount']?></th>
									<td><?=number_format($data['modify_tax'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Tax calculation method']?></th>
									<td style="white-space:normal"><? if($data['calc_method'] == 'def' || $data['calc_method'] == 'cam'){ echo $lng['Calculate in Advance Method'];}else{echo $lng['Accumulative Calculation Method'];}?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#tax">
						<?=$lng['Tax data']?>
					</button>
				</div>
				<div id="tax" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th><?=$lng['Standard deduction']?></th>
									<td><?=number_format($data['tax_standard_deduction'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Personal care']?></th>
									<td><?=number_format($data['tax_personal_allowance'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Spouse care']?></th>
									<td><?=number_format($data['tax_allow_spouse'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Parents care']?></th>
									<td><?=number_format($data['tax_allow_parents'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Parents in law care']?></th>
									<td><?=number_format($data['tax_allow_parents_inlaw'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Care disabled person']?></th>
									<td><?=number_format($data['tax_allow_disabled_person'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Child care - biological']?></th>
									<td><?=number_format($data['tax_allow_child_bio'],2)?></td>
								</tr>
								<tr>
									<th style="white-space:normal"><?=$lng['Child care - biological 2018/19/20']?></th>
									<td><?=number_format($data['tax_allow_child_bio_2018'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Child care - adopted']?></th>
									<td><?=number_format($data['tax_allow_child_adopted'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Child birth (Baby bonus)']?></th>
									<td><?=number_format($data['tax_allow_child_birth'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Own health insurance']?></th>
									<td><?=number_format($data['tax_allow_own_health'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Own life insurance']?></th>
									<td><?=number_format($data['tax_allow_own_life_insurance'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Health insurance parents']?></th>
									<td><?=number_format($data['tax_allow_health_parents'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Life insurance spouse']?></th>
									<td><?=number_format($data['tax_allow_life_insurance_spouse'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Pension fund']?></th>
									<td><?=number_format($data['tax_allow_pension_fund'],2)?></td>
								</tr>
								<? if($data['tax_allow_pvf'] != 0){ ?>
								<tr>
									<th><?=$lng['Provident fund']?></th>
									<td><?=number_format($data['tax_allow_pvf'],2)?></td>
								</tr>
								<? } ?>
								<tr>
									<th><?=$lng['NSF']?></th>
									<td><?=number_format($data['tax_allow_nsf'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['RMF']?></th>
									<td><?=number_format($data['tax_allow_rmf'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Social Security Fund']?></th>
									<td><?=number_format($data['tax_allow_sso'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['LTF']?></th>
									<td><?=number_format($data['tax_allow_ltf'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Home loan interest']?></th>
									<td><?=number_format($data['tax_allow_home_loan_interest'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Donation charity']?></th>
									<td><?=number_format($data['tax_allow_donation_charity'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Donation flooding']?></th>
									<td><?=number_format($data['tax_allow_donation_flood'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Donation education']?></th>
									<td><?=number_format($data['tax_allow_donation_education'],2)?></td>
								</tr>
								<tr>
									<th style="white-space:normal"><?=$lng['Exemption disabled person <65 yrs']?></th>
									<td><?=number_format($data['tax_allow_exemp_disabled_under'],2)?></td>
								</tr>
								<tr>
									<th style="white-space:normal"><?=$lng['Exemption tax payer => 65yrs']?></th>
									<td><?=number_format($data['tax_allow_exemp_payer_older'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['First home buyer']?></th>
									<td><?=number_format($data['tax_allow_first_home'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Year-end shopping']?></th>
									<td><?=number_format($data['tax_allow_year_end_shopping'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Domestic tour']?></th>
									<td><?=number_format($data['tax_allow_domestic_tour'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Other allowance']?></th>
									<td><?=number_format($data['tax_allow_other'],2)?></td>
								</tr>
								<tr>
									<th><?=$lng['Total deductions']?></th>
									<td style="font-weight:600"><?=number_format($emp_tax_deductions,2)?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
			<div class="item">
				<div class="accordion-header">
					<button class="btn collapsed" type="button" data-toggle="collapse" data-target="#documents">
						<?=$lng['Documents']?>
					</button>
				</div>
				<div id="documents" class="accordion-body collapse" data-parent="#accordionExample1">
					<div class="accordion-content" style="padding:0">
						<table class="accordion-table bordered">
							<tbody>
								<tr>
									<th style="width:10%"><?=$lng['ID card']?></th>
									<td><?=$data['att_idcard']?></td>
								</tr>
								<tr>
									<th><?=$lng['Housebook']?></th>
									<td><?=$data['att_housebook']?></td>
								</tr>
								<tr>
									<th><?=$lng['Bankbook']?></th>
									<td><?=$data['att_bankbook']?></td>
								</tr>
								<tr>
									<th><?=$lng['Contract']?></th>
									<td><?=$data['att_contract']?></td>
								</tr>
								<tr>
									<th><?=$lng['Additional file']?></th>
									<td><?=$data['attach1']?></td>
								</tr>
								<tr>
									<th><?=$lng['Additional file']?></th>
									<td><?=$data['attach2']?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		
		</div>
		
		<div style="height:55px"></div>
		
		
		
	</div>				
		
		
		
		












