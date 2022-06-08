<?php
	

	// echo '<pre>';
	// print_r($_SESSION['rego']);
	// echo '</pre>';
	// die();
?>

	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<div class="dash-left">
		
		<div class="dashbox <? if($_SESSION['rego']['leave_application']['view']){echo 'dblue';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=201';">
				<i class="fa fa-plane"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Leave application']?></p>
					</div>
				</div>						
				
			</div>
		</div>		
		
		<div class="dashbox   <? if($_SESSION['rego']['leave_approve']['view']){echo 'green';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=203';">
				<i class="fa fa-thumbs-up"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Approve leave period']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox <? if($_SESSION['rego']['leave_calendar']['view']){echo 'purple';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=202';">
				<i class="fa fa-calendar"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Leave calendar']?></p>
					</div>
				</div>						
				
			</div>
		</div>

		<div class="dashbox  <? if($_SESSION['rego']['leave']['report']){echo 'reds';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=205';">
				<i class="fa fa-file-pdf-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Report center']?></p>
					</div>
				</div>						
				
			</div>
		</div>

		<div class="dashbox  disabled <? //if($_SESSION['rego']['leave']['archive']){echo 'brown';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=205';">
				<i class="fa fa-file-archive-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Archive center']?></p>
					</div>
				</div>						
			</div>
		</div>
		
		
	</div>
	
	<div class="dash-right">
		<div class="notify_box">
			<h2 style="background:#a00"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Complete setup tasks']?></h2>
			<div class="inner">
				<? //if($checkSetup){
					//echo $checkSetup;
				//}else{
					echo '<b><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;'.$lng['All mandatory System settings are set'].'</b><br>';
				//}?>
			</div>
		</div>
	</div>

	<script type="text/javascript">

	$(document).ready(function() {
		
		
	});

</script>
						
