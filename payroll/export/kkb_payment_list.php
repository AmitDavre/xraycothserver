<?php

	if(session_id()==''){session_start();}
	ob_start(); //004
	
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

	$pattern = '%%%-%-%%%%%-%';
	$tot_salary = 0;
	$total = 0;
	$prdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '004', 'all');
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){
						$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);
					}
					$name = preg_replace('!\s+!', ' ', $name);
					$prdata[$row['emp_id']]['name'] = $name;
					$account = str_replace('-', '', $empinfo['bank_account']);
					$prdata[$row['emp_id']]['account'] = $account;
					$prdata[$row['emp_id']]['salary'] = number_format($row['net_income'],2);
					$tmp = round($row['net_income'],2);
					$salary = $tmp * 100;
					$prdata[$row['emp_id']]['salary'] = $salary;
					$tot_salary += $salary;
					$data[$row['emp_id']]['account'] = $account;
					if(strlen($account) == 10){
						$data[$row['emp_id']]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					$data[$row['emp_id']]['code'] = sprintf("%04d", substr($account,0,3));
					$data[$row['emp_id']]['name'] = $name;
					$data[$row['emp_id']]['income'] = number_format($row['net_income'],2);
					$total += round($row['net_income'],2);
				
				}
			}
		}
	}

	$payroll_txt = "HDCT".str_repeat(' ', 4);
	$payroll_txt .= "000000".str_repeat(' ', 4);
	$payroll_txt .= $_account.' ';
	$payroll_txt .= sprintf("%015d",$tot_salary);
	$payroll_txt .= " 201030".str_repeat(' ', 4);
	$payroll_txt .= $compname.str_repeat(' ', (35 - mb_strlen($compname)));
	$payroll_txt .= date('ymd').sprintf("%018d", count($prdata)).'N'; // N = Fee Payer  - Y = Fee Beneficiary
	$payroll_txt .= "\r\n";

	$nr = 1;
	foreach($prdata as $v){
		$payroll_txt .= "D".sprintf("%06d", $nr).str_repeat(' ', 8);
		$payroll_txt .= $v['account'].' ';
		$payroll_txt .= sprintf("%015d",$v['salary']).' 201030'.str_repeat(' ', 8);
		$payroll_txt .= $v['name'].str_repeat(' ', (45 - mb_strlen($v['name'])));
		$payroll_txt .= date('ymd').str_repeat('0', 4).'1234';
		$payroll_txt .= "\r\n";
		$nr++;
	}
	
	
	//$txt = 'Not available yet';

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

<div class="A4form" style="width:960px; padding:30px;">

	<ul class="nav nav-tabs" id="myTab">
		<li class="nav-item"><a class="nav-link active" data-target="#tab_list" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_upload" data-toggle="tab"><?=$lng['Text file']?></a></li>
		<!--<li><a data-target="#tab_other" data-toggle="tab">Other file<? //=$lng['Personal data']?></a></li>-->
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane show active" id="tab_list">
			<table style="width:100%; margin-bottom:10px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['Kasikorn Bank']?> <?=$lng['Payment list']?></td>
					<td style="text-align:right">
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_kkb_payment_list_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=004"><i class="fa fa-print"></i>&nbsp;&nbsp;<?=$lng['Print list']?></a>
					</td>
				</tr>
			</table>
			
	<table class="basicTable">
		<thead>
			<tr>
				<th class="tac" style="width:10px">#</th>
				<th><?=$lng['Account']?></th>
				<th><?=$lng['Branch']?></th>
				<th style="width:70%"><?=$lng['Account name']?></th>
				<th class="tar"><?=$lng['Amount']?></th>
			</tr>
		</thead>
		<tbody>
		<? if($data){ foreach($data as $k=> $v){ ?>
			<tr>
				<td class="tac"><?=$k?></td>
				<td><?=$v['account']?></td>
				<td><?=$v['code']?></td>
				<td><?=$v['name']?></td>
				<td class="tar"><?=$v['income']?></td>
			</tr>
		<? }} ?>
			<tr>
				<td colspan="4" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
				<td class="tar" style="font-weight:600"><?=number_format($total,2)?></td>
			</tr>
		</tbody>
	</table>

		</div>
		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:5px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['Kasikorn Bank']?> <?=$lng['Text file']?></td>
					<td style="text-align:right">
						<button xdisabled type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_kkb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$payroll_txt?></span>
		</div>
		
		<div class="tab-pane" id="tab_other">
			Other file
		</div>
		
	</div>

</div>






