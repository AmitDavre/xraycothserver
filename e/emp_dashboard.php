
<div class="main bread">
	<div id="dump"></div>
	
	<table border="0" width="100%">
		<tr>
			<td style="width:75%; padding-right:10px; vertical-align:top">
					
				<div class="dashbox green">
					<div class="inner" onclick="window.location.href='index.php?mn=910';">
						<i class="fa fa-id-card-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Personal data']?></p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="dashbox orange">
					<div class="inner" onclick="window.location.href='index.php?mn=911';">
						<i class="fa fa-files-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Payslips']?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="dashbox dblue">
					<div class="inner" onclick="window.location.href='index.php?mn=912';">
						<i class="fa fa-list"></i>
						<div class="parent">
							<div class="child">
								<p>Year overview<? //=$lng['Year overview']?></p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="dashbox purple" disabled="disabled">
					<!-- <div class="inner" onclick="window.location.href='index.php?mn=913';"> -->
					<div class="inner" onclick="#">
						<i class="fa fa-plane"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Leave module']?></p>
							</div>
						</div>						
					</div>
				</div>
				
				<div class="dashbox reds">
					<div class="inner" data-toggle="modal" data-target="#passModal">
						<i class="fa fa-unlock-alt"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Change password']?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="dashbox brown">
					<div class="inner" data-toggle="modal" data-target="#modalContactForm">
						<i class="fa fa-comments-o"></i>
						<div class="parent">
							<div class="child">
								<p><?=$lng['Contact']?></p>
							</div>
						</div>
					</div>
				</div>
	
			</td>
			<td style="width:25%; vertical-align:top; padding:6px">
			
				<div class="notify_box">
					<h2><i class="fa fa-bell"></i>&nbsp; Notification box</h2>
					<div class="inner">
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					</div>
				</div>
				
			</td>
		</tr>
	</table>
</div>
