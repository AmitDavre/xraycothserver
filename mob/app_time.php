<?php

?>	
	<div class="container-fluid" style="padding:0">
		
		<div style="padding:10px">							
			<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width:100%">
<!-- 				<label class="btn btn-outline-default <?=$appLeave?>" <?=$appLeave?>onClick="window.location.href='index.php?mn=1701'">
					<input type="radio"><?=$lng['Leave']?>
				</label> -->
				<label class="btn btn-outline-default active <?=$appTime?>" <?=$appTime?>onClick="window.location.href='index.php?mn=1702'">
					<input type="radio" checked><?=$lng['Time']?>
				</label>
<!-- 				<label class="btn btn-outline-default <?=$appPayroll?>" <?=$appPayroll?>onClick="window.location.href='index.php?mn=1703'">
					<input type="radio"><?=$lng['Payroll']?>
				</label> -->
			</div>
		</div>
		
		<ul class="nav nav-tabs lined" role="tablist" style="background:#f6f6f6">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#pending" role="tab"><?=$lng['Pending']?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#history" role="tab"><?=$lng['History']?></a>
			</li>
		</ul>
		
		<div class="tab-content mt-2" style="padding:0 10px">
			
			<div class="tab-pane fade show active" id="pending" role="tabpanel">
			
			
			</div>
			<div class="tab-pane fade show" id="history" role="tabpanel">
			
			
			</div>
			
		</div>
		
		
		<div style="height:60px"></div>
			
	</div>	
			
			
<script type="text/javascript">
	
	$(document).ready(function() {
		
		
	})
	
</script>	










