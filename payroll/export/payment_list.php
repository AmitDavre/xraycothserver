<?php

	$pattern = '%%%-%-%%%%%-%';
	$nr = 1;
	$total = 0;
	$data = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '002', 'all');
				if($empinfo){
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$account = str_replace('-', '', $empinfo['bank_account']);
					$data[$nr][1] = $account;
					if(strlen($account) == 10){
						$data[$nr][1] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					//$data[$nr][2] = $empinfo['bank_branch'];
					$data[$nr][2] = sprintf("%04d", substr($account,0,3));
					$data[$nr][3] = $name;
					$data[$nr][4] = number_format($row['net_income'],2);
					$total += round($row['net_income'],2);
					$nr++;
				}
			}
		
		}
	}
	//var_dump($data);
?>

	<table class="basicTable" style="margin-bottom:10px">
		<tbody>
			<tr>
				<th><?=$lng['Company']?> :</th>
				<td><?=$compname?></td>
				<th><?=$lng['Account']?> :</th>
				<td><?=$bank_account?></td>
				<th><?=$lng['Subject']?> :</th>
				<td><?=$lng['Payment wages']?> <?=$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_th']?></td>
				<td style="width:100%"></td>
			</tr>
		</tbody>
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
				<td><?=$v[1]?></td>
				<td><?=$v[2]?></td>
				<td><?=$v[3]?></td>
				<td class="tar"><?=$v[4]?></td>
			</tr>
		<? }} ?>
			<tr>
				<td colspan="4" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
				<td class="tar" style="font-weight:600"><?=number_format($total,2)?></td>
			</tr>
		</tbody>
	</table>
