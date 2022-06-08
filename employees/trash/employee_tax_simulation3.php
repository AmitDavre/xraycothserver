<?php
	
	//if($_SESSION['xhr']['access']['employee']['module'] == 0){echo '<div class="msg_nopermit">You have no permission to enter this module</div>'; exit;}
		
		/*$income = 151000;
	
		$rules = unserialize($pr_settings['taxrules']);	
		foreach($rules as $k => $v){
			if($v['to']>0){$band_top[$k] = (int)$v['to'];}
			$band_rate[$k] = (float)$v['percent']/100;
			$band[$k] = 0;
		}
		$band_top = array_reverse($band_top,true);
		$band_rate = array_reverse($band_rate,true);
		//var_dump($band_top);
		
		foreach($band_top as $k => $v){
			if($income > $v) {
				 $part = $income - $v;
				 //$band [$k+1] = ceil($part * $band_rate[$k+1]);
				 $band [$k+1] = $part * $band_rate[$k+1];
				 $income = $band_top[$k];
			}
			if(isset($part)){
				$data[$k][1] = $part;
				$data[$k][2] = $band[$k+1];
				$data[$k][3] = $band_rate[$k+1];
			}
		}
		$total_tax = 0;
		foreach($band as $v){$total_tax += $v;}
		if(isset($data)){$data = array_reverse($data);}
		
		if($income < $band_top[0]){
			$tax[0][1] = $income; $tax[0][2] = 0;
			$tax[5][1] = 0;
		}else{
			$tax[0][1] = $band_top[0]; $tax[0][2] = 0;
		}
		
		if(isset($data[0])){$tax[5][1] = $data[0][1]; $tax[5][2] = $data[0][2];}else{$tax[5][2] = 0; $tax[5][2] = 0;}
		if(isset($data[1])){$tax[10][1] = $data[1][1]; $tax[10][2] = $data[1][2];}else{$tax[10][1] = 0; $tax[10][2] = 0;}
		if(isset($data[2])){$tax[15][1] = $data[2][1]; $tax[15][2] = $data[2][2];}else{$tax[15][1] = 0; $tax[15][2] = 0;}
		if(isset($data[3])){$tax[20][1] = $data[3][1]; $tax[20][2] = $data[3][2];}else{$tax[20][1] = 0; $tax[20][2] = 0;}
		if(isset($data[4])){$tax[25][1] = $data[4][1]; $tax[25][2] = $data[4][2];}else{$tax[25][1] = 0; $tax[25][2] = 0;}
		if(isset($data[5])){$tax[30][1] = $data[5][1]; $tax[30][2] = $data[5][2];}else{$tax[30][1] = 0; $tax[30][2] = 0;}
		if(isset($data[6])){$tax[35][1] = $data[6][1]; $tax[35][2] = $data[6][2];}else{$tax[35][1] = 0; $tax[35][2] = 0;}
		$tax['year'] = $total_tax;*/
		
		//var_dump($tax); //exit;
	
	//var_dump($emp_status);
	$employee = '';
	$cgross = 0;
	$cdeduct = 0;
	$ctaxable = 0;
	if(!isset($_GET['id'])){
		$_GET['id'] = 0;
	}else{
		$sql = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_GET['id']."'";
		if($res = $dbc->query($sql)){
			$data = $res->fetch_assoc();
			$employee = $data['emp_id'].' - '.$data[$lang.'_name'];
		}
		include(DIR.'payroll/inc/payroll_functions.php');
		include(DIR.'payroll/inc/tax_modulle.php');
		$xdata = getEmployeeTaxdata($_GET['id']);
		
		$cgross = $xdata['data'][13]['gross'];
		$cdeduct = $xdata['taxable']['tot_deductions'];
		$ctaxable = $cgross - $cdeduct;
		if($ctaxable < 0){$ctaxable = 0;}
		//var_dump($data);
		//var_dump($cdeduct);
		//var_dump($ctaxable);
	}
	$min_tax_deductions = $data['total_tax_deductions'] + $data['tax_standard_deduction'];
	$total_tax_deductions = $data['total_tax_deductions'] + $data['tax_standard_deduction'] + $data['tax_allow_pvf'] + $data['tax_allow_sso'];
	//var_dump($data);
	
	
	$fix_allow = getUsedFixAllow($lang);
	foreach($fix_allow as $k=>$v){
		$fix_allowances[$v] = $data['fix_allow_'.$k];
	}
	//var_dump($fix_allowances);

	$var_allow = getUsedVarAllow($lang);
	foreach($var_allow as $k=>$v){
		$var_allowances[$v] = 0;
	}
	//var_dump($var_allowances);
	
/*	$net = array();
	$val = 150000;
	$add = 1052.63;
	for($i=151000; $i<293000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 300555.56;
	$net[293000] = round($val);
	$add = 1111.11;
	for($i=294000; $i<473000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 500588.24;
	$net[473000] = round($val);
	$add = 1176.47;
	for($i=474000; $i<686000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 751250;
	$net[686000] = round($val);
	$add = 1250;
	for($i=687000; $i<886000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 1001333.33;
	$net[886000] = round($val);
	$add = 1333.333;
	for($i=887000; $i<1635000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 2000000;
	$net[1635000] = round($val);
	$add = 1428.5715;
	for($i=1635000; $i<3735000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
	$val = 5000000;
	$net[3735000] = round($val);
	$add = 1537.246;
	for($i=3735000; $i<10000000; $i+=1000){
		$val += $add;
		//var_dump($val);
		$net[$i] = round($val);
	}
*/	
	//var_dump($net[9999000]);

?>
<style>
	table.main_table {
		border:0;
		width:100%;
		xmin-height:600px;
		border-collapse:separate;
		border-spacing: 15px;
		table-layout:fixed;
		margin-bottom:100px;
	}
	table.main_table td {
		xborder:1px red solid;
		vertical-align:top;
		text-align:left;
		padding:0;
		margin:5px;
	}
	
	table.sub_table {
		xborder:1px solid #ccc;
		width:100%;
		xmin-height:250px;
		border-collapse:collapse;
		table-layout:auto;
		background:#fff;
	}
	table.sub_table th {
		border:1px #900 solid;
		background:#900;
		color:#fff;
		vertical-align:middle;
		text-align:left;
		padding:5px 5px 5px 10px;
		white-space:nowrap;
	}
	table.sub_table td {
		border:1px #ddd solid;
		vertical-align:middle;
		text-align:left;
		padding:3px 10px;
		width:100px;
	}
	table.sub_table td.title {
		font-weight:600;
		color:#006699;
		vertical-align:middle;
		white-space:nowrap;
	}
	table.sub_table td.fatborder {
		border-bottom:2px solid #ccc;
	}
	table.sub_table td.nopad {
		padding:0;
	}
	table.sub_table td span {
		text-align:right;
		display:block;
		font-weight:600;
		color:#900;
	}
	table.sub_table td input[type="text"], select {
		border:0;
		margin:0;
		display:block;
		padding:3px 10px;
		width:100%;
		background: #ffd;
	}
	table.sub_table td select {
		padding:2px 8px;
	}
	table.sub_table td input[type="text"]:hover, select:hover {
		border:0;
	}
	.btn.btn-default.btn-xs {
		margin:0 0 0 10px !important;
		padding:0px 10px !important;
		font-weight:400 !important;
		line-height:16px !important;
		float:right;
	}
</style>
	
	<div class="widget autoheight">
		<h2><button onClick="location.href='index.php?mn=102&id=<?=$_GET['id']?>'" style="float:left; margin-top:3px" class="btn btn-primary btn-sm" type="button"><i class="fa fa-edit"></i>&nbsp; <?=$lng['Edit employee']?></button>&nbsp;&nbsp;&nbsp; <i class="fa fa-calculator"></i>&nbsp; <?=$lng['Tax simulation']?> <?=$employee?> </h2>
		<div class="widget_body" style="position:relative; padding:0px">
	
		<table class="main_table"><tr><td>
			<form id="currentForm">
			<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:green; border:1px solid green">
						<?=$lng['Current Package']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinCurrent"></i> 
						<button id="btnYearOverview" class="btn btn-default btn-xs" type="button"><?=$lng['Year overview']?></button>
						<a target="_blank" href="<?=ROOT?>employees/tax/print_current_package.php?id=<?=$_GET['id']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
					</th>
				</tr>
				<tr>
					<td class="title"><?=$lng['Gross income full year']?></td>
					<td><span><?=number_format($cgross,2)?></span></td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Personal Tax deductions']?></td>
					<td><span><?=number_format($cdeduct,2)?></span></td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td class="title fatborder"><?=$lng['Taxable income']?></td>
					<td class="fatborder"><span><?=number_format($ctaxable,2)?></span></td>
					<td class="fatborder" colspan="3"></td>
				</tr>
				<tr>
					<td><?=$lng['From 0 to 150,000']?></td>
					<td><span id="ctax0">0</span></td>
					<td class="tar" id="cper0">0 %</td>
					<td><span id="ctax_0">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 150,001 to 300,000']?></td>
					<td><span id="ctax5">0</span></td>
					<td class="tar" id="cper5">0 %</td>
					<td><span id="ctax_5">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 300,001 to 500,000']?></td>
					<td><span id="ctax10">0</span></td>
					<td class="tar" id="cper10">0 %</td>
					<td><span id="ctax_10">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 500,001 to 750,000']?></td>
					<td><span id="ctax15">0</span></td>
					<td class="tar" id="cper15">0 %</td>
					<td><span id="ctax_15">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 750,001 to 1,000,000']?></td>
					<td><span id="ctax20">0</span></td>
					<td class="tar" id="cper20">0 %</td>
					<td><span id="ctax_20">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 1000001 to 2000000']?></td>
					<td><span id="ctax25">0</span></td>
					<td class="tar" id="cper25">0 %</td>
					<td><span id="ctax_25">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 2,000,001 to 5,000,000']?></td>
					<td><span id="ctax30">0</span></td>
					<td class="tar" id="cper30">0 %</td>
					<td><span id="ctax_30">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['Over 5,000,000']?></td>
					<td><span id="ctax35">0</span></td>
					<td class="tar" id="cper35">0 %</td>
					<td><span id="ctax_35">0</span></td>
					<td></td>
				</tr>
				<tr style="background:#eee">
					<td class="title" style="text-align:right"><?=$lng['Total per year']?> :</td>
					<td><span id="ctot_year">0</span></td>
					<td></td>
					<td><span id="ctot_tax">0</span></td>
					<td><span id="cpercent">0.00 %</span></td>
				</tr>
			</table>
			</form>
				
		</td><td>
			<form id="basicForm">
			<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
			<table style="width:100%"><tr><td style="width:55%">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background: #36c; border:1px solid #36c">
						<?=$lng['Basic Package Monthly']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinBasic"></i>
						<button id="btnGetCurrent" class="btn btn-default btn-xs" type="button"><?=$lng['Get current']?></button>
					</th>
				</tr>
				<tr>
					<td class="title"><?=$lng['Basic salary']?></td>
					<td class="nopad" style="xmin-width:120px"><input type="text" class="float72 sel tar bpm" name="basic_salary"></td>
					<td><span id="basic_year">0</span></td>
					<td style="width:10px !important"></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Fixed allowances']?></td>
					<td class="nopad"><input type="text" class="float72 sel tar bpm" name="fix_allow"></td>
					<td><span id="fix_year">0</span></td>
					<td><a tabindex="-1" href="#" id="btnFixAllow"><i class="fa fa-edit fa-lg"></i></a></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Year bonus']?></td>
					<td class="nopad"><input type="text" class="float72 sel tar bpm" name="year_bonus"></td>
					<td><span id="bonus_year">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Avg Var allowances']?></td>
					<td class="nopad"><input type="text" class="float72 sel tar bpm" name="avg_var_allow"></td>
					<td><span id="var_year">0</span></td>
					<td><a tabindex="-1" href="#" id="btnVarAllow"><i class="fa fa-edit fa-lg"></i></a></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Avg Overtime']?></td>
					<td class="nopad"><input type="text" class="float72 sel tar bpm" name="avg_overtime"></td>
					<td><span id="overtime_year">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['SSO']?></td>
					<td><span id="sso">0</span></td>
					<td><span id="sso_year">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['PVF']?></td>
					<td><span id="pvf">0</span></td>
					<td><span id="pvf_year">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Personal Tax deductions']?></td>
					<td class="nopad">
						<input type="text" class="float72 sel tar bpm" name="tax_deductions">
						<input type="hidden" class="bpm" name="emp_tax_deductions">
					</td>
					<td><span id="tot_deduct">0</span></td>
					<td><a tabindex="-1" href="#" id="btnTaxDeduct"><i class="fa fa-edit fa-lg"></i></a></td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Gross income full year']?></td>
					<td><span id="bpm_gross">0</span></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Total tax deductions']?></td>
					<td><span id="bpm_deductions">0</span></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Taxable income']?></td>
					<td><span id="bpm_taxable">0</span></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			</td><td style="min-width:15px">&nbsp;</td><td style="width:45%">	
			<table class="sub_table" style="width:100%">
				<tr>
					<th colspan="2" style="background:#963; border:1px solid #963"><?=$lng['Settings']?></th>
				</tr>
				<tr>
					<td class="title"><?=$lng['Modify Tax amount']?></td>
					<td class="nopad"><input type="text" class="neg_numeric sel tal bpm" name="modify_tax" value="0"></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Tax calculation method']?></td>
					<td class="nopad">
						<select class="bpm" name="calc_method">
							<option value="acm"><?=$lng['ACM']?></option>
							<option value="cam"><?=$lng['CAM']?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Calculate SSO']?></td>
					<td class="nopad">
						<select class="bpm" name="calc_sso">
							<option value="Y"><?=$lng['Yes']?></option>
							<option value="N"><?=$lng['No']?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Calculate PVF']?></td>
					<td class="nopad">
						<select class="bpm" name="calc_pvf">
							<option value="Y"><?=$lng['Yes']?></option>
							<option value="N"><?=$lng['No']?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Calculate Tax']?></td>
					<td class="nopad">
						<select class="bpm" name="calc_tax">
							<option value="Y"><?=$lng['Yes']?></option>
							<option value="N"><?=$lng['No']?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="title"><?=$lng['PVF rate employee']?></td>
					<td class="nopad"><input type="text" class="numeric2 sel tal bpm" name="pvf_rate_employee" id="pvf_rate_employee" value="0"></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['PVF rate employer']?></td>
					<td class="nopad"><input type="text" class="numeric2 sel tal bpm" name="pvf_rate_employer" value="0"></td>
				</tr>
				<tr>
					<td class="title">&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td class="title">&nbsp;</td>
					<td></td>
				</tr>
				<tr>
					<td class="title">SSO Employer</td>
					<td><span id="sso_employer">0</span></td>
				</tr>
				<tr>
					<td class="title">PVF employer</td>
					<td><span id="pvf_employer">0</span></td>
				</tr>
				<tr>
					<td class="title">Total cost employer</td>
					<td><span id="tot_employer">0</span></td>
				</tr>
			</table>
			</td></tr></table>
			</form>

		</td></tr><tr><td>
			<form id="grossForm">
			<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
			<input type="hidden" name="en_name" value="<?=$data['en_name']?>">
			<input type="hidden" name="th_name" value="<?=$data['th_name']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:purple; border:1px solid purple">
						<?=$lng['Calculator Gross Income']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinGross"></i> 
						<button id="btnGrossPerMonth" class="btn btn-default btn-xs" type="button"><?=$lng['Per month']?></button>
						<a target="_blank" href="<?=ROOT?>employees/tax/print_calculate_gross.php?id=<?=$_GET['id']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
					</th>
				</tr>
				<tr>
					<td class="title"><?=$lng['Gross income full year']?></td>
					<td class="nopad"><input type="text" class="float92 sel tar" name="gross_income_year" value="0"></td>
					<td></td>
					<td class="title tar"><?=$lng['Gross / month']?></td>
					<td><span id="gincome_m">0</span></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Personal Tax deductions']?></td>
					<td class="nopad"><input type="text" class="float92 sel tar" name="pers_tax_deduct_gross" value="0"></td>
					<td></td>
					<td class="title tar"><?=$lng['Tax / month']?></td>
					<td><span id="gtax_m">0</span></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Taxable income']?></td>
					<td><span id="gincome">0</span></td>
					<td></td>
					<td class="title tar"><?=$lng['Net / month']?></td>
					<td><span id="gnet_m">0</span></td>
				</tr>
				<tr>
					<td class="title fatborder"><?=$lng['Netto income per year']?></td>
					<td class="fatborder"><span id="gnet">0</span></td>
					<td class="fatborder"></td>
					<td class="fatborder"></td>
					<td class="fatborder"></td>
				</tr>
				<tr>
					<td><?=$lng['From 0 to 150,000']?></td>
					<td><span id="gtax0">0</span></td>
					<td class="tar" id="gper0">0 %</td>
					<td><span id="gtax_0">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 150,001 to 300,000']?></td>
					<td><span id="gtax5">0</span></td>
					<td class="tar" id="gper5">0 %</td>
					<td><span id="gtax_5">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 300,001 to 500,000']?></td>
					<td><span id="gtax10">0</span></td>
					<td class="tar" id="gper10">0 %</td>
					<td><span id="gtax_10">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 500,001 to 750,000']?></td>
					<td><span id="gtax15">0</span></td>
					<td class="tar" id="gper15">0 %</td>
					<td><span id="gtax_15">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 750,001 to 1,000,000']?></td>
					<td><span id="gtax20">0</span></td>
					<td class="tar" id="gper20">0 %</td>
					<td><span id="gtax_20">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 1000001 to 2000000']?></td>
					<td><span id="gtax25">0</span></td>
					<td class="tar" id="gper25">0 %</td>
					<td><span id="gtax_25">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 2,000,001 to 5,000,000']?></td>
					<td><span id="gtax30">0</span></td>
					<td class="tar" id="gper30">0 %</td>
					<td><span id="gtax_30">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['Over 5,000,000']?></td>
					<td><span id="gtax35">0</span></td>
					<td class="tar" id="gper35">0 %</td>
					<td><span id="gtax_35">0</span></td>
					<td></td>
				</tr>
				<tr style="background:#eee">
					<td class="title" style="text-align:right"><?=$lng['Total per year']?> :</td>
					<td><span id="gtaxable">0</span></td>
					<td></td>
					<td><span id="gtax_tot">0</span></td>
					<td><span id="gpercent">0.00 %</span></td>
				</tr>
			</table>
			</form>
		</td><td>
			<form id="netForm">
			<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
			<input type="hidden" name="en_name" value="<?=$data['en_name']?>">
			<input type="hidden" name="th_name" value="<?=$data['th_name']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:#c20; border:1px solid #c20">
						<?=$lng['Calculator Net Income']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinNet"></i> 
						<button id="btnNetPerMonth" class="btn btn-default btn-xs" type="button"><?=$lng['Per month']?></button>
						<a target="_blank" href="<?=ROOT?>employees/tax/print_calculate_net.php?id=<?=$_GET['id']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
					</th>
				</tr>
				<tr>
					<td class="title"><?=$lng['Gross income full year']?></td>
					<td><span id="nincome_gross">0</span></td>
					<td></td>
					<td class="title tar"><?=$lng['Gross / month']?></td>
					<td><span id="nincome_m">0</span></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Personal Tax deductions']?></td>
					<td class="nopad"><input type="text" class="float92 sel tar" name="pers_tax_deduct_net" value="0"></td>
					<td></td>
					<td class="title tar"><?=$lng['Tax / month']?></td>
					<td><span id="ntax_m"></span></td>
				</tr>
				<tr>
					<td class="title"><?=$lng['Taxable income']?></td>
					<td><span id="ntaxable">0</span></td>
					<td></td>
					<td class="title tar"><?=$lng['Net / month']?></td>
					<td><span id="nnet_m"></span></td>
				</tr>
				<tr>
					<td class="title fatborder"><?=$lng['Netto income per year']?></td>
					<td class="fatborder nopad"><input type="text" class="float92 sel tar" name="net_income_year" value="0"></td>
					<td class="fatborder"></td>
					<td class="fatborder"></td>
					<td class="fatborder"></td>
				</tr>
				<tr>
					<td><?=$lng['From 0 to 150,000']?></td>
					<td><span id="ntax0">0</span></td>
					<td class="tar" id="nper0">0 %</td>
					<td><span id="ntax_0">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 150,001 to 300,000']?></td>
					<td><span id="ntax5">0</span></td>
					<td class="tar" id="nper5">0 %</td>
					<td><span id="ntax_5">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 300,001 to 500,000']?></td>
					<td><span id="ntax10">0</span></td>
					<td class="tar" id="nper10">0 %</td>
					<td><span id="ntax_10">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 500,001 to 750,000']?></td>
					<td><span id="ntax15">0</span></td>
					<td class="tar" id="nper15">0 %</td>
					<td><span id="ntax_15">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 750,001 to 1,000,000']?></td>
					<td><span id="ntax20">0</span></td>
					<td class="tar" id="nper20">0 %</td>
					<td><span id="ntax_20">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 1000001 to 2000000']?></td>
					<td><span id="ntax25">0</span></td>
					<td class="tar" id="nper25">0 %</td>
					<td><span id="ntax_25">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['From 2,000,001 to 5,000,000']?></td>
					<td><span id="ntax30">0</span></td>
					<td class="tar" id="nper30">0 %</td>
					<td><span id="ntax_30">0</span></td>
					<td></td>
				</tr>
				<tr>
					<td><?=$lng['Over 5,000,000']?></td>
					<td><span id="ntax35">0</span></td>
					<td class="tar" id="nper35">0 %</td>
					<td class="tar"><span id="ntax_35">0</span></td>
					<td></td>
				</tr>
				<tr style="background:#eee">
					<td class="title" style="text-align:right"><?=$lng['Total per year']?> :</td>
					<td><span id="nyear">0</span></td>
					<td></td>
					<td><span id="ntax_tot">0</span></td>
					<td><span id="npercent">0.00 %</span></td>
				</tr>
			</table>
			</form>	
		</td></tr></table>
		
		</div>
	</div>
	
<style>
	table.modalTable td.nopad {
		padding:0;
	}
	table.modalTable td span {
		text-align:right;
		display:block;
		font-weight:600;
		color:#900;
	}
	table.modalTable td input {
		border:0;
		margin:0;
		padding:2px 8px;
		background:#ffd;
		width:100%;
		text-align:right;
	}
	table.modalTable td input:hover {
		border:0;
	}
	table.modalTable.modTable tbody th, 
	table.modalTable.modTable tfoot th {
		white-space:nowrap;
		border-right:1px solid #ddd;
		font-weight:600;
	}
	table.modalTable.modTable tbody td, 
	table.modalTable.modTable tfoot td {
		padding:0;
	}
	table.modalTable.modTable thead th {
		padding:6px 8px !important;
		font-size:13px;
	}
	table.modalTable.modTable tfoot th, 
	table.modalTable.modTable tfoot td {
		font-size:13px;
		background:#eee !important;
		color:#900;
	}
	table.modalTable.modTable tbody td input[type="text"], 
	table.modalTable.modTable tfoot td input[type="text"] {
		text-align:left !important;
		background:transparent;
	}
</style>

	<!-- Modal Calculate Net -->
	<div class="modal fade" id="modalNetPerMonth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:800px">
			  <div class="modal-content">
					<div class="modal-header" style="background:#c20; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel">GROSS calculation from NET&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px; xheight:300px">
						
						<form id="nCalulation" method="post">
						<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
						<table class="modalTable" border="0" style="table-layout:fixed;">
							<tbody>
								<tr>
									<td style="font-weight:600"><?=$lng['Average']?></td>
									<td class="nopad"><input type="text" class="float92 sel" name="avg_nfix" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel" name="avg_nvar" value="0"></td>
									<td colspan="4" style="font-weight:600; text-align:right">Employee Tax deductions</td>
									<td class="nopad"><input type="text" class="float92 sel" name="ndeduct" value="0"></td>
								</tr>
							</tbody>
						</table>
						<table class="modalTable" border="0" style="table-layout:fixed; margin-bottom:10px">
							<thead>
								<tr>
									<th><?=$lng['Month']?></th>
									<th class="tac"><?=$lng['Fixed NET']?></th>
									<th class="tac"><?=$lng['Variable NET']?></th>
									<th class="tac"><?=$lng['Total NET']?></th>
									<th class="tac"><?=$lng['Gross income']?></th>
									<th class="tac"><?=$lng['SSO']?></th>
									<th class="tac"><?=$lng['PVF']?></th>
									<th class="tac"><?=$lng['Tax']?></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($months as $k=>$v){ ?>
								<tr>
									<td><?=$v?></td>
									<td class="nopad"><input type="text" class="float92 sel xfix" name="xfix[<?=$k?>]" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel xvar" name="xvar[<?=$k?>]" value="0"></td>
									<td><span class="xcal" id="xtot<?=$k?>">0</span></td>
									<td><span class="xcal" id="xgross<?=$k?>">0</span></td>
									<td><span class="xcal" id="xsso<?=$k?>">0</span></td>
									<td><span class="xcal" id="xpvf<?=$k?>">0</span></td>
									<td><span class="xcal" id="xtax<?=$k?>">0</span></td>
								</tr>
							</tbody>
								<? } ?>
							<tfoot>
								<tr>
									<td class="tac"><?=$lng['Totals']?></td>
									<td class="xcal" id="tot_xfix">0</td>
									<td class="xcal" id="tot_xvar">0</td>
									<td class="xcal" id="tot_xtot">0</td>
									<td class="xcal" id="tot_xgross">0</td>
									<td class="xcal" id="tot_xsso">0</td>
									<td class="xcal" id="tot_xpvf">0</td>
									<td class="xcal" id="tot_xtax">0</td>
								</tr>
							</tfoot>
						</table>
						<button id="nfillHistory" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['Input historical']?></button>
						<button id="nfillFromlast" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['From last month']?></button>
						<button id="nfillAvg" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['Avg filled in']?></button>
						<button style="margin-left:5px; float:right" data-dismiss="modal" class="btn btn-primary btn-xs" type="button"><?=$lng['Cancel']?></button>
						<button style="margin-left:5px; float:right" class="btn btn-primary btn-xs" type="submit"><?=$lng['Calculate']?></button>
						<button id="nClear" style="margin-left:5px; float:right" class="btn btn-primary btn-xs" type="button"><?=$lng['Clear']?></button>
						</form>
						
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal Calculate Gross -->
	<div class="modal fade" id="modalGrossPerMonth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:900px">
			  <div class="modal-content">
					<div class="modal-header" style="background:purple; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel">NET calculation from GROSS&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px; xheight:300px">
						
						<form id="gCalulation" method="post">
						<input type="hidden" name="emp_id" value="<?=$_GET['id']?>">
						<table class="modalTable" border="0" style="table-layout:fixed;">
							<tbody>
								<tr>
									<td style="font-weight:600"><?=$lng['Average']?></td>
									<td class="nopad"><input type="text" class="float92 sel" name="avg_sal" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel" name="avg_fix" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel" name="avg_var" value="0"></td>
									<td colspan="4" style="font-weight:600; text-align:right">Employee Tax deductions</td>
									<td class="nopad"><input type="text" class="float92 sel" name="deduct" value="0"></td>
								</tr>
							</tbody>
						</table>
						<table class="modalTable" border="0" style="table-layout:fixed; margin-bottom:10px">
							<thead>
								<tr>
									<th><?=$lng['Month']?></th>
									<th class="tac"><?=$lng['Salary']?></th>
									<th class="tac"><?=$lng['Fix allow']?></th>
									<th class="tac"><?=$lng['Var income']?></th>
									<th class="tac"><?=$lng['Gross income']?></th>
									<th class="tac"><?=$lng['SSO']?></th>
									<th class="tac"><?=$lng['PVF']?></th>
									<th class="tac"><?=$lng['Tax']?></th>
									<th class="tac"><?=$lng['Net income']?></th>
								</tr>
							</thead>
							<tbody>
								<? foreach($months as $k=>$v){ ?>
								<tr>
									<td><?=$v?></td>
									<td class="nopad"><input type="text" class="float92 sel ysal" name="sal[<?=$k?>]" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel yfix" name="fix[<?=$k?>]" value="0"></td>
									<td class="nopad"><input type="text" class="float92 sel yvar" name="var[<?=$k?>]" value="0"></td>
									<td><span class="ycal" id="ygross<?=$k?>">0</span></td>
									<td><span class="ycal" id="ysso<?=$k?>">0</span></td>
									<td><span class="ycal" id="ypvf<?=$k?>">0</span></td>
									<td><span class="ycal" id="ytax<?=$k?>">0</span></td>
									<td><span class="ycal" id="ynet<?=$k?>">0</span></td>
								</tr>
							</tbody>
								<? } ?>
							<tfoot>
								<tr>
									<td class="tac"><?=$lng['Totals']?></td>
									<td class="ycal" id="tot_ysal">0</td>
									<td class="ycal" id="tot_yfix">0</td>
									<td class="ycal" id="tot_yvar">0</td>
									<td class="ycal" id="tot_ygross">0</td>
									<td class="ycal" id="tot_ysso">0</td>
									<td class="ycal" id="tot_ypvf">0</td>
									<td class="ycal" id="tot_ytax">0</td>
									<td class="ycal" id="tot_ynet">0</td>
								</tr>
							</tfoot>
						</table>
						<button id="fillHistory" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['Input historical']?></button>
						<button id="fillFromlast" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['From last month']?></button>
						<button id="fillAvg" style="margin-right:2px" class="btn btn-primary btn-xs" type="button"><?=$lng['Avg filled in']?></button>
						<button style="margin-left:5px; float:right" data-dismiss="modal" class="btn btn-primary btn-xs" type="button"><?=$lng['Cancel']?></button>
						<button style="margin-left:5px; float:right" class="btn btn-primary btn-xs" type="submit"><?=$lng['Calculate']?></button>
						<button id="yClear" style="margin-left:5px; float:right" class="btn btn-primary btn-xs" type="button"><?=$lng['Clear']?></button>
						</form>
						
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal Year Overview -->
	<div class="modal fade" id="modalYearOverview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:1050px">
			  <div class="modal-content">
					<div class="modal-header" style="background:green; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel">Year overview&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px;">
						<div id="reportTable"></div>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal Fix Allow -->
	<div class="modal fade" id="modalFixAllow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:500px">
			  <div class="modal-content">
					<div class="modal-header" style="background:#36c; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel">Fixed allowances&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						<table class="modalTable modTable" border="0" style="margin-bottom:10px">
							<thead>
								<tr style="line-height:100%">
									<th class="tal" style="width:70%"><?=$lng['Allowances']?></th>
									<th class="tar"><?=$lng['Amount']?></th>
								</tr>
							</thead>
							<tbody>
							<? $totFix = 0; 
								foreach($fix_allowances as $k=>$v){ $totFix += $v;?>
								<tr>
									<th><?=$k?></th>
									<td><input style="text-align:right !important" class="sel numeric8 sFix" type="text" value="<?=$v?>"></td>
								</tr>
							<? } ?>
							</tbody>
							<tfoot>
								<tr style="border-bottom:1px solid #fff">
									<th><?=$lng['Total']?></th>
									<td><input style="text-align:right !important" readonly tabindex="-1" type="text" id="tot_fix" value="<?=number_format($totFix,2)?>"></td>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" id="totFix" value="<?=$totFix?>">
						<button style="float:right" data-dismiss="modal" class="btn btn-primary btn-xs" type="button"><?=$lng['Cancel']?></button>
						<button id="applyFix" style="float:left" class="btn btn-primary btn-xs" type="button"><?=$lng['Apply']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>

	<!-- Modal Var Allow -->
	<div class="modal fade" id="modalVarAllow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:500px">
			  <div class="modal-content">
					<div class="modal-header" style="background:#36c; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel">Variable allowances&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						<table class="modalTable modTable" border="0" style="margin-bottom:10px">
							<thead>
								<tr style="line-height:100%">
									<th class="tal" style="width:70%"><?=$lng['Allowances']?></th>
									<th class="tar"><?=$lng['Amount']?></th>
								</tr>
							</thead>
							<tbody>
							<? $totVar = 0; 
								foreach($var_allowances as $k=>$v){ $totVar += $v;?>
								<tr>
									<th><?=$k?></th>
									<td><input style="text-align:right !important" class="sel numeric8 sVar" type="text" value="<?=$v?>"></td>
								</tr>
							<? } ?>
							</tbody>
							<tfoot>
								<tr>
									<th><?=$lng['Total']?></th>
									<td><input style="text-align:right !important" readonly tabindex="-1" type="text" id="tot_var" value="<?=number_format($totVar,2)?>"></td>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" id="totVar" value="<?=$totVar?>">
						<button style="float:right" data-dismiss="modal" class="btn btn-primary btn-xs" type="button"><?=$lng['Cancel']?></button>
						<button id="applyVar" style="float:left" class="btn btn-primary btn-xs" type="button"><?=$lng['Apply']?></button>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal Tax Deductions -->
	<div class="modal fade" id="modalTaxDeduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 <div class="modal-dialog" style="width:550px">
			  <div class="modal-content">
					<div class="modal-header" style="background:#36c; color:#fff">
						<div type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="fa fa-times"></i></span>
							<span class="sr-only"><?=$lng['Close']?></span>                
						</div>
						 <h4 class="modal-title" id="myModalLabel"><?=$lng['Tax deductions']?>&nbsp; <small style="color:#fff"><?=$employee?></small></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px; height:450px; overflow-Y:scroll">

						<table class="modalTable modTable" border="0" style="margin-bottom:10px">
							<thead>
								<tr style="line-height:100%">
									<th class="tal" style="width:70%"><?=$lng['Description']?></th>
									<th class="tal"><?=$lng['Deductable amount']?></th>
								</tr>
							</thead>
							<tbody>
							<tr>
								<th><?=$lng['Standard deduction']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" value="<?=$data['tax_standard_deduction']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Personal care']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" value="<?=$data['tax_personal_allowance']?>"></td>
							</tr>
							<tr>
								<th style="width:5%"><?=$lng['Spouse care']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" value="<?=$data['tax_allow_spouse']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Parents care']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" value="<?=$data['tax_allow_parents']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Parents in law care']?></th>
								<td><input class="sel numeric8" type="text" name="tax_allow_parents_inlaw" id="parents_inlaw_allowance" value="<?=$data['tax_allow_parents_inlaw']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Care disabled person']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" name="tax_allow_disabled_person" id="disabled_allowance" value="<?=$data['tax_allow_disabled_person']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Child care - biological']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" name="tax_allow_child_bio" id="child_allowance" value="<?=$data['tax_allow_child_bio']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Child care - biological 2018/19/20']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" name="tax_allow_child_bio_2018" id="child_allowance_2018" value="<?=$data['tax_allow_child_bio_2018']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Child care - adopted']?></th>
								<td><input class="sel numeric8 sdeduct" type="text" name="tax_allow_child_adopted" id="child_adopt_allowance" value="<?=$data['tax_allow_child_adopted']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Child birth (Baby bonus)']?></th>
								<td><input class="numeric sel sdeduct" type="text" name="tax_allow_child_birth" id="child_birth_bonus" value="<?=$data['tax_allow_child_birth']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Own health insurance']?></th>
								<td><input class="numeric sel sdeduct" id="own_health_insurance" type="text" name="tax_allow_own_health" value="<?=$data['tax_allow_own_health']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Own life insurance']?></th>
								<td><input class="numeric sel sdeduct" id="own_life_insurance" type="text" name="tax_allow_own_life_insurance" value="<?=$data['tax_allow_own_life_insurance']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Health insurance parents']?></th>
								<td><input class="numeric sel sdeduct" type="text" name="tax_allow_health_parents" id="health_insurance_parent" value="<?=$data['tax_allow_health_parents']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Life insurance spouse']?></th>
								<td><input class="numeric sel sdeduct" type="text" name="tax_allow_life_insurance_spouse" id="life_allow_insurance_spouse" value="<?=$data['tax_allow_life_insurance_spouse']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Pension fund']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="pension_fund_allowance" name="tax_allow_pension_fund" value="<?=$data['tax_allow_pension_fund']?>"></td>
							</tr>
							<!--<tr style="display:none">
								<th><? //=$lng['Provident fund']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="provident_fund_allowance" name="tax_allow_pvf" value="<?=$data['tax_allow_pvf']?>"></td>-->
							</tr>
							<tr>
								<th><?=$lng['NSF']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="nsf_allowance" name="tax_allow_nsf" value="<?=$data['tax_allow_nsf']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['RMF']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="rmf_allowance" name="tax_allow_rmf" value="<?=$data['tax_allow_rmf']?>"></td>
							</tr>
							<!--<tr style="display:none">
								<th><? //=$lng['Social Security Fund']?></th>
								<td><input class="numeric sel sdeduct" name="tax_allow_sso" type="text" value="<?=$data['tax_allow_sso']?>"></td>
							</tr>-->
							<tr>
								<th><?=$lng['LTF']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="ltf_deduction" name="tax_allow_ltf" value="<?=$data['tax_allow_ltf']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Home loan interest']?></th>
								<td><input class="numeric sel" type="text" id="home_loan_interest" name="tax_allow_home_loan_interest" value="<?=$data['tax_allow_home_loan_interest']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Donation charity']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="donation_charity" name="tax_allow_donation_charity" value="<?=$data['tax_allow_donation_charity']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Donation flooding']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="donation_flood" name="tax_allow_donation_flood" value="<?=$data['tax_allow_donation_flood']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Donation education']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="donation_education" name="tax_allow_donation_education" value="<?=$data['tax_allow_donation_education']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Exemption disabled person <65 yrs']?></th>
								<td><input class="numeric sel sdeduct" type="text" name="tax_allow_exemp_disabled_under" id="exemp_disabled_under" value="<?=$data['tax_allow_exemp_disabled_under']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Exemption tax payer => 65yrs']?></th>
								<td><input class="numeric sel" type="text" name="tax_allow_exemp_payer_older" id="exemp_payer_older" value="<?=$data['tax_allow_exemp_payer_older']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['First home buyer']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="first_home_allowance" name="tax_allow_first_home" value="<?=$data['tax_allow_first_home']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Year-end shopping']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="year_end_shop_allowance" value="<?=$data['tax_allow_year_end_shopping']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Domestic tour']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="domestic_tour_allowance" value="<?=$data['tax_allow_domestic_tour']?>"></td>
							</tr>
							<tr>
								<th><?=$lng['Other allowance']?></th>
								<td><input class="numeric sel sdeduct" type="text" id="other_allowance" value="<?=$data['tax_allow_other']?>"></td>
							</tr>
							</tbody>
							<tfoot>
								<tr>
									<th><?=$lng['Total']?></th>
									<td><input readonly tabindex="-1" type="text" id="tot_sdeduct" name="" value="<?=number_format($min_tax_deductions,2)?>"></td>
								</tr>
							</tfoot>
						</table>
						<input type="hidden" id="total_sdeduct" value="<?=$min_tax_deductions?>">
						<button style="float:right" data-dismiss="modal" class="btn btn-primary btn-xs" type="button"><?=$lng['Cancel']?></button>
						<button id="applyDeduct" style="float:left" class="btn btn-primary btn-xs" type="button"><?=$lng['Apply']?></button>
					
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
<script type="text/javascript">
	
	$(document).ready(function() {
		var ctaxable = <?=json_encode($ctaxable)?>;
		var cgross = <?=json_encode($cgross)?>;
		var cdeduct = <?=json_encode($cdeduct)?>;
		var emp_id = <?=json_encode($_GET['id'])?>;
		//if(emp_id == 0){return false;}
		
		function calculateCurrent(ctaxable, cgross){
			$('.spinCurrent').show();
			$.ajax({
				url: ROOT+"employees/tax/get_tax_calculate_current.php",
				type: "POST",
				data: {ctaxable: ctaxable, cgross: cgross, cdeduct: cdeduct, emp_id: emp_id},
				dataType: 'json', 
				success: function(data){
					//$("#dump").html(data); return false;
					$('#ctax0').html((data['gross'][0]).format(2));
					$('#ctax5').html((data['gross'][1]).format(2));
					$('#ctax10').html((data['gross'][2]).format(2));
					$('#ctax15').html((data['gross'][3]).format(2));
					$('#ctax20').html((data['gross'][4]).format(2));
					$('#ctax25').html((data['gross'][5]).format(2));
					$('#ctax30').html((data['gross'][6]).format(2));
					$('#ctax35').html((data['gross'][7]).format(2));
					
					$('#cper0').html((data['percent'][0])+' %');
					$('#cper5').html((data['percent'][1])+' %');
					$('#cper10').html((data['percent'][2])+' %');
					$('#cper15').html((data['percent'][3])+' %');
					$('#cper20').html((data['percent'][4])+' %');
					$('#cper25').html((data['percent'][5])+' %');
					$('#cper30').html((data['percent'][6])+' %');
					$('#cper35').html((data['percent'][7])+' %');

					$('#ctax_0').html((data['tax'][0]).format(2));
					$('#ctax_5').html((data['tax'][1]).format(2));
					$('#ctax_10').html((data['tax'][2]).format(2));
					$('#ctax_15').html((data['tax'][3]).format(2));
					$('#ctax_20').html((data['tax'][4]).format(2));
					$('#ctax_25').html((data['tax'][5]).format(2));
					$('#ctax_30').html((data['tax'][6]).format(2));
					$('#ctax_35').html((data['tax'][7]).format(2));

					$('#ctot_year').html((data.tot_gross).format(2));
					$('#ctot_tax').html((data.tax_year).format(2));
					$('#cpercent').html((data.percent_tax).format(2)+' %');
					
					setTimeout(function(){$('.spinCurrent').fadeOut(200);},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		}
		function calculateBPM(){	
			$('.spinBasic').show();
			var basic_salary = parseFloat($('input[name="basic_salary"]').val());
			//alert(basic_salary)
			var basic_year = basic_salary * 12;
			var fix_allow = parseFloat($('input[name="fix_allow"]').val());
			var fix_year = fix_allow * 12;
			var year_bonus = parseFloat($('input[name="year_bonus"]').val());
			var avg_var_allow = parseFloat($('input[name="avg_var_allow"]').val());
			var var_year = avg_var_allow * 12;
			var avg_overtime = parseFloat($('input[name="avg_overtime"]').val());
			var overtime_year = avg_overtime * 12;
			var pvf_rate_empe = parseFloat($('input[name="pvf_rate_empe"]').val());
			var pvf_rate_empr = parseFloat($('input[name="pvf_rate_empr"]').val());
			var tax_deductions = parseFloat($('input[name="tax_deductions"]').val());
			
			var gross = ((basic_salary + fix_allow + avg_var_allow + avg_overtime)*12) + year_bonus;
			var sso = 0;
			var tot_sso = 0;
			if($('select[name="calc_sso"]').val() == 'Y'){
				sso = basic_salary * .05;
				if(sso>750){sso = 750;}
				tot_sso = sso * 12;
			}
			//tax_deductions += tot_sso;
			
			var pvf = 0;
			var tot_pvf = 0;
			var pvf_employer = 0;
			if($('select[name="calc_pvf"]').val() == 'Y'){
				var pvf = basic_salary * ($('input[name="pvf_rate_employee"]').val()/100);
				var tot_pvf = pvf * 12;
				var rpvf = basic_salary * ($('input[name="pvf_rate_employer"]').val()/100);
				var pvf_employer = rpvf * 12;
			}
			
			var tot_deduct = tax_deductions + tot_sso + tot_pvf;
			var taxable = gross - tot_deduct;
			var tot_employer = tot_sso + pvf_employer;
			//tax_deductions += tot_pvf;
			
			$('#basic_year').html(basic_year.format(2));
			$('#fix_year').html(fix_year.format(2));
			$('#bonus_year').html(year_bonus.format(2));
			$('#var_year').html(var_year.format(2));
			$('#overtime_year').html(overtime_year.format(2));
			$('#sso').html(sso.format(2));
			$('#sso_year').html(tot_sso.format(2));
			$('#sso_employer').html(tot_sso.format(2));
			$('#pvf').html(pvf.format(2));
			$('#pvf_year').html(tot_pvf.format(2));
			$('#pvf_employer').html(pvf_employer.format(2));
			$('#tot_deduct').html(tax_deductions.format(2));
			$('input[name="tax_deductions"]').val(tax_deductions);
			$('#tot_employer').html(tot_employer.format(2));
			
			$('#bpm_gross').html(gross.format(2));
			$('#bpm_deductions').html(tot_deduct.format(2));
			$('#bpm_taxable').html(taxable.format(2));
			
			setTimeout(function(){$('.spinBasic').fadeOut(200);},500);
		}
		function calculateGross(){	
			$('.spinGross').show();
			$.ajax({
				url: ROOT+"employees/tax/get_tax_calculate_gross.php",
				type: "POST",
				dataType: 'json',
				data:{emp_id: emp_id, pvf: $('#gtax0').html(), gross: cgross},
				success: function(data){
					//$(/"#dump").html(data);
					$('#gtax0').html((data['gross'][0]).format(2));
					$('#gtax5').html((data['gross'][1]).format(2));
					$('#gtax10').html((data['gross'][2]).format(2));
					$('#gtax15').html((data['gross'][3]).format(2));
					$('#gtax20').html((data['gross'][4]).format(2));
					$('#gtax25').html((data['gross'][5]).format(2));
					$('#gtax30').html((data['gross'][6]).format(2));
					$('#gtax35').html((data['gross'][7]).format(2));
					
					$('#gper0').html((data['percent'][0])+' %');
					$('#gper5').html((data['percent'][1])+' %');
					$('#gper10').html((data['percent'][2])+' %');
					$('#gper15').html((data['percent'][3])+' %');
					$('#gper20').html((data['percent'][4])+' %');
					$('#gper25').html((data['percent'][5])+' %');
					$('#gper30').html((data['percent'][6])+' %');
					$('#gper35').html((data['percent'][7])+' %');
					
					$('#gtax_0').html((data['tax'][0]).format(2));
					$('#gtax_5').html((data['tax'][1]).format(2));
					$('#gtax_10').html((data['tax'][2]).format(2));
					$('#gtax_15').html((data['tax'][3]).format(2));
					$('#gtax_20').html((data['tax'][4]).format(2));
					$('#gtax_25').html((data['tax'][5]).format(2));
					$('#gtax_30').html((data['tax'][6]).format(2));
					$('#gtax_35').html((data['tax'][7]).format(2));

					$('#gtaxable').html((data.tot_gross).format(2));
					$('#gtax_tot').html((data.tax_year).format(2));
					$('#gpercent').html((data.percent_tax).format(2)+' %');
					
					$('#gincome').html((data.taxable).format(2));
					$('#gnet').html((data.net_year).format(2));
					
					$('#gincome_m').html((data.gross_month).format(2));
					$('#gtax_m').html((data.tax_month).format(2));
					$('#gnet_m').html((data.net_month).format(2));

					setTimeout(function(){$('.spinGross').fadeOut(200);},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		}
		function calculateNet(){
			$('.spinNet').show();
			$.ajax({
				url: ROOT+"employees/tax/get_tax_calculate_net.php",
				type: "POST",
				dataType: 'json',
				data:{emp_id: emp_id}, 
				success: function(data){
					//$("#dump").html(data); return false;
					$('#ntax0').html((data['gross'][0]).format(2));
					$('#ntax5').html((data['gross'][1]).format(2));
					$('#ntax10').html((data['gross'][2]).format(2));
					$('#ntax15').html((data['gross'][3]).format(2));
					$('#ntax20').html((data['gross'][4]).format(2));
					$('#ntax25').html((data['gross'][5]).format(2));
					$('#ntax30').html((data['gross'][6]).format(2));
					$('#ntax35').html((data['gross'][7]).format(2));
					
					$('#nper0').html((data['percent'][0])+' %');
					$('#nper5').html((data['percent'][1])+' %');
					$('#nper10').html((data['percent'][2])+' %');
					$('#nper15').html((data['percent'][3])+' %');
					$('#nper20').html((data['percent'][4])+' %');
					$('#nper25').html((data['percent'][5])+' %');
					$('#nper30').html((data['percent'][6])+' %');
					$('#nper35').html((data['percent'][7])+' %');
					
					$('#ntax_0').html((data['tax'][0]).format(2));
					$('#ntax_5').html((data['tax'][1]).format(2));
					$('#ntax_10').html((data['tax'][2]).format(2));
					$('#ntax_15').html((data['tax'][3]).format(2));
					$('#ntax_20').html((data['tax'][4]).format(2));
					$('#ntax_25').html((data['tax'][5]).format(2));
					$('#ntax_30').html((data['tax'][6]).format(2));
					$('#ntax_35').html((data['tax'][7]).format(2));
					
					$('#ntaxable').html((data.net_year).format(2));
					$('#nyear').html((data.net_year).format(2));
					$('#ntax_tot').html((data.tax_year).format(2));
					$('#npercent').html((data.percent_tax).format(2)+' %');
					$('#nincome_gross').html((data.tot_gross).format(2));
					
					$('#nincome_m').html((data.gross_month).format(2));
					$('#ntax_m').html((data.tax_month).format(2));
					$('#nnet_m').html((data.net_month).format(2));
					
					setTimeout(function(){$('.spinNet').fadeOut(200);},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		}
		function getCurrentData(emp_id){
			$.ajax({
				url: ROOT+"employees/tax/get_current_data.php",
				type: "POST",
				data: {emp_id: emp_id},
				dataType: 'json', 
				success: function(data){
					//$("#dump").html(data); return false;
					
					$('input[name="basic_salary"]').val(data.basic_salary);
					$('input[name="fix_allow"]').val(data.fix_allow);
					$('input[name="year_bonus"]').val(data.year_bonus);
					$('input[name="avg_var_allow"]').val(data.avg_var_allow);
					$('input[name="avg_overtime"]').val(data.avg_overtime);
					//alert(data.tax_deductions)
					$('input[name="tax_deductions"]').val(data.min_deductions);
					
					$('input[name="modify_tax"]').val(data.modify_tax);
					$('select[name="calc_method"]').val(data.calc_method);
					$('select[name="calc_sso"]').val(data.calc_sso);
					$('select[name="calc_pvf"]').val(data.calc_pvf);
					$('select[name="calc_tax"]').val(data.calc_tax);
					$('input[name="pvf_rate_employee"]').val(data.pvf_rate_employee);
					$('input[name="pvf_rate_employer"]').val(data.pvf_rate_employer);
					
					calculateBPM();
					setTimeout(function(){$('#basicForm').submit();},300);
					
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		}
		function getTaxSimulationData(){
			$.ajax({
				url: ROOT+"employees/tax/get_tax_simulation.php",
				type: "POST",
				data:{emp_id: emp_id},
				dataType: 'json', 
				success: function(data){
					//$("#dump").html(data);
					if(data == 'empty'){getCurrentData(emp_id)}
					//alert()
					$('input[name="basic_salary"]').val(data.basic_salary);
					$('input[name="fix_allow"]').val(data.fix_allow);
					$('input[name="year_bonus"]').val(data.year_bonus);
					$('input[name="avg_var_allow"]').val(data.avg_var_allow);
					$('input[name="avg_overtime"]').val(data.avg_overtime);
					$('input[name="pvf_rate_employee"]').val(data.pvf_rate_employee);
					$('input[name="pvf_rate_employer"]').val(data.pvf_rate_employer);
					$('input[name="tax_deductions"]').val(data.tax_deductions);
					//$('input[name="emp_tax_deductions"]').val(data.emp_tax_deductions);
					$('input[name="modify_tax"]').val(data.modify_tax);
					
					$('select[name="calc_method"]').val(data.calc_method);
					$('select[name="calc_sso"]').val(data.calc_sso);
					$('select[name="calc_pvf"]').val(data.calc_pvf);
					$('select[name="calc_tax"]').val(data.calc_tax);
					
					$('input[name="gross_income_year"]').val(data.gross_income_year);
					$('input[name="pers_tax_deduct_gross"]').val(data.pers_tax_deduct_gross);
					$('input[name="pers_tax_deduct_net"]').val(data.pers_tax_deduct_net);
					$('input[name="net_income_year"]').val(data.net_income_year);
					
					calculateBPM();
					setTimeout(function(){calculateCurrent(ctaxable, cgross);},100);
					setTimeout(function(){calculateGross();},200);
					setTimeout(function(){calculateNet();},300);
					
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		}
		getTaxSimulationData();
		

		$('#gCalulation').on('submit', function(e){
			e.preventDefault();
			//alert(); return false;
			var formData = $(this).serialize();
			//formData += '&pvf='+$('#pvf_rate_employee').val()
			$.ajax({
				url: ROOT+"employees/tax/gross_calculation.php",
				type: "POST",
				dataType: 'json', 
				data: formData,
				success: function(data){
					//$("#dump").html(data); return false
					//alert()
					$.each(data, function(k,v){
						if(k == 13){return false}
						$('#ygross'+k).html((v.gross).format(2))
						$('#ysso'+k).html((v.sso).format(2))
						$('#ypvf'+k).html((v.pvf).format(2))
						$('#ytax'+k).html((v.tax).format(2))
						$('#ynet'+k).html((v.net).format(2))
					})
					
					$('#tot_ysal').html((data[13].sal).format(2))
					$('#tot_yfix').html((data[13].fix).format(2))
					$('#tot_yvar').html((data[13].var).format(2))
					$('#tot_ygross').html((data[13].gross).format(2))
					$('#tot_ysso').html((data[13].sso).format(2))
					$('#tot_ypvf').html((data[13].pvf).format(2))
					$('#tot_ytax').html((data[13].tax).format(2))
					$('#tot_ynet').html((data[13].net).format(2))
					return false
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		
		})
		
		$('#nCalulation').on('submit', function(e){
			e.preventDefault();
			//alert('There are only 24 hours in one day.'); return false;
			var formData = $(this).serialize();
			formData += '&pvf='+$('#pvf_rate_employee').val()
			$.ajax({
				url: ROOT+"employees/tax/net_calculation.php",
				type: "POST",
				dataType: 'json', 
				data: formData,
				success: function(data){
					//$("#dump").html(data); return false
					//alert()
					$.each(data, function(k,v){
						if(k == 13){return false}
						$('#xtot'+k).html((v.xtot).format(2))
						$('#xgross'+k).html((v.xgross).format(2))
						$('#xsso'+k).html((v.xsso).format(2))
						$('#xpvf'+k).html((v.xpvf).format(2))
						$('#xtax'+k).html((v.xtax).format(2))
					})
					//return false
					$('#tot_xfix').html((data[13].xfix).format(2))
					$('#tot_xvar').html((data[13].xvar).format(2))
					$('#tot_xtot').html((data[13].xtot).format(2))
					$('#tot_xgross').html((data[13].xgross).format(2))
					$('#tot_xsso').html((data[13].xsso).format(2))
					$('#tot_xpvf').html((data[13].xpvf).format(2))
					$('#tot_xtax').html((data[13].xtax).format(2))
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		
		})
		
		$('#basicForm').on('submit', function(e){
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT+"employees/tax/update_tax_simulation.php",
				type: "POST", 
				data: formData,
				success: function(result){
					//$("#dump").html(result);
					calculateBPM();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		});
		
		setTimeout(function(){
			$('input[name="gross_income_year"], input[name="pers_tax_deduct_gross"]').on('change', function(){
				$('.spinGross').show();
				var formData = $('#grossForm').serialize();
				$.ajax({
					url: ROOT+"employees/tax/update_gross.php",
					type: "POST", 
					data: formData,
					success: function(result){
						//$("#dump").html(result);
						calculateGross();
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError)
					}
				});
			})
			$('input[name="net_income_year"], input[name="pers_tax_deduct_net"]').on('change', function(){
				$('.spinNet').show();
				var formData = $('#netForm').serialize();
				$.ajax({
					url: ROOT+"employees/tax/update_net.php",
					type: "POST", 
					data: formData,
					success: function(result){
						//$("#dump").html(result);
						calculateNet();
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError)
					}
				});
			})
			$('.bpm').on('change', function(){
				$('.spinBasic').show();
				//$('input[name="emp_tax_deductions"]').val($('input[name="tax_deductions"]').val());
				//alert($('input[name="tax_deductions"]').val())
				$('#basicForm').submit();
			})
			$('.sdeduct').on('change', function(){
				var tot = 0;
				$('.sdeduct').each(function(){
					tot += parseFloat(this.value);
				})
				$('#total_sdeduct').val(tot)
				$('#tot_sdeduct').val(tot.format(2))
			})
			$('.sFix').on('change', function(){
				var tot = 0;
				$('.sFix').each(function(){
					tot += parseFloat(this.value);
				})
				$('#totFix').val(tot)
				$('#tot_fix').val(tot.format(2))
			})
			$('.sVar').on('change', function(){
				var tot = 0;
				$('.sVar').each(function(){
					tot += parseFloat(this.value);
				})
				$('#totVar').val(tot)
				$('#tot_var').val(tot.format(2))
			})
		},2000);
		
		$('#btnYearOverview').on('click', function(){
			$.ajax({
				url: ROOT+"payroll/ajax/get_annual_overview.php",
				cache: false,
				data: {id: emp_id},
				success:function(result){
					//$('#dump').html(result);
					$('#reportTable').html(result);
					$('#modalYearOverview').modal('toggle');
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		})
		
		$('#btnGetCurrent').on('click', function(){
			getCurrentData(emp_id)
		})
		
		$('#btnFixAllow').on('click', function(){
			$('#modalFixAllow').modal('toggle')
		})
		$('#btnVarAllow').on('click', function(){
			$('#modalVarAllow').modal('toggle')
		})
		$('#btnTaxDeduct').on('click', function(){
			$('#modalTaxDeduct').modal('toggle')
		})
		$('#applyDeduct').on('click', function(){
			$('input[name="tax_deductions"]').val($('#total_sdeduct').val());
			$('#basicForm').submit();
			//calculateBPM();
			$('#modalTaxDeduct').modal('toggle')
		})
					$('#applyFix').on('click', function(){
						$('input[name="fix_allow"]').val(parseFloat($('#totFix').val()));
						$('#basicForm').submit();
						//calculateBPM();
						$('#modalFixAllow').modal('toggle')
					})
					$('#applyVar').on('click', function(){
						$('input[name="avg_var_allow"]').val(parseFloat($('#totVar').val()));
						$('#basicForm').submit();
						//calculateBPM();
						$('#modalVarAllow').modal('toggle')
					})
		
		$('#btnGrossPerMonth').on('click', function(){
			//$('#modalGrossPerMonth .modal-header').css('background', 'purple')
			$('#modalGrossPerMonth').modal('toggle')
		})
		$('#btnNetPerMonth').on('click', function(){
			//$('#modalGrossPerMonth .modal-header').css('background', '#c20')
			$('#modalNetPerMonth').modal('toggle')
		})
		
		$('input').on('blur', function(){
			if(this.value == ''){$(this).val(0)}
		})
		
		$('#fillHistory').on('click', function(){
			$.ajax({
				url: ROOT+"employees/tax/get_payroll_history_gross.php",
				type: "POST",
				data: {id: emp_id},
				dataType: 'json', 
				success: function(data){
					//$("#dump").html(data); return false;
					$.each(data, function(k, val){
						//alert(val.salary)
						$('input[name="sal['+k+']"]').val(val.salary)
						$('input[name="fix['+k+']"]').val(val.fix_allow)
						$('input[name="var['+k+']"]').val(val.var_income)
					})
					
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		})
		
		$('#nfillHistory').on('click', function(){
			//return false;
			$.ajax({
				url: ROOT+"employees/tax/get_payroll_history_net.php",
				type: "POST",
				data: {id: emp_id},
				dataType: 'json', 
				success: function(data){
					//$("#dump").html(data); return false;
					$.each(data, function(k, val){
						//alert(val.salary)
						//$('input[name="xfix['+k+']"]').val(val.fix_allow)
						$('input[name="xvar['+k+']"]').val(val.net_income)
					})
					
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError)
				}
			});
		})
		
		$('#yClear').on('click', function(){
			$('#gCalulation').trigger('reset');
			$('.ycal').html('0');
		})
		
		$('#nClear').on('click', function(){
			$('#nCalulation').trigger('reset');
			$('.xcal').html('0');
		})
		
		$('#fillAvg').on('click', function(){
			$('.ysal').val($('input[name="avg_sal"]').val());
			$('.yfix').val($('input[name="avg_fix"]').val());
			$('.yvar').val($('input[name="avg_var"]').val());
		})
		
		$('#nfillAvg').on('click', function(){
			$('.xfix').val($('input[name="avg_nfix"]').val());
			$('.xvar').val($('input[name="avg_nvar"]').val());
		})
		
		$('#fillFromlast').on('click', function(){
			var nr = 1;
			var sal = 0;
			var fix = 0;
			var inc = 0;
			$('.ysal').each(function(){
				if(this.value == 0){
					return false
				}else{
					sal = this.value; 
					fix = $('input[name="fix['+nr+']"]').val();
					inc = $('input[name="var['+nr+']"]').val();
					nr++;
				}
			})
			for(var i=nr;i<=12;i++){
				$('input[name="sal['+i+']"]').val(sal)
				$('input[name="fix['+i+']"]').val(fix)
				$('input[name="var['+i+']"]').val(inc)
			}
		})
		
		$('#nfillFromlast').on('click', function(){
			var nr = 1;
			var fix = 0;
			var vari = 0;
			$('.xvar').each(function(){
				if(this.value == 0){
					return false
				}else{
					//var = this.value; 
					vari = $('input[name="xvar['+nr+']"]').val();
					nr++;
				}
			})
			for(var i=nr;i<=12;i++){
				$('input[name="xfix['+i+']"]').val(fix)
				$('input[name="xvar['+i+']"]').val(vari)
			}
		})
		
		
		
	})

</script>























