<?php
	
	$history_lock = getHistoryLock();
	$hisLink = 'index.php?mn=441&type=all';
	if($history_lock == 1){
		$hisLink = 'index.php?mn=440&type=add';
	}
	//var_dump($history_lock);

	// echo '<pre>';
	// print_r($_SESSION['rego']);
	// echo '</pre>';

	$last = $_SESSION['rego']['cid'];
	$ref  = $_SESSION['rego']['ref'];
	
	$sql = "SELECT * FROM ".$last."_users WHERE ref = '".$ref."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			$com_users = $res->fetch_assoc();
		}
	}

	$nouser = '';
	if(isset($com_users)){

		$tmp = unserialize($com_users['permissions']);
		if(!$tmp){$tmp = array();}

		$PermissionArray['rego'] = $tmp;
	}else{

		$nouser = 'no-user';
	}


	if(!is_array($_SESSION['RGadmin']['access'])){
		$myaccess = $PermissionArray;
	}else{
		$myaccess = $_SESSION;
	}


// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

// die();
?>

	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<div class="dash-left">
		
		<div class="dashbox <? if($myaccess['rego']['payroll_result']['view']){echo 'purple';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=411';">
				<i class="fa fa-list-ul"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Payroll results']?></p>
					</div>
				</div>						
				
			</div>
		</div>		
		
		<div class="dashbox <? if($myaccess['rego']['payroll_attendance']['view']){echo 'green';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=410';">
				<i class="fa fa-history"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Monthly attendance']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox <? if($myaccess['RGadmin']['access'] || $myaccess['rego']['payroll_result']['approve']){echo 'orange';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=412';">
				<i class="fa fa-thumbs-up"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Payroll approval']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox <? if($myaccess['rego']['payroll_benefits']['view'] && $myaccess['rego']['standard'][$standard]['pr_benefits']){echo 'teal';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=603';">
				<i class="fa fa-cogs"></i>
				<div class="parent">
					<div class="child">
						<p>Variable Benefits<? //=$lng['User settings']?></p>
					</div>
				</div>						
				
			</div>
		</div>
		<div class="dashbox <? if($myaccess['rego']['payroll_calculations']['view'] && $myaccess['rego']['standard'][$standard]['pr_individual']){echo 'reds';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=610';">
				<i class="fa fa-calculator"></i>
				<div class="parent">
					<div class="child">
						<p>Individual calculations<? //=$lng['Payroll settings']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox <? if($myaccess['rego']['payroll_forms']['view']){echo 'dblue';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=430&sm=41';">
				<i class="fa fa-file-text-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Government forms']?></p>
					</div>
				</div>						
			</div>
		</div>
		
		<div class="dashbox <? if($myaccess['rego']['payroll_export']['view']){echo 'teal';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=420&sm=40';">
				<i class="fa fa-file-excel-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Export center']?></p>
					</div>
				</div>						
			</div>
		</div>
				
		<div class="dashbox <? if($myaccess['rego']['payroll']['report']){echo 'reds';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='reports/index.php?mn=456';">
				<i class="fa fa-file-pdf-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Report center']?></p>
					</div>
				</div>						
				
			</div>
		</div>

		<div class="dashbox <? if($myaccess['rego']['payroll']['archive']){echo 'brown';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='../archive/index.php';">
				<i class="fa fa-file-archive-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Archive center']?></p>
					</div>
				</div>						
			</div>
		</div>
		
		<div class="dashbox <? if($myaccess['rego']['payroll_historical']['view']){echo 'blue';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='<?=$hisLink?>';">
				<i class="fa fa-history"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Historical data']?></p>
					</div>
				</div>						
			</div>
		</div>
		
	</div>
	
	<div class="dash-right">
				
		<div class="notify_box">
			<h2 style="background:#a00"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Employees to complete for payroll']?></h2>
			<div class="inner">
				<? if(checkEmployeesForPayroll($cid)){
					echo checkEmployeesForPayroll($cid);
				}else{
					echo '<b><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;'.$lng['All employees are set for Payroll'].'</b>';
				}?>
			</div>
		</div>
				
		<div class="notify_box">
			<h2 style="background:#a00"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Complete setup tasks']?></h2>
			<div class="inner">
				<? if($checkSetup){
					echo $checkSetup;
				}else{
					echo '<b><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;'.$lng['All mandatory System settings are set'].'</b><br>';
				}?>
			</div>
		</div>
	</div>

	<script type="text/javascript">

	$(document).ready(function() {
		
		
	});

</script>
						
