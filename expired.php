<?php

	if(session_id()==''){session_start();} 
	ob_start();

	
	
	$isMob = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
	$isTab = is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "tablet"));
	$isDesktop = !$isMob && !$isTab;
	if($isMob || $isTab){
		header('location: mob/login.php'); exit;
	}

	include('dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$logger = false;
	$years = '';
	$checkSetup = '';
	$periods = array();
	//$_SESSION['rego']['locked'] = true;
	$locked = true;
	$program = 20;
	$customers = array();
	$history_lock = 1;
	$suspended = 0;
	$expire_date = '';
	$days_left = 365;
	$price_table = array();
	$buyrego = false;
	$BuyRego = $lng['Buy REGO'];
	//var_dump($_SESSION['rego']['cid']); //exit;
	
	if(isset($_SESSION['rego']['cid']) && !empty($_SESSION['rego']['cid'])){
		
		// CHECK IF CUSTOMER IS NOT SUSPENDED //////////////////////////////////////////////////////////////
		$sql = "SELECT * FROM rego_customers WHERE clientID = '".$cid."'";
		if($res = $dbx->query($sql)){
			if($row = $res->fetch_assoc()){
				if($row['status'] == 0){ $suspended = 1;}
				$_SESSION['rego']['version'] = $row['version'];
				$standard = $row['version'];
				$_SESSION['rego']['max'] = $row['employees'];
				$_SESSION['rego']['emp_platform'] = $row['emp_platform'];
				$_SESSION['rego']['phone'] = $row['phone'];
				$_SESSION['rego']['email'] = $row['email'];
				$expire_date = $row['period_end'];
				$diff = strtotime($row['period_end']) - strtotime(date('d-m-Y'));
				$days_left = floor($diff / (60*60*24));
				if($days_left < 0){$days_left = 0;}
				if($row['version'] > 0 && $days_left < 30){$buyrego = true; $BuyRego = $lng['Extend REGO'];}
				if($row['version'] == 0){$buyrego = true;}
				if($row['status'] == 2){$buyrego = false;}
				if($days_left > 35){$buyrego = false;}
				$_SESSION['rego']['expire'] = $days_left;
				$_SESSION['rego']['status_val'] = $row['status'];
			}
		}
		
		if(isset($_SESSION['RGadmin'])){
			$logtime = 86000;
		}else{
			$logtime = (int)$comp_settings['logtime'];
		}
		//var_dump($logtime);
		if($logtime < 60){
			$logtime = 900; // 15 min
		}
		//var_dump($logtime);
		if(time() - $_SESSION['rego']['timestamp'] > $logtime) {
			$_SESSION['rego']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['rego']['timestamp'] = time();
			$logger = true;
			$years = getYears(); // Get payroll Years
			//var_dump($years);
			//$years = array(2019=>2019);//$years[$lang];
			$_SESSION['rego']['payroll_dbase'] = $_SESSION['rego']['cid'].'_payroll_'.$_SESSION['rego']['cur_year'];
			$_SESSION['rego']['emp_dbase'] = $cid.'_employees';
			//$_SESSION['rego']['paydate'] = getPaydate($cid);
			if(!isset($_SESSION['rego']['period'])){$_SESSION['rego']['period'] = $lng['Select period'];}
			//$_SESSION['rego']['period'] = $months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang];
			if($_SESSION['rego']['customers']){
				$customers = getCustomers($_SESSION['rego']['customers']);
			}
			//var_dump($customers);
			//$periods = getPayrollPeriods($lang);
			//$to_lock = $periods['to_lock'];
			//$to_unlock = $periods['unlock'];
			//$period = $periods['period'];
			//var_dump($teams);
			//$locked = getLockedMonth($_SESSION['rego']['cur_month']);
			//$_SESSION['rego']['locked'] = $locked;
			//$history_lock = getHistoryLock($cid);
			$checkSetup = checkSetupData($cid);
			$periods = getPayrollPeriods($lang);
			$period = $periods['period'];
			//getFormdate($cid);
			//echo($checkSetup);
		}
	}
	//var_dump($logtime); //exit;
	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	if(!isset($_GET['mn'])){$_GET['mn'] = 1;}
	if($sys_settings['demo'] == 0){$_GET['mn'] = 3;}
	//var_dump($days_left); exit;

	//check login user in each company...
	if($_SESSION['rego']['cid'] != ''){
		$checksqlss = "SELECT * FROM ".$_SESSION['rego']['cid']."_users WHERE username = '".$_SESSION['rego']['username']."'";
		$resdds = $dbc->query($checksqlss);
		if($resdds->num_rows > 0){
			$rowxz = $resdds->fetch_assoc();

			$typecchk = $rowxz['type'];
		}else{
			$typecchk = '';
		}
	}else{
		$typecchk ='';
	}

	// echo $rego;
	// die();

		// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();

	// GET ADMIN EMAIL
	$my_dbaname = $prefix.'admin';


	$dbadmin = new mysqli($my_database,$my_username,$my_password,$my_dbaname);
	mysqli_set_charset($dbadmin,"utf8");
	if($dbadmin->connect_error) {
		echo'<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error: ('.$dbadmin->connect_errno.') '.$dbadmin->connect_error.'<br>Please try again later or report this error to <a href="mailto:admin@regohr.com">admin@regohr.com</a></p>';
	}

	$sql102 = "SELECT * FROM rego_company_settings WHERE id = '1'";

	if($res102 = $dbadmin->query($sql102)){
		if($res102->num_rows > 0){
			if($row102 = $res102->fetch_assoc())
				{
					$admin_mail_value = $row102['admin_mail'];  // SELECTED TEAMS STORED IN SESSION 
						
				}
		}
	}


	
?>

<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
    
		<link rel="stylesheet" href="assets/css/bootstrap.min.css?<?=time()?>">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/line-awesome.min.css">
		<link rel="stylesheet" href="assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/navigation.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
		<link rel="stylesheet" href="assets/css/myBootstrap.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/basicTable.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/myForm.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/overhang.min.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/responsive.css?<?=time()?>">
		<link rel="stylesheet" href="assets/css/sumoselect-menu.css?<?=time()?>">
		
		<script src="assets/js/jquery-3.2.1.min.js"></script>
		<script src="assets/js/jquery-ui.min.js"></script>

		<script>
			//var headerCount = 2;
			var lang = <?=json_encode($lang)?>;
			//var mn = <? //=json_encode($_GET['mn'])?>;
			//var setup = <? //=json_encode($checkSetup)?>;
			var dtable_lang = <?=json_encode($dtable_lang)?>;
			var ROOT = <?=json_encode(ROOT)?>;
			//var locked = <? //=json_encode($_SESSION['rego']['locked'])?>;
			var logtime = <?=json_encode($logtime)*1000?>;
		</script>

	</head>

	<body>
	
	<? include('include/main_header.php');?>
	
	<div class="topnav-custom">
		<!-- BACK TO XRAY ADMIN /////////////////////////////////////////////////////////////////////-->
		<? if(isset($_SESSION['RGadmin']['id'])){ ?>
		<div class="btn-group"> 
			<a href="admin/index.php?mn=2" class="nav-link">&nbsp;<i class="fa fa-font fa-lg"></i>&nbsp;</a>
		</div>
		<? } ?>
		
		<div class="btn-group <? if($_GET['mn'] == 2){echo 'active';}?>"> 
			<a href="index.php?mn=2" class="home"><i class="fa fa-home"></i></a>
		</div>

		<? if(count($customers) > 1){
			//if((!isset($_SESSION['RGadmin']['id']) && $_GET['mn'] == 2) || $days_left <= 0){ 
			if((!isset($_SESSION['RGadmin']['id']) && ($_GET['mn'] == 2) || $_GET['mn'] == 3)){ ?>
			<div class="btn-group">
				<button type="button" class="dropdown-toggle" data-toggle="dropdown">
					Companies<? //=$customers[$cid]?>
				</button>
				<div class="dropdown-menu">
					<? foreach($customers as $k=>$v){ ?>
					<a class="dropdown-item changeCustomer" data-cid="<?=$k?>"><?=strtoupper($k)?> - <?=$v?></a>
					<? } ?>
				</div>
			</div>
		<? }} ?>
		
		<? include('include/main_menu_selection.php'); ?>
		
		<!--Move year to settings ???--> 
		<!--<div class="btn-group" style="float:right"> 
			<button class="dropdown-toggle" data-toggle="dropdown">
				Year <span class="caret"></span>
			</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#">2020</a>
					<a class="dropdown-item" href="#">2019</a>
				</div>
		</div>-->	

		<!-- USER ///////////////////////////////////////////////////////////////////////////////////////-->
		<? if($_GET['mn'] == 2){ ?>
		<div class="btn-group hide-xs" style="float:right; background:#000 !important">
			<button class="dropdown-toggle" data-toggle="dropdown" style="padding:0 10px 0 0">
				 <img style="height:35px; width:35px; display:inline-block; border-radius:0px; margin:-3px 10px 0 10px; border:0px solid #666" src="<?=$_SESSION['rego']['img']?>"><b><?=$_SESSION['rego']['name']?></b>
			</button>
				<? if(!isset($_SESSION['RGadmin']['id'])){?>
				<div class="dropdown-menu dropdown-menu-right" style="max-width:180px">
					<img style="width:100%; padding-bottom:2px" src="<?=$_SESSION['rego']['img']?>">
					<!--<a class="dropdown-item" xhref="myrego/index.php?mn=5" style="color:#ccc"><i class="fa fa-user"></i>&nbsp; My REGO</a>-->
					<? if(!empty($_SESSION['rego']['emp_id'])){ ?>
					<a class="dropdown-item" href="my_account/index.php?mn=2"><i class="fa fa-user"></i>&nbsp; <?=$lng['My account']?></a>
					<? } ?>
					<a class="dropdown-item" data-toggle="modal" data-target="#passModal"><i class="fa fa-key"></i>&nbsp; <?=$lng['Change password']?></a>
					<a class="dropdown-item logout"><i class="fa fa-sign-out"></i>&nbsp; <?=$lng['Sign out']?></a>
				</div>
				<? } ?>
		</div>
		<? } ?>
		
		<? if($_GET['mn'] == 2){ ?>
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
						<a class="dropdown-item selectMonth" data-id="<?=$k?>"><?=$v?></a>
					<? } ?>
				</div>
			</div>
			
			
			<? if(count($years) > 100){ ?>	<!-- Change year ---------------------------------------------------------------------->
			<div class="btn-group" style="float:right">
				<button class="dropdown-toggle" data-toggle="dropdown">
					<?=$lng['Year'].' '.$_SESSION['rego']['year_'.$lang]?>
				</button>
				<div class="dropdown-menu">
					<? foreach($years as $k=>$v){ ?>
					<a data-year="<?=$k?>" class="dropdown-item changeYear"><?=$v?></a>
					<? } ?>
				</div>
			</div>
			<? } ?>
		
		<? } if($_GET['mn'] == 2 || $_GET['mn'] == 3 || $_GET['mn'] == 4){ ?>
			<? //if($days_left > 0){ ?>
			<div class="btn-group hide-xs <? if($_GET['mn'] == 3){echo 'active';}?>" style="float:right">
				<a href="index.php?mn=3"><?=$lng['Welcome']?></a>
			</div>
			<? //} ?>
			<? if($buyrego){ ?>
			<div class="btn-group hide-xs <? if($_GET['mn'] == 5){echo 'active';}?>" style="float:right">
				<a href="myrego/index.php?mn=5"><?=$BuyRego?> : <?=$days_left?> <?=$lng['days remaining']?></a>
			</div>
			<? } ?>
		<? } ?>
	</div>

	<div class="page-wrap d-flex flex-row align-items-center">
	    <div class="container">
	        <div class="row justify-content-center">
	            <div class="col-md-12 text-center">
	                <span class="display-1 d-block" style="margin-top: 200px;">There is a problem with the subscription of your account.</span>
	                <div class="mb-4 lead" style="margin-top: 70px;font-size: 20px;">Please contact us at <?php echo $admin_mail_value ;?> for further assistance.</div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changeUserPassword" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Old password']?> <i class="man"></i></label>
						 <input name="opass" id="opass" type="password" />
						 <label><?=$lng['New password']?> <i class="man"></i></label>
						 <input name="npass" id="npass" type="password" />
						 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
						 <input name="rpass" id="rpass" type="password" />
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	
	<? include('include/modal_relog.php')?>

	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/bootstrap-datepicker.min.js"></script>
	<script src="assets/js/bootstrap-confirmation.js"></script>
	<script src="assets/js/jquery.mask.js"></script>	
	<script src="assets/js/overhang.min.js"></script>
	<script src="assets/js/jquery.sumoselect-menu.js"></script>
	<script src="assets/js/rego.js?<?=time()?>"></script>
	
	<? include('include/common_script.php')?>
	<? include('include/main_menu_script.php')?>
	
	
	
	
	
	
	
	
	</body>
</html>













