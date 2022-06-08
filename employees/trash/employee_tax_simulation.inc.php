<?php
	
	$employee = $data['emp_id'].' - '.$data[$lang.'_name'];
	$cgross = 0;
	$cdeduct = 0;
	$ctaxable = 0;
		
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions_tax.php');
	$xdata = getEmployeeTaxdata($_SESSION['rego']['empID']);
	
	//$cgross = $xdata['data'][13]['gross'];
	//$cdeduct = $xdata['taxable']['tot_deductions'];
	//$ctaxable = $cgross - $cdeduct;
	//if($ctaxable < 0){$ctaxable = 0;}
	//var_dump($data);
	//var_dump($cdeduct);
	//var_dump($xdata);
	
	//$min_tax_deductions = $data['total_tax_deductions'] + $data['tax_standard_deduction'];
	//$total_tax_deductions = $data['total_tax_deductions'] + $data['tax_standard_deduction'] + $data['tax_allow_pvf'] + $data['tax_allow_sso'];
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

?>		

<style>
	table.main_table {
		xborder:1px solid red;
		width:100%;
		xmin-height:600px;
		border-collapse: collapse;
		xborder-spacing: 10px;
		table-layout:fixed;
		xmargin-bottom:100px;
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
		xbackground: #ffd;
	}
	table.sub_table td select {
		padding:1px 8px !important;
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
	
<table class="main_table" border="0">
	<tr>
		<td style="padding:0 5px 5px 0">
			<form id="currentForm">
			<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:green; border:1px solid green">
						<?=$lng['Current Package']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinCurrent"></i> 
						<button id="btnYearOverview" class="btn btn-default btn-xs" type="button"><?=$lng['Year overview']?></button>
						<a target="_blank" xhref="<?=ROOT?>employees/tax/print_current_package.php?id=<?=$_SESSION['rego']['empID']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
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
		</td>
		<td style="padding:0 0 5px 5px">
			<form id="basicForm">
			<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
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
			</td><td style="min-width:10px">&nbsp;</td><td style="width:45%">	
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
		</td>
	</tr>
	<tr>
		<td style="padding:5px 5px 0 0">
			<form id="grossForm">
			<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
			<input type="hidden" name="en_name" value="<?=$data['en_name']?>">
			<input type="hidden" name="th_name" value="<?=$data['th_name']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:purple; border:1px solid purple">
						<?=$lng['Calculator Gross Income']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinGross"></i> 
						<button id="btnGrossPerMonth" class="btn btn-default btn-xs" type="button"><?=$lng['Per month']?></button>
						<a target="_blank" xhref="<?=ROOT?>employees/tax/print_calculate_gross.php?id=<?=$_SESSION['rego']['empID']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
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
		</td>
		<td style="padding:5px 0 0 5px">
			<form id="netForm">
			<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
			<input type="hidden" name="en_name" value="<?=$data['en_name']?>">
			<input type="hidden" name="th_name" value="<?=$data['th_name']?>">
			<table class="sub_table">
				<tr>
					<th colspan="5" style="background:#c20; border:1px solid #c20">
						<?=$lng['Calculator Net Income']?> &nbsp;&nbsp;<i style="display:none" class="fa fa-refresh fa-spin spinNet"></i> 
						<button id="btnNetPerMonth" class="btn btn-default btn-xs" type="button"><?=$lng['Per month']?></button>
						<a target="_blank" xhref="<?=ROOT?>employees/tax/print_calculate_net.php?id=<?=$_SESSION['rego']['empID']?>" class="btn btn-default btn-xs" type="button"><?=$lng['Print']?></a>
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
		</td>
	</tr>
</table>

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
	<div class="modal fade" id="modalNetPerMonth" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:900px">
			  <div class="modal-content">
					<div class="modal-header" style="background:purple; color:#fff">
						<h5 class="modal-title" id="myModalLabel">GROSS calculation from NET&nbsp; <small style="color:#fff"><?=$employee?></small></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px;">
	
	
						<form id="nCalulation" method="post">
						<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
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
	<div class="modal fade" id="modalGrossPerMonth" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:1000px">
			  <div class="modal-content">
					<div class="modal-header" style="background:purple; color:#fff">
						<h5 class="modal-title">NET calculation from GROSS&nbsp; <small style="color:#fff"><?=$employee?></small></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body" style="padding:15px 20px 20px 20px; xheight:300px">
						
						<form id="gCalulation" method="post">
						<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['empID']?>">
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

	
<script>

	$(document).ready(function() {
		
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
		
		
		
		var bonus = parseInt($("#sim_bonus").val());
		var month = $("#sim_month").val();
		$("#cbonus_"+month).html(bonus.format(2));	
		$('input[name="bonus"]').val(bonus);
		var month = 0;	
		var tot_sal = 0;
		var tot_fix = 0;
		var tot_var = 0;
		var tot_gross = 0;
		var id_exist = 0;
		var empid;
		
		$("#apply").on('click', function(e){
			var start = $('select[name="start"]').val();
			//alert(start)
			for(var i=1;i<=12;i++){
				$('input[name="salary['+i+']"]').val('0');
				$('input[name="fix_allow['+i+']"]').val('0');
				$('input[name="var_income['+i+']"]').val('0');
				$('input[name="pvf['+i+']"]').val('0');
				$('input[name="ssf['+i+']"]').val('0');
				$('input[name="deduct['+i+']"]').val('0');
				$('input[name="tax3['+i+']"]').val('0');
				$('input[name="tax['+i+']"]').val('0');
				$('input[name="basic['+i+']"]').val(salary);
				$('input[name="bonus['+i+']"]').val('0');
				$("#cgross_"+i).html('0');
				$("#cbonus_"+i).html('0');
				$("#agross_"+i).html('0');
				$("#abonus_"+i).html('0');
				$(".pvf_"+i).html('0');
				$(".ssf_"+i).html('0');
				$("#ctax_"+i).html('0');
				$("#cnet_"+i).html('0');
				$("#atax_"+i).html('0');
				$("#anet_"+i).html('0');
				$("#salary_"+i).html('0');
				$("#fix_allow_"+i).html('0');
				$("#var_income_"+i).html('0');
				
				$("#tot_csal").html('0:00');
				$("#tot_cfix").html('0:00');
				$("#tot_cvar").html('0:00');
				$("#tot_cbonus").html('0:00');
				$("#tot_cgross").html('0:00');
				$(".tot_pvf").html('0:00');
				$(".tot_ssf").html('0:00');
				$("#tot_ctax").html('0:00');
				$("#tot_cnet").html('0:00');
				
				$("#tot_asal").html('0:00');
				$("#tot_afix").html('0:00');
				$("#tot_avar").html('0:00');
				$("#tot_abonus").html('0:00');
				$("#tot_agross").html('0:00');
				$("#tot_atax").html('0:00');
				$("#tot_anet").html('0:00');
				
			}
			month = 0;	
			tot_sal = 0;
			tot_fix = 0;
			tot_var = 0;
			tot_gross = 0;
			var salary = parseInt($("#sim_salary").val());
			var fix_allow = parseInt($("#sim_fix_allow").val());
			var var_income = parseInt($("#sim_var_income").val());
			bonus = parseInt($("#sim_bonus").val());
			month = $("#sim_month").val();
			for(var i=start;i<=12;i++){
				$('input[name="salary['+i+']"]').val(salary);
				$('input[name="fix_allow['+i+']"]').val(fix_allow);
				$('input[name="var_income['+i+']"]').val(var_income);
				$('input[name="pvf['+i+']"]').val('0');
				$('input[name="ssf['+i+']"]').val('0');
				$('input[name="deduct['+i+']"]').val('0');
				$('input[name="tax3['+i+']"]').val('0');
				$('input[name="tax['+i+']"]').val('0');
				$('input[name="basic['+i+']"]').val(salary);
				$('input[name="bonus['+i+']"]').val('0');
				//$('#var_income_'+i).html(var_income);
				var gross = salary + fix_allow + var_income;
				if(month == i){gross += bonus}
				$("#cgross_"+i).html(gross.format(2));
				$("#cbonus_"+i).html(0);
				$("#basic_salary").val(salary);
				tot_sal += parseInt(salary);
				tot_fix += parseInt(fix_allow);
				tot_var += parseInt(var_income);
				tot_gross += parseInt(gross);
			}
			$("#cbonus_"+month).html(bonus);
			$('input[name="bonus_amount"]').val(bonus);
			$('input[name="prev"]').val(0);
			$("#tot_csal").html(tot_sal.format(2));
			$("#tot_cfix").html(tot_fix.format(2));
			$("#tot_cvar").html(tot_var.format(2));
			$("#tot_cbonus").html(bonus.format(2));
			$("#tot_cgross").html(tot_gross.format(2));
			//alert(month)
		})
	
		$("#simulationForm").on('submit', function(e){
			e.preventDefault();
			var bonus = parseInt($("#sim_bonus").val());
			var month = $("#sim_month").val();
			$('input[name="bonus['+month+']"]').val(bonus);
			var data = $(this).serialize();
			var pvf_rate = $("#pvf_rate_employee").val();
			
			data +='&pvfrate='+pvf_rate;
			data +='&employee_deductions='+$('#total_tax_deductions').val();
			//alert(data)
			$.ajax({
				url: ROOT+"employees/ajax/employee_tax_simulation.php",
				data: data,
				type: 'POST',
				dataType: 'json',
				success: function(result){
					//$("#dump").html(result);
					//$("#message").html(result);
					//var result = jQuery.parseJSON(result);
					//alert(result.gross[11])
					$.each(result.gross, function(i, item) {
						 $("#cgross_"+i).html(item.format(2));
						 $("#agross_"+i).html(item.format(2));
					});	
					$.each(result.pvf, function(i, item) {
						 $(".pvf_"+i).html(item.format(2));
					});					
					$.each(result.ssf, function(i, item) {
						 $(".ssf_"+i).html(item.format(2));
					});
					$.each(result.deduct, function(i, item){	
						 $(".deduct"+i).html(item.format(2));
						 //alert(item.format(2));
					});	
					$.each(result.ctax, function(i, item) {
						 $("#ctax_"+i).html(item.format(2));
					});	
					$.each(result.cnet, function(i, item) {
						 $("#cnet_"+i).html(item.format(2));
						 //alert(item.format(2));
					});	
					$.each(result.atax, function(i, item) {
						 $("#atax_"+i).html(item.format(2));
					});	
					$.each(result.anet, function(i, item) {
						 $("#anet_"+i).html(item.format(2));
						 
					});	
					
					for(var i=1;i<=12;i++){
						$('#salary_'+i).html($('input[name="salary['+i+']"]').val());
						$('#fix_allow_'+i).html($('input[name="fix_allow['+i+']"]').val());
						$('#var_income_'+i).html($('input[name="var_income['+i+']"]').val());
						//$('#agross_'+i).html($("#cgross_"+i).html());
					}
					//alert(result.tot_sal)
					$("#abonus_"+month).html(bonus.format(2));
					$("#tot_csal").html(result.tot_sal.format(2));
					$("#tot_asal").html(result.tot_sal.format(2));
					$("#tot_cfix").html(result.tot_fix.format(2));
					$("#tot_afix").html(result.tot_fix.format(2));
					$("#tot_cvar").html(result.tot_var.format(2));
					$("#tot_avar").html(result.tot_var.format(2));
					$(".tot_bonus").html(bonus.format(2));
					$("#tot_cgross").html(result.tot_gross.format(2));
					$("#tot_agross").html(result.tot_gross.format(2));
					$(".tot_pvf").html(result.tot_pvf.format(2));				
					$(".tot_ssf").html(result.tot_ssf.format(2));				
					$(".tot_deduct").html(result.tot_deduct.format(2));				
					$("#tot_ctax").html(result.tot_ctax.format(2));				
					$("#tot_atax").html(result.tot_atax.format(2));				
					$("#tot_cnet").html(result.tot_cnet.format(2));		
					$("#tot_anet").html(result.tot_anet.format(2));		
					$(".deduct").html(result.taxdeduct.format(2));
					
					$("#cam_table").html('<?=$lng['Calculate in Advance Method']?> (CAM)');
					$("#acm_table").html('<?=$lng['Accumulative Calculation Method']?> (ACM)');
					//setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
					//setTimeout(function(){$("#message").html('<b style="color:#b00">*</b> <b>Mandatory fields</b>').hide().fadeIn();},3000);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError);
				}
			});
		})
	
	
	})


</script>























