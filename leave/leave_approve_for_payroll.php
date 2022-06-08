<?

	/*$res = $dbc->query("SELECT emp_id, name, SUM(days) as days, leave_type FROM ".$cid."_leaves_data GROUP BY emp_id, leave_type"); 
	while($row = $res->fetch_assoc()){
		//$data[$row['emp_id']][$row['leave_type']]['name'] = $row['name'];
		//$data['leave_type'] = $row['leave_type'];
		//$data[$row['emp_id']][$row['leave_type']]['days'] = $row['days'];
		$data[$row['emp_id']][$row['leave_type']] = $row['days'] * 8;
	}

	var_dump($data); exit;*/
	
	$leave_settings = getLeaveTimeSettings();
	$period_start = $leave_settings['pr_leave_start'];
	$leave_periods = getMonthlyPeriod($period_start);
	//var_dump($leave_settings);

	$leave_colors = array(
		'bg-color-blue',
		'bg-color-blueLight',
		'bg-color-blueDark',
		'bg-color-green',
		'bg-color-greenLight',
		'bg-color-greenDark',
		'bg-color-red',
		'bg-color-yellow',
		'bg-color-orange',
		'bg-color-orangeDark',
		'bg-color-pink',
		'bg-color-pinkDark',
		'bg-color-purple',
		'bg-color-darken',
		'bg-color-grayDark',
		'bg-color-magenta',
		'bg-color-teal',
		'bg-color-redLight');
	
	$legend[$lng['Planned leave']] = 'planned';
	$legend[$lng['Public holiday']] = 'holiday';
	$legend[$lng['Normal working day']] = 'working';
	$legend[$lng['Non-working day']] = 'nonworking';

	$nr=0;
	//$departments = array();
	//var_dump($departments);
	
	foreach($leave_types as $k=>$v){
		$leave_types[$k]['class'] = $leave_colors[$nr]; $nr++;
	}
	$leave_status = array('RQ'=>$lng['Pending'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected']);
	
	//$employees = getEmployeeNameId($cid);
	$emps = getEmployees($cid,0);
	$emp_array = getJsonIdsEmployees($cid, $lang);
	//var_dump(json_encode($emp_array));
	
?>	

<link rel="stylesheet" href="../assets/css/bootstrap-year-calendar.css?<?=time()?>">
<style>
.subinfo {
	font-weight:600; 
	color:#005588; 
	display:inline-block; 
	border-left:1px #ccc solid; 
	padding:2px 0 2px 10px;
}
table.mytable tbody td, table.mytable thead th {
	text-align:left;
	padding:4px 10px;
}

table.sum tbody td, table.mytable tfoot td {
	text-align:right;
	padding:3px 10px;
}
table.sum thead th {
	padding:5px 10px;
	text-align:center;
}
table.sum tfoot td {
	font-weight:700;
	background:#eee;
}
.legend {
	display:inline-block;
	border-radius:2px;
	padding:4px 15px;
	font-size:13px;
	margin:0 5px 0 0;
	cursor:default;
}
.planned {
  background: #8d8 !important;
  border:1px #8d8 solid;
}		
.unplanned {
  background: #aaf !important;
  border:1px #aaf solid;
}		
.holiday {
  background: #fb0 !important;
  border:1px #fb0 solid;
}		
.nonworking {
  background: #eee !important;
  border:1px #eee solid;
}		
.working {
  background: #fff !important;
  border:1px #ddd solid;
}		
</style>
<style>
	.date_month, .cdate_month {
		cursor:pointer;
	}
	table.xbasicTable td, table.xbasicTable th {
		border:1px solid #ccc;
		padding:5px 10px;
	}
	table.xbasicTable th {
		font-weight:600;
		background:#f6f6f6;
		min-width:50px;
		white-space:nowrap;
	}
	.popover {
	  border-radius: 3px;
	}
	.popover-content {
	  padding:5px 10px;
	  color:#a00;
	  font-weight:400;
	  line-height:120%;
	}
	.popover-content span {
	  color:#000;
	}
	.popover.bottom {
	  margin-top: 0px;
	}
	.popover.top {
	  xmargin-top: -3px;
	}
	table.dataTable tbody td:first-child a {
		text-decoration:none;
		font-weight:600;
		color:#003399;
	}	
	table.dataTable tbody td:first-child a:hover {
		color:#900;
	}	
</style>
	
	<h2><i class="fa fa-check-square-o"></i>&nbsp; <?=$lng['Approve for payroll']?> <!-- <span style="float:right"><?=$lng['Leave period']?> :  <?=date('d/m/Y', strtotime($leave_periods['start']))?> - <?=date('d/m/Y', strtotime($leave_periods['end']))?></span> --></h2>		
		
		<div class="main">
			<div id="dump"></div>
			
			<ul style="position:relative" class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link" data-target="#tab_overview" data-toggle="tab"><?=$lng['Overview']?></a></li>
				<li class="nav-item"><a class="nav-link active" data-target="#tab_approve" data-toggle="tab"><?=$lng['Approve for payroll']?></a></li>
				<!--<li><a data-target="#tab_history" data-toggle="tab">Historical data</a></li>-->
			</ul>

			<div class="tab-content" style="height:calc(100% - 40px)">
			
				<input type="hidden" id="empFilter" />
				<!--<input type="text" name="emp_id" id="emp_id" />-->

				<div class="tab-pane" id="tab_overview">
				
				</div>
				
				<div class="tab-pane show active" id="tab_approve">
					<div id="showTable" style="display:none">
						
						<div class="searchFilter" style="margin-bottom:0px; width:300px; border:0px red solid">
							<input style="width:100%" class="sFilter" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selEmployee" />
							<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
						</div>
						<button id="approvePeriod" class="btn btn-primary btn-fl" type="button"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;Approve leave period</button>
						<div style="clear:both"></div>
					
						<table id="datatable" class="dataTable selectable nowrap" width="100%">
							<thead>
								<tr>
									<th><?=$lng['Employee name']?></th>
									<th data-sortable="false" style="width:1px; padding:0 !important"><i class="fa fa-user fa-lg"></i></th>
									<th data-sortable="false" data-visible="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-edit fa-lg"></i></th>
									<th data-sortable="false" data-visible="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Leave details']?>" class="fa fa-info-circle fa-lg"></i></th>
									<th data-sortable="false" data-visible="false" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Leave balance']?>" class="fa fa-balance-scale fa-lg"></i></th>
									<th><?=$lng['Type']?></th>
									<th><?=$lng['Date']?></th>
									<th data-sortable="false"><?=$lng['Days']?></th>
									<th data-sortable="false"><?=$lng['Hours']?></th>
									<th data-sortable="false"><?=$lng['Status']?></th>
									<th data-sortable="false">Cert.</th>
									<th data-sortable="false"><?=$lng['Reason']?></th>
									<th data-sortable="false" style="width:1px"><i data-toggle="tooltip" title="Approved" class="fa fa-check-square-o fa-lg"></i></th>
								</tr>
							</thead>
							<tbody>
		
							</tbody>
						</table>
					</div>
					
				</div>
				
				<div class="tab-pane" id="tab_history"></div>
				
			</div>
				
	</div>
	
<style>
	#modalEmployeeDetails .modal-dialog {
	  width:750px;
	}
	.modalEmployeeTable {
		cursor:default;
		table-layout:auto;
		border-collapse:collapse;
		margin:0px;
		width:100%;
	}
	.modalEmployeeTable tr {
		border-bottom:1px #eee solid;
	}
	.modalEmployeeTable tr:last-child {
		border-bottom:0;
	}
	.modalEmployeeTable th, .modalEmployeeTable td {
		padding:5px 3px 1px;
		white-space:nowrap;
		text-align:left;
	}
	.modalEmployeeTable th {
		text-align:right;
		font-weight:700;
		line-height:100%;
		color: #003366;
		width:10px;
	}
	.modalEmployeeTable input[type=text],
	.modalEmployeeTable select {
		width:100%;
		border:0;
		padding-left:5px;
	}
	.modalEmployeeTable select {
		padding-left:1px;
	}
	.popover {
		max-width:400px !important;
		width:auto !important;
	}
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>
	
	<!-- Modal modalLeaveDetails -->
	<div class="modal fade" id="modalLeaveDetails" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:650px">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom:10px;">
					<div type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true"><i class="fa fa-times"></i></span>
						<span class="sr-only"><?=$lng['Close']?></span>                
					</div>
					<h4 class="modal-title">
						<b><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Leave details']?> <span id="memp_id"></span></b>
					</h4>
				</div>
				
				<div class="modal-body" style="padding-top:15px">
					<span id="leave_table1"></span>
					<div style="padding-top:10px; float:right;">
						<button type="button" data-dismiss="modal" class="btn btn-primary btn-sm"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
					</div>
					<div class="clear"></div>
				</div>
				
	
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<!-- Modal modalLeaveBalance -->
	<div class="modal fade" id="modalLeaveBalance" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:650px">
			<div class="modal-content">
				<div class="modal-header" style="padding-bottom:10px;">
					<div type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true"><i class="fa fa-times"></i></span>
						<span class="sr-only"><?=$lng['Close']?></span>                
					</div>
					<h4 class="modal-title">
						<b><i class="fa fa-balance-scale"></i>&nbsp; <?=$lng['Leave balance']?> <span id="memp_id"></span></b>
					</h4>
				</div>
				
				<div class="modal-body" style="padding-top:15px">
					<span id="leave_balance"></span>
					<div style="padding-top:10px; float:right;">
						<button type="button" data-dismiss="modal" class="btn btn-primary btn-sm"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
					</div>
					<div class="clear"></div>
				</div>
				
	
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<script src='../assets/js/moment.min.js'></script>
	<script src="../assets/js/bootstrap-year-calendar.js?<?=time()?>"></script>
	<? if($lang == 'th'){ ?>
	<script src="../assets/js/languages/bootstrap-year-calendar.th.js?<?=time()?>"></script>
	<? } ?>
	<script src="../assets/js/jquery.autocomplete.js"></script>

	<script type="text/javascript">
		
		$(document).ready(function() {

			headerCount = 1;
			var employees = <?=json_encode($emp_array)?>;
			var emps = <?=json_encode($emps)?>;

			$(document).on("click", "#approvePeriod", function(e){
				$.ajax({
					url: "ajax/approve_leave_period.php",
					success: function(result){
						if(result=='success')
						{
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Leave period successfuly approved']?>',
								duration: 2,
							})

							dtable.ajax.reload(null, false);
						}else
						{
							("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								//closeConfirm: true
							})
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})

					}
				});
			});
			
			var dtable = $('#datatable').DataTable({
				scrollY:        	false,//scrY,//heights-260,
				scrollX:        	false,
				scrollCollapse: 	false,
				fixedColumns: 		false,
				lengthChange: 		false,
				pageLength: 		14,
				paging: 				true,
				searching:			true,
				ordering:			true,
				filter: 				false,
				info: 				false,
				autoWidth:			false,
				<?=$dtable_lang?>
				processing: 		false,
				serverSide: 		true,
				order: 				[[6,"asc"]],
				columnDefs: [
					{ targets: [9,10,12], class: 'tac' },
					//{ targets: [0], class: 'selEmp' },
					{ targets: [11], width: '60%' },
					{ targets: [1], class: 'pad2 tac' },
					{ targets: [7,8], class: 'tar' }
				],
				ajax: {
					url: "ajax/server_get_leaves_to_approve.php",
					data: function(d){
						d.empFilter = $("#empFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					setTimeout(function(){
						$('#showTable').fadeIn(200);
						dtable.columns.adjust().draw();
					},50);
				}
			});
			
			function removeFilterClass(){
				$('.but-filter').each(function(){
					$(this).removeClass('activ')
				})
			}
			
			$(document).on("click", "a.edit", function(e){
				e.preventDefault();
				var id = $(this).data('id');
				//var emp_id = $(this).closest('tr').find('#emp_id').html();
				//alert(id);
				location.replace('index.php?mn=21&id='+id);
				//return false;
						
			});
			
			$(document).on("click", "a.details", function(e){
				e.preventDefault();
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: "ajax/get_leave_details.php",
					data: {id: id},
					success:function(result){
						//var data = jQuery.parseJSON(result);
						$("#leave_table1").html(result);
						//alert(result);
						/*$('#memp_id').html(data.name);
						$('#table1').html(data.table1);
						$('#table2').html(data.table2);
						$('#table3').html(data.table3);*/
						$("#modalLeaveDetails").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});
			
			$(document).on("click", "a.balance", function(e){
				e.preventDefault();
				var id = $(this).data('id');
				//alert(id);
				$.ajax({
					url: "ajax/get_leave_balance.php",
					data: {emp_id: id},
					success:function(result){
						$("#leave_balance").html(result);
						//alert(result);
						//$('#dump').html(result);
						$("#modalLeaveBalance").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			$.ajax({
				url: "ajax/get_leave_summary.php",
				data: {emp_id: '', res: 'table'},
				success: function(result){
					$('#summaryTable').html(result);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
					
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#selEmployee').val('');
				$("#empFilter").val('');
				dtable.ajax.reload(null, false);
			})
			
			$('#selEmployee').devbridgeAutocomplete({
				 lookup: employees,
				 triggerSelectOnValidInput: false,
				 onSelect: function (suggestion) {
					$("#empFilter").val(suggestion.data);
					dtable.ajax.reload(null, false);
				 }
			});	
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('activeTab', $(e.target).data('target'));
				dtable.columns.adjust().draw();
			});
			/*var activeTab = localStorage.getItem('activeTab');
			if(activeTab){
				$('#myTab a[data-target="' + activeTab + '"]').tab('show');
			}*/

		});
	
	</script>
						
						


















