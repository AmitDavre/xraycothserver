<?php
	
	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../dbconnect/db_connect.php');
	$history = array();
	$sql = "SELECT * FROM ".$cid."_attendance WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND date <= '".date('Y-m-d')."' ORDER BY date DESC LIMIT 5";

	
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$history[$row['id']]['date'] = date('d-m-Y', strtotime($row['date']));
			/*if(strlen($row['scan1']) > 2){$history[$row['id']]['scan1'] = date('H:i', strtotime($row['scan1']));}else{$history[$row['id']]['scan1'] = $row['scan1'];}
			if(strlen($row['scan2']) > 2){$history[$row['id']]['scan2'] = date('H:i', strtotime($row['scan2']));}else{$history[$row['id']]['scan2'] = $row['scan2'];}
			if(strlen($row['scan3']) > 2){$history[$row['id']]['scan3'] = date('H:i', strtotime($row['scan3']));}else{$history[$row['id']]['scan3'] = $row['scan3'];}
			if(strlen($row['scan4']) > 2){$history[$row['id']]['scan4'] = date('H:i', strtotime($row['scan4']));}else{$history[$row['id']]['scan4'] = $row['scan4'];}*/
			$history[$row['id']]['scan1'] = $row['scan1'];
			$history[$row['id']]['scan2'] = $row['scan2'];
			$history[$row['id']]['scan3'] = $row['scan3'];
			$history[$row['id']]['scan4'] = $row['scan4'];
		}
	}


	// echo '<pre>';
	// print_r($history);
	// echo '</pre>';
	// die();

	
	$myear = date('Y');
	if($lang == 'th'){$myear += 543;}
	$thai_date = $weekdays[date('N')].' '.date('d-m-').$myear;




	$sql_work_from_home = "SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."' ";
	
	if($res_work_from_home = $dbc->query($sql_work_from_home)){
		while($row_work_from_home = $res_work_from_home->fetch_assoc()){

			$workFromHome = $row_work_from_home['workFromHome'];

		}
	}





?>	
	<div class="container-fluid" style="xborder:1px solid red; padding:0">
					
		<div class="time-profile">
			<div class="profile">
				<img src="../<?=$data['image']?>">
			</div>
			<div class="content">
				<h3><?=$title[$data['title']].' '.$data[$lang.'_name']?></h3>
				<!--<em><?=$data['emp_id']?></em>-->
				<p style="margin-top:0px; color:#666"><?=$positions[$data['position']][$lang]?></p>
				<p style="padding-top:10px; color:#000; font-weight:500"><?=$thai_date?></p>
				<p id="time"><?=date('H:m:s')?></p>
			</div>
			<div class="clear"></div>
		</div>
					 
		<div class="xrow" style="xborder:1px solid green; padding:10px; margin:0">
				
				
			<div class="time-content" > 
				<div class="outer" id="clockIN" style="padding-right:8px">	
					<a class="bg-blue-light" href="#">
						<i class="fa fa-sign-in"></i>
						<em><?=$lng['Punch IN']?></em>
					</a>
				</div>
				<div class="outer" id="clockOUT" style="padding-left:8px">
					<a class="bg-red-light" href="#">
						<i class="fa fa-sign-out"></i>
						<em><?=$lng['Punch OUT']?></em>
					</a>
				</div>
				<div class="clear" style="height:20px"></div>
				<?php if ($workFromHome == '1'){?>
					<div class="outer" id="homeWork" style="width: 100%">
						<a class="" href="#">
							<i class="fa fa-home"></i>
							<em><?=$lng['Home Work']?></em>
						</a>
					</div>
				<?php } ?>

			
				<table class="table table-bordered xtable-sm" style="width:100%">
					<thead class="thead-light">
						<tr style="font-size:14px; color:#fff">
							<th><?=$lng['Date']?></th>
							<th class="tac"><?=$lng['IN']?></th>
							<th class="tac"><?=$lng['OUT']?></th>
							<th class="tac"><?=$lng['Hrs']?></th>
							<th class="tac"><?=$lng['Break']?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($history as $k => $v) {?>
							<tr style="font-size:14px; color:#000">
								<td ><?php echo $v['date']?></td>
								<td class="tac"><?php echo $v['scan1']?></td>
								<td class="tac"><?php echo $v['scan2']?></td>
								<td class="tac"></td>
								<td class="tac"></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

			</div>
			
		</div>
	</div>

	<script> 
		
	$(document).ready(function() {
		
		$('#clockIN').on('click', function(){
			$(this).prop('disabled', true);
			window.location.href='scanner/scan.php?c=in';
		})	
		
		$('#clockOUT').on('click', function(){
			$(this).prop('disabled', true);
			window.location.href='scanner/scan.php?c=out';
		})	

		$('#homeWork').on('click', function(){
			$(this).prop('disabled', true);
			window.location.href='index.php?mn=1705';
		})	
		
		
		var span = document.getElementById('time');
		function time() {
			var d = new Date();
			var s = d.getSeconds();
			var m = d.getMinutes();
			var h = d.getHours();
			span.textContent = h + ":" + ('0'  + m).slice(-2) + ":" + ('0'  + s).slice(-2);
		}
		time();
		setInterval(time, 1000);		
	
	})
			
	</script>







