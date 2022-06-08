<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	$pattern = '%%%-%-%%%%%-%';
	
	$com_account = str_replace('-', '', $compinfo['bank_account']);
	$tmp = $compinfo['bank_acc_name'];
	if($lang == 'en'){
		$tmp = preg_replace("/[^a-zA-Z0-9\s]/", "", $tmp);
		$tmp = preg_replace('/\s+/', ' ',$tmp);
	}
	$compname = mb_substr($tmp, 0, 25);
	if(mb_strlen($compname) < 25){
		$compname .= str_repeat(' ', 25 - mb_strlen($compname));
	}
	
	$payroll_txt = 'H';
	$payroll_txt .= ' '.sprintf("%06d",1);
	$payroll_txt .= ' '.sprintf("%03d",$compinfo['bank_name']);
	$payroll_txt .= ' '.sprintf("%010d", $com_account);
	$payroll_txt .= ' '.$compname;
	$payroll_txt .= ' <span id="pDate">ddmmyy</span>';//date('dmy');
	$payroll_txt .= ' '.str_repeat('0', 57);
	$payroll_txt .= '<br>';
	
	$nr = 1;
	$tot_salary = 0;
	$ptotal = 0;
	$stotal = 0;
	$pdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$tmp = round($row['net_income'],2);
				$salary = $tmp * 100;
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', '002');
				if($empinfo){
					if($empinfo['bank_code'] == '002'){
						
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
						$account = str_replace('-', '', $empinfo['bank_account']);
						
						$pdata[$nr]['account'] = $account;
						if(strlen($account) == 10){
							$data[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
						}
						$pdata[$nr]['name'] = $name;
						$pdata[$nr]['income'] = number_format($row['net_income'],2);
						$pdata[$nr]['branch'] = $empinfo['bank_branch'];
						$pdata[$nr]['code'] = $empinfo['bank_code'];
						$ptotal += round($row['net_income'],2);
						
						$name = preg_replace('!\s+!', ' ', $name);
						$payroll_txt .= 'D';
						$payroll_txt .= ' '.sprintf("%06d",$nr);
						$payroll_txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
						$payroll_txt .= ' '.sprintf("%010d", $account);
						$payroll_txt .= ' C'.sprintf("%010d",$salary).'029'; // Payroll service
						$payroll_txt .= ' '.str_repeat('0', 30);
						$payroll_txt .= ' '.$name.str_repeat(' ', (35 - mb_strlen($name)));
						$payroll_txt .= '<br>';
						$nr++;
						$tot_salary += $salary;
					}
				}
			}
			$payroll_txt .= 'T';
			$payroll_txt .= ' '.sprintf("%06d",$nr);
			$payroll_txt .= ' '.sprintf("%03d",$compinfo['bank_name']);
			$payroll_txt .= ' '.sprintf("%010d", $com_account);
			$payroll_txt .= ' '.sprintf("%027d",($nr-2));
			$payroll_txt .= ' '.sprintf("%013d",$tot_salary);
			$payroll_txt .= ' '.str_repeat('0', 48);
		}
	}
	//var_dump($pdata); 
	
	$smart_txt = 'H ';
	$smart_txt .= sprintf("%06d",1);
	$smart_txt .= ' 002';
	$smart_txt .= ' 0000';
	$smart_txt .= ' '.sprintf("%011d", $account);
	$smart_txt .= ' '.$compname;
	$smart_txt .= ' <span id="sDate">ddmmyy</span>';
	$smart_txt .= ' UN1 ';
	$smart_txt .= ' '.str_repeat('0', 48);
	$smart_txt .= '<br>';
	
	$nr = 1;
	$tot_salary = 0;
	$sdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$tmp = round($row['net_income'],2);
				$salary = $tmp * 100;
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', $_GET['bank']);
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$account = str_replace('-', '', $empinfo['bank_account']);
					
					$sdata[$nr]['account'] = $account;
					if(strlen($account) == 10){
						$data[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					$sdata[$nr]['name'] = $name;
					$sdata[$nr]['income'] = number_format($row['net_income'],2);
					$sdata[$nr]['branch'] = $empinfo['bank_branch'];
					$sdata[$nr]['code'] = $empinfo['bank_code'];
					$stotal += round($row['net_income'],2);
					
					$name = preg_replace('!\s+!', ' ', $name);
					$smart_txt .= 'D ';
					$smart_txt .= sprintf("%06d",$nr);
					$smart_txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
					$smart_txt .= ' '.sprintf("%04d", substr($account,0,3));
					$smart_txt .= ' '.sprintf("%011d", $account);
					$smart_txt .= ' C';
					$smart_txt .= ' '.sprintf("%012d",$salary);
					$smart_txt .= ' 01'; // Payroll service
					$smart_txt .= ' '.$name.str_repeat(' ', (30 - mb_strlen($name)));
					$smart_txt .= '<br>';
					$nr++;
					$tot_salary += $salary;
				}
			}
			$smart_txt .= 'T '.sprintf("%06d",$nr);
			$smart_txt .= ' '.sprintf("%03d",$compinfo['bank_name']);
			$smart_txt .= ' '.sprintf("%011d", $account);
			$smart_txt .= ' '.sprintf("%07d",'0');
			$smart_txt .= ' '.sprintf("%013d",'0');
			$smart_txt .= ' '.sprintf("%07d",($nr-2));
			$smart_txt .= ' '.sprintf("%013d",$tot_salary);
			$smart_txt .= ' '.str_repeat('0', 47);
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
		<li><a data-target="#tab_payroll" data-toggle="tab">List BBL accounts</a></li>
		<li><a data-target="#tab_other" data-toggle="tab">List Other accounts</a></li>
		<li><a data-target="#tab_bbl" data-toggle="tab">Textfile BBL accounts<? //=$lng['Text file']?></a></li>
		<li><a data-target="#tab_smart" data-toggle="tab">Textfile Other accounts<? //=$lng['Text file']?></a></li>
		<li style="float:right">
			<select id="bank_filter">
				<option <? if($_GET['bank'] == 'all'){echo 'selected';}?> value="all">All Bank accounts</option>
				<option <? if($_GET['bank'] == '002'){echo 'selected';}?> value="002">Only Bankok Bank accounts</option>
				<option <? if($_GET['bank'] == 'other'){echo 'selected';}?> value="other">Other Bank accounts</option>
			</select>
		</li>
	</ul>
	
	<div class="tab-content" style="padding:10px; min-height:400px; border:1px #ccc solid; border-top:0">
		
		<div class="tab-pane" id="tab_payroll">
			<table width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600; width:90%">
						<?=$lng['Bangkok Bank']?> <?=$lng['Payment list']?> (PAYROLL)
					</td>
					<td style="padding-left:5px">
						<a type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/download/bbl_payroll_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; Download Excel file<? //=$lng['Print']?></a>
					</td>
					<td style="padding-left:5px">
						<a target="_blank" type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/print_paymentlist.php"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?> List</a>
					</td>
				</tr>
			</table>
			
			<table class="basicTable">
				<thead>
					<tr>
						<th class="tac" style="width:10px">#</th>
						<th style="width:70%"><?=$lng['Account name']?></th>
						<th style="min-width:110px"><?=$lng['Account']?></th>
						<th class="tar"><?=$lng['Amount']?></th>
					</tr>
				</thead>
				<tbody>
				<? if($pdata){ foreach($pdata as $k=> $v){ ?>
					<tr>
						<td class="tac"><?=$k?></td>
						<td><?=$v['name']?></td>
						<td><?=$v['account']?></td>
						<td class="tar"><?=$v['income']?></td>
					</tr>
				<? }} ?>
					<tr>
						<td colspan="3" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
						<td class="tar" style="font-weight:600"><?=number_format($ptotal,2)?></td>
					</tr>
				</tbody>
			</table>

		</div>
		
		<div class="tab-pane" id="tab_other">
			
			<table width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600; width:90%">
						<?=$lng['Bangkok Bank']?> <?=$lng['Payment list']?> (SMART)
					</td>
					<td style="padding-left:5px">
						<a type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/download/bbl_smart_excel.php?bank=<?=$_GET['bank']?>"><i class="fa fa-file-excel-o"></i>&nbsp; Download Excel file<? //=$lng['Print']?></a>
					</td>
					<td style="padding-left:5px">
						<a target="_blank" type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/print_paymentlist.php"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?> List</a>
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
				<? if($sdata){ foreach($sdata as $k=> $v){ ?>
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
					<td style="font-size:20px; font-weight:600; width:90%"><?=$lng['Bangkok Bank']?> <?=$lng['Payroll']?></td>
					<td style="padding-right:10px">
						<input id="prdate" placeholder="Select date" readonly style="display:inline-block; width:100px; cursor:pointer" type="text">
					</td>
					<td style="text-align:right">
						<button type="button" class="btn btn-primary btn-sm" id="payrollDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$payroll_txt?></span>
		</div>
		
		<div class="tab-pane" id="tab_smart">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600; width:90%"><?=$lng['Bangkok Bank']?> Smartpay<? //=$lng['Text file']?></td>
					
					<td style="padding-right:10px">
						<input id="smdate" placeholder="Select date" readonly style="display:inline-block; width:100px; cursor:pointer" type="text">
					</td>
					<td>
						<button type="button" class="btn btn-primary btn-sm" id="smartDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$smart_txt?></span>
		</div>
		
	</div>

</div>

<script>

	$(document).ready(function() {
		
		$('#bank_filter').on('change', function(){
			window.location.href = 'index.php?mn=420&sm=45&bank=' + this.value;
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
			window.location.href = ROOT+'payroll/export/download/download_bbl_payroll_textfile.php?date=' + date;
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
			window.location.href = ROOT+'payroll/export/download/download_bbl_smart_textfile.php?bank=' + bank + '&date=' + date;
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







