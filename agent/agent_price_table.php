<?php

	$price_schedule = array();
	$price_activities = array();
	if(isset($_SESSION['agent']['agent_id'])){
		if($res = $dbx->query("SELECT price_schedule, price_activities FROM rego_company_settings")){
			if($row = $res->fetch_assoc()){
				$price_schedule = unserialize($row['price_schedule']);
				$price_activities = unserialize($row['price_activities']);
			}
		}
	}
	//var_dump($price_activities); exit;
	unset($price_schedule[0]);

?>

<style>
	.list-group-item {
		font-size:16px;
		text-align:left;
		line-height:140%;
		border-radius:0;
	}
	.list-group-item.active {
		font-size:20px;
		font-weight:500;
		text-align:left;
	}
</style>

	<div class="header page_header">
		<div><i class="fa fa-money"></i>&nbsp; REGO Price Table<? //=$lng['Personal data']?></div>
		<a href="index.php?mn=2"><i class="fa fa-home"></i></a>
	</div>			
  
	<div style="height:50px"></div>

		<table class="listTable table-bordered" style="width:100%">
			<thead>
				<th>Version<? //=$lng['Subscription']?></th>
				<th><?=$lng['Employees']?></th>
				<th class="tar"><?=$lng['Month']?></th>
				<th class="tar"><?=$lng['Year']?></th>
			</thead>
			<tbody>
			<? foreach($price_schedule as $k=>$v){ ?>	
				<tr>
					<td>REGO <?=$k?></td>
					<td><?=$lng['Max']?> <?=$v['max_employees']?></td>
					<td class="tar"><?=number_format($v['price_month'])?></td>
					<td class="tar"><?=number_format($v['price_year'])?></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		
		<div style="height:10px"></div>
		
		<table class="listTable table-bordered" style="width:100%">
			<thead>
				<tr style="line-height:110%">
					<th>Activity<? //=$lng['Activity']?></th>
					<th class="tac" style="white-space:normal">Min. Price<? //=$lng['Employees']?></th>
					<th class="tac" style="white-space:normal">Unit rate<? //=$lng['Month']?></th>
					<th class="tac" style="white-space:normal">Price unit<? //=$lng['Year']?></th>
				<tr>
			</thead>
			<tbody>
			<? foreach($price_activities as $k=>$v){ ?>	
				<tr>
					<td><?=$v['activity']?></td>
					<td class="tar"><?=number_format($v['min_price'])?></td>
					<td class="tar" style="white-space:nowrap"><?=$v['rate']?></td>
					<td class="tar"><?=number_format($v['price'])?></td>
				</tr>
			<? } ?>
			</tbody>
		</table>
		
		<div style="height:10px"></div>
		
  














