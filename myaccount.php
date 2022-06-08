<?php
	
	$history_lock = getHistoryLock();
	$hisLink = 'index.php?mn=441&type=all';
	if($history_lock == 1){
		$hisLink = 'index.php?mn=440&type=add';
	}
	//var_dump($checkSetup); exit;
	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();

	$last = $_SESSION['rego']['cid'];
	$ref  = $_SESSION['rego']['ref'];
	$customers  = $_SESSION['rego']['customers'];

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






?>
	<div style="padding:0 0 0 20px" id="dump"></div>

		<?php if($nouser == 'no-user' && (!is_array($_SESSION['RGadmin']['access']))){ 

			echo '<br><br><br><div class="msg_nopermit">You have no permission to view this<br>User is suspended for this company</div>'; 
		}else{ ?>

			<div class="dash-left">
				
				<div class="dashbox <? if($myaccess['rego']['settings']['access']){echo 'reds';}else{echo 'disabled';} ?>">
					<div class="inner" onclick="window.location.href='settings/index.php?mn=313';">
						<i class="fa fa-cogs"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Cookie Consent Setting']?></p>
							</div>
						</div>						
					</div>
				</div>

			</div>



	<?php } ?>
