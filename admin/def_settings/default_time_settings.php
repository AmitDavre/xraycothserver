<?
	$team_bg[] = '#EC6F86';
	$team_bg[] = '#FFDD75';
	$team_bg[] = '#9FF3C3';
	$team_bg[] = '#FE816D';
	$team_bg[] = '#DAFF75';
	$team_bg[] = '#6AECF4';
	$team_bg[] = '#FFBA6D';
	$team_bg[] = '#B2F068';
	$team_bg[] = '#45B4E7';
	$team_bg[] = '#ADA7FC';
	$team_bg[] = '#D187EF';
	$team_bg[] = '#FDA6F8';

	$shiftplan = getDefaultShiftplan();
	if(!$shiftplan){$shiftplan = array();}
	//unset($shiftplan['NS8'], $shiftplan['DS12'], $shiftplan['NS12'], $shiftplan['OFF']);
	//var_dump($shiftplan); exit;
	
	//$weekdays = array(1=>"Monday", 2=>"Tuesday", 3=>"Wednesday", 4=>"Thursday", 5=>"Friday", 6=>"Saterday", 7=>"Sunday");
	//$day = array(1=>'day',2=>'days',3=>'days',4=>'days',5=>'days',6=>'days',7=>'days',8=>'days',9=>'days');

	$dayrange = hoursRange( 21600, 64800, 60 * 30, 'h:i a' );
	$shiftrange = hoursRange( 0, 86400, 60 * 30, 'h:i a' );
	$breakrange = hoursRange( 1800, 7200, 60 * 5, 'h:i a' );
	$halfrange = hoursRange( 7200, 28800, 60 * 30, 'h:i a' );
	//var_dump($breakrange);

	$shiftteam = array();
	$sql = "SELECT * FROM rego_default_shiftplans";
	if($res = $dba->query($sql)){
		while($row = $res->fetch_assoc()){
			$shiftteam[$row['id']] = unserialize($row['plan']);
			//$shiftdata[$row['code']] = unserialize($row['dates']);

			$shift_schedule[] = $row;
		}
	}
	//unset($shiftplan['']);
	//var_dump($shiftteam); exit;
	//var_dump($shiftdata);
	
	$time_settings = getDefaultTimeSettings();
	//var_dump($time_settings);
	$var_allow = array();
	if($time_settings){
		$var_allow = unserialize($time_settings['var_allow']);
	}

	$shift_type = array(
		'nw'=>'Normal workweek',
		'12d'=>'12 Hours day shift',
		'12n'=>'12 Hours night shift',
		'3x8'=>'3 x 8 Hours shift',
		'2x8'=>'2 x 8 Hours shift',
		'2x12'=>'2 x 12 Hours shift',
		'rd'=>'Running days');	
		
		//'fly'=>'Flying shift plan',	
	$shiftplan_list = getShiftplanList($cid);
	//var_dump($shiftplan_list);
	//$goto[date(($_SESSION['xhr']['cur_year']-1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['xhr']['cur_year']-1);
	foreach($months as $k=>$v){
		$goto[date($_SESSION['RGadmin']['cur_year'].'-'.sprintf('%02d', $k).'-01')] = $v.' '.$_SESSION['RGadmin']['cur_year'];
	}
	//var_dump($goto);
	//$goto[date(($_SESSION['xhr']['cur_year']+1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['xhr']['cur_year']+1);
	
	$data = array();
	$sql = "SELECT * FROM rego_default_leave_time_settings";//.$_SESSION['xhr']['cur_year']."`";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$data['accept_late'] = $row['accept_late'];
			$data['accept_early'] = $row['accept_early'];
			$data['ot_start_after'] = $row['ot_start_after'];
			$data['ot_period'] = $row['ot_period'];
			$data['scan_system'] = $row['scan_system'];
			$data['otnd'] = $row['otnd'];
			$otsa = unserialize($row['otsa']);
			$otsu = unserialize($row['otsu']);
			$othd = unserialize($row['othd']);
			$comments = unserialize($row['comments']);
		}
	}
	//var_dump($data); exit;
	if(!$comments){
		$comments[1] = array('th'=>'','en'=>'');
	}
	$lng['xxx'] = '';


	// echo '<pre>';
	// print_r($shift_schedule);
	// echo '</pre>';
	// die();
?>
 
	<link rel="stylesheet" type="text/css" href="../assets/css/jquery-clockpicker.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/fullcalendar.css?<?=time()?>">

		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="../assets/css/myStyle.css?<?=time()?>">
		<link rel="stylesheet" href="../assets/css/myBootstrap.css?<?=time()?>">

	<script src="../assets/js/popper.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<script src="../assets/js/bootstrap-confirmation.js"></script>

<style>
	.fc-event {
	  position: relative;
	  display: block;
	  font-size: 20px;
	  line-height:28px;
	  xheight:62px;
	  border:0px red solid;
	  background-color: transparent;
	  border-radius:0;
	  color:#999;
	  font-weight: 400;
	  white-space:normal;
	  text-align:left;
	  padding:0 5px;
	  top:-25px;
	  cursor:default !important;
	}
	.fc-event .fc-title {
	  padding:0;
	  cursor:default !important;
	}
	.fc-event .fc-title span {
	  font-size: 12px;
	  line-height:14px;
	  display:block;
	  color:#999;
	  background:#eee;
	  font-weight:500;
	  padding:3px 5px;
	  border-radius:1px;
	  white-space:normal;
	  border-left:5px solid rgba(0,0,0,0.2);
	  cursor:default !important;
	}
	.fc-event .fc-title span.fc-payday {
	  color:#fff;
	  background: #356e35;
	}
	.fc-event .fc-title span.fc-nonwork {
	  color:#fff;
	  background: #57889c;
	}
	.fc-event .fc-title span.fc-holiday {
	  color: #fff;
	  background: #ac5287;
	}
	.fc-event .fc-title span.fc-leave {
	  color: #fff;
	  background: #b09b5b;
	}
	.fc-event:hover {
	  color:#c00;
	}
	.fc-toolbar {
		xdisplay:none;
		xbackground:red;
		margin:0 !important;
		xborder:1px red solid !important;
	}
	.fc-toolbar h2 {
	  text-shadow: none !important;
	  margin:0 !important;
	  padding:0 !important;
	  display:block !important;
	  font-size: 24px !important;
	  background:transparent !important;
	  border:0 !important;
	  font-weight: 600 !important;
	  line-height:40px !important;
	  color:#333 !important;
	}
	.fc-sat, .fc-sun {
	}
	.fc-week-number {
		font-weight:700;
		color: #0099CC;
		cursor:default !important;
	}
	td.fc-sat, td.fc-sun {
	  xbackground-color: #eee;
	}
	.fc-day-number {
	  padding: 3px 7px 0 0 !important;
	  font-weight:600;
	  color:#b00;
	  font-size:16px;
	  cursor:default !important;
	}
	.fc-day {
	  cursor:default !important;
	}
	.fc-unthemed .fc-disabled-day {
		opacity: 0.5;
		background: #fff url(../images/bg-disabled.png);
	}
	.confDelete {
		background:red;
		border:0;
	}
	
</style>

	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;<?=$lng['Default time settings']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<ul style="position:relative" class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" href="#tab_shiftplan" data-toggle="tab">Working hours</a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_shiftteams" data-toggle="tab">Shift schedule</a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_shiftcalendar" data-toggle="tab">Shift calendar</a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_settings" data-toggle="tab">Other settings</a></li>
			<!--<li class="nav-item"><a class="nav-link" href="#tab_compensations" data-toggle="tab">Compensations</a></li>-->
			<li class="nav-item"><a class="nav-link" href="#tab_comments" data-toggle="tab">Comments</a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 40px)">

				<div class="tab-pane" id="tab_shiftplan">
			
				<form id="shiftplanForm" style="height:100%">
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<div style="overflow-x:auto; height:100%">
				<table id="workTable" class="basicTable inputs" border="0">
					<thead>
					<tr >
						<th width="5%">&nbsp;<?=$lng['Code']?>&nbsp;</th>
						<th><?=$lng['Description']?></th>
						<th colspan="2">
							<table width="100%">	
								<tr>
									<td><p><b></b></p></td>
									<td><p><b></b></p></td>
									<td><p><b><?=$lng['First']?></b></p></td>
									<td><p><b></b></p></td>
									<td><p><b></b></p></td>
								</tr>
								<tr>
									<td><p><b><?=$lng['From']?></b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b><?=$lng['Until']?></b></p></td>
								</tr>
							</table>
						</th>						
						<th colspan="2">
							<table width="100%">	
								<tr>
									<td><p><b></b></p></td>
									<td><p><b></b></p></td>
									<td><p><b><?=$lng['Second']?></b></p></td>
									<td><p><b></b></p></td>
									<td><p><b></b></p></td>
								</tr>
								<tr>
									<td><p><b><?=$lng['From']?></b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b> </b></p></td>
									<td><p><b><?=$lng['Until']?></b></p></td>
								</tr>
							</table>
						</th>
						<th><?=$lng['First Total']?></th>
						<th><?=$lng['Second Total']?></th>
						<th><?=$lng['Hours Worked']?></th>
						<th><?=$lng['Lunch Break']?></th>
						<th><?=$lng['Addit break']?></th>
						<th><span><i data-placement="left" data-toggle="tooltip" title="Delete file" class="fa fa-trash fa-lg"></i></span></th>
					</tr>
					</thead>
					<tbody id="tbodyO">
						<? $nr = 0; foreach($shiftplan as $key=>$val){ ?>
						<tr id="<?php echo $key;?>">
							<td class="input" style="background:<?=$team_bg[$nr]?>">
								<input readonly class="nofocus tac" style="min-width:50px; font-weight:600; background:transparent" type="text" name="shiftplan[<?=$key?>][code]" value="<?=$val['code']?>" />
								<input type="hidden" name="shiftplan[<?=$key?>][bg]" value="<?=$team_bg[$nr]?>" />
							</td>
							<td class="input">
								<input  <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="min-width:200px" type="text" name="shiftplan[<?=$key?>][name]" value="<?=$val['name']?>" />
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic f1" type="text" name="shiftplan[<?=$key?>][f1]" value="<?=$val['f1']?>" />
									<input type="hidden" class="f1hidden" value="<?=$val['f1']?>">
								</div>
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic u1" type="text" name="shiftplan[<?=$key?>][u1]" value="<?=$val['u1']?>" />
									<input type="hidden" class="u1hidden" value="<?=$val['u1']?>">

								</div>
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic f2" type="text" name="shiftplan[<?=$key?>][f2]" value="<?=$val['f2']?>" />
									<input type="hidden" class="f2hidden" value="<?=$val['f2']?>">

								</div>
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic u2" type="text" name="shiftplan[<?=$key?>][u2]" value="<?=$val['u2']?>" />
									<input type="hidden" class="u2hidden" value="<?=$val['u2']?>">

								</div>
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input  <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic first" type="text" name="shiftplan[<?=$key?>][first]" value="<?=$val['first']?>" />
									<input type="hidden" class="firsthidden" value="<?=$val['first']?>">
									<input type="hidden" class="firstThidden" value="<?=$val['firstThidden']?>" name="shiftplan[<?=$key?>][firstThidden]">

								</div>
							</td>
							<td class="input">
								<div class="clockpicker">
									<button type="button"><i class="fa fa-clock-o"></i></button>
									<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic second" type="text" name="shiftplan[<?=$key?>][second]" value="<?=$val['second']?>" />
									<input type="hidden" class="secondhidden" value="<?=$val['second']?>">
									<input type="hidden" class="secondThidden" value="<?=$val['secondThidden']?>" name="shiftplan[<?=$key?>][secondThidden]">

								</div>
							</td>
							<td class="input">
								<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="margin-top:2px; font-weight:600" readonly class="nofocus net_hours" name="shiftplan[<?=$key?>][hours]" type="text" value="<?=$val['hours']?>" />
							</td>
							<td class="input">
								<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="margin-top:2px; font-weight:400" readonly class="nofocus break" name="shiftplan[<?=$key?>][break]" type="text" value="<?=$val['break']?>" />
							</td>
							<td class="input">
								<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="margin-top:2px; font-weight:400" readonly class="nofocus addbreak" name="shiftplan[<?=$key?>][addbreak]" type="text" value="<?=$val['addbreak']?>" />
								<input type="hidden" class="hiddenaddbreak" value="">
							</td>
							<td style="text-align: center;vertical-align: center;" >
			<!-- 					<?php 
									if (in_array($val['code'], $wh_code)) {
									    echo '<span style="text-align:center;"><input type ="checkbox" id="status_checkbox" /></span>';
									}
									else
									{
										echo '<span style="text-align:center;"><input disabled="disabled" type ="checkbox" id="status_checkbox"  checked="checked"/></span>';
									}
								?> -->

								<span><a data-id="<?php echo $key; ?>" class="deleteFiles"><i style="color: #005588;" class="fa fa-trash fa-lg"></i></a></span>


							</td>
						</tr>
						<? $nr++; if($nr >=12){$nr = 0;}} ?>
					</tbody>
				</table>
				<button style="margin:10px 0 20px 0" class="btn btn-primary btn-xs" type="button" id="addworkhours"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add row']?></button>
				</div>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_shiftteams" style="height:100%; overflow-y:auto">
				<table id="shift_schedule_table" class="basicTable" border="0" style="margin-bottom:10px;">
					<thead>
						<tr>
							<!-- <th><?=$lng['xxx']?>ID</th>
							<th><?=$lng['xxx']?>Code</th>
							<th><?=$lng['xxx']?>Name</th>
							<th><?=$lng['xxx']?>Start date</th>
							<th><?=$lng['xxx']?>Shift type</th>
							<th style="width:90%"><?=$lng['xxx']?>Description</th>
							<th><a><i data-toggle="tooltip" title="<?=$lng['xxx']?>Edit" class="fa fa-edit fa-lg"></i></a></th>
							<th><i data-toggle="tooltip" title="<?=$lng['xxx']?>Delete" class="fa fa-trash fa-lg"></i></th> -->

							<th><?=$lng['Team Code']?></th>
							<th><?=$lng['Team Name']?></th>
							<th style="width:90%"><?=$lng['Shift Schedule Description']?></th>
							<th><a data-toggle="tooltip" title="<?=$lng['Edit']?>"><i class="fa fa-edit fa-lg"></i></a></th>
							<th><a data-toggle="tooltip" title="<?=$lng['Delete']?>"><i class="fa fa-trash fa-lg"></i></a></th>

						</tr>
					</thead>
					<tbody>
					<? if(!$shift_schedule){
							echo '<td colspan="7" style="padding:5px 10px; color:#b00;font-weight:600">'.$lng['xxx'].'>No data available in database</td>';
						}else{
						foreach($shift_schedule as $k=>$v){ ?>
						<tr id="<?php echo $v['id'];?>">
							<td style="display: none;"><?=$v['id']?></td>
							<td style="text-transform: uppercase;"><?=$v['id']?></td>
							<td><?=$v['name']?></td>
							<td><?=$v['description']?></td>
							<td><a><i data-id="<?php echo $v['id'];?>" class="editShiftplan fa fa-edit fa-lg"></i></a></td>
							<td><a data-id="<?php echo $v['id'];?>" class="delShiftplan" href="#"><i class="fa fa-trash fa-lg"></i></a></td>
						
						</tr>
					<? } } ?>
					</tbody>
				</table>
				<!-- <button class="btn btn-primary btn-xs" id="addnew" type="submit"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?=$lng['xxx']?>Add new</button> -->
			
				<div style="display:none; margin-top:20px" id="addnewplan">
					<form id="teamForm">
					<table class="basicTable inputs" border="0" style="margin-bottom:10px">
						<thead>
						<tr>
							<th style="min-width:50px;"><?=$lng['ID']?> <i class="man"></i></th>
							<th style="min-width:100px;"><?=$lng['xxx']?>Code <i class="man"></i></th>
							<th style="min-width:200px;"><?=$lng['xxx']?>Name <i class="man"></i></th>
							<th><?=$lng['xxx']?>Shift type <i class="man"></i></th>
							<th><?=$lng['xxx']?>Start date <i class="man"></i></th>
							<th class="oodays" style="display:none"><?=$lng['xxx']?>ON days <i class="man"></i></th>
							<th class="oodays" style="display:none"><?=$lng['xxx']?>OFF days <i class="man"></i></th>
							<th><?=$lng['xxx']?>Apply holidays <i class="man"></i></th>
							<th style="width:90%"><?=$lng['xxx']?>Description</th>
						</tr>
						</thead>
						<tbody>
							<td>
								<input maxlength="5" placeholder="ID" type="text" name="id" id="id" value="" />
							</td>
							<td>
								<input maxlength="10" placeholder="Code" type="text" name="code" id="code" value="" />
							</td>
							<td class="">
								<input placeholder="Name" type="text" name="name" id="name" value="" />
							</td>
							<td>
								<select name="shiftType" id="shiftType">
									<option selected value="0"><?=$lng['xxx']?>Select</option>
									<? foreach($shift_type as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?> (<?=$k?>)</option>
									<? } ?>
								</select>
							</td>
							<td>
								<div class="clockpicker" style="width:105px">
									<button type="button"><i class="fa fa-calendar"></i></button>
									<input placeholder="Startdate" readonly class="date_no_monday" type="text" name="startdate" id="startdate" value="" />
								</div>
							</td>
							<td class="oodays" style="display:none">
								<select name="ondays" id="ondays">
									<option selected value="0"><?=$lng['xxx']?>Select</option>
									<option value="1">1 <?=$lng['xxx']?>day</option>
									<? for($i=2;$i<=7;$i++){ ?>
											<option value="<?=$i?>"><?=$i?> days</option>
									<? } ?>
								</select>
							</td>
							<td class="oodays" style="display:none">
								<select name="offdays" id="offdays">
									<option selected value="0">Select</option>
									<option value="1">1 day</option>
									<? for($i=2;$i<=7;$i++){ ?>
											<option value="<?=$i?>"><?=$i?> days</option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="holidays" id="holidays" style="width:100%">
									<option selected value="0">Select</option>
									<? foreach($yesno as $k=>$v){ ?>
											<option value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
							<td>
								<input placeholder="Description" type="text" name="description" id="description" maxlength="255" value="" />
							</td>
						</tbody>
					</table>
			
					<table id="runningTable" class="basicTable inputs" border="0" style="margin-bottom:10px; display:none">
						<thead>
							<tr>
								<th><?=$lng['xxx']?>Running days</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="padding:4px 10px !important"><i style="color:#ccc"><?=$lng['xxx']?>Select ON days</i></td>
							</tr>	
						</tbody>
					</table>
			
					<table id="shiftTable" class="basicTable inputs" border="0">
						<thead>
							<tr>
								<th style="min-width:30px"><?=$lng['xxx']?>Week</th>
								<th id="quantTH" style="min-width:30px; display:none"><?=$lng['xxx']?>Quantity</th>
								<? foreach($weekdays as $k=>$v){ ?>
									<th><?=$v?></th>
								<? } ?>
								<th style="width:90%"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="9" style="padding:4px 10px !important"><i style="color:#ccc"><?=$lng['xxx']?>Select shift type</i></td>
							</tr>
						</tbody>
					</table>
			
					<table border="0" style="width:100%; margin-top:10px">
						<tr>
							<td style="vertical-align:top; padding-right:10px" width="1">
								<button class="btn btn-primary submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?> shiftteam</button>
							</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
			
			<div class="tab-pane" id="tab_shiftcalendar" style="position:relative">
				<table border="0" style="width:100%; position:absolute; left:0; top:5px">
					<tr>
						<td>
							<select id="cshiftType" class="button">
								<option disabled selected value="0"><?=$lng['xxx']?>Select shiftplan</option>
								<? foreach($shiftplan_list as $k=>$v){ ?>
										<option value="<?=$k?>"><?=$v?> (<?=$k?>)</option>
								<? } ?>
							</select>
						</td>
						<td style="padding-left:10px">
							<select id="gotoMonth" class="button">
								<option disabled selected value="0"><?=$lng['xxx']?>Select Month</option>
								<? foreach($goto as $k=>$v){ ?>
										<option value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td style="width:90%"></td>
						<td style="white-space:nowrap">
							<button class="btn btn-primary btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></button>
							<button class="btn btn-primary btn-xs" id="btn-today"> <?=$lng['Today']?> </button>
							<button class="btn btn-primary btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
				</table>
				<div id="calendar"></div>
			</div>
			
			<div class="tab-pane" id="tab_settings" style="height:100%; overflow-y:auto">
				<form id="otherForm">
				
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

				<table class="basicTable inputs" border="0">
					<tbody>
						<tr>
							<th><?=$lng['xxx']?>Acceptable late</th>
							<td>
								<input class="numeric sel tar" style="width:40px" type="text" name="accept_late" value="<?=$data['accept_late']?>"> Minutes
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>Acceptable early</th>
							<td>
								<input class="numeric sel tar" style="width:40px" type="text" name="accept_early" value="<?=$data['accept_early']?>"> Minutes
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT start after</th>
							<td>
								<input class="numeric sel tar" style="width:40px" type="text" name="ot_start_after" value="<?=$data['ot_start_after']?>"> Minutes
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT periods</th>
							<td>
								<input class="numeric sel tar" style="width:40px" type="text" name="ot_period" value="<?=$data['ot_period']?>"> Minutes
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT on working day</th>
							<td>
								<Select name="otnd" style="width:100%">
									<option <? if($data['otnd']=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($data['otnd']=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($data['otnd']=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($data['otnd']=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($data['otnd']=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT on saterday</th>
							<td>
								<Select name="otsa[hrs]" style="width:auto">
									<option value="0"><?=$lng['xxx']?>Normal Hrs</option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($otsa['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">First '.$i.' Hrs</option>';
									} ?>
								</Select>
								<Select name="otsa[1]" style="width:auto">
									<option <? if($otsa[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($otsa[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($otsa[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($otsa[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($otsa[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
								<?=$lng['xxx']?>Then : 
								<Select name="otsa[2]" style="width:auto">
									<option <? if($otsa[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($otsa[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($otsa[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($otsa[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($otsa[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT on sunday</th>
							<td>
								<Select name="otsu[hrs]" style="width:auto">
									<option value="0"><?=$lng['xxx']?>Normal Hrs</option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($otsu['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">First '.$i.' Hrs</option>';
									} ?>
								</Select>
								<Select name="otsu[1]" style="width:auto">
									<option <? if($otsu[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($otsu[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($otsu[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($otsu[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($otsu[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
								Then : 
								<Select name="otsu[2]" style="width:auto">
									<option <? if($otsu[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($otsu[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($otsu[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($otsu[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($otsu[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>OT on holidays</th>
							<td>
								<Select name="othd[hrs]" style="width:auto">
									<option value="0"><?=$lng['xxx']?>Normal Hrs</option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($othd['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">First '.$i.' Hrs</option>';
									} ?>
								</Select>
								<Select name="othd[1]" style="width:auto">
									<option <? if($othd[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($othd[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($othd[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($othd[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($othd[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
								<?=$lng['xxx']?>Then : 
								<Select name="othd[2]" style="width:auto">
									<option <? if($othd[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['xxx']?>N/A</option>
									<option <? if($othd[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['xxx']?>OT 1</option>
									<option <? if($othd[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['xxx']?>OT 1.5</option>
									<option <? if($othd[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['xxx']?>OT 2</option>
									<option <? if($othd[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['xxx']?>OT 3</option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['xxx']?>Time scan system</th>
							<td>
								<Select name="scan_system" style="width:100%">
								<? foreach($scan_system as $k=>$v){
										echo '<option ';
										if($data['scan_system']==$k){echo 'selected';}
										if($k=='other'){echo 'disabled';}
										echo ' value="'.$k.'">'.$v.'</option>';
								} ?>
								</Select>
							</td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_comments">
				<form id="commentForm">
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
				<table id="commentTable" class="basicTable inputs" border="0">
					<thead>
						<tr>
							<th style="width:1px">#</th>
							<th style="width:25%"><?=$lng['Thai description']?></th>
							<th style="width:25%"><?=$lng['English description']?></th>
							<th style="width:50%"></th>
						</tr>
					</thead>
					<tbody>
					<? if($comments){ foreach($comments as $k=>$v){ ?>
						<tr>
							<td class="tac"><b><?=$k?></b></td>
							<td><input name="comments[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
							<td><input name="comments[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
							<td></td>
						</tr>
					<? } } ?>
					</tbody>
				</table>
				
				<div style="height:10px"></div>
				<button class="btn btn-primary btn-xs" type="button" id="addComment"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add row']?></button>
				</form>
			</div>
			
		</div>
	
	</div>
	
	<script type="text/javascript" src="../assets/js/jquery-clockpicker.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src='../assets/js/fullcalendar.js'></script>
	<? if($lang == 'th'){ ?>
	<script type="text/javascript" src="../assets/js/fullcalendar-th.js?<?=time()?>"></script>
	<? } ?>

<script>
	
	var heights = window.innerHeight-275;

		function getTimeInterval(f1, u1, f2, u2,f1hidden,u1hidden,first,firstThidden,firstold,secondold,secondThidden,addbreak){
		var s1 = moment.utc(f1, "HH:mm");
		var e1 = moment.utc(u1, "HH:mm");
		var d1 = moment.duration(e1.diff(s1));
		var t1 = moment.utc(+d1).format('HH:mm');

		
		var s2 = moment.utc(f2, "HH:mm");
		var e2 = moment.utc(u2, "HH:mm");
		var d2 = moment.duration(e2.diff(s2));
		var t2 = moment.utc(+d2).format('HH:mm');
		
		var b1 = moment.utc(u1, "HH:mm");
		var b2 = moment.utc(f2, "HH:mm");
		var br = moment.duration(b2.diff(b1));
		var br = moment.utc(+br).format('HH:mm');
		
		var hrs = moment.utc(t1, "HH:mm").add(moment.duration(t2))
		var hrs = moment.utc(+hrs).format('HH:mm');

		var f1hidden = moment.utc(f1hidden, "HH:mm");
		var u1hidden = moment.utc(u1hidden, "HH:mm");
		var first 	 = moment.utc(first, "HH:mm");
		var addbreak 	 = moment.utc(addbreak, "HH:mm");
		var firstThidden 	 = moment.utc(firstThidden, "HH:mm");
		var secondThidden 	 = moment.utc(secondThidden, "HH:mm");
		var firstold 	 = moment.utc(firstold, "HH:mm");
		var secondold 	 = moment.utc(secondold, "HH:mm");

		var t3 = moment.duration(f1hidden.diff(s1));
		var t4 = moment.duration(e1.diff(u1hidden));


		var t5 = moment.utc(+t3).format('HH:mm'); // goes into addit
		var t6 = moment.utc(+t4).format('HH:mm'); // goes into addit


		var t10 = moment.utc(t5, "HH:mm").add(moment.duration(t6))
		var ft = moment.utc(+t10).format('HH:mm'); // first addit total // goes to addit 

		var t7 = moment.utc(t1, "HH:mm");

		var convertT5 =  moment.utc(ft, "HH:mm");

		var t9 = moment.duration(t7.diff(convertT5));
		// var firstTotal = moment.utc(+t9).format('HH:mm'); // first total 

		var subtract = moment.duration(firstThidden.diff(firstold));
		var subFormat = moment.utc(+subtract).format('HH:mm'); // first addit


		var convertSub = moment.utc(subFormat, "HH:mm");

		var newsubtract = moment.duration(firstThidden.diff(convertSub));
		var ft1 = moment.utc(+newsubtract).format('HH:mm');  // new f total


		var t11 = moment.utc(t2, "HH:mm"); 

		var subtract2 = moment.duration(secondThidden.diff(secondold));
		var subFormat2 = moment.utc(+subtract2).format('HH:mm'); // second addit

		var convertSub2 = moment.utc(subFormat2, "HH:mm");

		var newsubtract2 = moment.duration(secondThidden.diff(convertSub2));
		var ft2 = moment.utc(+newsubtract2).format('HH:mm');  // new s total


		var t13 = moment.utc(convertSub, "HH:mm").add(moment.duration(subFormat2));
		var t14 = moment.utc(+t13).format('HH:mm'); 	
		var t17 = moment.utc(+addbreak).format('HH:mm'); 	

		var t15 = moment.utc(t17, "HH:mm").add(moment.duration(subFormat2));
		var t16 = moment.utc(+t15).format('HH:mm'); 
		var t18 = moment.utc(+firstold).format('HH:mm'); 
		var t19 = moment.utc(+secondold).format('HH:mm'); 


		var t20 = moment.utc(t18, "HH:mm").add(moment.duration(t19));
		var t21 = moment.utc(+t20).format('HH:mm'); 	


		// alert(hrs)
		return [hrs, br, t1, t2,ft1,ft2,t14,t16,t18,t21];
	}


	
	function bindClockPicker() {
		$('.timePic').clockpicker({
			autoclose: true,
		});
		$('.f1, .u1, .f2, .u2').on("change", function(){
			//alert('hrs')

		
			var f1 = $(this).closest('tr').find('.f1').val();
			var u1 = $(this).closest('tr').find('.u1').val();
			var f2 = $(this).closest('tr').find('.f2').val();
			var u2 = $(this).closest('tr').find('.u2').val();
			var firstold = $(this).closest('tr').find('.first').val();
			var secondold = $(this).closest('tr').find('.second').val();

			var f1hidden = $(this).closest('tr').find('.f1hidden').val();
			var u1hidden = $(this).closest('tr').find('.u1hidden').val();
			var first = $(this).closest('tr').find('.first').val();
			var firstThidden = $(this).closest('tr').find('.firstThidden').val();		


			var f2hidden 	  = $(this).closest('tr').find('.f2hidden').val();
			var u2hidden 	  = $(this).closest('tr').find('.u2hidden').val();
			var second 		  = $(this).closest('tr').find('.second').val();
			var secondThidden = $(this).closest('tr').find('.secondThidden').val();
			var addbreak = $(this).closest('tr').find('.addbreak').val();

			// var hrss = getTimeInterval
			var hrs = getTimeInterval(f1,u1,f2,u2,f1hidden,u1hidden,first,firstThidden,firstold,secondold,secondThidden,addbreak);

			// console.log(hrs);

			// alert(hrs);	


			$(this).closest('tr').find('.net_hours').val(hrs[0]);
			$(this).closest('tr').find('.break').val(hrs[1]);


			$(this).closest('tr').find('.first').val(hrs[2]);

			$(this).closest('tr').find('.second').val(hrs[3]);
			$(this).closest('tr').find('.firstThidden').val(hrs[2]);
			$(this).closest('tr').find('.secondThidden').val(hrs[3]);
			$(this).closest('tr').find('.addbreak').val('00:00');
			
		});		

		$('.first').on("change", function(){

		
			var f1 = $(this).closest('tr').find('.f1').val();
			var u1 = $(this).closest('tr').find('.u1').val();
			var f2 = $(this).closest('tr').find('.f2').val();
			var u2 = $(this).closest('tr').find('.u2').val();
			var firstold = $(this).closest('tr').find('.first').val();
			var secondold = $(this).closest('tr').find('.second').val();

			var f1hidden = $(this).closest('tr').find('.f1hidden').val();
			var u1hidden = $(this).closest('tr').find('.u1hidden').val();
			var first = $(this).closest('tr').find('.first').val();
			var firstThidden = $(this).closest('tr').find('.firstThidden').val();		


			var f2hidden 	  = $(this).closest('tr').find('.f2hidden').val();
			var u2hidden 	  = $(this).closest('tr').find('.u2hidden').val();
			var second 		  = $(this).closest('tr').find('.second').val();
			var secondThidden = $(this).closest('tr').find('.secondThidden').val();
			var addbreak = $(this).closest('tr').find('.addbreak').val();

			var hrs = getTimeInterval(f1,u1,f2,u2,f1hidden,u1hidden,first,firstThidden,firstold,secondold,secondThidden,addbreak);



			// alert(hrs);


			$(this).closest('tr').find('.net_hours').val(hrs[0]);
			$(this).closest('tr').find('.break').val(hrs[1]);


			$(this).closest('tr').find('.first').val(firstold);
			$(this).closest('tr').find('.addbreak').val(hrs[6]);
			$(this).closest('tr').find('.hiddenaddbreak').val(hrs[6]);

			// $(this).closest('tr').find('.second').val(hrs[3]);
			$(this).closest('tr').find('.firstThidden').val(hrs[2]);
			$(this).closest('tr').find('.secondThidden').val(hrs[3]);
			$(this).closest('tr').find('.net_hours').val(hrs[9]);
			
		});

		$('.second').on("change", function(){

		
			var f1 = $(this).closest('tr').find('.f1').val();
			var u1 = $(this).closest('tr').find('.u1').val();
			var f2 = $(this).closest('tr').find('.f2').val();
			var u2 = $(this).closest('tr').find('.u2').val();
			var firstold = $(this).closest('tr').find('.first').val();
			var secondold = $(this).closest('tr').find('.second').val();

			var f1hidden = $(this).closest('tr').find('.f1hidden').val();
			var u1hidden = $(this).closest('tr').find('.u1hidden').val();
			var first = $(this).closest('tr').find('.first').val();
			var firstThidden = $(this).closest('tr').find('.firstThidden').val();		


			var f2hidden 	  = $(this).closest('tr').find('.f2hidden').val();
			var u2hidden 	  = $(this).closest('tr').find('.u2hidden').val();
			var second 		  = $(this).closest('tr').find('.second').val();
			var secondThidden = $(this).closest('tr').find('.secondThidden').val();
			var addbreak = $(this).closest('tr').find('.hiddenaddbreak').val();

			var hrs = getTimeInterval(f1,u1,f2,u2,f1hidden,u1hidden,first,firstThidden,firstold,secondold,secondThidden,addbreak);



			// alert(hrs);


			$(this).closest('tr').find('.net_hours').val(hrs[9]);
			$(this).closest('tr').find('.break').val(hrs[1]);


			$(this).closest('tr').find('.first').val(firstold);
			$(this).closest('tr').find('.addbreak').val(hrs[6]);
			$(this).closest('tr').find('.hiddenaddbreak').val(hrs[6]);


			$(this).closest('tr').find('.second').val(secondold);
			$(this).closest('tr').find('.firstThidden').val(hrs[2]);
			$(this).closest('tr').find('.secondThidden').val(hrs[3]);
			
		});
	}
	
	$(document).ready(function() {



		// function getTimeInterval(f1, u1, f2, u2){
		// 	var s1 = moment.utc('2018/01/01 '+f1, "YYYY/MM/DD HH:mm").toDate();
		// 	//alert(s1)
		// 	var e1 = moment.utc('2018/01/01 '+u1, "YYYY/MM/DD HH:mm").toDate();
		// 	if(moment(e1).isBefore(s1)){e1 = moment('2018/01/01 '+u1, "YYYY/MM/DD HH:mm").add(1, 'day')}//.format("YYYY/MM/DD HH:mm")}
		// 	//alert(e1)
		// 	var d1 = moment.duration(moment(e1,"YYYY/MM/DD HH:mm").diff(moment(s1,"YYYY/MM/DD HH:mm")))//.format("HH:mm")
		// 	var p1 = d1.format("HH:mm")
		// 	var s2 = moment.utc('2018/01/01 '+f2, "YYYY/MM/DD HH:mm").toDate();
		// 	if(moment(s2).isBefore(s1)){s2 = moment('2018/01/01 '+f2, "YYYY/MM/DD HH:mm").add(1, 'day')}//.format("YYYY/MM/DD HH:mm")}
		// 	//alert(s2)
		// 	var e2 = moment.utc('2018/01/01 '+u2, "YYYY/MM/DD HH:mm").toDate();
		// 	if(moment(e2).isBefore(s1)){e2 = moment('2018/01/01 '+u2, "YYYY/MM/DD HH:mm").add(1, 'day')}//.format("YYYY/MM/DD HH:mm")}
		// 	//alert(e2)
		// 	var d2 = moment.duration(moment(e2,"YYYY/MM/DD HH:mm").diff(moment(s2,"YYYY/MM/DD HH:mm")))//.format("HH:mm")
		// 	var p2 = d2.format("HH:mm")
		// 	var br = moment.utc(s2-e1).format("HH:mm")
		// 	d1.add(d2)
		// 	return [d1.format("HH:mm"), p1, p2, br];
		// }
	
		// function bindClockPicker() {
		// 	$('.timePic').clockpicker({
		// 		autoclose: true,
		// 	});
		// 	$('.f1, .u1, .f2, .u2').on("change", function(){
		// 		//alert('hrs')
		// 		var f1 = $(this).closest('tr').find('.f1').val();
		// 		var u1 = $(this).closest('tr').find('.u1').val();
		// 		var f2 = $(this).closest('tr').find('.f2').val();
		// 		var u2 = $(this).closest('tr').find('.u2').val();
		// 		var hrs = getTimeInterval(f1,u1,f2,u2);
		// 		//alert(hrs)
		// 		$(this).closest('tr').find('.net_hours').val(hrs[0]);
		// 		$(this).closest('tr').find('.break').val(hrs[3]);
		// 		$(this).closest('tr').find('.first').val(hrs[1]);
		// 		$(this).closest('tr').find('.second').val(hrs[2]);
		// 		//alert(hrs)
		// 	});
		// }
		
		$(document).on('click', '#addComment', function () {
			var comnr = 1+$('#commentTable tbody tr').length;
			if(comnr > 15){return false;}
			var row = '<tr>'+
					'<td class="tac"><b>'+comnr+'</b></td>'+
					'<td><input name="comments['+comnr+'][th]" type="text" /></td>'+
					'<td><input name="comments['+comnr+'][en]" type="text" /></td>'+
					'<td></td>'+
				'</tr>';
			$('#commentTable tbody').append(row);
		})
		
		$("#commentForm").submit(function(e){ 
			e.preventDefault();
			//$(".submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				url: "def_settings/ajax/update_default_comments.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
						})
					}
					//setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		});

		// $('.delShiftplan').confirmation({
		// 	container: 'body',
		// 	rootSelector: '.delShiftplan',
		// 	singleton: true,
		// 	animated: 'fade',
		// 	placement: 'left',
		// 	popout: true,
		// 	html: true,
		// 	title: '<?=$lng['Are you sure']?>',
		// 	btnOkIcon: '',
		// 	btnCancelIcon: '',
		// 	btnOkLabel: '<?=$lng['Delete']?>',
		// 	btnCancelLabel: '<?=$lng['Cancel']?>',
		// 	onConfirm: function() { 
		// 		//alert(id);
		// 		var plancode = $(this).data('code');
		// 		$.ajax({
		// 			url:AROOT+"def_settings/ajax/delete_def_shiftplan.php",
		// 			data:{code: plancode},
		// 			success: function(result){
		// 				//$("#dump").html(result);
		// 				location.reload();
		// 			}
		// 		});
		// 	}
		// });
		
		// $(document).on('click', '.editShiftplan', function () {
		// 	var id = $(this).data('id');
		// 	//alert(code);
		// 	$.ajax({
		// 		url:"def_settings/ajax/get_default_shiftplan.php",
		// 		data: {id: id},
		// 		dataType: 'json',
		// 		success: function(result){
		// 			//$("#dump").html(result);
		// 			//alert(result['week']['day'][1]);
		// 			//alert(code);
		// 			var shiftType = result['plan'].shiftType;
		// 			$("#id").val(result.id).prop('readonly', true);
		// 			$("#code").val(result['plan'].code);
		// 			$("#name").val(result.name);
		// 			$("#shiftType").val(shiftType).trigger('change');
		// 			$("#startdate").val(result['plan'].startdate);
		// 			$("#holidays").val(result['plan'].holidays);
		// 			$("#description").val(result.description);
					
		// 			if(shiftType == 'nw'){
		// 				$.each(result['plan']['week']['day'], function (k, v) {
		// 					$("select[name='week[day]["+k+"]']").val(v);
		// 				})
		// 			}
					
		// 			if(shiftType == '2x8' || shiftType == '3x8' || shiftType == '2x12'){
		// 				$.each(result['plan']['sequence'], function (k, v) {
		// 					$("select[name='sequence["+k+"]']").val(v);
		// 				})
		// 				$.each(result['plan']['quant'], function (k, v) {
		// 					$("select[name='quant["+k+"]']").val(v);
		// 				})
		// 				$.each(result['plan']['week'], function (key, val) {
		// 					$.each(val, function (k, v) {
		// 						$("select[name='week["+key+"]["+k+"]']").val(v);
		// 					})
		// 				})
		// 			}
					
		// 			if(shiftType == 'rd'){
		// 				$("#ondays").val(result['plan'].ondays).trigger('change');
		// 				$("#offdays").val(result['plan'].offdays);
		// 				$.each(result['plan']['day'], function (k, v) {
		// 					$("select[name='day["+k+"]']").val(v);
		// 				})
		// 			}
					
		// 			$("#addnewplan").slideDown(200);
		// 			//var data = jQuery.parseJSON(result);
		// 		},
		// 		error:function (xhr, ajaxOptions, thrownError){
		// 			alert(thrownError);
		// 		}
		// 	});
		// })
		
		$(document).on('change', '#cshiftType', function () {
			//alert($(this).val())
			$('#calendar').fullCalendar('refetchEvents');
		});
		$(document).on('change', '#gotoMonth', function () {
			$('#calendar').fullCalendar('gotoDate', $(this).val());
			$(this).val(0)
		});
		$('#calendar:not(".fc-event")').on('contextmenu', function (e) {
			 e.preventDefault()
		})
				
		$('#calendar').fullCalendar({
			header: {
				center: 'title',
				left: '',//'month,year',
				right: 'prev,today,next'
			},
			editable: false,
			weekends: true,
			weekNumbers: true,
			eventDurationEditable: false, // resize false
			//locale: 'th',
			firstDay: 1,
			droppable: false, 
			selectable: false,
			contentHeight: heights,
			//hiddenDays: [0,6],//<?//=$non_working_days?>,
			defaultDate: new Date(),
			/*visibleRange: {
				  start: '2019-01-01',
				  end: '2019-12-31'
			},*/			
			showNonCurrentDates: false,
			events: {
				url: AROOT+"def_settings/ajax/server_get_default_shiftplan.php",
				data: function(){
					return { code: $('#cshiftType').val() };
				}
			},
			eventRender: function (event, element, icon) {
				//if (!event.descr == "") {
					 //element.find('.fc-title').append('<br/><span class="' + event.class + '">' + event.descr + '</span>');
				//}
		  },
		  windowResize: function (event, ui) {
				$('#calendar').fullCalendar('render');
		  },
		});
		
		/* hide default buttons */
		$('.fc-right, .fc-left').hide();
		$('#btn-prev').click(function () {
			$('.fc-prev-button').click();
			return false;
		});
		$('#btn-next').click(function () {
			$('.fc-next-button').click();
			return false;
		});
		$('#btn-today').click(function () {
			$('.fc-today-button').click();
			return false;
		});
		
		var shiftplan = <?=json_encode($shiftplan)?>;	
		// var wnr = <?=json_encode(count($shiftplan))?>+1;
		// $("#addworkhours").click(function(){
		// 	var addrow = '<tr><td class="input"><input placeholder="Code" style="min-width:50px; font-weight:600" type="text" name="shiftplan['+wnr+'][code]" /></td>'
		// 	+ '<td class="input"><input placeholder="Description" style="min-width:200px" type="text" name="shiftplan['+wnr+'][name]" /></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f1" type="text" name="shiftplan['+wnr+'][f1]" value="00:00" /></div></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u1" type="text" name="shiftplan['+wnr+'][u1]" value="00:00" /></div></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f2" type="text" name="shiftplan['+wnr+'][f2]" value="00:00" /></div></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u2" type="text" name="shiftplan['+wnr+'][u2]" value="00:00" /></div></td>'
		// 	+ '<td class="input"><input style="margin-top:2px; font-weight:600" readonly class="nofocus net_hours" name="shiftplan['+wnr+'][hours]" type="text" value="00:00" /></td>'
		// 	+ '<td class="input"><input style="margin-top:2px; font-weight:400" readonly class="nofocus break" name="shiftplan['+wnr+'][break]" type="text" value="00:00" /></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic first" type="text" name="shiftplan['+wnr+'][first]" value="00:00" /></div></td>'
		// 	+ '<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic second" type="text" name="shiftplan['+wnr+'][second]" value="00:00" /></div></td>'
		// 	+'<td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic" type="text" name="shiftplan['+wnr+'][ot]" value="00:00" /></div></td>'
		// 	+'<td><Select name="shiftplan['+wnr+'][end]" style="width:auto"><option value="0">Same day</option><option value="1">Next day</option></Select></td>'
		// 	+'<td>'
		// 		+'<Select name="shiftplan['+wnr+'][scans]" style="min-width:100%; width:auto">'
		// 			+'<option value="2">2 <?=$lng['Scans per day']?></option>'
		// 			+'<option value="4">4 <?=$lng['Scans per day']?></option>'
		// 		+'</Select>'
		// 	+'</td><td></td></tr>';
			
		// 	$("#workTable tbody").append(addrow);
		// 	wnr ++;
		// 	bindClockPicker();
		// });

		var cnr = <?=json_encode($nr)?>;
		var team_bg = <?=json_encode($team_bg)?>;
		$("#addworkhours").click(function(){
			var wnr = $("#workTable tbody#tbodyO tr").length + 1;
			$(".submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);

			var addrow = '<tr><td class="input" style="background:'+team_bg[cnr]+'"><input type="hidden" name="shiftplan['+wnr+'][bg]" value="'+team_bg[cnr]+'" /><input maxlength="3" class="tac" placeholder="Code" style="min-width:50px; font-weight:600; background:transparent" type="text" name="shiftplan['+wnr+'][code]" value=""/></td><td class="input"><input style="min-width:200px" type="text" name="shiftplan['+wnr+'][name]" value="" /></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f1" type="text" name="shiftplan['+wnr+'][f1]" value="00:00" /><input type="hidden" class="f1hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u1" type="text" name="shiftplan['+wnr+'][u1]" value="00:00" /><input type="hidden" class="u1hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f2" type="text" name="shiftplan['+wnr+'][f2]" value="00:00" /><input type="hidden" class="f2hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u2" type="text" name="shiftplan['+wnr+'][u2]" value="00:00" /><input type="hidden" class="u2hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input   readonly class="timePic first" type="text" name="shiftplan['+wnr+'][first]" value="00:00" /><input type="hidden" class="firsthidden" value="00:00"><input type="hidden" class="firstThidden" value="00:00" name="shiftplan['+wnr+'][firstThidden]"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic second" type="text" name="shiftplan['+wnr+'][second]" value="00:00" /><input type="hidden" class="secondhidden" value="00:00"><input type="hidden" class="secondThidden" value="00:00" name="shiftplan['+wnr+'][secondThidden]"></div></td><td class="input"><input style="margin-top:2px; font-weight:600" readonly class="nofocus net_hours" name="shiftplan['+wnr+'][hours]" type="text" value="00:00" /></td><td class="input"><input style="margin-top:2px; font-weight:400" readonly class="nofocus break" name="shiftplan['+wnr+'][break]" type="text" value="00:00" /></td><td class="input"><input style="margin-top:2px; font-weight:400" readonly class="nofocus addbreak" name="shiftplan['+wnr+'][addbreak]" type="text" value="00:00" /><input type="hidden" class="hiddenaddbreak" value=""></td><td style="text-align:center!important;"><span><a data-id="shiftplan['+wnr+'][code]" class="deleteFiles"><i style="color: #005588;" class="fa fa-trash fa-lg"></i></a></span></td></tr>';
			
			$("#workTable tbody#tbodyO").append(addrow);
			wnr ++;
			cnr ++; if(cnr >=12){cnr = 0;}
			bindClockPicker();
		});
		
		$("#addnew").on('click', function(){ 
			$("#id").val('').prop('readonly', false);
			$("#code").val('');
			$("#name").val('');
			$("#shiftType").val(0).trigger('change');
			$("#startdate").val('');
			$("#holidays").val(0);
			$("#description").val('');
			$("#addnewplan").slideDown(200);
		});
			
		$("#shiftplanForm").submit(function(e){ 
			e.preventDefault();
			$(".submitbtn i").removeClass('fa-save');
			$(".submitbtn i").addClass('fa-repeat fa-spin');
			var data = $(this).serialize();
			$.ajax({
				url:AROOT+"def_settings/ajax/update_default_shiftplan.php",
				type: 'POST',
				data: data,
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$(".submitbtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');},500);
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
		
		$("#otherForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();
			//alert('data')
			$.ajax({
				url:AROOT+"def_settings/ajax/update_default_other_time_settings.php",
				data: data,
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$(".submitbtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');},500);
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
		
		$("#teamForm").submit(function(e){ 
			e.preventDefault();
			var err = true;
			var err2 = true;
			$("#message").hide();
			if($("#code").val() == ""){err = false;}
			if($("#name").val() == ""){err = false;}
			if($("#startdate").val() == ""){err = false;}
			if($("#shiftType").val() == 0){err = false;}
			if($("#holidays").val() == 0){err = false;}
			if($("#shiftType").val() == 'rd'){
				if($("#ondays").val() == 0){err = false;}
				if($("#offdays").val() == 0){err = false;}
				$(".rndays").each(function(){
					if($(this).val() == 0){err2 = false;}
				})
			}
			if(!err){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			if(!err2){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select working hours<? //=$lng['Please fill in required fields']?>',
					duration: 4,
				})
				return false;
			}
			//return false;
			$(".submitbtn i").removeClass('fa-save').addClass('fa-repeat fa-spin');
			//setTimeout(function(){
				var data = $(this).serialize();
				$.ajax({
					url:AROOT+"def_settings/ajax/update_default_shiftteam.php",
					type: 'POST',
					data: data,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})
							location.reload();
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								closeConfirm: true
							})
						}
						setTimeout(function(){$(".submitbtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');},500);
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
			//},50);
		});
		
		$('#shiftType, #ondays').on('change', function(){
			var type = $('#shiftType').val();
			//alert(type)
			$('#quantTH').css('display','none');
			var addRow = '';
			if(type!=0){
				$('#runningTable').css('display','none');
				$('#shiftTable').css('display','table');
				$('.oodays').css('display','none');
				//$('.date_month').datepicker('setDaysOfWeekDisabled', [0,2,3,4,5,6]);
				
				if(type=='3x8'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0">Sequence</option><option value="early">Early week</option><option value="late">Late week</option><option value="night">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[1][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0">Sequence</option><option value="early">Early week</option><option value="late">Late week</option><option value="night">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[2][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[3]"><option selected value="0">Sequence</option><option value="early">Early week</option><option value="late">Late week</option><option value="night">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[3]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[3][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='2x8'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0">Sequence</option><option value="early">Early week</option><option value="late">Late week</option><option value="night">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[1][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0">Sequence</option><option value="early">Early week</option><option value="late">Late week</option><option value="night">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[2][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='2x12'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0">Sequence</option><option value="day">Day week</option><option value="late">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[1][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0">Sequence</option><option value="night">Day week</option><option value="late">Night week</option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[2][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='fly'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td>1st Week</td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[1][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td>2th Week</td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="0">Select</option><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[2][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td>3th Week</td>';
					addRow += '<td class="input"><select name="quant[3]"><option value="0">Select</option><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[3][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					addRow += '<tr><td>4th Week</td>';
					addRow += '<td class="input"><select name="quant[4]"><option value="0">Select</option><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> weeks</option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[4][<?=$k?>]" id="workHrs" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='12d'){
					addRow = '<tr><td>Day week</td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[day][<?=$k?>]" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='12n'){
					addRow = '<tr><td>Night week</td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[night][<?=$k?>]" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='nw'){
					//$('.date_month').datepicker('setDaysOfWeekDisabled', []);
					addRow = '<tr><td style="padding:4px 10px !important">Normal workweek</td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[day][<?=$k?>]" style="width:auto"><option value="0">Non-working day</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					
				}else if(type=='rd'){
					$('.oodays').css('display','table-cell');
					$('#runningTable').css('display','table');
					$('#shiftTable').css('display','none');
					//$('.date_month').datepicker('setDaysOfWeekDisabled', []);
					var nr = $('#ondays').val();
					var thead = '<tr>';
					var tbody = '<tr>';
					if(nr>0){
						for(var i=1;i<=nr;i++){
							thead += '<th>Day '+i+'</th>';
						}
						thead += '<th style="width:90%"></th></tr>';
						for(var i=1;i<=nr;i++){
							tbody += '<td class="input"><select class="rndays" name="day['+i+']" style="width:auto"><option value="0">Select</option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td>';
						}
						tbody += '<td></td></tr>';
						$('#runningTable thead').html(thead);
						$('#runningTable tbody').html(tbody);
						return false;
					}
				}
			}
			$('#shiftTable tbody').html(addRow);
			
		});
		
		bindClockPicker();
		var activeTabTime = localStorage.getItem('activeTabTime');
		if(activeTabTime){
			$('.nav-link[href="' + activeTabTime + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_shiftplan"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabTime', $(e.target).attr('href'));
			//dtable.fnAdjustColumnSizing();
			$('#calendar').fullCalendar('render');
		});

		/*var activeTab = localStorage.getItem('activeTabTime');
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabTime', $(e.target).data('target'));
			$('#calendar').fullCalendar('render');
		});
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_shiftplan"]').tab('show');
		}*/

	});

	$(document).on('click', '.editShiftplan', function(e) {

		var id = $(this).closest('tr').find('td:eq(0)').text();

		window.location.href="index.php?mn=7002&id="+id;
	})		


	$(document).ajaxComplete(function( event,request, settings ) {

		$('.deleteFiles').confirmation({
			container: 'body',
			rootSelector: '.deleteFiles',
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

				// remove tr  and click on update 
				var plancode = $(this).data('id');
				$('table#workTable tr#'+plancode).remove();

				$(".submitBtn").trigger("click");

				// flash update 



	
			}
		})
	});				
$(document).ajaxComplete(function( event,request, settings ) {

		$('.delShiftplan').confirmation({
			container: 'body',
			rootSelector: '.delShiftplan',
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
				var plancode = $(this).data('id');

				// $.ajax({
				// 	url:AROOT+"def_settings/ajax/delete_def_shifschedule.php",
				// 	data:{code: plancode},
				// 	success: function(result){
				// 		//$("#dump").html(result);
				// 		if(result == 'ok')
				// 		{	
				// 			$('table#shift_schedule_table tr#'+plancode).remove();
				// 			$(".submitBtn").trigger("click");
				// 		}

				// 	}
				// });
			}
		})
	});


</script>	













