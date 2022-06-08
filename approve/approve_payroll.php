<?php
	
	if(!isset($_GET['m'])){$month = $_SESSION['rego']['cur_month'];}else{$month = $_GET['m'];}
	$data = false;
	$paid = false;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = ".$_SESSION['rego']['cur_month']." LIMIT 1";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = true;
			if($row['paid']=='Y'){$paid = true;}
		}
	}
	//var_dump($data);
	//var_dump($paid);
	
	if($data){echo '<script>var data = 1;</script>';}else{echo '<script>var data = 0;</script>';}
	//var_dump($data);

	$fix_allow = getUsedFixAllow($lang);
	$var_allow = getUsedVarAllow($lang);
	//var_dump($allow_names);
	$sCols = getEmptyResultColumns($fix_allow, $var_allow);
	//var_dump($sCols); exit;
	
	$eCols = '';
	foreach($sCols as $v){$eCols .= $v.',';}
	$eCols = '['.substr($eCols,0,-1).']';
	//var_dump($eCols);
?>	
<style>
	.dataTable thead th {
		white-space:normal !important;
		line-height:130% !important;
	}
	.btn.reject {
		background:#a00;
		border:1px #a00 solid;
	}
	.btn.reject:hover {
		background: #fb0;
		border:1px #fb0 solid;
	}
	.popover {
		max-width:300px !important;
		width:300px !important;
		border-radius:0 !important;
	}
</style>

	<h2><i class="fa fa-money fa-lg"></i>&nbsp; <?=$lng['Approve payroll']?> <!--<span style="float:right"><?=$lng['Leave period']?> :  <?=date('d/m/Y', strtotime($leave_periods['start']))?> - <?=date('d/m/Y', strtotime($leave_periods['end']))?></span>--></h2>		
		
	<div class="main">
		<div id="dump"></div>
			
		<ul class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" data-target="#tab_overview" data-toggle="tab"><?=$lng['Overview']?></a></li>
			<li class="nav-item"><a class="nav-link active" data-target="#tab_result" data-toggle="tab"><?=$lng['Payroll result']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 40px)">
		
			<div class="tab-pane" id="tab_overview">
				<? include(DIR.'include/tabletabs.php'); ?>
				<table id="appTable" class="dataTable selectable nowrap" width="100%" border="0">
					<thead>
						<tr>
							<th class="par30"><?=$lng['Date']?></th>
							<th data-visible="false"></th>
							<th class="par30"><?=$lng['Name']?></th>
							<th data-sortable="false"><?=$lng['Action']?></th>
							<th data-sortable="false" style="width:80%"><?=$lng['Comment']?></th>
							<th data-sortable="false"><?=$lng['Attach']?></th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
				
			<div class="tab-pane show active" id="tab_result" style="overflow-x:hidden">
				<div id="showTable" style="display:none">
				
					<div class="searchFilter">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
					<button type="button" id="approveBut" class="btn btn-primary btn-fl"><i class='fa fa-thumbs-up'>
						</i>&nbsp; <?=$lng['Approve payroll']?>
					</button>
					<button type="button" id="rejectBut" data-toggle="popover" class="btn btn-primary btn-fl reject">
						<i class='fa fa-thumbs-down'></i>&nbsp; <?=$lng['Reject payroll']?>
					</button>
					<button type="button" id="excel_report" class="btn btn-primary btn-fl">
						<i class='fa fa-file-excel-o'></i>&nbsp; <?=$lng['Excel report']?>
					</button>
					<div style="clear:both"></div>
				
					<table id="datatable" class="dataTable nowrap selectable compact tar" border="0" style="width:100%">
						<thead>
							<tr>
								<th class="par30" style="width:10px;"><?=$lng['ID']?></th>
								<th class="par30 tal" style="width:40%;"><?=$lng['Employee']?></th>
								<th data-sortable="false" data-visible="false" style="width:1px;"><i title="<?=$lng['Tax calculation']?>" data-toggle="tooltip" class="fa fa-calculator" style="font-size:14px"></i></th>
								<th data-sortable="false"><?=$lng['Days']?></th>
								<th class="par30"><?=$lng['Salary']?></th>
		
								<th class="tac" data-sortable="false">Fixed allowances<? //=$lng['OT 1']?></th>
								<th class="tac" data-sortable="false">Overtime<? //=$lng['OT 1']?></th>
								<th class="tac" data-sortable="false">Variable allowances<? //=$lng['OT 1']?></th>
								
								<th class="tac" data-sortable="false" ><?=$lng['Absence']?></th>
								<th class="tac" data-sortable="false"><?=$lng['Other income']?></th>
								<th class="tac" data-sortable="false">Deductions<? //=$lng['Paid leave']?></th>
								
								<th class="tac" data-sortable="false" style="white-space:nowrap !important"><?=$lng['PVF']?> / PSF</th>
								<th class="tac" data-sortable="false"><?=$lng['Social']?></th>
								<th class="tac" data-sortable="false"><?=$lng['Tax']?></th>
								<th class="tac" data-sortable="false">Gross Income<? //=$lng['Gr.Income']?></th>
								<th class="tac" data-sortable="false"><?=$lng['Advance']?></th>
								<th class="tac" data-sortable="false"><?=$lng['Legal deduct']?></th>
								<th class="tac" data-sortable="false"><?=$lng['Net Income']?></th>
							</tr>
						</thead>
						<tbody>
		
						</tbody>
					</table>
				</div>
				
			</div>
			
		</div>
				
	</div>
	

<script type="text/javascript">
	
	$(document).ready(function() {
		function removeFilterClass(){
			$('.month-btn').each(function(){
				$(this).removeClass('activ')
			})
		}
		
		//headerCount = 1;
		
		var atable = $('#appTable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns:   false,
			lengthChange:  	false,
			pageLength: 	16,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			filter: 		true,
			info: 			false,
			autoWidth:		true,
			processing: 	false,
			serverSide: 	true,
			order:[[0, 'DESC']],
			<?=$dtable_lang?>
			ajax: {
				url: "ajax/server_get_approvals.php",
			},
			columnDefs: [
				  {targets: [5], class: 'tac' },
				  //{targets: [1], visible: false },
				  //{targets: [5], width: '70%' }
			],
			initComplete : function( settings, json ) {
				//$('#showTable').fadeIn(200);
				atable.columns.adjust().draw();
				//atable.fnFilter('<?//=$_SESSION['rego']['cur_month']?>', 1, true);
			}
		});
		$('.fnMonth').on('click', function () {
			removeFilterClass()
			$(this).addClass('activ')
			atable.column(1).search($(this).data('val')).draw();
		});			
		$('.fnClear').on('click', function () {
			removeFilterClass()
			$(this).addClass('activ')
			atable.column(1).search('').draw();
		});			
		
		var dtable = $('#datatable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns:   true,
			lengthChange:  	false,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			pageLength: 	16,
			filter: 		true,
			info: 			false,
			autoWidth:		true,
			processing: 	false,
			serverSide: 	true,
			<?=$dtable_lang?>
			ajax: {
				url: "ajax/server_payroll_result.php",
				type: "POST"
			},
			columnDefs: [
				  { targets: [0,1], "class": 'tal' },
				  //{ targets: eCols, "visible": false },
				  //{ targets: [1], width: '80%' },
			],
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
			}
		});
		$("#searchFilter").keyup(function() {
			dtable.search(this.value).draw();
		});		
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
		})
		
		$(document).on('click','#approveBut', function(e) {
			$('#approveBut i').removeClass('fa-thumbs-up').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "ajax/save_approve_action.php",
				data: {type:'PR',	action:'AP'},
				success: function(result){
					//$("#dump").html(result); return false;
					$("#message").html('<div class="msg_success nomargin"><? //=$lng['Payroll approved']?></div>').hide().fadeIn(300);
					setTimeout(function(){$('#approveBut i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');},1000);
					setTimeout(function(){$("#message").fadeOut(300);},3000);
					
					atable.ajax.reload(null, false);
					//dtable.api().ajax.reload();
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
		
		// REJECT PAYROLL START //////////////////////////////////////			
		var popOverSettings = {
			placement: 'bottom',
			container: 'body',
			html: true,
			selector: '[data-toggle="popover"]', //Sepcify the selector here
			title: '<span id="pop_title"><?=$lng['Reject comment']?></span>',
			content: '<form id="popForm" class="popReject">'+
				'<input style="visibility:hidden;position:absolute;top:0;right:0;height:0;width:0" type="file" name="attach" id="attachment">'+
				'<input type="hidden" name="emp_id" value="<?=$_SESSION['rego']['emp_id']?>" />'+
				'<input type="hidden" name="emp_name" value="<?=$_SESSION['rego']['name']?>" />'+
				'<input type="hidden" name="month" value="<?=$_SESSION['rego']['cur_month']?>" />'+
				'<input type="hidden" name="type" value="PR" />'+
				'<input type="hidden" name="action" value="RJ" />'+
				'<div><textarea placeholder="..." name="comment" rows="5" style="width:100%; border:0; padding:0;resize:vertical;"></textarea></div>'+
				'<div style="padding:10px 0 5px 0">'+
				'<button type="submit" class="btn btn-default btn-xs butReject" style="display:inline-block;float:left"><?=$lng['Submit']?></button>'+
				'<button id="attachBut" type="button" class="btn btn-default btn-xs" style="display:inline-block;float:left;margin-left:10px"><?=$lng['Attachment']?></button>'+
				'<button type="button" class="btn btn-default btn-xs butCancel" style="display:inline-block;float:right"><?=$lng['Cancel']?></button>'+
				'<div style="clear:both;"></div></div>'+
				'</form>'
		}	
		var popover = $('body').popover(popOverSettings);
		$('body').on('hidden.bs.popover', function (e) {
			 $(e.target).data("bs.popover").inState.click = false;
		});			
		
		$(document).on('click','.butCancel', function(e) {
			$('body [data-toggle="popover"]').popover('hide');
		});			
		$(document).on('click','#attachBut', function(e) {
			$('#attachment').click();
		});			
		$(document).on("submit", "#popForm", function(e){
			e.preventDefault();
			$('#rejectBut i').removeClass('fa-thumbs-down').addClass('fa-refresh fa-spin');
			var data = new FormData(this);
			$.ajax({
				url: "ajax/save_approve_action.php",
				type: "POST", 
				data: data,
				cache: false,
				processData:false,
				contentType: false,
				success: function(result){
					//$("#dump").html(result);
					$("#message").html('<div class="msg_alert nomargin"><? //=$lng['Payroll rejected']?></div>').hide().fadeIn(300);
					setTimeout(function(){$('#rejectBut i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-down');},1000);
					setTimeout(function(){$("#message").fadeOut(300);},3000);
					$('body [data-toggle="popover"]').popover('hide');
					atable.ajax.reload(null, false);
					dtable.ajax.reload(null, false);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		})
		// REJECT PAYROLL END //////////////////////////////////////			
		
		$("#excel_report").on('click', function(){
			window.open(ROOT+'payroll/export_payroll_report_exel.php', '_self');
		});
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			//localStorage.setItem('activeTab', $(e.target).data('target'));
			dtable.columns.adjust().draw();
			atable.columns.adjust().draw();
		});
		/*var activeTab = localStorage.getItem('activeTab');
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}*/
		
		$("#tableTab"+parseInt(<?=$_SESSION['rego']['cur_month']?>)).trigger('click');
		
	});

</script>
					
					


















