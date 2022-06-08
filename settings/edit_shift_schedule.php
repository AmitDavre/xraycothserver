<?php

if(isset($_GET['id'])){
	
	$scheduleArray = array();
	$sql = "SELECT * FROM ".$cid."_shiftplans_".$cur_year. " WHERE id= '".$_GET['id']."'";
	if($res = $dbc->query($sql)){
			if($row = $res->fetch_assoc()){
				$ss_data = unserialize($row['ss_data']);
				$cyc_data = unserialize($row['cycle_details']);
				$noscanCheck = $ss_data['noscan']; // if 1 then hide tables 
				$starCdate = $ss_data['startdate'];
		}
	}
}


$accept_earlyTimeplan = $ss_data['accept_early'];
$accept_lateTimeplan = $ss_data['accept_late'];






$sql2 = "SELECT * FROM ".$cid."_leave_time_settings  WHERE id= '1'";
if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc()){
			$shiftplan_data = unserialize($row2['shiftplan']);
			$shiftplan_datas = unserialize($row2['shiftplan']);

			$accept_earlyTime = $row2['accept_early'];
			$accept_lateTime = $row2['accept_late'];

			
	}
}

$shiftplan_datas['OFF'] = ['code' => 'OFF','bg' => '#4e7298'];
$shiftplan_datas['PUB'] = ['code' => 'PUB','bg' => '#000000'];


// Get all the keys in the array
$all_keys = array_keys($cyc_data);

// first key and value
$first_key = array_shift($all_keys);


$arraykeys = array_keys($cyc_data);
$second_key = $arraykeys[1];



$getmonthdate = $ss_data['startdate'];

$explodedatevalue = explode('/', $getmonthdate);

$startdatemonth =  $explodedatevalue[1];

$monthsArry =array(

	'01' => 'January', 
	'02' => 'February', 
	'03' => 'March', 
	'04' => 'April', 
	'05' => 'May', 
	'06' => 'June', 
	'07' => 'July', 
	'08' => 'August', 
	'09' => 'September', 
	'10' => 'October', 
	'11' => 'November', 
	'12' => 'December', 
);

$startdatemonthplus1 = $startdatemonth + 1;  
foreach ($monthsArry as $key111 => $value11) {
	# code...

	if($key111 == $startdatemonth)
	{
		$newdata = $cyc_data[$value11];
	}

	if($key111 == $startdatemonthplus1 && $startdatemonthplus1 <= 12)
	{
		$newdata2 = $cyc_data[$value11];
	}
}




foreach ($newdata as $key => $value)
{
	foreach ($shiftplan_datas as $key2 => $value2) {
		if($value2['code'] ==  strtoupper($value['s1']))
		{

			$rowsss[] = array('title' => strtoupper($value['s1']), 'start' => $value['date'],  'end' => $value['date'],'color' =>  $value2['bg'] );
		}
	}

}


if($startdatemonthplus1 == '12')
{

foreach ($newdata2 as $key => $value2)
{
	foreach ($shiftplan_datas as $key2 => $value3) {
		if($value3['code'] == strtoupper($value2['s1']))
		{

			$rowssss[] = array('title' => strtoupper($value2['s1']), 'start' => $value2['date'],  'end' => $value2['date'],'color' =>  $value3['bg'] );
		}
	}

}

$newArrayDates = array_merge($rowsss, $rowssss);
}

else
{
	$newArrayDates =$rowsss;
}


$DateSliced = array_slice($newArrayDates, 0, 42);



$dataArray= $scheduleArray[$_GET['id']];

// RUN QUERY TO GET THE DEFAULT ACCEPTED EARLY TIME AND ACCEPTED LATE TIME






	$sql111 = "SELECT * FROM ".$cid."_leave_time_settings";
	if($res111 = $dbc->query($sql111)){
		if($row111 = $res111->fetch_assoc()){
			$data111['accept_late'] = $row111['accept_late'];
			$data111['accept_early'] = $row111['accept_early'];
			$data111['ot_start_after'] = $row111['ot_start_after'];
			$data111['default_normal_hrs'] = $row111['default_normal_hrs'];
			
		}
	}


	 $acceptearlyDefault = '00:'.$data111['accept_early'] ; 
	 $acceptLateDefault = '00:'.$data111['accept_late'] ; 

?>
<style type="text/css">

	#calendar {
    width: 350px;
    margin: 0 auto;
    font-size: 10px;
    margin-right: 0px;
}
.fc-toolbar {
    font-size: .9em;
}
.fc-toolbar h2 {
    font-size: 12px;
    white-space: normal !important;
}
/* click +2 more for popup */
.fc-more-cell a {
    display: block;
    width: 85%;
    margin: 1px auto 0 auto;
    border-radius: 3px;
    background: grey;
    color: transparent;
    overflow: hidden;
    height: 4px;
}
.fc-more-popover {
    width: 100px;
}
.fc-view-month .fc-event, .fc-view-agendaWeek .fc-event, .fc-content {
    font-size: 0;
    overflow: hidden;
    height: 2px;
}
.fc-view-agendaWeek .fc-event-vert {
    font-size: 0;
    overflow: hidden;
    width: 2px !important;
}
.fc-agenda-axis {
    width: 20px !important;
    font-size: .7em;
}

.fc-button-content {
    padding: 0;
}

.fc-view-harness {
    height: 304.259px!important;
}



	table.editTable thead tr {
    background: #eee;
    color: #900;
}

table.editTable thead th {
    padding: 2px 8px;
    font-weight: 600;
    text-align: left;
    /*border-right: 1px solid #fff;*/
    border-bottom: 1px solid #ccc;
}
table.editTable tbody th {
    padding: 2px 8px;
    white-space: nowrap;
    font-weight: 600;
    border-left: 0;
}
table.editTable td.nopad {
    padding: 0;
}
table.editTable input[type="text"] {
    border: 0;
    padding: 2px 8px;
    background: #ffe;
    width: 100%;
}


table.editTable select {
    border: 0;
    padding: 1px 6px !important;
    width: auto;
    min-width: 100%;
    background: #ffe;
}
.bg_none{
	background-color: #fff!important;
}
.checkbox_center
{
	text-align: center;
	vertical-align: middle;
}

.fc .fc-button-primary {
    background-color: var(--fc-button-bg-color, #080);
    border-color: var(--fc-button-border-color, #080);
}


/*button.fc-next-button.fc-button.fc-button-primary {
    display: none;
}
button.fc-prev-button.fc-button.fc-button-primary {
    display: none;
}*/

.fc .fc-toolbar-title {
    display: none;
}
button.fc-today-button.fc-button.fc-button-primary {
    display: none;
}

.fc-event-title {
    font-size: 14px;
    margin-left: 4px;
}
.hideRange
{
	visibility: hidden;
}
.showRange
{
	visibility: visible;
}

.hidetables 
{
	visibility: hidden!important;
}
</style>
	<h2 style="padding-right:60px">
		<i class="fa fa-cog"></i>&nbsp; <?=$lng['Shift Schedule']?>
		<span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
<div class="pops" style="display: contents;">
		<div style="width:100%; min-height:200px; max-height:100%; background:#fff; padding:20px;  position:relative; overflow-y:auto">
			<div id="dump2"></div>
			
			<form id="shiftscheduleForm">
			<input type="hidden" name="shift_schedule[team_id]" value="<?php echo $row['id']?>">

			<table border="0" style="margin:0; width:100%">
				<thead>
					<tr>
						<th style="padding:0 0 5px 2px; font-size:18px; white-space:nowrap" class="tal" id="empName"><?=$lng['Edit Shift Schedule']?></th>
						<th style="width:80%"></th>
						<th style="padding:0 0 5px 5px" class="tar">
							<button  id="submitBtn" type="submit" class="btn btn-primary"><i class="fa fa-save fa-mr"></i> <?=$lng['Update']?></button>
							<input type="hidden" name="hiddenClickVal" id="hiddenClickVal" value="">						
						</th>
				
					</tr>
				</thead>
			</table>
			
		
			<table border="0" style="width:100%; border-collapse:collapse">
				<input type="hidden" name="hidden_next_year_condition" id="hidden_next_year_condition" value="">
				<tbody><tr>
					<td style="<?php if($noscanCheck != '1'){echo 'border-right:1px solid #ddd;' ;}?> vertical-align:top; width:50%"> <!--LEFT TABLE-->
						<table border="0" style="width:100%"> <!--Allow-->
							<tbody><tr>
								<td style="width:50%; padding-right:3px; vertical-align:top;border: 1px solid grey">
									<table id="fixallowTable" class="editTable" border="0">
										<thead>
											<tr>
												<th style="width:100%"><?=$lng['Team & Shift']?> </th>
												<th class="tar" style="min-width:319px;"></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th><?=$lng['Team Code']?></th>
												<td class="nopad">
													<input  style= "text-transform: uppercase;" class="sel tar bg_none" type="text" value="<?php echo $row['id']?>" readonly="readonly">
													<input  id="hidden_code_id" type="hidden" value="<?php echo $row['id']?>" >
												</td>
											</tr>
											<tr>
												<th><?=$lng['Team Name']?></th>
												<td class="nopad">
													<input name="shift_schedule[name]" class=" bg_none sel tar addBlinkRed" type="text" value="<?php echo $row['name']?>">
												</td>
											</tr>			
											<tr>
												<th><?=$lng['Shift Schedule']?></th>
												<td class="nopad">
													<input name="shift_schedule[description]" class="sel tar addBlinkRed" type="text" value="<?php echo $row['description']?>">
												</td>
											</tr>
										</tbody>
									</table>
								</td>						

								<td style="width:50%; padding-right:3px; vertical-align:top;border: 1px solid grey">
									<table id="fixallowTable" class="editTable" border="0" style="width: 100%;">
									<thead>
										<tr>
											<th colspan="2"><?=$lng['Scan Frequency']?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<input type="hidden" name="hiddenNoscan" id="hiddenNoscan" value="<?php echo $ss_data['noscan']; ?>">
											<td class="pt3" style="padding: 2px 8px;">
												<?php
											if($ss_data['noscan'] == '1')
											{
												$checked2 = 'checked'; ?>

												<input onchange="onchangecheckboxscan();" id= "noscan1" type="checkbox" class="check addBOnC" name="shift_schedule[noscan]" value="1" checked="checked">

											<?php }
											else { ?>

												<input onchange="onchangecheckboxscan();" id= "noscan1" type="checkbox" class="check addBOnC" name="shift_schedule[noscan]" value="1" >


											<?php } 
											?>

											</td>
											<td class="tar nopad"><input name="paid_days" class="bg_none sel float72 tar" type="text" value="No Scans "></td>
										</tr>								
										<tr>
											
											<td class="pt3" style="padding: 2px 8px;">
												<?php
											if($ss_data['scan2'] == '1')
											{
												$checked2 = 'checked'; ?>

												<input  onchange="onchangecheckboxscan();" id= "scan2c" type="checkbox" class="check addBOnC" name="shift_schedule[scan2]" value="1" checked="checked">

											<?php }
											else { ?>

												<input  onchange="onchangecheckboxscan();" id= "scan2c" type="checkbox" class="check addBOnC" name="shift_schedule[scan2]" value="1" >


											<?php } 
											?>

											</td>
											<td class="tar nopad"><input name="paid_days" class="bg_none sel float72 tar" type="text" value="2 Scans - Lunch Break Fixed"></td>
										</tr>
										<tr>
											<td class="pt3" style="padding: 2px 8px;">
												<?php 

												if($ss_data['scan4'] == '1')
												{
													$checked4 = 'checked'; ?>
													<input  onchange="onchangecheckboxscan();" id= "scan4c" type="checkbox" class="check addBOnC" name="shift_schedule[scan4]" value="1" checked="checked">

												<?php }
												else{ ?>

													<input onchange="onchangecheckboxscan();" id= "scan4c" type="checkbox" class="check addBOnC" name="shift_schedule[scan4]" value="1" >


												<?php } 
												?>
											</td>
											<td class="tar nopad"><input name="paid_days" class="bg_none sel float72 tar" type="text" value="4 Scans - Variable Lunch Break"></td>
										</tr>

									</tbody>
									</table>
								</td>
				
							</tr>
						</tbody></table>
						
						<table border="0" style="width:100%" class="<?php if($noscanCheck == '1'){echo 'hidetables' ;}?>"> <!--Deduct-->
							<tbody>
								<tr>
									<td colspan ="4"style="width:100%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;border-bottom: none;">
									<table style="width:100%" id="fixdeductTable" class="editTable" border="0">
										<thead>
											<tr>
												<th ><?=$lng['Shift Conditions']?></th><th></th>
											</tr>
										</thead>
									</table>
								</td>
								</tr>

								<tr>
																
								
								<td style="width:25%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;">
									<table style="width:100%" id="fixdeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0">
			
										<tbody>
											<tr>
												<th style="width: 50%;"><?=$lng['Schedule Type']?></th>
												<td class="nopad">
													<select  class="addBOnC disableAll" id="scheduleType" name="shift_schedule[scheduleType]" style="text-align-last: right;" onchange="getSType();">
													<option value="select">Select</option>
													<option <? if($ss_data['scheduleType'] == 'monthly'){echo 'selected';}?> value="monthly">Monthly Shift Schedule</option>
													<option <? if($ss_data['scheduleType'] == 'weekly'){echo 'selected';}?> value="weekly">Alternating Shifts Weekly</option>
													<option <? if($ss_data['scheduleType'] == 'daily'){echo 'selected';}?> value="daily">Alternating Shifts Daily</option>
												</select>
												</td>
											</tr>
										</tbody>
									</table>
								</td>

								<td style="width:20%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;">
									<table style="width:100%" id="fixdeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0">
										<tbody>
											<tr>
												<th style="width: 50%;"><?=$lng['No of Schedule']?></th>
												<td class="nopad">
													<!-- <input autocomplete="off"  maxlength="1" id="noOfschEdule" name="shift_schedule[numberOfSchedule]" class="disableAll sel float72 tar" type="text" value="<?php echo $ss_data['numberOfSchedule']?>"> -->

													<select  class="disableAll addBOnC" id="noOfschEdule" name="shift_schedule[numberOfSchedule]" style="text-align-last: right;">
														<option value="select">Select</option>
														
													</select>


												</td>
											</tr>
										</tbody>
									</table>
								</td>								
										
								<td style="width:25%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;">
									<table style="width:100%" id="fixdeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0">
										<tbody>
											<tr>
												<th style="width: 50%;"><?=$lng['Week Setup']?></th>
												<td class="nopad">
													<select class="disableAll addBOnC" id="week_setup" name="shift_schedule[week_setup]" style="text-align-last: right;" onchange="getVariableOff();">
													<option value="select">Select</option>
													<!-- <option <? if($ss_data['week_setup'] == 'fixed'){echo 'selected';}?> value="fixed">Fixed</option>
													<option <? if($ss_data['week_setup'] == 'flexible'){echo 'selected';}?> value="flexible">Flexible</option>
													<option <? if($ss_data['week_setup'] == 'running'){echo 'selected';}?> value="running">Running</option> -->
												</select>
												</td>
											</tr>
										</tbody>
									</table>
								</td>	

								<td style="width:30%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;">
									<table style="width:100%;visibility: hidden;" id="variableoffdaytable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0">
										<tbody>
											<tr >
												<th style="width: 50%;"><?=$lng['Variable Off Days']?></th>
												<td >
													<select class="disableAll" id="variable_off_days" name="shift_schedule[variable_off_days]" style="padding-left: 0px!important;">
															<option value="na">N/A</option>
															<option <? if($ss_data['variable_off_days'] == 'month'){echo 'selected';}?> value="month">Per Month</option>
															<option <? if($ss_data['variable_off_days'] == 'week'){echo 'selected';}?> value="week">Per Week</option>
														</select>
												</td>
												<td class="nopad">
													<input autocomplete="off" class="disableAll sel tar addBlinkRed" type="text" name="shift_schedule[variableOffDaysVal]" value="<?php echo $ss_data['variableOffDaysVal'];?>" id="variableOffDaysVal"  />
												</td>
											</tr>
										</tbody>
									</table>
								</td>
		
							</tr>
						</tbody>
					</table>
						
						<table id="schedulesTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="border: 1px solid grey;border-top: none;"> <!--Deductions-->
							<thead>
								<tr>
									<th style="width:20%"><?=$lng['S.No']?></th>
									<th style="width:20%"><?=$lng['Schedules']?></th>
									<th style="width:20%"><?=$lng['Range']?></th>
									<th style="width:20%"><?=$lng['Working Hours']?></th>
									<th style="width:20%"><?=$lng['Break Hours']?></th>
									<th ><?=$lng['Monday']?></th>
									<th ><?=$lng['Tuesday']?></th>
									<th ><?=$lng['Wednesday']?></th>
									<th ><?=$lng['Thursday']?></th>
									<th ><?=$lng['Friday']?></th>
									<th ><?=$lng['Saturday']?></th>
									<th ><?=$lng['Sunday']?></th>
								</tr>
							</thead>
							<tbody>
								<tr class="tr1">
									<th>1</th>
									<td class="tar nopad">
										<select id="shift_schedule1" class="addBOnC" name="shift_schedule[schedule1]" style="text-align-last: right;" onchange="get_sched_val1(this.value,1);">
											<option value="select">Select</option>

											<?php if($ss_data['schedule1'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule1'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td class="tar" id="absence_b">
										
										<select id="s_s_range1" name="shift_schedule[range_day1]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day1'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day1'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day1'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day1'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day1'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day1'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day1'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day1'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>


									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours1" name="shift_schedule[t_hours1]" value="<?php echo $ss_data['t_hours1'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours1" name="shift_schedule[b_hours1]" value="<?php echo $ss_data['b_hours1'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row1" id="monday_td_1" type="checkbox" name="shift_schedule[monday_td_1]" value="1" <?php if($ss_data['monday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="tuesday_td_1" type="checkbox" name="shift_schedule[tuesday_td_1]" value="1" <?php if($ss_data['tuesday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="wednesday_td_1" type="checkbox" name="shift_schedule[wednesday_td_1]" value="1" <?php if($ss_data['wednesday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="thursday_td_1" type="checkbox" name="shift_schedule[thursday_td_1]" value="1" <?php if($ss_data['thursday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="friday_td_1" type="checkbox" name="shift_schedule[friday_td_1]" value="1" <?php if($ss_data['friday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="saturday_td_1" type="checkbox" name="shift_schedule[saturday_td_1]" value="1" <?php if($ss_data['saturday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row1" id="sunday_td_1" type="checkbox" name="shift_schedule[sunday_td_1]" value="1" <?php if($ss_data['sunday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
								<tr class="tr2">
									<th>2</th>
									<td class="tar nopad">
										<select id="shift_schedule2" class="addBOnC" name="shift_schedule[schedule2]" style="text-align-last: right;" onchange="get_sched_val1(this.value,2);">

											<?php if($ss_data['schedule2'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>

											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule2'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td class="tar" id="late_early_b">
										<select id="s_s_range2" name="shift_schedule[range_day2]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day2'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day2'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day2'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day2'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day2'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day2'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day2'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day2'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours2" name="shift_schedule[t_hours2]" value="<?php echo $ss_data['t_hours2'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours2" name="shift_schedule[b_hours2]" value="<?php echo $ss_data['b_hours2'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row2" id="monday_td_2" type="checkbox" name="shift_schedule[monday_td_2]" value="1" <?php if($ss_data['monday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="tuesday_td_2" type="checkbox" name="shift_schedule[tuesday_td_2]" value="1" <?php if($ss_data['tuesday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="wednesday_td_2" type="checkbox" name="shift_schedule[wednesday_td_2]" value="1" <?php if($ss_data['wednesday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="thursday_td_2" type="checkbox" name="shift_schedule[thursday_td_2]" value="1" <?php if($ss_data['thursday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="friday_td_2" type="checkbox" name="shift_schedule[friday_td_2]" value="1" <?php if($ss_data['friday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="saturday_td_2" type="checkbox" name="shift_schedule[saturday_td_2]" value="1" <?php if($ss_data['saturday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row2" id="sunday_td_2" type="checkbox" name="shift_schedule[sunday_td_2]" value="1" <?php if($ss_data['sunday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
								<tr class="tr3">
									<th>3</th>
									<td class="tar nopad">
										<select id="shift_schedule3" class="addBOnC" name="shift_schedule[schedule3]" style="text-align-last: right;" onchange="get_sched_val1(this.value,3);">
											<option value="select">Select</option>
											<?php if($ss_data['schedule3'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule3'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range3" name="shift_schedule[range_day3]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day3'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day3'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day3'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day3'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day3'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day3'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day3'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day3'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours3" name="shift_schedule[t_hours3]" value="<?php echo $ss_data['t_hours3'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours3" name="shift_schedule[b_hours3]" value="<?php echo $ss_data['b_hours3'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row3" id="monday_td_3" type="checkbox" name="shift_schedule[monday_td_3]" value="1" <?php if($ss_data['monday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="tuesday_td_3" type="checkbox" name="shift_schedule[tuesday_td_3]" value="1" <?php if($ss_data['tuesday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="wednesday_td_3" type="checkbox" name="shift_schedule[wednesday_td_3]" value="1" <?php if($ss_data['wednesday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="thursday_td_3" type="checkbox" name="shift_schedule[thursday_td_3]" value="1" <?php if($ss_data['thursday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="friday_td_3" type="checkbox" name="shift_schedule[friday_td_3]" value="1" <?php if($ss_data['friday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="saturday_td_3" type="checkbox" name="shift_schedule[saturday_td_3]" value="1" <?php if($ss_data['saturday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row3" id="sunday_td_3" type="checkbox" name="shift_schedule[sunday_td_3]" value="1" <?php if($ss_data['sunday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
								<tr class="tr4">
									<th>4</th>
									<td>
										<select id="shift_schedule4" class="addBOnC" name="shift_schedule[schedule4]" style="text-align-last: right;" onchange="get_sched_val1(this.value,4);">

											<?php if($ss_data['schedule4'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule4'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range4" name="shift_schedule[range_day4]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day4'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day4'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day4'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day4'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day4'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day4'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day4'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day4'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours4" name="shift_schedule[t_hours4]" value="<?php echo $ss_data['t_hours4'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours4" name="shift_schedule[b_hours4]" value="<?php echo $ss_data['b_hours4'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row4" id="monday_td_4" type="checkbox" name="shift_schedule[monday_td_4]" value="1" <?php if($ss_data['monday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="tuesday_td_4" type="checkbox" name="shift_schedule[tuesday_td_4]" value="1" <?php if($ss_data['tuesday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="wednesday_td_4" type="checkbox" name="shift_schedule[wednesday_td_4]" value="1" <?php if($ss_data['wednesday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="thursday_td_4" type="checkbox" name="shift_schedule[thursday_td_4]" value="1" <?php if($ss_data['thursday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="friday_td_4" type="checkbox" name="shift_schedule[friday_td_4]" value="1" <?php if($ss_data['friday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="saturday_td_4" type="checkbox" name="shift_schedule[saturday_td_4]" value="1" <?php if($ss_data['saturday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row4" id="sunday_td_4" type="checkbox" name="shift_schedule[sunday_td_4]" value="1" <?php if($ss_data['sunday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
								<tr class="tr5">
									<th>5</th>
									<td>
										<select id="shift_schedule5"class="addBOnC" name="shift_schedule[schedule5]" style="text-align-last: right;" onchange="get_sched_val1(this.value,5);">
											<option value="select">Select</option>

											<?php if($ss_data['schedule5'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											
											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule5'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range5" name="shift_schedule[range_day5]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day5'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day5'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day5'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day5'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day5'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day5'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day5'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day5'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours5" name="shift_schedule[t_hours5]" value="<?php echo $ss_data['t_hours5'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours5" name="shift_schedule[b_hours5]" value="<?php echo $ss_data['b_hours5'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row5" id="monday_td_5" type="checkbox" name="shift_schedule[monday_td_5]" value="1" <?php if($ss_data['monday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="tuesday_td_5" type="checkbox" name="shift_schedule[tuesday_td_5]" value="1" <?php if($ss_data['tuesday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="wednesday_td_5" type="checkbox" name="shift_schedule[wednesday_td_5]" value="1" <?php if($ss_data['wednesday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="thursday_td_5" type="checkbox" name="shift_schedule[thursday_td_5]" value="1" <?php if($ss_data['thursday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="friday_td_5" type="checkbox" name="shift_schedule[friday_td_5]" value="1" <?php if($ss_data['friday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="saturday_td_5" type="checkbox" name="shift_schedule[saturday_td_5]" value="1" <?php if($ss_data['saturday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row5" id="sunday_td_5" type="checkbox" name="shift_schedule[sunday_td_5]" value="1" <?php if($ss_data['sunday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>						
								<tr class="tr6">
									<th>6</th>
									<td>
										<select id="shift_schedule6" class="addBOnC" name="shift_schedule[schedule6]" style="text-align-last: right;" onchange="get_sched_val1(this.value,6);">

											<?php if($ss_data['schedule6'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule6'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range6" name="shift_schedule[range_day6]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day6'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day6'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day6'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day6'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day6'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day6'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day6'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day6'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours6" name="shift_schedule[t_hours6]" value="<?php echo $ss_data['t_hours6'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours6" name="shift_schedule[b_hours6]" value="<?php echo $ss_data['b_hours6'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row6" id="monday_td_6" type="checkbox" name="shift_schedule[monday_td_6]" value="1" <?php if($ss_data['monday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="tuesday_td_6" type="checkbox" name="shift_schedule[tuesday_td_6]" value="1" <?php if($ss_data['tuesday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="wednesday_td_6" type="checkbox" name="shift_schedule[wednesday_td_6]" value="1" <?php if($ss_data['wednesday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="thursday_td_6" type="checkbox" name="shift_schedule[thursday_td_6]" value="1" <?php if($ss_data['thursday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="friday_td_6" type="checkbox" name="shift_schedule[friday_td_6]" value="1" <?php if($ss_data['friday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="saturday_td_6" type="checkbox" name="shift_schedule[saturday_td_6]" value="1" <?php if($ss_data['saturday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row6" id="sunday_td_6" type="checkbox" name="shift_schedule[sunday_td_6]" value="1" <?php if($ss_data['sunday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
								<tr class="tr7">
									<th>7</th>
									<td>
										<select id="shift_schedule7" class="addBOnC" name="shift_schedule[schedule7]" style="text-align-last: right;" onchange="get_sched_val1(this.value,7);">
											<option value="select">Select</option>

											<?php if($ss_data['schedule7'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule7'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range7" name="shift_schedule[range_day7]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day7'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day7'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day7'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day7'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day7'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day7'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day7'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day7'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours7" name="shift_schedule[t_hours7]" value="<?php echo $ss_data['t_hours7'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours7" name="shift_schedule[b_hours7]" value="<?php echo $ss_data['b_hours7'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row7" id="monday_td_7" type="checkbox" name="shift_schedule[monday_td_7]" value="1"  <?php if($ss_data['monday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="tuesday_td_7" type="checkbox" name="shift_schedule[tuesday_td_7]" value="1" <?php if($ss_data['tuesday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="wednesday_td_7" type="checkbox" name="shift_schedule[wednesday_td_7]" value="1" <?php if($ss_data['wednesday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="thursday_td_7" type="checkbox" name="shift_schedule[thursday_td_7]" value="1" <?php if($ss_data['thursday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="friday_td_7" type="checkbox" name="shift_schedule[friday_td_7]" value="1" <?php if($ss_data['friday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="saturday_td_7" type="checkbox" name="shift_schedule[saturday_td_7]" value="1" <?php if($ss_data['saturday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row7" id="sunday_td_7" type="checkbox" name="shift_schedule[sunday_td_7]" value="1" <?php if($ss_data['sunday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>								
								<tr class="tr8">
									<th>8</th>
									<td>
										<select id="shift_schedule8" class="addBOnC" name="shift_schedule[schedule8]" style="text-align-last: right;" onchange="get_sched_val1(this.value,8);">

											<?php if($ss_data['schedule8'] == 'off') {?>

												<option selected value="off">OFF</option>

											<?php } else {?>

												<option value="off">OFF</option>

											<?php } ?>


											<?php foreach ($shiftplan_data as $s_value ) {
												?>
												<option <? if($ss_data['schedule8'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option>
											<?php }?>
										</select>
									</td>
									<td>
										<select id="s_s_range8" name="shift_schedule[range_day8]" style="text-align-last: right;" class="addBOnC rangeArr">
											<option <? if($ss_data['range_day8'] == ''){echo 'selected';}?> value="">Select</option>
											<option <? if($ss_data['range_day8'] == '1'){echo 'selected';}?> value="1">1 day</option>
											<option <? if($ss_data['range_day8'] == '2'){echo 'selected';}?> value="2">2 days</option>
											<option <? if($ss_data['range_day8'] == '3'){echo 'selected';}?> value="3">3 days</option>
											<option <? if($ss_data['range_day8'] == '4'){echo 'selected';}?> value="4">4 days</option>
											<option <? if($ss_data['range_day8'] == '5'){echo 'selected';}?> value="5">5 days</option>
											<option <? if($ss_data['range_day8'] == '6'){echo 'selected';}?> value="6">6 days</option>
											<option <? if($ss_data['range_day8'] == '7'){echo 'selected';}?> value="7">7 days</option>
										</select>
									</td>
									
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="t_hours8" name="shift_schedule[t_hours8]" value="<?php echo $ss_data['t_hours8'];?>" readonly="readonly" />
									</td>
									<td class="tar" id="absence_b">
										<input class="bg_none sel tar addBlinkRed" type="text" id="b_hours8" name="shift_schedule[b_hours8]" value="<?php echo $ss_data['b_hours8'];?>" readonly="readonly" />
									</td>
									<td class="checkbox_center"><input class="addBOnC row8" id="monday_td_8" type="checkbox" name="shift_schedule[monday_td_8]" value="1"  <?php if($ss_data['monday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="tuesday_td_8" type="checkbox" name="shift_schedule[tuesday_td_8]" value="1" <?php if($ss_data['tuesday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="wednesday_td_8" type="checkbox" name="shift_schedule[wednesday_td_8]" value="1" <?php if($ss_data['wednesday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="thursday_td_8" type="checkbox" name="shift_schedule[thursday_td_8]" value="1" <?php if($ss_data['thursday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="friday_td_8" type="checkbox" name="shift_schedule[friday_td_8]" value="1" <?php if($ss_data['friday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="saturday_td_8" type="checkbox" name="shift_schedule[saturday_td_8]" value="1" <?php if($ss_data['saturday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
									<td class="checkbox_center"><input class="addBOnC row8" id="sunday_td_8" type="checkbox" name="shift_schedule[sunday_td_8]" value="1" <?php if($ss_data['sunday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td>
								</tr>
							</tbody>
						</table>

						<table border="0" style="width:100%;" class="<?php if($noscanCheck == '1'){echo 'hidetables' ;}?>"> <!--Deduct-->
								<tbody>
									<tr>
										<td colspan ="4"style="width:100%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;border-bottom: none;">
										<table style="width:100%" id="fixdeductTable" class="editTable" border="0">
											<thead>
												<tr>
													<th ><?=$lng['Early / Late']?></th><th></th>
												</tr>
											</thead>
										</table>
									</td>
									</tr>
									<tr>
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="earlyLateTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<th><?=$lng['Apply']?></th>
														<td>
															<select id="earlylateApply" class="disableAll addBOnC" name="shift_schedule[early_late]" style="text-align-last: right;">
																<option <? if($ss_data['early_late'] == 'yes'){echo 'selected';}?> value="yes">Yes</option>
																<option <? if($ss_data['early_late'] == 'no'){echo 'selected';}?> value="no">No</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
										</td>

										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="earlyLateShowTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;visibility: hidden;">
												<tbody>
													<tr>
														<th><?=$lng['Show']?></th>
														<td>
															<select id="earlyLateShow" class="disableAll addBOnC" name="shift_schedule[show_early_late]" style="text-align-last: right;">
																<!-- <option <? if($ss_data['show_early_late'] == 'no'){echo 'selected';}?> value="no">No</option> -->

																<option <? if($ss_data['show_early_late'] == 'yes'){echo 'selected';}?> value="yes">Yes</option>
																<option <? if($ss_data['show_early_late'] == 'no'){echo 'selected';}?> value="no">No</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
										</td>				
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="acceptEarlyTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<th><?=$lng['Accept Early']?></th>
														<td>
															<input autocomplete="off" id="accept_early" class="disableAll hourFormat sel tar addBlinkRed" type="text" name="shift_schedule[accept_early]" value="<?php if($ss_data['accept_early']) { echo $acceptearlyDefault ;}else { echo $ss_data['accept_early']; } ?>"  />
														</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;" >
											<table id="acceptLateTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<th><?=$lng['Accept Late']?></th>
														<td>
															<input autocomplete="off" id="accept_late" class="disableAll hourFormat sel tar addBlinkRed" type="text" name="shift_schedule[accept_late]" value="<?php if($ss_data['accept_late']) { echo $acceptLateDefault ;}else { echo $ss_data['accept_late']; } ?>"  />
														</td>
														</td>
													</tr>
												</tbody>
											</table>
										</td>						
								</tr>
							</tbody>
						</table><table border="0" style="width:100%;" class="<?php if($noscanCheck == '1'){echo 'hidetables' ;}?>"> <!--Deduct-->
								<tbody>
									<tr>
										<td colspan ="3"style="width:100%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;border-bottom: none;">
										<table style="width:100%" id="fixdeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0">
											<thead>
												<tr>
													<th ><?=$lng['Overtime']?></th><th></th>
												</tr>
											</thead>
										</table>
										</td>
										<td style="width:100%; padding-right:3px; vertical-align:top;border: 1px solid grey;border-top: none;border-bottom: none;">
													<table style="width:100%" id="fixdeductTable" class="editTable" border="0">
														<thead>
															<tr>
																<th ><?=$lng['Start Date']?></th><th></th>
															</tr>
														</thead>
													</table>
										</td>
									</tr>
									<tr>
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="vardeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<th><?=$lng['Apply']?></th>
														<td>
															<select id="shift_s_overtime" class=" disableAll addBOnC" name="shift_schedule[overtime]" style="text-align-last: right;">
																<option <? if($ss_data['overtime'] == 'yes'){echo 'selected';}?> value="yes">Yes</option>
																<option <? if($ss_data['overtime'] == 'no'){echo 'selected';}?> value="no">No</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
										</td>

										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="showOvertimeTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;visibility: hidden;">
												<tbody>
													<tr>
														<th><?=$lng['Show']?></th>
														<td>
															<select id="overTimeShow" class="disableAll addBOnC" name="shift_schedule[show_overtme_val]" style="text-align-last: right;">
																<!-- <option <? if($ss_data['show_overtme_val'] == 'no'){echo 'selected';}?> value="no">No</option> -->
																
																<option <? if($ss_data['show_overtme_val'] == 'yes'){echo 'selected';}?> value="yes">Yes</option>
																<option <? if($ss_data['show_overtme_val'] == 'no'){echo 'selected';}?> value="no">No</option>
															</select>
														</td>
													</tr>
												</tbody>
											</table>
										</td>				
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="vardeductTable1" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<th><?=$lng['Normal hours']?></th>
														<td class="nopad">
															<input autocomplete="off" id="normal_hours" class=" hourFormat sel tar addBlinkRed" type="text" name="shift_schedule[normalhours]" value="<?php if(trim($ss_data['normalhours']) == '') {echo $data111['default_normal_hrs'] ;}else { echo $ss_data['normalhours'];}?>"   />
														</td>
													</tr>
												</tbody>
											</table>
										</td>						
										<td style="width:25%; padding-left:3px; vertical-align:top;border:1px solid grey;border-top: none;">
											<table id="vardeductTable" class="editTable <?php if($noscanCheck == '1'){echo 'hidetables' ;}?>" border="0" style="width:100%;">
												<tbody>
													<tr>
														<td>
															<input id="startdate" class="disableAll ssdatepick sel addBOnC"  type="text" value="<?php echo $ss_data['startdate'];?>"  name="shift_schedule[startdate]">
														</td>
														<td>
															<button  id="startCycle" type="button" class="disablebtn disableAll btn btn-primary"><i class="fa fa-check-circle-o fa-mr"></i> <?=$lng['Start Cyle']?></button>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
						
								</tr>
							</tbody>
						</table>						
					</td>

					<td style="padding:0 7px; vertical-align:top; width:10%"> <!--CENTER TABLE-->

						<table border="0" style="width:100%;border: 1px solid grey;border-top: none;" class="<?php if($noscanCheck == '1'){echo 'hidetables' ;}?>">
							<tbody>
								<tr>
									<td style="width:100%; padding-right:3px; vertical-align:top">
										<table style="width:100%" id="fixdeductTable" class="editTable" border="0">
										
											<tbody>

												<tr>
														<div id="calendar"></div>
													
												</tr>
												<tr>
												</tr>
											
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					
				</tr>
			</tbody>
		</table>


	</div>


	<div class="modal fade" id="modalalert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i>&nbsp; <?=$lng['Alert Message']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">

						<p id="modalPara">Following points are required to start the cycle:</p>
						<ul id="requiredList">
					
						</ul>
						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal" onclick="checkcheckedbox();"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>

						<button style="float:right;margin-right: 10px!important;display: none;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="savetheData();" id="okbuttonModal"><i class="fa fa-check"></i>&nbsp; <?=$lng['OK']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>		

	<div class="modal fade" id="modalOverwrites" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:393px;top: 120px;">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-file"></i>&nbsp; Continue the same plan for next year </h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p>Please select the option</p>

							<select id="selectyearCondition" class=" btn-fl" style="width: 100%">
								<option value="1">Yes</option>
								<option selected ="selected" value="2">No</option>
							
							</select>


						</div>
						<div style="height:10px"></div>
						<button style="float:right" class="btn btn-primary" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>

						<button id="buttonclickvalue" style="float:right;margin-right: 11px;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="savedatatoinput();"><i class="fa fa-check"></i>&nbsp; <?=$lng['OK']?></button>
						<div class="clear"></div>
					</div>
					
			  </div>
		 </div>
	</div>






	<script type="text/javascript">


		var tr1 = '<tr class="tr1"><th>1</th><td class="tar nopad"><select id="shift_schedule1" class="addBOnC" name="shift_schedule[schedule1]" style="text-align-last: right;" onchange="get_sched_val1(this.value,1);"><option value="select">Select</option><?php if($ss_data['schedule1'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule1'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class="tar " id="absence_b"><select onchange ="getleftVal();" id="s_s_range1" name="shift_schedule[range_day1]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day1'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day1'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day1'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day1'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day1'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day1'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day1'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day1'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours1" name="shift_schedule[t_hours1]" value="<?php echo $ss_data['t_hours1'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours1" name="shift_schedule[b_hours1]" value="<?php echo $ss_data['b_hours1'];?>" readonly="readonly" /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_mon" id="monday_td_1" type="checkbox" name="shift_schedule[monday_td_1]" value="1" <?php if($ss_data['monday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked();" class=" visiblityNone addBOnC row1 monthly2_tue" id="tuesday_td_1" type="checkbox" name="shift_schedule[tuesday_td_1]" value="1" <?php if($ss_data['tuesday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_wed" id="wednesday_td_1" type="checkbox" name="shift_schedule[wednesday_td_1]" value="1" <?php if($ss_data['wednesday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_thur" id="thursday_td_1" type="checkbox" name="shift_schedule[thursday_td_1]" value="1" <?php if($ss_data['thursday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_fri" id="friday_td_1" type="checkbox" name="shift_schedule[friday_td_1]" value="1" <?php if($ss_data['friday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_sat" id="saturday_td_1" type="checkbox" name="shift_schedule[saturday_td_1]" value="1" <?php if($ss_data['saturday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row1 monthly2_sun" id="sunday_td_1" type="checkbox" name="shift_schedule[sunday_td_1]" value="1" <?php if($ss_data['sunday_td_1'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr2 = '<tr class="tr2"><th>2</th><td class="tar nopad"><select id="shift_schedule2" class="addBOnC" name="shift_schedule[schedule2]" style="text-align-last: right;" onchange="get_sched_val1(this.value,2);"><?php if($ss_data['schedule2'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule2'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=" tar" id="late_early_b"><select id="s_s_range2" name="shift_schedule[range_day2]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day2'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day2'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day2'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day2'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day2'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day2'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day2'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day2'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours2" name="shift_schedule[t_hours2]" value="<?php echo $ss_data['t_hours2'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours2" name="shift_schedule[b_hours2]" value="<?php echo $ss_data['b_hours2'];?>" readonly="readonly" /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_mon" id="monday_td_2" type="checkbox" name="shift_schedule[monday_td_2]" value="1" <?php if($ss_data['monday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_tue" id="tuesday_td_2" type="checkbox" name="shift_schedule[tuesday_td_2]" value="1" <?php if($ss_data['tuesday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_wed" id="wednesday_td_2" type="checkbox" name="shift_schedule[wednesday_td_2]" value="1" <?php if($ss_data['wednesday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_thur" id="thursday_td_2" type="checkbox" name="shift_schedule[thursday_td_2]" value="1" <?php if($ss_data['thursday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_fri" id="friday_td_2" type="checkbox" name="shift_schedule[friday_td_2]" value="1" <?php if($ss_data['friday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_sat" id="saturday_td_2" type="checkbox" name="shift_schedule[saturday_td_2]" value="1" <?php if($ss_data['saturday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked();" class="visiblityNone addBOnC row2 monthly2_sun" id="sunday_td_2" type="checkbox" name="shift_schedule[sunday_td_2]" value="1" <?php if($ss_data['sunday_td_2'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr3 = '<tr class="tr3"><th>3</th><td class="tar nopad"><select id="shift_schedule3" class="addBOnC" name="shift_schedule[schedule3]" style="text-align-last: right;" onchange="get_sched_val1(this.value,3);"><option value="select">Select</option><?php if($ss_data['schedule3'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule3'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class ="" ><select onchange="getleftVal4();" id="s_s_range3" name="shift_schedule[range_day3]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day3'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day3'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day3'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day3'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day3'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day3'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day3'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day3'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours3" name="shift_schedule[t_hours3]" value="<?php echo $ss_data['t_hours3'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours3" name="shift_schedule[b_hours3]" value="<?php echo $ss_data['b_hours3'];?>" readonly="readonly" /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_mon" id="monday_td_3" type="checkbox" name="shift_schedule[monday_td_3]" value="1" <?php if($ss_data['monday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_tue" id="tuesday_td_3" type="checkbox" name="shift_schedule[tuesday_td_3]" value="1" <?php if($ss_data['tuesday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_wed" id="wednesday_td_3" type="checkbox" name="shift_schedule[wednesday_td_3]" value="1" <?php if($ss_data['wednesday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked34();"  class="visiblityNone addBOnC row3 monthly4_thur" id="thursday_td_3" type="checkbox" name="shift_schedule[thursday_td_3]" value="1" <?php if($ss_data['thursday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_fri" id="friday_td_3" type="checkbox" name="shift_schedule[friday_td_3]" value="1" <?php if($ss_data['friday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_sat" id="saturday_td_3" type="checkbox" name="shift_schedule[saturday_td_3]" value="1" <?php if($ss_data['saturday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked34();" class="visiblityNone addBOnC row3 monthly4_sun" id="sunday_td_3" type="checkbox" name="shift_schedule[sunday_td_3]" value="1" <?php if($ss_data['sunday_td_3'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr4 = '<tr class="tr4"><th>4</th><td><select id="shift_schedule4" class="addBOnC" name="shift_schedule[schedule4]" style="text-align-last: right;" onchange="get_sched_val1(this.value,4);"><?php if($ss_data['schedule4'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule4'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=""><select id="s_s_range4" name="shift_schedule[range_day4]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day4'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day4'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day4'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day4'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day4'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day4'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day4'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day4'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours4" name="shift_schedule[t_hours4]" value="<?php echo $ss_data['t_hours4'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours4" name="shift_schedule[b_hours4]" value="<?php echo $ss_data['b_hours4'];?>" readonly="readonly" /></td><td class="checkbox_center"><input onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_mon" id="monday_td_4" type="checkbox" name="shift_schedule[monday_td_4]" value="1" <?php if($ss_data['monday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_tue" id="tuesday_td_4" type="checkbox" name="shift_schedule[tuesday_td_4]" value="1" <?php if($ss_data['tuesday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_wed" id="wednesday_td_4" type="checkbox" name="shift_schedule[wednesday_td_4]" value="1" <?php if($ss_data['wednesday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_thur" id="thursday_td_4" type="checkbox" name="shift_schedule[thursday_td_4]" value="1" <?php if($ss_data['thursday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_fri" id="friday_td_4" type="checkbox" name="shift_schedule[friday_td_4]" value="1" <?php if($ss_data['friday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_sat" id="saturday_td_4" type="checkbox" name="shift_schedule[saturday_td_4]" value="1" <?php if($ss_data['saturday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked34();" class="visiblityNone addBOnC row4 monthly4_sun" id="sunday_td_4" type="checkbox" name="shift_schedule[sunday_td_4]" value="1" <?php if($ss_data['sunday_td_4'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr5 = '<tr class="tr5"><th>5</th><td><select id="shift_schedule5"class="addBOnC" name="shift_schedule[schedule5]" style="text-align-last: right;" onchange="get_sched_val1(this.value,5);"><option value="select">Select</option><?php if($ss_data['schedule5'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule5'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=""><select onchange="getleftVal6();" id="s_s_range5" name="shift_schedule[range_day5]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day5'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day5'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day5'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day5'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day5'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day5'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day5'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day5'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours5" name="shift_schedule[t_hours5]" value="<?php echo $ss_data['t_hours5'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours5" name="shift_schedule[b_hours5]" value="<?php echo $ss_data['b_hours5'];?>" readonly="readonly" /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_mon" id="monday_td_5" type="checkbox" name="shift_schedule[monday_td_5]" value="1" <?php if($ss_data['monday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_tue" id="tuesday_td_5" type="checkbox" name="shift_schedule[tuesday_td_5]" value="1" <?php if($ss_data['tuesday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_wed" id="wednesday_td_5" type="checkbox" name="shift_schedule[wednesday_td_5]" value="1" <?php if($ss_data['wednesday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_thur" id="thursday_td_5" type="checkbox" name="shift_schedule[thursday_td_5]" value="1" <?php if($ss_data['thursday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_fri" id="friday_td_5" type="checkbox" name="shift_schedule[friday_td_5]" value="1" <?php if($ss_data['friday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_sat" id="saturday_td_5" type="checkbox" name="shift_schedule[saturday_td_5]" value="1" <?php if($ss_data['saturday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();" class="visiblityNone addBOnC row5 monthly6_sun" id="sunday_td_5" type="checkbox" name="shift_schedule[sunday_td_5]" value="1" <?php if($ss_data['sunday_td_5'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr6 ='<tr class="tr6"><th>6</th><td><select id="shift_schedule6" class="addBOnC" name="shift_schedule[schedule6]" style="text-align-last: right;" onchange="get_sched_val1(this.value,6);"><?php if($ss_data['schedule6'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule6'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=""><select id="s_s_range6" name="shift_schedule[range_day6]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day6'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day6'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day6'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day6'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day6'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day6'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day6'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day6'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours6" name="shift_schedule[t_hours6]" value="<?php echo $ss_data['t_hours6'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours6" name="shift_schedule[b_hours6]" value="<?php echo $ss_data['b_hours6'];?>" readonly="readonly" /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_mon" id="monday_td_6" type="checkbox" name="shift_schedule[monday_td_6]" value="1" <?php if($ss_data['monday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();"  class="visiblityNone addBOnC row6 monthly6_tue" id="tuesday_td_6" type="checkbox" name="shift_schedule[tuesday_td_6]" value="1" <?php if($ss_data['tuesday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_wed" id="wednesday_td_6" type="checkbox" name="shift_schedule[wednesday_td_6]" value="1" <?php if($ss_data['wednesday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_thur" id="thursday_td_6" type="checkbox" name="shift_schedule[thursday_td_6]" value="1" <?php if($ss_data['thursday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_fri" id="friday_td_6" type="checkbox" name="shift_schedule[friday_td_6]" value="1" <?php if($ss_data['friday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_sat" id="saturday_td_6" type="checkbox" name="shift_schedule[saturday_td_6]" value="1" <?php if($ss_data['saturday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked56();" class="visiblityNone addBOnC row6 monthly6_sun" id="sunday_td_6" type="checkbox" name="shift_schedule[sunday_td_6]" value="1" <?php if($ss_data['sunday_td_6'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr7 ='<tr class="tr7"><th>7</th><td><select id="shift_schedule7" class="addBOnC" name="shift_schedule[schedule7]" style="text-align-last: right;" onchange="get_sched_val1(this.value,7);"><option value="select">Select</option><?php if($ss_data['schedule7'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule7'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=""><select onchange="getleftVal8();" id="s_s_range7" name="shift_schedule[range_day7]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day7'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day7'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day7'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day7'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day7'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day7'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day7'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day7'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours7" name="shift_schedule[t_hours7]" value="<?php echo $ss_data['t_hours7'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours7" name="shift_schedule[b_hours7]" value="<?php echo $ss_data['b_hours7'];?>" readonly="readonly" /></td><td class="checkbox_center"><input  onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_mon" id="monday_td_7" type="checkbox" name="shift_schedule[monday_td_7]" value="1"  <?php if($ss_data['monday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_tue" id="tuesday_td_7" type="checkbox" name="shift_schedule[tuesday_td_7]" value="1" <?php if($ss_data['tuesday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_wed" id="wednesday_td_7" type="checkbox" name="shift_schedule[wednesday_td_7]" value="1" <?php if($ss_data['wednesday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_thur" id="thursday_td_7" type="checkbox" name="shift_schedule[thursday_td_7]" value="1" <?php if($ss_data['thursday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_fri" id="friday_td_7" type="checkbox" name="shift_schedule[friday_td_7]" value="1" <?php if($ss_data['friday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_sat" id="saturday_td_7" type="checkbox" name="shift_schedule[saturday_td_7]" value="1" <?php if($ss_data['saturday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row7 monthly8_sun" id="sunday_td_7" type="checkbox" name="shift_schedule[sunday_td_7]" value="1" <?php if($ss_data['sunday_td_7'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';

		var tr8 = '<tr class="tr8"><th>8</th><td><select id="shift_schedule8" class="addBOnC" name="shift_schedule[schedule8]" style="text-align-last: right;" onchange="get_sched_val1(this.value,8);"><?php if($ss_data['schedule8'] == 'off') {?><option selected value="off">OFF</option><?php } else {?><option value="off">OFF</option><?php } ?><?php foreach ($shiftplan_data as $s_value ) {?><option <? if($ss_data['schedule8'] == $s_value['code']){echo 'selected';}?> value="<?php echo $s_value['code'];?>"><?php echo $s_value['name'];?></option><?php }?></select></td><td class=""><select id="s_s_range8" name="shift_schedule[range_day8]" style="text-align-last: right;" class="hiderange addBOnC rangeArr"><option <? if($ss_data['range_day8'] == ''){echo 'selected';}?> value="">Select</option><option <? if($ss_data['range_day8'] == '1'){echo 'selected';}?> value="1">1 day</option><option <? if($ss_data['range_day8'] == '2'){echo 'selected';}?> value="2">2 days</option><option <? if($ss_data['range_day8'] == '3'){echo 'selected';}?> value="3">3 days</option><option <? if($ss_data['range_day8'] == '4'){echo 'selected';}?> value="4">4 days</option><option <? if($ss_data['range_day8'] == '5'){echo 'selected';}?> value="5">5 days</option><option <? if($ss_data['range_day8'] == '6'){echo 'selected';}?> value="6">6 days</option><option <? if($ss_data['range_day8'] == '7'){echo 'selected';}?> value="7">7 days</option></select></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="t_hours8" name="shift_schedule[t_hours8]" value="<?php echo $ss_data['t_hours8'];?>" readonly="readonly" /></td><td class="tar" id="absence_b"><input class="bg_none sel tar addBlinkRed" type="text" id="b_hours8" name="shift_schedule[b_hours8]" value="<?php echo $ss_data['b_hours8'];?>" readonly="readonly" /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_mon" id="monday_td_8" type="checkbox" name="shift_schedule[monday_td_8]" value="1"  <?php if($ss_data['monday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();"  class="visiblityNone addBOnC row8 monthly8_tue" id="tuesday_td_8" type="checkbox" name="shift_schedule[tuesday_td_8]" value="1" <?php if($ss_data['tuesday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_wed" id="wednesday_td_8" type="checkbox" name="shift_schedule[wednesday_td_8]" value="1" <?php if($ss_data['wednesday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input  onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_thur" id="thursday_td_8" type="checkbox" name="shift_schedule[thursday_td_8]" value="1" <?php if($ss_data['thursday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_fri" id="friday_td_8" type="checkbox" name="shift_schedule[friday_td_8]" value="1" <?php if($ss_data['friday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_sat" id="saturday_td_8" type="checkbox" name="shift_schedule[saturday_td_8]" value="1" <?php if($ss_data['saturday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td><td class="checkbox_center"><input onchange="disableUnchecked78();" class="visiblityNone addBOnC row8 monthly8_sun" id="sunday_td_8" type="checkbox" name="shift_schedule[sunday_td_8]" value="1" <?php if($ss_data['sunday_td_8'] == '1'){ echo 'checked="checked"'; }?> /></td></tr>';
		

	
			$("#shiftscheduleForm").submit(function(e){ 

			e.preventDefault();
			$(".submitBtn i").removeClass('fa-save');
			$(".submitBtn i").addClass('fa-repeat fa-spin');
			var data = $(this).serialize();
			var hiddenClickVal = $('#hiddenClickVal').val();
			//alert('data')
			$.ajax({
				url: "ajax/update_shift_schedule.php",
				type: 'POST',
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){

						if(hiddenClickVal == '1')
						{
							
						}
						else
						{
							$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
							})

						}
						

							$("#submitBtn").removeClass('flash');
							$("#sAlert").fadeOut(1000);
							// location.reload();

						
					      setTimeout(function () {
					        location.reload(true);
					      }, 1000);






					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							//closeConfirm: true
						})
					}
					setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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

		$('.addBlinkRed').on('keyup', function (e) {
			$("#submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);
		});		

		$('.addBOnC').on('change',function (e) {
			$("#submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);
		});

		$(document).on('click', '.check', function() {      
		    $('.check').not(this).prop('checked', false);      
		});


		// FOR 2 ROWS
		$(document).on('click', '.monthly2_mon', function() {      
		    $('.monthly2_mon').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly2_tue', function() {      
		    $('.monthly2_tue').not(this).prop('checked', false);      
		});

		$(document).on('click', '.monthly2_wed', function() {      
		    $('.monthly2_wed').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly2_thur', function() {      
		    $('.monthly2_thur').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly2_fri', function() {      
		    $('.monthly2_fri').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly2_sat', function() {      
		    $('.monthly2_sat').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly2_sun', function() {      
		    $('.monthly2_sun').not(this).prop('checked', false);      
		});


		// FOR 4 ROWS 

		$(document).on('click', '.monthly4_mon', function() {      
		    $('.monthly4_mon').not(this).prop('checked', false);      
		});			

		$(document).on('click', '.monthly4_tue', function() {      
		    $('.monthly4_tue').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly4_wed', function() {      
		    $('.monthly4_wed').not(this).prop('checked', false);      
		});	

		$(document).on('click', '.monthly4_thur', function() {      
		    $('.monthly4_thur').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly4_fri', function() {      
		    $('.monthly4_fri').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly4_sat', function() {      
		    $('.monthly4_sat').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly4_sun', function() {      
		    $('.monthly4_sun').not(this).prop('checked', false);      
		});	

		// FOR 6 ROWS

		$(document).on('click', '.monthly6_mon', function() {      
		    $('.monthly6_mon').not(this).prop('checked', false);      
		});			

		$(document).on('click', '.monthly6_tue', function() {      
		    $('.monthly6_tue').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly6_wed', function() {      
		    $('.monthly6_wed').not(this).prop('checked', false);      
		});	

		$(document).on('click', '.monthly6_thur', function() {      
		    $('.monthly6_thur').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly6_fri', function() {      
		    $('.monthly6_fri').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly6_sat', function() {      
		    $('.monthly6_sat').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly6_sun', function() {      
		    $('.monthly6_sun').not(this).prop('checked', false);      
		});		

		// FOR 8 ROWS

		$(document).on('click', '.monthly8_mon', function() {      
		    $('.monthly8_mon').not(this).prop('checked', false);      
		});			

		$(document).on('click', '.monthly8_tue', function() {      
		    $('.monthly8_tue').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly8_wed', function() {      
		    $('.monthly8_wed').not(this).prop('checked', false);      
		});	

		$(document).on('click', '.monthly8_thur', function() {      
		    $('.monthly8_thur').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly8_fri', function() {      
		    $('.monthly8_fri').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly8_sat', function() {      
		    $('.monthly8_sat').not(this).prop('checked', false);      
		});		

		$(document).on('click', '.monthly8_sun', function() {      
		    $('.monthly8_sun').not(this).prop('checked', false);      
		});


		function get_sched_val1(that,value){


			var schedule_code = that;
			var cid= "<?php echo $cid?>";
			var cur_year= "<?php echo $cur_year?>";


			if(schedule_code == 'select')
			{
				if(value == '1')
				{
					$('#t_hours1').val('');
					$('#b_hours1').val('');	
				}							
				if(value == '2')
				{
					$('#t_hours2').val('');
					$('#b_hours2').val('');	
				}							
				if(value == '3')
				{
					$('#t_hours3').val('');
					$('#b_hours3').val('');	
				}							
				if(value == '4')
				{
					$('#t_hours4').val('');
					$('#b_hours4').val('');	
				}							
				if(value == '5')
				{
					$('#t_hours5').val('');
					$('#b_hours5').val('');	
				}							
				if(value == '6')
				{
					$('#t_hours6').val('');
					$('#b_hours6').val('');	
				}							
				if(value == '7')
				{
					$('#t_hours7').val('');
					$('#b_hours7').val('');	
				}
			}


			if(schedule_code == 'off')
			{

				if(value == '1')
				{
					$('#t_hours1').val('');
					$('#b_hours1').val('');	
				}							
				if(value == '2')
				{
					$('#t_hours2').val('');
					$('#b_hours2').val('');	
				}							
				if(value == '3')
				{
					$('#t_hours3').val('');
					$('#b_hours3').val('');	
				}							
				if(value == '4')
				{
					$('#t_hours4').val('');
					$('#b_hours4').val('');	
				}							
				if(value == '5')
				{
					$('#t_hours5').val('');
					$('#b_hours5').val('');	
				}							
				if(value == '6')
				{
					$('#t_hours6').val('');
					$('#b_hours6').val('');	
				}							
				if(value == '7')
				{
					$('#t_hours7').val('');
					$('#b_hours7').val('');	
				}

			}

			else
			{

			if(schedule_code == 'select')
			{

			}
			else
			{
				$.ajax({
						url: "ajax/get_sched_val1.php",
						type: 'POST',
						data: {'schedule_code':schedule_code,'cid':cid,'cur_year':cur_year},
						success: function(response){

							var data = JSON.parse(response);

							if(response != '')
							{	
								if(value == '1')
								{
									$('#t_hours1').val(data.totalhours);
									$('#b_hours1').val(data.totalbreak);	
								}							
								if(value == '2')
								{
									$('#t_hours2').val(data.totalhours);
									$('#b_hours2').val(data.totalbreak);	
								}							
								if(value == '3')
								{
									$('#t_hours3').val(data.totalhours);
									$('#b_hours3').val(data.totalbreak);	
								}							
								if(value == '4')
								{
									$('#t_hours4').val(data.totalhours);
									$('#b_hours4').val(data.totalbreak);	
								}							
								if(value == '5')
								{
									$('#t_hours5').val(data.totalhours);
									$('#b_hours5').val(data.totalbreak);	
								}							
								if(value == '6')
								{
									$('#t_hours6').val(data.totalhours);
									$('#b_hours6').val(data.totalbreak);	
								}							
								if(value == '7')
								{
									$('#t_hours7').val(data.totalhours);
									$('#b_hours7').val(data.totalbreak);	
								}
								
							}
						},

					});
				}
			}

		}		


		$(document).ready(function() {
			
			$('.sdatepick').datepicker({

				format: "dd/mm/yyyy",
				autoclose: true,
				inline: true,
				orientation: 'auto bottom',
				language: lang,
				todayHighlight: true,
				startView: 'year',

			}) 
		});


		$('#variableOffDaysVal').on('keyup', function (e) {
			$("#varOffdaysTotal").val(this.value);

		});	

		$(document).ready(function() {
			var varOffdays = $('#variable_off_days').val();

			if(varOffdays == 'na')
			{
				$("#variableOffDaysVal").attr("disabled", "disabled");
				$("#varOffdaysTotal").attr("disabled", "disabled");
			}
		});

		



		$('#variable_off_days').on('change', function (e) {

			var variable_off_days = $('#variable_off_days').val();
			$('#variableOffDaysVal').val('')

			
			if(variable_off_days == 'na')
			{
				$("#variableOffDaysVal").attr("disabled", "disabled");
				$("#varOffdaysTotal").attr("disabled", "disabled");
				$('#variableOffDaysVal').val('');
				$('#varOffdaysTotal').val('');
			}
			else
			{
				$("#variableOffDaysVal").removeAttr("disabled");
				$("#varOffdaysTotal").removeAttr("disabled");
			}
			

		});			



		$('#variableOffDaysVal').keydown(function(e) {

			var variable_off_days_Val =  $('#variable_off_days').val();
			var key = e.charCode || e.keyCode;

			

			if(variable_off_days_Val == 'month')
			{
				if ((key <= 57) || (key >= 96 && key <= 105)) 
				{ 
				    // enter key do nothing
				} 
				else 
				{
				   e.preventDefault();
				} 

				
			}
			else if(variable_off_days_Val == 'week')  
			{
				if ((key <= 55) || (key >= 97 && key <= 103)) 
				{ 
				    // enter key do nothing
				} 
				else 
				{
				   e.preventDefault();
				} 

			
			}




		});


		$('#variableOffDaysVal').on('change', function (e) {

			var variableOffDaysVasl = $('#variableOffDaysVal').val();

			var variable_off_days_Val =  $('#variable_off_days').val();
			var key = e.charCode || e.keyCode;

			

			if(variable_off_days_Val == 'month')
			{
				
				if(variableOffDaysVasl > 30)
				{
						$('#variableOffDaysVal').val('');
				}
				
			}
			else if(variable_off_days_Val == 'week')  
			{
				
				if(variableOffDaysVasl > 7)
				{
						$('#variableOffDaysVal').val('');
				}
			
			}

		

		});

		// $('#overTimeShow').on('change', function (e) {

		// 	var shift_s_overtime = $('#shift_s_overtime').val();
		// 	var overTimeShow = $('#overTimeShow').val();

			
		// 	if(shift_s_overtime == 'no' )
		// 	{
		// 		$("#normal_hours").attr("disabled");
		// 	}
		// 	else
		// 	{
		// 		$("#normal_hours").removeAttr("disabled", "disabled");

		// 	}

		// });	

		function getDates(startDate, stopDate) {
		    var dateArray = [];
		    var currentDate = moment(startDate);
		    var stopDate = moment(stopDate);
		    while (currentDate <= stopDate) {
		        dateArray.push( moment(currentDate).format('YYYY-MM-DD') )
		        currentDate = moment(currentDate).add(1, 'days');
		    }
		    return dateArray;
		}


		// START CYCLE 

		$('#startCycle').on('click', function (e) {



			var hiddenNextYear  = $('#hidden_next_year_condition').val();

			var week_setup = $('#week_setup').val(); 
			if(week_setup == 'fixed' || week_setup == 'flexible')
			{

				var shift_schedule1 = $('#shift_schedule1').val();
				var shift_schedule2 = $('#shift_schedule2').val();
				var shift_schedule3 = $('#shift_schedule3').val();
				var shift_schedule4 = $('#shift_schedule4').val();
				var shift_schedule5 = $('#shift_schedule5').val();
				var shift_schedule6 = $('#shift_schedule6').val();
				var shift_schedule7 = $('#shift_schedule7').val();

				// if no of schedules  = 2

				var yourArrayoff = [];
				var yourArraysel = [];
				var youroff = [];
				var yourschedule = [];
				var count =1;
				$('#schedulesTable > tbody  > tr').each(function() 
					{
						
						var schedule = $(this).find(".addBOnC").val();
						// get if monday is off or working 

						var counter = count ++ ;

						if(schedule == 'off')
						{
							// Case Monday 
							if($('#monday_td_'+counter).is(':checked'))
							{
								var Monday = '0';
								yourArrayoff.push(Monday); 
							}else{
								var Monday = '100';
								yourArrayoff.push(Monday);
							}

							// Case Tuesday
							if($('#tuesday_td_'+counter).is(':checked'))
							{
								var Tuesday = '0'; 
								yourArrayoff.push(Tuesday); 
							}else{
								var Tuesday = '100';
								yourArrayoff.push(Tuesday);
							}
							

							// Case Wednesday
							if($('#wednesday_td_'+counter).is(':checked'))
							{
								var Wednesday = '0';
								yourArrayoff.push(Wednesday); 
							}else{
								var Wednesday = '100';
								yourArrayoff.push(Wednesday);
							}
													

							// Case Thursday
							if($('#thursday_td_'+counter).is(':checked'))
							{
								var Thursday = '0'; 
								yourArrayoff.push(Thursday);
							}else{
								var Thursday = '100';
								yourArrayoff.push(Thursday);
							}
													

							// Case Friday
							if($('#friday_td_'+counter).is(':checked'))
							{
								var Friday = '0'; 
								yourArrayoff.push(Friday);
							}else{
								var Friday = '100';
								yourArrayoff.push(Friday);
							}
							

							// Case Saturday
							if($('#saturday_td_'+counter).is(':checked'))
							{
								var Saturday = '0'; 
								yourArrayoff.push(Saturday);
							}else{
								var Saturday = '100';
								yourArrayoff.push(Saturday);
							}
													
							// Case Sunday
							if($('#sunday_td_'+counter).is(':checked'))
							{
								var Sunday = '0'; 
								yourArrayoff.push(Sunday);
							}else{
								var Sunday = '100';
								yourArrayoff.push(Sunday);
							}

							youroff.push(schedule);
							

						}
						else if (schedule != 'select')
						{
							if($('#monday_td_'+counter).is(':checked'))
							{
								var Monday = '1';
								yourArraysel.push(Monday); 
							}else{
								var Monday = '100';
								yourArraysel.push(Monday);
							}
													

							if($('#tuesday_td_'+counter).is(':checked'))
							{
								var Tuesday = '1'; 
								yourArraysel.push(Tuesday); 
							}else{
								var Tuesday = '100';
								yourArraysel.push(Tuesday);
							}
													

							if($('#wednesday_td_'+counter).is(':checked'))
							{
								var Wednesday = '1'; 
								yourArraysel.push(Wednesday); 
							}else{
								var Wednesday = '100';
								yourArraysel.push(Wednesday);
							}
											

							if($('#thursday_td_'+counter).is(':checked'))
							{
								var Thursday = '1'; 
								yourArraysel.push(Thursday); 
							}else{
								var Thursday = '100';
								yourArraysel.push(Thursday);
							}
													

							if($('#friday_td_'+counter).is(':checked'))
							{
								var Friday = '1'; 
								yourArraysel.push(Friday);
							}else{
								var Friday = '100';
								yourArraysel.push(Friday);
							}
													

							if($('#saturday_td_'+counter).is(':checked'))
							{
								var Saturday = '1';
								yourArraysel.push(Saturday); 
							}else{
								var Saturday = '100';
								yourArraysel.push(Saturday);
							}
													

							if($('#sunday_td_'+counter).is(':checked'))
							{
								var Sunday = '1'; 
								yourArraysel.push(Sunday);
							}else{
								var Sunday = '100';
								yourArraysel.push(Sunday);
							}

							yourschedule.push(schedule);
							
						}
						else if (schedule == 'select')
						{
							return;
						}

					}
			);


			var finalarray = [];
			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '';
					finalarray.push(text);
				}
			});

			var noOfschEdule = $('#noOfschEdule').val();

			// check if all checkboxes are filled or not 

			var getSnO = $('#noOfschEdule').val();

			if(getSnO == '2')
			{
				var totalBox = 7;
				var offdaycountarray =[];
				// fetch range off day for 2 schedule 
				$('#schedulesTable > tbody  > tr').each(function() 
				{
					var scheduleoff2 = $(this).find(".addBOnC").val();
					var rangeoff2 = $(this).find(".hiderange").val();
					if(scheduleoff2 == 'off')
					{	
						var offrange2 =rangeoff2;
						offdaycountarray.push(offrange2);
					}
				});
			}		
			else if(getSnO == '4')
			{
				var totalBox = 14;
			}
			else if(getSnO == '6')
			{
				var totalBox = 21;
			}		
			else if(getSnO == '8')
			{
				var totalBox = 28;
			}

			var counter = 0;
			$.each(finalarray, function(key,value) {

				if(value != '')
				{
					counter ++;
				}		
			});

			// alert(offdaycountarray[0]);  off day count 

			// show alert with all required fields 
			if(counter != totalBox)
			{
				// check if range and selected check boxes are same 



				// $(".disablebtn").attr("disabled", "disabled");
				$('ul#requiredList').empty();

				if($('.check').is(':checked')){}
				else
				{
					$('ul#requiredList').append('<li>Please Select Scan Frequency</li>');
				}
				$('ul#requiredList').append('<li>Please select all days checkboxes</li>');
				$("#modalalert").modal('toggle');

				return false ;
			
			}
			else
			{
				if($('.check').is(':checked'))
				{
					$(".disablebtn").removeAttr("disabled", "disabled");
				}
				else
				{
					$('ul#requiredList').empty();
					$('ul#requiredList').append('<li>Please select Scan Frequency</li>');
					$("#modalalert").modal('toggle');

					 return false;
				}
			}


			if(noOfschEdule == '2')
			{
				var startdate = $('#startdate').val();
				var hidden_code_id = $('#hidden_code_id').val();
				var variableOffDaysVal = $('#variableOffDaysVal').val();


				if( startdate != '')
				{

					$('#submitBtn').click();
					$(".submitBtn i").removeClass('fa-save');
					$(".submitBtn i").addClass('fa-repeat fa-spin');

					$.ajax({
							url: "ajax/start_shift_cycle.php",
							type: 'POST',
							data: {'startdate':startdate,'Monday':finalarray[0],'Tuesday':finalarray[1],'Wednesday':finalarray[2],'Thursday':finalarray[3],'Friday':finalarray[4],'Saturday':finalarray[5],'Sunday':finalarray[6],'shift_schedule1':shift_schedule1,'shift_schedule2':shift_schedule2,'shift_schedule3':shift_schedule3,'shift_schedule4':shift_schedule4,'shift_schedule5':shift_schedule5,'shift_schedule6':shift_schedule6,'shift_schedule7':shift_schedule7,'hidden_code_id':hidden_code_id,'youroff':youroff[0],'yourschedule':yourschedule[0],'variableOffDaysVal':variableOffDaysVal,'hiddenNextYear':hiddenNextYear},

							success: function(result){
							if(result == 'success'){
								// $("body").overhang({
								// 	type: "success",
								// 	message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								// 	duration: 3,
								// })

									// $("#submitBtn").removeClass('flash');
									// $("#sAlert").fadeOut(700);
									// location.reload();

									setTimeout(function () {
								        location.reload(true);
								      }, 2000);




							}else{
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
									duration: 4,
									//closeConfirm: true
								})
							}
							setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
				}
				else if (noOfschEdule == '4' || noOfschEdule == '6' || noOfschEdule == '8')
				{
					var yourschedulArr = [];
					var yourArrayRange = [];
					var schedulerange4 = [];
					var sArray = [];

					$('#schedulesTable > tbody  > tr').each(function() 
					{
						

						var range = $(this).find(".rangeArr").val();
						var schedule = $(this).find(".addBOnC").val();

						if(range != '')
						{
							yourArrayRange.push(range);
						}
						if(schedule != 'select')
						{
							yourschedulArr.push(schedule);
						}

					});


					if(noOfschEdule == '4')
					{
						if(finalarray[0] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}						
						if(finalarray[1] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[2] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[3] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[4] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[5] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}				
						if(finalarray[6] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[7] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[8] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[9] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}	
						if(finalarray[10] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[11] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[12] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[13] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
					}
					else if(noOfschEdule == '6')
					{
						if(finalarray[0] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}						
						if(finalarray[1] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[2] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[3] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[4] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[5] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}				
						if(finalarray[6] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[7] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[8] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[9] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}	
						if(finalarray[10] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[11] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[12] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[13] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}		
						if(finalarray[14] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[15] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}

						if(finalarray[16] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
						if(finalarray[17] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[18] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
						if(finalarray[19] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[20] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
					}		
					else if(noOfschEdule == '8')
					{
						if(finalarray[0] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}						
						if(finalarray[1] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[2] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[3] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[4] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[5] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}				
						if(finalarray[6] == '1')
						{
							schedulerange4.push(yourschedulArr[0]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[1])
						}
						if(finalarray[7] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[8] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[9] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}	
						if(finalarray[10] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[11] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[12] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}
						if(finalarray[13] == '1')
						{
							schedulerange4.push(yourschedulArr[2]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[3])
						}		
						if(finalarray[14] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[15] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}

						if(finalarray[16] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
						if(finalarray[17] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[18] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
						if(finalarray[19] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}						
						if(finalarray[20] == '1')
						{
							schedulerange4.push(yourschedulArr[4]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[5])
						}
						if(finalarray[21] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}	
						if(finalarray[22] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
						if(finalarray[23] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
						if(finalarray[24] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
						if(finalarray[25] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
						if(finalarray[26] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
						if(finalarray[27] == '1')
						{
							schedulerange4.push(yourschedulArr[6]);
						}	
						else
						{
							schedulerange4.push(yourschedulArr[7])
						}
					}




					// alert(schedulerange4);


					$.each(yourArrayRange, function(key,value){

						  for (var i = 1; i <= value; i++) {

						  	
						    sArray.push(yourschedulArr[key]);

						  }

					});



					var fullyear = new Date().getFullYear();
					var dateResult = getDates(fullyear+'-01-01', fullyear+'-12-31'); // make it dyanamic 

					// console.log(test);
					// console.log(yourArrayRange);
					// console.log(finalarray);


					var dateArrs = {};
					$.each(dateResult, function(key,value){

					var dateArr = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][new Date(value).getDay()];

					//console.log(dateArr);
					//console.log(value);

					dateArrs[value] = dateArr;
					

					});





					var startdate = $('#startdate').val();
					var hidden_code_id = $('#hidden_code_id').val();
					if( startdate != '')
					{

						$('#submitBtn').click();
						$(".submitBtn i").removeClass('fa-save');
						$(".submitBtn i").addClass('fa-repeat fa-spin');

						$.ajax({
								url: "ajax/start_shift_cycle_even_rows.php",
								type: 'POST',
								data: {'startdate':startdate,'Monday':finalarray[0],'Tuesday':finalarray[1],'Wednesday':finalarray[2],'Thursday':finalarray[3],'Friday':finalarray[4],'Saturday':finalarray[5],'Sunday':finalarray[6],'shift_schedule1':shift_schedule1,'shift_schedule2':shift_schedule2,'shift_schedule3':shift_schedule3,'shift_schedule4':shift_schedule4,'shift_schedule5':shift_schedule5,'shift_schedule6':shift_schedule6,'shift_schedule7':shift_schedule7,'hidden_code_id':hidden_code_id,'youroff':youroff[0],'yourschedule':yourschedule[0],'finalarray':finalarray,'sArray':sArray,'dateResult':dateResult,'schedulerange4':schedulerange4,'hiddenNextYear':hiddenNextYear},

								success: function(result){
								if(result == 'success'){
									// $("body").overhang({
									// 	type: "success",
									// 	message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
									// 	duration: 2,
									// })

										// $("#submitBtn").removeClass('flash');
										// $("#sAlert").fadeOut(200);
										// location.reload();
										setTimeout(function () {
								        location.reload(true);
								      }, 2000);



								}else{
									$("body").overhang({
										type: "error",
										message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
										duration: 4,
										//closeConfirm: true
									})
								}
								setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
				}
			}


			if(week_setup == 'running')
			{

			// IF ITS A RUNNING CASE FOR SHIFT SCHEDULES 

				var yourschedulArr = [];
				var yourArrayRange = [];
				var sArray = [];

				$('#schedulesTable > tbody  > tr').each(function() 
				{
					

					var range = $(this).find(".rangeArr").val();
					var schedule = $(this).find(".addBOnC").val();

					if(range != '')
					{
						yourArrayRange.push(range);
					}
					if(schedule != 'select')
					{
						yourschedulArr.push(schedule);
					}

				});



				$.each(yourArrayRange, function(key,value){

					// alert(value);
					  for (var i = 1; i <= value; i++) {
					    sArray.push(yourschedulArr[key]);

					  }

				});



			var startdate = $('#startdate').val();
			var hidden_code_id = $('#hidden_code_id').val();
			if( startdate != '')
			{

				$('#submitBtn').click();
				$(".submitBtn i").removeClass('fa-save');
				$(".submitBtn i").addClass('fa-repeat fa-spin');

				$.ajax({
						url: "ajax/start_shift_cycle_running.php",
						type: 'POST',
						data: {'startdate':startdate,'sArray':sArray,'hidden_code_id':hidden_code_id,'hiddenNextYear':hiddenNextYear},

						success: function(result){
						if(result == 'success'){
							// $("body").overhang({
							// 	type: "success",
							// 	message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							// 	duration: 2,
							// })

							// 	$("#submitBtn").removeClass('flash');
							// 	$("#sAlert").fadeOut(200);
							// 	location.reload();
								setTimeout(function () {
						        location.reload(true);
						      }, 2000);



						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								//closeConfirm: true
							})
						}
						setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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





			}
		});	




// 49,50,51,52,53,54,55,56,97,98,99,100,101,102,103,104



function getSType()
{
	var scheduleType = $('#scheduleType').val();
	if(scheduleType == 'select')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		disableSchedule();

	}	
	else if(scheduleType == 'monthly')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$("<option <? if($ss_data['numberOfSchedule'] == '2'){echo 'selected';}?> ></option>").attr("value", "2").text("2").appendTo('#noOfschEdule');

		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		$("<option <? if($ss_data['week_setup'] == 'fixed'){echo 'selected';}?> ></option>").attr("value", "fixed").text("Fixed").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'flexible'){echo 'selected';}?> ></option>").attr("value", "flexible").text("Flexible").appendTo('#week_setup');


	}	
	else if(scheduleType == 'weekly')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$("<option <? if($ss_data['numberOfSchedule'] == '2'){echo 'selected';}?>></option>").attr("value", "2").text("2").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '4'){echo 'selected';}?>></option>").attr("value", "4").text("4").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '6'){echo 'selected';}?>></option>").attr("value", "6").text("6").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '8'){echo 'selected';}?>></option>").attr("value", "8").text("8").appendTo('#noOfschEdule');


		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		$("<option <? if($ss_data['week_setup'] == 'fixed'){echo 'selected';}?> ></option>").attr("value", "fixed").text("Fixed").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'flexible'){echo 'selected';}?> ></option>").attr("value", "flexible").text("Flexible").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'running'){echo 'selected';}?> ></option>").attr("value", "running").text("Running").appendTo('#week_setup');
	}
	else if(scheduleType == 'daily')
	{

	}
}


$(document).ready(function() {


	var scheduleType = $('#scheduleType').val();

	if(scheduleType == 'select')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		disableSchedule();

	}	
	else if(scheduleType == 'monthly')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$("<option <? if($ss_data['numberOfSchedule'] == '2'){echo 'selected';}?> ></option>").attr("value", "2").text("2").appendTo('#noOfschEdule');

		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		$("<option <? if($ss_data['week_setup'] == 'fixed'){echo 'selected';}?> ></option>").attr("value", "fixed").text("Fixed").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'flexible'){echo 'selected';}?> ></option>").attr("value", "flexible").text("Flexible").appendTo('#week_setup');


	}	
	else if(scheduleType == 'weekly')
	{
		$('#noOfschEdule option[value="2"]').remove();
		$('#noOfschEdule option[value="4"]').remove();
		$('#noOfschEdule option[value="6"]').remove();
		$('#noOfschEdule option[value="8"]').remove();
		$("<option <? if($ss_data['numberOfSchedule'] == '2'){echo 'selected';}?>></option>").attr("value", "2").text("2").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '4'){echo 'selected';}?>></option>").attr("value", "4").text("4").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '6'){echo 'selected';}?>></option>").attr("value", "6").text("6").appendTo('#noOfschEdule');
		$("<option <? if($ss_data['numberOfSchedule'] == '8'){echo 'selected';}?>></option>").attr("value", "8").text("8").appendTo('#noOfschEdule');


		$('#week_setup option[value="fixed"]').remove();
		$('#week_setup option[value="flexible"]').remove();
		$('#week_setup option[value="running"]').remove();
		$("<option <? if($ss_data['week_setup'] == 'fixed'){echo 'selected';}?> ></option>").attr("value", "fixed").text("Fixed").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'flexible'){echo 'selected';}?> ></option>").attr("value", "flexible").text("Flexible").appendTo('#week_setup');
		$("<option <? if($ss_data['week_setup'] == 'running'){echo 'selected';}?> ></option>").attr("value", "running").text("Running").appendTo('#week_setup');
	}


});


	$("#noOfschEdule").change(function(event) {

		$('#week_setup').val('select');

    });

	$("#scheduleType").change(function(event) {

		$('#week_setup').val('select');
		$('#variableoffdaytable').css("visibility", "hidden");


    });


	$("#week_setup").change(function(event) {

		var scheduleType = $('#scheduleType').val();
		var week_setup = $('#week_setup').val();


		if(scheduleType == 'monthly')
		{
			
			disableSchedule();

			if(week_setup == 'flexible')
			{
				$('.hiderange').css("visibility", "visible");
			}
   			
	        
		}
		else if(scheduleType == 'weekly')
		{
			
			disableSchedule();
			if(week_setup == 'flexible')
			{
				$('.hiderange').css("visibility", "visible");
			}
   			
		}			
		else if(scheduleType == 'daily')
		{
			
			disableSchedule();
			if(week_setup == 'flexible')
			{
				$('.hiderange').css("visibility", "visible");
			}
   			
		}				

    });



// ON CHANGE
	function disableSchedule()
	{
		var noOfschEdule = $('#noOfschEdule').val();

		var scheduleType = $('#scheduleType').val();


		// CASE 1 
		if(scheduleType == 'daily' && noOfschEdule == '4')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);

		}		
		else if(noOfschEdule == '1')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);

		}
		// CASE 2
		else if(noOfschEdule == '2')
		{

			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);

			// tr1 - remove off 
			$("#shift_schedule1 option[value='off']").each(function() {
			    $(this).remove();
			});

			// tr2 - keep only off

			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) {}
			    else
			    {
			    	$(this).remove();
			    }
			});


		}

			
		
		// CASE 3			
		else if(noOfschEdule == '3')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
		}
		// CASE 4			
		else if(noOfschEdule == '4')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);


			// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});



		}	
		// CASE 5		
		else if(noOfschEdule == '5')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
		}
		// CASE 6			
		else if(noOfschEdule == '6')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);

			// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});	

			// remove off tr5
			$('#shift_schedule5 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr6
			$('#shift_schedule6 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});

		}			
		// CASE 7
		else if(noOfschEdule == '7')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			
		}		
		else if(noOfschEdule == '8')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			$('#schedulesTable').append(tr8);

			// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});	

			// remove off tr5
			$('#shift_schedule5 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr6
			$('#shift_schedule6 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr7
			$('#shift_schedule7 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr8
			$('#shift_schedule8 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});



			
		}
		else
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();

			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			$('#schedulesTable').append(tr8);

			$(".tr1").css("visibility", "collapse");
			$(".tr2").css("visibility", "collapse");
			$(".tr3").css("visibility", "collapse");
			$(".tr4").css("visibility", "collapse");
			$(".tr5").css("visibility", "collapse");
			$(".tr6").css("visibility", "collapse");
			$(".tr7").css("visibility", "collapse");
			$(".tr8").css("visibility", "collapse");
		}
	
	}	

// ON REFRESH 
	$(document).ready(function() {

		var noOfschEdule = $('#noOfschEdule').val();

		if(noOfschEdule == '1')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);

		}
		// CASE 2
		else if(noOfschEdule == '2')
		{

			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);

			// tr1 - remove off 
			$("#shift_schedule1 option[value='off']").each(function() {
			    $(this).remove();
			});

			// tr2 - keep only off

			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) {}
			    else
			    {
			    	$(this).remove();
			    }
			});

		}

			
		
		// CASE 3			
		else if(noOfschEdule == '3')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
		}
		// CASE 4			
		else if(noOfschEdule == '4')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);

			// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});


		}	
		// CASE 5		
		else if(noOfschEdule == '5')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
		}
		// CASE 6			
		else if(noOfschEdule == '6')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);

			// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr5
			$('#shift_schedule5 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr6
			$('#shift_schedule6 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});

		}			
		// CASE 7
		else if(noOfschEdule == '7')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			
		}		
		else if(noOfschEdule == '8')
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();
			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			$('#schedulesTable').append(tr8);


					// remove off tr1
			$('#shift_schedule1 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr2
			$('#shift_schedule2 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr3
			$('#shift_schedule3 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr4
			$('#shift_schedule4 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr5
			$('#shift_schedule5 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr6
			$('#shift_schedule6 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});			

			// remove off tr7
			$('#shift_schedule7 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	$(this).remove();
			    }
			    else
			    {
			    	
			    }
			});			


			// keep off tr8
			$('#shift_schedule8 option').each(function() {
			    if ( $(this).val() == 'off' ) 
			    {
			    	
			    }
			    else
			    {
			    	$(this).remove();
			    }
			});

			
		}
		else
		{
			$('.tr1').remove();
			$('.tr2').remove();
			$('.tr3').remove();
			$('.tr4').remove();
			$('.tr5').remove();
			$('.tr6').remove();
			$('.tr7').remove();
			$('.tr8').remove();

			$('#schedulesTable').append(tr1);
			$('#schedulesTable').append(tr2);
			$('#schedulesTable').append(tr3);
			$('#schedulesTable').append(tr4);
			$('#schedulesTable').append(tr5);
			$('#schedulesTable').append(tr6);
			$('#schedulesTable').append(tr7);
			$('#schedulesTable').append(tr8);

			$(".tr1").css("visibility", "collapse");
			$(".tr2").css("visibility", "collapse");
			$(".tr3").css("visibility", "collapse");
			$(".tr4").css("visibility", "collapse");
			$(".tr5").css("visibility", "collapse");
			$(".tr6").css("visibility", "collapse");
			$(".tr7").css("visibility", "collapse");
			$(".tr8").css("visibility", "collapse");
		}

		var week_setup = $('#week_setup').val();
		var scheduleType = $('#scheduleType').val();
		if(week_setup == 'flexible' && scheduleType == 'monthly')
		{
			$('.hiderange').css("visibility", "visible");
		}		
		else if(week_setup == 'flexible' && scheduleType =='weekly' )
		{
			$('.hiderange').css("visibility", "visible");
		}		
		else if(week_setup == 'flexible' )
		{
			$('.hiderange').css("visibility", "hidden");
		}




	});

// FULL CALENDAR 
		document.addEventListener('DOMContentLoaded', function() {
	        var calendarEl = document.getElementById('calendar');
	        var calendarEl2 = document.getElementById('calendar2');



	        var startDate  =   '<?php echo  $starCdate; ?>';

	        var arr = startDate.split('/');

	        // date - arr[0] 
	        // month  - arr[1] 
	        // year  - arr[2] 

	        var newDate = arr[2]+'-'+arr[1]+'-'+arr[0];



	    var newdates = new Date(newDate);

	    newdates.setDate(newdates.getDate() + 30);
	    
	    var dd = newdates.getDate();
	    var mm = newdates.getMonth() + 1;
	    var y = newdates.getFullYear();

	    if (/^\d$/.test(mm))  {
	   	 var zero = '0';
		}
		else
		{
			 var zero = '';
		}    

		if (/^\d$/.test(dd))  {
	   	 var zero1 = '0';
		}
		else
		{
			 var zero1 = '';
		}
	    var someFormattedDate = y + '-' + zero+mm + '-' + zero1+dd;


	    console.log(someFormattedDate);

			var arrayFromPHP = <?php echo json_encode($DateSliced) ?>;

	        $.each(arrayFromPHP, function (key, val) {

	        	var sDate = val.date;
	        	var eTitle = val.s1;
			    // console.log(sDate);
			    // console.log(eTitle);


			   var calendar = new FullCalendar.Calendar(calendarEl, {

		           initialView: 'dayGridMonth',
		           initialDate:newDate,
		           // aspectRatio: 0.75 ,
		           firstDay:1,
		  //           validRange: {
				//     start: newDate,
				//     end: someFormattedDate
				// },

		          eventSources: [{
		          // color: '#306EFE',   
		          textColor: '#ffffff',
		          events: arrayFromPHP,
	
		      	}] ,

	        });

	        calendar.render();




			});	


			$.each(arrayFromPHP, function (key, val) {

	        	var sDate = val.date;
	        	var eTitle = val.s1;
			    console.log(sDate);
			    console.log(eTitle);


			   var calendar2 = new FullCalendar.Calendar(calendarEl2, {
		          initialView: 'dayGridMonth',
		          eventSources: [{
		          // color: '#306EFE',   
		          textColor: '#ffffff',
		          events: arrayFromPHP
		      		
		      	}] 

	        });
	        calendar2.render();



			});


      });


$( document ).ready(function() {

	var hiddenNoscan = $('#hiddenNoscan').val();

	if(hiddenNoscan == '1')
	{
		$(".disableAll").attr("disabled", "disabled");
	}

});


function getleftVal()
{
	var s_s_range1 = $('#s_s_range1').val();

	var changeVal = 7-s_s_range1  ;

	$("#s_s_range2").val(changeVal);

}

function getleftVal4()
{
	var s_s_range3 = $('#s_s_range3').val();

	var changeVal = 7-s_s_range3  ;

	$("#s_s_range4").val(changeVal);

}

function getleftVal6()
{
	var s_s_range5 = $('#s_s_range5').val();

	var changeVal = 7-s_s_range5  ;

	$("#s_s_range6").val(changeVal);

}

function getleftVal8()
{
	var s_s_range7 = $('#s_s_range7').val();

	var changeVal = 7-s_s_range7  ;

	$("#s_s_range8").val(changeVal);

}

function getVariableOff()
{
	var week_setup= $('#week_setup').val();

	if(week_setup == 'select')
	{
		$('#variableoffdaytable').css("visibility", "hidden");
		$('.visiblityNone').css("visibility", "visible");

	}	
	else if(week_setup == 'flexible')
	{
		$('#variableoffdaytable').css("visibility", "visible");
		$('.visiblityNone').css("visibility", "visible");

	}	
	if(week_setup == 'fixed')
	{
		$('#variableoffdaytable').css("visibility", "hidden");
		$('.visiblityNone').css("visibility", "visible");
	}	
	if(week_setup == 'running')
	{
		$('#variableoffdaytable').css("visibility", "hidden");
		 setTimeout(function() { 
			$('.visiblityNone').css("visibility", "hidden");
        }, 500);
	}


	// disableUnchecked();
	// disableUnchecked34();
	// disableUnchecked56();
	// disableUnchecked78();
}
$( document ).ready(function() {
	var week_setup= $('#week_setup').val();

	if(week_setup == 'select')
	{
		$('#variableoffdaytable').css("visibility", "hidden");

	}	
	else if(week_setup == 'flexible')
	{
		$('#variableoffdaytable').css("visibility", "visible");

	}	
	if(week_setup == 'fixed')
	{
		$('#variableoffdaytable').css("visibility", "hidden");
		
	}

	if(week_setup == 'running')
	{
		$('.visiblityNone').css("visibility", "hidden");
   
	}
});




$('#shift_s_overtime').on('change',function (e) {

	var overtimeValue  = this.value;

	if(overtimeValue == 'no')
	{
		$('table#vardeductTable1 tbody tr').css('visibility', 'hidden');
		$('#showOvertimeTable').css('visibility', 'visible');
		// $("#normal_hours").removeAttr("disabled", "disabled");
		$('#normal_hours').val('');


	}
	else
	{
		$('#showOvertimeTable').css('visibility', 'hidden');
		$('table#vardeductTable1 tbody tr').css('visibility', 'visible');

		// $("#normal_hours").attr("disabled", "disabled");
		// $('#normal_hours').val('08:00');


	}
});

$( document ).ready(function() {
    var overtimeValue  = $('#shift_s_overtime').val();
    var noscanCheckval = '<?php echo $noscanCheck?>';
    

	if(overtimeValue == 'no')
	{
		$('#showOvertimeTable').css('visibility', 'visible');
		$('table#vardeductTable1 tbody tr').css('visibility', 'hidden');

		// $("#normal_hours").removeAttr("disabled", "disabled");
		// $('#normal_hours').val('');


	}
	else
	{
		$('#showOvertimeTable').css('visibility', 'hidden');
		if(noscanCheckval != '1')
	    {
			$('table#vardeductTable1 tbody tr').css('visibility', 'visible');

	    }

		// $("#normal_hours").attr("disabled", "disabled");
		// $('#normal_hours').val('08:00');

	}
});	



$('#overTimeShow').on('change',function (e) {

	var overtimeValuse  = this.value;


	if(overtimeValuse == 'yes')
	{
		$('table#vardeductTable1 tbody tr').css('visibility', 'visible');
		// $("#normal_hours").attr("disabled", "disabled");

		// $('#normal_hours').val('08:00');


	}
	else
	{
		$('table#vardeductTable1 tbody tr').css('visibility', 'hidden');
		// $("#normal_hours").removeAttr("disabled", "disabled");
		// $('#normal_hours').val('08:00');
		// $('#normal_hours').val('');



	}
});


$('#earlyLateShow').on('change',function (e) {

	var earlyLateShow  = this.value;


	if(earlyLateShow == 'yes')
	{
		$('table#acceptEarlyTable tbody tr').css('visibility', 'visible');



	}
	else
	{
		$('table#acceptEarlyTable tbody tr').css('visibility', 'hidden');



	}
});

// $( document ).ready(function() {

//     var overtimeValuse  = $('#overTimeShow').val();


// 	if(overtimeValuse == 'yes')
// 	{
// 		$('table#vardeductTable1 tbody tr').css('visibility', 'visible');
// 		$("#normal_hours").attr("disabled", "disabled");

// 		$('#normal_hours').val('08:00');


// 	}
// 	else
// 	{

// 		$('table#vardeductTable1 tbody tr').css('visibility', 'hidden');
// 		$("#normal_hours").removeAttr("disabled", "disabled");
// 		$('#normal_hours').val('08:00');
// 		$('#normal_hours').val('');



// 	}
// });	


// EARLY LATE 

$('#earlylateApply').on('change',function (e) {

	var earlyLateValue  = this.value;
	var accept_earlyTime = '<?php echo $accept_earlyTimeplan ?>';
	var accept_lateTime = '<?php echo $accept_lateTimeplan ?>';	
	var accept_earlyTimeD = '<?php echo $acceptearlyDefault ?>';
	var accept_lateTimeD = '<?php echo $acceptLateDefault ?>';
	var earlytime =  accept_earlyTime;
	var latetime =  accept_lateTime;


	if(earlyLateValue == 'yes')
	{
		// no need to show yes in show 
		$('#earlyLateShowTable').css('visibility', 'hidden');
		$('#acceptEarlyTable').css('visibility', 'visible');
		// $('#accept_early').val(earlytime);
		$('#acceptLateTable').css('visibility', 'visible');
		// $('#accept_late').val(latetime);
		console.log('yessssss1');


	}
	else
	{
		$('#earlyLateShowTable').css('visibility', 'visible');
		$('#acceptEarlyTable').css('visibility', 'hidden');
		$('#acceptLateTable').css('visibility', 'hidden');
		$('#accept_early').val('');
		$('#accept_late').val('');
		console.log('yessssss2');


	}
});

$( document ).ready(function() {
    var earlyLateValue  = $('#earlylateApply').val();

    var accept_earlyTime = '<?php echo $accept_earlyTimeplan ?>';

	var accept_lateTime = '<?php echo $accept_lateTimeplan ?>';
	var earlytime =  accept_earlyTime;
	var latetime =  accept_lateTime;

	
	if(earlyLateValue == 'yes')
	{
		// no need to show yes in show 
		$('#earlyLateShowTable').css('visibility', 'hidden');
		$('#acceptEarlyTable').css('visibility', 'visible');
		$('#acceptLateTable').css('visibility', 'visible');
		// $('#accept_early').val(earlytime);
		// $('#accept_late').val(latetime);
		console.log('yessssss3');

	}
	else
	{
		$('#earlyLateShowTable').css('visibility','visible');
		$('#acceptEarlyTable').css('visibility', 'hidden');
		$('#acceptLateTable').css('visibility', 'hidden');
		$('#accept_early').val('');
		$('#accept_late').val('');
		console.log('yessssss4');

	}
});	


function disableUnchecked()
{

	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox = [];
	var checkedBoxs = [];
	var uncheckedBox = [];
	var uncheckedBoxs = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '';
					finalarray.push(text);
				}
			});	


			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;

				if(schedule == 'off')
				{

					if(yourArrayoff[0] == '0')
					{
						checkedBox.push('monday');
					}					
					if(yourArrayoff[1] == '0')
					{
						checkedBox.push('tuesday');
					}					
					if(yourArrayoff[2] == '0')
					{
						checkedBox.push('wednesday');
					}					
					if(yourArrayoff[3] == '0')
					{
						checkedBox.push('thursday');
					}					
					if(yourArrayoff[4] == '0')
					{
						checkedBox.push('friday');
					}					
					if(yourArrayoff[5] == '0')
					{
						checkedBox.push('saturday');
					}					
					if(yourArrayoff[6] == '0')
					{
						checkedBox.push('sunday');
					}


					if(jQuery.inArray("monday", checkedBox) != -1) {
					} 	
					else
					{
					    uncheckedBox.push('monday');
					}				

					if(jQuery.inArray("tuesday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('tuesday');
					}						

					if(jQuery.inArray("wednesday", checkedBox) != -1) {
					}
					else
					{
					    uncheckedBox.push('wednesday');
					}

					if(jQuery.inArray("thursday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('thursday');
					}

					if(jQuery.inArray("friday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('friday');
					}

					if(jQuery.inArray("saturday", checkedBox) != -1) {
					} 	
					else
					{
					    uncheckedBox.push('saturday');
					}				

					if(jQuery.inArray("sunday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('sunday');
					}

					$.each(uncheckedBox, function(key,value){

						var arrayCount = Object.keys(checkedBox).length;

						if(counter1 <= 2)
						{
							if(range == arrayCount)
							{
								$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
							}
							else
							{
								$('#'+value+'_td_'+'1').removeAttr("disabled", "disabled");
								$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
							}	
						}		
					});

				}
				else if (schedule != 'select')
				{	

					if(yourArrayoff[0] == '100')
					{
						checkedBoxs.push('monday');
					}					
					if(yourArrayoff[1] == '100')
					{
						checkedBoxs.push('tuesday');
					}					
					if(yourArrayoff[2] == '100')
					{
						checkedBoxs.push('wednesday');
					}					
					if(yourArrayoff[3] == '100')
					{
						checkedBoxs.push('thursday');
					}					
					if(yourArrayoff[4] == '100')
					{
						checkedBoxs.push('friday');
					}					
					if(yourArrayoff[5] == '100')
					{
						checkedBoxs.push('saturday');
					}					
					if(yourArrayoff[6] == '100')
					{
						checkedBoxs.push('sunday');
					}




					if(jQuery.inArray("monday", checkedBoxs) != -1) {
					} 	
					else
					{
					    uncheckedBoxs.push('monday');
					}				

					if(jQuery.inArray("tuesday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('tuesday');
					}						

					if(jQuery.inArray("wednesday", checkedBoxs) != -1) {
					}
					else
					{
					    uncheckedBoxs.push('wednesday');
					}

					if(jQuery.inArray("thursday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('thursday');
					}

					if(jQuery.inArray("friday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('friday');
					}

					if(jQuery.inArray("saturday", checkedBoxs) != -1) {
					} 	
					else
					{
					    uncheckedBoxs.push('saturday');
					}				

					if(jQuery.inArray("sunday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('sunday');
					}


					$.each(uncheckedBoxs, function(key,value){
						
						var arrayCounts = Object.keys(checkedBoxs).length;

						if(counter1 <= 2)
						{
							if(range == arrayCounts)
							{
								$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
							}
							else
							{
								$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
							}
						}

					});	

					$.each(checkedBoxs, function(key,value){

						var arrayCountss = Object.keys(checkedBoxs).length;

						if(counter1 <= 2)
						{
							if(range == arrayCountss)
							{
								$('#'+value+'_td_'+counter1).prop('checked', true);
							}
							else
							{
								// $('#'+value+'_td_'+counter1).prop('checked', false);
							}
						}
					});


				}
				


			});


}




$( document ).ready(function() {


	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox = [];
	var checkedBoxs = [];
	var uncheckedBox = [];
	var uncheckedBoxs = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '';
					finalarray.push(text);
				}
			});	


			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;

				if(schedule == 'off')
				{

					if(yourArrayoff[0] == '0')
					{
						checkedBox.push('monday');
					}					
					if(yourArrayoff[1] == '0')
					{
						checkedBox.push('tuesday');
					}					
					if(yourArrayoff[2] == '0')
					{
						checkedBox.push('wednesday');
					}					
					if(yourArrayoff[3] == '0')
					{
						checkedBox.push('thursday');
					}					
					if(yourArrayoff[4] == '0')
					{
						checkedBox.push('friday');
					}					
					if(yourArrayoff[5] == '0')
					{
						checkedBox.push('saturday');
					}					
					if(yourArrayoff[6] == '0')
					{
						checkedBox.push('sunday');
					}


					if(jQuery.inArray("monday", checkedBox) != -1) {
					} 	
					else
					{
					    uncheckedBox.push('monday');
					}				

					if(jQuery.inArray("tuesday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('tuesday');
					}						

					if(jQuery.inArray("wednesday", checkedBox) != -1) {
					}
					else
					{
					    uncheckedBox.push('wednesday');
					}

					if(jQuery.inArray("thursday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('thursday');
					}

					if(jQuery.inArray("friday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('friday');
					}

					if(jQuery.inArray("saturday", checkedBox) != -1) {
					} 	
					else
					{
					    uncheckedBox.push('saturday');
					}				

					if(jQuery.inArray("sunday", checkedBox) != -1) {
					} 
					else
					{
					    uncheckedBox.push('sunday');
					}

					$.each(uncheckedBox, function(key,value){

						var arrayCount = Object.keys(checkedBox).length;

						if(counter1 <= 2)
						{
							if(range == arrayCount)
							{
								$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
							}
							else
							{
								$('#'+value+'_td_'+'1').removeAttr("disabled", "disabled");
								$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
							}	
						}		
					});

				}
				else if (schedule != 'select')
				{	

					if(yourArrayoff[0] == '100')
					{
						checkedBoxs.push('monday');
					}					
					if(yourArrayoff[1] == '100')
					{
						checkedBoxs.push('tuesday');
					}					
					if(yourArrayoff[2] == '100')
					{
						checkedBoxs.push('wednesday');
					}					
					if(yourArrayoff[3] == '100')
					{
						checkedBoxs.push('thursday');
					}					
					if(yourArrayoff[4] == '100')
					{
						checkedBoxs.push('friday');
					}					
					if(yourArrayoff[5] == '100')
					{
						checkedBoxs.push('saturday');
					}					
					if(yourArrayoff[6] == '100')
					{
						checkedBoxs.push('sunday');
					}




					if(jQuery.inArray("monday", checkedBoxs) != -1) {
					} 	
					else
					{
					    uncheckedBoxs.push('monday');
					}				

					if(jQuery.inArray("tuesday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('tuesday');
					}						

					if(jQuery.inArray("wednesday", checkedBoxs) != -1) {
					}
					else
					{
					    uncheckedBoxs.push('wednesday');
					}

					if(jQuery.inArray("thursday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('thursday');
					}

					if(jQuery.inArray("friday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('friday');
					}

					if(jQuery.inArray("saturday", checkedBoxs) != -1) {
					} 	
					else
					{
					    uncheckedBoxs.push('saturday');
					}				

					if(jQuery.inArray("sunday", checkedBoxs) != -1) {
					} 
					else
					{
					    uncheckedBoxs.push('sunday');
					}


					$.each(uncheckedBoxs, function(key,value){
						
						var arrayCounts = Object.keys(checkedBoxs).length;

						if(counter1 <= 2)
						{
							if(range == arrayCounts)
							{
								$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
							}
							else
							{
								$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
							}
						}

					});	

					$.each(checkedBoxs, function(key,value){

						var arrayCountss = Object.keys(checkedBoxs).length;

						if(counter1 <= 2)
						{
							if(range == arrayCountss)
							{
								$('#'+value+'_td_'+counter1).prop('checked', true);
							}
							else
							{
								// $('#'+value+'_td_'+counter1).prop('checked', false);
							}
						}
					});


				}
				


			});
});


function disableUnchecked34()
{


	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox34 = [];
	var checkedBoxs34 = [];
	var uncheckedBox34 = [];
	var uncheckedBoxs34 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	

			console.log(finalarray);




			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=3 && counter1 <= 4)
				{
					if(schedule == 'off')
					{

						if(yourArrayoff[7] == '0')
						{
							checkedBox34.push('monday');
						}					
						if(yourArrayoff[8] == '0')
						{
							checkedBox34.push('tuesday');
						}					
						if(yourArrayoff[9] == '0')
						{
							checkedBox34.push('wednesday');
						}					
						if(yourArrayoff[10] == '0')
						{
							checkedBox34.push('thursday');
						}					
						if(yourArrayoff[11] == '0')
						{
							checkedBox34.push('friday');
						}					
						if(yourArrayoff[12] == '0')
						{
							checkedBox34.push('saturday');
						}					
						if(yourArrayoff[13] == '0')
						{
							checkedBox34.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox34) != -1) {
						} 	
						else
						{
						    uncheckedBox34.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox34) != -1) {
						}
						else
						{
						    uncheckedBox34.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox34) != -1) {
						} 	
						else
						{
						    uncheckedBox34.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('sunday');
						}






						$.each(uncheckedBox34, function(key,value){

							var arrayCount = Object.keys(checkedBox34).length;
							if(counter1 >= 3 && counter1 <= 4)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'3').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						console.log(yourArrayoff);
						if(yourArrayoff[7] == '100' )
						{
							checkedBoxs34.push('monday');
						}					
						if(yourArrayoff[8] == '100' )
						{
							checkedBoxs34.push('tuesday');
						}					
						if(yourArrayoff[9] == '100' )
						{
							checkedBoxs34.push('wednesday');
						}					
						if(yourArrayoff[10] == '100' )
						{
							checkedBoxs34.push('thursday');
						}					
						if(yourArrayoff[11] == '100' )
						{
							checkedBoxs34.push('friday');
						}					
						if(yourArrayoff[12] == '100' )
						{
							checkedBoxs34.push('saturday');
						}					
						if(yourArrayoff[13] == '100' )
						{
							checkedBoxs34.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs34) != -1) {
						} 	
						else
						{
						    uncheckedBoxs34.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs34) != -1) {
						}
						else
						{
						    uncheckedBoxs34.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs34) != -1) {
						} 	
						else
						{
						    uncheckedBoxs34.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('sunday');
						}

						$.each(uncheckedBoxs34, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs34).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs34, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs34).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


			});



}


$( document ).ready(function() {


	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox34 = [];
	var checkedBoxs34 = [];
	var uncheckedBox34 = [];
	var uncheckedBoxs34 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	

			console.log(finalarray);




			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=3 && counter1 <= 4)
				{
					if(schedule == 'off')
					{

						if(yourArrayoff[7] == '0')
						{
							checkedBox34.push('monday');
						}					
						if(yourArrayoff[8] == '0')
						{
							checkedBox34.push('tuesday');
						}					
						if(yourArrayoff[9] == '0')
						{
							checkedBox34.push('wednesday');
						}					
						if(yourArrayoff[10] == '0')
						{
							checkedBox34.push('thursday');
						}					
						if(yourArrayoff[11] == '0')
						{
							checkedBox34.push('friday');
						}					
						if(yourArrayoff[12] == '0')
						{
							checkedBox34.push('saturday');
						}					
						if(yourArrayoff[13] == '0')
						{
							checkedBox34.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox34) != -1) {
						} 	
						else
						{
						    uncheckedBox34.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox34) != -1) {
						}
						else
						{
						    uncheckedBox34.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox34) != -1) {
						} 	
						else
						{
						    uncheckedBox34.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox34) != -1) {
						} 
						else
						{
						    uncheckedBox34.push('sunday');
						}






						$.each(uncheckedBox34, function(key,value){

							var arrayCount = Object.keys(checkedBox34).length;
							if(counter1 >= 3 && counter1 <= 4)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'3').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						console.log(yourArrayoff);
						if(yourArrayoff[7] == '100' )
						{
							checkedBoxs34.push('monday');
						}					
						if(yourArrayoff[8] == '100' )
						{
							checkedBoxs34.push('tuesday');
						}					
						if(yourArrayoff[9] == '100' )
						{
							checkedBoxs34.push('wednesday');
						}					
						if(yourArrayoff[10] == '100' )
						{
							checkedBoxs34.push('thursday');
						}					
						if(yourArrayoff[11] == '100' )
						{
							checkedBoxs34.push('friday');
						}					
						if(yourArrayoff[12] == '100' )
						{
							checkedBoxs34.push('saturday');
						}					
						if(yourArrayoff[13] == '100' )
						{
							checkedBoxs34.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs34) != -1) {
						} 	
						else
						{
						    uncheckedBoxs34.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs34) != -1) {
						}
						else
						{
						    uncheckedBoxs34.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs34) != -1) {
						} 	
						else
						{
						    uncheckedBoxs34.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs34) != -1) {
						} 
						else
						{
						    uncheckedBoxs34.push('sunday');
						}

						$.each(uncheckedBoxs34, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs34).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs34, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs34).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


			});

});

function disableUnchecked56()
{
	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox56 = [];
	var checkedBoxs56 = [];
	var uncheckedBox56 = [];
	var uncheckedBoxs56 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	

			console.log(finalarray);




			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=5 && counter1 <= 6)
				{
					console.log(counter1);
					if(schedule == 'off')
					{

						if(yourArrayoff[14] == '0')
						{
							checkedBox56.push('monday');
						}					
						if(yourArrayoff[15] == '0')
						{
							checkedBox56.push('tuesday');
						}					
						if(yourArrayoff[16] == '0')
						{
							checkedBox56.push('wednesday');
						}					
						if(yourArrayoff[17] == '0')
						{
							checkedBox56.push('thursday');
						}					
						if(yourArrayoff[18] == '0')
						{
							checkedBox56.push('friday');
						}					
						if(yourArrayoff[19] == '0')
						{
							checkedBox56.push('saturday');
						}					
						if(yourArrayoff[20] == '0')
						{
							checkedBox56.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox56) != -1) {
						} 	
						else
						{
						    uncheckedBox56.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox56) != -1) {
						}
						else
						{
						    uncheckedBox56.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox56) != -1) {
						} 	
						else
						{
						    uncheckedBox56.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('sunday');
						}






						$.each(uncheckedBox56, function(key,value){

							var arrayCount = Object.keys(checkedBox56).length;
							if(counter1 >= 5 && counter1 <= 6)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'5').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						console.log(yourArrayoff);
						if(yourArrayoff[14] == '100' )
						{
							checkedBoxs56.push('monday');
						}					
						if(yourArrayoff[15] == '100' )
						{
							checkedBoxs56.push('tuesday');
						}					
						if(yourArrayoff[16] == '100' )
						{
							checkedBoxs56.push('wednesday');
						}					
						if(yourArrayoff[17] == '100' )
						{
							checkedBoxs56.push('thursday');
						}					
						if(yourArrayoff[18] == '100' )
						{
							checkedBoxs56.push('friday');
						}					
						if(yourArrayoff[19] == '100' )
						{
							checkedBoxs56.push('saturday');
						}					
						if(yourArrayoff[20] == '100' )
						{
							checkedBoxs56.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs56) != -1) {
						} 	
						else
						{
						    uncheckedBoxs56.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs56) != -1) {
						}
						else
						{
						    uncheckedBoxs56.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs56) != -1) {
						} 	
						else
						{
						    uncheckedBoxs56.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('sunday');
						}

						$.each(uncheckedBoxs56, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs56).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs56, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs56).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


			});	
}

$( document ).ready(function() {

	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox56 = [];
	var checkedBoxs56 = [];
	var uncheckedBox56 = [];
	var uncheckedBoxs56 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	

			console.log(finalarray);




			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=5 && counter1 <= 6)
				{
					console.log(counter1);
					if(schedule == 'off')
					{

						if(yourArrayoff[14] == '0')
						{
							checkedBox56.push('monday');
						}					
						if(yourArrayoff[15] == '0')
						{
							checkedBox56.push('tuesday');
						}					
						if(yourArrayoff[16] == '0')
						{
							checkedBox56.push('wednesday');
						}					
						if(yourArrayoff[17] == '0')
						{
							checkedBox56.push('thursday');
						}					
						if(yourArrayoff[18] == '0')
						{
							checkedBox56.push('friday');
						}					
						if(yourArrayoff[19] == '0')
						{
							checkedBox56.push('saturday');
						}					
						if(yourArrayoff[20] == '0')
						{
							checkedBox56.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox56) != -1) {
						} 	
						else
						{
						    uncheckedBox56.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox56) != -1) {
						}
						else
						{
						    uncheckedBox56.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox56) != -1) {
						} 	
						else
						{
						    uncheckedBox56.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox56) != -1) {
						} 
						else
						{
						    uncheckedBox56.push('sunday');
						}






						$.each(uncheckedBox56, function(key,value){

							var arrayCount = Object.keys(checkedBox56).length;
							if(counter1 >= 5 && counter1 <= 6)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'5').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						// console.log(yourArrayoff);
						if(yourArrayoff[14] == '100' )
						{
							checkedBoxs56.push('monday');
						}					
						if(yourArrayoff[15] == '100' )
						{
							checkedBoxs56.push('tuesday');
						}					
						if(yourArrayoff[16] == '100' )
						{
							checkedBoxs56.push('wednesday');
						}					
						if(yourArrayoff[17] == '100' )
						{
							checkedBoxs56.push('thursday');
						}					
						if(yourArrayoff[18] == '100' )
						{
							checkedBoxs56.push('friday');
						}					
						if(yourArrayoff[19] == '100' )
						{
							checkedBoxs56.push('saturday');
						}					
						if(yourArrayoff[20] == '100' )
						{
							checkedBoxs56.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs56) != -1) {
						} 	
						else
						{
						    uncheckedBoxs56.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs56) != -1) {
						}
						else
						{
						    uncheckedBoxs56.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs56) != -1) {
						} 	
						else
						{
						    uncheckedBoxs56.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs56) != -1) {
						} 
						else
						{
						    uncheckedBoxs56.push('sunday');
						}

						$.each(uncheckedBoxs56, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs56).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs56, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs56).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


			});	

});


function disableUnchecked78()
{
	var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox78 = [];
	var checkedBoxs78 = [];
	var uncheckedBox78 = [];
	var uncheckedBoxs78 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	





			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=7 && counter1 <= 8)
				{
					if(schedule == 'off')
					{

						if(yourArrayoff[21] == '0')
						{
							checkedBox78.push('monday');
						}					
						if(yourArrayoff[22] == '0')
						{
							checkedBox78.push('tuesday');
						}					
						if(yourArrayoff[23] == '0')
						{
							checkedBox78.push('wednesday');
						}					
						if(yourArrayoff[24] == '0')
						{
							checkedBox78.push('thursday');
						}					
						if(yourArrayoff[25] == '0')
						{
							checkedBox78.push('friday');
						}					
						if(yourArrayoff[26] == '0')
						{
							checkedBox78.push('saturday');
						}					
						if(yourArrayoff[27] == '0')
						{
							checkedBox78.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox78) != -1) {
						} 	
						else
						{
						    uncheckedBox78.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox78) != -1) {
						}
						else
						{
						    uncheckedBox78.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox78) != -1) {
						} 	
						else
						{
						    uncheckedBox78.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('sunday');
						}






						$.each(uncheckedBox78, function(key,value){

							var arrayCount = Object.keys(checkedBox78).length;
							if(counter1 >= 7 && counter1 <= 8)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'7').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						if(yourArrayoff[21] == '100' )
						{
							checkedBoxs78.push('monday');
						}					
						if(yourArrayoff[22] == '100' )
						{
							checkedBoxs78.push('tuesday');
						}					
						if(yourArrayoff[23] == '100' )
						{
							checkedBoxs78.push('wednesday');
						}					
						if(yourArrayoff[24] == '100' )
						{
							checkedBoxs78.push('thursday');
						}					
						if(yourArrayoff[25] == '100' )
						{
							checkedBoxs78.push('friday');
						}					
						if(yourArrayoff[26] == '100' )
						{
							checkedBoxs78.push('saturday');
						}					
						if(yourArrayoff[27] == '100' )
						{
							checkedBoxs78.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs78) != -1) {
						} 	
						else
						{
						    uncheckedBoxs78.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs78) != -1) {
						}
						else
						{
						    uncheckedBoxs78.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs78) != -1) {
						} 	
						else
						{
						    uncheckedBoxs78.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('sunday');
						}

						$.each(uncheckedBoxs78, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs78).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs78, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs78).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


		});	
}



$( document ).ready(function() {

		var yourArrayoff = [];
	var yourArraysel = [];
	var youroff = [];
	var yourschedule = [];
	var finalarray = [];
	var count =1;
	var checkedBox78 = [];
	var checkedBoxs78 = [];
	var uncheckedBox78 = [];
	var uncheckedBoxs78 = [];
	$('#schedulesTable > tbody  > tr').each(function() 
		{
			
			var schedule = $(this).find(".addBOnC").val();
			// get if monday is off or working 

			var counter = count ++ ;

			if(schedule == 'off')
			{
				// Case Monday 
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '0';
					yourArrayoff.push(Monday); 
				}else{
					var Monday = '100';
					yourArrayoff.push(Monday);
				}

				// Case Tuesday
				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '0'; 
					yourArrayoff.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArrayoff.push(Tuesday);
				}
				

				// Case Wednesday
				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '0';
					yourArrayoff.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArrayoff.push(Wednesday);
				}
										

				// Case Thursday
				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '0'; 
					yourArrayoff.push(Thursday);
				}else{
					var Thursday = '100';
					yourArrayoff.push(Thursday);
				}
										

				// Case Friday
				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '0'; 
					yourArrayoff.push(Friday);
				}else{
					var Friday = '100';
					yourArrayoff.push(Friday);
				}
				

				// Case Saturday
				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '0'; 
					yourArrayoff.push(Saturday);
				}else{
					var Saturday = '100';
					yourArrayoff.push(Saturday);
				}
										
				// Case Sunday
				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '0'; 
					yourArrayoff.push(Sunday);
				}else{
					var Sunday = '100';
					yourArrayoff.push(Sunday);
				}

				youroff.push(schedule);
				

			}
			else if (schedule != 'select')
			{
				if($('#monday_td_'+counter).is(':checked'))
				{
					var Monday = '1';
					yourArraysel.push(Monday); 
				}else{
					var Monday = '100';
					yourArraysel.push(Monday);
				}
										

				if($('#tuesday_td_'+counter).is(':checked'))
				{
					var Tuesday = '1'; 
					yourArraysel.push(Tuesday); 
				}else{
					var Tuesday = '100';
					yourArraysel.push(Tuesday);
				}
										

				if($('#wednesday_td_'+counter).is(':checked'))
				{
					var Wednesday = '1'; 
					yourArraysel.push(Wednesday); 
				}else{
					var Wednesday = '100';
					yourArraysel.push(Wednesday);
				}
								

				if($('#thursday_td_'+counter).is(':checked'))
				{
					var Thursday = '1'; 
					yourArraysel.push(Thursday); 
				}else{
					var Thursday = '100';
					yourArraysel.push(Thursday);
				}
										

				if($('#friday_td_'+counter).is(':checked'))
				{
					var Friday = '1'; 
					yourArraysel.push(Friday);
				}else{
					var Friday = '100';
					yourArraysel.push(Friday);
				}
										

				if($('#saturday_td_'+counter).is(':checked'))
				{
					var Saturday = '1';
					yourArraysel.push(Saturday); 
				}else{
					var Saturday = '100';
					yourArraysel.push(Saturday);
				}
										

				if($('#sunday_td_'+counter).is(':checked'))
				{
					var Sunday = '1'; 
					yourArraysel.push(Sunday);
				}else{
					var Sunday = '100';
					yourArraysel.push(Sunday);
				}

				yourschedule.push(schedule);
				
			}
			else if (schedule == 'select')
			{
				return;
			}

		});

			$.each(yourArrayoff, function(key,value){

				if(value > yourArraysel[key]){

					finalarray.push(yourArraysel[key]);

				}else if(value < yourArraysel[key]){

					finalarray.push(value);

				}else{
					var text = '2';
					finalarray.push(text);
				}
			});	





			var count1 =1;
			$('#schedulesTable > tbody  > tr').each(function() 
			{
				var schedule = $(this).find(".addBOnC").val();
				var range = $(this).find(".hiderange").val();

				var counter1 = count1++;
				if(counter1 >=7 && counter1 <= 8)
				{
					if(schedule == 'off')
					{

						if(yourArrayoff[21] == '0')
						{
							checkedBox78.push('monday');
						}					
						if(yourArrayoff[22] == '0')
						{
							checkedBox78.push('tuesday');
						}					
						if(yourArrayoff[23] == '0')
						{
							checkedBox78.push('wednesday');
						}					
						if(yourArrayoff[24] == '0')
						{
							checkedBox78.push('thursday');
						}					
						if(yourArrayoff[25] == '0')
						{
							checkedBox78.push('friday');
						}					
						if(yourArrayoff[26] == '0')
						{
							checkedBox78.push('saturday');
						}					
						if(yourArrayoff[27] == '0')
						{
							checkedBox78.push('sunday');
						}





						if(jQuery.inArray("monday", checkedBox78) != -1) {
						} 	
						else
						{
						    uncheckedBox78.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBox78) != -1) {
						}
						else
						{
						    uncheckedBox78.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBox78) != -1) {
						} 	
						else
						{
						    uncheckedBox78.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBox78) != -1) {
						} 
						else
						{
						    uncheckedBox78.push('sunday');
						}






						$.each(uncheckedBox78, function(key,value){

							var arrayCount = Object.keys(checkedBox78).length;
							if(counter1 >= 7 && counter1 <= 8)
							{
								if(range == arrayCount)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+'7').removeAttr("disabled", "disabled");
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}	
							}		
						});

					}
					else if (schedule != 'select')
					{	

						if(yourArrayoff[21] == '100' )
						{
							checkedBoxs78.push('monday');
						}					
						if(yourArrayoff[22] == '100' )
						{
							checkedBoxs78.push('tuesday');
						}					
						if(yourArrayoff[23] == '100' )
						{
							checkedBoxs78.push('wednesday');
						}					
						if(yourArrayoff[24] == '100' )
						{
							checkedBoxs78.push('thursday');
						}					
						if(yourArrayoff[25] == '100' )
						{
							checkedBoxs78.push('friday');
						}					
						if(yourArrayoff[26] == '100' )
						{
							checkedBoxs78.push('saturday');
						}					
						if(yourArrayoff[27] == '100' )
						{
							checkedBoxs78.push('sunday');
						}


						if(jQuery.inArray("monday", checkedBoxs78) != -1) {
						} 	
						else
						{
						    uncheckedBoxs78.push('monday');
						}				

						if(jQuery.inArray("tuesday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('tuesday');
						}						

						if(jQuery.inArray("wednesday", checkedBoxs78) != -1) {
						}
						else
						{
						    uncheckedBoxs78.push('wednesday');
						}

						if(jQuery.inArray("thursday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('thursday');
						}

						if(jQuery.inArray("friday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('friday');
						}

						if(jQuery.inArray("saturday", checkedBoxs78) != -1) {
						} 	
						else
						{
						    uncheckedBoxs78.push('saturday');
						}				

						if(jQuery.inArray("sunday", checkedBoxs78) != -1) {
						} 
						else
						{
						    uncheckedBoxs78.push('sunday');
						}

						$.each(uncheckedBoxs78, function(key,value){
							
							var arrayCounts = Object.keys(checkedBoxs78).length;

								if(range == arrayCounts)
								{
									$('#'+value+'_td_'+counter1).attr("disabled", "disabled");
								}
								else
								{
									$('#'+value+'_td_'+counter1).removeAttr("disabled", "disabled");
								}
							

						});	

						$.each(checkedBoxs78, function(key,value){

							var arrayCountss = Object.keys(checkedBoxs78).length;

							
								if(range == arrayCountss)
								{
									$('#'+value+'_td_'+counter1).prop('checked', true);
								}
								else
								{
									// $('#'+value+'_td_'+counter1).prop('checked', false);

								}
							
						});


					}
			}
				


		});	
});



$( document ).ready(function() {

	// check checkboxes on load 

	if($('#noscan1').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}
	else if($('#scan2c').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}	
	else if($('#scan4c').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}
	else
	{
		// $("#submitBtn").attr("disabled", "disabled");
	}
});

function onchangecheckboxscan() 
{
	// check checkbox on change 


	// run modal ok and cancel if user selects ok then continue if selects cancel then return false 


	$('ul#requiredList').empty();


	$('ul#requiredList').append('<li>If you continue to change the scan frequency all previous selected data on the Edit Shift Schedule page will be removed.</li>');
	$('#okbuttonModal').css('display','');
	$('#modalPara').html('Following points are required to be noticed:')


	$("#modalalert").modal('toggle');


	if($('#noscan1').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}
	else if($('#scan2c').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}	
	else if($('#scan4c').is(":checked"))
	{
		$("#submitBtn").removeAttr("disabled");
	}
	else
	{
		// $("#submitBtn").attr("disabled", "disabled");
		$('ul#requiredList').empty();

		$('#okbuttonModal').css('display','none');

		if($('.check').is(':checked')){}
		else
		{
			$('ul#requiredList').append('<li>Please select a scan frequency</li>');
		}
		$('#modalPara').html('Following points are required to start the cycle:')
		$("#modalalert").modal('toggle');

	}

}

function savetheData()
{
	// click on update and hide the message 
	$('#hiddenClickVal').val('1');
	$('#submitBtn').click();

}

function checkcheckedbox()
{

	var attr1  = $('#noscan1').attr('checked');
	var attr2  = $('#scan2c').attr('checked');
	var attr3  = $('#scan4c').attr('checked');

	if(attr1 == 'checked')
	{
		$('#noscan1').not(this).prop('checked', true); 
		$('#scan2c').not(this).prop('checked', false); 
		$('#scan4c').not(this).prop('checked', false); 
	}
	else if(attr2 == 'checked')
	{
		$('#scan2c').not(this).prop('checked', true);
		$('#noscan1').not(this).prop('checked', false); 
		$('#scan4c').not(this).prop('checked', false); 
	}
	else if(attr3 == 'checked')
	{
		$('#scan4c').not(this).prop('checked', true);
		$('#noscan1').not(this).prop('checked', false); 
		$('#scan2c').not(this).prop('checked', false); 
	}

}



function openselectyearbox()
{
	$('#modalOverwrites').modal('show');
}

function savedatatoinput()
{
	// 1 = yes 
	// 2 = no 
	var selectedCondition = $('#selectyearCondition').val();
	$('#hidden_next_year_condition').val(selectedCondition);




	var baseUrl = (window.location).href; // You can also use document.URL
	var koopId = baseUrl.substring(baseUrl.lastIndexOf('=') + 1);

	console.log(koopId);

	// if yes get the ssdata of previous year


	$.ajax({
		url: "ajax/update_ssdata.php",
		type: 'POST',
		data: {'selectedCondition':selectedCondition,'koopId':koopId},

			success: function(result){
			if(result == 'success'){

				location.reload();

				// setTimeout(function(){ myfunction(); }, 3000);
			}
		}
	});



}




</script>



