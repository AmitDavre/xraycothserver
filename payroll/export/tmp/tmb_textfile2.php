<?php

	if(session_id()==''){session_start(); ob_start();}
	
	$account = str_replace('-', '', $compinfo['bank_account']);
	$compname = substr($compinfo['en_compname'], 0, 60);
	$txt = '';
	$nr = 1; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				
				$tmp = number_format($row['net_income'],2);
				$tmp = str_replace(',','',$tmp);
				$salary = str_replace('.','',$tmp);
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$name = trim($empinfo['bank_account_name']);
				if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
				$name = preg_replace('!\s+!', ' ', $name);
				$len = strlen($name);
				
				$txt .= '102100001';

				$txt .= sprintf("%03d",$empinfo['bank_code']);
				$txt .= sprintf("%04d", substr($empinfo['bank_account'],0,3));
				$txt .= sprintf("%011d", $empinfo['bank_account']);
				
				$txt .= sprintf("%03d",$compinfo['bank_name']);
				$txt .= sprintf("%04d", substr($account,0,3));
				$txt .= sprintf("%011d", $account);
				$txt .= date('dmY', strtotime($_SESSION['rego']['paydate']));
				$txt .= '01';
				$txt .= sprintf("%014d",$salary);
				$txt .= $name.str_repeat('', (60-strlen($name)));
				
				$txt .= $compname;
				if(strlen($compname) < 60){
					$txt .= str_repeat(' ', 60 - strlen($compname));
				}
				$txt .= '0000000000';
				$txt .= str_repeat(' ', 90);
				$txt .= sprintf("%06d",$nr);
				$txt .= 'GI';

				$txt .= '<hr style="margin:2px 0 5px 0">';
				
				$nr++;
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

<div class="A4form" style="width:1100px;xheight:1260px; padding:30px;">

	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a data-target="#tab_list" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<li><a data-target="#tab_upload" data-toggle="tab"><?=$lng['Text file']?></a></li>
		<!--<li><a data-target="#tab_other" data-toggle="tab">Other file<? //=$lng['Personal data']?></a></li>-->
	</ul>
	
	<div class="tab-content autoheight" style="padding:10px; min-height:400px; border:1px #ccc solid; border-top:0">
		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600"><?=$lng['TMB Bank']?> <?=$lng['Text file']?> </td>
					<td style="text-align:right">
						<button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='<?=ROOT?>payroll/export/textfiles/download_tmb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
		<div class="tab-pane active" id="tab_list">
			<table style="width:100%; margin-bottom:10px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600"><?=$lng['TMB Bank']?> <?=$lng['Payment list']?></td>
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









