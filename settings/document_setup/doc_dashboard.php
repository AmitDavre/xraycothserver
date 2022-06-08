<h2 style="padding-right:60px">
	<i class="fa fa-cog"></i>&nbsp; <?=$lng['Communication center']?>
</h2>
<div style="padding:0 0 0 20px" id="dump"></div>
	
	<div class="dash-left">
		
		<div class="dashbox dblue">
			<div class="inner" onclick="window.location.href='index.php?mn=301';">
				<i class="fa fa-file-code-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Headers']?></p>
					</div>
				</div>						
				
			</div>
		</div>		
		
		<div class="dashbox green">
			<div class="inner" onclick="window.location.href='index.php?mn=302';">
				<i class="fa fa-file-code-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Footers']?></p>
					</div>
				</div>						
			</div>
		</div>

		<div class="dashbox purple">
			<div class="inner" onclick="window.location.href='index.php?mn=303';">
				<i class="fa fa-clipboard"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Default Text blocks']?></p>
					</div>
				</div>						
				
			</div>
		</div>

		<div class="dashbox reds">
			<div class="inner" onclick="window.location.href='#';">
				<i class="fa fa-file-text-o"></i>
				<div class="parent">
					<div class="child">
						<p><?=$lng['Standard Documents']?></p>
					</div>
				</div>						
				
			</div>
		</div>
		
	</div>
	
	<div class="dash-right">
		<!-- <div class="notify_box">
			<h2 style="background:#a00"><i class="fa fa-bell"></i>&nbsp; <?=$lng['Complete setup tasks']?></h2>
			<div class="inner">
				<? //if($checkSetup){
					//echo $checkSetup;
				//}else{
					//echo '<b><i class="fa fa-check-square-o"></i>&nbsp;&nbsp;'.$lng['All mandatory System settings are set'].'</b><br>';
				//}?>
			</div>
		</div> -->
	</div>