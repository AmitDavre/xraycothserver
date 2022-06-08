<?php
	
	$fix_allow = unserialize($sys_settings['fix_allow']);
	$var_allow = unserialize($sys_settings['var_allow']);
	
	$account_codes = unserialize($sys_settings['account_codes']);
	foreach($account_codes as $k=>$v){
		$acc_code[$v['code']] = $v[$lang];
	}
	//var_dump($account_codes);
	
	$emps = array();	
	$sql = "SELECT emp_id, account_code FROM ".$_SESSION['rego']['emp_dbase'];
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$emps[$row['emp_id']] = $row['account_code'];
		}
	}
	//var_dump($emps);
	$array = array();
	$debet = array();	
	$credit = array();	
	$sql = "SELECT account_allocations FROM ".$cid."_sys_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$array = unserialize($row['account_allocations']);
			$debet = $array['debet'];
			$credit = $array['credit'];
		}
	}
	//$array = array();
	//var_dump($debet);
	//var_dump($credit);
	
	$deb_data = array();
	$cre_data = array();
	if($array){
		foreach($debet['direct'] as $k=>$v){
			$deb_data[$v] = 0;
		}
		foreach($debet['indirect'] as $k=>$v){
			$deb_data[$v] = 0;
		}
		foreach($credit['direct'] as $k=>$v){
			$cre_data[$v] = 0;
		}
		foreach($credit['indirect'] as $k=>$v){
			$cre_data[$v] = 0;
		}
		//var_dump($deb_data);
		
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."'")){
			while($row = $res->fetch_assoc()){
				foreach($debet['direct'] as $k=>$v){
					if($emps[$row['emp_id']] == 0){
						if($k == 'salary'){
							$deb_data[$v] += ($row['salary']);
						}else if($k == 'tot_deduct_before'){
							$deb_data[$v] -= $row['tot_deduct_before'];
						}else if($k == 'tot_deduct_after'){
							$deb_data[$v] -= $row['tot_deduct_after'];
						}else if($k == 'absence_b'){
							$deb_data[$v] -= $row['absence_b'];
						}else if($k == 'late_early_b'){
							$deb_data[$v] -= $row['late_early_b'];
						}else if($k == 'leave_wop_b'){
							$deb_data[$v] -= $row['leave_wop_b'];
						}else{
							if($row[$k]){$deb_data[$v] += $row[$k];}
						}
					}
				}
				foreach($debet['indirect'] as $k=>$v){
					if($emps[$row['emp_id']] == 1){
						if($k == 'salary'){
							$deb_data[$v] += ($row['salary']);
						}else if($k == 'tot_deduct_after'){
							$deb_data[$v] -= $row['tot_deduct_after'];
						}else if($k == 'tot_deduct_before'){
							$deb_data[$v] -= $row['tot_deduct_before'];
						}else if($k == 'absence_b'){
							$deb_data[$v] -= $row['absence_b'];
						}else if($k == 'late_early_b'){
							$deb_data[$v] -= $row['late_early_b'];
						}else if($k == 'leave_wop_b'){
							$deb_data[$v] -= $row['leave_wop_b'];
						}else{
							if($row[$k]){$deb_data[$v] += $row[$k];}
						}
					}
				}
				foreach($credit['direct'] as $k=>$v){
					//var_dump($k);	
					if($emps[$row['emp_id']] == 0){
						if($k == 'social'){$cre_data[$v] += $row[$k];}
						//if($k == 'social_com'){$cre_data[$v] += $row[$k];}
						if($k == 'net_income'){$cre_data[$v] += $row['advance'];}
						$cre_data[$v] += $row[$k];
					}
				}
				foreach($credit['indirect'] as $k=>$v){
					if($emps[$row['emp_id']] == 1){
						if($k == 'social'){$cre_data[$v] += $row[$k];}
						//if($k == 'social_com'){$cre_data[$v] += $row[$k];}
						if($k == 'net_income'){$cre_data[$v] += $row['advance'];}
						$cre_data[$v] += $row[$k];
					}
				}
			}
		}
	}

	//var_dump($deb_data);	
			
	$debet_data = array();
	$credit_data = array();
	foreach($deb_data as $k=>$v){
		if(isset($acc_code[$k])){
			$debet_data[$k.' : '.$acc_code[$k]] = $v;
		}
	}
	foreach($cre_data as $k=>$v){
		if(isset($acc_code[$k])){
			$credit_data[$k.' : '.$acc_code[$k]] = $v;
		}
	}
	
	$total_debet = round(array_sum($debet_data),2);
	$total_credit = round(array_sum($credit_data),2);
	
	$accounting['debet_data'] = $debet_data;
	$accounting['credit_data'] = $credit_data;
	$accounting['total_debet'] = $total_debet;
	$accounting['total_credit'] = $total_credit;
	$sql = "INSERT INTO ".$cid."_payroll_months (month, accounting) VALUES(
		'".$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month']."','".serialize($accounting)."') 
		ON DUPLICATE KEY UPDATE accounting = VALUES(accounting)";
	
	if(!$dbc->query($sql)){
		//echo mysqli_error($dbc);
	};
	
	//var_dump($debet_data);
	//var_dump($credit_data);
	
?>

	<div class="A4form" style="width:960px">
		<table style="width:100%; margin-bottom:5px;" border="0">
			<tr>
				<td style="font-size:20px; font-weight:600"><?=$lng['Accounting entries']?></td>
				<td>
					<button type="button" class="btn btn-primary btn-fr" onclick="window.open('export/print_accounting.php', '_blank');"><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['PDF File']?></button>
					<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_accounting_excel.php';"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Excel File']?></button>
				</td>
			</tr>
		<? if($array){echo '<tr><td colspan="3" style="color:#b00; font-weight:600; padding-top:5px">'.$lng['Set up accounting allocations'].'</td></tr>';} ?>
		</table>
		<table class="basicTable">
			<thead>
				<tr>
					<th><?=$lng['Accounting code']?></th>
					<th style="width:110px" class="tar"><?=$lng['Debet']?></th>
					<th style="width:110px" class="tar"><?=$lng['Credit']?></th>
					<th style="width:40%"></th>
				</tr>
			</thead>
			<tbody>
				
				<? foreach($debet_data as $k=>$v){ ?>
				<tr>
					<td><?=$k?></td>
					<td class="tar"><?=number_format($v,2)?></td>
					<td class="tar"><?=number_format(0,2)?></td>
					<td></td>
				</tr>
				<? } ?>
				<? foreach($credit_data as $k=>$v){ ?>
				<tr>
					<td><?=$k?></td>
					<td class="tar"><?=number_format(0,2)?></td>
					<td class="tar"><?=number_format($v,2)?></td>
					<td></td>
				</tr>
				<? } ?>
				
				
				<tr style="background:#eee; border-bottom:2px solid #ccc">
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><?=$lng['Totals']?></td>
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><?=number_format($total_debet,2)?></td>
					<td class="tar" style="font-weight:600; border-right:1px solid #fff"><?=number_format($total_credit,2)?></td>
					<td></td>
				</tr>
			</tbody>
		</table>

