<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	$logger = false;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$comp_settings['logtime'];
		}
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
		}
		//$leave_types = getSelLeaves($cid);
		$leave_types = getSelLeaveTypes($cid);
		$leave_period_start = '01-01-'.date('Y');
		$leave_period_end = '31-12-'.date('Y');
		//$leave_request_before = $compinfo['request'];
		//var_dump($leave_types);

		$periods = getPayrollPeriods($lang);
		$to_lock = $periods['to_lock'];
		$to_unlock = $periods['unlock'];
		$period = $periods['period'];
		
	}

		// echo '<pre>';
		// print_r($_SESSION);
		// echo '</pre>';

		// die();

?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
		<link rel="icon" type="image/png" sizes="192x192" href="../assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
		
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/bootstrap-year-calendar.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/sumoselect-menu.css?<?=time()?>">

		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
	
	<script>
		//var headerCount = 2;
		var lang = <?=json_encode($lang)?>;
		var dtable_lang = <?=json_encode($dtable_lang)?>;
		var ROOT = <?=json_encode(ROOT)?>;
		var logtime = <?=json_encode($logtime)*1000?>;
	</script>

</head>

<body>
		
	<? include(DIR.'include/main_header.php');?>
	
	<div class="topnav-custom">
		<div class="btn-group"> 
			<a href="../index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==200){echo 'active';}?>">
			<a href="index.php?mn=200" class="home"><i class="fa fa-dashboard"></i></a>
		</div>
		
		<? if($_GET['mn'] == 201){ include('../include/main_menu_selection.php');} ?>
		
		<!--<div class="btn-group <? if($_GET['mn']==201){echo 'active';}?>">
			<a href="index.php?mn=201"><?=$lng['Leave application']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==202){echo 'active';}?>">
			<a href="index.php?mn=202"><?=$lng['Leave calendar']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==203){echo 'active';}?>">
			<a href="index.php?mn=203"><?=$lng['Approve leave period']?></a>
		</div>-->
		
		<div class="btn-group" style="float:right;"> 
			<button data-toggle="dropdown" style="padding:0 8px; background:#000; cursor:default">
				 <img class="nav-user-img" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>
		
		<? include('../include/common_select_year.php');?>

		<div class="btn-group" style="float:right;"> 
			<button class="dropdown-toggle" data-toggle="dropdown">
				<? if(isset($period[$_SESSION['rego']['cur_month']])){
					echo $period[$_SESSION['rego']['cur_month']];
				}else{
					echo $lng['Select period'];//end($period);
				}?>
			</button>
			<div class="dropdown-menu dropdown-menu-right">
				<? foreach($period as $k=>$v){ ?>
					<a class="dropdown-item selectMonth" data-id="<?=$k?>"><?=$v?></a>
				<? } ?>
			</div>
		</div>
	</div>
	
	<? if($logger){
			//$helpfile = getHelpfile(101);
			switch($_GET['mn']){
				case 200:
					include('leave_dashboard.php'); break;
				case 201:
					$helpfile = getHelpfile(201);
					include('leave_application.php'); break;
				case 202:
					$helpfile = getHelpfile(202);
					include('leave_calendar.php'); break;
				case 203:
					$helpfile = getHelpfile(203);
					include('leave_approve_for_payroll.php'); break;
				case 204:
					include('leave_report_center.php'); break;
				case 205:
					$helpfile = getHelpfile(205);
					include('leave_reports.php'); break;
			}
		}else{
			header('location: ../login.php');
		} ?>

	<? include('../include/modal_relog.php')?>

	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../assets/js/bootstrap-datepicker.min.js"></script>
	<script src="../assets/js/bootstrap-datepicker.th.js"></script>
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/jquery.sumoselect-menu.js"></script>
	<script src="../assets/js/rego.js?<?=time()?>"></script>
	
	<? include('../include/common_script.php')?>
	<? include('../include/main_menu_script.php')?>
	
<script type="text/javascript">
		
	$(document).ready(function() {
 		
		
	});
		
</script>
	
	<? if(!empty($helpfile) && $_GET['mn'] != 2 && $_GET['mn'] != 600){ ?>		
		<div class="openHelp"><i class="fa fa-question-circle fa-lg"></i></div>
		<div id="help">
			<div class="closeHelp"><i class="fa fa-arrow-circle-right"></i></div>
			<div class="innerHelp">
				<?=$helpfile?>
			</div>
		</div>
	<? } ?>
	
</body>
</html>








