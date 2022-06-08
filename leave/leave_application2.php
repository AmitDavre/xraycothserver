<?
	
	updateLeaveDatabase($cid);
	
	$leave_settings = getLeaveTimeSettings();
	$disabledWeekdays = '[0,6]';
	if($leave_settings['workingdays'] == 5){$disabledWeekdays = '[0,6]';}
	if($leave_settings['workingdays'] == 6){$disabledWeekdays = '[0]';}
	if($leave_settings['workingdays'] == 7){$disabledWeekdays = '[]';}
	
	$legend[$lng['Requested']] = 'RQ';
	$legend[$lng['Approved']] = 'AP';
	$legend[$lng['Rejected']] = 'RJ';
	$legend[$lng['Cancelled']] = 'CA';
	$legend[$lng['Taken']] = 'TA';
	$legend[$lng['Public holiday']] = 'holiday';
	//$legend[$lng['Normal working day']] = 'working';
	$legend[$lng['Non-working day']] = 'nonworking';

	$leave_status = array('RQ'=>$lng['Pending'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected']);
	$emps = getEmployees($cid,0);
	$emp_array = getJsonIdsEmployees();
	
	//var_dump($leave_periods); exit;
	
?>	

<style>
	table.mytable tbody td, 
	table.mytable thead th {
		text-align:left;
		padding:2px 10px;
	}
	
	table.sum tbody td, 
	table.mytable tfoot td {
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
		padding:4px 15px;
		font-size:13px;
		margin:0 5px 0 0;
		cursor:default;
	}
	
	.RQ {
	  background: #C5B8EE !important;
	}		
	.AP {
	  background: #9CE7B9 !important;
	}		
	.RJ {
	  background: #F593D3 !important;
	}		
	.CA {
	  background: #F8CD96 !important;
	}		
	.TA {
	  background: #C4DAEE !important;
	}		
	.holiday {
	  background: #fb0 !important;
	}		
	.nonworking {
	  background: #eee !important;
	}		
	.working {
	  background: #fff !important;
	  border:1px #ddd solid;
	}
	table.dataTable tbody td a {
		text-decoration:none;
		font-weight:600;
		color:#003399;
	}	
	table.dataTable tbody td a:hover {
		xcolor:#c00 !important;
	}
	table.dataTable tr td:nth-child(1) {
		cursor:pointer !important;
	}
	table.dataTable tr td:nth-child(1):hover a {
		color:#c00 !important;
		text-decoration:underline;
	}
		
	.date_month, .cdate_month {
		cursor:pointer;
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
	
	
	table.basicTable table.detailTable {
		width:100%;
	}
	table.basicTable table.detailTable tbody td {
		padding:2px 8px !important;
		border:0;
	}
	table.basicTable table.detailTable tr {
		border-bottom:1px solid #eee;
	}
	table.basicTable table.detailTable tr:last-child {
		border-bottom:0;
	}
	
	
	table.noStyle tr {
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
	}
		
	.btn-small {
		padding:0 10px;
		margin:0;
		background: #006699;
		color:#fff;
		border:0;
		font-size:14px;
		line-height:30px;
		text-align:center;
		width:100%;
		display:block;
		cursor:pointer;
	}
	.popTable {
		width:230px;
		border-collapse:collapse;
		table-layout:fixed;
		margin:5px 0;
	}
	.popTable td {
		padding:3px;
	}
	.popTable td .btn {
		padding:2px 10px !important;
		text-align:center;
		width:100%;
		font-size:14px !important;
		font-weight:400 !important;
		color:#333 !important;
	}
	.popTable td input[type="text"] {
		padding:4px 10px;
		text-align:center;
		width:100%;
		cursor:pointer;
		font-size:14px !important;
		color:#333 !important;
		font-weight:400 !important;
	}
	.popTable td input[type="text"]:hover {
		border:1px solid #999;
	}
	.dayType {
		min-width:100px !important;
		font-size:13px !important;
		font-weight:400 !important;
		text-align:center !important;
	}
	.popover {
    z-index: 9010; /* A value higher than 1010 that solves the problem */
	}
	.selEmp {
		line-height:26px;
		width:100%;
		display:block;
		padding:0 8px;
	}
	.selStatus {
		padding:0px 5px 1px !important;
		xmargin:0 3px !important;
		border-radius:2px !important;
		color:#fff !important;
		font-size:12px !important;
	}
	.selStatus option {
		background:#fff;
		color:#000;
	}
	table.dataTable tbody td.pad05 {
		padding:0 5px !important;
	}

</style>

	<h2><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Leave application']?></h2>		
		
		<div class="main leave-app">
			<div id="dump"></div>
					
			<div class="searchFilter show-800" style="margin-bottom:5px; width:100%">
				<input style="width:100%" class="sFilter" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selEmployee" />
				<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
			</div>
			<div style="clear:both"></div>

			<ul style="position:relative" class="nav nav-tabs" id="myTab">
				<li class="hide-800" style="padding:0 5px 0 0">
					<div class="searchFilter" style="margin-bottom:0px; width:300px; border:0px red solid">
						<input style="width:100%" class="sFilter" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" id="selEmployee" />
						<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
					</div>
				</li>
				<!--<li class="nav-item"><a class="nav-link" href="#tab_request" data-toggle="tab"><?=$lng['Leave request']?></a></li>-->
				<li class="nav-item"><a class="nav-link active" href="#tab_overview" data-toggle="tab">Leave overview<? //=$lng['Leave applied']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_summary" data-toggle="tab"><?=$lng['Leave summary']?></a></li>
				<li class="nav-item"><a class="nav-link" href="#tab_calendar" data-toggle="tab"><?=$lng['Leave calendar']?></a></li>
			</ul>

			<div class="tab-content">
				
				<button style="position:absolute; top:-37px;
				right:0" id="addLeave" data-toggle="modal" data-target="#modalAddLeave" disabled type="button" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Add Leave<? //=$lng['Taken']?></button>
				
				<input type="hidden" id="empFilter" />
				<input type="hidden" id="statFilter" value="status = 'RQ'" />

				<div class="tab-pane" id="tab_request">

				</div>
				
				<div class="tab-pane show active" id="tab_overview">
					<div id="showTable" style="display:none">
						<div style="margin-bottom:3px">
							<button type="button" class="but-filter abutALL"><?=$lng['All leaves']?></button>
							<button type="button" class="but-filter abutPE"><?=$lng['Pending']?></button>
							<button type="button" class="but-filter abutRQ activ"><?=$lng['Requested']?></button>
							<button type="button" class="but-filter abutAP"><?=$lng['Approved']?></button>
							<button type="button" class="but-filter abutRJ"><?=$lng['Rejected']?></button>
							<button type="button" class="but-filter abutCA"><?=$lng['Cancelled']?></button>
							<button type="button" class="but-filter abutTA"><?=$lng['Taken']?></button>
						</div>
						
						<table id="datatable" class="dataTable nowrap compact">
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
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Edit leave']?>" class="fa fa-edit fa-lg"></i></th>
									<th data-sortable="false"><i data-toggle="tooltip" title="<?=$lng['Edit leave']?>" class="fa fa-trash fa-lg"></i></th>
								</tr>
							</thead>
							<tbody>
		
							</tbody>
						</table>
						
					</div>
					
				</div>
				
				<div class="tab-pane" id="tab_summary">
					<table border="0" style="margin:0 0 10px; width:auto">
						<tr>
							<td style="vertical-align:top; width:1px"><img id="sum_img" style="height:160px; width:160px; border:10px #ddd solid; padding:1px; border-radius:2px; background:#fff" src="../images/profile_image.jpg" /></td>
							<td style="vertical-align:top; padding-left:20px">
								<div id="summaryTable" style="margin:0px 0"></div>
								<canvas id="canvas" height="120px"></canvas>
								<div style="height:30px"></div>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="tab-pane" id="tab_calendar" style="position:relative; min-height:480px">
					<div id="calendar"></div>
					<div style="padding:5px 8px">
					<? foreach($legend as $k=>$v){ ?>
						<div class="legend <?=$v?>"><?=$k?></div>
					<? } ?>
					</div>
					<div class="clear"></div>
				</div>
				
			
	</div>
	
	<style>
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
	
	<!-- Modal addLeave -->
	<div class="modal fade" id="modalAddLeave" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:1000px">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-plane"></i>&nbsp; Add Leave<? //=$lng['Leave details']?> <span id="memp_id"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:15px 20px 20px">
					<table style="width:100%; table-layout:fixed">
						<tr>
						<td style="width:52%; vertical-align:top; padding-right:10px; border-right:2px #eee solid">
							<form id="leaveRequest">
								<input name="leave_id" id="leave_id" type="hidden" value="0" />
								<input name="update" id="update" type="hidden" value="0" />
								<input name="by_name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
								<input name="emp_id" id="emp_id" type="hidden" />
								<input name="status" id="status" type="hidden" />
								<input name="attachment" type="hidden" />
								<input style="visibility:hidden; height:0;" id="attach" type="file" name="attach" />
								
								<table id="requestTable" class="basicTable inputs" border="0">
									<tbody>
										<tr>
											<th class="vat"><i class="man"></i><?=$lng['Employee']?></th>
											<td><input name="name" type="text" /></td>
											<td id="imgtd" rowspan="5" style="width:1px; padding:0 !important; vertical-align:top">
												<img id="emp_img" style="height:140px; width:140px; border:10px #eee solid; padding:1px; background:#fff" src="../images/profile_image.jpg" />
											</td>
										</tr>
										<tr>
											<th><?=$lng['Phone']?></th>
											<td><input readonly name="phone" type="text" /></td>
										</tr>
										<tr>
											<th class="vat"><i class="man"></i><?=$lng['Leave type']?></th>
											<td style="padding-right:10px !important">
												<select name="leave_type" id="leave_type" style="width:100%">
													<option disabled selected value="0"><?=$lng['Select']?> leave type</option>
													<? foreach($leave_types as $k=>$v){ ?>
															<option value="<?=$k?>"><?=$v['en']?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['First day']?></th>
											<td><input style="cursor:pointer" readonly type="text" id="startdate"></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Last day']?></th>
											<td><input style="cursor:pointer" readonly type="text" id="enddate"></td>
										</tr>
										<tr>
											<th><?=$lng['Details']?></th>
											<td colspan="2" style="padding:0; cursor:default;">
												<span id="rangeTable"></span>
											</td>
										</tr>
										<tr id="certRow" style="display:none">
											<th><?=$lng['Certificate']?></th>
											<td class="pad410" colspan="2" style="white-space:normal">
												
												<input type="hidden" name="certificate" id="certificate" value="NA" />
												
												<label><input type="radio" name="certificate" value="H" class="radiobox style-0"><span> Handed to HR department<? //=$lng['Handed to HR department or supervisor']?></span></label>&nbsp;&nbsp;&nbsp;
												
												<label><input type="radio" name="certificate" value="N" class="radiobox style-0"><span> <?=$lng['No certificate']?></span></label>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Reason']?></th>
											<td colspan="2">
												<textarea rows="2" name="reason" id="reason"></textarea>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Attachement']?></th>
											<td class="pad410" colspan="2">
												<button onclick="$('#attach').click()" class="btn btn-default btn-xs" style="margin:0; padding:0px 8px; display:inline-block" type="button"><?=$lng['Select file']?></button><span style="display:inline-block; padding-left:10px" id="attachMsg"><?=$lng['No file selected']?></span>
											</td>
										</tr>
									</tbody>
								</table>
								
								<div style="height:6px"></div>
								<div id="requestMsg" style="color:#c00; font-weight:600; display:none"></div>
								<div style="height:8px"></div>
								
								<table border="0" style="width:100%">
									<tr>
										<td>
											<button onclick="$('#status').val('RQ');" class="btn btn-primary btn-sm" style="margin:0 5px 0 0;" id="requestBut" type="submit"><i class="fa fa-feed"></i>&nbsp;&nbsp;<?=$lng['Request']?></button>
											<button class="btn btn-primary btn-sm" style="margin:0 5px 0 0; display:none" id="updateBut" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
										</td>
										<? //if($_SESSION['rego']['access']['leave']['approve'] == 1){?>
										<td class="vat">
											<button onclick="$('#status').val('AP');" class="btn btn-primary btn-sm" style="margin:0 5px 0 0;" id="approveBut" type="submit"><i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;<?=$lng['Approve']?></button>
										</td>
										<? //} ?>
										<!--<td>
											<button class="btn btn-primary btn-sm" style="margin:0 5px 0 0; display:none" id="newRequest" type="button"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;<?=$lng['New request']?></button>
										</td>-->
										<td style="width:90%"></td>
										<td>
											<button data-dismiss="modal" class="btn btn-primary btn-sm" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
										</td>
									</tr>
								</table>
							
							</form>
						</td>
						<td style="width:48%; vertical-align:top; padding-left:10px">
							<div id="balanceTable"></div>
						</td>
						</tr>
					</table>
				</div>
				
			</div>
		</div>
	</div>

	<!-- Modal modalLeaveDetails -->
	<div class="modal fade" id="modalStatus" tabindex="-1" role="dialog">
		<div class="modal-dialog" style="width:500px">
			<div class="modal-content">
				<div class="modal-body" style="padding-top:15px">
					<button type="button" class="but-filter abutRQ">Request<? //=$lng['Approved']?></button>
					<button type="button" class="but-filter abutAP">Approve<? //=$lng['Approved']?></button>
					<button type="button" class="but-filter abutRJ">Reject<? //=$lng['Approved']?></button>
					<button type="button" class="but-filter abutCA">Cancel<? //=$lng['Cancelled']?></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal modalLeaveDetails -->
	<div class="modal fade" id="modalLeaveDetails" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-list-ul"></i>&nbsp; <?=$lng['Leave details']?> <span id="memp_id"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-top:15px">
					<span id="leave_table1"></span>
					<button data-dismiss="modal" class="btn btn-primary btn-sm" style="margin:10px 0 0 0; float:right" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
					<div class="clear"></div>
				</div>
	
			</div>
		</div>
	</div>

	<!-- Modal modalLeaveBalance -->
	<div class="modal fade" id="modalLeaveBalance" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-balance-scale"></i>&nbsp; <?=$lng['Leave balance']?> <span id="memp_id"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-top:15px">
					<span id="leave_balance"></span>
					<button data-dismiss="modal" class="btn btn-primary btn-sm" style="margin:10px 0 0 0; float:right" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src='../js/moment.min.js'></script>
	<script type="text/javascript" src="../yearcalendar/js/bootstrap-year-calendar.js?<?=time()?>"></script>
	<? if($lang == 'th'){ ?>
	<script type="text/javascript" src="../yearcalendar/js/languages/bootstrap-year-calendar.th.js?<?=time()?>"></script>
	<? } ?>
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
	<script src="../timepicker/dist/jquery-clockpicker.min.js"></script>
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			
			headerCount = 1;
			
			var employees = <?=json_encode($emp_array)?>;
			var emps = <?=json_encode($emps)?>;
			var leave_types = <?=json_encode($leave_types)?>;
			var emp_id;
			
			function getBalance(){
				$.ajax({
					url: ROOT+"leave/ajax/get_leave_balance.php",
					data: {emp_id: emp_id},
					success: function(result){
						$('#balanceTable').html(result);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Error']?> ' + thrownError);
					}
				});
			}
			function getSummary(){
				$.ajax({ // get leave summary
					url: ROOT+"leave/ajax/get_leave_summary.php",
					data: {emp_id: emp_id, res: 'table'},
					success: function(result){
						//$('#dump').html(result);
						$('#summaryTable').html(result);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Error']?> : ' + thrownError);
					}
				});
			}
			function removeFilterClass(){
				$('.but-filter').each(function(){
					$(this).removeClass('activ')
				})
			}
			function clearApp(){
				emp_id = '';
				$("#selEmployee").val('');
				$('input[name="emp_id"]').val('xxx');
				$("#empFilter").val('');
				$("#statFilter").val('status = "RQ"');
				$('#rangeTable').html('');
				$('#balanceTable').html('');
				$('#leaveRequest').trigger('reset');
				$('#attachMsg').html('<?=$lng['No file selected']?>');
				$('#attach').val('');
				$('#emp_img').prop('src', ROOT+'images/profile_image.jpg');
				$("#requestBut").prop('disabled', false);
				$("#approveBut").prop('disabled', false);
				//$("#newRequest").css('display', 'none');
				$("#sum_img").prop('src', ROOT+'images/profile_image.jpg');
				dtable.column(0).search('').draw();
				getSummary()
				calendar.setYear('<?=$_SESSION['rego']['cur_year']?>');
				removeFilterClass()
				$('.abutRQ').addClass('activ');
				$("#addLeave").prop('disabled', true);
			}
			
			function refreshApp(){
				getBalance()
				getSummary()
				dtable.column(0).search($("#empFilter").val()).draw();
				$("#sum_img").prop('src', ROOT+emps[emp_id]['image']);
				calendar.setYear('<?=$_SESSION['rego']['cur_year']?>');
				removeFilterClass()
				$('.abutRQ').addClass('activ');
			}
			function showLeave(emp_id){
				$("#statFilter").val("status = 'RQ'");
				$("#empFilter").val(emp_id);
				$("#selEmployee").val(emp_id+' - '+emps[emp_id][lang+'_name']);
				$('input[name="emp_id"]').val(emp_id);
				$('input[name="name"]').val(emps[emp_id][lang+'_name']);
				$('input[name="phone"]').val(emps[emp_id]['phone']);
				$('input[name="update"]').val(0);
				$('input[name="leave_id"]').val(0);
				$("#emp_img").prop('src', ROOT+emps[emp_id]['image']);
				$("#addLeave").prop('disabled', false);
				refreshApp();
			}
			
			getSummary()

			$(document).on("click", "a.edit", function(e){
				var id = $(this).data('id');
				emp_id = $(this).closest('tr').find(".balance").data('id');
				//alert(emp_id)
				$.ajax({
					url: "ajax/get_leave_details.php",
					data: {id: emp_id, lid: id},
					dataType: 'json',
					success:function(data){
						//$('#dump').html(data); return false;
						$('input[name="leave_id"]').val(data.leave_id);
						$('input[name="update"]').val(1);
						$('input[name="emp_id"]').val(data.emp_id);
						$('input[name="name"]').val(data.name);
						$('input[name="status"]').val(data.status);
						if(data.attach != ''){ // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
							$('#attachMsg').html('<a target="_blank" href="'+data.attach+'">Uploaded document</a>');
						}else{
							$('#attachMsg').html('No file selected');
						}
						$('input[name="attachment"]').val(data.attach);
						$('input[name="phone"]').val(data.phone);
						$('select[name="leave_type"]').val(data.leave_type);
						$('input:radio[name=certificate]').filter('[value='+data.certificate+']').prop('checked', true);
						$('textarea[name="reason"]').val(data.reason);
						$('#startdate').val(data.start);
						$('#enddate').val(data.end);
						$('#emp_img').attr('src', data.img);
						
						$('#requestBut').css('display','none');
						$('#approveBut').css('display','none');
						$('#updateBut').css('display','block');
						$('#message').html('').hide();
						$("#subButton").prop('disabled', false);
						$('#rangeTable').html(data.table);
						getBalance();
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Sorry but someting went wrong']?> ' + thrownError);
					}
				});
			});
			
			$(document).on('click', '#datatable tbody tr td a.selEmp', function () {
				emp_id = $(this).closest("tr").find(".balance").data('id');
				//alert(emp_id)
				$("#addLeave").prop('disabled', false);
				showLeave(emp_id);
			})
			
			/*$(document).on('change', '#datatable .selStatus', function () {
				var id = $(this).closest("tr").find(".edit").data('id');
				var status = $(this).val();
				//alert(status)
				$.ajax({
					url: ROOT+"leave/ajax/update_leave_status.php",
					data: {id: id, status: status},
					success: function(result){
						//$('#dump').html(result);
						dtable.ajax.reload(null, false);
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert('<?=$lng['Error']?> : ' + thrownError);
					}
				});
			})*/
			
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
			
			var calendar = $('#calendar').calendar({ 
				enableContextMenu: false,
				enableRangeSelection: false,
				disabledWeekDays: <?=$disabledWeekdays?>,
				displayWeekNumber: true,
				displayHeader: false,
				language: '<?=$lang?>',
				startYear: <?=$_SESSION['rego']['cur_year']?>,
				weekStart: 1,
				style: 'background',
				yearChanged: function(e) {
					//e.preventRendering = true;
					//$(e.target).append('<div style="text-align:center"><img src="../images/loading.gif" /></div>');
					$.ajax({ 
						url: ROOT+"leave/ajax/json_calendar_leave_events.php", 
						dataType: "json",
						data:{emp_id: emp_id},
						success: function(response) {
							//alert(response.color);
							 var myData = [];
							 for (var i = 0; i < response.length; i++) {
								  myData.push({
									 //id: response[i].id,
									 name: response[i].title,
									 //type: response[i].type,
									 startDate: new Date(response[i].start),
									 endDate: new Date(response[i].start),
									 color: response[i].color
								  });
							 }
					
							 $(e.target).data('calendar').setDataSource(myData);
						} 
					});
					//alert(myData['title'])
				},
				customDayRenderer: function(element, date) {
					//alert(date.getTime())
					//mdata = jQuery.parseJSON(this.dataSource);
					//alert(myData)
					//if(typeof (this.dataSource) != "undefined") {
								//$(element).html('X') ;
								//$(element).addClass(this.dataSource[i].color) ;
					//}
				},
				mouseOnDay: function(e) {
					if(e.events.length != 0) {
						 var content = '';
						 for(var i in e.events) {
							  content += '<div class="event-tooltip-content">'
										+ '<div class="event-name">' + e.events[i].name + '</div>'
										+ '</div>';
						 }
					
						 $(e.element).popover({ 
							  trigger: 'manual',
							  placement: 'top',
							  container: 'body',
							  html:true,
							  content: content
						 });
						 
						 $(e.element).popover('show');
					}
				},
				mouseOutDay: function(e) {
					if(e.events.length > 0) {
						 $(e.element).popover('hide');
					}
				},
				dataSource: []
			});

			var dtable = $('#datatable').DataTable({
				scrollY:        false,//scrY,//heights-260,
				scrollX:        true,
				//scrollCollapse: false,
				//fixedColumns: 	false,
				lengthChange: 	false,
				pageLength: 	14,
				paging: 		true,
				searching:		true,
				ordering:		true,
				filter: 		false,
				info: 			false,
				//autoWidth:		false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				//order: [[ 11, "asc" ]],
				columnDefs: [
					{ targets: [7,8,9,12,13,14,15,16], class: 'tac' },
					{ targets: [1], class: 'pad2' },
					{ targets: [2], class: 'pad2 tac' },
					{ targets: [2], class: 'pad2' },
					{ targets: [10], class: 'pad05' },
					{ targets: [11], width: '60%' }
				],
				ajax: {
					url: ROOT+"leave/ajax/server_get_leaves.php",
					type: "POST",
					data: function(d){
						d.empFilter = $("#empFilter").val();
						d.statFilter = $("#statFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					//$(".fa-refresh").removeClass('fa-refresh fa-spin').addClass('fa-table');
					dtable.columns.adjust().draw();
					//alert('init')
					
				}
			});
			
			$('.abutAP').on('click', function () {
				$("#statFilter").val("status = 'AP'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutRJ').on('click', function () {
				$("#statFilter").val("status = 'RJ'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutCA').on('click', function () {
				$("#statFilter").val("status = 'CA'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutRQ').on('click', function () {
				$("#statFilter").val("status = 'RQ'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutPE').on('click', function () {
				$("#statFilter").val("status != 'CA' && status != 'TA'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutTA').on('click', function () {
				$("#statFilter").val("status = 'TA'");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$('.abutALL').on('click', function () {
				//$("#filter").val("status != 'CA' && status != 'TA'");
				$("#statFilter").val("");
				removeFilterClass()
				$(this).addClass('activ')
				dtable.ajax.reload(null, false);
				setTimeout(function(){dtable.columns.adjust().draw();},100);
			});			
			$(document).on("click", "#clearSearchbox", function(e) {
				clearApp();
			})
			
			$(document).on("change", "#leave_type", function(e){
				var cert = leave_types[$(this).val()]['certificate'];
				if(cert == 0){
					$('#certRow').hide();
					//$('#certificate').val('N');
				}else{
					$('#certRow').show();
				}
			});

			$(document).on("click", "#certAttach", function(e){
				e.preventDefault();
			})
			$(document).on("change", "#attach", function(e){
				e.preventDefault();
				//var id = cid.replace('x',acc);
				//alert('id');
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				//alert(ff)
				$('#attachMsg').html('');
				if(!(ext == 'pdf' || ext == 'doc' || ext == 'docx' || ext == 'txt' || ext == 'jpg' || ext == 'jpeg' || ext == 'xls' || ext == 'xlsx')){
					alert('Please use only .pdf - .doc - .docx - .txt - .jpg - .jpeg - .xls - .xlsx files');
					return false;
				}
				$('#attachMsg').html(ff);
				//$('#message').html('');
				$('input:radio[name=certificate]').filter('[value=Y]').prop('checked', true);
				//$("form#leaveRequest").submit();
				return false;
			});
			
			$('#modalAddLeave').on('show.bs.modal', function () {
				popOver.popover('dispose');
				popOver = $('body').popover(popRequestSettings);
				getBalance();
			})
			$('#modalAddLeave').on('hidden.bs.modal', function () {
				$('input[name="attachment"]').val('');
				$('#attachMsg').html('');
				$('select[name="leave_type"]').val(0);
				//$('input:radio[name=certificate]').filter('[value='+data.certificate+']').prop('checked', true);
				$('textarea[name="reason"]').val('');
				$('#startdate').val('');
				$('#enddate').val('');
				$('#rangeTable').html('');
				$('#requestMsg').html('').hide();
				
				popOver.popover('dispose');
				popOver = $('body').popover(popActionSettings);
			});
			
			$(document).on('focus',"#timeFrom", function(){
				$(this).clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'left',
					afterDone: function() {
						$('#timeUntil').prop('disabled', false);
						$('#timeUntil').focus();
					}
				});
			});			
			$(document).on('focus',"#timeUntil", function(){
				$(this).clockpicker({
					autoclose: true,
					placement: 'bottom',
					align: 'right',
					afterDone: function() {
						$('#timeUntil').trigger("change");
					}
				});
			});			
			
			var dayType;
			$(document).on('click','.dayType', function(e) {
				dayType = $(this).data('id');
				//alert(id)
			});			
			$(document).on('click','.selDayType', function(e) {
				var type = $(this).data('id');
				 $('.day'+dayType).html($(this).text());
				$('#mday'+dayType).val(type);
				$('body [data-toggle="popOver"]').popover('hide');
			});			
			
			$(document).on('change','#timeUntil', function(e) {
				var hours = $('#timeFrom').val() + ' - ' + $(this).val();
				$('.day'+dayType).html(hours);
				$('#mday'+dayType).val(hours);
				$('body [data-toggle="popOver"]').popover('hide');
			});			
			
			var popRequestSettings = {
				placement: 'right',
				container: 'body',
				html: true,
				selector: '[data-toggle="popover"]', //Sepcify the selector here
				title: '',
				content: '<table class="popTable" border="0">'+
								'<tr>'+
									'<td colspan="2">'+
										'<button data-id="full" class="selDayType btn btn-default btn-xs" type="button">Full day</button>'+
									'</td>'+
								'</tr>'+
								'<tr>'+
									'<td><button data-id="first" class="selDayType btn btn-default btn-xs" type="button">First half</button></td>'+
									'<td><button data-id="second" class="selDayType btn btn-default btn-xs" type="button">Second half</button></td>'+
								'</tr>'+
								'<tr>'+
									'<td><input id="timeFrom" class="timePic" readonly placeholder="From 00:00" type="text" value=""></td>'+
									'<td><input id="timeUntil" class="timePic" disabled readonly placeholder="Until 00:00" type="text" value=""></td>'+
								'</tr>'+
							'</table>'
			}	
			var popActionSettings = {
				placement: 'right',
				container: 'body',
				html: true,
				selector: '[data-toggle="popover"]', //Sepcify the selector here
				title: '<span id="pop_title">Title</span>',
				content: '<form id="popForm" class="popReject">'+
						'<div><textarea placeholder="Reason" name="comment" rows="3" style="width:350px; border:0; padding:0;resize:none;color:#333"></textarea></div>'+
						'<div style="padding:10px 0 5px 0">'+
						'<button type="submit" class="btn btn-default btn-xs butReject" style="display:inline-block;float:left"><i class="fa fa-thumbs-down-o"></i>&nbsp;Submit</button>'+
						'<button type="button" class="btn btn-default btn-xs butCancel" style="display:inline-block;float:right">Cancel</button>'+
						'<div style="clear:both;"></div></div></form>'
			}	
			var popOver = $('body').popover(popActionSettings);

			$('body').on('hidden.bs.popover', function (e) {
				 $(e.target).data("bs.popover").inState.click = false;
			});			
			
			var row_id;
			var leave_id;
			var action;
			$(document).on("click", "a.approve", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				//alert(row_id)
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
			});
			$(document).on("click", "a.reject", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				$('#pop_title').html('<i class="fa fa-thumbs-down"></i>&nbsp; Reject reason');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).on("click", "a.cancel", function(e){
				row_id = $(this).closest('tr').find('.edit').data('id');
				leave_id = $(this).closest('tr').find('.leaveid').html();
				action = $(this).data('action');
				$('#pop_title').html('<i class="fa fa-times-circle"></i>&nbsp; Cancel reason');
				//$('#popForm').removeClass().addClass('cancelForm');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).on('click','.butCancel', function(e) {
				$('body [data-toggle="popover"]').popover('hide');
			});			
			
			$(document).on("submit", "#popForm", function(e){
				e.preventDefault();
				var data = $(this).serialize();
				data += '&row_id='+row_id;
				data += '&leave_id='+leave_id;
				data += '&action='+action;
				//alert(action)
				$.ajax({
					url: ROOT+"leave/ajax/save_leave_action.php",
					data: data,
					success: function(result){
						//$("#dump").html(result); return false;
						if(result == 'success'){
							$('#amessage').html('<div class="msg_success nomargin" style="padding:4px 8px">Database updated successfuly.</div>').fadeIn(200);
							setTimeout(function(){$('#amessage').fadeOut(200);},2500);							
						}else{
							$('#amessage').html('<div class="msg_alert nomargin" style="padding:4px 8px">Error : '+result+'</div>').fadeIn(200);
							setTimeout(function(){$('#amessage').fadeOut(200);},2500);							
						}						
						$('body [data-toggle="popover"]').popover('hide');
						dtable.ajax.reload(null, false);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$('#amessage').html('<div class="msg_error nomargin" style="padding:4px 8px"><?=$lng['Error']?> : '+thrownError+'</div>').fadeIn(200);
						setTimeout(function(){$('#amessage').fadeOut(200);},4000);							
					}
				});
			})
			
			
			$("#leaveRequest").on('submit', function(e){ 
				e.preventDefault();
				var err = false;
				$('#requestMsg').html('').hide();
				if($('#leave_type').val() == null){err = true;}
				if($('#startdate').val() == ""){err = true;}
				if($('#enddate').val() == ""){err = true;}
				//err = false;
				if(err == true){
					$('#requestMsg').html('<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please fill in required fields').fadeIn(300);
					return false;
					/*$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please fill in required fields',
						duration: 4,
						closeConfirm: true
					})*/
				}

				var id = $('#emp_id').val();
				if($('#status').val() == 'RQ'){
					$("#requestBut i").removeClass('fa-feed').addClass('fa-refresh fa-spin');
				}else{
					$("#approveBut i").removeClass('fa-thumbs-o-up').addClass('fa-refresh fa-spin');
				};
				$("#updateBut i").removeClass('fa-save').addClass('fa-refresh fa-spin');
				var data = new FormData(this);
				//alert(data)
				
				$.ajax({
					url: ROOT+"leave/ajax/save_leave_request.php",
					type: "POST", 
					data: data,
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							if($('#update').val() == 0){
								$('#requestMsg').html('<i class="fa fa-check"></i>&nbsp;&nbsp;Request send successfuly').fadeIn(300);
							}else{
								$('#requestMsg').html('<i class="fa fa-check"></i>&nbsp;&nbsp;Updated successfuly').fadeIn(300);
							}
							$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							$("#approveBut i").removeClass('fa-refresh fa-spin').addClass('fa-thumbs-o-up');
							$("#updateBut i").removeClass('fa-refresh fa-spin').addClass('fa-save');
							$("#requestBut").prop('disabled', true);
							$("#approveBut").prop('disabled', true);
							//$("#subButton").prop('disabled', true);
							setTimeout(function(){$("#modalAddLeave").modal('toggle');},2000);
							refreshApp();
						}else{
							$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							$("#approveBut i").removeClass('fa-refresh fa-spin').addClass('fa-thumbs-o-up');
							$('#requestMsg').html('<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' + result).fadeIn(300);
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$('#requestMsg').html('<b><?=$lng['Error']?> :</b> '+thrownError).fadeIn(300);
						setTimeout(function(){$('#requestMsg').fadeOut(200);},4000);
					}
				});
			});
			
			var startDate = $('#startdate').datepicker({
				format: "D dd-mm-yyyy", 
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startView: 'month',
				orientation: "auto",
				//startDate: new Date(),
				leftArrow: '<i class="fa fa-arrow-left"></i>',
				rightArrow: '<i class="fa fa-arrow-right"></i>',
				todayHighlight: true,
				language: 'en',
			}).on('changeDate', function(e){
				$('#enddate').datepicker('setDate', startDate.val()).datepicker('setStartDate', startDate.val()).focus();
			});
			var endDate = $('#enddate').datepicker({
				format: "D dd-mm-yyyy",
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startView: 'month',
				startDate: new Date(),
				leftArrow: '<i class="fa fa-arrow-left"></i>',
				rightArrow: '<i class="fa fa-arrow-right"></i>',
				todayHighlight: true,
				language: 'en',
			}).on('changeDate', function(e){
				if($('#startdate').val() !== ''){
					$.ajax({
						url: "ajax/get_leave_range.php",
						data: {startDate: $('#startdate').val(), endDate: e.format()},
						success: function(result){
							$('#rangeTable').html(result); return false;
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert(thrownError);
						}
					});
				}
			})
	
			$('#selEmployee').devbridgeAutocomplete({
				 lookup: employees,
				 triggerSelectOnValidInput: false,
				 onSelect: function (suggestion) {
					emp_id = suggestion.data;
					$("#empFilter").val(suggestion.data);
				 	$("#statFilter").val("status = 'RQ'");
					
					$('input[name="emp_id"]').val(suggestion.data);
					$('input[name="name"]').val(suggestion.name);
					$('input[name="phone"]').val(suggestion.phone);
				 	$("#emp_img").prop('src', '../'+suggestion.image);
				 	$("#addLeave").prop('disabled', false);
					refreshApp();
				 }
			});	
			
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				dtable.columns.adjust().draw();
				//alert($(e.target).data('target'))
				if($(e.target).data('target') == '#tab_request'){
				}
				if($(e.target).data('target') == '#tab_applied'){
				}
			});
			
			var last_id = '';
			$(document).on('click', '#datatable .delLeave', function(e){
				last_id = $(this).data('id');
				e.preventDefault();
				e.stopPropagation();
			});
			$(document).ajaxComplete(function( event,request, settings ){		
				$('#datatable .delLeave').confirmation({
					container: 'body',
					rootSelector: '#datatable .delLeave',
					singleton: true,
					animated: 'fade',
					placement: 'left',
					popout: true,
					html: true,
					title: '<div style="text-align:center"><b><?=$lng['Are you sure']?></b></div>',
					btnOkIcon: '',
					btnCancelIcon: '',
					btnOkLabel: '<?=$lng['Delete']?>',
					btnCancelLabel: '<?=$lng['Cancel']?>',
					onConfirm: function() { 
						//alert(id);
						$.ajax({
							url: ROOT+"payroll/ajax/lock_period.php",
							data:{id:last_id },
							success: function(result){
								//alert(result)
								location.replace('index.php?mn=410');
							},
							error:function (xhr, ajaxOptions, thrownError){
								alert('3 '+thrownError);
							}
						});
					},
				});
			})


		});
	
	</script>
						
						


















