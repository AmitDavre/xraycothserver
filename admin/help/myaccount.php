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
					
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['comp_settings']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=107';">
					<i class="fa fa-cog"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Cookie Consent Setting']?></p>
							</div>
						</div>
					</div>
				</div>


	
			</div>
			

						














