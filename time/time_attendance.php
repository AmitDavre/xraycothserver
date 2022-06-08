<?


	
	$date_start = date('D d-m-Y');
	$date_end = date('D d-m-Y');
	$emp_array = array();//getJsonUserEmployees($cid, $lang, $user_departments, $_SESSION['rego']['access']['emp_group']);
	
	$teams = array();
	$tmp = getShifTeams();
	if($_SESSION['rego']['teams'] == 'all'){
		$teams = $tmp;
	}else{
		if(!empty($_SESSION['rego']['teams'])){
			$team = explode(',', $_SESSION['rego']['teams']);
			foreach($team as $k=>$v){
				$teams[$v] = $tmp[$v];
			}
		}
	}

	$hrs_range = hoursRange( 0, 86400, 60 * 30, 'h:i a' );
	
	$leave_types = array();
	
	foreach($leave_types as $k=>$v){
		if($v['min_request'] == 'hrs'){$leave_type_hrs[$k] = $v;}
		if($v['min_request'] == 'half'){$leave_type_half[$k] = $v;}
	}
	$compensations = getCompensations();
	$comments = array();
	$tmp = unserialize($time_settings['comments']);
	foreach($tmp as $k=>$v){
		$comments[$k] = $v[$lang];
	}


	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();

?>
<style>
	.redbold {
		xfont-weight:600;
		color:#b00;
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
	.leaveType:hover {
		font-weight:600;
	}
	
	table.basicTable table.detailTable {
		width:100%;
	}
	table.basicTable table.detailTable td {
		padding:0;
	}
	table.basicTable table.detailTable td input[type="text"] {
		padding:4px !important;
		line-height:normal !important;
		background:#fff !important;
		border-bottom:0 !important;
	}
	table.basicTable table.detailTable td select {
		padding:3px !important;
		border-bottom:0 !important;
	}
	
	.xsubinfo {
		font-weight:600; 
		color:#005588; 
		display:inline-block; 
		padding:0px 2px 0px 10px;
		float:left;
		text-align:right;
		line-height:200%;
	}
	table.basicTable table.detailTable {
		xwidth:100%;
	}
	table.basicTable table.detailTable td {
		padding:0;
		border:0;
		border-left:1px solid #eee;
		text-align:left;
	}
	table.basicTable table.detailTable tr {
		border:0;
	}
	table.basicTable table.detailTable td input[type="text"] {
		padding:4px 10px !important;
		line-height:normal !important;
		background:#fff !important;
		border-bottom:0 !important;
	}
	table.basicTable table.detailTable td select {
		padding:3px !important;
		border-bottom:0 !important;
		xbackground:green;
	}
	table.dataTable td input[type="text"] {
		padding:4px 10px !important;
		border:0 !important;
		xbackground:green !important;
		xwidth:auto !important;
	}
	table.dataTable tbody td {
		padding:0px 10px !important;
	}
	table.dataTable tbody td.nopad {
		padding:0 !important;
	}

	table.basicTable tbody td input[type="text"] {
		background:transparent !important;
	}
	table.basicTable td input[type="text"]:hover {
		background: #ff9 !important;
	}
	table.basicTable td input[type="text"].dragover {
		background: #ff9 !important;
	}
	table.basicTable tbody.aScans td span:hover {
		font-weight:600 !important;
		background: #fff6f6 !important;
		color:#b00;
	}
	table.basicTable tbody.pScans td span:hover {
		font-weight:600 !important;
		background: #f6f6ff !important;
		color:#b00;
	}
	table.basicTable tbody td span {
		padding:2px 8px !important;
	}
	
	table.dataTable tbody td {
		padding:4px 8px !important;
		vertical-align:middle;
	}
</style>		
	
	<h2>
		<!-- <i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['Time attendance']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$lng['From']?> <?=date('d-m-Y', strtotime($time_period['start']))?> <?=$lng['Until']?> <?=date('d-m-Y', strtotime($time_period['end']))?> -->
		<i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['Time attendance']?>&nbsp;&nbsp;
	</h2>
	<div class="main">
		<div id="dump"></div>
		<?php if($_SESSION['rego']['time_attendance']['edit']) {?>
		<div class="searchFilter btn-fl" style="width:150px">
			<input placeholder="<?=$lng['Filter']?> <?=$lng['Employee']?>" id="searchFilter" class="sFilter" type="text" />
			<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
		</div>
		<div class="dpicker btn-fl">
			<input readonly placeholder="Date from" class="xdate_month" id="sdate" style="width:150px" type="text" value="" />
			<!-- <input readonly placeholder="Date from" class="xdate_month" id="sdate" style="width:150px" type="text" value="<?=$date_start?>" /> -->
			<button onclick="$('#sdate').focus()" type="button"><i class="fa fa-calendar"></i></button>
		</div>
		<div class="dpicker btn-fl">
			<input readonly placeholder="Date until" class="xdate_month" id="edate" style="width:150px" type="text" value="" />
			<!-- <input readonly placeholder="Date until" class="xdate_month" id="edate" style="width:150px" type="text" value="<?=$date_end?>" /> -->
			<button onclick="$('#edate').focus()" type="button"><i class="fa fa-calendar"></i></button>
		</div>
		<?php } ?>
		
		<!--<button id="datesBtn" class="btn btn-primary btn-fl" type="button"><i class="fa fa-search fa-lg"></i></button>-->
		
		<select id="apprFilter" class=" btn-fl">
			<option value="nappr"><?=$lng['All not Approved']?></option>
			<option value="appr"><?=$lng['All Approved']?></option>
			<option value="all"><?=$lng['Show all']?></option>
		</select>
		
		<select id="typeFilter" class="btn-fl">
			<option selected value=""><?=$lng['Show all']?></option>
			<option value="scan"><?=$lng['All Incomplete Scans']?></option>
			<option value="ctime"><?=$lng['All conform plan']?></option>
			<option value="itime"><?=$lng['All non-conform plan']?></option>
			<option value="ot"><?=$lng['All with Overtime']?></option>
			<option value="leave"><?=$lng['All with Leave']?></option>
			<option value="late"><?=$lng['All Late or Early']?></option>
			<option value="plan">All without plan<? //=$lng['All Late or Early']?></option>
			<!--<option value="off"><? //=$lng['Only OFF days']?></option>
			<option value="wday"><? //=$lng['Only working days']?></option>-->
		</select>
		
<!-- 		<select id="teamFilter" class="btn-fl">
			<option selected value="all"><?=$lng['All shiftteams']?></option>
			<? foreach($teams as $k=>$v){ ?>
					<option value="<?=$k?>"><?=$v?></option>
			<? } ?>
		</select> -->
		<?php if($_SESSION['rego']['time_attendance']['approve']) {?>
		<button id="dApprSelected" type="button" class="btn btn-primary btn-fl"><i class="fa fa-thumbs-up"></i>&nbsp; <?=$lng['Approve selected']?></button>
		<?php } ?>
		<?php if($_SESSION['rego']['time_attendance']['edit']) {?>
		<button id="exportExcel" type="button" class="btn btn-primary btn-fr"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Export Excel']?></button>
		<button id="calcBut" type="button" class="btn btn-primary btn-fr"><i class="fa fa-calculator"></i>&nbsp; <?=$lng['Calculate']?></button>
		<?php }?>

		<div id="showTable" style="display:xnone; position:relative; clear:both">
			<table id="datatable" class="dataTable hoverable selectable nowrap compact tac" width="100%">
				<thead>
				<tr style="line-height:100%">
					<th style="padding-right:30px"><?=$lng['Emp. ID']?></th>
					<th><?=$lng['Employee']?></th>
					<th style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-edit fa-lg"></i></th>
					<th style="width:1px"><i id="dApprAll" style="cursor:pointer" data-toggle="tooltip" title="<? //=$lng['Approve']?>Select all" class="fa fa-thumbs-up fa-lg"></i></th>
					<th style="text-align:center !important; padding-right:30px"><?=$lng['Date']?></th>
					<th><?=$lng['Shiftplan']?><br><? //=$lng['Plan']?></th>
					<th><?=$lng['Plan']?><br><?=$lng['Hrs']?></th>
					<th><?=$lng['OT']?><br><?=$lng['Hrs']?></th>
					<th data-visible="false"><?=$lng['OT']?><br><?=$lng['Hrs']?></th>
					<th style="cursor:default" data-toggle="tooltip" title="All scans"><img style="height:22px; display:block" src="../images/fingerprint_red.png" /></th>
					<th><?=$lng['Scan']?> IN</th>
					<th><?=$lng['Scan']?> OUT</th>
					<th><?=$lng['Scan']?> BIN</th>
					<th><?=$lng['Scan']?> BOUT</th>
					<th style="min-width:35px"><?=$lng['Late']?></th>
					<th style="min-width:35px"><?=$lng['Early']?></th>
					<th style="text-align:center !important"><?=$lng['Actual']?><br><?=$lng['Hrs']?></th>
					<th style="text-align:center !important">Normal<? //=$lng['Paid']?><br><?=$lng['Hrs']?></th>
					<th style="min-width:35px"><?=$lng['OT']?><br>1</th>
					<th style="min-width:35px"><?=$lng['OT']?><br>1.5</th>
					<th style="min-width:35px"><?=$lng['OT']?><br>2</th>
					<th style="min-width:35px"><?=$lng['OT']?><br>3</th>
					<th><?=$lng['Leave']?><br><?=$lng['Type']?></th>
					<th><?=$lng['Leave']?><br><?=$lng['Paid']?></th>
					<th><?=$lng['Leave']?><br><?=$lng['unPaid']?></th>
					<? foreach($compensations as $k=>$v){ ?>
					<th data-sortable="false" style="cursor:default; padding:0 8px" data-toggle="tooltip" title="<?=$v['description']?>"><?=$v['code']?></th>
					<? } ?>
					<th data-visible="false" data-sortable="false" style="padding:4px 10px"><?=$lng['Comment']?></th>
				</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
			<div style="position:absolute;top:0px; right:0; background:#eee; height:37px; width:6px; border-bottom:1px solid #ccc"></div>
		</div>
		
	</div>
	<!--<div style="position:absolute;top:221px; left:0; right:0; background:rgba(255,0,0,0.5); height:672px;"></div>-->
	
<style>
	table.basicTable tbody td input[type="text"] {
		border-bottom:0 !important;
		padding:5px 8px !important;
		text-align:center;
	}
	table.basicTable tbody td input[type="text"]:hover, 
	table.basicTable tbody td input[type="text"]:focus {
		border-bottom:0 !important;
		background:#ff9;
	}
	.basicTable td img {
		display:block;
		width:100%;
	}
	.basicTable td.locName {
		padding:4px 5px;
		white-space:normal;
		text-align:center;
		line-height:100%;
		font-size:12px;
	}
	.checkgreen {
		background:#cfc;
	}
	.checkred {
		background:#fee;
	}
	.nonred {
		background:#fee;
	}
	table.editTable tbody td {
		min-width:65px
	}

	th#eScan3Head {
	    display: table-cell!important;
	}
	td#eScan4Both {
	    display: table-cell!important;
	}
	
</style>	
	
	<!-- Modal Details Time -->
	<div class="modal fade" id="modalEditTime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="min-width:950px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit"></i>&nbsp; <?=$lng['Edit']?> <span id="eName"></span> </h4> <h4><span style="float: right; font-style: italic; color: rgb(187, 0, 0);margin-right: 100px;" id="noplanmessage"></span> </h4>

					</div>
					<div class="modal-body" style="padding:20px 25px 15px">
						<span style="font-weight:600; color:#cc0000;" id="timeMsg"></span>
						<form id="editForm">
						<input id="aID" scope="part1" name="id" type="hidden" />
						<input id="aDate" name="date" type="hidden" />
						
						<table class="basicTable" width="100%" style="table-layout:fixed">
							<thead>
								<tr style="line-height:100%">
									<th colspan="9"><?=$lng['All scans']?> - <?=$lng['Drag & Drop to employee hours']?></th>
								</tr>
							</thead>
							<tbody class="aScans">
								<tr style="background:#fff">
									<td style="padding:1px"><img id="img1"></td>
									<td style="padding:1px"><img id="img2"></td>
									<td style="padding:1px"><img id="img3"></td>
									<td style="padding:1px"><img id="img4"></td>
									<td style="padding:1px"><img id="img5"></td>
									<td style="padding:1px"><img id="img6"></td>
									<td style="padding:1px"><img id="img7"></td>
									<td style="padding:1px"><img id="img8"></td>
									<td style="padding:1px"><img id="img9"></td>
								</tr>
								<tr style="background:#fff">
									<td class="locName" id="loc1"></td>
									<td class="locName" id="loc2"></td>
									<td class="locName" id="loc3"></td>
									<td class="locName" id="loc4"></td>
									<td class="locName" id="loc5"></td>
									<td class="locName" id="loc6"></td>
									<td class="locName" id="loc7"></td>
									<td class="locName" id="loc8"></td>
									<td class="locName" id="loc9"></td>
								</tr>
								<tr style="background:#fff6f6" class="draggable">
									<td class="tac"><span id="aScan1"></span></td>
									<td class="tac"><span id="aScan2"></span></td>
									<td class="tac"><span id="aScan3"></span></td>
									<td class="tac"><span id="aScan4"></span></td>
									<td class="tac"><span id="aScan5"></span></td>
									<td class="tac"><span id="aScan6"></span></td>
									<td class="tac"><span id="aScan7"></span></td>
									<td class="tac"><span id="aScan8"></span></td>
									<td class="tac"><span id="aScan9"></span></td>
								</tr>
						  </tbody>
						</table>
						<table class="basicTable inputs" style="table-layout:fixed; width:100%">
							<thead>
								<tr style="line-height:100%">
									<th class="tac"><?=$lng['Scan']?> IN </th>
									<!-- <th id="eScan2Head" class="tac"><?=$lng['Scan']?> OUT</th> -->
									<!-- <th id="eScan3Head" class="tac"><?=$lng['Scan']?> IN</th> -->
									<th id="eScan4Head" class="tac"><?=$lng['Scan']?> OUT</th>
									<th colspan="5"></th>
								</tr>
							</thead>
							<tbody>
								<tr style="background:#f6fff6">
									<td class="tac"><input name="scan1" id="eScan1" scope="part1" class="sel hourFormat p_lang ui-droppable" type="text"></td>
									<!-- <td class="tac"><input name="scan2" id="eScan2" scope="part1" class="sel hourFormat p_lang ui-droppable" type="text"></td> -->
									<!-- <td id="eScan3Both" class="tac"><input name="scan3" id="eScan3" scope="part1" class="sel hourFormat p_lang ui-droppable" type="text"></td> -->
									<td id="eScan4Both" class="tac"><input name="scan4" id="eScan4" scope="part1" class="sel hourFormat p_lang ui-droppable" type="text"></td>
									<td class="tal" colspan="5" style="padding:0 10px !important"><?=$lng['Employee hours']?></td>
								</tr>
						  </tbody>
						</table>
						
						<table class="basicTable" width="100%" style="table-layout:fixed">
							<tbody class="pScans">
								<tr style="background:#f6f6ff" class="draggable">
									<td class="tac"><span id="pScan1"></span></td>
									<td id="pcan4td" class="tac"><span id="pScan4"></span></td>
									<td class="tal" colspan="5"><?=$lng['Shiftplan hours']?> - <?=$lng['Drag & Drop to employee hours']?></td>
								</tr>
						  </tbody>
						</table>
						
						<table class="basicTable inputs editTable" width="100%" style="margin-top:10px">
							<thead class="tac">
								<tr style="line-height:100%">
									<th><?=$lng['Plan Total']?></th>
									<th><?=$lng['Plan Normal']?></th>
									<th><?=$lng['Plan OT']?></th>
									<th><?=$lng['Actual']?> <?=$lng['Hrs']?></th>
									<th><?=$lng['Break']?></th>
									<th colspan="6" class="tal" style="width:80%"><?=$lng['Remarks']?></th>
									<th><i style="color:#b00" class="fa fa-flag"></i></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="tac" id="pTothrs"></td>
									<td class="tac" id="pPlanhrs"></td>
									<td class="tac" id="pOThrs"></td>
									<td class="tac" id="pActualhrs"></td>
									<td class="tac" id="pBreak"></td>
									<td colspan="6">
										<input id="eRemarks" style="background:#fff !important" class="tal" placeholder="Remarks" name="remarks" type="text" value="" />
									</td>
									<td id="eCheck" class="tac"></td>
								</tr>
						  </tbody>
							<thead class="tac">
								<tr style="line-height:100%">
									<th><?=$lng['Total']?> <?=$lng['Hrs']?></th>
									<th><?=$lng['Normal']?> <?=$lng['Hrs']?></th>
									<th><?=$lng['OT']?> <?=$lng['Hrs']?></th>
									<th><?=$lng['Leave']?> <?=$lng['Hrs']?></th>
									<th style="color:#b00; cursor:default" data-toggle="tooltip" title="unPaid Late"><?=$lng['Late']?></th>
									<th style="color:#b00; cursor:default" data-toggle="tooltip" title="unPaid Early"><?=$lng['Early']?></th>
									<th><?=$lng['OT']?> 1</th>
									<th><?=$lng['OT']?> 1.5</th>
									<th><?=$lng['OT']?> 2</th>
									<th><?=$lng['OT']?> 3</th>
									<th style="color:#080; cursor:default" data-toggle="tooltip" title="Paid Late"><?=$lng['Late']?></th>
									<th style="color:#080; cursor:default" data-toggle="tooltip" title="Paid Early"><?=$lng['Early']?></th>
									<!--<th><i style="color:#b00" class="fa fa-flag"></i></th>-->
								</tr>
							</thead>
							<tbody>
								<tr id="checkClass">
									<td class="tac" id="eTothrs"></td>
									<td><input id="eNormal" name="normal_hrs" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td class="tac" id="eOThrs"></td>
									<td class="tac" id="eLeave"></td>
									<td><input id="uLate" name="unpaid_late" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="uEarly" name="unpaid_early" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="eOt1" name="ot1" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="eOt15" name="ot15" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="eOt2" name="ot2" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="eOt3" name="ot3" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="pLate" name="paid_late" class="eApprove sel hourFormat" type="text" value="" /></td>
									<td><input id="pEarly" name="paid_early" class="eApprove sel hourFormat" type="text" value="" /></td>
									<!--<td style="min-width:50px" id="eCheck" class="tac"></td>-->
								</tr>
								<tr>
									<td colspan="4" style="padding-right:5px !important">
										<select id="eComment" name="comment" style="width:100%">
											<option selected value="0"><?=$lng['Select comment']?></option>
											<? foreach($comments as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
									<td style="padding:4px 12px !important; color:#a00" colspan="9"><?=$lng['Select comment to approve incorrect data']?></td>
								</tr>
						  </tbody>
						</table>
						
						<div style="height:10px"></div>
						<button id="calcBut" type="button" class="btn btn-primary btn-fr"><i class="fa fa-calculator"></i>&nbsp; <?=$lng['Calculate']?></button>
						<button disabled id="eApprove" class="btn btn-primary btn-fl" type="submit"><i class="fa fa-thumbs-up"></i>&nbsp; <?=$lng['Approve']?></button>
						<button class="btn btn-primary btn-fl" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
						
							</form>
						
						<div class="clear"></div>
					</div>
					<div style="position:absolute; top:15px; right:15px"><img id="timeImage" style="width:100px; border-radius:50%; box-shadow:0 0 10px rgba(0,0,0,0.3)"></div>
			  </div>
		 </div>
	</div>
	
<script>

	//var headerCount = 1;
	var scrY = window.innerHeight-240;

	// DRAG HOURS ///////////////
	$.fn.insertAtCaret = function (myValue) {
	  return this.each(function(){
		  if (document.selection) {
			 this.focus();
			 sel = document.selection.createRange();
			 sel.text = myValue;
			 this.focus();
		  }
		  else if (this.selectionStart || this.selectionStart == '0') {
			 var startPos = this.selectionStart;
			 var endPos = this.selectionEnd;
			 var scrollTop = this.scrollTop;
			 this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
			 this.focus();
			 this.selectionStart = startPos + myValue.length;
			 this.selectionEnd = startPos + myValue.length;
			 this.scrollTop = scrollTop;
		  } else {
			 this.value += myValue;
			 this.focus();
		  }
	  });
	}
	
	$(document).ready(function() {
		
		function validateHours(hrs){
			if(hrs.length == 0){return true;}
			if(hrs.length < 5){return false;}
    	var parts = hrs.split(':');
    	if (parts[0] > 23 || parts[1] > 59 || parts[2] > 59){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Wrong hour format<? //=$lng['Data updated successfully']?>',
					duration: 2,
				})
				return false;
			}
    	return true;		
		}


		function checktimevalid ()
		{
			var eScan1Value  = $('#eScan1').val();
			var eScan4Value  = $('#eScan4').val();

	

			var time1 = eScan1Value;
			var time2 = eScan4Value;

			const getTime = time => new Date(2019, 9, 2, time.substring(0, 2), time.substring(3, 5), 0, 0);

			var result = getTime(time1) < getTime(time2);
			// console.log('This should be true:', result);
			
			return result; 
		}

		function reCalculateHours(){


			// if scan is grater than scan our give alert and return false 


			var test = checktimevalid();

			console.log(test);




			if(test == false)
			{
			return false;
			}


			
			

			var data = $('[scope="part1"]').serialize();
			$.ajax({
				url: "ajax/update_scanhours.php",
				data: data,
				success: function(response){
					//$("#dump").html(response);
					if(response != 'success'){
						$('#timeMsg').html(response)
					}else{
						dtable.ajax.reload(null, false);
						var sdate = $("#aDate").val()
						var sID = $("#aID").val()
						$.ajax({
							url: "ajax/calculate_hours_attendance.php",
							data: {sdate: sdate, edate: sdate, id: sID},
							success: function(data){
								//dtable.ajax.reload(null, false);
								getTimeDetails(sID);
								//$('#dump').html(data); return false;
								$("body").overhang({
									type: "success",
									message: '<i class="fa fa-check"></i>&nbsp;&nbsp;re<?=$lng['Calculated successfuly']?>',
									duration: 2,
								})
								setTimeout(function(){
									//$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');
									dtable.ajax.reload(null, false);
								},500);
							},
							error:function (xhr, ajaxOptions, thrownError){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
									duration: 4,
								})
								setTimeout(function(){$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');},500);
							}
						});
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		}
		
		//$('#modalEditTime').modal('toggle');
		
		$(document).on("keyup", '.eApprove', function(e){
			e.preventDefault();
			var val = $(this).val();
			if(val.length > 0 && val.length < 5){return false;}
			if(!validateHours($(this).val())){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Wrong hour format']?>',
					duration: 2,
				})
				return false;
			}
			var data = $('#editForm').serialize();
			$.ajax({
				url: "ajax/update_time_checkline.php",
				data: data,
				success: function(response){
					//$("#dump").html(response); //return false;
					var sID = $("#aID").val()
					getTimeDetails(sID);
					if(response != 'success'){
						$('#timeMsg').html(response)
					}else{
						//dtable.ajax.reload(null, false);
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						setTimeout(function(){
							//$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');
							dtable.ajax.reload(null, false);
						},500);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$(document).on("keyup", '[scope="part1"]', function(e){
			if(validateHours($(this).val())){reCalculateHours();}
		});
		
		$(document).on("change", "#eComment", function(e){
			if(this.value != 0){
				$('#eApprove').prop('disabled', false);
			}else{
				$('#eApprove').prop('disabled', true);
			}
		});

		// DATATABLE AND FILTERS /////////////////////////////////////////////////////////////////
		var user = <?=json_encode($_SESSION['rego']['type'])?>;
		var dtable = $('#datatable').DataTable({
			scrollY:        scrY,//heights-260,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns: 	true,//{leftColumns: 2},
			lengthChange: 	false,
			pageLength: 	17,
			paging: 			false,
			searching:		true,
			ordering:		false,
			filter: 			false,
			info: 			false,
			autoWidth:		false,
			buttons: ['excel'],
			<?=$dtable_lang?>
			/*language: {"decimal":"", "emptyTable":"No data available in table","info":"Showing _START_ to _END_ of _TOTAL_ entries","infoEmpty":"Showing 0 to 0 of 0 entries","infoFiltered":   "(filtered from _MAX_ total entries)","infoPostFix":"","thousands":",","lengthMenu":"Show _MENU_ entries","loadingRecords": "Loading . . .","processing":"<i class='fa fa-refresh fa-spin fa-lg'></i><br>Loading . . .","search":"Search:","zeroRecords":"No matching records found","paginate": {"first":"First","last":"Last","next":"Next","previous":"Previous"}},*/
			processing: true,
			serverSide: true,
			columnDefs: [
				{ targets: [1], class: 'tal cur_hand bold' },
				{ targets: [1], width: '50%' },
				{ targets: [0,1], class: 'tal' },
				{ targets: [4], class: 'tar' },
				{ targets: [2,3], class: 'tac pad48' },
				{ targets: [10,11,12,13], class: 'red' },
				{ targets: [5,6], class: 'blue' },
				{ targets: [7], class: 'blue tac' },
				{ targets: [8,22,23], class: 'tac' },
				{ targets: [17], class: 'bold blue' },
				{ targets: [16,17], class: 'tar' },
			],
			ajax: {
				url: "ajax/server_get_attendance.php",
				type: "POST", 
				data: function(d){
					d.emp_id = $("#searchFilter").val();
					d.sdate = $("#sdate").val();
					d.edate = $("#edate").val();
					d.filter = $("#typeFilter").val();
					d.apprfilter = $("#apprFilter").val();
					d.team = $("#teamFilter").val();

							var select_button_text = $('#selBox-teams option:selected')
                .toArray().map(item => item.text).join();

					d.team_filter = select_button_text;
				}
			},
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
			},
			"createdRow": function ( row, data, index ) {

				if(data[0].indexOf('non') != -1){
					$(row).removeClass('nonred');

				}
				else
				{
					$(row).addClass('nonred');
				}
				if(data[0].indexOf('approved') != -1 && user != 'sys'){
					$(row).closest('tr').find('a.edit').prop('disabled', true).addClass('disabled');
				}
				if(data[0].indexOf('locked') != -1){
					$(row).closest('tr').find('a.edit').prop('disabled', true).addClass('disabled');
				}
			}
		});
		$("#searchFilter").keyup(function() {
			dtable.ajax.reload(null, true);
		});
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.search('').draw();
		})
		$(document).on("change", "#typeFilter, #apprFilter, #teamFilter", function(e){
			$('body').tooltip("dispose");
			dtable.ajax.reload(null, false);
			setTimeout(function(){
				$("body").tooltip({ 
					container: 'body',
					selector: '[data-toggle=tooltip]',
					animated: 'fade',
					placement: 'top',
					html: true
				});
			},50);
		});
		$("#exportExcel").on("click", function() {
			dtable.button('.buttons-excel').trigger('click');
		});
		$('.buttons-excel').hide();

		$(document).on('click', '#datatable tbody tr td', function(){
			var cell = dtable.cell(this).index().columnVisible;
			if(cell == 1){
				emp_id = $(this).closest('tr').find('.emp_id').html();
				$('#searchFilter').val(emp_id);
				dtable.ajax.reload(null, true);
			}
		});
		
		$('#datatable').on('mouseenter', 'td', function(e){
			$(this).closest('tr').find('.emp_name').addClass('redbold');
		}).on('mouseleave', 'td', function(){
			$(this).closest('tr').find('.emp_name').removeClass('redbold');
		})
		
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
			dtable.ajax.reload(null, false);
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
		/*$(document).on("click", "#datesBtn", function(e){
			dtable.ajax.reload(null, false);
		})*/
		

			$(document).on("change", "#selBox-teams", function(e) {

			var select_button_text = $('#selBox-teams option:selected')
                .toArray().map(item => item.text).join();

	
			dtable.ajax.reload(null, false);
				
				
		})	

					
		$(document).on("change", ".combox", function(e){
			var id = $(this).closest('tr').find('.edit').data('id')
			var val = $(this).is(':checked')
			var fld = $(this).data('fld')
			//alert(id+' : '+val+' : '+fld)
			$.ajax({
				url: "ajax/update_allowances.php",
				data: {id: id, val: val, fld:fld},
				success: function(data){
					//$('#dump').html(data);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		
		/*$(document).on("click", "#reCalculate", function(e){  // ??????????????????????????????????????????????????????
			$("#calcBut i").removeClass('fa-calculator').addClass('fa-repeat fa-spin');
			//var id = $("#calcID").val()
			var data = $('#editForm').serialize();
			//alert(id); return false
			$.ajax({
				url: ROOT+"time/ajax/recalculate_attendance.php",
				data: data,
				//dataType: 'json',
				success: function(data){
					//$('#dump').html(data); return false;
					//alert(data.actual_hrs)
					$('input[name="late"]').val(data.late)
					$('input[name="early"]').val(data.early)
					$('input[name="actual_hrs"]').val(data.actual_hrs)
					$('input[name="paid_hrs"]').val(data.paid_hrs)
					$('input[name="ot1"]').val(data.ot1)
					$('input[name="ot15"]').val(data.ot15)
					$('input[name="ot2"]').val(data.ot2)
					$('input[name="ot3"]').val(data.ot3)
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});*/
		
		$(document).on("click", "#calcBut", function(e){
			$("#calcBut i").removeClass('fa-calculator').addClass('fa-repeat fa-spin');
			var sdate = $("#sdate").val()
			$.ajax({
				url: "ajax/calculate_hours_attendance.php",
				//type: "POST", 
				data: {sdate: $('#sdate').val(), edate: $('#edate').val()},
				//data: {date: sdate},
				success: function(data){
					//$('#dump').html(data); //return false;

					var newVariable  =  $.trim(data) ; 
					console.log(newVariable);

					if(newVariable  == '1')
					{
						$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : A Shiftplan is missing for a team . Please set the shiftplan first and try again.',
						duration: 4,
					})
					setTimeout(function(){$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');},500);

					}
					else
					{
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Calculated successfuly']?>',
							duration: 2,
						})
						setTimeout(function(){
							$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');
							dtable.ajax.reload(null, false);
						},500);
					}
		
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
					setTimeout(function(){$("#calcBut i").removeClass('fa-repeat fa-spin').addClass('fa-calculator');},500);
				}
			});
		});
		
	// APPROVE & CHECK /////////////////////////////////////////////////////////////////////////////
		var checkall = 0;
		$(document).on("click", "#dApprAll", function(e){
			if(checkall == 0){
				$('.dbox').not(":disabled").prop('checked', true);
				checkall = 1;
			}else{
				$('.dbox').not(":disabled").prop('checked', false);
				checkall = 0;
			}
		});
		$(document).on("click", "#dApprSelected", function(e){
			var ids = [];
			$('.dbox').each(function(){
				if($(this).not(":disabled").is(':checked')){
					ids.push($(this).closest('tr').find('.edit').data('id'));
				}
			})
			//alert(ids)
			if(ids != ''){
				$.ajax({
					url: "ajax/approve_time_line.php",
					type: "POST", 
					data: {ids: ids, date: $("#sdate").val()},
					success: function(data){
						//$('#dump').html(data); return false;
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						dtable.ajax.reload(null, false);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			}else{
			
			}
		});
		var mcheckall = 0;
		$(document).on("click", "#mApprAll", function(e){
			if(mcheckall == 0){
				$('.mbox').not(":disabled").prop('checked', true);
				mcheckall = 1;
			}else{
				$('.mbox').not(":disabled").prop('checked', false);
				mcheckall = 0;
			}
		});
		$(document).on("click", "#mApprSelected", function(e){
			var ids = [];
			$('.mbox').each(function(){
				if($(this).not(":disabled").is(':checked')){
					ids.push($(this).closest('tr').find('.edit').data('id'));
				}
			})
			//alert(ids)
			$.ajax({
				url: "ajax/approve_time_line.php",
				type: "POST", 
				data: {ids: ids, date: $("#sdate").val()},
				success: function(data){
					//$('#dump').html(data);
					dtable.ajax.reload(null, false);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});

	// MODAL EDIT /////////////////////////////////////////////////////////////////////////////
		function getTimeDetails(id){
			$.ajax({
				url: "ajax/get_time_details.php",
				data: {id: id},
				dataType: 'json',
				success: function(data){
					//$("#dump").html(data); return false;
					console.log(data);
					$("#aID").val(data.id);
					$("#aDate").val(data.date);
					$("#aScan1").html(data.ascan1);
					$("#aScan2").html(data.ascan2);
					$("#aScan3").html(data.ascan3);
					$("#aScan4").html(data.ascan4);
					$("#aScan5").html(data.ascan5);
					$("#aScan6").html(data.ascan6);
					$("#aScan7").html(data.ascan7);
					$("#aScan8").html(data.ascan8);
					$("#aScan9").html(data.ascan9);
					
					/*if(data.img1 != ''){$("#img1").attr('src', data.img1);}else{$("#img1").attr('src', '');}
					if(data.img2 != ''){$("#img2").attr('src', data.img2);}else{$("#img2").attr('src', '');}
					if(data.img3 != ''){$("#img3").attr('src', data.img3);}else{$("#img3").attr('src', '');}
					if(data.img4 != ''){$("#img4").attr('src', data.img4);}else{$("#img4").attr('src', '');}
					if(data.img5 != ''){$("#img5").attr('src', data.img5);}else{$("#img5").attr('src', '');}
					if(data.img6 != ''){$("#img6").attr('src', data.img6);}else{$("#img6").attr('src', '');}
					if(data.img7 != ''){$("#img7").attr('src', data.img7);}else{$("#img7").attr('src', '');}
					if(data.img8 != ''){$("#img8").attr('src', data.img8);}else{$("#img8").attr('src', '');}
					if(data.img9 != ''){$("#img9").attr('src', data.img9);}else{$("#img9").attr('src', '');}*/
					
					$("#img1").attr('src', data.img1);
					$("#img2").attr('src', data.img2);
					$("#img3").attr('src', data.img3);
					$("#img4").attr('src', data.img4);
					$("#img5").attr('src', data.img5);
					$("#img6").attr('src', data.img6);
					$("#img7").attr('src', data.img7);
					$("#img8").attr('src', data.img8);
					$("#img9").attr('src', data.img9);
					

					$("#loc1").html(data.loc1);
					$("#loc2").html(data.loc2);
					$("#loc3").html(data.loc3);
					$("#loc4").html(data.loc4);
					$("#loc5").html(data.loc5);
					$("#loc6").html(data.loc6);
					$("#loc7").html(data.loc7);
					$("#loc8").html(data.loc8);
					$("#loc9").html(data.loc9);
					



					$("#eScan1").val(data.scan1);
					// $("#eScan2").val(data.scan2);
					// $("#eScan3").val(data.scan3);
					// $("#eScan4").val(data.scan4);

					if(data.scan3 == '-' ||  data.scan4 == '-')
					{

						// check if its a scan out or not 

						var str3 = data.loc2;
						var str4 = "-IN";
						if(str3.indexOf(str4) != -1){
						    // found IN
						    if(data.comp10 == '1')
						    {
						    	$("#eScan4").val(data.scan2);
						    }
						}
						else
						{
							// found OUT

							$("#eScan4").val(data.scan2);
						}

					}
					else
					{

						var str1 = data.loc4;
						var str2 = "-IN";
						if(str1.indexOf(str2) != -1){
						    // found IN
						    if(data.comp10 == '1')
						    {
						    	$("#eScan4").val(data.scan4);
						    }
						}
						else
						{
							// found OUT
							$("#eScan4").val(data.scan4);
						}
					}

					

					









					$("#pScan1").html(data.f1);
					$("#pScan2").html(data.u1);
					$("#pScan3").html(data.f2);
					$("#pScan4").html(data.u2);

					$("#pTothrs").html(data.tot_hrs);
					$("#pPlanhrs").html(data.planned_hrs);
					$("#pOThrs").html(data.ot_hrs);
					$("#pActualhrs").html(data.actual_hrs);
					$("#pBreak").html(data.break);
					
					$("#eTothrs").html(data.tot_hrs);
					$("#ePlanhrs").val(data.planned_hrs);
					$("#eOThrs").html(data.tot_ot);
					
					$("#eLeave").html(data.unpaid_leave);
					$("#uLate").val(data.unpaid_late);
					$("#uEarly").val(data.unpaid_early);
					$("#pLate").val(data.paid_late);
					$("#pEarly").val(data.paid_early);
					$("#eActual").val(data.actual_hrs);
					$("#eNormal").val(data.normal_hrs);
					$("#eOt1").val(data.ot1);
					$("#eOt15").val(data.ot15);
					$("#eOt2").val(data.ot2);
					$("#eOt3").val(data.ot3);
					$("#eRemarks").val(data.remarks);
					$("#aComment").val(data.comment);
					$("#eCheck").html(data.flag);
					$("#timeImage").attr('src', '../'+data.image);
					$("#checkClass").removeClass('checkgreen');
					$("#checkClass").removeClass('checkred');
					$("#checkClass").addClass(data.class);
					//$("#timeTable tbody").html(response);
					if(data.flag == '0:00'){
						$('#eApprove').prop('disabled', false);
					}else{
						$('#eApprove').prop('disabled', true);
					}


					// remove scan 3 and 4 if no value exists

					var scan3Val = data.scan3;
					var scan4Val = data.scan4;

					if(scan3Val == '-' || scan3Val == '') 
					{
						$('#eScan3').attr( "style", "display: none !important;" )
						$('#eScan3Head').attr( "style", "display: none !important;" )
						$('#eScan3Both').attr( "style", "display: none !important;" )
						$('#pcan3td').attr( "style", "display: none !important;" )
					}
					else
					{
						$('#eScan3').css('display','');
						$('#eScan3Head').css('display','');
						$('#eScan3Both').css('display','');
						$('#pcan3td').css('display','');
						$('#eScan3Head').html('Scan IN');
					}	
					if(scan4Val == '-' ||  scan4Val == '') 
					{

						// $('#eScan4').attr( "style", "display: none !important;" )
						// $('#eScan4Head').attr( "style", "display: none !important;" )
						// $('#eScan4Both').attr( "style", "display: none !important;" )
						// $('#pcan4td').attr( "style", "display: none !important;" )
						$('#eScan2Head').html('Scan OUT');


					}
					else
					{
						$('#eScan4').css('display','');
						$('#eScan4Head').css('display','');
						$('#eScan4Both').css('display','');
						$('#pcan4td').css('display','');

						// change heading to break out and break in 

						// $('#eScan2Head').html('Break OUT');
						// $('#eScan3Head').html('Break IN');
						$('#eScan4Head').html('Scan OUT');
					}

					var planValues = data.plan;

					if(planValues == '')
					{
						var errormSg = 'NO LINK TO SHIFTPLAN YET';
						$('span#noplanmessage').html(errormSg);
					}

				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		}
		$('#datatable').on("click", ".edit", function(e){
			e.preventDefault();
			getTimeDetails($(this).data('id'));
			$("#modalEditTime").modal('toggle');
		});
		$('#modalEditTime').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
		});

		$(document).on('submit', "#editForm", function (e) {
			e.preventDefault();
			var data = $(this).serialize();
			//alert(data);
			$.ajax({
				url: "ajax/update_attendance.php",
				data: data,
				success: function(response){
					//$("#dump").html(response); return false;
					if(response != 'success'){
						$('#timeMsg').html(response)
					}else{
						dtable.ajax.reload(null, false);
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						$("#modalEditTime").modal('toggle');
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});
		
	// CHANGE & UPDATE COMMENT ///////////////////////////////////////////////////////////////////////////
		/*$(document).on("change", ".comment", function(e) {
			var text = $(this).val();
			var id = $(this).closest('tr').find('.edit').data('id');
			$.ajax({
				url:"ajax/update_comment.php",
				data: {id: id, text: text},
				success: function(data){
					//$('#dump').html(data);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});*/
	
	// DRAG HOURS /////////////////////////////////////////////////////////////////////////////////////
		$('span').mouseover(function(){
			$(this).css('cursor', 'pointer');
		});
		$( ".draggable span" ).draggable({helper: 'clone'});
		$(".p_lang").droppable({
			accept: ".draggable span",
			drop: function(ev, ui) {
				$(this).val('').insertAtCaret(ui.draggable.text()).removeClass('dragover');
				reCalculateHours();
				//$(this).removeClass('ui-droppable');
				//$("#draggable").removeAttr('id');
			},
			over: function(ev, ui) {
				$(this).addClass('dragover');
			},
			out: function(ev, ui) {
				$(this).removeClass('dragover');
			}
		});
	
		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});

		$(window).on('resize', function(){
			scrY = window.innerHeight-250;
			$('div.dataTables_scrollBody').height(scrY);
			dtable.columns.adjust().draw();
		});	
		
	
	});
		
</script>


<script type="text/javascript">



	



</script>



	
