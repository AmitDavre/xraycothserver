<?
	
	if(!isset($_GET['dh'])){
		$_GET['dh'] = 'hrs';
	}
	if($_GET['dh'] == 'hrs'){
		$dayhrs = 'Hours';
	}else{
		$dayhrs = 'Days';
	}
	
	$var_allow = getUsedVarAllow($lang);
	$compensations = getCompensations();
	foreach($var_allow as $k=>$v){
		$allow_cols[$k] = 0;
	}
	//var_dump($allow_cols); //exit;
	//var_dump($compensations); //exit;
	
	$approved = false;
	$sql = "SELECT * FROM ".$cid."_approvals WHERE month = '".$_SESSION['rego']['cur_month']."' AND type = 'TA' AND action = 'AP'";
	//echo $sql;
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			$approved = true;
		}
	}
	//var_dump($approved);
	$input = 'class="sel float32"';
	$inputOT = 'class="sel float32"';
	$input2 = 'class="sel float52"';
	if($approved){$input = 'readonly';}
	if($approved){$input2 = 'readonly';}
	
	
	$time_period = getTimePeriod();
	$start = date('Y-m-d', strtotime($time_period['start']));
	$end = date('Y-m-d', strtotime($time_period['end']));
	//var_dump($start); //exit;
	//var_dump($end); //exit;

	$data = array();
	$sql = "SELECT COUNT(id) as days, 
		emp_id, 
		en_name, 
		th_name, 
		plan, 
		SUM(planned_hrs) as planned_hrs, 
		SUM(planned_days) as planned_days, 
		SUM(paid_days) as paid_days, 
		SUM(normal_hrs) as normal_hrs, 
		SUM(planned_ot) as planned_ot, 
		SUM(ot1) as ot1, 
		SUM(ot15) as ot15, 
		SUM(ot2) as ot2, 
		SUM(ot3) as ot3, 
		SUM(paid_late) as paid_late, 
		SUM(paid_early) as paid_early, 
		SUM(unpaid_late) as unpaid_late, 
		SUM(unpaid_early) as unpaid_early, 
		SUM(public) as public, 
		SUM(personal) as personal, 
		SUM(unpaid_leave) as unpaid_leave, 
		SUM(comp1) as comp1, 
		SUM(comp2) as comp2, 
		SUM(comp3) as comp3, 
		SUM(comp4) as comp4, 
		SUM(comp5) as comp5, 
		SUM(comp6) as comp6, 
		SUM(comp7) as comp7, 
		SUM(comp8) as comp8, 
		SUM(comp9) as comp9, 
		SUM(comp10) as comp10 
		FROM ".$cid."_attendance WHERE calculate = 1 AND scan1 !='-' AND date >= '".$start."' AND date <= '".$end."' GROUP BY emp_id";
	//echo $sql; exit;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$step = getEmployeeStep($row['emp_id']);
			//var_dump($step);
			$days = $row['days'];
			$data[$row['emp_id']]['id'] = $row['emp_id'].$cur_month;
			$data[$row['emp_id']]['month'] = $cur_month;
			$data[$row['emp_id']]['emp_id'] = $row['emp_id'];
			$data[$row['emp_id']]['en_name'] = $row['en_name'];
			$data[$row['emp_id']]['th_name'] = $row['th_name'];
			
			$data[$row['emp_id']]['planned_days'] = $row['planned_days'];
			$data[$row['emp_id']]['normal_days'] = 0;//$row['paid_days'];
			$data[$row['emp_id']]['paid_days'] = $row['paid_days'];
			//$data[$row['emp_id']]['planned_hrs'] = $row['planned_hrs'];
			//$data[$row['emp_id']]['normal_hrs'] = $row['normal_hrs'];
			$paidhrs = round(($row['normal_hrs'] + $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'] + ($row['public']*8)),2);

			$data[$row['emp_id']]['ot1h'] = $row['ot1'];
			$data[$row['emp_id']]['ot15h'] = $row['ot15'];
			$data[$row['emp_id']]['ot2h'] = $row['ot2']; 
			$data[$row['emp_id']]['ot3h'] = $row['ot3'];
			$data[$row['emp_id']]['absence'] = round($row['unpaid_leave'],2);
			$data[$row['emp_id']]['unpaid_leave'] = $row['unpaid_leave'];
			$data[$row['emp_id']]['late_early_paid'] = round(($row['paid_late']+$row['paid_early']),2);
			$data[$row['emp_id']]['late_early_unpaid'] = round(($row['unpaid_late']+$row['unpaid_early']),2);
			$data[$row['emp_id']]['public'] = $row['public'];
			//$data[$row['emp_id']]['personal'] = $row['personal'];
			//$data[$row['emp_id']]['planned'] = $row['personal'];
			//$data[$row['emp_id']]['normal'] = $row['personal'];
			//$data[$row['emp_id']]['paid'] = $row['personal'];

			if($_GET['dh'] == 'hrs'){
				$data[$row['emp_id']]['planned'] = number_format($row['planned_hrs'],1);
				$data[$row['emp_id']]['normal'] = number_format($row['normal_hrs'],2);
				$data[$row['emp_id']]['paid'] = number_format($paidhrs,2);
				$data[$row['emp_id']]['personal'] = round(($row['personal']),2);
				$data[$row['emp_id']]['public'] = round(($row['public']*8),2);
			}else{
				$data[$row['emp_id']]['planned'] = $row['planned_days'];
				$data[$row['emp_id']]['normal'] = number_format(($row['normal_hrs']/8),2);
				$data[$row['emp_id']]['paid'] = number_format(($paidhrs/8),2);
				$data[$row['emp_id']]['ot1h'] /= 8;
				$data[$row['emp_id']]['ot15h'] /= 8;
				$data[$row['emp_id']]['ot2h'] /= 8;
				$data[$row['emp_id']]['ot3h'] /= 8;
				$data[$row['emp_id']]['personal'] = round(($row['personal']/8),2);
				$data[$row['emp_id']]['late_early_paid'] /= 8;
				$data[$row['emp_id']]['late_early_unpaid'] /= 8;
				$inputOT = 'readonly';
			}
			
			//$data[$row['emp_id']]['planned_ot'] = $row['planned_ot'];
			//$data[$row['emp_id']]['paid_early'] = round(($row['paid_early']),2);
			
			foreach($var_allow as $k=>$v){
				$data[$row['emp_id']]['var_allow_'.$k] = 0;
				$allow_cols[$k] = 0;
			}
			foreach($compensations as $k=>$v){
				if($v['type'] == 'permonth'){
					$qty = $days - $row['comp'.$k];
					$data[$row['emp_id']]['comp'.$k] = $qty;
					/*if($qty == 0){
						$data[$row['emp_id']]['comp'.$k] = '<i style="color:green" class="fa fa-check xfa-lg"></i>';
					}else{
						$data[$row['emp_id']]['comp'.$k] = '<i style="color:#b00" class="fa fa-times xfa-lg"></i>';
					}*/
					if($qty < $v['occurance']){ // YES
						if($v['compensation_type'] == 1){
							$data[$row['emp_id']]['var_allow_'.$v['allowance']] += $v['step1'];
							$allow_cols[$v['allowance']] = 1;
						}else{
							if($step < $v['compensation_type']){
								$step ++;
								$data[$row['emp_id']]['var_allow_'.$v['allowance']] += $v['step'.$step];
								$allow_cols[$v['allowance']] = 1;
							}
						}
					}else{ // NO
						if($v['compensation_type'] > 1){
							if($v['failure'] == 'decending'){
								if($step > 1){
									$step --;
									$data[$row['emp_id']]['var_allow_'.$v['allowance']] += $v['step'.$step];
									$allow_cols[$v['allowance']] = 1;
								}
							}
						}
					}
				}else{
					$data[$row['emp_id']]['comp'.$k] = $row['comp'.$k];
					$data[$row['emp_id']]['var_allow_'.$v['allowance']] +=  $row['comp'.$k] * $v['step1'];
					$allow_cols[$v['allowance']] = 1;
				}
			}
			//$data[$row['emp_id']]['days'] = $row['days'];
			$data[$row['emp_id']]['step'] = $step;
		}
	}else{
		var_dump(mysqli_error($dbc));
	}
	//var_dump($data); exit;
	
	/*$data = array();
	$sql = "SELECT * FROM ".$cid."_prepayroll WHERE month = '".$cur_month."' AND status = 0 ORDER BY emp_id ASC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$data[$row['id']] = $row;
			for($i=1;$i<=10;$i++){
				if($row['var_allow_'.$i] > 0){$allow_cols[$i] = 1;}
			}
			$data[$row['id']]['absence'] += round($row['unpaid_leave'],2);
			if($_GET['dh'] == 'hrs'){
				$data[$row['id']]['planned'] = number_format($row['planned_hrs'],1);
				$data[$row['id']]['normal'] = number_format($row['normal_hrs'],2);
				$data[$row['id']]['paid'] = number_format($row['paid_hrs'],2);
				$data[$row['id']]['personal'] = round(($row['personal']),2);
				$data[$row['id']]['public'] = round(($row['public']*8),2);
			}else{
				$data[$row['id']]['planned'] = $row['planned_days'];
				$data[$row['id']]['normal'] = number_format(($row['normal_hrs']/8),2);
				$data[$row['id']]['paid'] = number_format(($row['paid_hrs']/8),2);
				$data[$row['id']]['ot1h'] /= 8;
				$data[$row['id']]['ot15h'] /= 8;
				$data[$row['id']]['ot2h'] /= 8;
				$data[$row['id']]['ot3h'] /= 8;
				$data[$row['id']]['personal'] = round(($row['personal']/8),2);
				$data[$row['id']]['late_early_paid'] /= 8;
				$data[$row['id']]['late_early_unpaid'] /= 8;
				$inputOT = 'readonly';
			}
		}
	}*/
	
	//var_dump($allow_cols); exit;
	//var_dump($data); exit;
	//$data = array();
	
?>
	
<style>
	.btn.approve {
		background:#a00;
		border:1px #a00 solid;
	}
	.btn.approve:hover {
		background: #fb0;
		border:1px #fb0 solid;
	}
	.popover {
		max-width:500px !important;
		width:500px !important;
		xwidth:auto !important;
		border-radius:0 !important;
	}

	.pop1 {
		max-width:200px !important; 
		width:160px; 
		padding:3px 0px;
	}
	.pop1 button {
		width:100%; 
		padding:0px 10px !important;
		line-height:20px !important;
		margin:2px 0;
		text-align:left;
		color:#fff;
	}
	
	
	table.dataTable thead th {
		cursor:default;
	}
	table.dataTable input {
		border:0 !important;
		margin:0;
		text-align:right;
		padding:4px 8px;
		width:50px;
		float:right;
		xbackground:red !important;
	}

</style>
	
  <h2>
  	<i class="fa fa-table"></i>&nbsp;&nbsp;<?=$lng['Monthly attendance']?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?=$lng['From']?> <?=date('d-m-Y', strtotime($time_period['start']))?> <?=$lng['Until']?> <?=date('d-m-Y', strtotime($time_period['end']))?>
		<!--<i><span id="alert" style="float:right; display:block; display:none"></span></i>-->
	</h2>
	
	<div class="main">
		<div id="dump"></div>
		
		<ul style="position:relative" class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" data-target="#tab_approvals" data-toggle="tab"><?=$lng['Approvals']?></a></li>
			<li class="nav-item"><a class="nav-link active" data-target="#tab_attendance" data-toggle="tab"><?=$lng['Monthly attendance']?></a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 40px); position:relative">
	
			<div class="tab-pane" id="tab_approvals">
				<? include(DIR.'include/tabletabs.php'); ?>
				<table id="appTable" class="dataTable compact nowrap" width="100%" style="margin-bottom:10px" border="0">
					<thead>
						<tr>
							<th class="par30"><?=$lng['Date']?></th>
							<th></th>
							<th data-visible="false" class="par30"><?=$lng['ID']?></th>
							<th class="par30"><?=$lng['Name']?></th>
							<th data-sortable="false"><?=$lng['Action']?></th>
							<th data-sortable="false" style="width:90%"><?=$lng['Comment']?></th>
							<th data-sortable="false"><?=$lng['Attach']?></th>
						</tr>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			
			</div>
		
			<div class="tab-pane active" id="tab_attendance" style="height:100%; overflow-y:auto">
				
				<!--<form id="payrollForm" style="padding:0; margin:0">-->
				
				<!--<table border="0" style="position:absolute; top:15px; right:15px; display:none">
					<tr>
						<td style="vertical-align:top; display:none">
							<div class="searchFilter">
								<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
								<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
							</div>
						</td>
						<td style="padding:0 0px; vertical-align:top; display:none">
							<div id="message"></div>
						</td>
						<? //if($_SESSION['rego']['access']['approve']['payroll']['review'] == 1){ ?>
						<td style="padding-left:10px; display:none">
							<button <? if($approved){echo 'disabled';}?> type="button" id="reviewBut" class="btn btn-primary btn-sm approve"><i class='fa fa-thumbs-up'></i>&nbsp; <?=$lng['Review']?> </button>
						</td>
						<? //} if($_SESSION['rego']['access']['approve']['payroll']['approve'] == 1){ ?> 
						<td style="padding-left:5px;">
						</td>
						<? //} ?>
						<td style="padding-left:5px; display:none">
							<button <? if($approved){echo 'disabled';}?> type="button" id="rejectBut" data-toggle="popover" class="btn btn-primary btn-sm approve"><i class='fa fa-thumbs-down'></i>&nbsp; <?=$lng['Reject']?> </button>
						</td>
						
						<td style="vertical-align:top; padding-left:10px">
						</td>
					</tr>
				</table>-->
				
				<div style="position:absolute; top:-38px; right:0">
					
					<button <? if(!$approved){echo 'disabled';}?>  id="lockTimeperiod" type="button" class="btn btn-primary btn-fr approve">
						<i class="fa fa-lock"></i>&nbsp; <?=$lng['Lock period']?>
					</button>
					
					<button <? if($approved){echo 'disabled';}?> type="button" id="approveBut" class="btn btn-primary btn-fr"><i class='fa fa-thumbs-up'></i>&nbsp; <?=$lng['Approve']?> </button>
					
					<!--<button <? if($approved){echo 'disabled';}?> id="updateBtn" type="submit" class="btn btn-primary btn-fr">
						<i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?>
					</button>-->
					
					<select onChange="location.href='index.php?mn=44&dh='+this.value" class="btn-fr" style="padding:5px 10px 4px !important; border-radius:3px">
						<option <? if($_GET['dh'] == 'hrs'){echo 'selected';}?> value="hrs"><?=$lng['Hours']?></option>
						<option <? if($_GET['dh'] == 'days'){echo 'selected';}?> value="days"><?=$lng['Days']?></option>
					</select>
					
				</div>

				<table id="datatable" class="dataTable hoverable selectable nowrap compact" style="clear:both">
					<thead>
						<tr>
							<th colspan="2"><?=$lng['Employee']?></th>
							<th class="tac" colspan="3"><?=$dayhrs?></th>
							<th class="tac" colspan="4"><?=$lng['Overtime']?> (<?=$dayhrs?>)</th>
							<th class="tac" colspan="2"><?=$lng['Leave unPaid']?> (<?=$dayhrs?>)</th>
							<th class="tac" colspan="2"><?=$lng['Leave Paid']?> (<?=$dayhrs?>)</th>
							<th class="tac" colspan="<?=count($compensations)?>"><?=$lng['Compensations']?></th>
							<? if(array_sum($allow_cols) > 0){ ?>
							<th class="tac" colspan="<?=count($var_allow)?>"><?=$lng['Variable allowances']?></th>
							<? } ?>
						</tr>
						<tr>
							<th class="par30"><?=$lng['ID']?></th>
							<th class="tal par30" style="width:50%"><?=$lng['Employee name']?></th>
							<th class="tar" style="min-width:45px"><?=$lng['Planned']?></th> 
							<th class="tar" style="min-width:45px"><?=$lng['Normal']?></th> 
							<th class="tar" style="min-width:45px"><?=$lng['Paid']?></th> 
							<th class="tac" style="min-width:35px"><?=$lng['OT 1']?></th>
							<th class="tac" style="min-width:35px"><?=$lng['OT 1.5']?></th>
							<th class="tac" style="min-width:35px"><?=$lng['OT 2']?></th>
							<th class="tac" style="min-width:35px"><?=$lng['OT 3']?></th>
							<th class="tac" style="min-width:60px"><?=$lng['Absence']?></th>
							<th class="tac" style="min-width:60px"><?=$lng['Late Early']?></th>
							<th class="tac" style="min-width:60px"><?=$lng['Personal']?></th>
							<th class="tac" style="min-width:60px"><?=$lng['Public']?></th>
							<? foreach($compensations as $k=>$v){
									echo '<th class="tac" style="min-width:30px" data-placement="left" data-toggle="tooltip" title="'.$v['description'].'" data-sortable="false">'.$v['code'].'</th>';
								} ?>
							<? foreach($var_allow as $k=>$v){ if($allow_cols[$k] == 1){
									echo '<th class="tac" style="min-width:60px" data-sortable="false">'.$v.'</th>';
								}} ?>
						</tr>
					</thead>
					<tbody>
					<? if($data){ foreach($data as $key=>$val){ ?>
						<tr>
							<td><?=$val['emp_id']?></td>
							<td><?=$val[$lang.'_name']?></td>
							<td class="tac"><?=$val['planned']?></td>
							<td class="tac"><?=$val['normal']?></td>
							<td class="tac"><?=$val['paid']?></td>
							<td class="tar nopad"><input <?=$inputOT?> type="text" name="<?=$val['id']?>[ot1h]" value="<?=round($val['ot1h'],3)?>"></td>
							<td class="tar nopad"><input <?=$inputOT?> type="text" name="<?=$val['id']?>[ot15h]" value="<?=round($val['ot15h'],3)?>"></td>
							<td class="tar nopad"><input <?=$inputOT?> type="text" name="<?=$val['id']?>[ot2h]" value="<?=round($val['ot2h'],3)?>"></td>
							<td class="tar nopad"><input <?=$inputOT?> type="text" name="<?=$val['id']?>[ot3h]" value="<?=round($val['ot3h'],3)?>"></td>
							<td class="tar nopad"><input <?=$input?> style="width:80px" type="text" name="<?=$val['id']?>[absence]" value="<?=round($val['absence'],3)?>"></td>
							<td class="tar nopad"><input <?=$input?> style="width:80px" type="text" name="<?=$val['id']?>[late_early_unpaid]" value="<?=round($val['late_early_unpaid'],3)?>"></td>
							<td class="tar nopad"><input <?=$input?> style="width:80px" type="text" name="<?=$val['id']?>[personal]" value="<?=round($val['personal'],3)?>"></td>
							<td class="tar nopad"><input <?=$input?> style="width:80px" type="text" name="<?=$val['id']?>[public]" value="<?=round($val['public'],3)?>"></td>
							<? foreach($compensations as $k=>$v){
									echo '<td class="tac">'.$val['comp'.$k].'</td>';
								} ?>
							<? foreach($var_allow as $k=>$v){ if($allow_cols[$k] == 1){
									echo '<td class="tar nopad"><input style="width:80px" '.$input2.' type="text" name="'.$val['id'].'[var_allow_'.$k.']" value="'.round($val['var_allow_'.$k],2).'"></td>';
								}} ?>
						</tr>
					<? }}else{ ?>
						<tr>
							<td colspan="30" class="dataTables_empty"><?=$lng['No data available']?></td>
						</tr>
					 <? } ?>
					</tbody>
				</table>

				<!--</form>-->
			
			</div>
		
		</div>
		
	</div>
		
	<script type="text/javascript">
	
	$(document).ready(function() {
		
		var innerheight = window.innerHeight;
		var aheight = innerheight-382;
		var cid = <?=json_encode($cid)?>;//.replace('x',acc);
		
		function removeFilterClass(){
			$('.month-btn').each(function(){
				$(this).removeClass('activ')
			})
		}
		
		var drows = Math.floor(aheight/25);
		var dtable = $('#datatable').DataTable({
			//scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			//scrollCollapse: false,
			lengthChange:  	false,
			pageLength: 	drows,
			searching: 		true,
			ordering: 		false,
			paging: 		true,
			filter: 		true,
			info: 			false,
			<?=$dtable_lang?>
			columnDefs: [
				  //{targets: [6], class: 'tac' },
				  //{targets: [1], visible: false },
				  //{targets: [5], width: '70%' }
			],
			initComplete : function( settings, json ) {
				//$('#dTable').fadeIn(200);
				//$('body').addClass('loaded');
				//dtable.columns.adjust().draw();
				//atable.fnFilter('<?//=$_SESSION['rego']['cur_month']?>', 1, true);
			}
		});
		//setTimeout(function(){dtable.columns.adjust().draw();},50);
		
		var arows = Math.floor(aheight/25);
		var atable = $('#appTable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        false,
			scrollCollapse: false,
			fixedColumns:   false,
			lengthChange:  	false,
			pageLength: 	arows,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			filter: 		true,
			info: 			false,
			autoWidth:		false,
			processing: 	false,
			serverSide: 	true,
			order: [[0, 'desc']],
			<?=$dtable_lang?>
			ajax: {
				url: "ajax/server_get_approvals_attendance.php",
			},
			columnDefs: [
				  {targets: [6], class: 'tac' },
				  {targets: [1], visible: false },
				  {targets: [5], width: '70%' }
			],
			initComplete : function( settings, json ) {
				//$('#showTable').fadeIn(200);
				//$('body').addClass('loaded');
				//atable.columns.adjust().draw();
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
			
		
		var popOverSettings = {
			placement: 'bottom',
			container: 'body',
			html: true,
			selector: '[data-toggle="popover"]', //Sepcify the selector here
			title: '<span id="pop_title"><?=$lng['Reject comment']?></span>',
			content: '<form id="popForm" class="popReject">'+
				'<input style="visibility:hidden;position:absolute;top:0;right:0;height:0;width:0" type="file" name="attach" id="attachment">'+
				'<input type="hidden" name="emp_id" value="<? //=$_SESSION['rego']['id']?>" />'+
				'<input type="hidden" name="emp_name" value="<?=$_SESSION['rego']['name']?>" />'+
				'<input type="hidden" name="month" value="<?=$_SESSION['rego']['cur_month']?>" />'+
				'<input type="hidden" name="emp_group" value="<? //=$_SESSION['rego']['emp_group']?>" />'+
				'<input type="hidden" name="type" value="PR" />'+
				'<input type="hidden" name="action" value="RJ" />'+
				'<div><textarea placeholder="__" name="comment" rows="5" style="width:100%; border:0; padding:0;resize:vertical;"></textarea></div>'+
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
					url: ROOT+"time/ajax/save_approve_action.php",
					type: "POST", 
					data: data,
					cache: false,
					processData:false,
					contentType: false,
					success: function(result){
						//$("#dump").html(result);
						$("#message").html('<div class="msg_alert nomargin"><?=$lng['Monthly attendance rejected']?></div>').hide().fadeIn(300);
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
			/*$(document).on('click','#reviewBut', function(e) {
				$('#reviewBut i').removeClass('fa-thumbs-up').addClass('fa-refresh fa-spin');
				$.ajax({
					url: ROOT+"time/ajax/save_approve_action.php",
					data: {	emp_id:"<? //=$_SESSION['rego']['id']?>", 
								emp_name:"<?=$_SESSION['rego']['name']?>", 
								month:"<?=$_SESSION['rego']['cur_month']?>", 
								emp_group:"<? //=$_SESSION['rego']['emp_group']?>", 
								type:'TA', 
								action:'RV'
							},
					success: function(result){
						//$("#dump").html(result);
						$("#message").html('<div class="msg_success nomargin"><?=$lng['Monthly attendance reviewed for payroll']?></div>').hide().fadeIn(300);
						setTimeout(function(){$('#reviewBut i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');},1000);
						setTimeout(function(){$("#message").fadeOut(300);},3000);
						atable.ajax.reload(null, false);
						//dtable.api().ajax.reload();
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});*/			
			
			$(document).on('click','#approveBut', function(e) {
				$('#approveBut i').removeClass('fa-thumbs-up').addClass('fa-refresh fa-spin');
				$.ajax({
					url: "ajax/save_approve_action.php",
					data: {	emp_id:"<? //=$_SESSION['rego']['id']?>", 
								emp_name:"<?=$_SESSION['rego']['name']?>", 
								month:"<?=$_SESSION['rego']['cur_month']?>", 
								//emp_group:"<? //=$_SESSION['rego']['emp_group']?>", 
								type:'TA', 
								action:'AP'
							},
					success: function(responce){
						//$("#dump").html(responce); return false;
						if(responce > 0){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;'+responce+' rows are not approved yet in Time attendance',
								duration: 4,
							})
						}else if(responce == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Monthly attendance approved for payroll']?>',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : ' + responce,
								duration: 4,
							})
						}
						setTimeout(function(){$('#approveBut i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');},1000);
						//$("#dump").html(result); return false;
						atable.ajax.reload(null, false);
						//$("#sendToPayroll").prop('disabled', false)
						//$("#updateBtn").prop('disabled', true)
						//$("#payrollForm input").prop('readonly', true)
						//$("#rejectBut").prop('disabled', true)
						//$("#approveBut").prop('disabled', true)
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
							closeConfirm: true
						})
						setTimeout(function(){$('#approveBut i').removeClass('fa-refresh fa-spin').addClass('fa-thumbs-up');},500);
					}
				});
			});			
		
		$('#payrollForm').on('submit', function(e) {
			e.preventDefault();
			$('#updateBtn i').removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				url: "ajax/update_monthly_attendance.php",
				type: "POST", 
				data: data,
				success: function(data){
					//$('#dump').html(data); return false;
					if(data == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data,
							duration: 4,
						})
					}
					setTimeout(function(){$('#updateBtn i').removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : ' + thrownError,
						duration: 4,
					})
					setTimeout(function(){$('#sendToPayroll i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');},500);
				}
			});
		})
		
		$('#sendToPayroll').on('click', function(e) {
			e.preventDefault();
			$('#sendToPayroll i').removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			$.ajax({
				url: "ajax/send_attendance_to_payroll.php",
				type: "POST", 
				success: function(data){
					//$('#dump').html(data); return false;
					if(data == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data send to payroll successfully']?>',
							duration: 2,
						})
					}else if(data == 'empty'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Payroll for this month has not been created yet']?>',
							duration: 4,
						})
					}else if(data == 'locked'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Payroll for this month is locked already']?>',
							duration: 4,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data,
							duration: 4,
						})
					}
					setTimeout(function(){$('#sendToPayroll i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : ' + thrownError,
						duration: 4,
					})
					setTimeout(function(){$('#sendToPayroll i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');},500);
				}
			});
		})
		

		//if(data == 1){
			//var rows = <?//=count($data)?>;
			//var scrY = true;
			//if(rows>20){scrY = heights}
	//}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			//if($(e.target).data('target') == '#tab_overview'){headerCount = 1;}else{headerCount = 2;}
			atable.columns.adjust().draw();
			//dtable.columns.adjust().draw();
			$('body [data-toggle="popover"]').popover('hide');
			//$('#calendar').fullCalendar('render');
		});
		//var activeTab = localStorage.getItem('activeTab');
		//if(activtab){
			//$('#myTab a[data-target="#tab_request"]').tab('show');
		//}
		
		$('.autoheight').css('min-height', innerheight-240);
		$(window).on('resize', function(){
			var innerheight = window.innerHeight;
			$('.autoheight').css('min-height', innerheight-240)
		});	
			
		$("#tableTab"+parseInt(<?=$_SESSION['rego']['cur_month']?>)).trigger('click');
		
	})
	
	</script>

































