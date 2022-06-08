<?php

	if(session_id()==''){session_start();}
	ob_start(); //002
	
	mb_internal_encoding('UTF-8');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	$pattern = '%%%-%-%%%%%-%';
	
	$_account = str_replace('-', '', $banks['002']['number']);
	$bank_account = $banks['002']['number'];
	
	$tmp = $banks['002']['name'];
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
	$payroll_txt .= ' 002';
	$payroll_txt .= ' '.sprintf("%010d", $_account);
	$payroll_txt .= ' '.$compname;
	$payroll_txt .= ' <span id="pDate">ddmmyy</span>';//date('dmy');
	$payroll_txt .= ' '.str_repeat('0', 57);
	$payroll_txt .= '<br>';
	echo '<pre>';
	$nr = 1;
	$tot_salary = 0;
	$ptotal = 0;
	$stotal = 0;
	$pdata = array();
	//$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', '002');
				//var_dump($empinfo); 
				if($empinfo){
					if($empinfo['bank_code'] == '002'){
						
						$tmp = round($row['net_income'],2);
						$salary = $tmp * 100;
						
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
						$account = str_replace('-', '', $empinfo['bank_account']);
						
						$pdata[$nr]['account'] = $account;
						if(strlen($account) == 10){
							$pdata[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
						}
						$pdata[$nr]['name'] = $name;
						$pdata[$nr]['income'] = number_format($row['net_income'],2);
						$pdata[$nr]['branch'] = $empinfo['bank_branch'];
						$pdata[$nr]['code'] = $empinfo['bank_code'];
						//$ptotal += round($row['net_income'],2);
						$ptotal += $tmp;
						
						$name = preg_replace('!\s+!', ' ', $name);
						$payroll_txt .= 'D';
						$payroll_txt .= ' '.sprintf("%06d",$nr+1);
						$payroll_txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
						$payroll_txt .= ' '.sprintf("%010d", $account);
						
						$payroll_txt .= ' C'.str_repeat('0', (10 - mb_strlen($salary)));
						$payroll_txt .= $salary;
						$payroll_txt .= ' 029'; // Payroll service
						$payroll_txt .= ' '.str_repeat('0', 30);
						$payroll_txt .= ' '.$name.str_repeat(' ', (35 - mb_strlen($name)));
						$payroll_txt .= '<br>';
						$nr++;
						$tot_salary += $salary;
					}
				}
			}
			$payroll_txt .= 'T';
			$payroll_txt .= ' '.sprintf("%06d",$nr+1);
			$payroll_txt .= ' 002';
			$payroll_txt .= ' '.sprintf("%010d", $_account);
			$payroll_txt .= ' '.sprintf("%027d",($nr-1));
			$payroll_txt .= ' '.str_repeat('0', (13 - mb_strlen($tot_salary)));
			$payroll_txt .= $tot_salary;
			$payroll_txt .= ' '.str_repeat('0', 48);
		}
	}
	//var_dump($tot_salary);
	echo '</pre>';
	$smart_txt = 'H ';
	$smart_txt .= sprintf("%06d",1);
	$smart_txt .= ' 002';
	$smart_txt .= ' 0000';
	$smart_txt .= ' '.sprintf("%011d", $_account);
	$smart_txt .= ' '.$compname;
	$smart_txt .= ' <span id="sDate">ddmmyy</span>';
	$smart_txt .= ' UN1 ';
	$smart_txt .= ' '.str_repeat('0', 48);
	$smart_txt .= '<br>';
	
	$nr = 1;
	$tot_salary = 0;
	$sdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', $_GET['bank']);
				if($empinfo){
					$tmp = round($row['net_income'],2);
					$salary = $tmp * 100;
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$account = str_replace('-', '', $empinfo['bank_account']);
					
					$sdata[$nr]['account'] = $account;
					if(strlen($account) == 10){
						$sdata[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					$sdata[$nr]['name'] = $name;
					$sdata[$nr]['income'] = number_format($row['net_income'],2);
					$sdata[$nr]['branch'] = $empinfo['bank_branch'];
					$sdata[$nr]['code'] = $empinfo['bank_code'];
					$stotal += round($row['net_income'],2);
					
					$name = preg_replace('!\s+!', ' ', $name);
					$smart_txt .= 'D ';
					$smart_txt .= sprintf("%06d",$nr+1);
					$smart_txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
					$smart_txt .= ' '.sprintf("%04d", substr($account,0,3));
					$smart_txt .= ' '.sprintf("%011d", $account);
					$smart_txt .= ' C'.str_repeat('0', (12 - mb_strlen($salary)));
					$smart_txt .= $salary;
					$smart_txt .= ' 01'; // Payroll service
					$smart_txt .= ' '.$name.str_repeat(' ', (30 - mb_strlen($name)));
					$smart_txt .= '<br>';
					$nr++;
					$tot_salary += $salary;
				}
			}
			$smart_txt .= 'T '.sprintf("%06d",$nr+1);
			$smart_txt .= ' 002';
			$smart_txt .= ' '.sprintf("%011d", $_account);
			$smart_txt .= ' '.sprintf("%07d",'0');
			$smart_txt .= ' '.sprintf("%013d",'0');
			$smart_txt .= ' '.sprintf("%07d",($nr-1));
			$smart_txt .= str_repeat('0', (13 - mb_strlen($tot_salary)));
			$smart_txt .= $tot_salary;
			$smart_txt .= ' '.str_repeat('0', 48);
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
		<li class="nav-item"><a class="nav-link" data-target="#tab_payroll" data-toggle="tab"><?=$lng['List BBL accounts']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_other" data-toggle="tab"><?=$lng['List Other accounts']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_bbl" data-toggle="tab"><?=$lng['Textfile BBL accounts']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_smart" data-toggle="tab"><?=$lng['Textfile Other accounts']?></a></li>
		<li class="nav-item">
			<select id="bank_filter">
				<option <? if($_GET['bank'] == 'all'){echo 'selected';}?> value="all"><?=$lng['All Bank accounts']?></option>
				<option <? if($_GET['bank'] == '002'){echo 'selected';}?> value="002"><?=$lng['Only Bankok Bank accounts']?></option>
				<option <? if($_GET['bank'] == 'other'){echo 'selected';}?> value="other"><?=$lng['Other Bank accounts']?></option>
			</select>
		</li>
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane" id="tab_payroll">
			<table width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600">
						<?=$lng['Bangkok Bank']?> <?=$lng['Payment list']?> (PAYROLL)
					</td>
					<td style="padding-left:5px">
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_bbl_payroll_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=002"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?></a>
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
					<td style="font-size:18px; font-weight:600">
						<?=$lng['Bangkok Bank']?> <?=$lng['Payment list']?> (SMART)
					</td>
					<td style="padding-left:5px">
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_bbl_smart_excel.php?bank=<?=$_GET['bank']?>"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=002"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?></a>
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
					<td style="font-size:18px; font-weight:600"><?=$lng['Bangkok Bank']?> <?=$lng['Payroll']?></td>
					<td>
						<button type="button" class="btn btn-primary btn-fr" id="payrollDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
						<input id="prdate" placeholder="<?=$lng['Select date']?>" readonly style="display:inline-block; width:100px; cursor:pointer; float:right" type="text">
					</td>
				</tr>
			</table>
			<span class="txt"><?=$payroll_txt?></span>
		</div>
		
		<div class="tab-pane" id="tab_smart">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['Bangkok Bank']?> Smartpay<? //=$lng['xxx']?></td>
					
					<td>
						<button type="button" class="btn btn-primary btn-fr" id="smartDownload"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
						<input id="smdate" placeholder="<?=$lng['Select date']?>" readonly style="display:inline-block; width:100px; cursor:pointer; float:right" type="text">
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
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please select payment date']?>',
					duration: 4,
				})
				return false;
			}
			window.location.href = 'export/download/download_bbl_payroll_textfile.php?date=' + date;
		})
	
		$('#smartDownload').on('click', function(){
			var bank = $('#bank_filter').val();
			var date = $('#smdate').val();
			if(date == ''){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please select payment date']?>',
					duration: 4,
				})
				return false;
			}
			window.location.href = 'export/download/download_bbl_smart_textfile.php?bank=' + bank + '&date=' + date;
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







