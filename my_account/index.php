<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include('../files/functions.php');
	include('../files/payroll_functions.php');
	include('../files/arrays_'.$_SESSION['rego']['lang'].'.php');

	$logger = false;
	$data = array();
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
			
			if($res = $dbc->query("SELECT * FROM ".$cid."_employees WHERE emp_id = '".$_SESSION['rego']['emp_id']."'")){
				$data = $res->fetch_assoc();
			}
		}
		$years = getYears();
		//var_dump($years);
	}
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
		<link rel="stylesheet" href="../assets/css/jquery-clockpicker.min.css">
		
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
		
	<? include('../include/main_header.php');?>
	
	<div class="topnav-custom">
		<div class="btn-group"> 
			<a href="../index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>
		
		<div class="btn-group <? if($_GET['mn']==2){echo 'active';}?>">
			<a href="index.php?mn=2"><?=$lng['Personal data']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==3){echo 'active';}?>">
			<a href="index.php?mn=3"><?=$lng['Payslips']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==4){echo 'active';}?>">
			<a href="index.php?mn=4"><?=$lng['Year overview']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==5){echo 'active';}?>">
			<a href="index.php?mn=5"><?=$lng['Leave application']?></a>
		</div>
		<div style="float:right" class="btn-group">
			<a data-toggle="modal" data-target="#passModal"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></a>
		</div>
		<div style="float:right" class="btn-group">
			<a data-toggle="modal" data-target="#modalContactForm"><?=$lng['Contact']?></a>
		</div>
		<? if(count($years) > 1){ ?>	<!-- Change year ---------------------------------------------------------------------->
		<div class="btn-group" style="float:right">
			<button data-toggle="dropdown">
				<?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<? foreach($years as $k=>$v){ ?>
				<li><a data-year="<?=$k?>" class="changeYear"><?=$v?></a></li>
				<? } ?>
			</ul>
		</div>
		<? }else{ ?>
			<div style="float:right" class="btn-group">
				<a><?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?></a>
			</div>
		<? } ?>

	</div>
	
	<? if($logger){
			switch($_GET['mn']){
				case 2: 
					include('../e/emp_personal_data.php'); break;
				case 3: 
					include('../e/emp_payslips.php'); break;
				case 4: 
					include('../e/emp_year_overview.php'); break;
				case 5: 
					include('../e/emp_leave_application.php'); break;
				/*case 914: 
					include('emp_ot_application.php'); break;
				case 916: 
					include('emp_ot_application2.php'); break;
				case 915: 
					include('emp_work_schedule.php'); break;*/
			}
		}else{
			header('location: ../login.php');
		} ?>
	

	<div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:600px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Contact']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 40px 30px 40px">
					<form style="padding-top:5px" id="contactForm" class="sform" enctype="multipart/form-data">
						<input name="name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
						<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
						<input name="email" type="hidden" value="<?=$_SESSION['rego']['username']?>" />
						<input name="phone" type="hidden" value="<?=$_SESSION['rego']['phone']?>" />
						<input style="visibility:hidden; height:0;" id="contactAttach" type="file" name="contactAttach" />

						<label><?=$lng['Subject']?> <i class="man"></i></label>
						<input name="subject" id="subject" type="text" />
						
						<label><?=$lng['Message Question']?> <i class="man"></i></label>
						<textarea name="comment" id="comment" rows="4"></textarea>
						
						<div style="height:3px"></div>
						<div id="contactMsg" style="color:#c00; padding:2px 0; font-weight:600; display:none"></div>
						<div style="height:7px"></div>
						
						<table><tr><td>
						<button onClick="$('#contactAttach').click()" class="btn btn-default btn-xs" style="display:block; margin:0" type="button"><?=$lng['Attachement']?></button></td><td style="padding-left:10px"><span id="attachMsg"><?=$lng['No file selected']?></span></td></tr></table>

						<div style="height:15px"></div>
						
						<button id="contactBtn" style="float:left" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Send']?></button>
						
						<button style="float:right" type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						
						<div class="clear"></div>
					</form>
					</div>
			  </div>
		 </div>
	</div>

	<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
						<form id="changePassForm" class="sform" style="padding-top:10px;">
							 
							 <label><?=$lng['Old password']?> <i class="man"></i></label>
							 <input name="opass" id="opass" type="text" />
							 
							 <label><?=$lng['New password']?> <i class="man"></i></label>
							 <input name="npass" id="npass" type="password" />
							 
							 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
							 <input name="rpass" id="rpass" type="password" />
							
							<div style="height:2px"></div>
							<div id="passMsg" style="color:#c00; padding:2px 0; font-weight:600; display:none"></div>
							<div style="height:8px"></div>
							 
							<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
							
							<button style="float:right" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
							<div class="clear"></div>
							
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
	<script src="../assets/js/rego.js?<?=time()?>"></script>
	
	<? include('../include/common_script.php')?>
	
	
	
	
<script type="text/javascript">
		
	$(document).ready(function() {
 		
		$("#contactForm").submit(function(e){
			e.preventDefault();
			$("#contactBtn").prop('disabled', true);
			$("#contactBtn i").removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			var formData = new FormData($(this)[0]);
			$.ajax({
				url: ROOT+"ajax/send_contact_mail.php",
				data: formData,
				type: "POST", 
				cache: false,
				processData:false,
				contentType: false,
				success: function(response){
					//$('#dump').html(response)
					if(response=='success'){
						$('#contactMsg').html('<?=$lng['Mail send successfully']?>').fadeIn(400);
						setTimeout(function(){
							$('#modalContactForm').modal('toggle');
						},2000);
					}else if(response=='empty'){
						$('#contactMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}else{
						$('#contactMsg').html('<?=$lng['Error']?> : ' + response).fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#contactMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				}
			});
		});
		$('#modalContactForm').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#contactMsg").html('');
			$("#contactAttach").val('');
		});

		$("#changePassForm").submit(function(e){
			e.preventDefault();
			var formData = $(this).serialize();
			//alert(formData)
			$.ajax({
				url: ROOT+"ajax/change_employee_password.php",
				data: formData,
				success: function(response){
					//$('#dump3').html(response)
					if(response=='success'){
						$('#passMsg').html('<?=$lng['Password changed successfuly']?>').fadeIn(400);
						setTimeout(function(){
							$('#passModal').modal('toggle');
						},2000);
					}else if(response=='empty'){
						$('#passMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
					}else if(response=='short'){
						$('#passMsg').html('<?=$lng['New password to short min 8 characters']?>').fadeIn(400);
					}else if(response=='same'){
						$('#passMsg').html('<?=$lng['New passwords are not the same']?>').fadeIn(400);
					}else if(response=='old'){
						$('#passMsg').html('<?=$lng['Old Password is wrong']?>').fadeIn(400);
					}else{
						$('#passMsg').html('<?=$lng['Error']?> : '+response).fadeIn(400);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#passMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
				}
			});
		});

	});
		
</script>
	
</body>
</html>








