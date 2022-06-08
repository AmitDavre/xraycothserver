<?php

	if(session_id()==''){session_start();} 
	ob_start();
	include('../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	$logger = false;
	$price_table = array();
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
		$sql = "SELECT price_schedule FROM rego_company_settings";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_assoc()){
				$price_table = unserialize($row['price_schedule']);
			}
		}
	}
	//$myhelpfile = getHelpfile(5);
	//echo($myhelpfile); exit;
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
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

		<!--<link rel="stylesheet" type="text/css" href="../css/myStyle.css?<?=time()?>">-->

		<script src="../assets/js/jquery-3.2.1.min.js"></script>
		<script src="../assets/js/jquery-ui.min.js"></script>
	
	
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
		<div class="btn-group <? if($_GET['mn']==5){echo 'active';}?>">
			<a href="index.php?mn=5"><?=$lng['My profile']?></a>
		</div>
		<div class="btn-group <? if($_GET['mn']==6){echo 'active';}?>">
			<a href="index.php?mn=6"><?=$lng['Company']?></a>
		</div>
		<!--<div class="btn-group <? if($_GET['mn']==7){echo 'active';}?>">
			<a href="index.php?mn=7"><?=$lng['Purchase']?> / <?=$lng['Upgrade']?></a>
		</div>-->
	</div>
	
	<? if($logger){
			$myhelpfile = getHelpfile(5);
			switch($_GET['mn']){
				case 5: 
					include('my_profile.php'); 
					break;
				case 6: 
					include('my_company.php'); 
					break;
				case 7: 
					include('my_purchase.php'); 
					break;
				/*case 8: 
					include('my_payment.php'); 
					break;
				case 9: 
					include('my_history.php'); 
					break;*/
			}
		}else{
			header('location: ../login.php');
		} ?>

	<!-- Modal Contactform -->
	<div class="modal fade" id="modalContactForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:600px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Contact']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 40px 30px 40px">
					<span style="font-weight:600; color:#cc0000;" id="conMess"></span>
					<form method="post" style="padding-top:5px" id="contactForm" class="sform" enctype="multipart/form-data">
						<input name="name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
						<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
						<input name="email" type="hidden" value="<?=$_SESSION['rego']['username']?>" />
						<!--<input name="phone" type="hidden" value="<?=$_SESSION['rego']['phone']?>" />-->
						<input style="visibility:hidden; height:0;" id="contactAttach" type="file" name="contactAttach" />

						<label><?=$lng['Subject']?> <i class="man"></i></label>
						<input name="subject" id="subject" type="text" />
						
						<label><?=$lng['Message Question']?> <i class="man"></i></label>
						<textarea name="comment" id="comment" rows="5"></textarea>
						<div style="height:10px"></div>
						
						<table><tr><td>
						<button onClick="$('#contactAttach').click()" class="btn btn-default btn-xs" style="display:block; margin:0" type="button"><?=$lng['Attachement']?></button></td><td style="padding-left:10px"><span id="err_msg"><?=$lng['No file selected']?></span></td></tr></table>

						<div style="height:15px"></div>
						<button id="eSendContact" style="float:left" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Send']?></button>
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
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changePassword" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Old password']?> <i class="man"></i></label>
						 <input name="opass" id="opass" type="text" />
						 <label><?=$lng['New password']?> <i class="man"></i></label>
						 <input name="npass" id="npass" type="password" />
						 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
						 <input name="rpass" id="rpass" type="password" />
						 <button class="btn btn-primary btn-sm" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
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
	<script src="../assets/js/bootstrap-datepicker.min.js"></script>
	<script src="../assets/js/bootstrap-datepicker.th.js"></script>
	<script src="../assets/js/bootstrap-confirmation.js"></script>
	<script src="../assets/js/jquery.numberfield.js"></script>	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/overhang.min.js?<?=time()?>"></script>
	<script src="../assets/js/rego.js?<?=time()?>"></script>
	
	<? include('../include/common_script.php')?>
	
<script type="text/javascript">
		
	$(document).ready(function() {
 		
		$('#toggleHelp').click(function() {
			$('#myhelp').animate({right: '0px'}, 200);
		});
 		$('.closemyHelp').on('click', function (e) {
			$('#myhelp').animate({right: '-500px'}, 200);
		});

	});
		
</script>
	
	<? if(!empty($myhelpfile)){ ?>		
		<div id="myhelp">
			<div class="closemyHelp"><i class="fa fa-arrow-circle-right"></i></div>
			<div class="innerHelp">
				<?=$myhelpfile?>
			</div>
		</div>
	<? } ?>
	
</body>
</html>








