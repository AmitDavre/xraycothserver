<?php

	if(session_id()==''){session_start();}
	ob_start();
	mb_internal_encoding('UTF-8');
	//echo strlen('00000000000000000000000000000000000000000000000000000000000');
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	$tot_salary = 0;
	
	$account = str_replace('-', '', $compinfo['bank_account']);
	$txt = 'H'.sprintf("%06d",1);
	$txt .= sprintf("%03d",$compinfo['bank_name']);
	$txt .= sprintf("%010d", $account);
	$tmp = $compinfo[$lang.'_compname'];
	if($lang == 'en'){
		$tmp = preg_replace("/[^a-zA-Z0-9\s]/", "", $compinfo[$lang.'_compname']);
		$tmp = preg_replace('/\s+/', ' ',$tmp);
	}
	//$res = $compinfo[$lang.'_compname'];
	$compname = mb_substr($tmp, 0, 25);
	$txt .= $compname;
	if(mb_strlen($compname) < 25){
		$txt .= str_repeat(' ', 25 - mb_strlen($compname));
	}
	$txt .= date('dmy');
	$txt .= str_repeat('0', 77);
	$txt .= '<br>';
	
	$nr = 2; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$tmp = round($row['net_income'],2);
				$salary = $tmp * 100;
				$empinfo = getEmployeesByBank($cid, $row['emp_id']);
				//var_dump($empinfo);
				if(($_GET['bank'] == '002' && $empinfo['bank_code'] == '002') || $_GET['bank'] == 'all'){
					$name = '';//trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$name = preg_replace('!\s+!', ' ', $name);
					$len = mb_strlen($name);
					$txt .= 'D'.sprintf("%06d",$nr);
					$txt .= sprintf("%03d",$empinfo['bank_code']);
					$txt .= sprintf("%010d", $empinfo['bank_account']);
					$txt .= 'C'.sprintf("%010d",$salary).'029'; // Payroll service
					$txt .= str_repeat('0', 59);
					$txt .= $name.str_repeat(' ', (35 - $len));
					$txt .= '<br>';
					$nr++;
					$tot_salary += round($row['net_income'],2);
				}elseif($_GET['bank'] == 'other' && $empinfo['bank_code'] != '002'){
					$name = '';//trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$name = preg_replace('!\s+!', ' ', $name);
					$len = mb_strlen($name);
					$txt .= 'D'.sprintf("%06d",$nr);
					$txt .= sprintf("%03d",$empinfo['bank_code']);
					$txt .= sprintf("%010d", $empinfo['bank_account']);
					$txt .= 'C'.sprintf("%010d",$salary).'029'; // Payroll service
					$txt .= str_repeat('0', 59);
					$txt .= $name.str_repeat(' ', (35 - $len));
					$txt .= '<br>';
					$nr++;
					$tot_salary += round($row['net_income'],2);
				}
			}
			$tot_salary = str_replace('.','',$tot_salary);
			$txt .= 'T'.sprintf("%06d",$nr);
			$txt .= sprintf("%03d",$compinfo['bank_name']);
			$txt .= sprintf("%010d", $account);
			$txt .= sprintf("%027d",($nr-2));
			$txt .= sprintf("%013d",$tot_salary);
			$txt .= str_repeat('0', 68);
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

<div class="A4form" style="width:1100px;xheight:1260px; padding:30px;">

	<ul class="nav nav-tabs" id="myTab">
		<li><a data-target="#tab_list" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<li><a data-target="#tab_upload" data-toggle="tab"><?=$lng['Text file']?></a></li>
		<li style="float:right">
			<select id="bank_filter">
				<option <? if($_GET['bank'] == 'all'){echo 'selected';}?> value="all">All Bank accounts</option>
				<option <? if($_GET['bank'] == '002'){echo 'selected';}?> value="002">Only Bankok Bank accounts</option>
				<option <? if($_GET['bank'] == 'other'){echo 'selected';}?> value="other">Other Bank accounts</option>
			</select>
		</li>
	</ul>
	
	<div class="tab-content autoheight" style="padding:10px; min-height:400px; border:1px #ccc solid; border-top:0">
		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600"><?=$lng['Bangkok Bank']?> <?=$lng['Text file']?></td>
					<td style="text-align:right">
						<button type="button" class="btn btn-primary btn-sm" id="txtDownload" onclick="window.location.href='<?=ROOT?>payroll/export/textfiles/download_bkb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
		<div class="tab-pane" id="tab_list">
			<table style="width:100%; margin-bottom:10px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600"><?=$lng['Bangkok Bank']?> <?=$lng['Payment list']?></td>
					<td style="text-align:right">
						<a target="_blank" type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/print_paymentlist.php"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></a>
					</td>
				</tr>
			</table>

			<? include('payment_list.php');?>

		</div>
		
		<div class="tab-pane" id="tab_other">
			Other file
		</div>
		
	</div>

</div>

<script>

	$(document).ready(function() {
		
		var bank = <?=json_encode($_GET['bank'])?>;
		
		$('#bank_filter').on('change', function(){
			window.location.href = 'index.php?mn=420&sm=45&bank=' + this.value;
		})
	
		$('#txtDownload').on('click', function(){
			window.location.href = ROOT+'payroll/export/textfiles/download_bkb_textfile.php?bank=' + bank;
		})
	
		var activeTab = localStorage.getItem('activeTabExp');
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_list"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabExp', $(e.target).data('target'));
		});
	
	});
	
</script>







