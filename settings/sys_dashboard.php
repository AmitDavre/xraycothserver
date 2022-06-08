<?php
	
	$create_year = false;
	if(date('m') == 12 && !in_array(($cur_year+1), $years)){$create_year = true;}
	if(!isset($years[date('Y')])){$create_year = true;}

	//var_dump($years);
?>

	<div style="padding:0 0 0 20px" id="dump"></div>
	
	<div class="dash-left">
		<div class="dashbox teal">
			<div class="inner" onclick="window.location.href='index.php?mn=6010';">
				<i class="fa fa-cog"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Company settings']?></p>
					</div>
				</div>						
			</div>
		</div>
		<div class="dashbox <? if($_SESSION['rego']['employee']['settings'] && $_SESSION['rego']['standard'][$standard]['set_employee']){echo 'reds';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=602';">
				<i class="fa fa-cog"></i>
				<div class="parent">
					<div class="child">
						<p>Employee defaults<? //=$lng['Employee defaults']?></p>
					</div>
				</div>						
			</div>
		</div>
		<div class="dashbox green<? //if($_SESSION['rego']['sys_users']['access'] == 1){echo 'green';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=603';">
				<i class="fa fa-user"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['User settings']?></p>
					</div>
				</div>						
				
			</div>
		</div>
		<div class="dashbox <? if($_SESSION['rego']['payroll']['settings']){echo 'dblue';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=610';">
				<i class="fa fa-money"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Payroll settings']?></p>
					</div>
				</div>						
			</div>
		</div>
		<div class="dashbox <? if($_SESSION['rego']['leave']['settings'] && $_SESSION['rego']['standard'][$standard]['leave']){echo 'blue';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=608';">
				<i class="fa fa-plane"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Leave settings']?></p>
					</div>
				</div>						
			</div>
		</div>
		<!-- <div class="dashbox purple">	 -->

			<div class="dashbox <? if($_SESSION['rego']['time']['settings'] && $_SESSION['rego']['standard'][$standard]['time']){echo 'purple';}else{echo 'disabled';} ?>">
			<div class="inner" onclick="window.location.href='index.php?mn=607';">
				<i class="fa fa-clock-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Time settings']?></p>
					</div>
				</div>						
			</div>
		</div>
		<div class="dashbox orange">
			<div class="inner" onclick="window.location.href='index.php?mn=609';">
				<i class="fa fa-rebel"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Public holidays']?></p>
					</div>
				</div>						
			</div>
		</div>
		<div class="dashbox brown">
			<div class="inner" onclick="window.location.href='index.php?mn=605';">
				<i class="fa fa-calculator"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Accounting']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox green">
			<div class="inner" onclick="window.location.href='index.php?mn=300';">
				<i class="fa fa-comments"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Communication center']?></p>
					</div>
				</div>						
			</div>
		</div>
		
		<div id="newyearBtn" class="dashbox <? if($create_year){echo 'reds';}else{echo 'disabled';}?>">
			<div class="inner" id="createNewYear">
				<i class="fa fa-database"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Create new year']?></p>
					</div>
				</div>						
			</div>
		</div>
	</div>
	
	<div class="dash-right">
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
		
		$('#createNewYear').on('click', function() {
			$("#createNewYear i").removeClass('fa-database').addClass('fa-refresh fa-spin');
			$("#newyearBtn").addClass('disabled');
			//alert(); 
			//return false;
			$.ajax({
				url: "ajax/create_new_year.php",
				//dataType: 'json',
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: 'New year created successfully<br>Please check Shiftteams and Periods<? //=$lng['New year created successfully . . . Wait for reload . . .']?>',
							duration: 60,
							closeConfirm: true
						})
						setTimeout(function(){$("#newyearBtn").removeClass('brown');},2000);
						//setTimeout(function(){window.location.href='<?=ROOT?>index.php?mn=2';},2000);
					}else if(result == 'error'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>',
							duration: 4,
						})
						$("#newyearBtn").addClass('brown').removeClass('disabled');
						//setTimeout(function(){$("#createNewYear i").removeClass('fa-cog fa-spin').addClass('fa-cogs');},1000);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<b><?=$lng['Error']?></b> : ' + result,
							duration: 4,
						})
						$("#newyearBtn").addClass('brown').removeClass('disabled');
					}
					setTimeout(function(){$("#createNewYear i").removeClass('fa-refresh fa-spin').addClass('fa-database');},1000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){$("#createNewYear i").removeClass('fa-refresh fa-spin').addClass('fa-database');},3000);
				}
			});
		
		})
		
		
	});

</script>
						
