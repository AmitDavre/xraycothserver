<?php

	if(session_id()==''){session_start();}
	ob_start(); //025

	$tot_salary = 0;
	
	$_account = str_replace('-', '', $banks['025']['number']);
	$bank_account = $banks['025']['number'];
	$compname = substr($banks['025']['name'], 0, 25);
	
	$nr = 2; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				//$data[] = $row;
				$tmp = round($row['net_income'],2);
				//$tmp = str_replace(',','',$tmp);
				$salary = str_replace('.','',$tmp);
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$name = trim($empinfo['bank_account_name']);
				if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
				$name = preg_replace('!\s+!', ' ', $name);
				$len = strlen($name);
				$nr++;
				$tot_salary += round($row['net_income'],2);
				//var_dump($tot_salary);
			}
			$tot_salary = str_replace('.','',$tot_salary);
			//$txt .= '<br>';
		}
	}
	$txt = $lng['Not available yet'];

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

<div class="A4form" style="width:1100px;">

	<ul class="nav nav-tabs" id="myTab">
		<li class="nav-item"><a class="nav-link active" data-target="#tab_list" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_upload" data-toggle="tab"><?=$lng['Text file']?></a></li>
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['Kungsri Bank']?> <?=$lng['Text file']?></td>
					<td style="text-align:right">
						<button disabled type="button" class="btn btn-primary btn-fr" onclick="window.location.href='../textfiles/download_bkb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
		<div class="tab-pane show active" id="tab_list">
			<table style="width:100%; margin-bottom:10px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['Kungsri Bank']?> <?=$lng['Payment list']?></td>
					<td style="text-align:right">
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=025"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?></a>
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









