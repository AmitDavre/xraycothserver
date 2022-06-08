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
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/overhang.min.css?<?=time()?>">
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
	<!-- Modal Login -->
	<div class="modal fade" id="modalExpired" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:350px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Logtime expired']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 30px 30px">
						<div style="font-size:15px; padding-bottom:4px"><?=str_replace('{(name)}', '<b>'.$_SESSION['rego']['fname'].'</b>', $lng['Hi are you still there'])?></div><br>
						<span id="relogMsg" style="color:#b00"></span>
						<form id="relogForm" class="sform" style="padding-top:6px;">
							 <input placeholder="<?=$lng['Password']?>" name="repassword" type="password" />
							 <button class="btn btn-primary btn-sm" style="margin-top:10px; float:left" type="submit"><i class="fa fa-sign-in"></i>&nbsp;<?=$lng['Log-in']?></button>
							 <button class="btn btn-primary btn-sm logout" style="margin-top:10px; float:right" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Exit']?></button>
							 <div style="clear:both"></div>
						</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
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
		
		$(document).ready(function() {

			headerCount = 1;
			var row_id;
			var leave_id;
			var action;
			
			var htable = $('#history_table').DataTable({
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
				autoWidth:		true,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				order: [[ 4, "desc" ]],
				columnDefs: [
					{ targets: [10,11], class: 'tac' },
					//{ targets: [1], class: 'pad2' },
					{ targets: [2], class: 'pad2 tac' },
					//{ targets: [2], class: 'pad2' },
					//{ targets: [10], class: 'pad05' },
					//{ targets: [11], width: '60%' }
				],
				ajax: {
					url: ROOT+"approve/ajax/server_get_leave_history.php",
					type: "POST",
					data: function(d){
						//d.empFilter = $("#empFilter").val();
						//d.statFilter = $("#statFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					//$('.showTable').fadeIn(200);
					htable.columns.adjust().draw();
				}
			});
			
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
						//alert(id);
						//return false;
						$.ajax({
							url: ROOT+"payroll/ajax/delete_employee_from_payroll.php",
							data:{id:last_id},
							success: function(ajaxresult){
								dtable.ajax.reload(null, false);
								$.getJSON('ajax/get_missing_employees_payroll.php', function(result) {
									var options = $('#addEmp');
									options.empty()
									options.append(new Option('Add employee', ''));
									$.each(result, function(index, txt) {
										options.append(new Option(txt, index));
									});
								});							
							}
						});
					}
				});
			});
					
			
			
			
			
			$(document).ajaxComplete(function( event,request, settings ){		
				//$('.approve').confirmation({
					//container: 'body',
					//rootSelector: '.approve',
					//singleton: true,
					//animated: 'fade',
					//placement: 'right',
					//popout: true,
					//html: true,
					/*title 			: '<?=$lng['Are you sure']?>',
					btnOkClass 		: 'btn btn-danger btn-sm',
					btnOkLabel 		: '<?=$lng['Approve']?>',
					btnOkIconContent: '',
					btnCancelClass 	: 'btn btn-success btn-sm',
					btnCancelLabel 	: '<?=$lng['Cancel']?>',*/
					//onConfirm: function() { 
						//alert('confirm')
						/*$.ajax({
							url: ROOT+"leave/ajax/save_leave_action.php",
							data:{row_id: row_id, leave_id: leave_id, action: action},
							success: function(result){
								//$("#dump").html(result);
								if(result == 'success'){
									$("body").overhang({
										type: "success",
										message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Database updated successfuly',
										duration: 2,
									})
								}else{
									$("body").overhang({
										type: "warn",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Error : ' + result,
										duration: 4,
										closeConfirm: true
									})							
								}						
								dtable.ajax.reload(null, false);
								//location.replace('index.php?mn=4');
							},
							error:function (xhr, ajaxOptions, thrownError){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Error : ' + thrownError,
									duration: 6,
									closeConfirm: true
								})							
							}
						});*/
					//}
				//});
			})
			
			$(document).on("click", "a.reject", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				$('#pop_title').html('<i class="fa fa-thumbs-down"></i>&nbsp; Reject reason');
				e.preventDefault();
				e.stopPropagation();
			});
			
			$(document).on("click", "a.cancel", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				$('#pop_title').html('<i class="fa fa-times-circle"></i>&nbsp; Cancel reason');
				//$('#popForm').removeClass().addClass('cancelForm');
				e.preventDefault();
				e.stopPropagation();
			});
			
			$(document).on('click','.butCancel', function(e) {
				$('body [data-toggle="popOver"]').popover('hide');
			});			
			
			/*var popActionSettings = {
				placement: 'right',
				container: 'body',
				html: true,
				selector: '[data-toggle="popOver"]', //Sepcify the selector here
				title: '<span id="pop_title">Title</span>',
				content: '<form id="popForm" class="popReject">'+
						'<div><textarea placeholder="<?=$lng['Reason']?>" name="comment" rows="3" style="width:350px; border:0; padding:0;resize:none;color:#333"></textarea></div>'+
						'<div style="padding:10px 0 5px 0">'+
						'<button type="submit" class="btn btn-default btn-xs butReject" style="display:inline-block;float:left"><i class="fa fa-thumbs-down-o"></i>&nbsp;Submit</button>'+
						'<button type="button" class="btn btn-default btn-xs butCancel" style="display:inline-block;float:right">Cancel</button>'+
						'<div style="clear:both;"></div></div></form>'
			}*/	
			//var popOver = $('body').popover(popActionSettings);

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








