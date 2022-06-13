<?php
	

	
	$leave_typess = getLeaveTypes($cid);

	// 

	foreach ($leave_typess as $keysss => $valuess) {
		# code...

		if($valuess['emp_request'] == '1')
		{
			$leave_types[$keysss] = $valuess;
			$leave_types1[$keysss] = $valuess;
		}
	}

	// get leave period on the basis of the selected year   
	$getCurrentDateForQuery = date('Y-m-d');
	$res20009 = $dbc->query("SELECT * FROM ".$cid."_leave_periods WHERE DATE('".$getCurrentDateForQuery."') BETWEEN DATE(leave_period_start) AND DATE(leave_period_end)"); 
	if($row20009 = $res20009->fetch_assoc()){

		$periodStartDate9 = $row20009['leave_period_start'];
		$periodEndDate9   = $row20009['leave_period_end'];

	}

	$currentyearValue = '%'.$_SESSION['rego']['mob_year'].'%';

	$getLeaveReqBefore = getLeaveReqBefore($cid);
	$pending = array();
	$approved = array();
	$history = array();

	$res = $dbc->query("SELECT * FROM ".$cid."_leaves WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND (DATE(start) BETWEEN '".$periodStartDate9."' AND '".$periodEndDate9."' OR DATE(end) BETWEEN '".$periodStartDate9."' AND '".$periodEndDate9."') ORDER BY id DESC LIMIT 10"); 
	while($row = $res->fetch_assoc()){
		if($row['status'] == 'RQ'){
			$pending[$row['id']]['leave_type'] = $row['leave_type'];
			$pending[$row['id']]['days'] = $row['days'];
			$pending[$row['id']]['start'] = date('d-m-Y', strtotime($row['start']));
			$pending[$row['id']]['end'] = date('d-m-Y', strtotime($row['end']));
			$pending[$row['id']]['status'] = $row['status'];
			$pending[$row['id']]['created'] = $row['created'];
		}elseif ($row['status'] == 'AP'){	
			$approved[$row['id']]['leave_type'] = $row['leave_type'];
			$approved[$row['id']]['days'] = $row['days'];
			$approved[$row['id']]['start'] = date('d-m-Y', strtotime($row['start']));
			$approved[$row['id']]['end'] = date('d-m-Y', strtotime($row['end']));
			$approved[$row['id']]['status'] = $row['status'];
			$approved[$row['id']]['created'] = $row['created'];
		}else{	
			$history[$row['id']]['leave_type'] = $row['leave_type'];
			$history[$row['id']]['days'] = $row['days'];
			$history[$row['id']]['start'] = date('d-m-Y', strtotime($row['start']));
			$history[$row['id']]['end'] = date('d-m-Y', strtotime($row['end']));
			$history[$row['id']]['status'] = $row['status'];
			$history[$row['id']]['created'] = $row['created'];
		}
	}
	//var_dump($history); exit;
	$status_color = array('RQ'=>'bg-blue-light','CA'=>'bg-yellow-dark','AP'=>'bg-green-dark','RJ'=>'bg-red-light','TA'=>'bg-night-light');
	if($lang == 'en'){
		$leave_status['RQ'] = 'Pending';
	}else{
		$leave_status['RQ'] = 'อยู่ระหว่างดำเนินการ';
	}


	$emp_id_value = $_SESSION['rego']['emp_id'];



	// echo '<pre>';
	// print_r($leave_types);
	// echo '</pre>';
	// exit;

	function getLeaveTimeSettingsss(){
		global $dbc;
		$row = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}

	function getUsedLeaveEmployeess($cid, $id, $balance){
		global $dbc;
		$res = $dbc->query("SELECT * FROM ".$cid."_leaves_data WHERE emp_id = '".$id."'"); 
		while($row = $res->fetch_assoc()){
			if($row['status'] == 'RQ' || $row['status'] == 'AP'){
				$balance[$row['leave_type']]['pending'] += $row['days'];
			}elseif($row['status'] == 'TA'){
				$balance[$row['leave_type']]['used'] += $row['days'];
			}
		}

		// echo '<pre>';
		// print_r($balance);
		// echo '</pre>';
		return $balance;
	}

	function getALemployeess($cid, $id){
		global $dbc;
		$data = 0;
		$res = $dbc->query("SELECT annual_leave FROM ".$cid."_employees WHERE emp_id = '".$id."'");
		if($row = $res->fetch_assoc()){
			$data = $row['annual_leave'];
		}
		return $data;
	}


	function getALemployeeOtherss($cid, $id, $cur_year){
		global $dbc;
		$data = 0;
		$res = $dbc->query("SELECT annual_leave FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$id."' AND year = '".$cur_year."' ");
		if($row = $res->fetch_assoc()){
			$data = $row['annual_leave'];
		}
		else
		{
			$res1 = $dbc->query("SELECT annual_leave FROM ".$cid."_employees WHERE emp_id = '".$id."'");
			if($row1 = $res1->fetch_assoc()){
				$data = $row1['annual_leave'];
			}
		}
		return $data;
	}





	$leave_time_settings = getLeaveTimeSettingsss();
	$leave_typess = unserialize($leave_time_settings['leave_types']);





	foreach($leave_typess as $k=>$v){

		// if(($v['activ'] == 1) || ($v['emp_request'] == 1)  || ($v['emp_request'] == 0 && $v['bab_request'] == 'after'))
		// if($v['activ'] == 1)
		// {
			$balance[$k] = array('activ' => $v['activ'],'th'=>$v['th'], 'en'=>$v['en'], 'maxdays'=>$v['max'][$_SESSION['rego']['emp_group']], 'maxpaid'=>$v['pay'][$_SESSION['rego']['emp_group']], 'pending'=>0, 'used'=>0);

		// }

	}
	$ALemp = getALemployeess($cid, $_SESSION['rego']['emp_id']);

	// $ALemp = getALemployeeOtherss($cid, $_SESSION['rego']['emp_id'],$_SESSION['rego']['year_en']);

	// $balance['AL']['maxdays'] = $ALemp;
	// $balance = getUsedLeaveEmployeess($cid, $_SESSION['rego']['emp_id'], $balance);
	//var_dump($ALemp); exit;	


	$currentyear = '%'.$_SESSION['rego']['year_en'].'%';

	// $ALemp = getALemployee($cid, $_REQUEST['emp_id']);

	$previousYear = $_SESSION['rego']['year_en'] -1 ;


		// if annual leave carry forward is yes then add AL condition here 

	// $sqlGetCarryForawrdAL= "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$_SESSION['rego']['emp_id']."'  AND year = '".$previousYear."'";
	// if($resSqlGetCarryForawrdAL = $dbc->query($sqlGetCarryForawrdAL)){
	// 	if($rowSqlGetCarryForawrdAL = $resSqlGetCarryForawrdAL->fetch_assoc()){
	// 		$carryForward = unserialize($rowSqlGetCarryForawrdAL['other_fields']);
	// 	}
	// }



	// if($carryForward['leaveForwardorNot'] == '1')
	// {
	// 	// carry forward annual leave balance 

	// 	// get annual leave balance 

	// 	$sqlGetAlBAl= "SELECT * FROM ".$cid."_employee_per_year_records WHERE emp_id = '".$_SESSION['rego']['emp_id']."' AND year = '".$previousYear."'";



	// 	// die();
	// 	if($resSqlGetALBal = $dbc->query($sqlGetAlBAl)){
	// 		if($rowSQlgEtBal = $resSqlGetALBal->fetch_assoc()){
	// 			$annualLeaveBal  = $rowSQlgEtBal['annual_leave'];
	// 		}
	// 	}






	// 	if($annualLeaveBal)
	// 	{
	// 		$balance['AL']['maxdays'] = $annualLeaveBal;
	// 	}
	// 	else
	// 	{
	// 		$balance['AL']['maxdays'] = $ALemp;

	// 	}


	// }
	// else
	// {
		// do not carry forward 
		$balance['AL']['maxdays'] = $ALemp;

	// }
	






	// $balance = getUsedLeaveEmployee($cid, $_REQUEST['emp_id'], $balance);
	$balance = getUsedLeaveEmployeeWithBal($cid, $_SESSION['rego']['emp_id'], $balance, $currentyear, $_SESSION['rego']['year_en']);


	// echo '<pre>';
	// print_r($balance);
	// echo '</pre>';

	// die();

	$maxdaysAL= $balance['AL']['maxdays'];
	$maxpaidAL= $balance['AL']['maxpaid'];
	$pendingAL= $balance['AL']['pending'];
	$usedAL	  = $balance['AL']['used'];

	$maxdaysAU= $balance['AU']['maxdays'];
	$maxpaidAU= $balance['AU']['maxpaid'];
	$pendingAU= $balance['AU']['pending'];
	$usedAU	  = $balance['AU']['used'];




	$balance['AU']['maxdays']=  $maxdaysAL;
	$balance['AU']['maxpaid']= $maxpaidAL;
	$balance['AU']['pending']= $pendingAU + $pendingAL;
	$balance['AU']['used']  =  $usedAU + $usedAL;	
	$balance['AL']['maxdays']= $maxdaysAL;
	$balance['AL']['maxpaid']= $maxpaidAL;
	$balance['AL']['pending']= $pendingAU + $pendingAL;
	$balance['AL']['used']  =  $usedAU + $usedAL;	



	$maxdaysSL= $balance['SL']['maxdays'];
	$maxpaidSL= $balance['SL']['maxpaid'];
	$pendingSL= $balance['SL']['pending'];
	$usedSL	  = $balance['SL']['used'];

	$maxdaysSN= $balance['SN']['maxdays'];
	$maxpaidSN= $balance['SN']['maxpaid'];
	$pendingSN= $balance['SN']['pending'];
	$usedSN	  = $balance['SN']['used'];	

	$totSickpending		= $pendingSL + $pendingSN;
	$totSickused	 	= $usedSL    + $usedSN;


	$balance['SN']['maxdays']= $maxdaysSL;
	$balance['SN']['maxpaid']= $maxpaidSL;
	$balance['SN']['pending']= $totSickpending;
	$balance['SN']['used']  =  $totSickused;

	$balance['SL']['maxdays']= $maxdaysSL;
	$balance['SL']['maxpaid']= $maxpaidSL;
	$balance['SL']['pending']= $totSickpending;
	$balance['SL']['used']  =  $totSickused;


	// UNSET AU AND SN AND COMBINE TEXT IN ONE LINE 

	// GET AU AND SN TEXTS AND ADD THEM TO AL AND SL RESPECTIVELY

	$auEngText = $balance['AU']['en']; // AU
	$auThaiText = $balance['AU']['th']; // AU

	$alEngText = $balance['AL']['en']; // AL
	$alThaiText = $balance['AL']['th']; // AL

	$snEngText = $balance['SN']['en']; // SN
	$snThaiText = $balance['SN']['th']; // SN

	$slEngText = $balance['SL']['en']; // SL
	$slThaiText = $balance['SL']['th']; // SL

	$balance['AL']['en'] = $alEngText .' + '.$auEngText;
	$balance['AL']['th'] = $alThaiText .' + '.$auThaiText;

	$balance['SL']['en'] = $slEngText .' + '.$snEngText;
	$balance['SL']['th'] = $slThaiText .' + '.$snThaiText;

	unset($balance['AU']);
	unset($balance['SN']);



	foreach ($balance as $key_100 => $value_100) 
	{
		if($value_100['activ'] == '1')
		{
			$balances[$key_100] =$value_100;
		}		
		
	}


	$table = '	
		<table class="table basicTable compact" border="0">
			<thead>
				<th style="width:70%">'.$lng['Leave type'].'</th>
				<th class="tac paddingclass">'.$lng['Entitled'].'</th>
				<th class="tac paddingclass">'.$lng['Taken'].'</th>
				<th class="tac paddingclass">'.$lng['Pending'].'</th>
				<th class="tac paddingclass">'.$lng['Balance'].'</th>
			</thead>
			<tbody>';
			foreach($balances as $k=>$v){

				if($k == 'AL')
				{
					$newkey = 'AL + AU' ;
				}
				else if($k == 'SL')
				{
					$newkey = 'SL + SN' ;
				}
				else
				{
					$newkey = $k;
				}
				$bal = $v['maxdays'] - $v['used'] - $v['pending'];

				$balss = round($v['maxdays'],2) - (number_format($v['used'],2) + number_format($v['pending'],2) );

				$userformat= number_format($v['used'],2) ;

				if($userformat == '0.00')
				{
					$usedFormatVal = '0';
				}
				else
				{
					$str_arr_used = explode('.',$userformat);
					$usedBefore = $str_arr_used[0]; // value before decimal  
					$usedAfter  = $str_arr_used[1]; // value after decimal 

					if($usedAfter)
					{
						if($usedAfter == '00' || $usedAfter == '000')
						{
							$usedFormatVal = $usedBefore; // value without zero 
						}
						else
						{
							$usedFormatVal = $userformat; // value with zero 
						}
					}

				}			

				$pendformat= number_format($v['pending'],2) ;

				if($pendformat == '0.00')
				{
					$pendFormatVal = '0';
				}
				else
				{	

					$str_arr_pen = explode('.',$pendformat);
					$penBefore = $str_arr_pen[0]; // value before decimal  
					$penAfter  = $str_arr_pen[1]; // value after decimal 

					if($penAfter)
					{
						if($penAfter == '00' || $penAfter == '000')
						{
							$pendFormatVal = $penBefore; // value without zero 
						}
						else
						{
							$pendFormatVal = $pendformat; // value with zero 
						}
					}

				}


				$table .= '<tr>
						<td style="width:70%;">'.$v[$_SESSION['rego']['lang']].' ('.$newkey.')</td>
						<td class="tac paddingclass">'.round($v['maxdays'],2).'</td>
						<td class="tac paddingclass';
						if($v['used'] != '0'){$table .= 'strong';}
						$table .= '">'.$usedFormatVal.'</td>
						<td class="tac ';
						if($v['pending'] != '0'){$table .= 'strong';}
						$table .= '">'.$pendFormatVal.'</td>
						<td class="tac paddingclass"><b style="';
						if($bal<1)
						{
							$table .= 'color:#c00';
						}
						else
						{
							$table .= 'color:#393';
						}
				$table .= '">'.$balss.'</b></td>
					</tr>';
			}
				
	$table .= '</tbody></table>';


// echo '<pre>';
// print_r($leave_types);
// echo '</pre>';
// die();

	
?>	
<style type="text/css">
	.fade:not(.show) {
    display: none;
}
.fade {
    display: contents;
}
.paddingclass{
	padding-left: 10px;
	padding-right: 10px;
}

</style>

	<ul class="nav nav-tabs lined" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#leaverequestab" role="tab"><?=$lng['Request a Leave']?></a>
		</li>
		<li class="nav-item">
			<a onclick="showbalanceleave();" class="nav-link" data-toggle="tab" href="#leavebalancetab" role="tab"><?=$lng['Leave balance']?></a>
		</li>
	</ul>

	<div class="tab-pane fade show active" id="leaverequestab" role="tabpanel">
			<div class="container-fluid" style="xborder:1px solid red">
		
			<div class="row" style="xborder:1px solid green; padding:10px 4px">
			<form id="requestForm" style="height:100%; position:relative">
				<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
				<!--<input name="name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
				<input name="phone" type="hidden" value="<?=$_SESSION['rego']['phone']?>" />-->
				<input name="leave_type" type="hidden" />
				<input style="visibility:hidden; height:0; position:absolute" id="certificate" type="file" name="attach" />
			
				<button data-toggle="modal" data-target="#leavetypeModal" type="button" class="btn btn-danger btn-block"><span id="btn_leavetype"><?=$lng['Select Leave type']?></span></button>
				
				<div style="float:left; width:50%; padding:10px 5px 10px 0">
					<button data-toggle="modal" data-target="#startModal" type="button" class="btn btn-info btn-block"><span id="leavestart"><i class="fa fa-calendar"></i><?=$lng['Leave start']?></span></button>
				</div>
				
				<div style="float:right; width:50%; padding:10px 0 10px 5px">
					<button type="button" class="btn btn-info btn-block"><span id="leaveend"><i class="fa fa-calendar"></i><?=$lng['Leave end']?></span></button>
				</div>
				<div class="clear"></div>
			
				<div id="rangeTable" style="xborder:1px red solid">
					<table class="table-bordered text-center" style="background:#fff; table-layout:fixed; margin:0; width:100%">
						<tbody>
							<tr>
								<td style="padding:0">00-00-0000</td>
								<td style="padding:1px">
									<button class="btn btn-info btn-block" type="button"><span class="day1"><?=$lng['Full day']?></span></button>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
		
				<div style="border:1px solid #ddd; padding:0px 0 0 10px; margin:10px 0; background:#fff; clear:both">
					<i><?=$lng['Reason']?> / <?=$lng['Note']?></i>
					<textarea required="required" style="display:block; border:0; padding:0 10px 5px 0; width:100%; border:0; resize:vertical" rows="3" name="reason"></textarea>
				</div>
		
				<button onclick="$('#certificate').click()" type="button" style="color:#fff; margin-bottom:2px" class="button btn btn-info btn-block"><?=$lng['Attachement']?>&nbsp;:&nbsp;<span style="font-size:13px" id="attachMsg"><?=$lng['No file selected']?></span></button>
				
				<button id="submitBtn" type="submit" class="button btn btn-default btn-block"><i class="fa fa-paper-plane"></i><?=$lng['Submit request']?></button>
				
				<div id="requestMsg" class="bg-yellow-dark" style="font-size:16px; text-align:center; margin:10px 0 0 0; padding:5px 10px; display:none; color:#fff"></div>
				
				<div id="dump"></div>
			
			</form>
			
			 
			</div>
			
			<div style="height:10px"></div>
			
			<ul class="nav nav-tabs lined" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#pending" role="tab"><?=$lng['Pending']?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#approved" role="tab"><?=$lng['Approved']?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#history" role="tab"><?=$lng['History']?></a>
				</li>
			</ul>
			<div class="tab-content mt-2">
					<div class="tab-pane fade show active" id="pending" role="tabpanel">
						<? if($pending){ ?>
						<div class="timeline timed">
						<? foreach($pending as $k=>$v){ 
							if($v['days'] == 1){$d = $lng['day'];}else{$d = $lng['days'];}
							?>
								<div class="item">
										<span class="time"><?=substr($v['created'],0,10)?></span>
										<div class="dot <?=$status_color[$v['status']]?>"></div>
										<div class="content">
												<h4 class="title"><?=$leave_types1[$v['leave_type']][$lang]?> - <?=$v['days']?> <?=$d?></h4>
												<div class="text"><?=date('D d-m-Y', strtotime($v['start']))?> - <?=date('D d-m-Y', strtotime($v['end']))?></div>
												<div class="text"><?=$lng['Status']?> : <?=$leave_status[$v['status']]?></div>
										</div>
								</div>
						<? } ?>
						</div>
						<? }else{ ?>
								 <div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999"><?=$lng['No data available']?></div>
						<? } ?>
					</div>
					<div class="tab-pane fade" id="approved" role="tabpanel">
						<? if($approved){ ?>
						<div class="timeline timed">
						<? foreach($approved as $k=>$v){ ?>
								<div class="item">
										<span class="time"><?=substr($v['created'],0,10)?></span>
										<div class="dot <?=$status_color[$v['status']]?>"></div>
										<div class="content">
												<h4 class="title"><?=$leave_types1[$v['leave_type']][$lang]?> - <?=$v['days']?> days</h4>
												<div class="text"><?=date('D d-m-Y', strtotime($v['start']))?> - <?=date('D d-m-Y', strtotime($v['end']))?></div>
												<div class="text"><?=$lng['Status']?> : <?=$leave_status[$v['status']]?></div>
										</div>
								</div>
						<? } ?>
						</div>
						<? }else{ ?>
								 <div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999; text-align:center"><?=$lng['No data available']?></div>
						<? } ?>
					</div>
					<div class="tab-pane fade" id="history" role="tabpanel">
						<? if($history){ ?>
						<div class="timeline timed">
						<? foreach($history as $k=>$v){ ?>
								<div class="item">
										<span class="time"><?=substr($v['created'],0,10)?></span>
										<div class="dot <?=$status_color[$v['status']]?>"></div>
										<div class="content">
												<h4 class="title"><?=$leave_types1[$v['leave_type']][$lang]?> - <?=$v['days']?> days</h4>
												<div class="text"><?=date('D d-m-Y', strtotime($v['start']))?> - <?=date('D d-m-Y', strtotime($v['end']))?></div>
												<div class="text"><?=$lng['Status']?> : <?=$leave_status[$v['status']]?></div>
										</div>
								</div>
						<? } ?>
						</div>
						<? }else{ ?>
								 <div style="padding:0; margin:-10px 0 0; font-size:13px; color:#999; text-align:right"><?=$lng['No data available']?></div>
						<? } ?>
					</div>
			</div>
			
			<div style="height:60px"></div>
									
									
		</div>

	</div>

	<div class="tab-pane fade show active" id="leavebalancetab" role="tabpanel" style="display:none;">
		<div style="overflow: auto;">
			<?php echo $table;?>
		</div>

	</div>



	
	
	<!-- Modal -->
	<div class="modal fade" id="leavetypeModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="color:#333">
					<div class="list-group">
						<? foreach($leave_types1 as $k=>$v){ if(($v['emp_request'] == 1)  || ($v['emp_request'] == 0 && $v['bab_request'] == 'after')){?>
							<a onclick="checkLeaveAvail('<?php echo $k?>');" data-dismiss="modal" class="myList selLeaveType list-group-item" data-id="<?=$k?>"><?=$v[$lang]?></a>
						<? }} ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade my-modal" id="startModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Leave start']?></h5>
				</div>
				<div class="modal-body" style="color:#333">
					<div class="list-group">
						<div id="startPicker"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade my-modal" id="endModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Leave end']?></h5>
				</div>
				<div class="modal-body" style="color:#333">
					<div class="list-group">
						<div id="endPicker"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="dayModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body" style="color:#333">
					<div class="list-group">

						<a data-dismiss="modal" class="myList selDayType list-group-item" data-id="full"><?=$lng['Full day']?></a>
						<a data-dismiss="modal" class="myList selDayType list-group-item" data-id="first"><?=$lng['First half']?></a>
						<a data-dismiss="modal" class="myList selDayType list-group-item" data-id="second"><?=$lng['Second half']?></a>
						<a class="myList list-group-item" style="padding:0 !important; background:#fff !important">
							<table style="width:100%; border-collapse:collapse" border="0">
								<tr>
									<td style="width:50%; padding:5px">
									<input id="time_from" placeholder="<?=$lng['From']?> 00:00" style="font-size:16px; cursor:pointer; background:#fff !important; border:1px solid #ddd; width:100%; padding:5px" class="timePic tac" readonly type="text" />
									</td>
									<td style="width:50%; padding:5px">
									<input id="time_until" disabled placeholder="<?=$lng['Until']?> 00:00" style="font-size:16px; cursor:pointer; background:#fff !important; border:1px solid #ddd; width:100%; padding:5px" class="timePic tac" readonly type="text" />
									</td>
								</tr>
							</table>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	

<script> 



	function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}


	function addDays(leave_type) {

	  var value = readCookie('leave_type');
	  // var leaveType = $("input[name=leave_type]").val();
	  // console.log(leaveType);

	  var arrayFromPHP = <?php echo json_encode($leave_types1) ?>;



	  // arrayFromPHP["AB"]["request_leave"] = "";

	  var test= arrayFromPHP[leave_type]["request_leave"];

	  if(test == null)
	  {
		  	var result = new Date();
		  	result.setDate(result.getDate() );
	  }
	  else
	  {
	  		var days = parseInt(test);
		 	var result = new Date();
			result.setDate(result.getDate() + days + 1);
	  }

	  // var days = 2;

	  console.log(result);
	  
	  return result;


	}	

	function addDays2(leave_type) {

	  var value = readCookie('leave_type');
	  // var leaveType = $("input[name=leave_type]").val();
	  // console.log(leaveType);

	  var arrayFromPHP = <?php echo json_encode($leave_types1) ?>;


	  // arrayFromPHP["AB"]["request_leave"] = "";

	  var test= arrayFromPHP[leave_type]["request_leave"];
	  var bothvalue = arrayFromPHP[leave_type]["bab_request"];

	  var results =[] ;
	  if(test == null)
	  {
		  	results.push("setmaxdatevalue");
		  	results.push(bothvalue);

	  }
	  else
	  {
		  	results.push("donotsetmaxdatevalue");
		  	results.push(bothvalue);


	  }


	  
	  return results;


	}


	$(document).ready(function() {
		
		function readAttURL(input) {
		  if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					var fileExtension = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
					var ext = input.files[0].name.split('.').pop();
					if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
						alert('Use only '+fileExtension+' files')
						$('#attachMsg').html('<?=$lng['No file selected']?>');
					}else{				
						$('#attachMsg').html(input.files[0].name);
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		};
		
		var emp_id = <?=json_encode($_SESSION['rego']['emp_id'])?>;
		var leaveTypes = <?=json_encode($leave_types1)?>;
		
		var startDate;
		var endDate;
		
		$('#certificate').on('change', function(){ 
			readAttURL(this);
		});
		$(document).on('submit', '#requestForm', function(e){
			e.preventDefault();
			var err = 0;
			$('#requestMsg').html('').hide();
			if($('input[name="leave_type"]').val() == ''){$('#requestMsg').html('<?=$lng['Select Leave type']?>').fadeIn(400); return false;}
			if($('input[name="date[0]"]').val() == null){$('#requestMsg').html('<?=$lng['Select Start & End date']?>').fadeIn(400); return false;}
			//alert($('input[name="date[0]"]').val())
			$('#submitBtn i').removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			//$('#submitBtn').prop('disabled', true);
			var formData = new FormData($(this)[0]);
			//alert(formData);
			$.ajax({
				url: "ajax/save_leave_request.php",
				data: formData,
				type: "POST", 
				cache: false,
				processData:false,
				contentType: false,
				success: function(result){
					//alert(result);
					// return false;
					if(result == 'error'){

						$('#requestMsg').html('<?=$lng['Error']?>: Leave already requested').fadeIn(400);
						setTimeout(function(){
							$('#submitBtn i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
						},500);

					}else{
					//$('#dump').html(result); return false;
						$('#requestMsg').html('<?=$lng['Request send successfully']?>').fadeIn(400);
						setTimeout(function(){
							$('#submitBtn i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
						},500);
					}
					//$('.modalTable').addClass('payslipTable table-bordered table-striped wrapnormal').removeClass('modalTable');
					//$('#showTable').fadeIn(200);

					setTimeout(function(){
						location.reload();
					},2000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#requestMsg').html('<?=$lng['Error']?>: ' + thrownError).fadeIn(400);
					$('#submitBtn').prop('disabled', false);
				}
			});
		})

		$(document).on('click', '.selLeaveType', function(e){
			var id = $(this).data('id');
			//alert(id);
			
			$('#btn_leavetype').html($(this).text());
			$('input[name="leave_type"]').val(id);

			var min_request = leaveTypes[id]['min_request']
			if(min_request == 'half'){

				$('#time_from').attr('disabled',true);
			}else{
				$('#time_from').attr('disabled',false);
			}


		})

		var dayType;
		$(document).on('click', '.dayType', function(e){
			dayType = $(this).data('id');
			$('#dayModal').modal('toggle');
		})
		$(document).on('click', '.selDayType', function(e){
			var id = $(this).data('id');
			$('.day'+dayType).html($(this).text());
			$('#mday'+dayType).val(id);
		})
		$(document).on('change', '#time_until', function(e){
			var hours = $('#time_from').val() + ' - ' + $(this).val();
			$('.day'+dayType).html(hours);
			$('#mday'+dayType).val(hours);
			$('#dayModal').modal('toggle');
		})
		
		

		$(document).on('focus',"#time_from", function(){
			$(this).clockpicker({
				autoclose: true,
				placement: 'bottom',
				align: 'left',
				afterDone: function() {
					$('#time_until').prop('disabled', false);
					$('#time_until').focus();
				}
			});
		});			
		$(document).on('focus',"#time_until", function(){
			$(this).clockpicker({
				autoclose: true,
				placement: 'bottom',
				align: 'right',
				afterDone: function() {
					$('#time_until').trigger("change");
				}
			});
		});			


	})

	function checkLeaveAvail(leave_type)
	{

		// check leave space if avaialble then allow other wise set to select option default 
		// make cookie of leave type 
		document.cookie = "leave_type="+leave_type; 


		var afterbefore = addDays2(leave_type);

		console.log(afterbefore[0]);
		console.log(afterbefore[1]);

		if(afterbefore[0] == 'donotsetmaxdatevalue' && afterbefore[1] != 'both') 
		{

			$("#startPicker").datepicker("destroy");
			$("#endPicker").datepicker("destroy");
			$('#startPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
				//startDate: new Date(),
				startDate: addDays(leave_type),
			}).on('changeDate', function(e){
				startDate = e.format();
				$('#startModal').modal('toggle');
				$('#leavestart').html(e.format());
				$('#enddate').val(e.format());
				$('#endPicker').datepicker('setStartDate', e.format());
				$('#endPicker').datepicker('setDate', e.format());
				//alert(e.format())
			
			});	
			$('#endPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
			}).on('changeDate', function(e){
				endDate = e.format();
				$('#leaveend').html(e.format());
				$('#endModal').modal('toggle');
				$('#enddate').val(e.format());
				$.ajax({
					url: "ajax/get_leave_range.php",
					data: {startDate: startDate, endDate: endDate},
					success: function(result){
						//alert(result);
						$('#rangeTable').html(result); return false;
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			
			});
		}
		else if(afterbefore[0] == 'setmaxdatevalue' && afterbefore[1] != 'both')
		{
			$("#startPicker").datepicker("destroy");
			$("#endPicker").datepicker("destroy");
			$('#startPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
				//startDate: new Date(),
				endDate: addDays(leave_type),
			}).on('changeDate', function(e){
				startDate = e.format();
				$('#startModal').modal('toggle');
				$('#leavestart').html(e.format());
				$('#endModal').modal('toggle');

				//alert(e.format())
			
			});	
			$('#endPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
				endDate: addDays(leave_type),
			}).on('changeDate', function(e){
				endDate = e.format();
				$('#leaveend').html(e.format());
				$('#endModal').modal('toggle');
				$('#enddate').val(e.format());
				$.ajax({
					url: "ajax/get_leave_range.php",
					data: {startDate: startDate, endDate: endDate},
					success: function(result){
						//alert(result);
						$('#rangeTable').html(result); return false;
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			
			});
		}
		else if((afterbefore[0] == 'setmaxdatevalue' || afterbefore[0] == 'donotsetmaxdatevalue') && (afterbefore[1] == 'both'))
		{
			$("#startPicker").datepicker("destroy");
			$("#endPicker").datepicker("destroy");
			$('#startPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
				//startDate: new Date(),
				setDate: new Date(),
			}).on('changeDate', function(e){
				startDate = e.format();
				$('#startModal').modal('toggle');
				$('#leavestart').html(e.format());
				$('#enddate').val(e.format());
				$('#endPicker').datepicker('setStartDate', e.format());
				$('#endPicker').datepicker('setDate', e.format());
				//alert(e.format())
			
			});	
			$('#endPicker').datepicker({
				autoclose: true,
				format: 'D dd-mm-yyyy',
				language: '<?=$lang?>',
			}).on('changeDate', function(e){
				endDate = e.format();
				$('#leaveend').html(e.format());
				$('#endModal').modal('toggle');
				$('#enddate').val(e.format());
				$.ajax({
					url: "ajax/get_leave_range.php",
					data: {startDate: startDate, endDate: endDate},
					success: function(result){
						//alert(result);
						$('#rangeTable').html(result); return false;
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			
			});
		}
		
	
	




		var emp_id = '<?php echo $emp_id_value ?>';


		$.ajax({
			url: "ajax/get_leave_balance_mob.php",
			data: {emp_id: emp_id},
			success: function(result){

				var data = JSON.parse(result);

				 $.each(data, function(index, value) {


						if(leave_type == index)
						{

							var balance = value.maxdays -value.used - value.pending ;

							if(balance > 0)
							{
								// accept leave
								$('#submitBtn').prop("disabled", false);
								$('#requestMsg').html('');
								$('#requestMsg').fadeOut();
							}
							else
							{
								$('#submitBtn').prop("disabled", true);
								// give error popup 
								$('#requestMsg').html('Leave balance not sufficient').fadeIn(400);
								setTimeout(function(){
									$('#submitBtn i').removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
								},500);

							}
							
						}


				 });


			},
			error:function (xhr, ajaxOptions, thrownError){
				alert('<?=$lng['Error']?> ' + thrownError);
			}
		});
	}

	function showbalanceleave()
	{
		$('#leavebalancetab').css('display','');
	}
			
	</script>







