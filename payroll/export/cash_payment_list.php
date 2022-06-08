<?php

	$total = 0;
	$data = array();
	$sql = "SELECT emp_id, emp_name_".$lang.", net_income FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$empinfo = getEmployees($cid, $_SESSION['rego']['gov_entity']);
			//var_dump($empinfo); exit;
			if($empinfo){
				if($empinfo[$row['emp_id']]['pay_type'] == 'cash'){
					$data[$row['emp_id']]['name'] = $row['emp_name_'.$lang];
					$data[$row['emp_id']]['net'] = $row['net_income'];
				}
			}
		}
	}
	$totals['total'] = 0;
	$totals['1000'] = 0;
	$totals['500'] = 0;
	$totals['100'] = 0;
	$totals['50'] = 0;
	$totals['20'] = 0;
	$totals['10'] = 0;
	$totals['5'] = 0;
	$totals['1'] = 0;
	$totals['050'] = 0;
	$totals['025'] = 0;
	$totals['001'] = 0;
	
	foreach($data as $k=>$v){
		$totals['total'] += round($v['net'],2);
		$tmp = explode('.', round($v['net'],2));
		if(empty($tmp[0])){$tmp[1] = 0;}
		if(empty($tmp[1])){$tmp[1] = 0;}
		
		$amt = $tmp[0];
		$total = floor($amt/1000);
		$data[$k]['1000'] = $total;
		$totals['1000'] += $total;
		
		$amt -= $total*1000;
		$total = floor($amt/500);
		$data[$k]['500'] = $total;
		$totals['500'] += $total;
		
		$amt -= $total*500;
		$total = floor($amt/100);
		$data[$k]['100'] = $total;
		$totals['100'] += $total;
		
		$amt -= $total*100;
		$total = floor($amt/50);
		$data[$k]['50'] = $total;
		$totals['50'] += $total;
		
		$amt -= $total*50;
		$total = floor($amt/20);
		$data[$k]['20'] = $total;
		$totals['20'] += $total;
		
		$amt -= $total*20;
		$total = floor($amt/10);
		$data[$k]['10'] = $total;
		$totals['10'] += $total;
		
		$amt -= $total*10;
		$total = floor($amt/5);
		$data[$k]['5'] = $total;
		$totals['5'] += $total;
		
		$amt -= $total*5;
		$total = floor($amt);
		$data[$k]['1'] = $total;
		$totals['1'] += $total;
		
		$amt = $tmp[1]; // decimals
		//var_dump($amt);
		if($amt == 5){
			$data[$k]['050'] = 1;
			$totals['050'] += 1;
			$amt = 0;
			$total = 0;
		}else{
			$total = floor($amt/50);
			$data[$k]['050'] = $total;
			$totals['050'] += $total;
		}
			
		$amt -= $total*50;
		$total = floor($amt/25);
		$data[$k]['025'] = $total;
		$totals['025'] += $total;
		
		$amt -= $total*25;
		$total = floor($amt);
		$data[$k]['001'] = $total;
		$totals['001'] += $total;
	}
	
	//var_dump($data); 
	//exit;
	
?>
<style>
	.basicTable th {
		min-width:40px;
	}
	.basicTable td.f11 {
		font-size:10px;
	}
	.basicTable tr.bold td {
		font-weight:600;
	}
</style>

	<div class="A4form" style="width:1100px">
		<table style="width:100%; margin-bottom:5px;" border="0">
			<tr>
				<td style="font-size:20px; font-weight:600"><?=$lng['Cash payment list']?></td>
				<td>
					<!--<button type="button" class="btn btn-primary btn-fr" onclick="window.open('export/print_cash_payment_list.php', '_blank');"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['PDF File']?></button>-->
					<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_cash_payment_list_excel.php';"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Excel File']?></button>
				</td>
			</tr>
		</table>
		<table class="basicTable">
			<thead>
				<tr>
					<th><?=$lng['Emp. ID']?></th>
					<th><?=$lng['Employee']?></th>
					<th class="tac">Net pay<? //=$lng['Credit']?></th>
					<th class="tac">1000</th>
					<th class="tac">500</th>
					<th class="tac">100</th>
					<th class="tac">50</th>
					<th class="tac">20</th>
					<th class="tac">10</th>
					<th class="tac">5</th>
					<th class="tac">1</th>
					<th class="tac">0.50</th>
					<th class="tac">0.25</th>
					<th class="tac">0.01</th>
				</tr>
			</thead>
			<tbody>
				<? foreach($data as $k=>$v){ ?>
				<tr>
					<td><?=$k?></td>
					<td><?=$v['name']?></td>
					<td class="tar"><?=number_format($v['net'],2)?></td>
					<td class="tac"><?=$v['1000']?></td>
					<td class="tac"><?=$v['500']?></td>
					<td class="tac"><?=$v['100']?></td>
					<td class="tac"><?=$v['50']?></td>
					<td class="tac"><?=$v['20']?></td>
					<td class="tac"><?=$v['10']?></td>
					<td class="tac"><?=$v['5']?></td>
					<td class="tac"><?=$v['1']?></td>
					<td class="tac"><?=$v['050']?></td>
					<td class="tac"><?=$v['025']?></td>
					<td class="tac"><?=$v['001']?></td>
				</tr>
				<? } ?>
				<tr><td colspan="14" style="padding:5px"></td></tr>
				<tr class="bold" style="background:#efe">
					<td colspan="2"><? //=$lng['Totals']?>Total notes / coins</td>
					<td class="tar"><?=number_format($totals['total'],2)?></td>
					<td class="tac"><?=$totals['1000']?></td>
					<td class="tac"><?=$totals['500']?></td>
					<td class="tac"><?=$totals['100']?></td>
					<td class="tac"><?=$totals['50']?></td>
					<td class="tac"><?=$totals['20']?></td>
					<td class="tac"><?=$totals['10']?></td>
					<td class="tac"><?=$totals['5']?></td>
					<td class="tac"><?=$totals['1']?></td>
					<td class="tac"><?=$totals['050']?></td>
					<td class="tac"><?=$totals['025']?></td>
					<td class="tac"><?=$totals['001']?></td>
				</tr>
				<tr class="bold" style="background:#ffe">
					<td colspan="2">Total amounts</td>
					<td class="tar f11"><?=number_format($totals['total'],2)?></td>
					<td class="tac f11"><?=number_format(($totals['1000']*1000),2)?></td>
					<td class="tac f11"><?=number_format(($totals['500']*500),2)?></td>
					<td class="tac f11"><?=number_format(($totals['100']*100),2)?></td>
					<td class="tac f11"><?=number_format(($totals['50']*50),2)?></td>
					<td class="tac f11"><?=number_format(($totals['20']*20),2)?></td>
					<td class="tac f11"><?=number_format(($totals['10']*10),2)?></td>
					<td class="tac f11"><?=number_format(($totals['5']*5),2)?></td>
					<td class="tac f11"><?=number_format(($totals['1']),2)?></td>
					<td class="tac f11"><?=number_format(($totals['050']*0.5),2)?></td>
					<td class="tac f11"><?=number_format(($totals['025']*0.25),2)?></td>
					<td class="tac f11"><?=number_format($totals['001']*0.01,2)?></td>
				</tr>
				
				<!--<tr style="background:#eee; border-bottom:2px solid #ccc">
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><?=$lng['Totals']?></td>
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><? //=number_format($total_debet,2)?></td>
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><? //=number_format($total_credit,2)?></td>
					<td></td>
				</tr>-->
			</tbody>
		</table>

