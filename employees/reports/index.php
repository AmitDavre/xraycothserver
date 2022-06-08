<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	include(DIR.'files/payroll_functions.php');

	$logger = false;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$sys_settings['logtime'];
		}
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
		}
	}
	
	/*$logger = false;
	$locked = true;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
			
			$periods = getPayrollPeriods($lang);
			$to_lock = $periods['to_lock'];
			$to_unlock = $periods['unlock'];
			$period = $periods['period'];
			//var_dump($periods);
			//$_SESSION['rego']['locked'] = $locked;
			$history_lock = getHistoryLock($cid);
			$_SESSION['rego']['paydate'] = getPaydate($cid);
			getFormdate($cid);
			
			
		}
	}*/
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title>REGO HR</title>
	
		<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../../images/favicon.ico" type="image/x-icon">
	
		<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/line-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/autocomplete.css?<?=time()?>">
		<link rel="stylesheet" href="../../assets/css/sumoselect-menu.css?<?=time()?>">

		
		<script src="../../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../../assets/js/jquery-ui.min.js"></script>
	
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
	
	<div class="topnav-custom hide-600">
		<div class="btn-group"> 
			<a href="../../index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>

		<? if($_GET['mn'] == 460){ include('../../include/main_menu_selection.php');} ?>


		<div class="btn-group <? //if($_GET['mn']==101){echo 'active';}?>">
			<a href="../index.php?mn=101"><?=$lng['Employee register']?></a>
		</div>

		<div class="btn-group <? if($_GET['mn'] >= 450 && $_GET['mn'] <= 455){echo 'active';}?>">
			<button class="dropdown-toggle" data-toggle="dropdown">
				<?=$lng['Reports']?>
			</button>
				<ul class="dropdown-menu">
					<li><a class="dropdown-item <? if($_GET['mn']==450){echo 'active';}?>" href="index.php?mn=450"><?=$lng['Employee record']?></a></li>
					<li><a class="dropdown-item " href="index.php?mn=460"><?=$lng['Consent Letter']?></a></li>
					<!--<li><a class="dropdown-item <? if($_GET['mn']==451){echo 'active';}?>" href="index.php?mn=451"><?=$lng['Overview per employee per month']?></a></li>
					<li><a class="dropdown-item <? if($_GET['mn']==452){echo 'active';}?>" href="index.php?mn=452"><?=$lng['Overview employee per year']?></a></li>-->
				</ul>
		</div>
		
		<div class="btn-group" style="float:right;"> 
			<button data-toggle="dropdown" style="padding:0 8px; background:#000; cursor:default">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 0 0 0; border:0px solid #666" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>
		
	</div>
	
	<? if($logger){
			switch($_GET['mn']){
				case 450: 
					include('employee_record.php');
					$helpfile = getHelpfile(450);
					break;
				case 451: 
					include('employee_month.php');
					$helpfile = getHelpfile(450);
					break;
				case 452: 
					include('employee_year.php');
					$helpfile = getHelpfile(450);
					break;		
				case 460: 
					include('consent_letter/consent_letter_report_table.php');
					$helpfile = getHelpfile(450);
					break;
			}
		}else{
			header('location: ../../login.php');
		} ?>
	
	<? include('../../include/modal_relog.php')?>

	<script src="../../assets/js/popper.min.js"></script>
	<script src="../../assets/js/bootstrap.min.js"></script>
	<script src="../../assets/js/jquery.dataTables.min.js"></script>
	<script src="../../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../../assets/js/bootstrap-datepicker.min.js"></script>
	<script src="../../assets/js/bootstrap-datepicker.th.js"></script>
	<script src="../../assets/js/bootstrap-confirmation.js"></script>
	<script src="../../assets/js/jquery.numberfield.js"></script>	
	<script src="../../assets/js/jquery.mask.js"></script>	
	<script src="../../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../../assets/js/rego.js?<?=time()?>"></script>
	<script src="../../assets/js/jquery.sumoselect.js"></script>
	<script src="../../assets/js/jquery.sumoselect-menu.js"></script>
	<script src="../../assets/js/jquery.autocomplete.js"></script>

	<? include('../../include/common_script.php')?>
	<? include('../../include/main_menu_script.php')?>
	
<script type="text/javascript">
		
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








