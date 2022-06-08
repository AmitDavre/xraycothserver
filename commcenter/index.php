<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$helpfile = '';
	$logger = false;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$comp_settings['logtime'];
		}
		if($logtime < 60){
			$logtime = 900; // 15 min
		}
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
		}
	}

	$periods = array();
	$periods = getPayrollPeriods($lang);
	$period = $periods['period'];

	include(DIR.'commcenter/create_communication_center_tables.php'); //create tables...
	

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
		<link rel="icon" type="image/png" sizes="192x192" href="../assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
    	
    	<link rel="stylesheet" href="../assets/css/datetimepicker.css">
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
		<link rel="stylesheet" href="../assets/css/croppie_emp.css?<?=time()?>" />
		<link rel="stylesheet" href="../assets/css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/sumoselect-menu.css?<?=time()?>">

		<link rel="stylesheet" href="../assets/css/sumoselect.css?<?=time()?>">
		<style type="text/css">
			.form-group.note-form-group.note-group-select-from-files {
			    display: none;
			}
		</style>
		

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
		
	<? include('../include/main_header.php');?>
	
	<div class="topnav-custom">
		<div class="btn-group"> 
			<a href="../index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>
		<div class="btn-group">
			<a href="index.php?mn=801" class="home"><i class="fa fa-dashboard"></i></a>
		</div>

		<div class="btn-group" style="float:right;"> 
			<button style="padding:0 8px; background:#000; cursor:default">
				 <img class="nav-user-img" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>

		<?if($_GET['mn'] == 801){?>
		<div class="btn-group" style="float:right;"> 
			<button class="dropdown-toggle" data-toggle="dropdown">
				<? if(isset($period[$_SESSION['rego']['cur_month']])){
					echo $period[$_SESSION['rego']['cur_month']];
				}else{
					echo $lng['Select period'];
				}?>
			</button>
			<div class="dropdown-menu">
				<? foreach($period as $k=>$v){ ?>
					<a class="dropdown-item" data-id="<?=$k?>"><?=$v?></a>
				<? } ?>
			</div>
		</div>
		<? } ?>

	</div>

	<? if($logger){
			switch($_GET['mn']){
				case 801: 
					$helpfile = getHelpfile(801);
					include('comm_list.php'); 
					break;
				case 802: 
					$helpfile = getHelpfile(802);
					include('comm_data.php'); 
					break;
				case 803: 
					include('send_to.php'); 
					break;
				
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
	<? if($lang == 'th'){ ?>
	<script src="../assets/js/bootstrap-datepicker.th.js"></script>
	<? } ?>
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/jquery.autocomplete.js"></script>	
	<script src="../assets/js/jquery.sumoselect-menu.js"></script>
	<script src="../assets/js/rego.js?<?=time()?>"></script>
	<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBV4OwtunNsg-_t446caGdt1QCBZQQhWUs"></script> -->
	<script src="../assets/js/jquery.sumoselect.js"></script>
	<script src="../assets/js/datetimepicker.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {

			//$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
			
			$('body').on('focus', '.datetimepicker', function() {
				$(this).datetimepicker({
					format: 'dd-mm-yyyy hh:ii',
					autoclose: true,
			        todayHighlight: true,
			        language: lang,
			        //showMeridian: true,
			        
				});
			})
		})
	</script>
	
	<? include('../include/common_script.php')?>
	<? include('../include/main_menu_script.php')?>


</body>
</html>
