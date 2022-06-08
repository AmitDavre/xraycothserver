<?
	//var_dump($sys_settings); exit;
	
	$data = array();
	$sql = "SELECT * FROM ".$cid."_sys_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
			$fix_allow = unserialize($row['fix_allow']);
			$var_allow = unserialize($row['var_allow']);
			$fix_deduct = unserialize($row['fix_deduct']);
			$var_deduct = unserialize($row['var_deduct']);
			$payslip = unserialize($row['payslip_field']);
		}
	}else{
		//var_dump(mysqli_error($dbc));
	}

	$pperiods = array();
	$sql = "SELECT * FROM ".$cid."_payroll_months WHERE month LIKE '".$cur_year."%'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$nr = explode('_',$row['month']);
			$pperiods[$nr[1]] = $row;
		}
	}else{
		//var_dump(mysqli_error($dbc));
	}

	if(isset($_GET['sso'])){

		foreach($rego_settings['sso_defaults'] as $k=>$v){
			$pperiods[$k]['sso_eRate'] = $v['sso_eRate'];
			$pperiods[$k]['sso_eMax'] = $v['sso_eMax'];
			$pperiods[$k]['sso_eMin'] = $v['sso_eMin'];
			$pperiods[$k]['sso_cRate'] = $v['sso_cRate'];
			$pperiods[$k]['sso_cMax'] = $v['sso_cMax'];
			$pperiods[$k]['sso_cMin'] = $v['sso_cMin'];
			$pperiods[$k]['wht'] = $v['wht'];
		}
	}
	
	//var_dump($data); exit;
	//var_dump($var_allow); exit;
	
	


	//var_dump($pperiods); exit;
	//var_dump($fix_deduct); exit;
	$taxrules = unserialize($rego_settings['taxrules']);
	$tax_settings = unserialize($rego_settings['tax_settings']);
	//var_dump($tax_settings);
	$tax_info_th = unserialize($rego_settings['tax_info_th']);
	//var_dump($tax_info_th);
	$tax_info_en = unserialize($rego_settings['tax_info_en']);
	//var_dump($tax_info_en);
	$tax_err_th = unserialize($rego_settings['tax_err_th']);
	//var_dump($tax_err_th);
	$tax_err_en = unserialize($rego_settings['tax_err_en']);
	//var_dump($tax_err_en);
?>
	<style>
		.xdatepick {
			cursor:pointer;
			padding-left:20px !important;
			min-width:110px;
		}

		#submitBtn {
			position:relative !important;
			top:0 !important;
			right:0 !important;
			margin-left:3px;
		}
	</style>
	
	<h2 style="padding-right:60px">
		<i class="fa fa-cog"></i>&nbsp; <?=$lng['Payroll settings']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
	
	<form id="payrollForm">
		<div class="main">
			<div style="padding:0 0 0 20px" id="dump"></div>
	
			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link" href="#tab_payroll" data-toggle="tab"><?=$lng['General']?> / <?=$lng['SSO']?> / <?=$lng['PND']?><? //=$lng['Payroll']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_fund" data-toggle="tab"><?=$lng['PSF']?> / <?=$lng['PVF']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_reports" data-toggle="tab"><?=$lng['Payslips']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_allowances" data-toggle="tab"><?=$lng['Allowances']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_deductions" data-toggle="tab"><?=$lng['Deductions']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_periods" data-toggle="tab"><?=$lng['Periods']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_tax_rates" data-toggle="tab"><?=$lng['Tax rates']?></a></li>
				<!--<li class="hide-xxs nav-item" style="padding:10px 0 0 15px">
					<b style="color:#b00"><?=$lng['Please fill in all fields with a * in every Tab']?></b>
				</li>-->
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				<div style="position:absolute; top:12px; right:15px">
					<button class="btn btn-primary" onClick="window.location.href='index.php?mn=610&sso'" type="button"><?=$lng['Get default SSO PND3']?></button>
					<button class="btn btn-primary" id="submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				</div>
				
				<div class="tab-pane" id="tab_payroll">
					<table class="basicTable inputs" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2">General defaults</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="width:5%;"><?=$lng['Payroll startdate']?></th>
								<td><input style="color:#b00; font-weight:600" readonly type="text" value="<?=$sys_settings['pr_startdate']?>" />
								</td>
							</tr>
							<!--<tr>
								<th>Contract type<? //=$lng['Contract type']?></th>
								<td>
									<select name="contract_type" style="width:100%">
										<option <? if($data['contract_type'] == 'month'){echo 'selected';}?> value="month"><?=$lng['Monthly wage']?></option>
										<option <? if($data['contract_type'] == 'day'){echo 'selected';}?> value="day"><?=$lng['Daily wage']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th>Calculation base<? //=$lng['Calculation base']?></th>
								<td>
									<select name="calc_base" style="width:100%">
										<option <? if($data['calc_base'] == 'gross'){echo 'selected';}?> value="gross"><?=$lng['Gross amount']?></option>
										<option <? if($data['calc_base'] == 'net'){echo 'selected';}?> value="net"><?=$lng['Net amount']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th>Base OT rate<? //=$lng['Base OT rate']?></th>
								<td>
									<input class="sel numeric" type="text" name="ot_rate" placeholder="..." value="<?=$data['ot_rate']?>">
								</td>
							</tr>-->
						</tbody>
							
						<thead>
							<tr style="line-height:100%">
								<th colspan="2">SSO defaults</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['SSO amount']?></th>
								<td>
									<select name="sso_act_max" style="width:100%">
										<option <? if($sys_settings['sso_act_max'] == 'act'){echo 'selected';}?> value="act"><?=$lng['Actual amount']?></option>
										<option <? if($sys_settings['sso_act_max'] == 'max'){echo 'selected';}?> value="max"><?=$lng['Max']?> <?=number_format(15000)?> <?=$lng['Baht']?></option>
									</select>
								</td>
							</tr>
							<!--<tr>
								<th><?=$lng['Calculate SSO']?></th>
								<td>
									<select name="calc_sso" style="width:100%">
										<? foreach($noyes01 as $k=>$v){
												echo '<option ';
												if($data['calc_sso']==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>-->
							<!--<tr>
								<th>List of hospitals<? //=$lng['SSO account ID']?></th>
								<td>
									<table class="subTable">
										<thead>
											<tr style="line-height:100%">
												<th style="width:20%">Name</th>
												<th style="width:80%">Address</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="text" name="hospitals[1][name]" placeholder="..." value=""></td>
												<td><input type="text" name="hospitals[1][address]" placeholder="..." value=""></td>
											</tr>
											<tr>
												<td><input type="text" name="hospitals[2][name]" placeholder="..." value=""></td>
												<td><input type="text" name="hospitals[2][address]" placeholder="..." value=""></td>
											</tr>
											<tr>
												<td><input type="text" name="hospitals[3][name]" placeholder="..." value=""></td>
												<td><input type="text" name="hospitals[3][address]" placeholder="..." value=""></td>
											</tr>
											<tr>
												<td><input type="text" name="hospitals[4][name]" placeholder="..." value=""></td>
												<td><input type="text" name="hospitals[4][address]" placeholder="..." value=""></td>
											</tr>
											<tr>
												<td><input type="text" name="hospitals[5][name]" placeholder="..." value=""></td>
												<td><input type="text" name="hospitals[5][address]" placeholder="..." value=""></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>-->
						</tbody>
							
						<!--<thead>
							<tr style="line-height:100%">
								<th colspan="2">PND defaults</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Tax calculation method']?></th>
								<td>
									<select name="tax_method" style="width:100%">
										<option <? if($data['tax_method'] == 'cam'){echo "selected";} ?> value="cam"><?=$lng['Calculate in Advance Method']?> (CAM)</option>
										<option <? if($data['tax_method'] == 'acm'){echo "selected";} ?> value="acm"><?=$lng['Accumulative Calculation Method']?> (ACM)</option>
										<option <? if($data['tax_method'] == 'ytd'){echo "selected";} ?> value="ytd">Year To Date<? //=$lng['xxx']?> (YTD)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Calculate Tax']?></th>
								<td>
									<select name="calc_tax" style="width:100%">
										<? foreach($noyes01 as $k=>$v){
												echo '<option ';
												if($data['calc_tax']==$k){echo 'selected';}
												echo ' value="'.$k.'">'.$v.'</option>';
										} ?>
									</select>
								</td>
							</tr>
							<tr>
								<th>PND1 / PND3<? //=$lng['Bank']?></th>
								<td>
									<select name="pnd" style="width:100%">
										<option <? if($data['pnd'] == 1){echo 'selected';}?> value="1">PND1</option>
										<option <? if($data['pnd'] == 3){echo 'selected';}?> value="3">PND3</option>
									</select>
								</td>
							</tr>-->
						</tbody>
					</table>
				</div>
							
				<div class="tab-pane" id="tab_fund">
					<table class="basicTable inputs" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2">Pension Fund (internal)</th>
							</tr>
						</thead>
						<tbody>
							<!--<tr>
								<th>PSF applied<? //=$lng['PSF applied']?></th>
								<td>
									<select name="calc_psf" style="width:100%">
									<? foreach($noyes as $k=>$v){
											echo '<option ';
											if($data['calc_psf']==$k){echo 'selected';}
											echo ' value="'.$k.'">'.$v.'</option>';
									} ?>
									</select>
								</td>
							</tr>-->
							<tr>
								<th>PSF Fund name<? //=$lng['PVF account ID']?></th>
								<td><input name="psf_name" type="text" value="<?=$data['psf_name']?>" /></td>
							</tr>
							<!--<tr>
								<th>Contribute rate employee %<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric13" name="psf_rate_emp" type="text" value="<?=$data['psf_rate_emp']?>" />
								</td>
							</tr>
							<tr>
								<th>Contribute rate employer %<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric13" name="psf_rate_com" type="text" value="<?=$data['psf_rate_com']?>" />
								</td>
							</tr>-->
							<tr>
								<th>Contribution employee THB<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric13" name="psf_thb_emp" type="text" value="<?=$data['psf_thb_emp']?>" />
								</td>
							</tr>
							<tr>
								<th>Contribute employer THB<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric13" name="psf_thb_com" type="text" value="<?=$data['psf_thb_com']?>" />
								</td>
							</tr>
						</tbody>
						<thead>
							<tr style="line-height:100%">
								<th colspan="2">Provident Fund</th>
							</tr>
						</thead>
						<tbody>
							<!--<tr>
								<th><?=$lng['PVF applied']?></th>
								<td>
									<select name="calc_pvf" style="width:100%">
									<? foreach($noyes01 as $k=>$v){
											echo '<option ';
											if($data['calc_pvf']==$k){echo 'selected';}
											echo ' value="'.$k.'">'.$v.'</option>';
									} ?>
									</select>
								</td>
							</tr>-->
							<tr>
								<th>PVF account ID<? //=$lng['PVF account ID']?></th>
								<td><input name="pvf_idnr" type="text" value="<?=$data['pvf_idnr']?>" /></td>
							</tr>
							<tr>
								<th>PVF Fund name<? //=$lng['PVF account ID']?></th>
								<td><input name="pvf_name" type="text" value="<?=$data['pvf_name']?>" /></td>
							</tr>
							<!--<tr>
								<th>Contribute rate employee %<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric" name="pvf_rate_emp" type="text" value="<?=$data['pvf_rate_emp']?>" />
								</td>
							</tr>
							<tr>
								<th>Contribute rate employer %<? //=$lng['xxx']?></th>
								<td>
									<input class="sel numeric" name="pvf_rate_com" type="text" value="<?=$data['pvf_rate_com']?>" />
								</td>
							</tr>-->
						</tbody>
					</table>
				</div>
							
				<div class="tab-pane" id="tab_reports">
					<table class="basicTable inputs" border="0">
						<thead>
							<tr style="line-height:100%">
								<th colspan="2">Payroll reports</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th><?=$lng['Payslip template']?> </th>
								<td style="padding-top:2px;padding-right:10px">
									<select name="payslip_template" style="width:100%">
										<option <? if($sys_settings['payslip_template'] == 'la4'){echo "selected ";}?> value="la4"><?=$lng['Laser A4 template']?></option>
										<option <? if($sys_settings['payslip_template'] == 'la5'){echo "selected ";}?> value="la5">Laser A5 template<? //=$lng['Laser A5 template']?></option>
										<option <? if($sys_settings['payslip_template'] == 'tme'){echo "selected ";}?> value="tme"><?=$lng['Thai matrix template (A5 Empty)']?></option>
										<option <? if($sys_settings['payslip_template'] == 'tmp'){echo "selected ";}?> value="tmp"><?=$lng['Thai matrix template (A5 Preprinted)']?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th style="width:5px"><?=$lng['Payslip fields']?></th>
								<td class="pad410">
									<label><input <? if(isset($payslip['ytd1'])){echo 'checked';} ?> name="payslip_field[ytd1]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Income']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd2'])){echo 'checked';} ?> name="payslip_field[ytd2]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Tax']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd3'])){echo 'checked';} ?> name="payslip_field[ytd3]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Prov. Fund']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd4'])){echo 'checked';} ?> name="payslip_field[ytd4]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Social SF']?></span></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input <? if(isset($payslip['ytd5'])){echo 'checked';} ?> name="payslip_field[ytd5]" type="checkbox" value="1" class="checkbox style-0" /><span><?=$lng['YTD. Other allowance']?></span></label>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Payslip rate']?> </th>
								<td style="padding-top:2px;padding-right:10px;">
									<select name="payslip_rate" style="width:100%">
										<option disabled selected value=""><?=$lng['Select']?></option>
										<option <? if($sys_settings['payslip_rate'] == 'em'){echo "selected ";}?> value="em"><?=$lng['Empty']?></option>
										<option <? if($sys_settings['payslip_rate'] == 'dr'){echo "selected ";}?> value="dr"><?=$lng['Day rate']?></option>
										<option <? if($sys_settings['payslip_rate'] == 'hr'){echo "selected ";}?> value="hr"><?=$lng['Hour rate']?></option>
									</select>
								</td>
							</tr>
								<th>Show address<? //=$lng['xxx']?> </th>
								<td style="padding-top:2px;padding-right:10px">
									<select name="show_address" style="width:100%">
										<option <? if($sys_settings['show_address'] == 1){echo "selected ";}?> value="1"><?=$lng['Yes']?></option>
										<option <? if($sys_settings['show_address'] == 0){echo "selected ";}?> value="0"><?=$lng['No']?></option>
									</select>
								</td>
							</tr>
							</tr>
								<th>Show bankinfo<? //=$lng['xxx']?> </th>
								<td style="padding-top:2px;padding-right:10px">
									<select name="show_bankinfo" style="width:100%">
										<option <? if($sys_settings['show_bankinfo'] == 1){echo "selected ";}?> value="1"><?=$lng['Yes']?></option>
										<option <? if($sys_settings['show_bankinfo'] == 0){echo "selected ";}?> value="0"><?=$lng['No']?></option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			
				<div class="tab-pane" id="tab_tax_deduct">
					<? //include('tax_deductions_inc.php')?>
				</div>
			
				<div class="tab-pane" id="tab_allowances">
					<div class="tab-content-left">
						<div style="overflow-x:auto">
						<table class="basicTable inputs" border="0" id="fixTable">
							<thead>
								<tr style="border-bottom:1px solid #fff">
									<th colspan="6" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Fixed allowances']?></th>
								</tr>
								<tr>
									<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
									<th><?=$lng['Apply']?></th>
									<th style="width:50%;"><?=$lng['Thai description']?></th>
									<th style="width:50%; text-align:left"><?=$lng['English description']?></th>
									<th><?=$lng['Taxable']?></th>
									<th class="tac" style="xwhite-space:normal"><?=$lng['Rate']?> <i data-toggle="tooltip" title="<?=$lng['Include in Day & Hour rate']?>" class="fa fa-question-circle fa-lg"></i></th>
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
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="fix_allow[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="fix_allow[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
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
						</div>
					</div>
					<div class="tab-content-right">
						<div style="overflow-x:auto">
						<table class="basicTable inputs" border="0" id="varTable">
							<thead>
								<tr style="border-bottom:1px solid #fff">
									<th colspan="5" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Variable allowances']?></th>
								</tr>
								<tr>
									<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
									<th><?=$lng['Apply']?></th>
									<th style="width:50%;"><?=$lng['Thai description']?></th>
									<th style="width:50%; text-align:left"><?=$lng['English description']?></th>
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
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="var_allow[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="var_allow[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
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
						</div>
					</div>
				</div>
				
				<div class="tab-pane" id="tab_deductions">
					<div class="tab-content-left">
						<div style="overflow-x:auto">
						<table class="basicTable inputs" border="0" id="fixTable">
							<thead>
								<tr style="border-bottom:1px solid #fff">
									<th colspan="6" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Fixed deductions']?></th>
								</tr>
								<tr>
									<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
									<th><?=$lng['Apply']?></th>
									<th style="width:50%;"><?=$lng['Thai description']?></th>
									<th style="width:50%; text-align:left"><?=$lng['English description']?></th>
									<th class="tac"><?=$lng['Tax']?></th>
								</tr>
							</thead>
							<tbody>
							<?	if($fix_deduct){ foreach($fix_deduct as $k=>$v){ ?>
								<tr>
									<td class="tac" style="border-right:1px #ddd solid"><b><?=$k?></b></td>
									<td class="tac" style="vertical-align:middle">
										<input name="fix_deduct[<?=$k?>][apply]" type="hidden" value="0" />
										<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="fix_deduct[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
									</td>
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="fix_deduct[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
									<td><input placeholder="..." name="fix_deduct[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
									<td>
										<select name="fix_deduct[<?=$k?>][tax]" style="width:auto">
											<option <? if($v['tax'] == '0'){echo "selected ";} ?>value="0"><?=$lng['Before']?></option>
											<option <? if($v['tax'] == '1'){echo "selected ";} ?>value="1"><?=$lng['After']?></option>
										</select>
									</td>
								</tr>
							<? }} ?>
							</tbody>
						</table>
						</div>
					</div>
					<div class="tab-content-right">
						<div style="overflow-x:auto">
						<table class="basicTable inputs" border="0" id="varTable">
							<thead>
								<tr style="border-bottom:1px solid #fff">
									<th colspan="5" class="tac" style="padding:4px; color:#a00; font-size:15px;"><?=$lng['Variable deductions']?></th>
								</tr>
								<tr>
									<th style="width:1px">&nbsp;&nbsp;#&nbsp;&nbsp;</th>
									<th><?=$lng['Apply']?></th>
									<th style="width:50%;"><?=$lng['Thai description']?></th>
									<th style="width:50%; text-align:left"><?=$lng['English description']?></th>
									<th class="tac"><?=$lng['Tax']?></th>
								</tr>
							</thead>
							<tbody>
							<?	if($var_deduct){ foreach($var_deduct as $k=>$v){ ?>
								<tr>
									<td class="tac" style="border-right:1px #ddd solid"><b><?=$k?></b></td>
									<td class="tac" style="vertical-align:middle">
										<input name="var_deduct[<?=$k?>][apply]" type="hidden" value="0" />
										<label><input <? if($v['apply'] == 1){echo 'checked';} ?> name="var_deduct[<?=$k?>][apply]" type="checkbox" value="1" class="checkbox notxt" /><span></span></label>
									</td>
									<td style="border-right:1px #ddd solid"><input placeholder="..." name="var_deduct[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
									<td><input placeholder="..." name="var_deduct[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
									<td>
										<select name="var_deduct[<?=$k?>][tax]" style="width:auto">
											<option <? if($v['tax'] == '0'){echo "selected ";} ?>value="0"><?=$lng['Before']?></option>
											<option <? if($v['tax'] == '1'){echo "selected ";} ?>value="1"><?=$lng['After']?></option>
										</select>
									</td>
								</tr>
							<? }} ?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
				
				<div class="tab-pane" id="tab_periods">
					<table class="basicTable compact inputs" id="period_table" border="0">
						<thead>
							<tr>
								<th></th>
								<th colspan="2" class="tac"><?=$lng['Time']?> / <?=$lng['Payroll']?></th>
								<th colspan="2" class="tac"><?=$lng['Leave']?></th>
								<th colspan="2" class="tac"><?=$lng['Payroll']?></th>
								<th colspan="3" class="tac"><?=$lng['SSO']?> <?=$lng['Employee']?></th>
								<th colspan="3" class="tac"><?=$lng['SSO']?> <?=$lng['Company']?></th>
								<th><?=$lng['PND']?> 3</th>
								<th style="width:80%">&nbsp;</th>
							</tr>
							<tr>
								<th class="tac"><?=$lng['Month']?></th>
								<th class="tac"><?=$lng['Start']?></th>
								<th class="tac"><?=$lng['Ends']?></th>
								<th class="tac"><?=$lng['Start']?></th>
								<th class="tac"><?=$lng['Ends']?></th>
								<th class="tac"><?=$lng['Paydate']?></th>
								<th class="tac"><i class="fa fa-lock fa-lg"></i></th>
								<th class="tac" style="min-width:60px"><?=$lng['Rate']?> %</th>
								<th class="tac" style="min-width:60px"><?=$lng['Max']?>.</th>
								<th class="tac" style="min-width:60px"><?=$lng['Min']?>.</th>
								<th class="tac" style="min-width:60px"><?=$lng['Rate']?> %</th>
								<th class="tac" style="min-width:60px"><?=$lng['Max']?>.</th>
								<th class="tac" style="min-width:60px"><?=$lng['Min']?>.</th>
								<th class="tac" style="min-width:60px"><?=$lng['WHT']?> %</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<? foreach($pperiods as $k=>$v){ $id = $cur_year.'_'.$k;?>
							<tr>
								<td style="padding:4px 10px !important"><b><?=$months[$k]?></b></td>
								<td>
									<input readonly class="xdatepick tstart<?=$k?>" name="periods[time_start][<?=$id?>]" type="text" value="<?=$v['time_start']?>" />
								</td>
								<td>
									<input readonly data-m="<?=$k?>" class="xdatepick tend" name="periods[time_end][<?=$id?>]" type="text" value="<?=$v['time_end']?>" />
								</td>
								<td>
									<input readonly class="xdatepick lstart<?=$k?>" name="periods[leave_start][<?=$id?>]" type="text" value="<?=$v['leave_start']?>" />
								</td>
								<td>
									<input readonly data-m="<?=$k?>" class="xdatepick lend" name="periods[leave_end][<?=$id?>]" type="text" value="<?=$v['leave_end']?>" />
								</td>
								<td>
									<input readonly class="xdatepick" name="periods[paydate][<?=$id?>]" type="text" value="<?=$v['paydate']?>" />
								</td>
								<td style="padding:0 10px !important" class="tac"><a href="#"><i class="fa fa-lock fa-lg"></i></a></td>
								<td>
									<input class="sel numeric tac" type="text" name="periods[sso_eRate][<?=$id?>]" value="<?=$v['sso_eRate']?>" />
								</td>
								<td>
									<input class="sel numeric tar" type="text" name="periods[sso_eMax][<?=$id?>]" value="<?=$v['sso_eMax']?>" />
								</td>
								<td>
									<input class="sel numeric tar" type="text" name="periods[sso_eMin][<?=$id?>]" value="<?=$v['sso_eMin']?>" />
								</td>
								<td>
									<input class="sel numeric tac" type="text" name="periods[sso_cRate][<?=$id?>]" value="<?=$v['sso_cRate']?>" />
								</td>
								<td>
									<input class="sel numeric tar" type="text" name="periods[sso_cMax][<?=$id?>]" value="<?=$v['sso_cMax']?>" />
								</td>
								<td>
									<input class="sel numeric tar" type="text" name="periods[sso_cMin][<?=$id?>]" value="<?=$v['sso_cMin']?>" />
								</td>
								<td>
									<input class="sel numeric tar" type="text" name="periods[wht][<?=$id?>]" value="<?=$v['wht']?>" />
								</td>
								<td></td>
							</tr>
						<? } ?>
						
						</tbody>
					</table>
				</div>
				
				<div class="tab-pane" id="tab_tax_rates">
					<table id="rulesTable" class="basicTable" border="0">
						<thead>
							<tr>
								<th class="tac" colspan="2"><?=$lng['GROSS to NET']?></th>
								<th class="tac"><?=$lng['Tax rate']?></th>
								<th class="tac" colspan="2"><?=$lng['NET to GROSS']?></th>
								<th style="width:50%;"></th>
							</tr>
							<tr style="line-height:100%">
								<th class="tar"><?=$lng['From']?></th>
								<th class="tar"><?=$lng['To']?></th>
								<th class="tar"><?=$lng['Percent']?></th>
								<th class="tar"><?=$lng['From']?></th>
								<th class="tar"><?=$lng['To']?></th>
								<th></th>
							</tr>
						</thead>
					<tbody>
					<? foreach($taxrules as $k=>$v){ ?>
					<tr>
						<td class="tar" style="background:#f3fff3 !important"><?=number_format($v['from'])?></td>
						<td class="tar" style="background:#f3fff3 !important"><?=number_format($v['to'])?></td>
						<td class="tar" style="background:#f3fff3 !important"><?=$v['percent']?> %</td>
						<td class="tar" style="background:#fffff3 !important"><?=number_format($v['net_from'])?></td>
						<td class="tar" style="background:#fffff3 !important"><?=number_format($v['net_to'])?></td>
						<td></td>
					</tr>
					<? } ?>
					</tbody>
					</table>
				</div>
			
			</div>
				
		</div>
	</form>
	
<script>
	
$(document).ready(function() {
			
	$('.xdatepick').datepicker({
		format: "dd-mm-yyyy",
		autoclose: true,
		inline: true,
		orientation: 'auto bottom',
		language: lang,
		todayHighlight: true,
		startView: 'year',
		//startDate : startYear,
		//endDate   : endYear
	})

	$('.tend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.tstart'+m).val(tomorrow);
		}
	})
	$('.lend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.lstart'+m).val(tomorrow);
		}
	})
	$('.pend').on('change', function(){
		var m = $(this).data('m')+1;
		var parts = this.value.split('-');
		var d = parts[2]+'-'+parts[1]+'-'+parts[0]; 				
		var today = moment(d);
		var tomorrow = moment(today).add(1, 'days').format('DD-MM-YYYY');
		if(m <= 12){
			$('.pstart'+m).val(tomorrow);
		}
	})

	$("#payrollForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "ajax/update_payroll_settings.php",
			type: 'POST',
			data: formData,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitBtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
						duration: 2,
					})
					//setTimeout(function(){location.reload();},2000);
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
	});
	
	var activeTabPay = localStorage.getItem('activeTabPay');
	if(activeTabPay){
		$('.nav-link[href="' + activeTabPay + '"]').tab('show');
	}else{
		$('.nav-link[href="#tab_payroll"]').tab('show');
	}
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
		localStorage.setItem('activeTabPay', $(e.target).attr('href'));
	});
	
	$('input, textarea').on('keyup', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
	$('select, .checkbox').on('change', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
			
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
