<?php
	
?>	
<style>
	
	/*table.noStyle tr {
		border: 0 !important;
		border-bottom: 1px solid #eee !important;
	}
	table.noStyle tr:last-child {
		border: 0 !important;
		border-bottom: 0 !important;
	}
	table.noStyle th, table.noStyle td {
		margin: 0 !important;
		padding: 4px 8px !important;
		border: 0 !important;
		border-right: 1px solid #eee !important;
		outline: 0 !important;
		xfont-size: 100% !important;
		vertical-align: baseline !important;
		background: transparent !important;
	}
	table.noStyle td:last-child {
		border-right: 0 !important;
	}*/
		
	.popover {
	  border-radius: 3px;
	  min-width:0 !important;
	  max-width:400px;
	}
	.popover .btn-sm {
		margin:5px 1px 5px;
	}
	.popover .btn-sm:hover {
		color:#fff !important;
	}
	.popover .popover-header{
		text-align:left;
	}
	
</style>

	<h2><i class="fa fa-plane fa-lg"></i>&nbsp; Approve leave<? //=$lng['Approve leave']?> <!--<span style="float:right"><?=$lng['Leave period']?> :  <?=date('d/m/Y', strtotime($leave_periods['start']))?> - <?=date('d/m/Y', strtotime($leave_periods['end']))?></span>--></h2>		
		
		<div class="main">
			<div id="dump"></div>
			
			<ul class="nav nav-tabs" id="myTab">
				<!--<li class="nav-item"><a class="nav-link" data-target="#tab_overview" data-toggle="tab"><?=$lng['Overview']?></a></li>-->
				<li class="nav-item"><a class="nav-link active" data-target="#tab_result" data-toggle="tab">Requested leave<? //=$lng['Requested leave']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_history" data-toggle="tab">History<? //=$lng['History']?></a></li>
			</ul>

			<div class="tab-content" style="height:calc(100% - 40px)">
			
				<div class="tab-pane" id="tab_overview">
					<? include(DIR.'include/tabletabs.php'); ?>
					<table id="appTable" class="dataTable nowrap" width="100%" style="margin-bottom:10px" border="0">
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
				
				<div class="tab-pane active" id="tab_result">
					<div class="showTable" style="display:none">
						<table id="datatable" class="dataTable selectable nowrap">
							<thead>
								<tr>
									<th data-visible="false"class="par30"><?=$lng['Emp. ID']?></th>
									<th style="padding:0 30px 0 10px"><?=$lng['Employee']?></th>
									<th style="padding:0 8px" data-sortable="false"><i class="fa fa-user fa-lg"></i></th>
									<th class="par30"><?=$lng['Leave type']?></th>
									<th class="par30"><?=$lng['Start date']?></th>
									<th class="par30"><?=$lng['End date']?></th>
									<th data-sortable="false"><?=$lng['Days']?></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Approve']?>" class="fa fa-thumbs-up fa-lg"></i></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Reject']?>" class="fa fa-thumbs-down fa-lg"></i></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Cancel']?>" class="fa fa-times-circle fa-lg"></i></th>
									<th class="tac" style="padding:0 10px" data-sortable="false"><?=$lng['Status']?></th>
									<th data-sortable="false"><?=$lng['Reason']?></th>
									<th data-sortable="false">Cert.<? //=$lng['']?></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Leave balance']?>" class="fa fa-balance-scale fa-lg"></i></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Leave details']?>" class="fa fa-info-circle fa-lg"></i></th>
									<th data-sortable="false" data-visible="false"><i data-toggle="tooltip" title="<?=$lng['Edit leave']?>" class="fa fa-edit fa-lg"></i></th>
									<th data-sortable="false">Attach<? //=$lng['']?></th>
									<!--<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Edit leave']?>" class="fa fa-trash fa-lg"></i></th>-->
								</tr>
							</thead>
							<tbody>
		
							</tbody>
						</table>
					</div>
				</div>
			
				<div class="tab-pane" id="tab_history">
					<div class="showTable" style="display:none">
						<table id="history_table" class="dataTable selectable nowrap">
							<thead>
								<tr>
									<th data-visible="false" class="par30"><?=$lng['Emp. ID']?></th>
									<th class="par30"><?=$lng['Employee']?></th>
									<th style="padding:0 8px" data-sortable="false"><i class="fa fa-user fa-lg"></i></th>
									<th class="par30"><?=$lng['Leave type']?></th>
									<th class="par30"><?=$lng['Start date']?></th>
									<th class="par30"><?=$lng['End date']?></th>
									<th data-sortable="false"><?=$lng['Days']?></th>
									<th style="width:80%" data-sortable="false"><?=$lng['Reason']?></th>
									<th class="tac" style="padding:0 10px" data-sortable="false"><?=$lng['Status']?></th>
									<th data-sortable="false">Handled by<? //=$lng['']?></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Leave details']?>" class="fa fa-info-circle fa-lg"></i></th>
									<th data-sortable="false">Attach<? //=$lng['']?></th>
								</tr>
							</thead>
							<tbody>
		
							</tbody>
						</table>
					</div>
				</div>
				
			</div>
				
	</div>
	
	<!-- Modal modalLeaveDetails -->
	<div class="modal fade" id="modalLeaveDetails" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Leave details']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-bottom:20px">
					<span id="leave_table1"></span>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal modalLeaveBalance -->
	<div class="modal fade" id="modalLeaveBalance" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Leave balance']?> <span id="memp_id"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-bottom:20px">
					<span id="leave_balance"></span>
				</div>
			</div>
		</div>
	</div>
		
	<div id="popoverReject" class="d-none">
		<form id="popForm" class="popReject">
			<div>
				<textarea placeholder="<?=$lng['Reason']?>" name="comment" rows="2" style="width:350px; border:0; padding:0;resize:none;color:#333"></textarea>
			</div>
			<div style="padding:10px 0 5px 0">
				<button type="submit" class="btn btn-default btn-xs butReject" style="display:inline-block;float:left"><i class="fa fa-thumbs-down-o"></i>&nbsp;Submit</button>
				<button type="button" class="btn btn-default btn-xs butCancel" style="display:inline-block;float:right">Cancel</button>
				<div style="clear:both;"></div>
			</div>
		</form>
	</div>	

	<script type="text/javascript">
		
		$(document).ready(function() {

			headerCount = 1;
			var row_id;
			var leave_id;
			var action;
			
			$(document).on('click', '.approve_leave', function(e){
				row_id = $(this).closest('tr').find('.details').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).ajaxComplete(function( event,request, settings ) {
				$('.approve_leave').confirmation({
					container: 'body',
					rootSelector: '.approve_leave',
					singleton: true,
					animated: 'fade',
					placement: 'right',
					popout: true,
					html: true,
					title 			: '<?=$lng['Are you sure']?>',
					btnOkClass 		: 'btn btn-danger btn-sm',
					btnOkLabel 		: '<?=$lng['Approve']?>',
					btnCancelClass 	: 'btn btn-success btn-sm',
					btnCancelLabel 	: '<?=$lng['Cancel']?>',
					onConfirm: function() { 
						$.ajax({
							url: ROOT+"leave/ajax/save_leave_action.php",
							data:{row_id: row_id, leave_id: leave_id, action: action},
							success: function(result){
								//$("#dump").html(result);
								if(result == 'success'){
									$("body").overhang({
										type: "success",
										message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Database updated successfuly',
										duration: 2,
									})
								}else{
									$("body").overhang({
										type: "warn",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Error : ' + result,
										duration: 4,
										closeConfirm: true
									})							
								}						
								dtable.ajax.reload(null, false);
								//location.replace('index.php?mn=4');
							},
							error:function (xhr, ajaxOptions, thrownError){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Error : ' + thrownError,
									duration: 6,
									closeConfirm: true
								})							
							}
						});
					}
				});
			});
			var popOver;
			$(document).on("click", ".reject_leave", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).ajaxComplete(function( event,request, settings ) {
				popOver = $('.reject_leave').popover({
					placement: 'right',
					container: 'body',
					html: true,
					sanitize: false,
					title: '<i class="fa fa-thumbs-down"></i>&nbsp; Reject reason',
					content: function () {
							return $("#popoverReject").html();
					}
				}).on("show.bs.popover", function(e){
          // hide all other popovers
          $('.reject_leave').not(this).popover('hide');
        });
			})
			
			$(document).on("click", ".cancel_leave", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				//$('#popForm').removeClass().addClass('cancelForm');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).ajaxComplete(function( event,request, settings ) {
				popOver = $('.cancel_leave').popover({
					placement: 'right',
					container: 'body',
					html: true,
					sanitize: false,
					title: '<i class="fa fa-thumbs-down"></i>&nbsp; Cancel reason',
					content: function () {
							return $("#popoverReject").html();
					}
				}).on("show.bs.popover", function(e){
          // hide all other popovers
          $('.cancel_leave').not(this).popover('hide');
        });
			})

			$(document).on('click','.butCancel', function(e) {
				$('.reject_leave').popover('hide');
				$('.cancel_leave').popover('hide');
			});			
			
			$(document).on("click", "a.balance", function(e){
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: ROOT+"leave/ajax/get_leave_balance.php",
					data: {emp_id: id},
					success:function(result){
						$("#leave_balance").html(result);
						//$('#dump').html(result);
						$("#modalLeaveBalance").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Sorry but someting went wrong']?> : ' + thrownError);
					}
				});
			});
			
			$(document).on("click", "a.details", function(e){
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: ROOT+"leave/ajax/get_leave_alt_details.php",
					data: {id: id},
					success:function(result){
						$("#leave_table1").html(result);
						//alert(result);
						$("#modalLeaveDetails").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Sorry but someting went wrong']?> ' + thrownError);
					}
				});
			});
			
			var htable = $('#history_table').DataTable({
				scrollY:        false,//scrY,//heights-260,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns: 	false,
				lengthChange: 	false,
				pageLength: 	14,
				paging: 		true,
				searching:		true,
				ordering:		true,
				filter: 		false,
				info: 			false,
				autoWidth:		true,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				order: [[ 4, "desc" ]],
				columnDefs: [
					{ targets: [10,11], class: 'tac' },
					//{ targets: [1], class: 'pad2' },
					{ targets: [2], class: 'pad2 tac' },
					//{ targets: [2], class: 'pad2' },
					//{ targets: [10], class: 'pad05' },
					//{ targets: [11], width: '60%' }
				],
				ajax: {
					url: ROOT+"approve/ajax/server_get_leave_history.php",
					type: "POST",
					data: function(d){
						//d.empFilter = $("#empFilter").val();
						//d.statFilter = $("#statFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					//$('.showTable').fadeIn(200);
					htable.columns.adjust().draw();
				}
			});
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,//scrY,//heights-260,
				scrollX:        true,
				scrollCollapse: false,
				fixedColumns: 	false,
				lengthChange: 	false,
				pageLength: 	14,
				paging: 		true,
				searching:		true,
				ordering:		true,
				filter: 		false,
				info: 			false,
				autoWidth:		true,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				order: [[ 4, "asc" ]],
				columnDefs: [
					{ targets: [7,8,9,12,13,14,15,16], class: 'tac' },
					//{ targets: [1], class: 'pad2' },
					{ targets: [2], class: 'pad2 tac' },
					{ targets: [2], class: 'pad2' },
					{ targets: [10], class: 'pad05' },
					{ targets: [11], width: '60%' }
				],
				ajax: {
					url: "ajax/server_get_leaves_to_approve.php",
					type: "POST",
					data: function(d){
						d.empFilter = $("#empFilter").val();
						//d.statFilter = $("#statFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					$('.showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				//localStorage.setItem('activeTab', $(e.target).data('target'));
				dtable.columns.adjust().draw();
				htable.columns.adjust().draw();
			});
		
		});
	
	</script>
						


















