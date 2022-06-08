<?
	include(DIR.'leave/functions.php');
	//updateLeaveDatabase($cid);
	
	$leave_settings = getLeaveTimeSettings();
	$leave_types = getSelLeaveTypes($cid);
	$leave_status = array('RQ'=>$lng['Pending'],'CA'=>$lng['Cancelled'],'AP'=>$lng['Approved'],'RJ'=>$lng['Rejected']);
	//var_dump($leave_types); //exit;
	
	foreach($leave_types as $k=>$v){
		if(!isset($v['emp_request']) || $v['emp_request'] == 0){unset($leave_types[$k]);}
		$balance[$k] = array('type'=>$v[$lang], 'pending'=>0, 'used'=>0);
	}
	//var_dump($balance); exit;
	//$balance = getUsedLeaveEmployee($cid, $_SESSION['rego']['emp_id'], $balance);
		
	$res = $dbc->query("SELECT status, days, leave_type FROM ".$cid."_leaves_data WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND date >= '".$_SESSION['rego']['cur_year'].'-01-01'."'"); // date > 1st day this year
	while($row = $res->fetch_assoc()){
		if($row['status'] == 'RQ' || $row['status'] == 'AP'){
			$balance[$row['leave_type']]['pending'] += $row['days'];
		}elseif($row['status'] == 'TA'){
			$balance[$row['leave_type']]['used'] += $row['days'];
		}
	}
	$ALemp = $data['annual_leave'] - $balance['AL']['used'];
	foreach($balance as $k=>$v){
		if($v['pending'] == 0 && $v['used'] == 0){unset($balance[$k]);}
	}
	//var_dump($balance); exit;
	
	//var_dump($leave_types); exit;
	$pending = array();
	$history = array();
	$sql = "SELECT * FROM ".$cid."_leaves";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if($row['status'] != 'RQ'){
				$day = 'day';
				if($row['days'] > 1){$day = 'days';}
				$history[$row['id']]['date'] = date('D d-m-Y', strtotime($row['start'])).' - '.date('D d-m-Y', strtotime($row['end']));
				$history[$row['id']]['type'] = $leave_types[$row['leave_type']][$lang];
				$history[$row['id']]['days'] = round($row['days'],1).' '.$day;
				$history[$row['id']]['approved'] = $row['approved_by'];
				$history[$row['id']]['status'] = $row['status'];
			}
			if($row['status'] == 'RQ'){
				//$pending[] = $row;
				$day = 'day';
				if($row['days'] > 1){$day = 'days';}
				$pending[$row['id']]['date'] = date('D d-m-Y', strtotime($row['start'])).' - '.date('D d-m-Y', strtotime($row['end']));
				$pending[$row['id']]['type'] = $leave_types[$row['leave_type']][$lang];
				$pending[$row['id']]['days'] = round($row['days'],1).' '.$day;
				$pending[$row['id']]['details'] = unserialize($row['details']);
				$pending[$row['id']]['reason'] = $row['reason'];
			}
		}
	}
	//var_dump($pending); //exit;
	
?>	

<style>
	/*table.mytable tbody td, 
	table.mytable thead th {
		text-align:left;
		padding:4px 10px;
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
	.planned {
	  background: #8d8 !important;
	  border:1px #8d8 solid;
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
	table.dataTable tbody td:first-child a {
		text-decoration:none;
		font-weight:600;
		color:#003399;
	}	
	table.dataTable tbody td:first-child a:hover {
		color:#900;
	}	
	.date_month, .cdate_month {
		cursor:pointer;
	}*/
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
		min-width:120px !important;
		font-size:13px !important;
		font-weight:400 !important;
		xcolor:#333 !important;
		text-align:center !important;
	}
	.xdayType:hover {
		background:#999 !important;
		color:#fff !important;
	}

	.pannel {
		position:absolute; 
		top:130px; 
		bottom:10px; 
		border:0px solid red;
		padding:15px;
		box-size:border-box;
		overflow:hidden;
	}
	.left_pannel {
		left:0; 
		width:205px;
		padding-right:5px;
	}
	.main_pannel {
		left:205px; 
		right:0;
		padding-left:5px;
	}
	b {
		font-weight:600;
	}
	input, select, textarea {
		background:transparent !important;
	}
	textarea{  
		box-sizing: border-box;
		resize: none;
		overflow:hidden;
	}
/*		.fileBtn {
			display:block;
			margin-top:5px;
		}
		.fileBtn [type="file"]{
			border:0;
			visibility:false;
			position:absolute;
			width:0px;
			height:0px;
		}
		.fileBtn label{
			background:#eee;
			background: linear-gradient(to bottom, #eee, #ddd);
			border-radius: 2px;
			border:1px #ccc solid;
			padding:1px 8px;
			line-height:18px;
			white-space:nowrap;
			color: #000;
			cursor: pointer;
			display: inline-block;
			xfont-family: 'Open Sans', sans-serif;
			font-size:13px;
			font-weight:400;
		}
		.fileBtn label:hover{
			background: linear-gradient(to bottom, #ddd, #eee);
		}
		.fileBtn p {
			padding:0 0 0 5px;
			margin:0;
			display:inline-block;
			xfont-family: Arial, Helvetica, sans-serif;
			font-size:13px;
		}
*/

	.basicTable tbody td {
		position:relative;
		xpadding:5px 10px !important;
	}
	.basicTable tbody td b {
		font-weight:600;
		display:block;
		padding-bottom:2px;
		font-size:13px;
		color:#b00;
	}
	.basicTable tbody td span {
		display:block;
		color:#666;
	}
	.basicTable tbody td .status {
		position:absolute;
		top:7px;
		right:5px;
		font-size:18px;
		background:#aaa;
		color:#fff;
		width:33px;
		height:33px;
		line-height:33px;
		text-align:center;
		border-radius:3px; 
	}
	.basicTable tbody td .status.RQ {
		background: #aaa;
	}
	.basicTable tbody td .status.RQ:before {
		font-family: "FontAwesome";
		content: "\f254";
	}

	.basicTable tbody td .status.AP {
		background: #009966;
	}
	.basicTable tbody td .status.AP:before {
		font-family: "FontAwesome";
		font-size:20px;
		content: "\f164";
	}

	.basicTable tbody td .status.RJ {
		background: #b00;
	}
	.basicTable tbody td .status.RJ:before {
		font-family: "FontAwesome";
		content: "\f165";
	}

	.basicTable tbody td .status.CA {
		background: #f90;
	}
	.basicTable tbody td .status.CA:before {
		font-family: "FontAwesome";
		content: "\f00d";
	}
	.basicTable tbody td .delete:before {
		background:#c00;
		height:33px;
		width:33px;
		line-height:33px;
		margin:0;
		color:#fff;
		text-align:center; 
		font-family: "FontAwesome";
		font-size:20px;
		content: "\f1f8";
		cursor:pointer;
		border-radius:3px;
		display:block;
	}
	
	.detail_table td {
		padding:0 5px !important;
		border:0 !important;
	}
	.detail_table tr {
		border:0 !important
	}
	.popover .btn-sm {
		margin:5px 1px 5px;
	}
	.popover .btn-sm:hover {
		color:#fff !important;
	}
	.popover-title {
		padding:6px 10px
	}
</style>

<div style="width:100%">
	
	<h2><i class="fa fa-plane fa-lg"></i>&nbsp; <?=$lng['Leave application']?></h2>		

	<div class="pannel left_pannel">
		<? include('emp_picture.php'); ?>
	</div>
	
	<div class="pannel main_pannel" style="padding-bottom:0">
			
			<div id="dump"></div>
								
			<div style="padding:10px; border:1px solid #ccc; height:100%">
				
				<table style="width:100%; table-layout:fixed; height:100%">
					<tr>
						<td style="width:50%; vertical-align:top; padding-right:10px; border-right:2px #eee solid">
							<div style="height:100%; xoverflow-Y:auto">
							<form id="leaveRequest">
								<input name="leave_id" id="leave_id" type="hidden" value="0" />
								<input name="update" id="update" type="hidden" value="0" />
								<input name="by_name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
								<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
								<input name="status" id="status" type="hidden" value="RQ" />
								<input style="visibility:hidden; height:0; float:left" id="attach" type="file" name="attach" />
								
								<table class="basicTable inputs" border="0" style="margin-bottom:10px">
									<thead>
										<tr>
											<th colspan="2"><?=$lng['Leave request']?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th><i class="man"></i><?=$lng['Employee']?></th>
											<td><input readonly name="name" type="text" value="<?=$_SESSION['rego']['name']?>" /></td>
										</tr>
										<tr>
											<th><?=$lng['Phone']?></th>
											<td><input readonly name="phone" type="text" value="<?=$data['personal_phone']?>" /></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Leave type']?></th>
											<td>
												<select name="leave_type" id="leave_type" style="width:100%">
													<option disabled selected value="0"><?=$lng['Select']?></option>
													<? foreach($leave_types as $k=>$v){ ?>
															<option value="<?=$k?>"><?=$v[$lang]?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['First day']?></th>
											<td><input style="cursor:pointer" readonly type="text" name="" id="startdate"></td>
										</tr>
										<tr>
											<th><i class="man"></i><?=$lng['Last day']?></th>
											<td><input style="cursor:pointer" readonly type="text" name="" id="enddate"></td>
										</tr>
										<tr>
											<th><?=$lng['Details']?></th>
											<td style="padding:0; cursor:default;">
												<span id="rangeTable"></span>
											</td>
										</tr>
										<tr id="certRow" style="display:none">
											<th><i class="man"></i><?=$lng['Certificate']?></th>
											<td class="pad410" style="white-space:normal">
												<input type="hidden" name="certificate" id="certificate" value="NA" />
												<label><input type="radio" id="certAttach" name="certificate" value="Y" class="radiobox style-0"><span> <?=$lng['In attachment']?></span></label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="certificate" value="H" class="radiobox style-0"><span> <?=$lng['Handed to HR department']?></span></label>&nbsp;&nbsp;&nbsp;
												<label><input type="radio" name="certificate" value="N" class="radiobox style-0"><span> <?=$lng['No certificate']?></span></label>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Reason']?></th>
											<td>
												<textarea rows="3" name="reason" id="reason"></textarea>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Attachement']?></th>
											<td class="pad410">
												<button onclick="$('#attach').click()" class="btn btn-default" style="margin:0; padding:0px 8px; display:inline-block" type="button"><?=$lng['Select file']?></button><span style="display:inline-block; padding-left:10px" id="err_msg"><?=$lng['No file selected']?></span>
											</td>
										</tr>
									</tbody>
								</table>
								
								<button onclick="$('#status').val('RQ');" class="btn btn-primary" id="requestBut" type="submit"><i class="fa fa-feed"></i>&nbsp;&nbsp;<?=$lng['Request']?></button>
							
							</form>
							</div>
						</td>
						<td style="width:50%; vertical-align:top; padding-left:10px">
							<div style="height:100%; xoverflow-Y:auto">
							
							<table class="basicTable" border="0" style="margin-bottom:8px">
								<thead>
									<tr>
										<th colspan="2"><?=$lng['Pending requests']?></th>
									</tr>
								</thead>
								<tbody>
								<? if($pending){ foreach($pending as $k=>$v){ ?>
									<tr>
										<td style="width:95%; border:0">
											<b><i class="fa fa-plane"></i>&nbsp;&nbsp;<?=$v['type'].' &nbsp;-&nbsp; '.$v['days']?></b>
											<table class="detail_table" border="0">
												<? foreach($v['details'] as $pen){ 
													$tmp = $pen['day'];
													if($pen['day'] == 'full'){$tmp = $lng['Full day'];}
													if($pen['day'] == 'first'){$tmp = $lng['First half'];}
													if($pen['day'] == 'second'){$tmp = $lng['Second half'];}
													$d = date('N', strtotime($pen['date']));
												?>
												<tr>
													<td class="tar"><?=$xsdays[$d].date(' d-m-Y', strtotime($pen['date']))?></td>
													<td>&bull;</td>
													<td><?=$tmp?></td>
													
												</tr>
												<? } ?>
											</table>
											<? if($v['reason']){ ?>
											<i style="padding-left:5px"><?=$lng['Reason'].' : '.$v['reason']?></i>
											<? } ?>
											
										</td>
										<td style="vertical-align:top; padding:6px 5px 0 0"><a data-id="<?=$k?>" class="delete"></a></td>
									</tr>
								<? }}else{ ?>
									<tr>
										<td><?=$lng['No data available']?></td>
									</tr>
								<? } ?>
								</tbody>
							</table>

							<table class="basicTable" border="0" style="margin-bottom:8px">
								<thead>
									<tr>
										<th colspan="3"><?=$lng['Balance']?> <i style="float:right; font-style:normal; color:#a00"><?=$lng['Remaining annual leave']?> : <?=$ALemp?> <?=$lng['Days']?></i></th>
									</tr>
									<tr style="background:#f3f3f3; border:0">
										<th style="line-height:90%; width:80%"><?=$lng['Leave type']?></th>
										<th style="line-height:90%; width:10%" class="tac"><?=$lng['Pending']?></th>
										<th style="line-height:90%; width:10%" class="tac"><?=$lng['Used']?></th>
									</tr>
								</thead>
								<tbody>
								<? if($balance){ foreach($balance as $k=>$v){ ?>	
									<tr>
										<td><?=$v['type']?></td>
										<td class="tac"><?=round($v['pending'],1)?></td>
										<td class="tac"><?=round($v['used'],1)?></td>
									</tr>
								<? }}else{ ?>
									<tr>
										<td colspan="3"><?=$lng['No data available']?></td>
									</tr>
								<? } ?>
								</tbody>
							</table>

							<table class="basicTable" border="0" style="margin-bottom:10px">
								<thead>
									<tr>
										<th colspan="2"><?=$lng['Leave history']?></th>
									</tr>
								</thead>
								<tbody>
								<? if($history){ foreach($history as $k=>$v){ ?>
									<tr>
										<td>
											<b><?=$v['date']?></b>
											<span><i class="fa fa-plane"></i>&nbsp;&nbsp;<?=$v['type']?> - <?=$v['days']?></span>
											<? if(!empty($v['approved'])){ ?>
											<span><?=$lng['Approved by']?> <?=$v['approved']?></span>
											<? } ?>
											<div class="status <?=$v['status']?>"></div>
										</td>
									</tr>
								<? }}else{ ?>
									<tr>
										<td><?=$lng['No data available']?></td>
									</tr>
								<? } ?>
								</tbody>
							</table>
							</div>
						</td>
					</tr>
				</table>
				
			</div>
				
	</div>		
			
</div>
	
	<script type="text/javascript" src='../js/moment.min.js'></script>
	<script src="../timepicker/dist/jquery-clockpicker.min.js"></script>
	
	<script type="text/javascript">
		
		$(document).ready(function() {
			
			var leave_types = <?=json_encode($leave_types)?>;
			var emp_id = <?=json_encode($_SESSION['rego']['emp_id'])?>;
			var dayType;
			var cert;
			var row_id;

			$(document).on("change", "#leave_type", function(e){
				cert = leave_types[$(this).val()]['certificate'];
				if(cert == 0){
					$('#certRow').hide();
				}else{
					$('#certRow').show();
				}
			});

			$(document).on("click", "#certAttach", function(e){
				e.preventDefault();
			})
			$(document).on("change", "#attach", function(e){
				e.preventDefault();
				$('#err_msg').html('');
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				$('#err_msg').html(ff);
				$('input:radio[name=certificate]').filter('[value=Y]').prop('checked', true);
				$('input:radio[name=certificate]').filter('[value=N]').prop('disabled', true);
				$('input:radio[name=certificate]').filter('[value=H]').prop('disabled', true);
			});
			
			$(document).on('click','.dayType', function(e) {
				dayType = $(this).data('id');
			});			
			$(document).on('click','.selDayType', function(e) {
				var type = $(this).data('id');
				 $('.day'+dayType).html($(this).text());
				$('#mday'+dayType).val(type);
				$('body [data-toggle="popOver"]').popover('hide');
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
				selector: '[data-toggle="popOver"]', //Sepcify the selector here
				title: '',
				content: '<table class="popTable" border="0">'+
					'<tr>'+
						'<td colspan="2">'+
							'<button data-id="full" class="selDayType btn btn-default btn-xs" type="button"><?=$lng['Full day']?></button>'+
						'</td>'+
					'</tr>'+
					'<tr>'+
						'<td><button data-id="first" class="selDayType btn btn-default btn-xs" type="button"><?=$lng['First half']?></button></td>'+
						'<td><button data-id="second" class="selDayType btn btn-default btn-xs" type="button"><?=$lng['Second half']?></button></td>'+
					'</tr>'+
					'<tr>'+
						'<td><input id="timeFrom" class="timePic" readonly placeholder="<?=$lng['From']?> 00:00" type="text"></td>'+
						'<td><input id="timeUntil" class="timePic" disabled readonly placeholder="<?=$lng['Until']?> 00:00" type="text"></td>'+
					'</tr>'+
				'</table>'
			}	
			var requestPop = $('body').popover(popRequestSettings);
			$('body').on('hidden.bs.popover', function (e) {
				 $(e.target).data("bs.popover").inState.click = false;
			});			
			
			$("#leaveRequest").on('submit', function(e){ 
				e.preventDefault();
				var err = false;
				if($('#leave_type').val() == null){err = true;}
				if($('#startdate').val() == ""){err = true;}
				if($('#enddate').val() == ""){err = true;}
				if(cert == 1 && $('input[name=certificate]:checked').val() == null){err = true;}
				//err = false;
				if(err == true){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false;
				}

				$("#requestBut i").removeClass('fa-feed').addClass('fa-refresh fa-spin');
				var data = new FormData(this);
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
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							//$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							//$("#requestBut").prop('disabled', true);
							setTimeout(function(){location.reload();},2000);
						}else{
							$("#requestBut i").removeClass('fa-refresh fa-spin').addClass('fa-feed');
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' + result,
								duration: 4,
								closeConfirm: true
							})
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<?=$lng['Sorry but someting went wrong']?>&nbsp; <b><?=$lng['Error']?> :</b> ' + thrownError,
							duration: 4,
							closeConfirm: true
						})
					}
				});
			});
			
			$(document).on('click','a.delete', function(e) {
				row_id = $(this).data('id');
			});			
			$('a.delete').confirmation({
				container: 'body',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title: '<div style="text-align:center"><b><?=$lng['Are you sure']?></b></div>',
				btnOkIcon: '',
				btnOkClass: 'btn btn-sm btn-danger',
				btnCancelClass: 'btn btn-sm btn-success',
				btnCancelIcon: '',
				btnOkLabel: '<?=$lng['Delete']?>',
				btnCancelLabel: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					$.ajax({
						url: ROOT+"leave/ajax/delete_leave.php",
						data: {id: row_id},
						success: function(result){
							setTimeout(function(){location.reload();},100);
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<?=$lng['Sorry but someting went wrong']?>&nbsp; <b><?=$lng['Error']?> :</b> ' + thrownError,
								duration: 4,
								closeConfirm: true
							})
						}
					});
				},
			});
			
			var startDate = $('#startdate').datepicker({
				format: "D dd-mm-yyyy", 
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startDate: new Date(),
				todayHighlight: true,
				language: lang,
			}).on('changeDate', function(e){
				$('#enddate').datepicker('setDate', startDate.val()).datepicker('setStartDate', startDate.val()).focus();
			});
			var endDate = $('#enddate').datepicker({
				format: "D dd-mm-yyyy",
				multidate: false,
				keyboardNavigation: false,
				autoclose: true,
				startDate: new Date(),
				todayHighlight: true,
				language: lang,
			}).on('changeDate', function(e){
				if($('#startdate').val() !== ''){
					$.ajax({
						url: ROOT+"leave/ajax/get_leave_range.php",
						data: {startDate: $('#startdate').val(), endDate: e.format()},
						success: function(result){
							$('#rangeTable').html(result); return false;
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<?=$lng['Sorry but someting went wrong']?>&nbsp; <b><?=$lng['Error']?> :</b> ' + thrownError,
								duration: 4,
								closeConfirm: true
							})
						}
					});
				}
			})
	
		});
	
	</script>
						
						


















