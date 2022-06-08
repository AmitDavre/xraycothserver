<?


	include(ROOT.'dbconnect/db_connect.php');
	include(ROOT.'time/functions.php');



	$scan_app = $time_settings['scan_system'];
	$userType = $_SESSION['rego']['type'];
	$cidd = $_SESSION['rego']['cid'];

	$checkedScanFSV = '1';

	$sql_check = "SELECT * FROM ".$cidd."_leave_time_settings WHERE id= '1' ";
						
	if($res_check = $dbc->query($sql_check))
	{
		while($row_check = $res_check->fetch_assoc())
		{
			$checkboxScan = unserialize($row_check['compensations']);
		}
	}

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();

?>
<style>

	.alignclass
	{
		text-align: -webkit-center!important;
	}
	.redbold {
		font-weight:600;
		color:#000;
	}
	table.mytable thead th {
		text-align:center;
		line-height:100%;
		font-weight:600;
		white-space:normal;
	}
	table.mytable tbody td img {
		padding:0px !important;
		display:block !important;
		margin:1px !important;
		height:28px !important;
	}
	.dataTableHidden {
		visibility:hidden;
		
	}
	.red {
		color:#b00;
	}
	.green {
		color:#080
	}
	.blue {
		color: #06c;
	}
	table.mytable tbody td {
		font-weight:600;
		xfont-size:12px;
	}
	table.mytable thead th {
		xfont-weight:700;
		xfont-size:12px;
		color:#000000 !important;
	}
.subinfo {
	font-weight:600; 
	color:#005588; 
	display:inline-block; 
	border-left:1px #ccc solid; 
	padding:2px 0 2px 10px;
}


#inORout
{
	text-transform: capitalize;
}



img.cp-lightimg {

  position:fixed;

  transition:all 300ms;

  z-index:1001;

  box-shadow: rgba(0,0,0,0.2)2px 2px 3px;

}

 

div.cp-lightoverlay {

  position:fixed;

  background: rgba(0,0,0,0.7);

  top:0;

  left:0;

  right:0;

  bottom:0;

  z-index:1000;

  opacity:0;

  transition:all 300ms;

}

 

div.cp-lightoverlay.cp-lightoverlay-in {

  opacity:1;

}
	
/*.dataTable > thead > tr > th[class*="sort"]:after{
    content: "" !important;
}*/
</style>		
	
<!-- 	<h2><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['Import timesheet']?> &nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp; <?=$scan_system[$scan_app]?>
		<span style="float:right"><?=$lng['Time period']?> :  <?=date('d-m-Y', strtotime($time_period['start']))?> - <?=date('d-m-Y', strtotime($time_period['end']))?></span>
	</h2> -->	

	<h2><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['Import timesheet']?> <!-- &nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp; <?=$scan_system[$scan_app]?>
		<span style="float:right"><?=$lng['Time period']?> :  <?=date('d-m-Y', strtotime($time_period['start']))?> - <?=date('d-m-Y', strtotime($time_period['end']))?></span> -->
	</h2>
	
	<div class="main">
		<input type="hidden" name="fileTypeVal" id="fileTypeVal" value="">
		<div id="dump"></div>
			<ul class="nav nav-tabs" id="myTab">
						<li class="nav-item"><a class="nav-link" href="#tab_overview" data-toggle="tab"><?=$lng['Overview']?></a></li>
						<li class="nav-item"><a class="nav-link" href="#tab_scandata" data-toggle="tab"><?=$lng['Scan Data']?></a></li>
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				<div class="tab-pane" id="tab_overview">
					<div style="overflow-x:auto">

						<select id="periodFilter" style="float:left">
						<option selected ="selected" value=""><?=$lng['Select period']?></option>
						<option  value=""><?=$lng['Show all']?></option>
						<? for($i=1;$i<=12;$i++){
							echo '<option ';
							echo 'value="'.sprintf('%02d', $i).'/'.$_SESSION['rego']['cur_year'].'">'.$months[$i].' '.$_SESSION['rego']['cur_year'].'</option>';
						} ?>
						</select>

						<select id="validFilter" style="float:left; margin-left:8px">
							<option selected value=""><?=$lng['Show all']?></option>
							<option value="0"><?=$lng['Not validated']?></option>
							<option value="1"><?=$lng['Validated']?></option>
						</select>

						<? if($userType == 'sys') {
							if($_SESSION['rego']['time_import']['edit']) {?>
						<form id="importForm" enctype="multipart/form-data">
							<input style="visibility:hidden; height:0; width:0; position:absolute; left:-5000px" type="file" name="timesheet" id="timesheet" />
							<button id="importBut" type="button" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Import timesheet']?></button>
						</form>
						<? }}?>

						<!-- <button id="exportBut" type="button" class="btn btn-primary btn-fr" onclick="exportExcel();"><i class="fa fa-download"></i>&nbsp; <?=$lng['Export Time Sheet']?></button> -->

						<?php if($_SESSION['rego']['time_import']['edit']) {?>

							 <a target="_blank" href="<? echo ROOT.'time/ajax/export_scandata_excel.php'?>"><button id="exportExcel" type="button" class="btn btn-primary btn-fr">
							<i class="fa fa-file-excel-o"></i>&nbsp;<?=$lng['Export Time Sheet']?> 
							</button></a>

						<?php } ?>


				<!-- 		<? if($scan_app == 'REGOXLS'){ ?>
							<button onClick="window.location='REGOXLS.xlsx'" type="button" class="btn btn-outline-secondary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download empty Excel file']?></button>
						<? } ?> -->

						<div id="showTable" style="display:none; clear:both">
						<table id="datatable" class="dataTable nowrap xcompact tac" width="100%">
							<thead>
							<tr style="line-height:100%">
								<th data-sortable="true"><?=$lng['Date']?></th>
								<th data-sortable="false"><?=$lng['Period']?></th>
								<th data-sortable="true"><?=$lng['Filename']?></th>
								<th data-sortable="false"><?=$lng['Scan system']?></th>
								<th data-sortable="false"><?=$lng['IN/OUT']?></th>
								<th data-sortable="false"><?=$lng['Import']?></th>
								<th data-sortable="false"><?=$lng['Validated']?></th>
								<th data-sortable="false" style="width:1px"><i data-toggle="tooltip" title="View &amp; Validate" class="fa fa-eye fa-lg"></i></th>
								<th data-sortable="false" style="width:90%"></th>
								<th data-sortable="false"><i data-placement="left" data-toggle="tooltip" title="Download file" class="fa fa-download fa-lg"></i></th>
								<th data-sortable="false"><i data-placement="left" data-toggle="tooltip" title="Delete file" class="fa fa-trash fa-lg"></i></th>
							</tr>
							</thead>
							<tbody>
							
							</tbody>
						</table>
						</div>
					</div>
				</div>
				
				<div class="tab-pane" id="tab_scandata">
					<div style="overflow-x:auto">
						<div id="dump"></div>

						<div class="searchFilter btn-fl" style="width:150px">
							<input placeholder="<?=$lng['Filter']?> <?=$lng['Employee']?>" id="searchFilter" class="sFilter" type="text" />
							<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
						</div>
						<div class="dpicker btn-fl">
							<input readonly placeholder="Date from" class="xdate_month" id="sdate" style="width:150px" type="text" value="<?=$date_start?>" />
							<button onclick="$('#sdate').focus()" type="button"><i class="fa fa-calendar"></i></button>
						</div>
						<div class="dpicker btn-fl">
							<input readonly placeholder="Date until" class="xdate_month" id="edate" style="width:150px" type="text" value="<?=$date_end?>" />
							<button onclick="$('#edate').focus()" type="button"><i class="fa fa-calendar"></i></button>
						</div>

<!-- 						 <button id="exportExcelOpt" type="button" class="btn btn-primary btn-fr">
							<i class="fa fa-file-excel-o"></i>&nbsp;<?=$lng['Export Time Sheet']?> 
						</button> -->


						<div id="showTables" style="display:xnone; position:relative; clear:both">
							<table id="datatable2" class="dataTable hoverable selectable nowrap compact tac" width="100%">
								<thead>
								<tr style="line-height:100%">
									<th style="padding-right:30px"><?=$lng['Emp. ID']?></th>
									<th ><?=$lng['Employee']?></th>
									<th style="text-align:center !important; padding-right:30px"><?=$lng['Date scan']?></th>
									<th style="min-width: 80px;"><?=$lng['Time scan ']?><br></th>
									<th><?=$lng['Shiftplan Key']?><br><? //=$lng['Plan']?></th>
									<th><?=$lng['IN/OUT']?> </th>
									<th><?=$lng['Status']?> </th>
									<th><?=$lng['Picture']?> </th>
								</tr>
								</thead>
								<tbody>
								
								</tbody>
							</table>
							<div style="position:absolute;top:0px; right:0; background:#eee; height:37px; width:6px; border-bottom:1px solid #ccc"></div>
						</div>

					</div>
				</div>
				
			</div>
	</div>
	
<style>
	table.basicTable tbody td {
		padding:1px 10px !important;
	}
	table.basicTable tbody td input[type="text"] {
		border-bottom:0 !important;
		padding:5px 10px !important;
		text-align:center;
	}
	table.basicTable tbody td input[type="text"]:hover, 
	table.basicTable tbody td input[type="text"]:focus {
		border-bottom:0 !important;
		background:#ff9;
	}
</style>	
	
	<!-- Modal Details Time -->
	<div class="modal fade" id="modalValidate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<input type="hidden" name="hiddenIdTimeSheet" id="hiddenIdTimeSheet" value="" />
		 <div class="modal-dialog" style="max-width:1000px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-eye"></i>&nbsp; <?=$lng['Timesheet content']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<select class="removeinvalid" id="validDataFilter" style="float:left; margin-left:8px">
							<option selected value="0"><?=$lng['Show all']?></option>
							<option value="1"><?=$lng['VALID']?></option>
							<option value="2"><?=$lng['INVALID']?></option>
							<option value="3"><?=$lng['EXIST']?></option>
						</select>
							<label style="display: none;"><span style="margin-right: 5px;color: green;"><b><?=$lng['VALID']?></b></span><input type="checkbox" class="  dbox checkbox notxt" ><span style="z-index:0"></span></label>
							<label style="display: none;"><span style="margin-right: 5px;color: green;"><b><?=$lng['OVERWRITE']?></b></span><input type="checkbox" class="overwrite dbox checkbox notxt"><span style="z-index:0"></span></label>
							<br>
							<br>
						<table id="timeTable" class="basicTable" width="100%" border="0">
							<thead>
							<tr style="line-height:100%">
								<th><?=$lng['Status']?></th>
								<th style="width:1px"><i id="dApprAll" style="cursor:pointer" data-toggle="tooltip" title="Select all" class="fa fa-thumbs-up fa-lg checkvalc"></i></th>
								<th><?=$lng['Date']?></th>
								<th><?=$lng['Scan ID']?></th>
								<th><?=$lng['Emp. ID']?></th>
								<th style="width:50%"><?=$lng['Name']?></th>
								<th class="tac"><?=$lng['Scan']?> 1</th>
								<th class="tac"><?=$lng['Scan']?> 2</th>
								<th class="tac"><?=$lng['Scan']?> 3</th>
								<th class="tac"><?=$lng['Scan']?> 4</th>
								<th class="red tac"><?=$lng['Scan']?> 5</th>
								<th class="red tac"><?=$lng['Scan']?> 6</th>
								<th class="red tac"><?=$lng['Scan']?> 7</th>
								<th class="red tac"><?=$lng['Scan']?> 8</th>
								<th class="red tac"><?=$lng['Scan']?> 9</th>
							</tr>
							</thead>
							<tbody>

						  </tbody>
						</table>
						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<button id="validateTimesheet" style="float:left" class="btn btn-primary" type="button"><i class="fa fa-thumbs-up"></i>&nbsp; <?=$lng['Validate timesheet']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>

	<!---File type Modal -->

		<div class="modal fade" id="modalFileType" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-file"></i>&nbsp; <?=$lng['Registration File Type']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">

							<select id="regFileType" class=" btn-fl" onchange="selectTimesheet();">
								<option selected="" value=""><?=$lng['Please select']?></option>
								<option value="REGOXLS">REGO <?=$lng['Time']?> <?=$lng['Import']?> (<?=$lng['Excel']?> file)</option>
								<?php if(in_array('FSV2011', $checkboxScan)) { ?>

										<option value="FSV2011">Fingerscan V2011 ZKTECO (Txt file)</option>

								<?php } ?>			

								<?php if(in_array('FSV2012', $checkboxScan)) { ?>

										<option value="FSV2012">Fingerscan V2012 ZKTECO (Txt file)</option>
										
								<?php } ?>
								
								<!-- <option value="AGL"><?=$lng['AGL Scan']?> (<?=$lng['Tab']?> <?=$lng['separated textfile']?>)</option> -->
								<!-- <option value="WELADEE"><?=$lng['Weladee Scan (Excel file)']?></option>	 -->
							</select>

						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal" onclick="resetFileType();"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>		

	<!-- Modal Overwrite --> 
	<div class="modal fade" id="modalOverwrite" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-file"></i>&nbsp; <?=$lng['Confirm Box']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p><?=$lng['Are you sure you want to overwrite the data']?>?</p>

						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>

						<button style="float:right;margin-right: 11px;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="openFileType();"><i class="fa fa-check"></i>&nbsp; <?=$lng['OK']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>


		<!---File type Modal -->

		<div class="modal fade" id="modalExportOpt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-file"></i>&nbsp; <?=$lng['Export Time Sheet']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p><label><input type="checkbox" class="dbox checkbox notxt " ><span style="z-index:0"></span></label>&nbsp;With employee names</p> 
							<p><label><input type="checkbox" class="dbox checkbox notxt " ><span style="z-index:0"></span></label>&nbsp;Selection only</p> 
							<p><label><input type="checkbox" class="dbox checkbox notxt " ><span style="z-index:0"></span></label>&nbsp;With shift plan data as scan data </p> 
						</div>
						<div style="height:10px"></div>
						<button id="exportManualExcel" style="float:right" class="btn btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i>&nbsp; <?=$lng['Export']?></button>
						<button style="float:right;margin-right: 11px;" class="btn btn-primary" type="button" data-dismiss="modal" ><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						 <a target="_blank" href="<? echo ROOT.'time/ajax/export_scandata_excel.php'?>"><input style="display: none;" type="button" name="hiddenExportBtn" id="hiddenExportBtn"></a>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>	


	<input type="hidden" name="hidden1" id="hidden1" value="">
	<input type="hidden" name="hiddenIdArray" id="hiddenIdArray" value="">
	<input type="hidden" name="hiddenOverwriteArray" id="hiddenOverwriteArray" value="">
	
	<script>
		$(function () {

		  $(document).on("click","img",function () {

		    $(this).cpLightimg();

		  });  
		});


		function openFileType()
		{
			$("#modalFileType").modal('toggle');

		}

		var headerCount = 1;
		var scrY = window.innerHeight-240;
		
		$(document).ready(function() {
			//alert('ready')

			var dtable5 = $('#datatable2').DataTable({
				scrollY:        	false,//scrY,//heights-260,
				scrollX:        	false,
				scrollCollapse: 	false,
				//fixedColumns: 	true,
				lengthChange: 		false,
				pageLength: 		17,
				paging: 				true,
				searching:			true,
				ordering:			true,
				filter: 				false,
				info: 				false,
				autoWidth:			true,
				<?=$dtable_lang?>
				processing: 		false,
				serverSide: 		true,
				order:				[[2, 'desc']],
				columnDefs: [
					{ targets: [2], 'class': 'tal' },
					{ targets: [7], 'class': 'alignclass' },
				],
				ajax: {
					url: "ajax/server_get_scan_meta.php",
					type: "POST", 
					data: function(d){
						d.period = $("#periodFilter").val();
						d.valid = $("#validFilter").val();
						d.emp_id = $("#searchFilter").val();
						d.sdate = $("#sdate").val();
						d.edate = $("#edate").val();
						d.cidd = '<?php echo $cidd ?>';
					}
				},
				initComplete : function( settings, json ) {
					$('#showTables').fadeIn(200);
					dtable5.columns.adjust().draw();
				}

			});


			var from = $('#sdate').datepicker({
				format: "D dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				todayHighlight: true,
				//startDate : sdate,
				//endDate   : edate
			}).on('changeDate', function(e){
				//e.stopPropagation();
				//$('#edate').datepicker('setDate', from.val()).datepicker('setStartDate', from.val()).focus();
				$('#edate').focus();
				sdate = from.val();
				$(".tooltip").tooltip("hide");
			});
			
			var until = $('#edate').datepicker({
				format: "D dd-mm-yyyy",
				autoclose: true,
				inline: true,
				language: '<?=$lang?>-en',//lang+'-th',
				todayHighlight: true,
			}).on('changeDate', function(e){
				dtable5.ajax.reload(null, false);
				$('body').tooltip("dispose");
				setTimeout(function(){
					$("body").tooltip({ 
						container: 'body',
						selector: '[data-toggle=tooltip]',
						animated: 'fade',
						placement: 'top',
						html: true
					});
				},50);
				edate = until.val(); 
			});


			$("#searchFilter").keyup(function() {
				dtable5.ajax.reload(null, true);
			});		
			// $("#edate").on('change',function() {
			// 	dtable5.ajax.reload(null, true);
			// });
			var scan_app = <?=json_encode($scan_app)?>;
			var id;
			
			var dtable1 = $('#datatable').DataTable({
				scrollY:        	false,//scrY,//heights-260,
				scrollX:        	false,
				scrollCollapse: 	false,
				//fixedColumns: 	true,
				lengthChange: 		false,
				pageLength: 		17,
				paging: 				true,
				searching:			true,
				ordering:			true,
				filter: 				false,
				info: 				false,
				autoWidth:			true,
				<?=$dtable_lang?>
				processing: 		false,
				serverSide: 		true,
				order:				[[2, 'desc']],
				columnDefs: [
					{ targets: [2], 'class': 'tal' },
				],
				ajax: {
					url: "ajax/server_get_scan.php",
					type: "POST", 
					data: function(d){
						d.period = $("#periodFilter").val();
						d.valid = $("#validFilter").val();
					}
				},
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					dtable1.columns.adjust().draw();
				}
			});
			
			$(document).on("change", "#validFilter", function(e){
				dtable1.ajax.reload(null, false);
			});
			$(document).on("change", "#periodFilter", function(e){
				dtable1.ajax.reload(null, false);
			});
			
			$('#validateTimesheet').on('click', function(){

				$('#validateTimesheet i').removeClass('fa-thumbs-up').addClass('fa-refresh fa-spin');
				$.ajax({
					url: "ajax/validate_scan.php",
					type: "POST", 
					data: {id: id},
					success: function(responce){
						//$('#dump').html(responce); return false;
						//alert(responce)
						if(responce == 'confirm'){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please first confirm planning for this month']?>',
								duration: 4,
							})
							$("#modalValidate").modal('toggle');
							//setTimeout(function(){
								//window.location.href="index.php?mn=5";
							//},4000);
							return false							
						}
						if(responce == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Timesheet validated successfully']?>',
								duration: 3,
							})
							dtable1.ajax.reload(null, false);
							$("#modalValidate").modal('toggle');
							 location.reload(); 


							// insert data into other table


						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+responce,
								duration: 4,
							})
						}
						$('#validateTimesheet i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 8,
							closeConfirm: "true",
						})
					}
				});
			})
			
			$(document).ajaxComplete(function( event,request, settings ) {
				$('.deleteFile').confirmation({
					container: 'body',
					rootSelector: '.deleteFile',
					singleton: true,
					animated: 'fade',
					placement: 'left',
					popout: true,
					html: true,
					title: '<?=$lng['Are you sure']?>',
					btnOkClass: 'btn btn-danger',
					btnOkLabel: '<?=$lng['Delete']?>',
					btnCancelClass: 'btn btn-success',
					btnCancelLabel: '<?=$lng['Cancel']?>',
					onConfirm: function() {
						//alert('confirmation'); return false
						$.ajax({
							url: "ajax/delete_scanfile.php",
							//type: "POST", 
							data: {id: $(this).data('id')},
							success: function(responce){
								//$('#dump').html(data); return false;
								if(responce == 'success'){
									dtable1.ajax.reload(null, false);
									$('#datatable2').DataTable().clear().draw();
									// $('#datatable2').data.reload();


								}else{
									$("body").overhang({
										type: "error",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+responce,
										duration: 4,
									})
								}
							},
							error:function (xhr, ajaxOptions, thrownError){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
									duration: 8,
									closeConfirm: "true",
								})
							}
						});
					}
				})
			});
			
			$('#datatable, #modalTable').on("click", ".viewContent", function(e){
				e.preventDefault();
				id = $(this).data('id');
				$('#hiddenIdTimeSheet').val(id); // pass ID to hidden field 
				//alert(id)
				$.ajax({
					url: "ajax/get_scan_details.php",
					data: {id: id},
					success: function(response){
						//$("#dump").html(response);
						$("#timeTable tbody").html(response);
					},
					error:function (xhr, ajaxOptions, thrownError){
						//$("#dump").html(thrownError);
					}
				});
				$("#modalValidate").modal('toggle');
			});
			$('#modalValidate').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
				$('#hiddenIdTimeSheet').val('');
			});

			function readAttURL(input) {
			  if(input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
						var fileExtension = ['xls', 'xlsx', 'txt'];
						var ext = input.files[0].name.split('.').pop();
						if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Only ... formats are allowed']?>',
								duration: 4,
							})
							//alert("Only  " + fileExtension.join(', ')+ " formats are allowed !");
							//$(id).html('No file selected');
						}else{
							//alert('url')
							$("#importForm").submit();
						}
					}
					reader.readAsDataURL(input.files[0]);
			  }
			}
			$("#timesheet").change(function(){
				readAttURL(this);
			});
		
			$("#importBut").on('click', function(e){
				// CHECK IF SCANSYSTEM !== 0 //////////////////////////////////////////////////////////////////
				if(scan_app == 0){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please setup scan application in']?> <u><a href="../settings/index.php?mn=607"><?=$lng['Time settings']?></a></u>.',
						duration: 8,
						closeConfirm: true
					})
					return false;
				}

				// open modal to select type of file 

				// $('#timesheet').click();
				$("#modalFileType").modal('toggle');
			})
			$("#importForm").on('submit', function(e){
				//var scan_app = 'AGL';
				$("#modalFileType").modal('toggle');

				e.preventDefault();
				
				$('#importBut i').removeClass('fa-download').addClass('fa-repeat fa-spin');
				$('#message').html('<div class="msg_info nomargin"><i class="fa fa-repeat fa-spin"></i>&nbsp; <?=$lng['Please wait']?> . . .</div>').hide().fadeIn(400);
				var data = new FormData(this);
				var fileTypeValue = $('#fileTypeVal').val();

				var hidden1Value = $('#hidden1').val();
				// alert(hidden1Value);
				data.append('scan_system', fileTypeValue);
				data.append('hidden1Value', hidden1Value);

				$.ajax({
					url: "ajax/import_scan_" + fileTypeValue + ".php",
					type: "POST", 
					data: data,
					cache: false,
					processData:false,
					contentType: false,
					success: function(responce){
						//$('#dump').html(responce); return false;
						setTimeout(function(){
							//$("#message").fadeOut(200);
							$('#importBut i').removeClass('fa-repeat fa-spin').addClass('fa-download');
							dtable1.ajax.reload(null, false);
						},500);
						if(responce == 'wrong'){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong file (not excel file)']?>',
								duration: 4,
							})
						}else if(responce == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Timesheet imported successfully']?>',
								duration: 2,
							})
						}else if($.trim(responce) == 'duplicate'){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+'Data already available',
								duration: 4,
							})
							$("#modalOverwrite").modal('toggle');
							$('#hidden1').val('1');



						}else if($.trim(responce) == 'newInsert'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Timesheet imported successfully']?>',
								duration: 2,
							})
							$('#hidden1').val('');

						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+responce,
								duration: 4,
							})
						}
						$('#importForm').trigger('reset');
						$('#timesheet').val('');
						$('#fileTypeVal').val('');
						$('#regFileType').val('');

					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 8,
							closeConfirm: "true",
						})
					}
				});
			});
			
			/*$(window).on('resize', function(){
				scrY = window.innerHeight-250;
				//$('div.dataTables_scrollBody').height(scrY);
			});*/	

		});

	
		$(document).ready(function() {
			
			var activeTabLeave = localStorage.getItem('activeTabLeave');
			if(activeTabLeave){
				$('.nav-link[href="' + activeTabLeave + '"]').tab('show');
			}else{
				$('.nav-link[href="#tab_overview"]').tab('show');
			}
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('activeTabLeave', $(e.target).attr('href'));

				  $($.fn.dataTable.tables(true)).DataTable().columns.adjust();

			});

			







		});

		function selectTimesheet()
		{
			var fileType = $('#regFileType').val();
			$('#fileTypeVal').val(fileType);

			if(fileType != '')
			{
				$('#timesheet').click();
			}
		}

		function resetFileType()
		{
			var fileType = $('#regFileType').val('');
		}


		// CHECK VALID CHECKBOX 

		$('.checkval').change(function () {
			if ($('.checkval:checked').length == $('.checkval').length)
			{
		  		$('.valid').prop('checked',true);
		  		var importTimesheet = '<i class="fa fa-thumbs-up">&nbsp</i><?=$lng['Import timesheet']?>';
		  		$("#validateTimesheet").html(importTimesheet);

		  		var selected = [];

			    $("table#timeTable tbody tr input[type=checkbox]").each(function() {
			      if ($(this).is(":checked")) {

			        selected.push($(this).attr('id'));
			      }
			    });

			    $('#hiddenIdArray').val(selected);
			    var hiddenIdArray = $('#hiddenIdArray').val();
			    $.ajax({
					url: "ajax/update_valid_checkbox.php",
					data: {hidden_scaniD: hiddenIdArray},
					type: 'POST',
					success: function(response){
					
					},
				});

		 	}
			else 
			{
				$('.valid').prop('checked',false);
			  	var validateTimesheet = '<i class="fa fa-thumbs-up"></i>&nbsp<?=$lng['Validate timesheet']?>';
			  	$("#validateTimesheet").html(validateTimesheet);

			  	var hiddenIdArray = $('#hiddenIdArray').val();
			    $.ajax({
					url: "ajax/update_uvalid_checkbox.php",
					data: {hidden_scaniD: hiddenIdArray},
					type: 'POST',
					success: function(response){
					
					},
				});


			}
		});	



			$('.checkvalc').click(function () {

					// alert('2');

			  		$('.valid').prop('checked',true);
			  		var importTimesheet = '<i class="fa fa-thumbs-up">&nbsp</i><?=$lng['Import timesheet']?>';
			  		$("#validateTimesheet").html(importTimesheet);

			  		var selected = [];

				    $("table#timeTable tbody tr input[type=checkbox]").each(function() {
				      if ($(this).is(":checked")) {

				        selected.push($(this).attr('id'));
				      }
				    });

				    $('#hiddenIdArray').val(selected);
				    var hiddenIdArray = $('#hiddenIdArray').val();
				    $.ajax({
						url: "ajax/update_valid_checkbox.php",
						data: {hidden_scaniD: hiddenIdArray},
						type: 'POST',
						success: function(response){
						
						},
					});

					$('#dApprAll').addClass('removeCheckVal');
					$('#dApprAll').addClass('test1234');
					$('#dApprAll').removeClass('checkvalc');
				});			

			$('.removeCheckVal').click(function () {

				// alert('1');
			  	$('.valid').prop('checked',false);
			  	var validateTimesheet = '<i class="fa fa-thumbs-up"></i>&nbsp<?=$lng['Validate timesheet']?>';
			  	$("#validateTimesheet").html(validateTimesheet);

			  	var hiddenIdArray = $('#hiddenIdArray').val();
			    $.ajax({
					url: "ajax/update_uvalid_checkbox.php",
					data: {hidden_scaniD: hiddenIdArray},
					type: 'POST',
					success: function(response){
					
					},
				});

					$('#dApprAll').addClass('checkvalc');
					$('#dApprAll').removeClass('removeCheckVal');

				});	


		function allCheckbox(that,id)
		{
			if($('.both_' + id).is(":checked"))
			{
				var importTimesheet = '<i class="fa fa-thumbs-up">&nbsp</i><?=$lng['Import timesheet']?>';
		  		$("#validateTimesheet").html(importTimesheet);

		  			var hidden_scaniD = $('#hidden_scaniD_'+id).val();
		  		  $.ajax({
					url: "ajax/update_checkbox.php",
					data: {hidden_scaniD: hidden_scaniD},
					type: 'POST',
					success: function(response){
					
					},
				});

			}
			else
			{
				var hidden_scaniD = $('#hidden_scaniD_'+id).val();
		  		  $.ajax({
					url: "ajax/update_ucheckbox.php",
					data: {hidden_scaniD: hidden_scaniD},
					type: 'POST',

					success: function(response){
					
					},
				
				});
			}
			// else
			// {
			// 	var validateTimesheet = '<i class="fa fa-thumbs-up"></i>&nbsp<?=$lng['Validate timesheet']?>';
			//   	$("#validateTimesheet").html(validateTimesheet);
			// }

			var checkboxValue = $('input.totalVal').not(':checked').length;

			var checkboxValue1 = $('input.totalVal:checked').length;
			if(checkboxValue1 == '')
			{
				var validateTimesheet = '<i class="fa fa-thumbs-up"></i>&nbsp<?=$lng['Validate timesheet']?>';
			  	$("#validateTimesheet").html(validateTimesheet);
			}

		}



			// OVERWRITE CEHCKBOC
			$('.overwrite').change(function () {
			if ($('.overwrite:checked').length == $('.overwrite').length)
			{
		  		$('.exist').prop('checked',true);
		  		$('.existSpan').html('<b>OVERWRITE</b>');
		  		$('.existSpan').css('color','green');
		  		var importTimesheet = '<i class="fa fa-thumbs-up">&nbsp</i><?=$lng['Import timesheet']?>';
		  		$("#validateTimesheet").html(importTimesheet);

		  		var selected = [];

			    $("table#timeTable tbody tr input[type=checkbox]").each(function() {
			      if ($(this).is(":checked")) {

			        selected.push($(this).attr('id'));
			      }
			    });

			    $('#hiddenOverwriteArray').val(selected);
			    var hiddenIdArray = $('#hiddenOverwriteArray').val();
			    $.ajax({
					url: "ajax/update_valid_checkbox.php",
					data: {hidden_scaniD: hiddenIdArray},
					type: 'POST',
					success: function(response){
					
					},
				});
		 	}
			else 
			{
				$('.exist').prop('checked',false);
				$('.existSpan').html('EXIST');
		  		$('.existSpan').css('color','red');
			  	var validateTimesheet = '<i class="fa fa-thumbs-up"></i>&nbsp<?=$lng['Validate timesheet']?>';
			  	$("#validateTimesheet").html(validateTimesheet);

			  	var hiddenIdArray = $('#hiddenOverwriteArray').val();
			    $.ajax({
					url: "ajax/update_uvalid_checkbox.php",
					data: {hidden_scaniD: hiddenIdArray},
					type: 'POST',
					success: function(response){
					
					},
				});


			}
		});		


		$("#exportExcelOpt").on('click', function(e){

			$("#modalExportOpt").modal('toggle');
		})		

		$("#exportManualExcel").on('click', function(e){

			$("#hiddenExportBtn").click();
		})
		

		// remove valid tr on checking checkbox valid 

		$('.removeinvalid').change(function () {

			var removeInV = $('.removeinvalid').val();



			if(removeInV == '1')
			{
				var id = $('#hiddenIdTimeSheet').val();

				$.ajax({
					url: "ajax/get_scan_details.php",
					data: {id: id},
					success: function(response){
						//$("#dump").html(response);
						$("#timeTable tbody").html(response);
							$("table#timeTable tbody tr ").each(function() {

					    	 var value = $(this).find(".InvalidSpan").html();
					    	 var valueexists = $(this).find(".existSpan").html();
					    	 var valuevalid = $(this).find(".valid_span").html();


					    	 if(value == 'INVALID')
					    	 {
					    	 	$("span.InvalidSpan").parents('tr').addClass('invalidTR');
					    	 }

					    	 if(valueexists == 'EXIST')
					    	 {
					    	 	$("span.existSpan").parents('tr').addClass('invalidTRa');
					    	 }
					    	 // alert(value);
					    	 $('.invalidTR').remove();
					    	 $('.invalidTRa').remove();

					    });

					},
				})


			}
			else if(removeInV == '2')
			{
					var id = $('#hiddenIdTimeSheet').val();

				$.ajax({
					url: "ajax/get_scan_details.php",
					data: {id: id},
					success: function(response){
						//$("#dump").html(response);
						$("#timeTable tbody").html(response);

							$("table#timeTable tbody tr ").each(function() {

					    	 var value = $(this).find(".InvalidSpan").html();
					    	 var valueexists = $(this).find(".existSpan").html();
					    	 var valuevalid = $(this).find(".valid_span").text();


					    	 if(valuevalid == 'VALID')
					    	 {
					    	 	$("span.valid_span").parents('tr').addClass('invalidTR');
					    	 }			    	 

					    	 if(valueexists == 'EXIST')
					    	 {
					    	 	$("span.existSpan").parents('tr').addClass('invalidTRa');
					    	 }
					    	 // alert(value);
					    	 $('.invalidTR').remove();
					    	 $('.invalidTRa').remove();

					    });

					},
				})


			}			

			else if(removeInV == '3')
			{
				var id = $('#hiddenIdTimeSheet').val();

				$.ajax({
					url: "ajax/get_scan_details.php",
					data: {id: id},
					success: function(response){
						//$("#dump").html(response);
						$("#timeTable tbody").html(response);

						$("table#timeTable tbody tr ").each(function() {

					    	 var value = $(this).find(".InvalidSpan").html();
					    	 var valueexists = $(this).find(".existSpan").html();
					    	 var valuevalid = $(this).find(".valid_span").text();

					    	 if(valuevalid == 'VALID')
					    	 {
					    	 	$("span.valid_span").parents('tr').addClass('invalidTR');
					    	 }			    	 

					    	 if(value == 'INVALID')
					    	 {
					    	 	$("span.InvalidSpan").parents('tr').addClass('invalidTRa');
					    	 }
					    	 // alert(value);
					    	 $('.invalidTR').remove();
					    	 $('.invalidTRa').remove();

					    });

			    
					},
				})

			}

			else
			{
				// re run ajax 

				var id = $('#hiddenIdTimeSheet').val();

				$.ajax({
					url: "ajax/get_scan_details.php",
					data: {id: id},
					success: function(response){
						//$("#dump").html(response);
						$("#timeTable tbody").html(response);
					},
				})


			}

		    
		})
	
			
	</script>



	