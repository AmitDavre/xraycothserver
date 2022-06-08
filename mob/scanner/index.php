<?php

	if(session_id()==''){session_start();} 
	ob_start();
	
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	if(isset($_SESSION['rego']['emp_id'])){
		$res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'");
		$data = $res->fetch_assoc();
	}
	if(empty($data['image'])){$data['image'] = '../images/profile_image.jpg';}
	
	$history = array();
	$sql = "SELECT * FROM ".$cid."_attendance WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND date <= '".date('Y-m-d')."' ORDER BY date DESC LIMIT 5";
	//$sql = "SELECT * FROM ".$cid."_attendance WHERE emp_id = '".$_SESSION['rego']['emp_id']."' ORDER BY date DESC LIMIT 10";
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
	//var_dump(date('N')); exit;
	$myear = date('Y');
	if($lang == 'th'){$myear += 543;}
	$thai_date = $weekdays[date('N')].' '.date('d-m-').$myear;
	
?>

<!DOCTYPE html>
<html>
  <head>
    <title>QR Code Scanner</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
  	<meta http-equiv="Pragma" content="no-cache"/>
  	<meta http-equiv="Expires" content="0"/>
 	 	<meta name="apple-mobile-web-app-status-bar" content="#066" />
 		<meta name="theme-color" content="#066" />
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
		<link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../assets/css/style.css?<?=time()?>" rel="stylesheet">
		<link href="../assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">
		
		<script type="text/javascript" src="../../assets/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="../../assets/js/popper.min.js"></script>
		<script type="text/javascript" src="../../assets/js/bootstrap.min.js"></script>
	
	<style>
		.scanTable {
			width:100%;
			margin-top:25px;
			border-collapse:separate;
			border-spacing:2px;
			border:0;
		}
		.scanTable td {
			padding:2px 8px;
			background:rgba(255,255,255,0.1);
			font-size:12px;
			white-space:nowrap;
		}
		.scanTable thead td {
			background:rgba(255,255,255,0.2);
		}
		.clock-btn {
			width:75%;
			height:65px;
			text-align:center;
			padding:0;
			border:1px solid #fff !important;
			font-size:18px;
			line-height:130%;
			white-space:nowrap;
		}
	</style>
	
	</head>

  <body style="background:#006666">
    
		<div class="header page_header">
			<div><i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time registration']?></div>
			<a style="float:right" href="../index.php?mn=2"><i class="fa fa-home"></i></a>
		</div>			
	
		<div class="scanner" style="position:absolute; top:55px; bottom:0; left:0; right:0; padding:10px 15px">
			<div id="dump"></div>
			
			<table border="0" width="100%" style="margin-bottom:25px">
				<tr>
					<td valign="top" style="width:120px; padding-bottom:0px">
						<img class="picture" src="<?=ROOT.$data['image']?>?<?=time()?>">
      		</td>
					<td valign="middle" style="font-size:20px; line-height:140%; color:#fff">
						<?=$title[$data['title']].' '.$data[$lang.'_name']?><br>
						<?=$positions[$data['position']]?>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2" style="font-size:20px; color:#fff; padding:0px 0 5px; line-height:120%"><?=$thai_date?></td>
				</tr>
				<tr>
					<td colspan="2" valign="top" style="padding:5px; font-size:60px; color:#fff; line-height:120%; text-align:center; font-family:monospace; background:rgba(255,255,255,0.1)">
						<span id="time">00:00:00</span>
					</td>
			</table>
			
			<table border="0" style="width:100%; table-layout:fixed;">
				<tr>
					<td align="center">
						<button id="clockIN" class="clock-btn btn-info"><i class="fa fa-sign-in fa-lg"></i><br><?=$lng['Punch IN']?></button>
					</td>
					<td align="center">
						<button id="clockOUT" onclick="window.location.href='scan.php?c=out'" class="clock-btn btn-danger"><i class="fa fa-sign-out fa-lg"></i><br><?=$lng['Punch OUT']?></button>
					</td>
				</tr>
			</table>
					
			<table class="scanTable" style="display:xnone">
				<thead>
					<tr style="font-size:14px; color:#fff">
						<td><?=$lng['Date']?></td>
						<td class="tac"><?=$lng['Scan']?> 1</td>
						<td class="tac"><?=$lng['Scan']?> 2</td>
						<td class="tac"><?=$lng['Scan']?> 3</td>
						<td class="tac"><?=$lng['Scan']?> 4</td>
					</tr>
				</thead>
				<tbody>
					<? foreach($history as $k=>$v){ ?>
					<tr style="font-size:14px; color:#fff">
						<td><?=$v['date']?></td>
						<td class="tac"><?=$v['scan1']?></td>
						<td class="tac"><?=$v['scan2']?></td>
						<td class="tac"><?=$v['scan3']?></td>
						<td class="tac"><?=$v['scan4']?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
					
    </div>
		
	<script> 
		
	$(document).ready(function() {
		
		$('#clockIN').on('click', function(){
			$(this).prop('disabled', true);
			window.location.href='scan.php?c=in';
		})	
		
		$('#clockOUT').on('click', function(){
			$(this).prop('disabled', true);
			window.location.href='scan.php?c=out';
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
	
  </body>
</html>
















