<?php

	if(session_id()==''){session_start(); ob_start();}
	$pattern = '%%%-%-%%%%%-%';
	$account = str_replace('-', '', $compinfo['bank_account']);
	$compname = substr($compinfo['en_compname'], 0, 60);
	$txt = '';
	$data = array();
	$total = 0;
	$nr = 1; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				
				$name = trim($empinfo['bank_account_name']);
				if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
				$account = str_replace('-', '', $empinfo['bank_account']);
				
				$data[$nr]['account'] = $account;
				if(strlen($account) == 10){
					$data[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
				}
				$data[$nr]['name'] = $name;
				$data[$nr]['income'] = number_format($row['net_income'],2);
				$data[$nr]['branch'] = $empinfo['bank_branch'];
				$data[$nr]['code'] = $empinfo['bank_code'];
				$total += round($row['net_income'],2);
				
				$tmp = number_format($row['net_income'],2);
				$tmp = str_replace(',','',$tmp);
				$salary = str_replace('.','',$tmp);
				
				$name = trim($empinfo['bank_account_name']);
				if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
				$name = preg_replace('!\s+!', ' ', $name);
				$len = strlen($name);
				
				$txt .= '102100001';

				$txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
				$txt .= ' '.sprintf("%04d", substr($empinfo['bank_account'],0,3));
				$txt .= ' '.sprintf("%011d", $empinfo['bank_account']);
				
				$txt .= ' '.sprintf("%03d",$compinfo['bank_name']);
				$txt .= ' '.sprintf("%04d", substr($account,0,3));
				$txt .= ' '.sprintf("%011d", $account);
				$txt .= ' '.date('dmY', strtotime($_SESSION['rego']['paydate']));
				$txt .= ' 01';
				$txt .= ' '.sprintf("%014d",$salary);
				$txt .= ' '.$name.str_repeat('', (60-strlen($name)));
				
				$txt .= '<br>'.$compname;
				if(strlen($compname) < 60){
					//$txt .= str_repeat(' ', 60 - strlen($compname));
				}
				$txt .= ' 0000000000';
				//$txt .= str_repeat(' ', 90);
				$txt .= ' '.sprintf("%06d",$nr);
				$txt .= ' GI';

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

<div class="A4form" style="width:960px;padding:30px;">

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
						<button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_tmb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
		<div class="tab-pane active" id="tab_list">
			<table border="0" width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600; width:90%">
						<?=$lng['TMB Bank']?> <?=$lng['Payment list']?>
					</td>
					<td style="padding-left:5px">
						<a type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/download/tmb_paylist_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; Download Excel file<? //=$lng['Print']?></a>
					</td>
					<td style="padding-left:5px">
						<a target="_blank" type="button" class="btn btn-primary btn-sm" href="<?=ROOT?>payroll/export/print_paymentlist.php"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?> List</a>
					</td>
				</tr>
			</table>
			
			<table border="0" class="basicTable" width="100%">
				<thead>
					<tr>
						<th class="tac" style="width:10px">#</th>
						<th style="width:70%"><?=$lng['Account name']?></th>
						<th style="min-width:110px"><?=$lng['Account']?></th>
						<th class="tar" style="min-width:110px"><?=$lng['Amount']?></th>
						<th class="tar"><?=$lng['Bank code']?></th>
					</tr>
				</thead>
				<tbody>
				<? if($data){ foreach($data as $k=> $v){ ?>
					<tr>
						<td class="tac"><?=$k?></td>
						<td><?=$v['name']?></td>
						<td><?=$v['account']?></td>
						<td class="tar"><?=$v['income']?></td>
						<td class="tac"><?=$v['code']?></td>
					</tr>
				<? }} ?>
					<tr>
						<td colspan="3" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
						<td class="tar" style="font-weight:600"><?=number_format($total,2)?></td>
						<td></td>
					</tr>
				</tbody>
			</table>

		</div>
		
	</div>

</div>









