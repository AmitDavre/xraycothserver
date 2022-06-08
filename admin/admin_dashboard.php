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
					
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['customer']['access'] == 1){echo 'green';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=11';">
						<i class="fa fa-users"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Customers']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['users']['access'] == 1){echo 'orange';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=30';">
						<i class="fa fa-user"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Users setup']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['def_settings']['access'] == 1){echo 'dblue';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=51';"> <!--51-->
					<i class="fa fa-cogs"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Default settings']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['comp_settings']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=80';">
					<i class="fa fa-cog"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Company settings']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['price']['access'] == 1){echo 'reds';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=40';">
					<i class="fa fa-money"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['REGO Standards']?></p>
							</div>
						</div>
					</div>
				</div>
				<!--<div class="dashbox disabled <? //if($_SESSION['RGadmin']['access']['billing']['access'] == 1){echo 'teal';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=20';">
					<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Billing']?></p>
							</div>
						</div>
					</div>
				</div>-->
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['help']['access'] == 1){echo 'brown';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=60';">
					<i class="fa fa-question-circle-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Help files']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['help']['access'] == 1){echo 'brown';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=61';">
					<i class="fa fa-handshake-o"></i>
						<div class="parent">
							<div class="child">
							<p><?=$lng['Welcome files']?></p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['help']['access'] == 1){echo 'brown';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=62';">
					<i class="fa fa-file-image-o"></i>
						<div class="parent">
							<div class="child">
								<p>Promo files<? //=$lng['Promo files']?></p>
							</div>
						</div>
					</div>
				</div>
	
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['support']['access'] == 1){echo 'green';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=90';">
					<i class="fa fa-life-ring"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Support desk']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['language']['access'] == 1){echo 'reds';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=99';">
					<i class="fa fa-language"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Language list']?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['logdata']['access'] == 1){echo 'dblue';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=70';">
					<i class="fa fa-database"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Log data']?></p>
							</div>
						</div>
					</div>
				</div>
<!-- 				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=75';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Terms & Conditions']?></p>
							</div>
						</div>
					</div>
				</div>	 -->
				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=101';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Legal Conditions']?></p>
							</div>
						</div>
					</div>
				</div>
<!-- 				<div class="dashbox <? if($_SESSION['RGadmin']['access']['privacy']['access'] == 1){echo 'purple';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=76';">
						<i class="fa fa-file-text-o"></i>
						<div class="parent">
							<div class="child">
								<p>Privacy Policy<? //=$lng['Privacy Policy']?></p>
							</div>
						</div>
					</div>
				</div> -->
				<!--<div class="dashbox <? if($_SESSION['RGadmin']['access']['agents']['access'] == 1){echo 'blue';}else{echo 'disabled';}?>">
					<div class="inner" onclick="window.location.href='index.php?mn=95';">
						<i class="fa fa-user-secret"></i>
						<div class="parent">
							<div class="child">
								<p>REGO Agents<? //=$lng['REGO Agents']?></p>
							</div>
						</div>
					</div>
				</div>-->
	
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
   
						














