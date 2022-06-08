<?php

	if(session_id()==''){session_start();}
	ob_start();

	$tot_salary = 0;
	
	$account = str_replace('-', '', $compinfo['bank_account']);
	$txt = 'H'.sprintf("%06d",1);
	$txt .= sprintf("%03d",$compinfo['bank_name']);
	$txt .= sprintf("%04d", substr($account,0,3));
	$txt .= sprintf("%011d", $account);
	$compname = substr($compinfo['en_compname'], 0, 25);
	$txt .= $compname;
	if(strlen($compname) < 25){
		$txt .= str_repeat(' ', 25 - strlen($compname));
	}
	$txt .= date('dmy', strtotime($_SESSION['rego']['paydate']));
	$txt .= 'UN1D';
	$txt .= str_repeat('0', 68);
	$txt .= '<br>';
	
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
				$txt .= 'D'.sprintf("%06d",$nr);
				$txt .= sprintf("%03d",$empinfo['bank_code']);
				$txt .= sprintf("%04d", substr($empinfo['bank_account'],0,3));
				$txt .= sprintf("%011d", $empinfo['bank_account']);
				//$txt .= 'D '.sprintf("%04d",$empinfo['bank_code']).' 0020 '.substr($empinfo['bank_account'],0,3).' 0 ';
				//$txt .= vsprintf(str_replace('%','%s',$pattern),str_split($empinfo['bank_account']));
				$txt .= 'C'.sprintf("%012d",$salary).'01'; // Payroll service
				$txt .= $name.str_repeat(' ', (58-strlen($name)));
				//$txt .= 'x'.strtoupper($name).'x ';
				$txt .= str_repeat('0', 30);
				$txt .= '<br>';
				$nr++;
				$tot_salary += round($row['net_income'],2);
				//var_dump($tot_salary);
			}
			$tot_salary = str_replace('.','',$tot_salary);
			//$txt .= '<br>';
			$txt .= 'T'.sprintf("%06d",$nr);
			$txt .= sprintf("%03d",$compinfo['bank_name']);
			$txt .= sprintf("%011d", $account);
			$txt .= sprintf("%027d",($nr-2));
			$txt .= sprintf("%013d",$tot_salary);
			$txt .= str_repeat('0', 67);
		}
	}
	$txt = 'Not available yet';
	
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
					<td style="font-size:20px; font-weight:600"><?=$lng['Krungthai Bank']?> <?=$lng['Text file']?></td>
					<td style="text-align:right">
						<button disabled type="button" class="btn btn-primary btn-sm" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_ktb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
		<div class="tab-pane active" id="tab_list">
			<table style="width:100%; margin-bottom:10px;" border="0">
				<tr>
					<td style="font-size:20px; font-weight:600"><?=$lng['Krungthai Bank']?> <?=$lng['Payment list']?></td>
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









