<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'leave/functions.php');
	include(DIR.'time/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	
	$logtime = 1800;
	$logger = false;
	
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
		}
		//$leave_types = getSelLeaves($cid);
		$leave_types = getSelLeaveTypes($cid);
		$leave_period_start = '01-01-'.date('Y');
		$leave_period_end = '31-12-'.date('Y');
		//$leave_request_before = $compinfo['request'];
		//var_dump($leave_types);
		
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title>REGO HR</title>
	
		<link rel="shortcut icon" href="../images/favicon.ico?x" type="image/x-icon">
		<link rel="icon" href="../images/favicon.ico?x" type="image/x-icon">
    
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="../assets/css/line-awesome.min.css">
		<link rel="stylesheet" href="../assets/css/style.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/datepicker/bootstrap-datepicker.css?<?=time()?>" />
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/sumoselect.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/responsive.css?<?=time()?>">

	<link rel="stylesheet" href="../timepicker/dist/jquery-clockpicker.min.css">
	<link rel="stylesheet" href="../css/autocomplete.css?<?=time()?>">
	<link rel="stylesheet" href="../yearcalendar/css/bootstrap-year-calendar2.css?<?=time()?>">
		
		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
	
		<script>
			var headerCount = 2;
			var lang = <?=json_encode($lang)?>;
			var dtable_lang = <?=json_encode($dtable_lang)?>;
			var ROOT = <?=json_encode(ROOT)?>;
			var logtime = <?=json_encode($logtime)*1000?>;
		</script>

</head>

<body>
		
	<? include(DIR.'include/main_header.php');?>
	
	<div class="topnav-custom show-600">
		<div class="btn-group" style="background:#111 !important">
			<button type="button" data-toggle="dropdown">
				<a href="#"><i class="fa fa-bars fa-lg"></i></a>
			</button>
			<div class="dropdown-menu">
				<a href="../index.php?mn=2" class="dropdown-item"><i class="fa fa-home"></i>  Dashboard</a>
				<div class="dropdown-divider"></div>
				<? if($_SESSION['rego']['approve'] == 'all'){ ?>
				<a href="index.php?mn=20" class="dropdown-item">Approve payroll</a>
				<? } ?>
				<? if($_SESSION['rego']['approve'] != 'non'){ ?>
				<a href="index.php?mn=22" class="dropdown-item">Approve leave</a>
				<a href="index.php?mn=24" class="dropdown-item">Approve time</a>
				<? } ?>
			</div>
		</div>
		<div class="btn-group" style="float:right;"> 
			<button style="padding:0 8px; background:#111; cursor:default">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 0 0 0; border:0px solid #666" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>
	</div>

	<div class="topnav-custom hide-600">
		<div class="btn-group"> 
			<a href="../index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>
		<? if($_SESSION['rego']['approve'] == 'all'){ ?>
		<div class="btn-group <? if($_GET['mn']==20){echo 'active';}?>">
			<a href="index.php?mn=20">Approve payroll<? //=$lng['Leave application']?></a>
		</div>
		<? } ?>
		<? if($_SESSION['rego']['approve'] != 'non'){ ?>
		<div class="btn-group <? if($_GET['mn']==22){echo 'active';}?>">
			<a href="index.php?mn=22">Approve leave<? //=$lng['Leave application']?></a>
		</div>
		
		<div class="btn-group <? if($_GET['mn']==24){echo 'active';}?>">
			<a href="index.php?mn=24">Approve time<? //=$lng['Leave application']?></a>
		</div>
		<? } ?>
		<div class="btn-group" style="float:right;"> 
			<button data-toggle="dropdown" style="padding:0 8px; background:#111; cursor:default">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 0 0 0; border:0px solid #666" src="<?=ROOT.$_SESSION['rego']['img']?>?<?=time()?>">
			</button>
		</div>
	</div>
	
	<? if($logger){
			//$helpfile = getHelpfile(101);
			switch($_GET['mn']){
				case 20:
					include('approve_payroll.php'); break;
				case 22:
					include('approve_leave.php'); break;
				case 24:
					include('approve_time.php'); break;
			}
		}else{
			header('location: ../login.php');
		} ?>
	
	<? include('../include/modal_relog.php')?>

	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../assets/datepicker/bootstrap-datepicker.min.js"></script>
	<script src="../assets/datepicker/bootstrap-datepicker.th.js"></script>
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/rego.js"></script>
	<script src="../assets/js/jquery.sumoselect.js"></script>
	
	<? include('../include/common_script.php')?>
	
	<script type="text/javascript">
		headerCount = 1;
		
		$(document).ready(function() {

			var row_id;
			var leave_id;
			var action;
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,//scrY,//heights-260,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns: 	false,
				lengthChange: 	false,
				pageLength: 	14,
				paging: 		true,
				searching:		true,
				ordering:		true,
				filter: 		false,
				info: 			false,
				autoWidth:		false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				order: [[ 4, "asc" ]],
				columnDefs: [
					{ targets: [7,8,9,12,13,14,15,16], class: 'tac' },
					//{ targets: [1], class: 'pad2' },
					{ targets: [2], class: 'pad2 tac' },
					{ targets: [2], class: 'pad2' },
					{ targets: [10], class: 'pad05' },
					{ targets: [11], width: '60%' }
				],
				ajax: {
					url: "ajax/server_get_leaves_to_approve.php",
					type: "POST",
					data: function(d){
						d.empFilter = $("#empFilter").val();
						//d.statFilter = $("#statFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					$('.showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});
			
			$(document).on('click', '.approve_leave', function(e){
				row_id = $(this).closest('tr').find('.details').data('id');
				//leave_id = $(this).closest('tr').find('.leaveid').html();
				//action = $(this).data('action');
				alert(row_id)
				//e.preventDefault();
				//e.stopPropagation();
			});
			$(document).ajaxComplete(function( event,request, settings ) {
				$('.approve_leave').confirmation({
					container: 'body',
					rootSelector: '.approve_leave',
					singleton: true,
					animated: 'fade',
					placement: 'left',
					popout: true,
					html: true,
					title 			: '<?=$lng['Are you sure']?>',
					btnOkClass 		: 'btn btn-danger btn-sm',
					btnOkLabel 		: '<?=$lng['Lock']?>',
					btnOkIconContent: '',
					btnCancelClass 	: 'btn btn-success btn-sm',
					btnCancelLabel 	: '<?=$lng['Cancel']?>',
					onConfirm: function() { 
						alert('id');
					}
				});
			});
			
			$('body').on('hidden.bs.popover', function (e) {
				 $(e.target).data("bs.popover").inState.click = false;
			});			
			
			$(document).on("click", "a.balance", function(e){
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: ROOT+"leave/ajax/get_leave_balance.php",
					data: {emp_id: id},
					success:function(result){
						$("#leave_balance").html(result);
						//$('#dump').html(result);
						$("#modalLeaveBalance").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Sorry but someting went wrong']?> : ' + thrownError);
					}
				});
			});
			
			$(document).on("click", "a.details", function(e){
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: ROOT+"leave/ajax/get_leave_alt_details.php",
					data: {id: id},
					success:function(result){
						$("#leave_table1").html(result);
						//alert(result);
						$("#modalLeaveDetails").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Sorry but someting went wrong']?> ' + thrownError);
					}
				});
			});
			
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				//localStorage.setItem('activeTab', $(e.target).data('target'));
				dtable.columns.adjust().draw();
				htable.columns.adjust().draw();
			});

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








