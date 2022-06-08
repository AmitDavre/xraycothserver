<?php

	if(session_id()==''){session_start();}
	ob_start(); //024
	
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	$pattern = '%%%-%-%%%%%-%';

	$_account = str_replace('-', '', $banks['011']['number']);
	$bank_account = $banks['011']['number'];
	$compname = substr($banks['011']['name'], 0, 60);
	
	$nr = 1;
	$account = '';
	$tot_salary = 0;
	$ptotal = 0;
	$stotal = 0;
	
	$nr = 1;
	$tot_salary = 0;
	$other_data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$tmp = round($row['net_income'],2);
				$salary = $tmp * 100;
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '024', $_GET['bank']);
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$account = str_replace('-', '', $empinfo['bank_account']);
					
					$other_data[$nr]['account'] = $account;
					if(strlen($account) == 10){
						$data[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					$other_data[$nr]['name'] = $name;
					$other_data[$nr]['income'] = number_format($row['net_income'],2);
					$other_data[$nr]['branch'] = $empinfo['bank_branch'];
					$other_data[$nr]['code'] = $empinfo['bank_code'];
					$stotal += round($row['net_income'],2);
					$nr++;
					$tot_salary += $salary;
				}
			}
		}
	}

?>

<style>
	table.codeTable {
		border-collapse:collapse;
		font-family: Courier New, Verdana;
	}
	table.codeTable td {
		color:#069;
		white-space:nowrap;
	}
	span.txt {
		font-family: Courier New, Verdana;
		font-size:12px;
		color:#069;
		padding-bottom:10px;
		display:block;
		white-space:pre-wrap;
	}
</style>

<div class="A4form" style="width:960px;padding:30px;">

	<ul class="nav nav-tabs" id="myTab">
		<li class="nav-item"><a class="nav-link active" data-target="#tab_payroll" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<!--<li><a data-target="#tab_other" data-toggle="tab">List Other accounts</a></li>
		<li><a data-target="#tab_bbl" data-toggle="tab">Textfile SCB accounts<? //=$lng['Text file']?></a></li>
		<li><a data-target="#tab_smart" data-toggle="tab">Textfile Other accounts<? //=$lng['Text file']?></a></li>-->
		<li class="nav-item">
			<select id="bank_filter">
				<option <? if($_GET['bank'] == 'all'){echo 'selected';}?> value="all"><?=$lng['All Bank accounts']?></option>
				<option <? if($_GET['bank'] == '024'){echo 'selected';}?> value="024"><?=$lng['Only UOB accounts']?></option>
				<option <? if($_GET['bank'] == 'other'){echo 'selected';}?> value="other"><?=$lng['Other Bank accounts']?></option>
			</select>
		</li>
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane show active" id="tab_payroll">
			
			<table width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600">
						UOB <?=$lng['Payment list']?>
					</td>
					<td style="padding-left:5px">
						<!--<a type="button" class="btn btn-primary btn-fr" href="export/download/download_uob_payment_list_excel.php?bank=<?=$_GET['bank']?>"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>-->
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=024"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?> List</a>
					</td>
				</tr>
			</table>
			
			<table class="basicTable">
				<thead>
					<tr>
						<th class="tac" style="width:10px">#</th>
						<th style="width:70%"><?=$lng['Account name']?></th>
						<th class="tar" style="min-width:110px"><?=$lng['Amount']?></th>
						<th class="tac"><?=$lng['Bank code']?></th>
						<th class="tac"><?=$lng['Branch']?></th>
						<th><?=$lng['Account']?></th>
					</tr>
				</thead>
				<tbody>
				<? if($other_data){ foreach($other_data as $k=> $v){ ?>
					<tr>
						<td class="tac"><?=$k?></td>
						<td><?=$v['name']?></td>
						<td class="tar"><?=$v['income']?></td>
						<td class="tac"><?=$v['code']?></td>
						<td class="tac"><?=$v['branch']?></td>
						<td><?=$v['account']?></td>
					</tr>
				<? }} ?>
					<tr>
						<td colspan="2" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
						<td class="tar" style="font-weight:600"><?=number_format($stotal,2)?></td>
						<td colspan="3"></td>
					</tr>
				</tbody>
			</table>

		</div>
		
		<div class="tab-pane" id="tab_bbl">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600; width:90%"><?=$lng['Text file']?> : SCB </td>
					<td style="padding-right:10px">
						<input id="prdate" placeholder="Select date" readonly style="display:inline-block; width:100px; cursor:pointer" type="text">
					</td>
					<td style="text-align:right">
						<button type="button" class="btn btn-primary btn-sm" id="payrollDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><? //=$payroll_txt?></span>
		</div>
		
		<div class="tab-pane" id="tab_smart">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600; width:90%"><?=$lng['Text file']?> : <?=$lng['Other accounts']?></td>
					
					<td style="padding-right:10px">
						<input id="smdate" placeholder="Select date" readonly style="display:inline-block; width:100px; cursor:pointer" type="text">
					</td>
					<td>
						<button type="button" class="btn btn-primary btn-sm" id="smartDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><? //=$smart_txt?></span>
		</div>
		
	</div>

</div>

<script>

	$(document).ready(function() {
		
		$('#bank_filter').on('change', function(){
			window.location.href = 'index.php?mn=420&sm=50&bank=' + this.value;
		})
	
		$('#payrollDownload').on('click', function(){
			var date = $('#prdate').val();
			if(date == ''){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select payment date<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				return false;
			}
			window.location.href = ROOT+'payroll/export/textfiles/download_bbl_payroll_textfile.php?date=' + date;
		})
	
		$('#smartDownload').on('click', function(){
			var bank = $('#bank_filter').val();
			var date = $('#smdate').val();
			if(date == ''){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select payment date<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				return false;
			}
			window.location.href = ROOT+'payroll/export/textfiles/download_bbl_smart_textfile.php?bank=' + bank + '&date=' + date;
		})
	
		var activeTab = localStorage.getItem('activeTabExp');
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_payroll"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabExp', $(e.target).data('target'));
		});
	
	});
	
</script>







