<?

	if(!isset($_GET['m'])){$month = $_SESSION['rego']['cur_month'];}else{$month = $_GET['m'];}
	$data = false;
	$paid = false;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month']." LIMIT 1";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = true;
			if($row['paid']=='Y'){$paid = true;}
		}
	}
	//var_dump($data);
	//var_dump($paid);
	/*$sql = "SELECT SUM(net_income) as net, SUM(gross_income) as gross, SUM(pvf_employer) as pvf, SUM(psf_employer) as psf, SUM(social_com) as sso, SUM(advance) as advance FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month'];
	if($res = $dbc->query($sql)){
		if($totals = $res->fetch_assoc()){
			//var_dump($totals); exit;
			$tot_pay = number_format($totals['net'],2);
			$tot_cost = number_format(($totals['gross'] + $totals['pvf'] + $totals['psf'] + $totals['sso'] + $totals['advance']),2);
		}
	}*/
	//var_dump($tot_pay); //exit;
	//var_dump($tot_cost); exit;
	
	if($data){echo '<script>var data = 1;</script>';}else{echo '<script>var data = 0;</script>';}
	//var_dump($data);

	$fix_allow = getUsedFixAllow($lang);
	$var_allow = getUsedVarAllow($lang);
	//var_dump($allow_names);
	$sCols = getEmptyResultColumns($fix_allow, $var_allow);
	//var_dump($sCols); exit;
	
	$eCols = '';
	foreach($sCols as $v){$eCols .= $v.',';}
	$eCols = '['.substr($eCols,0,-1).']';
	//var_dump($eCols);
	
?>
	
<style>
.bgc1 {
	background:rgba(147,137,80,0.12) !important;
}
.bgc2 {
	background:rgba(54,96,144,0.1) !important;
}
.bgc3 {
	background:rgba(118,146,60,0.15) !important;
}
.bgc4 {
	background:rgba(149,55,52,0.1) !important;
}
.bgc5 {
	background:rgba(95,73,122,0.1) !important;
}
.bgc11 {
	background:rgba(147,137,80,0.06) !important;
}
.bgc22 {
	background:rgba(54,96,144,0.05) !important;
}
.bgc33 {
	background:rgba(118,146,60,0.08) !important;
}
.bgc44 {
	background:rgba(149,55,52,0.05) !important;
}
.bgc55 {
	background:rgba(95,73,122,0.05) !important;
}
table.mytable thead th.rbrd, table.mytable tbody td.rbrd {
	border-right:1px #aaa solid !important;
}

	
	table.xtable {
		table-layout:auto;
		border-collapse: collapse;
		border:1px #ccc solid;
	}
	table.xtable thead th {
		white-space:nowrap;
		border:1px #ccc solid;
		padding:4px 5px;
		background:#eee;
		text-align:center;
		font-weight:700;
		font-size:13px;
		cursor:default;
	}
	table.xtable tbody td {
		padding:3px 8px;
		white-space:nowrap;
		xborder:1px #bbb dotted;
		border-right:1px #ddd solid;
		cursor:default;
		text-align:right;
	}
	table.xtable tbody td:first-child {
		border-left:1px #ccc solid;
	}
	table.xtable tbody td:last-child {
		border-right:1px #ccc solid;
	}
	table.xtable tbody tr {
		border-bottom:1px #ddd solid;
	}
	table.xtable tbody tr:last-child {
		border-bottom:1px #ccc solid !important;
	}
	
	.dataTables_filter {
		  display: none;
	}
	#searchbox {
		padding:5px 10px;
		border:1px #ccc solid;
		width:200px;
		border-radius:2px;
		float:left;
	}
	table.xtable tbody td input[type="text"] {
		text-align:right;
		font-size:12px;
		padding:2px 5px;
		margin:0;
		border:0;
		background:transparent !important;
	}
	table.xtable tbody td input[type="text"]:hover {
		background:transparent !important;
	}
	table.xtable thead th img {
		height:0px;
		width:60px;
		float:left;
		border:0px red dotted;
	}
	.fa-info-circle, .fa-times-circle {
		color: #006699;
		margin-top:3px;
	}
	.fa-info-circle:hover, .fa-times-circle:hover {
		color:#b00;
	}
	.fa-check {
		color: #009900;
	}
	table.xxtable tbody tr:hover {
		background-color: #cec;
	}
	table.xtable tbody tr.trhover {
		background-color: #ecf3f8;
		background-color: #FFFF99;
	}
	.trhover {
		background-color: #FFFF99;
	}
	table.xtable thead th {
		background:#d9edf7;
		color: #005588;
		background:#eee;
		color:#000;
	}
	table.xtable thead th.pink {
		background:#f2dede;
		color: #990000;
		background:#ddd;
		color:#333;
	}
	table.xtable tbody td.tac {text-align:center;}
	.btn.approve {
		background:#a00;
		border:1px #a00 solid;
	}
	.btn.approve:hover {
		background: #fb0;
		border:1px #fb0 solid;
	}
	a.disabled i.fa, a.disabled:hover i.fa {
		color:#ccc;
		cursor:default;
	}
	table.basicTable thead th {
		min-width:85px;
		padding:4px 10px !important;
	}

	table.excelTable {
		width:100%;
		border-collapse:collapse;
	}
	table.excelTable thead th {
		padding:2px 8px;
		text-align:left;
		font-weight:600;
		background: #066;
		color:#fff;
		border-right:1px solid #fff;
		border-bottom:1px solid #fff;
		white-space:nowrap;
	}
	table.excelTable thead th:last-child {
		border-right:0;
	}
	table.excelTable thead tr.title th {
		padding:1px 8px;
		background:#eee;
		color:#333;
		border-right:1px solid #fff;
		border-bottom:1px solid #ddd;
		min-width:90px;
		white-space:nowrap;
		text-align:right;
	}
	table.excelTable tbody td {
		padding:0;
		border-right:1px solid #eee;
		border-bottom:1px solid #eee;
		min-width:90px;
		white-space:nowrap;
		text-align:right;
	}
	table.excelTable tbody td:last-child {
		border-right:0;
	}
	table.excelTable tbody td.tal {
		text-align:left;
	}
	table.excelTable tbody td.pad18 {
		padding:1px 8px;
	}
	table.excelTable tbody td input[type="text"] {
		text-align:right;
		padding:1px 8px;
		margin:0;
		border:0;
		background:#ffd;
		width:100%;
	}
	table.excelTable tbody td input[type="text"].nofoc {
		background:transparent;
	}

.payslipTable {
	width:100%;
	border-collapse:collapse;
}
.payslipTable th {
	padding:3px 6px;
	white-space:nowrap;
	border:1px solid #aaa;
	background:#eee;
}
.payslipTable td {
	padding:4px 8px;
	white-space:nowrap;
	border:1px solid #aaa;
	text-align:right;
}
.payslipTable th.tar {
	text-align:right;
}
.payslipTable th.tac {
	text-align:center;
}
table.modaltable input[type=text] {
	padding:3px 5px;
	text-align:right;
	width:150px;
}
table.modalTable td.tar { 
	text-align:right;
	padding:4px 10px;
}
table.modalTable thead th { 
	padding:4px;
	white-space:normal;
	line-height:100%;
	vertical-align:middle;
	width:100px;
}

table.editTable {
	width:100%;
	border-collapse:collapse;
	font-size:13px;
	margin-bottom:5px;
}
table.editTable thead tr {
	background:#eee;
	color:#900;
}
table.editTable thead th {
	padding:2px 8px;
	font-weight:600;
	text-align:left;
	border-right:1px solid #fff;
	border-bottom:1px solid #ccc;
}
table.editTable tbody th {
	padding:2px 8px;
	white-space:nowrap;
	font-weight:600;
	border:1px solid #eee;
	border-left:0;
}
table.editTable tbody td {
	padding:2px 8px;
	border:1px solid #eee;
	white-space:nowrap;
}
table.editTable tbody td:first-child {
	border-left:0;
}
table.editTable tbody td:last-child {
	border-right:0;
}
table.editTable input[type="text"] {
	border:0;
	padding:2px 8px;
	background:#ffe;
	width:100%;
}
table.editTable select {
	border:0;
	padding:1px 6px !important;
	width:auto;
	min-width:100%;
	background:#ffe;
}
table.editTable td.nopad {
	padding:0;
}
.smBut {
	float:right;
	margin-left:10px;
}

</style>
			
	<h2>
		<i class="fa fa-table"></i>&nbsp; <?=$lng['Payroll result']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;
		<?=$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang]?>
		<!--&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<span style="color:#0033CC">Labor cost : <?=$tot_cost?> <?=$lng['Baht']?></span>
		&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<span style="color:green">Total to pay : <?=$tot_pay?> <?=$lng['Baht']?></span>-->
		&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<span style="color:#333"><?=$lng['Paydate']?> : <?=$_SESSION['rego']['paydate']?></span>
	</h2>
		

	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<table border="0" style="width:100%; margin-bottom:8px">
			<tr>
				<td style="padding-right:5px">
					<div class="searchFilter btn-result" style="margin:0">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default"><i class="fa fa-times"></i></button>
					</div>
				</td>
				<td style="padding-right:5px">
					<select id="pageLength" class="button">
						<option selected value=""><?=$lng['Rows / page']?></option>
						<option value="10">10 <?=$lng['Rows / page']?></option>
						<option value="15">15 <?=$lng['Rows / page']?></option>
						<option value="20">20 <?=$lng['Rows / page']?></option>
						<option value="30">30 <?=$lng['Rows / page']?></option>
						<option value="40">40 <?=$lng['Rows / page']?></option>
						<option value="50">50 <?=$lng['Rows / page']?></option>
					</select>
				</td>
				<td style="padding-right:5px">
					<select id="taxFilter" class="button">
						<option selected value="all"><?=$lng['Show all']?></option>
						<option value="1"><?=$lng['PND']?> 1</option>
						<option value="3"><?=$lng['PND']?> 3</option>
						<option value="0"><?=$lng['no Tax']?></option>
					</select>
				</td>
				<td style="width:80%"></td>
				<td style="padding-left:5px">
					<button type="button" class="btn-result btn btn-primary" id="summary">
						<i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Summary']?>
					</button>
				</td>
				<td style="padding-left:5px">
					<button type="button" class="btn-result btn btn-primary" id="printExcelReport">
						<i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Excel report']?>
					</button>
				</td>
				<!--<td style="padding-left:5px">
					<button type="button" class="btn-result btn btn-primary" id="printPayslips">
						<i class="fa fa-print"></i>&nbsp; <?=$lng['Print payslips & Archive']?></span>
					</button>
				</td>-->
			</tr>
		</table>
		
		<div id="showTable" style="display:none">
			<table id="datatable" class="dataTable hoverable selectable nowrap compact tar" border="0">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th colspan="7" class="tac"><?=$lng['Employee']?></th>
						<th class="tac" colspan="5"><?=$lng['Overtime']?></th>
						<? if(count($fix_allow) > 0){?>
						<th class="tac" colspan="<?=count($fix_allow)?>"><?=$lng['Fix. allowances']?></th>
						<? } ?>
						<? if(count($var_allow) > 0){?>
						<th class="tac" colspan="<?=count($var_allow)?>"><?=$lng['Var. allowances']?></th>
						<? } ?>
						<!--<th class="tac"><?=$lng['Total']?></th>-->
						<th class="tac" colspan="7"><?=$lng['Other income']?></th>
						<th class="tac" style="xbackground:#cdc; xborder-bottom:1px solid #eee;"><?=$lng['Gross']?></th>
						<!--<th class="tac"><?=$lng['Deductions']?></th>-->
						<th class="tac" colspan="12"><?=$lng['Deductions']?></th>
						<th class="tac" colspan="4" style="xbackground:#ccd; xborder-bottom:1px solid #eee;"><?=$lng['Net']?></th>
					</tr>
					
					<tr>
						<th class="par30" style="width:10px;"><?=$lng['ID']?></th>
						<th class="par30 tal" style="width:90%;"><?=$lng['Employee name']?></th>
						<th data-sortable="false" style="width:1px;"><i title="<?=$lng['Tax calculation']?>" data-toggle="tooltip" class="fa fa-calculator" style="font-size:14px"></i></th>
						<th data-sortable="false" style="width:1px;"><i title="<?=$lng['Show Payslip']?>" data-toggle="tooltip" class="fa fa-product-hunt" style="font-size:14px"></i></th>
						<th data-visible="false" style="width:1px;"><i title="<?=$lng['Employee year totals']?>" data-toggle="tooltip" class="fa fa-list" style="font-size:14px"></i></th>
						<th data-visible="false" data-visible="true" style="width:1px;"><i title="<?=$lng['Edit']?>" data-toggle="tooltip" class="fa fa-edit" style="font-size:15px"></i></th>
						<th data-sortable="false"><?=$lng['Days']?></th>
						<th class="par30"><?=$lng['Salary']?></th>

						<th class="tac" data-sortable="false"><?=$lng['OT 1']?></th>
						<th class="tac" data-sortable="false"><?=$lng['OT 1.5']?></th>
						<th class="tac" data-sortable="false"><?=$lng['OT 2']?></th>
						<th class="tac" data-sortable="false"><?=$lng['OT 3']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Other OT']?> &#3647;</th>
						<!--<th class="tac" data-sortable="false"><?=$lng['Total']?></th>-->
						
						<? foreach($fix_allow as $k=>$v){
								echo '<th class="tac" data-sortable="false">'.$v.'</th>';
							}  
							foreach($var_allow as $k=>$v){
								echo '<th class="tac" data-sortable="false">'.$v.'</th>';
							} ?>
						<th class="tac" data-sortable="false"><?=$lng['Tax by company']?></th>
						<th class="tac" data-sortable="false"><?=$lng['SSO by company']?></th>
						
						<th class="tac" data-sortable="false"><?=$lng['Other income']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Severance']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Remaining salary']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Notice payment']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Paid leave']?></th>
						
						<th class="tac" data-sortable="false"><?=$lng['Income']?></th>

						<th class="tac" data-sortable="false" ><?=$lng['Absence']?></th>
						<th class="tac" data-sortable="false" ><?=$lng['Leave WOP']?></th>
						<th class="tac" data-sortable="false" ><?=$lng['Late Early']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Before tax']?></th>
						
						<th class="tac" data-sortable="false"><?=$lng['After tax']?></th>
						<th class="tac" data-sortable="false"><?=$lng['PVF']?></th>
						<th class="tac" data-sortable="false"><?=$lng['PSF']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Social']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Tax']?></th>
						
						<th class="tac" data-sortable="false"><?=$lng['Advance']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Legal deduct']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Total']?></th>
						<th class="tac" data-sortable="false"><?=$lng['Income']?></th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
		
	</div>
	<!--<div style="position:absolute;top:244px; left:0; right:0; background:rgba(255,0,0,0.5); height:104px;"></div>-->
	
	<!-- Modal Anual Overview -->
	<div class="modal fade" id="modalReport" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:1000px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span><?=$lng['Annual overview']?> - </span><span id="mname"></span>
					</h5>
					<div style=" position:absolute; right:40px; padding:4px 0">
						<span style="padding-right:10px; font-size:14px; color:#a00; font-weight:600" id="taxReturn"></span>
						<a href="#" id="prAnnualOverview" style="margin-right:10px"><i class="fa fa-print fa-lg"></i></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px">
					<div style="overflow-x:auto" id="reportTable"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Payslip -->
	<div class="modal fade" id="modalPayslip" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:750px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span><?=$lng['Payslip']?></span>
					</h5>
					<div style=" position:absolute; right:40px; padding:4px 0">
						<a href="#" id="prPayslip" style="margin-right:8px"><i class="fa fa-print fa-lg"></i></a>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="overflow-x:auto; padding:0">
					<div style="min-width:700px; padding:5px 20px 20px">
						<span id="payslipTable"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Summary -->
	<div class="modal fade" id="modalSummary" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document" style="max-width:500;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span><?=$lng['Total income this month']?></span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:20px">
					<div id="summaryTable"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Edit Payroll -->
	<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" style="max-width:1000px;">
			<div class="modal-content">
				<div class="modal-body" style="padding-bottom:12px;">
					<!--<div style="background:#880000; color:#fff; padding:6px 10px; font-weight:600; text-align:left; font-size:14px">Adjust payroll<? //=$lng['Edit Payroll']?>
					</div>-->
					<form id="editForm">
						<span id="editTable"></span>
						<div style="height:10px"></div>
						<button type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<button disabled type="button" class="btn btn-primary btn-fr update"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>
						<button disabled type="button" class="btn btn-primary btn-fl recalculate" data-save="0"><i class="fa fa-calculator"></i>&nbsp; <?=$lng['Recalculate']?></button>
					</form>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="pops">
		<div style="width:100%; min-height:200px; max-height:100%; background:#fff; padding:20px; box-shadow:0 0 15px rgba(0,0,0,0.6); position:relative; overflow-y:auto">
			<div id="dump2"></div>
			<table border="0" style="margin:0; width:100%">
				<thead>
					<tr>
						<th style="padding:0 0 5px 2px; font-size:18px; white-space:nowrap" class="tal" id="empName"></th>
						<th style="width:80%"></th>
						<th style="padding:0 0 5px 5px" class="tar">
							<button id="recalculate" type="button" class="btn btn-primary"><i class="fa fa-calculator fa-mr"></i> <?=$lng['Recalculate']?></button>						
						</th>
						<th style="padding:0 0 5px 5px" class="tar">
							<button type="button" class="btn btn-primary showreport"><i class="fa fa-list fa-mr"></i><?=$lng['Employee year totals']?></button>						
						</th>
						<th style="padding:0 0 5px 5px" class="tar">
							<button type="button" class="btn btn-primary" id="hidePops"><i class="fa fa-times fa-mr"></i> <?=$lng['Close popup']?></button>						
						</th>
					</tr>
				</thead>
			</table>
			
			<form id="employeeForm">
			<table border="0" style="width:100%; border-collapse:collapse">
				<tr>
					<td style="padding-right:7px; border-right:1px solid #ddd; vertical-align:top; width:50%"> <!--LEFT TABLE-->
						<table class="editTable" border="0" style="table-layout:fixed"> <!--OT-->
							<thead>
								<tr>
									<th><?=$lng['Overtime']?></th>
									<th class="tar"><?=$lng['OT 1']?></th>
									<th class="tar"><?=$lng['OT 1.5']?></th>
									<th class="tar"><?=$lng['OT 2']?></th>
									<th class="tar"><?=$lng['OT 3']?></th>
									<th class="tar"><?=$lng['Other OT']?></th>
									<th class="tar"><?=$lng['OT rate']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Hours']?></th>
									<td class="tar nopad"><input name="ot1h" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar nopad"><input name="ot15h" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar nopad"><input name="ot2h" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar nopad"><input name="ot3h" class="sel hourFormat tar" type="text" value="0"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<th><?=$lng['Baht']?></th>
									<td class="tar" id="ot1b"></td>
									<td class="tar" id="ot15b"></td>
									<td class="tar" id="ot2b"></td>
									<td class="tar" id="ot3b"></td>
									<td class="tar nopad"><input name="ootb" class="sel numeric tar" type="text" value="0"></td>
									<td class="tar nopad"><input name="ot_rate" class="sel float22 tar" type="text" value="0"></td>
								</tr>
							</tbody>
						</table>
						
						<table border="0" style="width:100%"> <!--Allow-->
							<tr>
								<td style="width:50%; padding-right:3px; vertical-align:top">
									<table id="fixallowTable" class="editTable" border="0">
										<thead>
											<tr>
												<th style="width:90%"><?=$lng['Fixed allowances']?></th>
												<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</td>
								<td style="width:50%; padding-left:3px; vertical-align:top">
									<table id="varallowTable" class="editTable" border="0">
										<thead>
											<tr>
												<th style="width:90%"><?=$lng['Variable allowances']?></th>
												<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
						
						<table border="0" style="width:100%"> <!--Deduct-->
							<tr>
								<td style="width:50%; padding-right:3px; vertical-align:top">
									<table id="fixdeductTable" class="editTable" border="0">
										<thead>
											<tr>
												<th style="width:90%"><?=$lng['Fixed deductions']?></th>
												<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</td>
								<td style="width:50%; padding-left:3px; vertical-align:top">
									<table id="vardeductTable" class="editTable" border="0">
										<thead>
											<tr>
												<th style="width:90%"><?=$lng['Variable deductions']?></th>
												<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</td>
							</tr>
						</table>
						
						<table class="editTable" border="0"> <!--Deductions-->
							<thead>
								<tr>
									<th style="width:90%"><?=$lng['Deductions']?></th>
									<th class="tar" style="min-width:80px"><?=$lng['Hours']?></th>
									<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Absence']?></th>
									<td class="tar nopad"><input name="absence" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar" id="absence_b">0</td>
								</tr>
								<tr>
									<th><?=$lng['Late Early']?></th>
									<td class="tar nopad"><input name="late_early" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar" id="late_early_b">0</td>
								</tr>
								<tr>
									<th><?=$lng['Leave WOP']?></th>
									<td class="tar nopad"><input name="leave_wop" class="sel hourFormat tar" type="text" value="0"></td>
									<td class="tar" id="leave_wop_b">0</td>
								</tr>
								<tr>
									<th><?=$lng['Deduct before']?></th>
									<td></td>
									<td class="tar" id="deduct_before">0</td>
								</tr>
								<tr>
									<th><?=$lng['Deduct after']?></th>
									<td></td>
									<td class="tar" id="deduct_after">0</td>
								</tr>
							</tbody>
						</table>
						
						<table class="editTable" border="0"> <!--PVF-->
							<thead>
								<tr>
									<th style="width:90%"><?=$lng['PVF']?> / <?=$lng['PSF']?></th>
									<th class="tar" style="min-width:80px">%</th>
									<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['PVF Employee']?></th>
									<td class="tar nopad"><input name="pvf_rate_emp" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="pvf_employee">0</td>
								</tr>
								<tr>
									<th><?=$lng['PVF Employer']?></th>
									<td class="tar nopad"><input name="pvf_rate_com" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="pvf_employer">0</td>
								</tr>
								<tr>
									<th><?=$lng['PSF Employee']?></th>
									<td class="tar nopad"><input name="psf_rate_emp" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="psf_employee">0></td>
								</tr>
								<tr>
									<th><?=$lng['PSF Employer']?></th>
									<td class="tar nopad"><input name="psf_rate_com" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="psf_employer">0</td>
								</tr>
							</tbody>
						</table>
						
						<table class="editTable" border="0"> <!--SSO-->
							<thead>
								<tr>
									<th style="width:90%"><?=$lng['SSO']?></th>
									<th class="tar" style="min-width:80px">%</th>
									<th class="tar" style="min-width:100px"><?=$lng['Baht']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['SSO Employee']?></th>
									<td class="tar nopad"><input name="sso_rate_emp" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="social">0</td>
								</tr>
								<tr>
									<th><?=$lng['SSO Employer']?></th>
									<td class="tar nopad"><input name="sso_rate_com" class="sel float22 tar" type="text" value="0"></td>
									<td class="tar" id="social_com">0</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="padding:0 7px; vertical-align:top; width:25%"> <!--CENTER TABLE-->
						<table class="editTable">
							<thead>
								<tr>
									<th colspan="2"><?=$lng['Fixed income']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="pt3"><?=$lng['Days paid']?></td>
									<td class="tar nopad"><input name="paid_days" class="sel float72 tar" type="text" value="0"></td>
								</tr>
								<tr>
									<td class="pt3"><?=$lng['Salary']?></td>
									<td class="tar" id="eSalary"></td>
								</tr>
								<tr>
									<td><?=$lng['Fixed allowances']?></td>
									<td class="tar" id="eFixAllow">0</td>
								</tr>
								<tr>
									<td><?=$lng['Fixed deductions']?></td>
									<td class="tar" id="eFixDeduct">0</td>
								</tr>
								<tr>
									<th class="pb5"><?=$lng['Total fixed income']?></th>
									<th class="tar pb5" id="eTotFix">0</th>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th colspan="2"><?=$lng['Variable income']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?=$lng['Overtime']?></td>
									<td class="tar" id="eOT">0</td>
								</tr>
								<tr>
									<td class="pt3"><?=$lng['Variable allowances']?></td>
									<td class="tar pt3" id="eVarAllow">0</td>
								</tr>
								<tr>
									<td><?=$lng['Variable deductions']?></td>
									<td class="tar" id="eVarDeduct">0</td>
								</tr>
								<tr>
									<td><?=$lng['Other income']?></td>
									<td class="tar nopad"><input name="other_income" class="sel float72 tar" type="text" value="0"></td>
								</tr>
								<tr>
									<td><?=$lng['Tax by company']?></td>
									<td class="tar" id="eTaxCompany"></td>
								</tr>
								<tr>
									<td><?=$lng['SSO by company']?></td>
									<td class="tar" id="eSsoCompany"></td>
								</tr>
								<tr>
									<td><?=$lng['End contract']?></td>
									<td class="tar" id="eContract">0</td>
								</tr>
								<tr>
									<th class="pb5"><?=$lng['Total variable income']?></th>
									<th class="tar pb5" id="eTotVar">0</th>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th><?=$lng['Gross income this month']?></th>
									<th class="tar" id="eGross">0</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="pt3"><?=$lng['PVF']?> / <?=$lng['PSF']?></td>
									<td class="tar pt3" id="ePvfEmp">0</td>
								</tr>
								<tr>
									<td><?=$lng['SSO']?></td>
									<td class="tar" id="eSsoEmp">0</td>
								</tr>
								<tr>
									<td><?=$lng['TAX']?></td>
									<td class="tar" id="eTax">0</td>
								</tr>
								<tr>
									<td class="pb5"><?=$lng['Other deductions']?></td>
									<td class="tar" id="eOtherDeduct"></td>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th><?=$lng['Net income this month']?></th>
									<th class="tar" id="eNet">0</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="pt3"><?=$lng['Advance payment']?></td>
									<td class="nopad"><input name="advance" class="sel float72 tar" type="text" value="0"></td>
								</tr>
								<tr>
									<td class="pb5"><?=$lng['Legal deductions']?></td>
									<td class="tar pb5" id="eLegal">0</td>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th><?=$lng['Net paid salary this month']?></th>
									<th class="tar" id="eNetPaid">0</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="pt3"><?=$lng['PVF']?> /<?=$lng['PSF']?>  <?=$lng['Company']?></td>
									<td class="tar pt3" id="ePvfCom">0</td>
								</tr>
								<tr>
									<td class="pb5"><?=$lng['SSO']?> <?=$lng['Company']?></td>
									<td class="tar pb5" id="eSsoCom">0</td>
								</tr>
							</tbody>
							<thead>
								<tr>
									<th><?=$lng['Total cost this month']?></th>
									<th class="tar" id="eCost">0</th>
								</tr>
							</thead>
						</table>
					</td>
					<td style="padding-left:7px; border-left:1px solid #ddd; vertical-align:top; width:25%"> <!--RIGHT TABLE-->
						<table class="editTable" border="0">
							<thead>
								<tr>
									<th colspan="2">
										<?=$lng['Settings']?>
										<!--<a id="updateSettings" class="smBut" href="#"><i class="fa fa-save fa-lg"></i></a>-->
										<!--<a class="smBut" href="#"><i class="fa fa-edit fa-lg"></i></a>-->
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Basic salary']?></th>
									<td class="nopad"><input name="basic_salary" class="sel float72" type="text" value="0"></td>
								</tr>
								<tr>
									<th><?=$lng['Contract type']?></th>
									<td class="nopad">
										<select name="contract_type">
											<option value="month"><?=$lng['Monthly wage']?></option>
											<option value="day"><?=$lng['Daily wage']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculation base']?></th>
									<td class="nopad">
										<select name="calc_base" id="calc_base">
											<option value="gross"><?=$lng['Gross amount']?></option>
											<option value="net"><?=$lng['Net amount']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['SSO paid by']?></th>
									<td class="nopad">
										<select name="sso_by">
											<option value="0"><?=$lng['Employee']?></option>
											<option value="1"><?=$lng['Company']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Base OT rate']?></th>
									<td class="nopad">
										<select name="base_ot_rate">
											<option value="cal"><?=$lng['Calculated']?></option>
											<option value="fix"><?=$lng['Fixed']?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Modify Tax amount']?></th>
									<td class="nopad"><input name="modify_tax" class="sel neg_numeric" type="text" value="0"></td>
								</tr>
								<tr>
									<th><?=$lng['Tax calculation method']?></th>
									<td class="nopad">
										<select name="calc_method">
											<option value="cam">CAM</option>
											<option value="acm">ACM</option>
											<option value="ytd">YTD</option>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculate SSO']?></th>
									<td class="nopad">
										<select name="calc_sso">
											<? foreach($noyes01 as $k=>$v){ ?>
												<option value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculate PVF']?></th>
									<td class="nopad">
										<select name="calc_pvf">
											<? foreach($noyes01 as $k=>$v){ ?>
												<option value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Calculate Tax']?></th>
									<td class="nopad">
										<select name="calc_tax">
											<option value="1"><?=$lng['PND']?> 1</option>
											<option value="3"><?=$lng['PND']?> 3</option>
											<option value="0"><?=$lng['no Tax']?></option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="editTable" border="0">
							<thead>
								<tr>
									<th><?=$lng['Tax calculation']?></th>
									<th class="tar"><?=$lng['Baht']?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th><?=$lng['Gross year income']?></th>
									<td class="tar" id="c_gross_year"></td>
								</tr>
								<tr>
									<th><?=$lng['Total tax deductions']?></th>
									<td class="tar" id="c_tax_deductions"></td>
								</tr>
								<tr>
									<th><?=$lng['Taxable year income']?></th>
									<td class="tar" id="c_taxable_year"></td>
								</tr>
								<tr>
									<th><?=$lng['Tax whole year']?></th>
									<td class="tar" id="c_tax_year"></td>
								</tr>
								<tr>
									<th><?=$lng['Modified tax']?></th>
									<td class="tar" id="c_tax_modify"></td>
								</tr>
								<tr>
									<th><?=$lng['Tax this month']?></th>
									<td class="tar" id="c_tax_this_month"></td>
								</tr>
							</tbody>
						</table>
						<table id="taxTable" class="editTable" border="0">
							<thead>
								<tr>
									<th><?=$lng['Tax deductions']?></th>
									<th class="tar"><?=$lng['Baht']?></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			</form>
		
		
		</div>
	</div>
	
	
	<!-- PAGE RELATED PLUGIN(S) -->
	
	<script type="text/javascript">

		var innerheight = window.innerHeight;
		var dheight = innerheight-303;

		$(document).ready(function() {
			
			var id = '';
			var emp_id = '';
			var emp_name = '';
			
			function getPayrollData(id){
				$.ajax({
					url: "ajax/get_editdata_payroll.php",
					data: {id: id},
					dataType: 'json',
					success:function(data){
						//$('#dump2').html(data); return false;
						emp_id = data.emp_id;
						emp_name = data.emp_name;
						$('#empName').html(data.emp_id + ' ' + data.emp_name);
						$('input[name="paid_days"]').val(data.paid_days);
						$('input[name="ot1h"]').val(data.ot1h);
						$('input[name="ot15h"]').val(data.ot15h);
						$('input[name="ot2h"]').val(data.ot2h);
						$('input[name="ot3h"]').val(data.ot3h);
						$('input[name="ootb"]').val(data.ootb);
						
						$('#ot1b').html(parseFloat(data.ot1b).format(2));
						$('#ot15b').html(parseFloat(data.ot15b).format(2));
						$('#ot2b').html(parseFloat(data.ot2b).format(2));
						$('#ot3b').html(parseFloat(data.ot3b).format(2));

						$('input[name="ot_rate"]').val(data.ot_rate);
						
						$('input[name="absence"]').val(data.absence);
						$('#absence_b').html(parseFloat(data.absence_b).format(2));
						$('input[name="late_early"]').val(data.late_early);
						$('#late_early_b').html(parseFloat(data.late_early_b).format(2));
						$('input[name="leave_wop"]').val(data.leave_wop);
						$('#leave_wop_b').html(parseFloat(data.leave_wop_b).format(2));

						$('#deduct_before').html(parseFloat(data.tot_deduct_before).format(2));
						$('#deduct_after').html(parseFloat(data.tot_deduct_after).format(2));

						$('input[name="pvf_rate_emp"]').val(data.pvf_rate_emp);
						$('input[name="pvf_rate_com"]').val(data.pvf_rate_com);
						$('input[name="sso_rate_emp"]').val(data.sso_rate_emp);
						$('input[name="sso_rate_com"]').val(data.sso_rate_com);
						
						$('#pvf_rate_emp').html(parseFloat(data.pvf_employee).format(2));
						$('#pvf_employer').html(parseFloat(data.pvf_employer).format(2));
						$('#psf_employee').html(parseFloat(data.psf_employee).format(2));
						$('#psf_employer').html(parseFloat(data.psf_employer).format(2));

						$('#pvf_employee').html(parseFloat(data.pvf_employee).format(2));
						$('#pvf_employer').html(parseFloat(data.pvf_employer).format(2));
						$('#psf_employee').html(parseFloat(data.psf_employee).format(2));
						$('#psf_employer').html(parseFloat(data.psf_employer).format(2));

						$('#social').html(parseFloat(data.social).format(2));
						$('#social_com').html(parseFloat(data.social_com).format(2));

						$('input[name="paid_days"]').val(parseFloat(data.paid_days));
						$('#eSalary').html(parseFloat(data.salary).format(2));
						$('#eFixAllow').html(parseFloat(data.total_fix_allow).format(2));
						$('#eFixDeduct').html('-'+parseFloat(data.fix_deduct_before).format(2));
						$('#eTotFix').html(parseFloat(data.tot_fix).format(2));
						
						$('#eOT').html(parseFloat(data.total_otb).format(2));
						$('#eVarAllow').html(parseFloat(data.total_var_allow).format(2));
						$('#eVarDeduct').html('-'+parseFloat(data.var_deduct_before).format(2));
						$('input[name="other_income"]').val(parseFloat(data.other_income));
						$('#eTaxCompany').html(parseFloat(data.tax_by_company).format(2));
						$('#eSsoCompany').html(parseFloat(data.sso_by_company).format(2));
						$('#eContract').html(parseFloat(data.contract).format(2));
						$('#eTotVar').html(parseFloat(data.tot_var).format(2));
						$('#eGross').html(parseFloat(data.gross).format(2));
						
						$('#ePvfEmp').html(parseFloat(data.pvf_emp).format(2));
						$('#eSsoEmp').html(parseFloat(data.social).format(2));
						$('#eTax').html(parseFloat(data.tax_month).format(2));
						$('#eOtherDeduct').html(parseFloat(data.other_deduct).format(2));
						$('#eNet').html(parseFloat(data.net).format(2));
						
						$('input[name="advance"]').val(parseFloat(data.advance));
						$('#eLegal').html(parseFloat(data.legal_deductions).format(2));
						$('#eNetPaid').html(parseFloat(data.net_paid).format(2));
						
						$('#ePvfCom').html(parseFloat(data.pvf_com).format(2));
						$('#eSsoCom').html(parseFloat(data.social_com).format(2));
						$('#eCost').html(parseFloat(data.tot_cost).format(2));
						
						$('input[name="basic_salary"]').val(data.basic_salary);
						$('select[name="contract_type"]').val(data.contract_type);
						$('select[name="calc_base"]').val(data.calc_base);
						$('select[name="sso_by"]').val(data.sso_by);
						/*if(data.calc_base == 'gross'){
							$('select[name="sso_by"]').prop('disabled', true);
						}else{
							$('select[name="sso_by"]').prop('disabled', false);
						}*/
						$('select[name="base_ot_rate"]').val(data.base_ot_rate);
						$('input[name="modify_tax"]').val(data.modify_tax);
						$('select[name="calc_method"]').val(data.calc_method);
						$('select[name="calc_sso"]').val(data.calc_sso);
						$('select[name="calc_pvf"]').val(data.calc_pvf);
						$('select[name="calc_tax"]').val(data.calc_tax);
						
						$('#c_gross_year').html(data.gross_year);
						$('#c_tax_deductions').html(data.tax_deductions);
						$('#c_taxable_year').html(data.taxable_year);
						$('#c_tax_year').html(data.tax_year);
						$('#c_tax_month').html(data.tax_month);
						$('#c_tax_modify').html(data.tax_modify);
						$('#c_tax_this_month').html(data.tax_this_month);
						
						$('#fixallowTable tbody').html(data.fixallowTable);
						$('#varallowTable tbody').html(data.varallowTable);
						$('#fixdeductTable tbody').html(data.fixdeductTable);
						$('#vardeductTable tbody').html(data.vardeductTable);
						$('#taxTable tbody').html(data.taxTable);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			}
			
			/*$(document).on('change','#calc_base', function(){
				if($(this).val() == 'gross'){
					$('select[name="sso_by"]').prop('disabled', true);
				}else{
					$('select[name="sso_by"]').prop('disabled', false);
				}
			})*/
			
			
			
			$(document).on('click','.showPops', function(){
				id = $(this).data('id');
				getPayrollData(id);
				$('.pops').fadeIn(300);
			});
			$(document).on('click','#hidePops', function(){
				$('.pops').fadeOut(300);
			});
			
			$(document).on("click", "#recalculate", function(){
				$('#recalculate i').removeClass('fa-calculator').addClass('fa-refresh fa-spin');
				var data = $('#employeeForm').serialize();
				data += '&prid='+id+'&change=0';
				//alert(data)
				$.ajax({
					url: "ajax/calculate_payroll.php",
					type: 'POST',
					data: data,
					//dataType: 'json',
					success: function(result) {
						//$("#dump2").html(data); //return false;
						getPayrollData(id);
						dtable.ajax.reload(null, false);
						if($.trim(result) == 'success'){	
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+result,
								duration: 4,
							})
						}
						setTimeout(function(){$('#recalculate i').removeClass('fa-refresh fa-spin').addClass('fa-calculator');},500);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				 });
			})
			
			/*$(document).on("click", ".update", function(){
				$('.update i').removeClass('fa-save').addClass('fa-refresh fa-spin');
				var data = $('#editForm').serialize();
				data += '&change=0&save=1';
				$.ajax({
					url: "ajax/recalculate_employee_payroll.php",
					type: 'POST',
					data: data,
					success: function(data) {
						//$("#dump").html(data); //return false;
						dtable.ajax.reload(null, false);
						setTimeout(function(){location.reload();},400);
					},
					error: function (xhr, ajaxOptions, thrownError) {
					 	alert(thrownError);
					}
				 });
			})*/
			
			/*$(document).on("change", "form#editForm input", function(e) {
				var pvfe = $("input[name=pvfe]").val();
				if(pvfe==''){pvfe = 0;}
				var pvfr = $("input[name=pvfr]").val();
				var ssf = $("input[name=ssf]").val();
				if(ssf==''){ssf = 0;}
				var tax = $("input[name=tax]").val();
				if(tax==''){tax = 0;}
				var btax = $("input[name=btax]").val();
				var odeduct = $("input[name=odeduct]").val();
				var gross = $("input[name=gross]").val();
				var deductions = parseFloat(pvfe) + parseFloat(ssf) + parseFloat(tax) + parseFloat(odeduct);
				var net_income = parseFloat(gross) - parseFloat(deductions);
				$("input[name=deduct]").val(deductions);
				$("#deductions").html(deductions.format(2));
				$("#net_income").html(net_income.format(2));
				$("input[name=net]").val(net_income);
			});*/
			
			$('#employeeForm').keypress(function(e) {
				 if(e.which == 13) {
					  e.preventDefault();
				 }
			});

			var drows = Math.floor(dheight/26);
			var eCols = <?=$eCols?>;
			
			$(document).on("change", ".notnull", function(){
				if(this.value == ''){$(this).val(0)}
			})
			
			$("#printPayslips").on('click', function(){
				window.open('print/print_payslips.php?m=<?=$_SESSION['rego']['cur_month']?>', '_blank');
			});
			$("#prPayslip").on('click', function(){
				$('#modalPayslip').modal('toggle');
				window.open('print/print_payslips.php?id='+popid,'_blank');
			});
			$("#printPdfReport").on('click', function(){
				window.open('reports/generate_payroll_report_emp.php', '_blank');
			});
			$("#printExcelReport").on('click', function(){
				window.open('export_payroll_report_exel.php', '_self');
			});

			if(data == 1){
				//var rows = <?//=count($data)?>;
				//var scrY = true;
				//if(rows>20){scrY = heights}
				 var dtable = $('#datatable').DataTable({
					scrollY:        false,//scrY,//heights-288,
					scrollX:        true,
					scrollCollapse: false,
					fixedColumns:   true,
					lengthChange:  	false,
					searching: 		true,
					ordering: 		true,
					paging: 		true,
					//pagingType: 'full_numbers',
					//pageLength: 	drows,
					filter: 		true,
					info: 			true,
					//autoWidth:		false,
					processing: 	false,
					serverSide: 	true,
					<?=$dtable_lang?>
					ajax: {
						url: "ajax/server_get_payroll_result.php",
						type: "POST",
						"data": function(d){
							d.filter = $('#taxFilter').val();
						}
					},
					columnDefs: [
						  { targets: [0,1], "class": 'tal' },
						  //{ targets: [7], "class": 'bold' },
						  //{ targets: [25], "class": 'gross' },
						  //{ targets: [37], "class": 'duct' },
						  //{ targets: [38], "class": 'net' },
						  { targets: eCols, "visible": false },
						  { targets: [1], width: '80%' }
					],
					initComplete : function( settings, json ) {
						$('#showTable').fadeIn(200);
						dtable.columns.adjust().draw();
					}
				});
			}
			
			$("#searchFilter").keyup(function() {
				dtable.search(this.value).draw();
			});		
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})
			$(document).on("change", "#pageLength", function(e) {
				if(this.value > 0){
					dtable.page.len( this.value ).draw();
				}
			})
			$(document).on("change", "#taxFilter", function(e) {
				dtable.ajax.reload(null, false);
			})
			//##### Show Payslip #########
			$(document).on("click", "a.showpayslip", function(e) {
				e.preventDefault();
				popid = $(this).data('id');
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: "ajax/get_modal_payslip.php",
					cache: false,
					data: {id: id},
					success:function(result){
						//var data = result;//$.parseJSON(result);
						//$('#mprint').attr('href','print_payslips_laser_A4_window.php?id='+id);
						$('#payslipTable').html(result);
						//return false;
						$('#modalPayslip').modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			 });
			
			//##### Show Summary #########
			$(document).on("click", "#summary", function(e) {
				$.ajax({
					url: "ajax/get_summary.php",
					success:function(result){
						//$('#dump').html(result);
						$('#summaryTable').html(result);
						$('#modalSummary').modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			 });
			
			//##### Show Employee Anual Overview #########
			$(document).on("click", ".showreport", function(e) {
				$.ajax({
					url: "ajax/get_annual_overview.php",
					data: {id: emp_id},
					dataType: 'json',
					success:function(result){
						//$('#dump').html(result); return false;
						$('#mname').html(emp_id + ' : ' + emp_name);
						if(result.tax_return > 0){
							$('#taxReturn').html('<?=$lng['Tax refund']?> : ' + (result.tax_return).format(2) + ' <?=$lng['Baht']?>');
						}else{
							$('#taxReturn').html('');
						}
						$('#reportTable').html(result.table);
						$('#modalReport').modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			 });
		
			$(document).on("click", "a#prAnnualOverview", function(e) {
				$('#modalReport').modal('toggle');
				setTimeout(function(){window.open(ROOT+'payroll/print/print_employee_tax_overview.php?id='+id,'_blank')},400);
			 });
			 
		})
	</script>

	</body>

</html>