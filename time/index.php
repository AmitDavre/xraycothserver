<?php





	if(session_id()==''){session_start();} 

	// 	echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();



	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');



	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();


	$logger = false;
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$sys_settings['logtime'];
		}
		//$logtime = 3;
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
			
			$time_period = getTimePeriod();
		
		}
	}
	
	$time_settings = getTimeSettings();
	$scan_app = $time_settings['scan_system'];

	// echo $_GET['mn'];
	// die();
	
	if(!isset($_GET['mn']) && $scan_app != 'REGO'){
		$_GET['mn'] = 3;
	}
	if(!isset($_GET['mn']) && $scan_app == 'REGO'){
		$_GET['mn'] = 3;
	}
	
	// User type session 

	$userType = $_SESSION['rego']['type'];
	$teams = getAllTeams();

	// echo '<pre>';
	// print_r($teams);
	// echo '</pre>';
	// die();
	$apprTeams= $_SESSION['rego']['sel_teams'];

	$aprTeam = explode(',', $apprTeams);


	$cid =$_SESSION['rego']['cid'];
	$username =$_SESSION['rego']['username'];

	$id=$cid.'_'.$username;
	$sql = "SELECT session_team FROM ".$cid."_user_permissions WHERE id = '".$id."'";

	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			if($row = $res->fetch_assoc())
				{
					$sessionTeams = $row['session_team'];  // SELECTED TEAMS STORED IN SESSION 
						
				}
		}
	}

	$sesTeamArray = explode(',', $sessionTeams);



	$splitnumber = $apprTeams;
	$splittedNumbers = explode(",", $splitnumber);
	$numbers = "'" . implode("', '", $splittedNumbers) ."'";

	$sql22 = "SELECT * FROM ".$_SESSION['rego']['cid']."_teams WHERE id in (".$numbers.")";

	if($res22 = $dbc->query($sql22)){
		if($res22->num_rows > 0){
			while($row22 = $res22->fetch_assoc())
				{
					$datateamsS['code_id'][$row22['code']] = $row22['code'];  // SELECTED TEAMS STORED IN SESSION 
						
				}
		}
	}


	// echo '<pre>';
	// print_r($sesTeamArray);
	// echo '</pre>';
	// die();


	
	
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title>REGO Thailand</title>
	
		<link rel="shortcut icon" href="../images/favicon.ico?x" type="image/x-icon">
		<link rel="icon" href="../images/favicon.ico?x" type="image/x-icon">
		
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
		<link rel="stylesheet" href="../assets/css/jquery.hoverZoom.css">
		<link rel="stylesheet" href="../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/sumoselect-menu.css?<?=time()?>">
		

		<!-- <link rel="stylesheet" href="../assets/css/sumoselect-menu.css<?=time()?>"> -->
		
		<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">-->
		
		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
		<script src="../assets/js/jquery.sumoselect.js"></script>
		<script src="../assets/js/jquery.sumoselect-menu.js"></script>
	
	<script>
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
		<div class="btn-group ">
			<a href="time_dashboard.php?mn=7007" class="home"><i class="fa fa-dashboard"></i></a>
		</div>
			

	 <? include(DIR.'include/main_menu_selection_time.php');?>







			
		
		<div class="btn-group" style="float:right;"> 
			<button data-toggle="dropdown" style="padding:0 8px; background:#000; cursor:default">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 0 0 0; border:0px solid #666" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
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
			switch($_GET['mn']){
				case 3: 
					$helpfile = getHelpfile(33);
					include('time_scan.php'); break;
				case 4: 
					$helpfile = getHelpfile(4);
					include('time_attendance.php'); break;
				case 44: 
					$helpfile = getHelpfile(44);
					include('monthly_attendance.php'); break;
				case 5: 
					$helpfile = getHelpfile(55);
					include('monthly_planning.php');	break;
				case 6: 
					include('work_calendar.php'); break;
				case 7: 
					include('ot_requests.php'); 
					break;
				case 8: 
					include('employee_performance.php'); 
					break;
				case 9: 
					include('shiftplan_calendar.php'); 
					break;	
				case 7007: 
					include('time_dashboard.php'); 
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
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/rego.js?<?=time()?>"></script>

	<script src="../assets/js/moment.min.js"></script>
	<script src="../assets/js/moment-duration-format.min.js"></script>

	<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
  	<script src='../assets/js/jquery.hoverZoom.min.js'></script>
	<script src='../assets/js/cp-lightimg.min.js'></script>
	<!--<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>-->	
	
	<? include('../include/common_script.php')?>


	<script type="text/javascript">


		function updateAccessTime(access, values){

			$.ajax({
				url: ROOT+"ajax/update_session_access.php",
				data: {access: access, values: values},
				success: function(result){
					//$('#dump').html(result); return false;
					window.location.reload()
				}
			});
		}



		var teamsL = "<?php echo $lng['Teams']?>";
		var teamsAll = "<?php echo $lng['Teams All']?>";

		if(lang == 'en'){	
			var locale =  ['OK', 'Cancel', 'Select All'];
		}else{
			var locale =  ['ตกลง', 'ยกเลิก', 'เลือกทั้งหมด'];
		}


		$(document).ready(function() {

				window.sb = $('#selBox-teams').mnSumoSelect({ 
				placeholder: teamsL,
				captionFormat: teamsL+' ({0})', 
				csvDispCount: 1, 
				search: true, 
				searchText:'Search ...',
				// locale: locale,  
				selectAll:true,
				okCancelInMulti:true,
				showTitle: false,
				triggerChangeCombined:true,
				captionFormatAllSelected: teamsAll+' ({0})', 
			})


			// 	window.sb = $('#selBox-entities').mnSumoSelect({ 
			// 	placeholder: '<?=$lng['Entities']?>',
			// 	captionFormat:'<?=$lng['Entities']?> ({0})', 
			// 	captionFormatAllSelected:'<?=$lng['All Entities']?>',
			// 	csvDispCount: 1, 
			// 	search: true, 
			// 	searchText:'Search ...', 
			// 	selectAll:true,
			// 	okCancelInMulti:true,
			// 	showTitle: false,
			// 	triggerChangeCombined:true
			// })

			// 	$('#selBox-entities').on('change',function(e){
			// 		//alert($(this).val());
			// 		updateAccessTime('entities', $(this).val()); 
			// 	});


			// 	window.sb = $('#selBox-branches').mnSumoSelect({ 
			// 	placeholder: '<?=$lng['Branches']?>',
			// 	captionFormat:'<?=$lng['Branches']?> ({0})', 
			// 	captionFormatAllSelected:'<?=$lng['All Branches']?>',
			// 	csvDispCount: 1, 
			// 	search: true, 
			// 	searchText:'<?=$lng['Search']?> ...',
			// 	locale: locale,  

			// 	selectAll:true,
			// 	okCancelInMulti:true,
			// 	showTitle: false,
			// 	triggerChangeCombined:true
			// })
			// $('#selBox-branches').on('change',function(e){
			// 	//alert($(this).val()); 
			// 	updateAccessTime('branches', $(this).val());
			// });


			// 	window.sb = $('#selBox-divisions').mnSumoSelect({ 
			// 	placeholder: '<?=$lng['Divisions']?>',
			// 	captionFormat:'<?=$lng['Divisions']?> ({0})', 
			// 	captionFormatAllSelected:'<?=$lng['All Divisions']?>',
			// 	csvDispCount: 1, 
			// 	search: true, 
			// 	searchText:'<?=$lng['Search']?> ...',
			// 	locale: locale,  
			// 	selectAll:true,
			// 	okCancelInMulti:true,
			// 	showTitle: false,
			// 	triggerChangeCombined:true
			// })
			// $('#selBox-divisions').on('change',function(e){
			// 	//alert($(this).val());
			// 	updateAccessTime('divisions', $(this).val()); 
			// });


			// 	window.sb = $('#selBox-departments').mnSumoSelect({ 
			// 	placeholder: '<?=$lng['Departments']?>',
			// 	captionFormat:'<?=$lng['Departments']?> ({0})', 
			// 	captionFormatAllSelected:'<?=$lng['All Departments']?>',
			// 	csvDispCount: 1, 
			// 	search: true, 
			// 	searchText:'<?=$lng['Search']?> ...',
			// 	locale: locale,  
			// 	selectAll:true,
			// 	okCancelInMulti:true,
			// 	showTitle: false,
			// 	triggerChangeCombined:true
			// })
			// $('#selBox-departments').on('change',function(e){
			// 	//alert($(this).val()); 
			// 	updateAccessTime('departments', $(this).val());
			// });


			// 	window.sb = $('#selBox-teams').mnSumoSelect({ 
			// 	placeholder: '<?=$lng['Teams']?>',
			// 	captionFormat:'<?=$lng['Teams']?> ({0})', 
			// 	captionFormatAllSelected:'<?=$lng['All Teams']?>',
			// 	csvDispCount: 1, 
			// 	search: true, 
			// 	searchText:'<?=$lng['Search']?> ...',
			// 	locale: locale,  
			// 	selectAll:true,
			// 	okCancelInMulti:true,
			// 	showTitle: false,
			// 	triggerChangeCombined:true
			// })
			// $('#selBox-teams').on('change',function(e){
			// 	//alert($(this).val());
			// 	updateAccessTime('teams', $(this).val());
			// });



	


		
		});

		// $('#selBox-teams').on('change',function(e){
		// 	alert('You have selected '+ $(this).val());
		// });


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








