<?php
 	
	$tickets = getSupportTickets();
	$checkHolidays = '';
	if(date('n') == 12){
		if(!checkHolidaysDB((date('Y')+1))){
			$checkHolidays = '<b><a href="index.php?mn=54">Add holidays for the year '.(date('Y')+1).'</a></b>';
		}
	}else{
		if(!checkHolidaysDB((date('Y')))){
			$checkHolidays = '<b><a href="index.php?mn=54">Add holidays for the year '.(date('Y')).'</a></b>';
		}
	}
	//var_dump($checkHolidays);
	$new_tickets = $tickets['new'];
	$open_tickets = $tickets['open'];
	
?>
	
			<div class="dash-left">
					
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'dblue';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=75';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Terms & Conditions']?></p>
							</div>
						</div>
					</div>
				</div>	

				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'blue';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=76';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p>Privacy Policy<? //=$lng['Privacy Policy']?></p>
							</div>
						</div>
					</div>
				</div>				

				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'orange';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=102';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p>Cookie Consent <? //=$lng['Privacy Policy']?></p>
							</div>
						</div>
					</div>
				</div>				
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'green';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=103';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p>Confirmation Text <? //=$lng['Privacy Policy']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'brown';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=109';">
						<i class="fa fa-file-o"></i>
						<div class="parent">
							<div class="child">
								<p> <?=$lng['Consent Letter']?></p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=104';">
						<i class="fa fa-database"></i>
						<div class="parent">
							<div class="child">
								<p>Confirmation Log <? //=$lng['Privacy Policy']?></p>
							</div>
						</div>
					</div>
				</div>				
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'reds';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=105';">
						<i class="fa fa-cog"></i>
						<div class="parent">
							<div class="child">
								<p> <?=$lng['Consent Settings']?></p>
							</div>
						</div>
					</div>
				</div>				


	
			</div>
			
			<div class="dash-right">
		
				<div class="notify_box">
					<h2 style="background:#a00"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Notification box']?></h2>
					<div class="inner">
						<?=$lng['New support tickets']?> : <b><?=$new_tickets?></b><br>
						<?=$lng['Open support tickets']?> : <b><?=$open_tickets?></b><br>
						<?=$checkHolidays?>
	
					</div>
				</div>
		
			</div>
   
						














