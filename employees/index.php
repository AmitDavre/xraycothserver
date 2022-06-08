<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	//include(DIR.'time/functions.php');
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
	
	//var_dump($standard);
	//var_dump($_SESSION['rego']['standard'][$standard]['other_benefits']);


	// create new table for saving annual leaves 

	// include(DIR.'employees/create_employee_record_table.php');


	
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

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
	

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
		
		<? if($_GET['mn'] == 101){ include('../include/main_menu_selection.php');} ?>
		
		
		<? if($_GET['mn'] >= 1021 && $_GET['mn'] <= 1026){?>
		<div class="btn-group <? if($_GET['mn']==101){echo 'active';}?>">
			<a href="index.php?mn=101"><?=$lng['Employee register']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==1021){echo 'active';}?>">
			<a href="index.php?mn=1021"><?=$lng['Employee info']?></a>
		</div>
		<? if(isset($_SESSION['rego']['empID']) && $_SESSION['rego']['empID'] != '0'){ ?>
		<div class="btn-group <? if($_GET['mn']==1022){echo 'active';}?>">
			<a href="index.php?mn=1022"><?=$lng['Financial info']?></a>
		</div>
		
		<? if($_SESSION['rego']['standard'][$standard]['other_benefits']){ ?>
		<div class="btn-group <? if($_GET['mn']==1023){echo 'active';}?>">
			<a href="index.php?mn=1023"><?=$lng['Other benefits']?></a>
		</div>
		<? } if($_SESSION['rego']['standard'][$standard]['historical']){ ?>
		<div class="btn-group <? if($_GET['mn']==1024){echo 'active';}?>">
			<a href="index.php?mn=1024"><?=$lng['Historical records']?></a>
		</div>
		<? } if($_SESSION['rego']['standard'][$standard]['workpermit']){ ?>
		<div class="btn-group <? if($_GET['mn']==1025){echo 'active';}?>">
			<a href="index.php?mn=1025"><?=$lng['Workpermit']?></a>
		</div>
		<? } if($_SESSION['rego']['standard'][$standard]['tax_simulation']){ ?>
		<div class="btn-group <? if($_GET['mn']==1026){echo 'active';}?>">
			<a href="index.php?mn=1026"><?=$lng['Tax simulation']?></a>
		</div>
		<? } ?>
		<? } ?>
		<? } ?>
		
		<div class="btn-group <? //if($_GET['mn']==1026){echo 'active';}?>">
			<a href="reports/index.php?mn=450"><?=$lng['Reports']?></a>
		</div>

		<div class="btn-group" style="float:right;"> 
			<button style="padding:0 8px; background:#000; cursor:default">
				 <img class="nav-user-img" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>

		<? include('../include/common_select_year.php');?>
		


	</div>
	
	<? if($logger){
			switch($_GET['mn']){
				case 101: 
					$helpfile = getHelpfile(101);
					include('employees_list.php'); 
					break;
				case 1021: 
					$helpfile = getHelpfile(102);
					include('employee_info.php'); 
					break;
				case 1022: 
					//$helpfile = getHelpfile(102);
					include('employee_financial.php'); 
					break;
				case 1023: 
					//$helpfile = getHelpfile(102);
					include('employee_benefits.php'); 
					break;
				case 1024: 
					//$helpfile = getHelpfile(102);
					include('employee_history.php'); 
					break;
				case 1025: 
					//$helpfile = getHelpfile(102);
					include('employee_workpermit.php'); 
					break;
				case 1026: 
					//$helpfile = getHelpfile(102);
					include('employee_tax_simulation.php'); 
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

		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBV4OwtunNsg-_t446caGdt1QCBZQQhWUs"></script>
		

	
	<? include('../include/common_script.php')?>
	<? include('../include/main_menu_script.php')?>

</body>
</html>








