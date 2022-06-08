<?php
	
?>	

	<h2><i class="fa fa-clock-o fa-lg"></i>&nbsp; Approve time<? //=$lng['Approve leave']?> <!--<span style="float:right"><?=$lng['Leave period']?> :  <?=date('d/m/Y', strtotime($leave_periods['start']))?> - <?=date('d/m/Y', strtotime($leave_periods['end']))?></span>--></h2>		
		
		<div class="main">
			<div id="dump"></div>
			
			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link" data-target="#tab_overview" data-toggle="tab"><?=$lng['Overview']?></a></li>
				<li class="nav-item"><a class="nav-link active" data-target="#tab_result" data-toggle="tab">Time<? //=$lng['Payroll result']?></a></li>
			</ul>

			<div class="tab-content" style="height:calc(100% - 40px)">
			
				<div class="tab-pane" id="tab_overview">
					Not available yet
				</div>
				
				<div class="tab-pane active" id="tab_result">
					Not available yet
				</div>
				
				
			</div>
				
	</div>
	

	<script type="text/javascript">
		
		$(document).ready(function() {

			headerCount = 1;
			
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('activeTab', $(e.target).data('target'));
				//dtable.columns.adjust().draw();
			});
			/*var activeTab = localStorage.getItem('activeTab');
			if(activeTab){
				$('#myTab a[data-target="' + activeTab + '"]').tab('show');
			}*/

		});
	
	</script>
						
						


















