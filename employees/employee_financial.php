<?php
	
	if(!$_SESSION['rego']['employee_finance']['view']){ 
		echo '<div class="msg_nopermit">You have no access to this page</div>'; exit;
	}
	$delDoc = 'delColor';
	if($_SESSION['rego']['employee_finance']['del']){$delDoc = 'delDoc';}

	if(isset($_SESSION['rego']['empID'])){ // EDIT EMPLOYEE ////////////////////////////////////////////////
		$empID = $_SESSION['rego']['empID'];
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$empID."'");
		$data = $res->fetch_assoc();
		if(empty($data['image'])){$data['image'] = 'images/profile_image.jpg';}
		$update = 1;

		if($data['calc_method'] ==''){
			$data['calc_method'] = $sys_settings['calc_method'];
		}if($data['calc_tax'] ==''){
			$data['calc_tax'] = $sys_settings['calc_tax'];
		}if($data['calc_sso'] ==''){
			$data['calc_sso'] = $sys_settings['calc_sso'];
		}if($data['ot_rate'] ==''){
			$data['ot_rate'] = $sys_settings['ot_rate'];
		}if($data['base_ot_rate'] ==''){
			$data['base_ot_rate'] = $sys_settings['base_ot_rate'];
		}if($data['calc_base'] ==''){
			$data['calc_base'] = $sys_settings['calc_base'];
		}if($data['contract_type'] ==''){
			$data['contract_type'] = $sys_settings['contract_type'];
		}if($data['pnd'] ==''){
			$data['pnd'] = $sys_settings['pnd'];
		}if($data['calc_psf'] ==''){
			$data['calc_psf'] = $sys_settings['calc_psf'];
		}if($data['psf_rate_emp'] ==''){
			$data['psf_rate_emp'] = $sys_settings['psf_rate_emp'];
		}if($data['psf_rate_com'] ==''){
			$data['psf_rate_com'] = $sys_settings['psf_rate_com'];
		}if($data['calc_pvf'] ==''){
			$data['calc_pvf'] = $sys_settings['calc_pvf'];
		}if($data['pvf_rate_emp'] ==''){
			$data['pvf_rate_emp'] = $sys_settings['pvf_rate_emp'];
		}if($data['pvf_rate_com'] ==''){
			$data['pvf_rate_com'] = $sys_settings['pvf_rate_com'];
		}
	}
	
	$entity_banks = getEntityBanks($data['entity']);
	//var_dump($entity_banks); exit;

	$bank_codes = unserialize($rego_settings['bank_codes']);
	$fix_allow = getFixAllowances($sys_settings);
	$fix_deductions = unserialize($sys_settings['fix_deduct']);
	$fix_deduct = getUsedFixDeduct($lang);
	$tax_settings = unserialize($rego_settings['tax_settings']);
	//var_dump($tax_settings); exit;
	$tax_info = unserialize($rego_settings['tax_info_'.$lang]);
	//var_dump($tax_info);
	$tax_err = unserialize($rego_settings['tax_err_'.$lang]);
	
	//var_dump($data); exit;
	
	if(empty($data['att_bankbook'])){$att_bankbook = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_bankbook = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_bankbook'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['att_contract'])){$att_contract = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$att_contract = '<a download href="'.ROOT.$cid.'/employees/'.$data['att_contract'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach5'])){$attach5 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach5 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach5'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach6'])){$attach6 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach6 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach6'].'"><i class="fa fa-download fa-lg"></i></a>';}
	if(empty($data['attach7'])){$attach7 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach7 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach7'].'"><i class="fa fa-download fa-lg"></i></a>';}
	// if(empty($data['attach8'])){$attach8 = '<i style="color:#ccc" class="fa fa-download fa-lg"></i></a>';}else{$attach8 = '<a download href="'.ROOT.$cid.'/employees/'.$data['attach8'].'"><i class="fa fa-download fa-lg"></i></a>';}

	
	// echo '<pre>';
	// print_r($fix_deductions);
	// echo '</pre>';
	// exit;

?>

	<h2 style="position:relative">
		<span><i class="fa fa-users fa-mr"></i> <?=$lng['Financial info']?>&nbsp; <i class="fa fa-arrow-circle-right"></i> </span>
		<span><?=$data['emp_id']?> : <?=$data[$lang.'_name']?></span>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<? include('employee_image_inc.php')?>
	
	<div class="pannel main_pannel employee-profile">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab_fin_financial" data-toggle="tab"><?=$lng['Financial']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_fin_benefits" data-toggle="tab"><?=$lng['Benefits']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_fin_tax" data-toggle="tab"><?=$lng['Tax']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_fin_documents" data-toggle="tab"><?=$lng['Documents']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_fin_contract" data-toggle="tab"><?=$lng['End contract']?></a></li>
		</ul>
		
		<form id="financialForm" style="height:100%">
		<fieldset style="height:100%" <? if(!$_SESSION['rego']['employee_finance']['edit']){echo 'disabled';} ?>>
		<div class="tab-content" style="height:calc(100% - 30px)">
			<button id="submitBtn" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			<input type="hidden" name="emp_id" value="<?=$data['emp_id']?>">
			
			<div class="tab-pane" id="tab_fin_financial">
				<div class="tab-content-left">
					<table class="basicTable editTable" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2"><?=$lng['FINANCIAL DATA']?></th>
							</tr>
						</thead>
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
								<td><input type="text" name="bank_branch" placeholder="..." value="<?=$data['bank_branch']?>"></td>
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
										<!--<option value=""><?=$lng['Select']?></option>-->
										<option value="cash"><?=$lng['Cash']?></option>
										<option value="cheque"><?=$lng['Cheque']?></option>
										<? if($entity_banks){foreach($entity_banks as $k=>$v){ ?>
											<option <? if($data['pay_type'] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$bank_codes[$v['code']][$lang]?></option>
										<? }} ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
					
					<table class="basicTable inputs">
						
						<thead>
							<tr style="line-height:100%">
								<th colspan="2" style="width:50%"><?=$lng['PENSION FUND PSF']?></th>
								<th colspan="2" style="width:50%"><?=$lng['PROVIDENT FUND PVF']?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="background:#ffe "><?=$lng['Calculate PSF']?></th>
								<td>
									<select name="calc_psf" style="width:100%;background:#ffe">
										<? foreach($noyes01 as $k=>$v){ ?>
											<option <? if($data['calc_psf'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
								<th style="background: #efe"><?=$lng['Calculate PVF']?></th>
								<td>
									<select name="calc_pvf" style="width:100%; background:#efe">
										<? foreach($noyes01 as $k=>$v){ ?>
											<option <? if($data['calc_pvf'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th style="background:#ffe"><?=$lng['Rate employee']?> %</th>
								<td style="background:#ffe"><input name="psf_rate_emp" class="sel float320" type="text" value="<?=$data['psf_rate_emp']?>"></td>
								<th style="background: #efe"><?=$lng['Rate employee']?> %</th>
								<td style="background: #efe"><input name="pvf_rate_emp" class="sel float320" type="text" value="<?=$data['pvf_rate_emp']?>"></td></td>
							</tr>
							
							<tr>
								<th style="background:#ffe"><?=$lng['Rate employer']?> %</th>
								<td style="background:#ffe"><input name="psf_rate_com" class="sel float320" type="text" value="<?=$data['psf_rate_com']?>"></td>
								<th style="background: #efe"><?=$lng['Rate employer']?> %</th>
								<td style="background: #efe"><input name="pvf_rate_com" class="sel float320" type="text" value="<?=$data['pvf_rate_com']?>"></td></td>
							</tr>
							<tr>
								<th style="background:#ffe"><?=$lng['Previous years employee']?> <?=$lng['THB']?></th>
								<td style="background:#ffe">
									<input name="psf_prev_years_emp" class="sel numeric" type="text" value="<?=$data['psf_prev_years_emp']?>">
								</td>
								<th style="background: #efe"><?=$lng['Previous years employee']?> <?=$lng['THB']?></th>
								<td style="background: #efe">
									<input name="pvf_prev_years_emp" class="sel numeric" type="text" value="<?=$data['pvf_prev_years_emp']?>">
								</td>
							</tr>
							<tr>
								<th style="background:#ffe"><?=$lng['Previous years employer']?> <?=$lng['THB']?></th>
								<td style="background:#ffe">
									<input name="psf_prev_years_emp" class="sel numeric" type="text" value="<?=$data['psf_prev_years_com']?>">
								</td>
								<th style="background: #efe"><?=$lng['Previous years employer']?> <?=$lng['THB']?></th>
								<td style="background: #efe">
									<input name="pvf_prev_years_com" class="sel numeric" type="text" value="<?=$data['pvf_prev_years_com']?>">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-content-right">
					<table class="basicTable editTable" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2"><?=$lng['REVENU DEPARTMENT']?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Tax calculation method']?></th>
								<td>
									<select name="calc_method">
										<option <? if($data['calc_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['Calculate in Advance Method']?> (CAM)</option>
										<option <? if($data['calc_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['Accumulative Calculation Method']?> (ACM)</option>
										<option <? if($data['calc_method'] == 'ytd'){echo "selected";} ?> value="ytd"><?=$lng['Year To Date']?> (YTD)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Calculate Tax']?></th>
								<td>
									<select name="calc_tax">
										<option <? if($data['calc_tax'] == '1'){echo 'selected';}?> value="1"><?=$lng['PND']?> 1 40(1)</option>
										<option <? if($data['calc_tax'] == '2'){echo 'selected';}?> value="2"><?=$lng['PND']?> 1 40(2)</option>
										<option <? if($data['calc_tax'] == '3'){echo 'selected';}?> value="3"><?=$lng['PND']?> 3</option>
										<option <? if($data['calc_tax'] == '0'){echo 'selected';}?> value="0"><?=$lng['no Tax']?></option>
									</select>
								</td>
							</tr>
							<!--<tr>
								<th>PND 1</th>
								<td style="padding:5px 0 0 10px"><input <? if($data['pnd'] == 1){echo "checked";} ?> type="radio" name="pnd" value="1"></td>
							</tr>
							<tr>
								<th>PND 3<? //=$lng['PND 1']?></th>
								<td style="padding:5px 0 0 10px"><input <? if($data['pnd'] == 3){echo "checked";} ?> type="radio" name="pnd" value="1"></td>
							</tr>-->
							<tr>
								<th style="width:5%"><?=$lng['Modify Tax amount']?></th>
								<td><input class="sel neg_numeric" type="text" name="modify_tax" placeholder="..." value="<?=$data['modify_tax']?>"></td>
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
									<select name="sso_by" style="xwidth:auto">
										<option <? if($data['sso_by'] == '0'){echo 'selected';}?> value="0"><?=$lng['Employee']?></option>
										<option <? if($data['sso_by'] == '1'){echo 'selected';}?> value="1"><?=$lng['Company']?></option>
									</select>
									<!--<b style="color:#c00">Only if Calculation base = Net amount</b>-->
								</td>
							</tr>
							<!--<tr>
								<th>Hospital chosen<? //=$lng['Annual leave (days)']?></th>
								<td><input type="text" name="sso_hospital" placeholder="__" value="<?=$data['sso_hospital']?>"></td>
							</tr>-->
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="tab-pane" id="tab_fin_benefits">
				<div class="tab-content-left">
					<table class="basicTable editTable" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2"><?=$lng['BASIC SALARY']?></th>
							</tr>
						</thead>
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
								<td><input type="text" id="base_salary" name="base_salary" value="<?=$data['base_salary']?>"></td>
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
										<option <? if($data['base_ot_rate'] == 'cal'){echo 'selected';}?> value="cal"><?=$lng['Calculated']?></option>
										<option <? if($data['base_ot_rate'] == 'fix'){echo 'selected';}?> value="fix"><?=$lng['Fixed']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['OT rate']?></th>
								<td><input class="sel float2" type="text" name="ot_rate" value="<?=$data['ot_rate']?>"></td>
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
				<div class="tab-content-right">
					<table class="basicTable editTable" border="0">
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
									<input style="width:70px" class="numeric8 sel notnull calcRate fixAllow" type="text" name="fix_allow_<?=$k?>" placeholder="..." value="<?=$data['fix_allow_'.$k]?>">
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
				</div>
			</div>
			
			<div class="tab-pane" id="tab_fin_tax">
				<? include('employee_tax_data.inc.php')?>
			</div>
			
			<div class="tab-pane" id="tab_fin_documents">
				<div style="width:0; height:0; overflow:hidden" >
				<input name="att_bankbook" id="att_bankbook" type="file" />
				<input name="att_contract" id="att_contract" type="file" />
				<input name="attach5" id="attach5" type="file" />
				<input name="attach6" id="attach6" type="file" />
				<input name="attach7" id="attach7" type="file" />
				<!-- <input name="attach8" id="attach8" type="file" /> -->
				</div>
				
				<table class="basicTable" border="0">
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['DOCUMENTS']?></th>
							<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Upload']?>"><i class="fa fa-upload fa-lg"></i></th>
							<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Download']?>"><i class="fa fa-download fa-lg"></i></th>
							<th style="width:1%" data-toggle="tooltip" title="<?=$lng['Delete']?>"><i class="fa fa-trash fa-lg"></i></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Bankbook']?></th>
							<td id="bankbook_name" style="width:95%; color:#999; font-style:italic"><?=$data['att_bankbook']?></td>
							<td><a href="#" onClick="$('#att_bankbook').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_bankbook?></td>
							<td><a href="#" data-id="att_bankbook" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Contract']?></th>
							<td id="contract_name" style="color:#999; font-style:italic"><?=$data['att_contract']?></td>
							<td><a href="#" onClick="$('#att_contract').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$att_contract?></td>
							<td><a href="#" data-id="att_contract" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach5_name" style="color:#999; font-style:italic"><?=$data['attach5']?></td>
							<td><a href="#" onClick="$('#attach5').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach5?></td>
							<td><a href="#" data-id="attach5" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach6_name" style="color:#999; font-style:italic"><?=$data['attach6']?></td>
							<td><a href="#" onClick="$('#attach6').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach6?></td>
							<td><a href="#" data-id="attach6" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach7_name" style="color:#999; font-style:italic"><?=$data['attach7']?></td>
							<td><a href="#" onClick="$('#attach7').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach7?></td>
							<td><a href="#" data-id="attach7" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	
<!-- 						<tr>
							<th><?=$lng['Additional file']?></th>
							<td id="attach8_name" style="color:#999; font-style:italic"><?=$data['attach8']?></td>
							<td><a href="#" onClick="$('#attach8').click();"><i class="fa fa-upload fa-lg"></i></a></td>
							<td class="tac"><?=$attach8?></td>
							<td><a href="#" data-id="attach8" class="<?=$delDoc?>"><i class="fa fa-trash fa-lg"></i></a></td>
						</tr>	 -->
					</tbody>
				</table>
			</div>
			
			<div class="tab-pane" id="tab_fin_contract">
				<table class="basicTable editTable" border="0">
					<thead>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Joining date']?></th>
							<td><input id="startdat" readonly type="text" value="<? if(!empty($data['joining_date'])){echo date('d-m-Y', strtotime($data['joining_date']));}?>"></td>
						</tr>
						<tr>
							<th><?=$lng['Notice date']?></th>
							<td><input type="text" readonly style="cursor:pointer" class="datepick" name="notice_date" placeholder="..." value="<?=$data['notice_date']?>"></td>
						</tr>
						<tr>
							<th><?=$lng['End date']?></th>
							<td><input type="text" style="cursor:pointer; width:109px" class="datepick" name="resign_date" placeholder="..." value="<? if(!empty($data['resign_date'])){echo date('d-m-Y', strtotime($data['resign_date']));}?>"><b style="color:#b00"><?=$lng['Last working day']?></b></td>
						</tr>
						<tr>
							<th><?=$lng['End reason']?></th>
							<td><input type="text" name="resign_reason" placeholder="..." value="<?=$data['resign_reason']?>"></td>
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
					</tbody>
					<thead>
						<tr style="line-height:100%">
							<th colspan="2"><?=$lng['ADDITIONAL COMPENSATIONS AT END OF EMPLOYEMENT']?></th>
						</tr>
					</thead>
					<tbody>
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
			</div>
			
		</div>
		</fieldset
		></form>
		
	</div>
	
	<? include('employee_new_edit_script.php')?>

	<script>
		
	$(document).ready(function() {
		
		var update = <?=json_encode($update)?>;
		var emp_id = <?=json_encode($_SESSION['rego']['empID'])?>;
		var fix_allow = <?=json_encode($fix_allow)?>;

		$("#financialForm").on('submit', function(e){ // SUBMIT EMPLOYEE FORM ///////////////////////////////////
			e.preventDefault();
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

		var dayhours = 8;
		var workdays = 30;
		$(".calcRate, #base_salary").on('change', function(){
			var wage = parseFloat($('#base_salary').val());
			$.each(fix_allow, function(i, v){
				if(v.rate == 'Y'){
					wage += parseFloat($('input[name="fix_allow_'+i+'"]').val());
				}
			})
			if($('#contract_type').val() == 'day'){
				var day_rate = parseInt(wage);
				var hour_rate = (parseInt(wage) / parseInt(dayhours));
			}else{
				var day_rate = (parseInt(wage) / parseInt(workdays));
				var hour_rate = (parseInt(wage) / parseInt(workdays) / parseInt(dayhours));
			}
			$('input[name="day_rate"]').val(day_rate)
			$('input[name="hour_rate"]').val(hour_rate)
			$('#day_rate').val(parseFloat(day_rate).format(2))
			$('#hour_rate').val(parseFloat(hour_rate).format(2))
		})
		if($('input[name="day_rate"]').val() == 0){
			$(".calcRate").trigger('change');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		}
	
	// DOCUMENTS ///////////////////////////////////////////////////////////////////////////////
		$("#att_bankbook").change(function(){
			readAttURL(this,'#bankbook_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#att_contract").change(function(){
			readAttURL(this,'#contract_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach5").change(function(){
			readAttURL(this,'#attach5_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach6").change(function(){
			readAttURL(this,'#attach6_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		$("#attach7").change(function(){
			readAttURL(this,'#attach7_name');
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		});
		// $("#attach8").change(function(){
		// 	readAttURL(this,'#attach8_name');
		// 	$('#sAlert').fadeIn(200);
		// 	$("#submitBtn").addClass('flash');
		// });
		
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
		
		
		
		
		
		$('input, textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})
		$('input, select').on('change', function(e){
			$('#sAlert').fadeIn(200);
			$("#submitBtn").addClass('flash');
		})




		
		
		var activeTabFin = localStorage.getItem('activeTabFin');
		if(activeTabFin){
			$('.nav-link[href="' + activeTabFin + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_fin_financial"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabFin', $(e.target).attr('href'));
		});

	})
		
	</script>

















