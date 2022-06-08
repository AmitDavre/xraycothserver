<?
	$disabled = '';
	$readonly = 'readonly';
	$nofocus = 'nofocus';
	//if($_SESSION['RGadmin']['access']['settings']['payroll'] == 0){$disabled = 'disabled';}
	//var_dump(unserialize('a:2:{s:2:"en";a:3:{s:2:"NP";s:11:"no Position";s:3:"DES";s:8:"Designer";s:3:"DEV";s:10:"Develloper";}s:2:"th";a:3:{s:2:"NP";s:36:"ไม่มีตำแหน่ง";s:3:"DES";s:9:"-Designer";s:3:"DEV";s:11:"-Develloper";}}'));


	$settings = array();
	if($res = $dba->query("SELECT * FROM rego_default_settings")){
		$settings = $res->fetch_assoc();
		$settings['bank_codes'] = unserialize($settings['bank_codes']);
	}
	//var_dump($settings['bank_codes']);
	
	$fix_allow = unserialize($settings['fix_allow']);
	$var_allow = unserialize($settings['var_allow']);
	if(empty($fix_allow)){$fix_allow = array();}
	if(empty($var_allow)){$var_allow = array();}
	//var_dump($fix_allow);
	//var_dump($var_allow);
	
	$taxrules = unserialize($settings['taxrules']);
	//var_dump($taxrules);
	$tax_settings = unserialize($settings['tax_settings']);
	//var_dump($tax_settings);
	$tax_info_th = unserialize($settings['tax_info_th']);
	//var_dump($tax_info_th);
	$tax_info_en = unserialize($settings['tax_info_en']);
	//var_dump($tax_info_en);
	$tax_err_th = unserialize($settings['tax_err_th']);
	//var_dump($tax_err_th);
	$tax_err_en = unserialize($settings['tax_err_en']);
	//var_dump($tax_err_en);
	$payslip = unserialize($settings['payslip_field']);
	//var_dump($payslip);
	
	$positions = unserialize($settings['positions']);
	if($positions){
		$pos_count = count($positions['th']);
	}else{
		$pos_count = 0;
	}
	//var_dump($positions);
	
	$acc_count = 0;
	$account_codes = unserialize($settings['account_codes']);
	if($account_codes){
		$acc_count = count($account_codes);
	}
	//var_dump($account_codes);
	
	$allocations = unserialize($settings['allocations']);
	//var_dump($allocations);

	$debet = array();
	
	$debet['salary'] = $lng['Basic salary'];
	$debet['total_otb'] = $lng['Overtime'];
	$debet['fix_allow'] = 'Fixed allowances';//$lng['Overtime'];
	$debet['var_allow'] = 'Variable allowances';//$lng['Overtime'];
/*	foreach($fix_allow as $k=>$v){
		if($v['apply'] == 1){$debet['fix_allow_'.$k] = 'Fix. allow '.$v[$lang];}
	}
	foreach($var_allow as $k=>$v){
		if($v['apply'] == 1){$debet['var_allow_'.$k] = 'Var. allow '.$v[$lang];}
	}
*/	$debet['bonus'] = $lng['Bonus'];
	$debet['other_income'] = $lng['Other income'];
	$debet['severance'] = $lng['Severance'];
	$debet['absence_b'] = $lng['Absence'];
	$debet['late_early_b'] = $lng['Late Early'];
	$debet['leave_wop_b'] = $lng['Leave WOP'];
	$debet['uniform'] = $lng['Uniform'];
	$debet['deduct_3'] = $lng['Other deduct'];
	$debet['pvf_employer'] = $lng['Provident fund'];
	$debet['social'] = $lng['SSO Employer'];
	
	$credit['net_income'] = $lng['Net salary cost'];
	$credit['tax'] = $lng['PND1'];
	$credit['social'] = $lng['SSO Employee'];
	$credit['comment'] = $lng['SSO Employer'];
	$credit['pvf_employee'] = $lng['PVF Employee'];
	$credit['pvf_employer'] = $lng['PVF Employer'];
	
	//var_dump($debet);
	
?>
 
<style>
	*:disabled {
		cursor:default !important;
	}
	table.basicTable td.info {
		color: #006699;
		font-style:italic;
	}
</style>
	
	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;Default settings <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<form id="payrollForm" style="height:100%">
	
			<ul class="nav nav-tabs" id="myTab">
				<li><a data-target="#tab_settings" data-toggle="tab"><?=$lng['Settings']?></a></li>
				<li><a data-target="#tab_positions" data-toggle="tab"><?=$lng['Positions']?></a></li>
				<li><a data-target="#tab_defaults" data-toggle="tab"><?=$lng['Employee defaults']?></a></li>
				<li><a data-target="#tab_allowances" data-toggle="tab"><?=$lng['Allowances']?></a></li>
				<li><a data-target="#tab_taxsettings" data-toggle="tab"><?=$lng['Tax Settings']?></a></li>
				<li><a data-target="#tab_taxrules" data-toggle="tab"><?=$lng['Tax rules']?></a></li>
				<li><a data-target="#tab_bank" data-toggle="tab"><?=$lng['Bank codes']?></a></li>
				<li><a data-target="#tab_accounting" data-toggle="tab"><?=$lng['Accounting Codes']?></a></li>
				<li><a data-target="#tab_allocations" data-toggle="tab"><?=$lng['Accounting Allocations']?></a></li>
			</ul>
		
			<table border="0" style="position:absolute; top:15px; right:15px;">
				<tr>
					<td>
						<div style="display:none;" id="message"></div>
					</td>
					<td style="padding-left:10px; width:1px">
						<button class="btn btn-primary btn-sm" style="margin:0;" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
					</td>
					<? //} ?>
				</tr>
			</table>
	
			<div class="tab-content" style="padding:15px; height:calc(100% - 40px); border:1px #ccc solid; border-top:0">
				
				<div class="tab-pane" id="tab_settings">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th><i class="man"></i> <?=$lng['Working days a month']?></th>
								<td><b><input name="days_month" id="days_month" type="text" value="<?=$settings['days_month']?>" /></b></td>
							</tr>
							<tr>
								<th><i class="man"></i> <?=$lng['Working hours a day']?></th>
								<td><b><input name="hours_day" id="hours_day" type="text" value="<?=$settings['hours_day']?>" /></b></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['SSO rate']?> (%)</th>
								<td><input class="sel numeric" name="sso_rate" type="text" value="<?=$settings['sso_rate']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Minimum SSO amount']?> (<?=$lng['Baht']?>)</th>
								<td><input class="sel numeric" name="sso_min" type="text" value="<?=$settings['sso_min']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Maximum SSO amount']?> (<?=$lng['Baht']?>)</th>
								<td><input style="width:60px" class="sel numeric" name="sso_max" type="text" value="<?=$settings['sso_max']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Minimum wages']?> (<?=$lng['Baht']?>)</th>
								<td><input class="sel numeric" name="sso_min_wage" type="text" value="<?=$settings['sso_min_wage']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Maximum wages']?> (<?=$lng['Baht']?>)</th>
								<td><input class="sel numeric" name="sso_max_wage" type="text" value="<?=$settings['sso_max_wage']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['PVF applied']?></th>
								<td>
								<select name="pvf_applied" style="width:100%">
									<? foreach($noyes as $k=>$v){
									echo '<option ';
									if(strtoupper($settings['pvf_applied'])==$k){echo 'selected';}
									echo ' value="'.$k.'">'.$v.'</option>';
									} ?>
								</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['PVF rate employee']?></th>
								<td>
								<select name="pvf_rate_employee" style="width:100%">
									<? for($i=0;$i<=15;$i++){
									echo '<option ';
									if($settings['pvf_rate_employee'] == $i){echo 'selected';}
									echo ' value="'.$i.'">'.$i.' %</option>';
									} ?>
								</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['PVF rate employer']?></th>
								<td>
								<select name="pvf_rate_employer" style="width:100%">
									<? for($i=0;$i<=15;$i++){
									echo '<option ';
									if($settings['pvf_rate_employer'] == $i){echo 'selected';}
									echo ' value="'.$i.'">'.$i.' %</option>';
									} ?>
								</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i> <?=$lng['Default Tax calculation method']?></th>
								<td>
								<select name="tax_calc_method" style="width:100%">
									<option <? if($settings['tax_calc_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['CAM Tax calculation']?></option>
									<option <? if($settings['tax_calc_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['ACM Tax calculation']?></option>
								</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Payslip template']?></th>
								<td style="padding-top:2px;padding-right:10px">
									<select name="payslip_template" id="pslip_template" style="width:100%">
										<!--<option disabled selected value=""><?=$lng['Select']?></option>-->
										<option <? if($settings['payslip_template'] == 'la4'){echo "selected ";} ?>value="la4"><?=$lng['Laser A4 template']?></option>
										<option <? if($settings['payslip_template'] == 'tme'){echo "selected ";} ?>value="tme"><?=$lng['Thai matrix template (A5 Empty)']?></option>
										<option <? if($settings['payslip_template'] == 'tmp'){echo "selected ";} ?>value="tmp"><?=$lng['Thai matrix template (A5 Preprinted)']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th style="width:5px"><?=$lng['Payslip fields']?> :</th>
								<td class="pad410">
									<label><input <? if(isset($payslip['ytd1'])){echo 'checked';} ?> name="payslip_field[ytd1]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Income']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd2'])){echo 'checked';} ?> name="payslip_field[ytd2]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Tax']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd3'])){echo 'checked';} ?> name="payslip_field[ytd3]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Prov. Fund']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd4'])){echo 'checked';} ?> name="payslip_field[ytd4]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Social SF']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd5'])){echo 'checked';} ?> name="payslip_field[ytd5]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Other allowance']?></span></label>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Payslip rate']?> : </th>
								<td style="padding-top:2px;padding-right:10px;">
									<select name="payslip_rate" id="pslip_rate" style="width:100%">
										<!--<option disabled selected value=""><?=$lng['Select']?></option>-->
										<option <? if($settings['payslip_rate'] == 'em'){echo "selected ";} ?>value="em"><?=$lng['Empty']?></option>
										<option <? if($settings['payslip_rate'] == 'dr'){echo "selected ";} ?>value="dr"><?=$lng['Day rate']?></option>
										<option <? if($settings['payslip_rate'] == 'hr'){echo "selected ";} ?>value="hr"><?=$lng['Hour rate']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Support email']?></th>
								<td>
									<input placeholder="__" type="text" name="support_email" id="support_email" value="<?=$settings['support_email']?>" />
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			
				<div class="tab-pane" id="tab_positions" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table class="basicTable inputs" id="position_table" border="0">
						<thead>
							<tr>
								<th style="width:1px">#</th>
								<th style="width:25%"><?=$lng['Thai description']?></th>
								<th style="width:25%"><?=$lng['English description']?></th>
								<th style="width:50%"></th>
							</tr>
						</thead>
						<tbody>
						<? if($positions){ foreach($positions['th'] as $k=>$v){ ?>
							<tr>
								<td><b><input readonly name="positions[<?=$k?>][code]" type="text" value="<?=$k?>" /></b></td>
								<td><input name="positions[<?=$k?>][th]" type="text" value="<?=$v?>" /></td>
								<td><input name="positions[<?=$k?>][en]" type="text" value="<?=$positions['en'][$k]?>" /></td>
								<td></td>
							</tr>
						<? } } ?>
						</tbody>
					</table>
					<div style="height:10px"></div>
					<button class="btn btn-primary btn-xs" type="button" id="addposition"><?=$lng['Add row']?></button>
					</div>
				</div>
		
				<div class="tab-pane" id="tab_defaults">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th><?=$lng['Employee type']?></th>
								<td>
									<select name="emp_type" style="width:100%">
										<!--<option value="0" selected disabled><?//=$lng['Select']?></option>-->
										<? foreach($emp_type as $k=>$v){ ?>
											<option <? if($settings['emp_type'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Employee status']?></th><td>
									<select name="emp_status" style="width:100%">
										<!--<option value="x" selected disabled><?//=$lng['Select']?></option>-->
										<? foreach($emp_status as $k=>$v){
											echo '<option ';
											if($settings['emp_status']==$k){echo 'selected';}
											echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Allow login']?></th>
								<td>
									<select name="allow_login" style="width:100%">
										<? foreach($yesno as $k=>$v){
												echo '<option ';
												if(strtoupper($settings['allow_login'])==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Calculate SSF']?></th>
								<td>
									<select name="calc_sso" style="width:100%">
										<? foreach($noyes as $k=>$v){
												echo '<option ';
												if(strtoupper($settings['calc_sso'])==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Calculate PVF']?></label></th>
								<td>
									<select name="calc_pvf" style="width:100%">
										<? foreach($noyes as $k=>$v){
												echo '<option ';
												if(strtoupper($settings['calc_pvf'])==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Calculate Tax']?></th>
								<td>
									<select name="calc_tax" style="width:100%">
										<? foreach($noyes as $k=>$v){
												echo '<option ';
												if(strtoupper($settings['calc_tax'])==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>Print payslip<? //=$lng['Print payslip']?></th>
								<td>
									<select name="print_payslip" style="width:100%">
										<? foreach($noyes as $k=>$v){
												echo '<option ';
												if(strtoupper($settings['print_payslip'])==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Bonus paid in month']?></th>
								<td>
									<select name="bonus_payinmonth" id="bonus_payinmonth" style="width:100%">
									<? for($i=12;$i>=1;$i--){ ?>
										<option <? if($settings['bonus_payinmonth'] == $i){echo 'selected';}?> value="<?=$i?>"><?=$months[$i]?></option>
									<? } ?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane" id="tab_allowances">
					<table border="0" style="width:100%; table-layout:fixed">
						<tr>
							<td style="padding-right:15px; vertical-align:top">
							
								<table class="basicTable inputs" border="0" id="fixTable">
									<thead>
										<tr style="border-bottom:1px solid #fff">
											<th colspan="6" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Fixed allowances']?></th>
										</tr>
										<tr>
											<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
											<th><?=$lng['Apply']?></th>
											<th style="width:50%;"><?=$lng['Allowance Thai']?></th>
											<th style="width:50%; text-align:left"><?=$lng['Allowance English']?></th>
											<th><?=$lng['Taxable']?></th>
											<th>Include<? //=$lng['Include']?></th>
										</tr>
									</thead>
									<tbody>
									<?	if($fix_allow){ foreach($fix_allow as $k=>$v){ ?>
										<tr>
											<td class="tac" style="border-right:1px #ddd solid"><b><?=$k?></b></td>
											<td class="tac" style="vertical-align:middle">
												<input name="fix_allow[<?=$k?>][apply]" type="hidden" value="0" />
												<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="fix_allow[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
											</td>
											<td style="border-right:1px #ddd solid"><input placeholder="__" name="fix_allow[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
											<td style="border-right:1px #ddd solid"><input placeholder="__" name="fix_allow[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
											<td>
												<select name="fix_allow[<?=$k?>][tax]" style="width:100%">
													<option <? if($v['tax'] == 'Y'){echo "selected ";} ?>value="Y"><?=$lng['Yes']?></option>
													<option <? if($v['tax'] == 'N'){echo "selected ";} ?>value="N"><?=$lng['No']?></option>
												</select>
											</td>
											<td>
												<select name="fix_allow[<?=$k?>][rate]" style="width:100%">
													<option <? if($v['rate'] == 'N'){echo "selected ";} ?>value="N"><?=$lng['No']?></option>
													<option <? if($v['rate'] == 'Y'){echo "selected ";} ?>value="Y"><?=$lng['Yes']?></option>
												</select>
											</td>
										</tr>
									<? }} ?>
									</tbody>
								</table>
								<!--<? if($_SESSION['RGadmin']['access']['def_settings']['add'] == 1 && count($fix_allow) < 15){ ?>
								<div style="height:10px"></div>
								<button class="btn btn-primary btn-xs" type="button" id="addfix"><?=$lng['Add row']?></button>
								<? } ?>					
								<div style="height:20px"></div>-->
							
							</td>
							<td style="padding-left:15px; vertical-align:top">
								
								<table class="basicTable inputs" border="0" id="varTable">
									<thead>
										<tr style="border-bottom:1px solid #fff">
											<th colspan="6" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Variable allowances']?></th>
										</tr>
										<tr>
											<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
											<th><?=$lng['Apply']?></th>
											<th style="width:50%;"><?=$lng['Allowance Thai']?></th>
											<th style="width:50%; text-align:left"><?=$lng['Allowance English']?></th>
											<th><?=$lng['Taxable']?></th>
										</tr>
									</thead>
									<tbody>
									<?	if($var_allow){ foreach($var_allow as $k=>$v){ ?>
										<tr>
											<td class="tac" style="border-right:1px #ddd solid"><b><?=$k?></b></td>
											<td class="tac" style="vertical-align:middle">
												<input name="var_allow[<?=$k?>][apply]" type="hidden" value="0" />
												<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="var_allow[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
											</td>
											<td style="border-right:1px #ddd solid"><input placeholder="__" name="var_allow[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
											<td style="border-right:1px #ddd solid"><input placeholder="__" name="var_allow[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
											<td>
												<select name="var_allow[<?=$k?>][tax]" style="width:100%">
													<option <? if($v['tax'] == 'Y'){echo "selected ";} ?>value="Y"><?=$lng['Yes']?></option>
													<option <? if($v['tax'] == 'N'){echo "selected ";} ?>value="N"><?=$lng['No']?></option>
												</select>
											</td>
										</tr>
									<? }} ?>
									</tbody>
								</table>
								<!--<? if($_SESSION['RGadmin']['access']['def_settings']['add'] == 1 && count($var_allow) < 25){ ?>
								<div style="height:10px"></div>
								<button class="btn btn-primary btn-xs" type="button" id="addvar"><?=$lng['Add row']?></button>
								<? } ?>					
								<div style="height:20px"></div>-->
							
							</td>
						</tr>
					</table>
				</div>
	
				<div class="tab-pane" id="tab_taxsettings" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table id="deductionTable" class="basicTable inputs taxset vat" border="0">
					  <thead>
						 <tr style="line-height:100%">
							<th><?=$lng['Description']?></th>
							<th class="tac" style="min-width:60px"><?=$lng['Max']?><br /><?=strtolower($lng['Input'])?></th>
							<th class="tac" style="min-width:80px"><?=$lng['Max']?><br /><?=strtolower($lng['Amount'])?></th>
							<th style="width:90%"><?=$lng['Information condition error messages']?></th>
						 </tr>
					  </thead>
					  <tbody>
					  <tr>
						 <th><?=$lng['Standard deduction']?> (%)</th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[stdeduct_per]" placeholder="__" value="<?=$tax_settings['stdeduct_per']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[stdeduct_per]" placeholder="Thai info" value="<?=$tax_info_th['stdeduct_per']?>">
							<input class="in_info" type="text" name="tax_info_en[stdeduct_per]" placeholder="English info" value="<?=$tax_info_en['stdeduct_per']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Standard deduction']?><br><?=$lng['Max. amount']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[standard_deduction]" placeholder="__" value="<?=$tax_settings['standard_deduction']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[standard_deduction]" placeholder="Thai info" value="<?=$tax_info_th['standard_deduction']?>">
							<input class="in_info" type="text" name="tax_info_en[standard_deduction]" placeholder="English info" value="<?=$tax_info_en['standard_deduction']?>">
							<input class="in_err" type="text" name="tax_err_th[standard_deduction]" placeholder="Thai error message" value="<?=$tax_err_th['standard_deduction']?>">
							<input class="in_err" type="text" name="tax_err_en[standard_deduction]" placeholder="English error message" value="<?=$tax_err_en['standard_deduction']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Personal care']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[personal_allowance]" placeholder="__" value="<?=$tax_settings['personal_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[personal_allowance]" placeholder="Thai info" value="<?=$tax_info_th['personal_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[personal_allowance]" placeholder="English info" value="<?=$tax_info_en['personal_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Spouse care']?></th>
						 <td class="tac">Y/N</td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[spouse_allowance]" placeholder="__" value="<?=$tax_settings['spouse_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[spouse_allowance]" placeholder="Thai info" value="<?=$tax_info_th['spouse_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[spouse_allowance]" placeholder="English info" value="<?=$tax_info_en['spouse_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Parents care']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[parents_allow]" placeholder="__" value="<?=$tax_settings['parents_allow']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[parents_allowance]" placeholder="__" value="<?=$tax_settings['parents_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[parents_allow]" placeholder="Thai info" value="<?=$tax_info_th['parents_allow']?>">
							<input class="in_info" type="text" name="tax_info_en[parents_allow]" placeholder="English info" value="<?=$tax_info_en['parents_allow']?>">
							<input class="in_err" type="text" name="tax_err_th[parents_allow]" placeholder="Thai error message" value="<?=$tax_err_th['parents_allow']?>">
							<input class="in_err" type="text" name="tax_err_en[parents_allow]" placeholder="English error message" value="<?=$tax_err_en['parents_allow']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Parents in law care']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[parents_inlaw_allow]" placeholder="__" value="<?=$tax_settings['parents_inlaw_allow']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[parents_inlaw_allowance]" placeholder="__" value="<?=$tax_settings['parents_inlaw_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[parents_inlaw_allow]" placeholder="Thai info" value="<?=$tax_info_th['parents_inlaw_allow']?>">
							<input class="in_info" type="text" name="tax_info_en[parents_inlaw_allow]" placeholder="English info" value="<?=$tax_info_en['parents_inlaw_allow']?>">
							<input class="in_err" type="text" name="tax_err_th[parents_inlaw_allow]" placeholder="Thai error message" value="<?=$tax_err_th['parents_inlaw_allow']?>">
							<input class="in_err" type="text" name="tax_err_en[parents_inlaw_allow]" placeholder="English error message" value="<?=$tax_err_en['parents_inlaw_allow']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Care disabled person']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[disabled_allow]" placeholder="__" value="<?=$tax_settings['disabled_allow']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[disabled_allowance]" placeholder="__" value="<?=$tax_settings['disabled_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[disabled_allow]" placeholder="Thai info" value="<?=$tax_info_th['disabled_allow']?>">
							<input class="in_info" type="text" name="tax_info_en[disabled_allow]" placeholder="English info" value="<?=$tax_info_en['disabled_allow']?>">
							<input class="in_err" type="text" name="tax_err_th[disabled_allow]" placeholder="Thai error message" value="<?=$tax_err_th['disabled_allow']?>">
							<input class="in_err" type="text" name="tax_err_en[disabled_allow]" placeholder="English error message" value="<?=$tax_err_en['disabled_allow']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Child care - biological']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[child_allow]" placeholder="__" value="<?=$tax_settings['child_allow']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[child_allowance]" placeholder="__" value="<?=$tax_settings['child_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[child_allow]" placeholder="Thai info" value="<?=$tax_info_th['child_allow']?>">
							<input class="in_info" type="text" name="tax_info_en[child_allow]" placeholder="English info" value="<?=$tax_info_en['child_allow']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Child care - biological 2018/19/20']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[child_allow_2018]" placeholder="__" value="<?=$tax_settings['child_allow_2018']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[child_allowance_2018]" placeholder="__" value="<?=$tax_settings['child_allowance_2018']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[child_allow_2018]" placeholder="Thai info" value="<?=$tax_info_th['child_allow_2018']?>">
							<input class="in_info" type="text" name="tax_info_en[child_allow_2018]" placeholder="English info" value="<?=$tax_info_en['child_allow_2018']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Child care - adopted']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[child_adopt_allow]" placeholder="__" value="<?=$tax_settings['child_adopt_allow']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[child_adopt_allowance]" placeholder="__" value="<?=$tax_settings['child_adopt_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[child_adopt_allow]" placeholder="Thai info" value="<?=$tax_info_th['child_adopt_allow']?>">
							<input class="in_info" type="text" name="tax_info_en[child_adopt_allow]" placeholder="English info" value="<?=$tax_info_en['child_adopt_allow']?>">
							<input class="in_err" type="text" name="tax_err_th[child_adopt_allow]" placeholder="Thai error message" value="<?=$tax_err_th['child_adopt_allow']?>">
							<input class="in_err" type="text" name="tax_err_en[child_adopt_allow]" placeholder="English error message" value="<?=$tax_err_en['child_adopt_allow']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Child birth (Baby bonus)']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[child_birth_bonus]" placeholder="__" value="<?=$tax_settings['child_birth_bonus']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[child_birth_bonus]" placeholder="Thai info" value="<?=$tax_info_th['child_birth_bonus']?>">
							<input class="in_info" type="text" name="tax_info_en[child_birth_bonus]" placeholder="English info" value="<?=$tax_info_en['child_birth_bonus']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Own health insurance']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[own_health_insurance]" placeholder="__" value="<?=$tax_settings['own_health_insurance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[own_health_insurance]" placeholder="Thai info" value="<?=$tax_info_th['own_health_insurance']?>">
							<input class="in_info" type="text" name="tax_info_en[own_health_insurance]" placeholder="English info" value="<?=$tax_info_en['own_health_insurance']?>">
							<input class="in_err" type="text" name="tax_err_th[own_health_insurance]" placeholder="Thai error message" value="<?=$tax_err_th['own_health_insurance']?>">
							<input class="in_err" type="text" name="tax_err_en[own_health_insurance]" placeholder="English error message" value="<?=$tax_err_en['own_health_insurance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Own life insurance']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[own_life_insurance]" placeholder="__" value="<?=$tax_settings['own_life_insurance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[own_life_insurance]" placeholder="Thai info" value="<?=$tax_info_th['own_life_insurance']?>">
							<input class="in_info" type="text" name="tax_info_en[own_life_insurance]" placeholder="English info" value="<?=$tax_info_en['own_life_insurance']?>">
							<input class="in_err" type="text" name="tax_err_th[own_life_insurance]" placeholder="Thai error message" value="<?=$tax_err_th['own_life_insurance']?>">
							<input class="in_err" type="text" name="tax_err_en[own_life_insurance]" placeholder="English error message" value="<?=$tax_err_en['own_life_insurance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Max sum above']?><br />Own health + Own life above</th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[max_own_health_life]" placeholder="__" value="<?=$tax_settings['max_own_health_life']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[max_own_health_life]" placeholder="Thai info" value="<?=$tax_info_th['max_own_health_life']?>">
							<input class="in_info" type="text" name="tax_info_en[max_own_health_life]" placeholder="English info" value="<?=$tax_info_en['max_own_health_life']?>">
							<input class="in_err" type="text" name="tax_err_th[max_own_health_life]" placeholder="Thai error message" value="<?=$tax_err_th['max_own_health_life']?>">
							<input class="in_err" type="text" name="tax_err_en[max_own_health_life]" placeholder="English error message" value="<?=$tax_err_en['max_own_health_life']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Health insurance parents']?></th>
						 <td><input type="text" class="float21 tar sel" name="tax_settings[health_insurance_par]" placeholder="__" value="<?=$tax_settings['health_insurance_par']?>"></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[health_insurance_parent]" placeholder="__" value="<?=$tax_settings['health_insurance_parent']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[health_insurance_par]" placeholder="Thai info" value="<?=$tax_info_th['health_insurance_par']?>">
							<input class="in_info" type="text" name="tax_info_en[health_insurance_par]" placeholder="English info" value="<?=$tax_info_en['health_insurance_par']?>">
							<input class="in_err" type="text" name="tax_err_th[health_insurance_par]" placeholder="Thai error message" value="<?=$tax_err_th['health_insurance_par']?>">
							<input class="in_err" type="text" name="tax_err_en[health_insurance_par]" placeholder="English error message" value="<?=$tax_err_en['health_insurance_par']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Life insurance spouse']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[life_insurance_spouse]" placeholder="__" value="<?=$tax_settings['life_insurance_spouse']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[life_insurance_spouse]" placeholder="Thai info" value="<?=$tax_info_th['life_insurance_spouse']?>">
							<input class="in_info" type="text" name="tax_info_en[life_insurance_spouse]" placeholder="English info" value="<?=$tax_info_en['life_insurance_spouse']?>">
							<input class="in_err" type="text" name="tax_err_th[life_insurance_spouse]" placeholder="Thai error message" value="<?=$tax_err_th['life_insurance_spouse']?>">
							<input class="in_err" type="text" name="tax_err_en[life_insurance_spouse]" placeholder="English error message" value="<?=$tax_err_en['life_insurance_spouse']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Pension fund']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[pension_fund_allowance]" placeholder="__" value="<?=$tax_settings['pension_fund_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[pension_fund_allowance]" placeholder="Thai info" value="<?=$tax_info_th['pension_fund_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[pension_fund_allowance]" placeholder="English info" value="<?=$tax_info_en['pension_fund_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[pension_fund_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['pension_fund_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[pension_fund_allowance]" placeholder="English error message" value="<?=$tax_err_en['pension_fund_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Provident fund']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[provident_fund_allowance]" placeholder="__" value="<?=$tax_settings['provident_fund_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[provident_fund_allowance]" placeholder="Thai info" value="<?=$tax_info_th['provident_fund_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[provident_fund_allowance]" placeholder="English info" value="<?=$tax_info_en['provident_fund_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[provident_fund_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['provident_fund_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[provident_fund_allowance]" placeholder="English error message" value="<?=$tax_err_en['provident_fund_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['NSF']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[nsf_allowance]" placeholder="__" value="<?=$tax_settings['nsf_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[nsf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['nsf_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[nsf_allowance]" placeholder="English info" value="<?=$tax_info_en['nsf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[nsf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['nsf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[nsf_allowance]" placeholder="English error message" value="<?=$tax_err_en['nsf_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['RMF']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[rmf_allowance]" placeholder="__" value="<?=$tax_settings['rmf_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[rmf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['rmf_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[rmf_allowance]" placeholder="English info" value="<?=$tax_info_en['rmf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[rmf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['rmf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[rmf_allowance]" placeholder="English error message" value="<?=$tax_err_en['rmf_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Max sum above']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[max_pension_provident_nsf_rmf]" placeholder="__" value="<?=$tax_settings['max_pension_provident_nsf_rmf']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[max_pension_provident_nsf_rmf]" placeholder="Thai info" value="<?=$tax_info_th['max_pension_provident_nsf_rmf']?>">
							<input class="in_info" type="text" name="tax_info_en[max_pension_provident_nsf_rmf]" placeholder="English info" value="<?=$tax_info_en['max_pension_provident_nsf_rmf']?>">
							<input class="in_err" type="text" name="tax_err_th[max_pension_provident_nsf_rmf]" placeholder="Thai error message" value="<?=$tax_err_th['max_pension_provident_nsf_rmf']?>">
							<input class="in_err" type="text" name="tax_err_en[max_pension_provident_nsf_rmf]" placeholder="English error message" value="<?=$tax_err_en['max_pension_provident_nsf_rmf']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['LTF']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[ltf_allowance]" placeholder="__" value="<?=$tax_settings['ltf_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[ltf_allowance]" placeholder="Thai info" value="<?=$tax_info_th['ltf_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[ltf_allowance]" placeholder="English info" value="<?=$tax_info_en['ltf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[ltf_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['ltf_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[ltf_allowance]" placeholder="English error message" value="<?=$tax_err_en['ltf_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Home loan interest']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[home_loan_interest]" placeholder="__" value="<?=$tax_settings['home_loan_interest']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[home_loan_interest]" placeholder="Thai info" value="<?=$tax_info_th['home_loan_interest']?>">
							<input class="in_info" type="text" name="tax_info_en[home_loan_interest]" placeholder="English info" value="<?=$tax_info_en['home_loan_interest']?>">
							<input class="in_err" type="text" name="tax_err_th[home_loan_interest]" placeholder="Thai error message" value="<?=$tax_err_th['home_loan_interest']?>">
							<input class="in_err" type="text" name="tax_err_en[home_loan_interest]" placeholder="English error message" value="<?=$tax_err_en['home_loan_interest']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Social Security Fund']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[social_security_fund]" placeholder="__" value="<?=$tax_settings['social_security_fund']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[social_security_fund]" placeholder="Thai info" value="<?=$tax_info_th['social_security_fund']?>">
							<input class="in_info" type="text" name="tax_info_en[social_security_fund]" placeholder="English info" value="<?=$tax_info_en['social_security_fund']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Donation charity']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[donation_charity]" placeholder="__" value="<?=$tax_settings['donation_charity']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[donation_charity]" placeholder="Thai info" value="<?=$tax_info_th['donation_charity']?>">
							<input class="in_info" type="text" name="tax_info_en[donation_charity]" placeholder="English info" value="<?=$tax_info_en['donation_charity']?>">
							<input class="in_err" type="text" name="tax_err_th[donation_charity]" placeholder="Thai error message" value="<?=$tax_err_th['donation_charity']?>">
							<input class="in_err" type="text" name="tax_err_en[donation_charity]" placeholder="English error message" value="<?=$tax_err_en['donation_charity']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Donation flooding']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[donation_flood]" placeholder="__" value="<?=$tax_settings['donation_flood']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[donation_flood]" placeholder="Thai info" value="<?=$tax_info_th['donation_flood']?>">
							<input class="in_info" type="text" name="tax_info_en[donation_flood]" placeholder="English info" value="<?=$tax_info_en['donation_flood']?>">
							<input class="in_err" type="text" name="tax_err_th[donation_flood]" placeholder="Thai error message" value="<?=$tax_err_th['donation_flood']?>">
							<input class="in_err" type="text" name="tax_err_en[donation_flood]" placeholder="English error message" value="<?=$tax_err_en['donation_flood']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Donation education']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[donation_education]" placeholder="__" value="<?=$tax_settings['donation_education']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[donation_education]" placeholder="Thai info" value="<?=$tax_info_th['donation_education']?>">
							<input class="in_info" type="text" name="tax_info_en[donation_education]" placeholder="English info" value="<?=$tax_info_en['donation_education']?>">
							<input class="in_err" type="text" name="tax_err_th[donation_education]" placeholder="Thai error message" value="<?=$tax_err_th['donation_education']?>">
							<input class="in_err" type="text" name="tax_err_en[donation_education]" placeholder="English error message" value="<?=$tax_err_en['donation_education']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Exemption disabled person <65 yrs']?></th>
						 <td class="tac">Y/N</td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[exemp_disabled_under]" placeholder="__" value="<?=$tax_settings['exemp_disabled_under']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[exemp_disabled_under]" placeholder="Thai info" value="<?=$tax_info_th['exemp_disabled_under']?>">
							<input class="in_info" type="text" name="tax_info_en[exemp_disabled_under]" placeholder="English info" value="<?=$tax_info_en['exemp_disabled_under']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Exemption tax payer => 65yrs']?></th>
						 <td class="tac">Y/N</td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[exemp_payer_older]" placeholder="__" value="<?=$tax_settings['exemp_payer_older']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[exemp_payer_older]" placeholder="Thai info" value="<?=$tax_info_th['exemp_payer_older']?>">
							<input class="in_info" type="text" name="tax_info_en[exemp_payer_older]" placeholder="English info" value="<?=$tax_info_en['exemp_payer_older']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['First home buyer']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[first_home_allowance]" placeholder="__" value="<?=$tax_settings['first_home_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[first_home_allowance]" placeholder="Thai info" value="<?=$tax_info_th['first_home_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[first_home_allowance]" placeholder="English info" value="<?=$tax_info_en['first_home_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[first_home_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['first_home_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[first_home_allowance]" placeholder="English error message" value="<?=$tax_err_en['first_home_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Year-end shopping']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[year_end_shop_allowance]" placeholder="__" value="<?=$tax_settings['year_end_shop_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[year_end_shop_allowance]" placeholder="Thai info" value="<?=$tax_info_th['year_end_shop_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[year_end_shop_allowance]" placeholder="English info" value="<?=$tax_info_en['year_end_shop_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[year_end_shop_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['year_end_shop_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[year_end_shop_allowance]" placeholder="English error message" value="<?=$tax_err_en['year_end_shop_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Domestic tour']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[domestic_tour_allowance]" placeholder="__" value="<?=$tax_settings['domestic_tour_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[domestic_tour_allowance]" placeholder="Thai info" value="<?=$tax_info_th['domestic_tour_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[domestic_tour_allowance]" placeholder="English info" value="<?=$tax_info_en['domestic_tour_allowance']?>">
							<input class="in_err" type="text" name="tax_err_th[domestic_tour_allowance]" placeholder="Thai error message" value="<?=$tax_err_th['domestic_tour_allowance']?>">
							<input class="in_err" type="text" name="tax_err_en[domestic_tour_allowance]" placeholder="English error message" value="<?=$tax_err_en['domestic_tour_allowance']?>">
						 </td>
					  </tr>
					  <tr>
						 <th><?=$lng['Other allowance']?></th>
						 <td></td>
						 <td>
							<input type="text" class="numeric tar sel" name="tax_settings[other_allowance]" placeholder="__" value="<?=$tax_settings['other_allowance']?>">
						 </td>
						 <td class="info">
							<input class="in_info" type="text" name="tax_info_th[other_allowance]" placeholder="Thai info" value="<?=$tax_info_th['other_allowance']?>">
							<input class="in_info" type="text" name="tax_info_en[other_allowance]" placeholder="English info" value="<?=$tax_info_en['other_allowance']?>">
						 </td>
					  </tr>
					  </tbody>
					</table>
					</div>
				</div>
					
				<div class="tab-pane" id="tab_taxrules">
					<table id="rulesTable" class="basicTable inputs" border="0">
						<thead>
							<tr>
								<th class="tac" colspan="2"><?=$lng['GROSS to NET']?></th>
								<th class="tac"><?=$lng['Tax rate']?></th>
								<th class="tac" colspan="2"><?=$lng['NET to GROSS']?></th>
								<th style="width:50%;"></th>
							</tr>
							<tr>
								<th><?=$lng['From']?></th>
								<th><?=$lng['To']?></th>
								<th><?=$lng['Percent']?></th>
								<th><?=$lng['From']?></th>
								<th><?=$lng['To']?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<? foreach($taxrules as $k=>$v){ ?>
							<tr>
								<td><input style="background:#f3fff3" class="numeric9 changeRules" type="text" name="from[<?=$k?>]" placeholder="..." value="<?=$v['from']?>"></td>
								<td><input style="background:#f3fff3" class="numeric9 changeRules" type="text" name="to[<?=$k?>]" placeholder="..." value="<?=$v['to']?>"></td>
								<td><input style="background:#f3fff3" type="text" class="numeric2 changeRules" name="percent[<?=$k?>]" placeholder="..." value="<?=$v['percent']?>"></td>
								<td><input style="background:#fffff3" readonly name="net_from[<?=$k?>]" type="text" placeholder="..." value="<?=$v['net_from']?>"></td>
								<td><input style="background:#fffff3" readonly name="net_to[<?=$k?>]" type="text"placeholder="..." value="<?=$v['net_to']?>"></td>
								<td></td>
							</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
			
				<div class="tab-pane" id="tab_bank" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table class="basicTable inputs" border="0" id="fixTable">
						<thead>
							<tr>
								<th style="width:1px">Code</th>
								<th class="tac" style="width:1px"><i class="fa fa-check-square-o fa-lg"></i></th>
								<th style="width:50%"><?=$lng['Bank name']?> (<?=$lng['Thai']?>)</th>
								<th style="width:50%"><?=$lng['Bank name']?> (<?=$lng['English']?>)</th>
							</tr>
						</thead>
						<tbody>
						<?	if($settings['bank_codes']){ foreach($settings['bank_codes'] as $k=>$v){ ?>
							<tr>
								<td class="tac" style="border-right:1px #ddd solid">
									<b><input class="nofocus" readonly placeholder="__" name="bank_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></b>
								</td>
								<td class="tac" style="border-right:1px #ddd solid">
									<input name="bank_codes[<?=$k?>][apply]" type="hidden" value="0" />
									<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="bank_codes[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
								</td>
								<td style="border-right:1px #ddd solid">
									<input placeholder="__" name="bank_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>" />
								</td>
								<td>
									<input placeholder="__" name="bank_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>" />
								</td>
							</tr>
						<? }} ?>
						</tbody>
					</table>
					</div>
				</div>
	
				<div class="tab-pane" id="tab_accounting" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<table class="basicTable inputs" id="accounting_table" border="0">
						<thead>
							<tr>
								<th style="width:10%"><?=$lng['Code']?></th>
								<th style="width:25%"><?=$lng['Account name Thai']?></th>
								<th style="width:25%"><?=$lng['Account name English']?></th>
								<th style="width:40%"></th>
								<th><i class="fa fa-trash fa-lg"></i></th>
							</tr>
						</thead>
						<tbody>
						<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
							<tr>
								<td><b><input name="account_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></b></td>
								<td><input name="account_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
								<td><input name="account_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
								<td></td>
								<td class="tac"><a class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>
							</tr>
						<? } } ?>
						</tbody>
					</table>
					<div style="height:10px"></div>
					<button class="btn btn-primary btn-xs" type="button" id="addAccountCode"><?=$lng['Add row']?></button>
							
					</div>
				</div>
	
				<div class="tab-pane" id="tab_allocations" style="height:100%">
					<div style="height:100%; overflow-y:auto">
					<p style="font-weight:600; margin:0 0 3px 2px; color:#a00"><?=$lng['Please reload this page if you have changed Accounting Codes']?></p>
					<table class="basicTable inputs" border="0" style="margin-bottom:10px">
						<thead>
							<tr style="line-height:100%">
								<th colspan="4" class="tac" style="color:#a00; font-size:14px"><?=$lng['Profit & Loss Account']?></th>
							</tr>
							<tr style="line-height:100%">
								<th style="width:20%"><?=$lng['Salary element']?></th>
								<th><?=$lng['Direct']?></th>
								<th><?=$lng['Indirect']?></th>
								<th style="width:40%"></th>
							</tr>
						</thead>
						<tbody>
							<? foreach($debet as $key=>$val){ ?>
							<tr>
								<td style="padding:4px 10px !important"><b><?=$val?></b></td>
								<td>
									<select name="allocations[debet][direct][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['debet']['direct'][$key]) && $allocations['debet']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td>
									<select name="allocations[debet][indirect][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['debet']['indirect'][$key]) && $allocations['debet']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td></td>
							</tr>
							<? } ?>
						</tbody>
					</table>
							
					<table class="basicTable inputs" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="4" class="tac" style="color:#a00; font-size:14px"><?=$lng['Balance sheet Payments']?></th>
							</tr>
							<tr style="line-height:100%">
								<th style="width:20%"><?=$lng['Salary element']?></th>
								<th><?=$lng['Direct']?></th>
								<th><?=$lng['Indirect']?></th>
								<th style="width:40%">&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<? foreach($credit as $key=>$val){ ?>
							<tr>
								<td style="padding:4px 10px !important"><b><?=$val?></b></td>
								<td>
									<select name="allocations[credit][direct][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['credit']['direct'][$key]) && $allocations['credit']['direct'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td>
									<select name="allocations[credit][indirect][<?=$key?>]" style="min-width:100%; width:auto">
										<option selected value="0"><?=$lng['Select']?></option>
									<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
										<option <? if(isset($allocations['credit']['indirect'][$key]) && $allocations['credit']['indirect'][$key] == $v['code']){echo 'selected';}?> value="<?=$v['code']?>"><?=$v['code'].' '.$v[$lang]?></option>
									<? } } ?>
									</select>
								</td>
								<td></td>
							</tr>
							<? } ?>
						</tbody>
					</table>
					
					</div>
				</div>
	
			</div>
		</form>
	</div>
	
	
<script>
var height = window.innerHeight-265;

$(document).ready(function() {
	
	var acc = <?=json_encode($acc_count + 1)?>;
	$("#addAccountCode").click(function(){
		var rowCount = $('#accounting_table tbody tr').length;
		var addrow = '<tr>'+
			'<td><b><input placeholder="Code" name="account_codes['+acc+'][code]" type="text" /></b></td>'+
			'<td><input placeholder="Account name Thai" name="account_codes['+acc+'][th]" type="text" /></td>'+
			'<td><input placeholder="Account name English" name="account_codes['+acc+'][en]" type="text" /></td>'+
			'<td></td>'+
			'<td class="tac"><a class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>'+
		'</tr>';
		if(acc == 1 || rowCount == 0){
			$("#accounting_table tbody").html(addrow);
		}else{
			$("#accounting_table tr:last").after(addrow);
		}
		acc ++;
	});
	$(document).on('click', '.delCode', function (e) {
		$(this).closest('tr').remove();
		$("#sAlert").html('Data is not updated to last changes made').fadeIn(200);
		$("#submitbtn").addClass('flash');
	});

	var posi = <?=json_encode($pos_count + 1)?>;
	$("#addposition").click(function(){
		var addrow = '<tr><td><b><input name="positions['+posi+'][code]" type="text" value="'+posi+'" /></b></td><td><input placeholder="<?=$lng['Thai description']?>" name="positions['+posi+'][th]" type="text" /></td><td><input placeholder="<?=$lng['English description']?>" name="positions['+posi+'][en]" type="text" /></td><td></td></tr>';
		if(posi == 1){
			$("#position_table tbody").html(addrow);
		}else{
			$("#position_table tr:last").after(addrow);
		}
		posi ++;
	});

	function calculateNet(){
		var data = new FormData($('#payrollForm')[0]);
		$.ajax({
			url: AROOT+"def_settings/ajax/calculate_net.php",
			type: 'POST',
			data: data,
			async: false,
			cache: false,
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function(data){
				//alert(data['net_from'][2])
				//$('#dump').html(data['net_from'][2]);
				$.each(data.net_from, function(i,val){
					$('#payrollForm input[name="net_from['+i+']"]').val(val)
				})
				$.each(data.net_to, function(i,val){
					$('#payrollForm input[name="net_to['+i+']"]').val(val)
				})
				
				

			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 8,
					closeConfirm: "true",
				})
			}
		});
	}
	//calculateNet()
	$(".changeRules").on('change', function(){
		calculateNet();
	})
	
	var fixa = <?=json_encode(count($fix_allow)+1)?>;
	var vara = <?=json_encode(count($var_allow)+1)?>;
	$("#addfix").on('click', function(){
		if(fixa <= 15){
			var addrow = '<tr><td class="tac" style="border-right:1px #ddd solid"><b>'+fixa+'</b></td>'+
				'<td class="tac" style="vertical-align:middle"><input name="fix_allow['+fixa+'][apply]" type="hidden" value="0" /><label><input name="fix_allow['+fixa+'][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label></td>'+
				'<td style="border-right:1px #ddd solid"><input placeholder="__" name="fix_allow['+fixa+'][th]" type="text" /></td>'+
				'<td style="border-right:1px #ddd solid"><input placeholder="__" name="fix_allow['+fixa+'][en]" type="text" /></td>'+
				'<td><select name="fix_allow['+fixa+'][tax]"><option value="Y"><?=$lng['Yes']?></option><option value="N"><?=$lng['No']?></option></select></td></tr>';
			$("#fixTable tbody").append(addrow);
			fixa ++;
		}
	});

	$("#addvar").on('click', function(){
		if(vara <= 15){
			var addrow = '<tr><td class="tac" style="border-right:1px #ddd solid"><b>'+vara+'</b></td>'+
				'<td class="tac" style="vertical-align:middle"><input name="var_allow['+vara+'][apply]" type="hidden" value="0" /><label><input name="var_allow['+vara+'][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label></td>'+
				'<td style="border-right:1px #ddd solid"><input placeholder="__" name="var_allow['+vara+'][th]" type="text" /></td>'+
				'<td style="border-right:1px #ddd solid"><input placeholder="__" name="var_allow['+vara+'][en]" type="text" /></td>'+
				'<td><select name="var_allow['+vara+'][tax]"><option value="Y"><?=$lng['Yes']?></option><option value="N"><?=$lng['No']?></option></select></td></tr>';

			$("#varTable tbody").append(addrow);
			vara ++;
		}
	});

	$("#payrollForm").submit(function(e){ 
		e.preventDefault();
		$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var data = $(this).serialize();
		$.ajax({
			url: AROOT+"def_settings/ajax/update_default_settings.php",
			type: 'POST',
			data: data,
			success: function(result){
				//$('#dump').html(result); return false;
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
						duration: 2,
					})
				}else{
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
						closeConfirm: true
					})
				}
				setTimeout(function(){
					$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
					$("#submitbtn").removeClass('flash');
					$("#sAlert").fadeOut(200);
				},300);
			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 8,
					closeConfirm: "true",
				})
			}
		});
	});
	
	var activeTab = localStorage.getItem('activeTab10');
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		localStorage.setItem('activeTab10', $(e.target).data('target'));
	});
	if(activeTab){
		$('#myTab a[data-target="' + activeTab + '"]').tab('show');
	}else{
		$('#myTab a[data-target="#tab_settings"]').tab('show');
	}
	
	$('.autoheight').css('min-height', height);
	$(window).on('resize', function(){
		var height = window.innerHeight-265;
		$('.autoheight').css('min-height', height);
	});	
	
	setTimeout(function(){
		$('body').on('change', 'input, textarea, select', function (e) {
			$("#submitbtn").addClass('flash');
			$("#sAlert").fadeIn(200);
		});	
	},1000);
	
});

</script>	













