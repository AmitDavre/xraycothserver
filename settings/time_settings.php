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
	//$colors[] = '#4573E7';
	//$colors[] = '#7E69FF';
	//$colors[] = '#AD61ED';

	$shiftplan = getDefaultShiftplan();
	if(!$shiftplan){$shiftplan = array();}
	//unset($shiftplan['NS8'],$shiftplan['NS12'],$shiftplan['DS12'],$shiftplan['OFF']);
	unset($shiftplan['']);
	// var_dump($shiftplan); exit; //work_hrs


	$wh_code = array();
	// $sql1 = "SELECT * FROM ".$cid."_shiftplans_".$cur_year;
	// if($res1 = $dbc->query($sql1)){
	// 	while($row1 = $res1->fetch_assoc()){
	// 		$wh_code[] = $row1['wh_code'];
	// 	}
	// }
		



// echo '<pre>';
// print_r($shiftplan);
// print_r($wh_code);
// echo '</pre>';
// die();



	
	// foreach ($shiftplan as $key => $value) {



	// 			if (in_array($value['code'], $wh_code)) {
	// 			    echo 'active';
	// 			}
	// 			else
	// 			{
	// 				echo 'inactive';
	// 			}


		
			
	// 	}
	

	// die();





	$day = array(1=>$lng['day'],2=>$lng['days'],3=>$lng['days'],4=>$lng['days'],5=>$lng['days'],6=>$lng['days'],7=>$lng['days'],8=>$lng['days'],9=>$lng['days']);



		function hoursRange($lower = 0, $upper = 86400, $step = 1800, $format = 'h:i a'){
		 $times = array();
		 foreach(range($lower, $upper, $step) as $increment){
			  $increment = gmdate('H:i', $increment);
			  list($hour, $minutes) = explode(':', $increment);
			  $date = new DateTime($hour . ':' . $minutes);
			  $times[(string)$increment] = $date->format($format);
		 }
		 return $times;
	}	



	$dayrange = hoursRange( 21600, 64800, 60 * 30, 'h:i a' );
	$shiftrange = hoursRange( 0, 86400, 60 * 30, 'h:i a' );
	$breakrange = hoursRange( 1800, 7200, 60 * 5, 'h:i a' );
	$halfrange = hoursRange( 7200, 28800, 60 * 30, 'h:i a' );




	//var_dump($breakrange);

	$shiftteam = array();
	$shiftteams = array();
	$sql = "SELECT * FROM ".$cid."_shiftplans_".$cur_year;
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$shiftteam[$row['id']] = unserialize($row['plan']);
			$shiftteams[$row['id']] = unserialize($row['new_plan']);
			//$shiftdata[$row['code']] = unserialize($row['dates']);
		}
	}


	// fetch teams 
	$rowFt = array();
	$sqlFteams = "SELECT * FROM ".$cid."_shiftplans_".$cur_year." WHERE (new_plan ='' OR new_plan is NULL)";
	if($resFteams = $dbc->query($sqlFteams)){
		while($rowFteams = $resFteams->fetch_assoc()){

		
				$rowFt[$rowFteams['id']] = $rowFteams;



		}
	}	


	// fetch locations  
	$sqlFLoc = "SELECT * FROM ".$cid."_location";
	if($resFLoc = $dbc->query($sqlFLoc)){
		while($rowFLoc = $resFLoc->fetch_assoc()){

		
				$rowFLocc[] = $rowFLoc;



		}
	}	


	// fetch branches  
	$rowBt = array();
	$sqlBranches = "SELECT * FROM ".$cid."_branches";
	if($resBranches = $dbc->query($sqlBranches)){
		while($rowBranches = $resBranches->fetch_assoc()){
				$rowBt[$rowBranches['id']] = $rowBranches;
		}
	}


	// GET WORKING HOUR CODE FROM SHIFPLAN TABLE 






	//unset($shiftteam['']);
	//var_dump($shiftteam); exit;
	//var_dump($shiftdata);

		function getTimeSettings(){
		global $dbc;
		$row = array();
		if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['cid']."_leave_time_settings")){
			$row = $res->fetch_assoc();
		}
		return $row;
	}
	
	$time_settings = getTimeSettings();
	$var_allow = array();//unserialize($time_settings['var_allow']);

	$shift_type = array(
		'nw'=>$lng['Normal workweek'],
		'12d'=>$lng['12 Hours day shift'],
		'12n'=>$lng['12 Hours night shift'],
		'3x8'=>$lng['3 x 8 Hours shift'],
		'2x8'=>$lng['2 x 8 Hours shift'],
		'2x12'=>$lng['2 x 12 Hours shift'],
		'rd'=>$lng['Running days']);	
		
		//'fly'=>'Flying shift plan',


	function getShiftplanList($cid){
		global $dbc;
		$data = array();
		$sql = "SELECT * FROM ".$cid."_shiftplans_".$_SESSION['rego']['cur_year'];
		//$sql = "SELECT * FROM `".$cid."_shiftplans`";
		if($res = $dbc->query($sql)){
			while($row = $res->fetch_object()){
				$data[$row->code] = $row->description;
			}
		}
		return $data;
	}

	
	$shiftplan_list = getShiftplanList($cid);
	//var_dump($shiftplan_list);




	//$goto[date(($_SESSION['rego']['cur_year']-1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['rego']['cur_year']-1);
	foreach($months as $k=>$v){
		$goto[date($_SESSION['rego']['cur_year'].'-'.sprintf('%02d', $k).'-01')] = $v.' '.$_SESSION['rego']['cur_year'];
	}
	//var_dump($goto);
	//$goto[date(($_SESSION['rego']['cur_year']+1).'-'.sprintf('%02d', $k).'-01')] = $v.' '.($_SESSION['rego']['cur_year']+1);
	
	$data = array();
	$locations = array();
	$sql = "SELECT * FROM ".$cid."_leave_time_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data['time_start'] = $row['time_start'];
			$data['time_end'] = $row['time_end'];
			$data['accept_late'] = $row['accept_late'];
			$data['accept_early'] = $row['accept_early'];
			$data['ot_start_after'] = $row['ot_start_after'];
			$data['ot_period'] = $row['ot_period'];
			$data['ot_roundup'] = $row['ot_roundup'];
			$data['default_normal_hrs'] = $row['default_normal_hrs'];
			//$data['fixed_break'] = $row['fixed_break'];
			$data['scan_system'] = $row['scan_system'];
			$data['gps'] = $row['gps'];
			$data['qrcode'] = $row['qrcode'];
			$data['perimeter'] = $row['perimeter'];
			$data['default_scan_perimeter'] = $row['default_scan_perimeter'];
			$var_allow = unserialize($row['var_allow']);
			$compensations = unserialize($row['compensations']);
			$locations = unserialize($row['scan_locations']);
			$data['otnd'] = $row['otnd'];
			$otsa = unserialize($row['otsa']);
			$otsu = unserialize($row['otsu']);
			$othd = unserialize($row['othd']);
			$comments = unserialize($row['comments']);
		}
	}




	//$locations = array();
	if(!$locations){
		for($i=1;$i<=5;$i++){
			$locations[$i]['name'] = '';
			$locations[$i]['code'] = '';
			$locations[$i]['qr'] = '';
			$locations[$i]['latitude'] = '';
			$locations[$i]['longitude'] = '';
			$locations[$i]['perimeter'] = '';
		}
	}


	for($i=1;$i<=5;$i++){
		$scan_locations[$i] = array('name'=>$locations[$i]['name'],'latitude'=>$locations[$i]['latitude'],'longitude'=>$locations[$i]['longitude']);
	}
	//echo json_encode($scan_locations);
	//var_dump($locations); exit;
	// 	echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';
	// die();


	$var_allow = getUsedVarAllow($lang);
	//var_dump($compensations); exit;
	//$compensations = getCompensations();
	//var_dump($compensations); exit;
	
	$condition[''] = '';
	$condition['presence'] = 'Presence';
	$condition['nolateearly'] = 'No late/early';
	
	if(!$comments){
		$comments[1] = array('th'=>'','en'=>'');
	}
	//var_dump($comments); exit;


	// Get File images
	$fsv2011ImagePath    =  'https://www.'.$_SERVER['HTTP_HOST'].'/hr/assets/uploads/fsv2011.jpeg';
	$exportexcelimg    =  'https://www.'.$_SERVER['HTTP_HOST'].'/hr/assets/uploads/exportexcel.png';

	// Get File Path 
	$fsv2011FilePath     =  'https://www.'.$_SERVER['HTTP_HOST'].'/hr/assets/uploads/Fingerprint Scan.txt';
	$exportexcelFilePath     =  'https://www.'.$_SERVER['HTTP_HOST'].'/hr/assets/uploads/Scan_Data.xlsx';


	// GET the value of checked checkboxes in scan systems

		$sqlCheckbox = "SELECT * FROM ".$cid."_leave_time_settings WHERE id= '1'";

	if($resCheckbox = $dbc->query($sqlCheckbox)){
		while($rowCheckbox = $resCheckbox->fetch_assoc()){
				$checkboxArray = unserialize($rowCheckbox['compensations']);
		}
	}

	
	$sbranches = str_replace(',', "','", $_SESSION['rego']['sel_branches']);
	$sdivisions = str_replace(',', "','", $_SESSION['rego']['sel_divisions']);
	$sdepartments = str_replace(',', "','", $_SESSION['rego']['sel_departments']);
	$steams = str_replace(',', "','", $_SESSION['rego']['sel_teams']);
	
	$where = "emp_group = '".$_SESSION['rego']['emp_group']."'";
	$where .= " AND branch IN ('".$sbranches."')";
	$where .= " AND division IN ('".$sdivisions."')";
	$where .= " AND department IN ('".$sdepartments."')";
	$where .= " AND team IN ('".$steams."')";
	
	$res1 = $dbc->query("SELECT * FROM ".$cid."_employees WHERE ".$where." ORDER BY emp_id ASC");
	if($res1->num_rows > 0){
		while($row1 = $res1->fetch_assoc()){



			$image = $row1['image'];
			if(empty($row1['image'])){$image = 'images/profile_image.jpg';}



			
			$emp_array[] = array('data'=>$row1['emp_id'], 'value'=>$row1['emp_id'].' - '.$row1[$lang.'_name'], 'name'=>$row1[$lang.'_name'], 'phone'=>$row1['personal_phone'], 'image'=>$image);	
					
		}
	}





	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';


	// die();





?>

<link rel="stylesheet" href="../assets/css/jquery-clockpicker.min.css">
<link rel="stylesheet" href="../assets/css/fullcalendar.css?<?=time()?>">

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
  font-size: 15px;
  line-height:22px;
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
.SumoSelect{
	width: 99% !important;
	min-width: 200px !important;
	padding: 4px 0 0 10px !important;
	border:0 !important;
}
input.step2:read-only, 
input.step3:read-only ,
input.step4:read-only, 
input.step5:read-only  {
	color:#aaa;
	cursor:not-allowed;
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


.hideshowBTn
{
	display: none;
}
	

</style>


	<h2>
		<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time settings']?>
		<span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span>
	</h2>	
	
	<div class="main" style="overflow:hidden">
		<div id="dump"></div>
		
		<ul class="nav nav-tabs" id="myTab">
			<li class="nav-item" onclick="hideShowbtn('shiftplan');"><a class="nav-link" href="#tab_shiftplan" data-toggle="tab"><?=$lng['Working hours']?></a></li>
			<li class="nav-item" onclick="hideShowbtn('shiftteams');"><a class="nav-link" href="#tab_shiftteams" data-toggle="tab"><?=$lng['Shift schedule']?></a></li>
			<!-- <li class="nav-item"><a class="nav-link" href="#tab_shiftcalendar" data-toggle="tab"><?=$lng['Shiftplan calendar']?></a></li> -->
			<li class="nav-item" onclick="hideShowbtn('settings');"><a class="nav-link" href="#tab_settings" data-toggle="tab"><?=$lng['Other settings']?></a></li>
			<!-- <li class="nav-item"><a class="nav-link" href="#tab_compensations" data-toggle="tab">Compensations</a></li> -->
			<li class="nav-item" onclick="hideShowbtn('lcoations');"><a class="nav-link" href="#tab_locations" data-toggle="tab"><?=$lng['Scan locations'] ?></a></li>
			<li class="nav-item" id="externalLoc" onclick="hideShowbtn('external');"><a id="externallocationanchor" class="nav-link" href="#tab_external_locations" data-toggle="tab"><?=$lng['External Location'] ?></a></li>
			<li class="nav-item" onclick="hideShowbtn('comments');"><a class="nav-link" href="#tab_comments" data-toggle="tab"><?=$lng['Comments'] ?></a></li>
			<li class="nav-item" nclick="hideShowbtn('scansystems');"><a class="nav-link" href="#tab_scansystems" data-toggle="tab"><?=$lng['Scan Systems'] ?></a></li>
		</ul>
	<!-- show only when tab external location is selected -->
		<button  style="position:absolute; top:15px; right:16px" class="hideshowBTn btn btn-primary addLocBtn addlocations" id="externalLocBtn" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Add External Location']?></button>

		
		<div class="tab-content" style="height:calc(100% - 40px)">
			
			<div class="tab-pane" id="tab_shiftplan">
			
				<form id="shiftplanForm" style="height:100%">
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<div style="overflow-x:auto; height:100%">
				<table id="workTable" class="basicTable inputs" border="0">
					<thead>
					<tr>
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
						<!-- <th><span><i data-placement="left" data-toggle="tooltip" title="Delete file" class="fa fa-trash fa-lg"></i></span></th> -->
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
						<!-- 	<td style="text-align: center;vertical-align: center;" >
								<span><a data-id="<?php echo $key; ?>" class="deleteFiles"><i style="color: #005588;" class="fa fa-trash fa-lg"></i></a></span>
							</td> -->
						</tr>
						<? $nr++; if($nr >=12){$nr = 0;}} ?>
					</tbody>
				</table>
				<button style="margin:10px 0 20px 0" class="btn btn-primary btn-xs" type="button" id="addworkhours"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add row']?></button>
				</div>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_shiftteams" style="position:relative">
				<table class="basicTable" border="0" style="margin-bottom:10px;">
					<thead>
						<tr>
							<!-- <th><?=$lng['ID']?></th> -->
							<!-- <th><?=$lng['Code']?></th> -->
							<th><?=$lng['Team Code']?></th>
							<th><?=$lng['Team Name']?></th>
							<!-- <th><?=$lng['Name']?></th> -->
							<!-- <th><?=$lng['Start date']?></th> -->
							<!-- <th><?=$lng['Shift type']?></th> -->
							<th style="width:90%"><?=$lng['Shift Schedule Description']?></th>
							<th><a data-toggle="tooltip" title="<?=$lng['Edit']?>"><i class="fa fa-edit fa-lg"></i></a></th>
							<!-- <th><a data-toggle="tooltip" title="<?=$lng['Delete']?>"><i class="fa fa-trash fa-lg"></i></a></th> -->
						</tr>
					</thead>
					<tbody>
					<? if(!$rowFt){
							echo '<td colspan="7" style="padding:5px 10px; color:#b00;font-weight:600">'.$lng['No data available in database'].'</td>';
						}else{
						foreach($rowFt as $k=>$v){ ?>
						<tr>
							<td style="display: none;"><?=$v['id']?></td>
							<td style="text-transform: uppercase;"><?=$v['id']?></td>
							<td><?=$v['name']?></td>
							<td><?=$v['description']?></td>
							<td><a style="color: #0066CC;" data-id="<?=$k?>" class="editShiftplan"><i class="fa fa-edit fa-lg"></i></a></td>
						</tr>
					<? } } ?>
					</tbody>
				</table>
				<!-- <button class="btn btn-primary btn-xs" id="addnew" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?=$lng['Add new']?></button> -->
				<!--<button style="margin:0 !important" id="exportTeams" type="button" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Export Excel']?></button>-->

				<div style="display:none; margin-top:15px" id="addnewplan">
					<form id="teamForm">
					<table class="basicTable inputs" border="0" style="margin-bottom:10px">
						<thead>
						<tr>
							<th style="min-width:50px;"><?=$lng['ID']?> <i class="man"></i></th>
							<th style="min-width:100px;"><?=$lng['Code']?> <i class="man"></i></th>
							<th style="min-width:200px;"><?=$lng['Name']?> <i class="man"></i></th>
							<th><?=$lng['Shift type']?> <i class="man"></i></th>
							<th><?=$lng['Start date']?> <i class="man"></i></th>
							<th class="oodays" style="display:none"><?=$lng['ON days']?> <i class="man"></i></th>
							<th class="oodays" style="display:none"><?=$lng['OFF days']?> <i class="man"></i></th>
							<th><?=$lng['Apply holidays']?> <i class="man"></i></th>
							<th style="width:90%"><?=$lng['Description']?></th>
						</tr>
						</thead>
						<tbody>
							<td>
								<input maxlength="5" placeholder="ID" type="text" name="id" id="id" value="" />
							</td>
							<td>
								<input maxlength="15" placeholder="Code" type="text" name="code" id="code" value="" />
							</td>
							<td>
								<input placeholder="Name" type="text" name="name" id="name" value="" />
							</td>
							<td>
								<select name="shiftType" id="shiftType">
									<option selected value="0"><?=$lng['Select']?></option>
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
									<option selected value="0"><?=$lng['Select']?></option>
									<option value="1">1 <?=$lng['day']?></option>
									<? for($i=2;$i<=7;$i++){ ?>
											<option value="<?=$i?>"><?=$i?> <?=$lng['days']?></option>
									<? } ?>
								</select>
							</td>
							<td class="oodays" style="display:none">
								<select name="offdays" id="offdays">
									<option selected value="0"><?=$lng['Select']?></option>
									<option value="1">1 <?=$lng['day']?></option>
									<? for($i=2;$i<=7;$i++){ ?>
											<option value="<?=$i?>"><?=$i?> <?=$lng['days']?></option>
									<? } ?>
								</select>
							</td>
							<td>
								<select name="holidays" id="holidays" style="width:100%">
									<option selected value="0"><?=$lng['Select']?></option>
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
								<th><?=$lng['Running days']?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="padding:4px 10px !important"><i style="color:#ccc"><?=$lng['Select ON days']?></i></td>
							</tr>	
						</tbody>
					</table>
			
					<table id="shiftTable" class="basicTable inputs" border="0">
						<thead>
							<tr>
								<th style="min-width:30px"><?=$lng['Week']?></th>
								<th id="quantTH" style="min-width:30px; display:none"><?=$lng['Quantity']?></th>
								<? foreach($weekdays as $k=>$v){ ?>
									<th><?=$v?></th>
								<? } ?>
								<th style="width:90%"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="9" style="padding:4px 10px !important"><i style="color:#ccc"><?=$lng['Select shift type']?></i></td>
							</tr>
						</tbody>
					</table>
			
					<table border="0" style="width:100%; margin-top:10px">
						<tr>
							<td style="vertical-align:top; padding-right:10px" width="1">
								<button class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Save shiftteam']?> </button>
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
							<select id="cshiftType" class="button noflash">
								<option disabled selected value="0"><?=$lng['Select shiftplan']?></option>
								<? foreach($shiftplan_list as $k=>$v){ ?>
										<option value="<?=$k?>"><?=$k?></option>
								<? } ?>
							</select>
						</td>
						<td style="padding-left:10px">
							<select id="gotoMonth" class="button noflash">
								<option disabled selected value="0"><?=$lng['Select month']?></option>
								<? foreach($goto as $k=>$v){ ?>
										<option value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
						<td style="width:90%"></td>
						<td style="white-space:nowrap">
							<button style="margin:0 1px; display:inline-block" class="btn btn-primary" id="btn-prev"><i class="fa fa-chevron-left"></i></button>
							<button style="margin:0; display:inline-block" class="btn btn-primary" id="btn-today"><?=$lng['Today']?></button>
							<button style="margin:0; display:inline-block" class="btn btn-primary" id="btn-next"><i class="fa fa-chevron-right"></i></button>
						</td>
					</tr>
				</table>
				<div id="calendar"></div>
			</div>
			
			<div class="tab-pane" id="tab_settings">
				<form id="otherForm">
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

				<table class="basicTable inputs" border="0">
					<tbody>
						<tr>
							<th><?=$lng['Default Normal Hours']?></th>
							<td>
								<input class=" sel tar hourFormat" style="width:55px" type="text" name="default_normal_hrs" value="<?php if($data['default_normal_hrs']){echo $data['default_normal_hrs'];}else{ echo '08:00';}?>">
							</td>
						</tr>

						<tr>
							<th><?=$lng['Acceptable late']?></th>
							<td>
								<input class="numeric sel tar" style="width:45px" type="text" name="accept_late" value="<?=$data['accept_late']?>"> <?=$lng['Minutes']?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Acceptable early']?></th>
							<td>
								<input class="numeric sel tar" style="width:45px" type="text" name="accept_early" value="<?=$data['accept_early']?>"> <?=$lng['Minutes']?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT start after']?></th>
							<td>
								<input class="numeric sel tar" style="width:45px" type="text" name="ot_start_after" value="<?=$data['ot_start_after']?>"> <?=$lng['Minutes']?>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT periods']?></th>
							<td>
								<input class="numeric sel tar" style="width:45px" type="text" name="ot_period" value="<?=$data['ot_period']?>"> <?=$lng['Minutes']?>
							</td>
						</tr>
						<!--<tr>
							<th><?=$lng['OT Round-up']?></th>
							<td>
								<input class="numeric sel tar" style="width:45px" type="text" name="ot_roundup" value="<?=$data['ot_roundup']?>"> <?=$lng['Minutes']?>
							</td>
						</tr>-->
						<tr>
							<th><?=$lng['OT on working day']?></th>
							<td>
								<Select name="otnd" style="width:100%">
									<option <? if($data['otnd']=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($data['otnd']=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($data['otnd']=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($data['otnd']=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($data['otnd']=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT on saterday']?></th>
							<td>
								<Select name="otsa[hrs]" style="width:auto">
									<option value="0"><?=$lng['Normal hours']?></option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($otsa['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">'.$lng['First'].' '.$i.' '.$lng['hours'].'</option>';
									} ?>
								</Select>
								<Select name="otsa[1]" style="width:auto">
									<option <? if($otsa[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($otsa[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($otsa[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($otsa[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($otsa[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
								<?=$lng['Then']?> : 
								<Select name="otsa[2]" style="width:auto">
									<option <? if($otsa[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($otsa[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($otsa[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($otsa[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($otsa[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT on sunday']?></th>
							<td>
								<Select name="otsu[hrs]" style="width:auto">
									<option value="0"><?=$lng['Normal hours']?></option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($otsu['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">'.$lng['First'].' '.$i.' '.$lng['hours'].'</option>';
									} ?>
								</Select>
								<Select name="otsu[1]" style="width:auto">
									<option <? if($otsu[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($otsu[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($otsu[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($otsu[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($otsu[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
								<?=$lng['Then']?> : 
								<Select name="otsu[2]" style="width:auto">
									<option <? if($otsu[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($otsu[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($otsu[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($otsu[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($otsu[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['OT on holidays']?></th>
							<td>
								<Select name="othd[hrs]" style="width:auto">
									<option value="0"><?=$lng['Normal hours']?></option>
									<? for($i=1; $i<=12;$i++){
											echo '<option ';
											if($othd['hrs']==$i){echo 'selected';}
											echo ' value="'.$i.'">'.$lng['First'].' '.$i.' '.$lng['hours'].'</option>';
									} ?>
								</Select>
								<Select name="othd[1]" style="width:auto">
									<option <? if($othd[1]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($othd[1]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($othd[1]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($othd[1]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($othd[1]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
								<?=$lng['Then']?> : 
								<Select name="othd[2]" style="width:auto">
									<option <? if($othd[2]=='0'){ echo 'selected';}?> value="0"><?=$lng['N/A']?></option>
									<option <? if($othd[2]=='1'){ echo 'selected';}?> value="1"><?=$lng['OT 1']?></option>
									<option <? if($othd[2]=='1.5'){ echo 'selected';}?> value="1.5"><?=$lng['OT 1.5']?></option>
									<option <? if($othd[2]=='2'){ echo 'selected';}?> value="2"><?=$lng['OT 2']?></option>
									<option <? if($othd[2]=='3'){ echo 'selected';}?> value="3"><?=$lng['OT 3']?></option>
								</Select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Time scan system']?></th>
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
						<tr>
							<th>Mobile GPS location<? //=$lng['Time scan system']?></th>
							<td>
								<Select name="gps" style="width:100%">
									<option <? if($data['gps']=='0'){echo 'selected';} ?> value="0">ON - Scan QR code only within perimeter</option>
									<option <? if($data['gps']=='1'){echo 'selected';} ?> value="1">OFF - No GPS location required</option>
								</Select>
							</td>
						</tr>

						<tr>
							<th><?=$lng['Default Scan Perimeter']?></th>
							<td>
								<input class=" tar" style="width:55px" type="text" name="default_scan_perimeter" value="<?php if($data['default_scan_perimeter']){echo $data['default_scan_perimeter'];}else{ echo '100';}?>">
							</td>
						</tr>
					</tbody>
				</table>
				</form>
			</div>
			
			<div class="tab-pane" id="tab_compensations">
				<form id="compensationForm">
					<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
					<table class="basicTable inputs" border="0" style="margin-bottom:10px">
						<thead>
							<tr>
								<th>Apply</th>
								<th>Code</th>
								<th style="width:30%; min-width:250px">Description</th>
								<th>Type</th>
								<th>Condition</th>
								<th>Compensation type</th>
								<th colspan="5" class="tac">Amount (Step 1-5)</th>
								<th>Occurance</th>
								<th>Failure</th>
								<th style="width:50%">Variable allowance</th>
							</tr>
						</thead>
						<tbody>
							<? // for($nr=1;$nr<=10;$nr++){ ?>
							<? foreach($compensations as $k=>$v){ ?>
							<tr>
								
								<td class="tac">
									<input name="compensations[<?=$k?>][apply]" type="hidden" value="0"  />
									<label><input <? if($v['apply'] == 1){echo 'checked';} ?> type="checkbox" name="compensations[<?=$k?>][apply]" value="1" class="checkbox notxt"><span style="margin:0"></span></label>									
								</td>
								
								<td><input <? if($v['apply'] == 1){echo 'readonly';} ?> maxlength="4" style="min-width:65px" name="compensations[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></td>
								
								<td><input name="compensations[<?=$k?>][description]" type="text" value="<?=$v['description']?>" /></td>
								
								<td>
									<select class="compType" name="compensations[<?=$k?>][type]" style="min-width:100%">
										<option <? if($v['type'] == 'permonth'){echo 'selected';} ?> value="permonth">Per month</option>
										<option <? if($v['type'] == 'pershift'){echo 'selected';} ?> value="pershift">Per shift</option>
									</select>
								</td>
								
								<td class="condition" style="padding:0px 10px !important">
									<input name="compensations[<?=$k?>][condition]" type="hidden" value="<?=$v['condition']?>" />
									<span><?=$condition[$v['condition']]?></span>
								</td>
								
								<td>
									<select class="compSelect" name="compensations[<?=$k?>][compensation_type]" style="min-width:100%">
										<option <? if($v['compensation_type'] == '1'){echo 'selected';} ?> value="1">1 Step / Fixed</option>
										<option class="comp_type" <? if($v['type'] == 'pershift'){echo 'disabled';} ?> <? if($v['compensation_type'] == '2'){echo 'selected';} ?> value="2">2 Steps</option>
										<option class="comp_type" <? if($v['type'] == 'pershift'){echo 'disabled';} ?> <? if($v['compensation_type'] == '3'){echo 'selected';} ?> value="3">3 Steps</option>
										<option class="comp_type" <? if($v['type'] == 'pershift'){echo 'disabled';} ?> <? if($v['compensation_type'] == '4'){echo 'selected';} ?> value="4">4 Steps</option>
										<option class="comp_type" <? if($v['type'] == 'pershift'){echo 'disabled';} ?> <? if($v['compensation_type'] == '5'){echo 'selected';} ?> value="5">5 Steps</option>
									</select>
								</td>
								
								<td style="min-width:60px">
									<input class="sel numeric tac" name="compensations[<?=$k?>][step1]" type="text" value="<?=$v['step1']?>" />
								</td>
								
								<td style="min-width:55px">
									<input <? if($v['compensation_type'] < 2){echo 'readonly';} ?> class="<? if($v['compensation_type'] >= 2){echo 'sel';}?> numeric tac step2" name="compensations[<?=$k?>][step2]" type="text" value="<?=$v['step2']?>" />
								</td>
								
								<td style="min-width:55px">
									<input <? if($v['compensation_type'] < 3){echo 'readonly';} ?> class="<? if($v['compensation_type'] >= 3){echo 'sel';}?> numeric tac step3" name="compensations[<?=$k?>][step3]" type="text" value="<?=$v['step3']?>" />
								</td>
								
								<td style="min-width:55px">
									<input <? if($v['compensation_type'] < 4){echo 'readonly';} ?> class="<? if($v['compensation_type'] >= 4){echo 'sel';}?> numeric tac step4" name="compensations[<?=$k?>][step4]" type="text" value="<?=$v['step4']?>" />
								</td>
								
								<td style="min-width:55px">
									<input <? if($v['compensation_type'] < 5){echo 'readonly';} ?> class="<? if($v['compensation_type'] >= 5){echo 'sel';}?> numeric tac step5" name="compensations[<?=$k?>][step5]" type="text" value="<?=$v['step5']?>" />
								</td>
								
								<td class="tar">
									<select class="occurSelect" name="compensations[<?=$k?>][occurance]" style="padding:4px 20px !important">
										<option <? if($v['occurance'] == '1'){echo 'selected';} ?> value="1">1</option>
										<option <? if($v['type'] == 'pershift'){echo 'disabled';} ?> class="occur" <? if($v['occurance'] == '2'){echo 'selected';} ?> value="2">2</option>
										<option <? if($v['type'] == 'pershift'){echo 'disabled';} ?> class="occur" <? if($v['occurance'] == '3'){echo 'selected';} ?> value="3">3</option>
										<option <? if($v['type'] == 'pershift'){echo 'disabled';} ?> class="occur" <? if($v['occurance'] == '4'){echo 'selected';} ?> value="4">4</option>
										<option <? if($v['type'] == 'pershift'){echo 'disabled';} ?> class="occur" <? if($v['occurance'] == '5'){echo 'selected';} ?> value="5">5</option>
									</select>
								</td>
								
								<td>
									<select class="failure" name="compensations[<?=$k?>][failure]" style="min-width:100%">
										<option <? if($v['failure'] == 'suddendeath'){echo 'selected';} ?> value="suddendeath">Sudden death</option>
										<option class="decending" <? if($v['compensation_type'] == 1){echo 'disabled';} ?> <? if($v['failure'] == 'decending'){echo 'selected';} ?> value="decending">Decending</option>
									</select>
								</td>
								
								<td>
									<select name="compensations[<?=$k?>][allowance]" style="min-width:98%">
										<option value="">Select</option>
									<? foreach($var_allow as $key=>$val){
											echo '<option value="'.$key.'" ';
											if($key == $v['allowance']){echo 'selected ';}
											echo '>'.$val.'</option>';
									} ?>
									</select>
								</td>
							
							</tr>
							<? } ?>
						</tbody>
					</table>
				</form>
				<!--<button class="btn btn-primary btn-xs" id="addCompensation" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?=$lng['Add new']?></button>-->
			</div>


			

			<div class="tab-pane" id="tab_locations" style="position:relative">
				<table class="basicTable" border="0" style="margin-bottom:10px;">
					<thead>
						<tr>
							<th><?=$lng['Branch code']?></th>
							<th><?=$lng['Branch name']?></th>
							<th style="width:90%"><?=$lng['Location name']?></th>
							<th><a data-toggle="tooltip" title="<?=$lng['Edit']?>"><i class="fa fa-edit fa-lg"></i></a></th>
						</tr>
					</thead>
					<tbody>
					<? if(!$rowFt){
							echo '<td colspan="7" style="padding:5px 10px; color:#b00;font-weight:600">'.$lng['No data available in database'].'</td>';
						}else{
						foreach($rowBt as $k=>$v){ ?>
						<tr>
							<td style="display: none;"><?=$v['id']?></td>
							<td style="text-transform: uppercase;"><?=$v['code']?></td>
							<td><?=$v['en']?></td>
							<td><?=$v['en']?></td>
							<td><a style="color: #0066CC;" data-id="<?=$k?>" class="editlocations"><i class="fa fa-edit fa-lg"></i></a></td>
						</tr>
					<? } } ?>
					</tbody>
				</table>
			</div>


			<div class="tab-pane" id="tab_external_locations" style="position:relative">
				<table class="basicTable" border="0" style="margin-bottom:10px;">
					<thead>
						<tr>
							<th><?=$lng['Location code']?></th>
							<th><?=$lng['Location name']?></th>
							<th style="width:80%"><?=$lng['Address']?></th>
							<th style="width:90%"><?=$lng['Contact person']?></th>
							<th><a data-toggle="tooltip" title="<?=$lng['Edit']?>"><i class="fa fa-edit fa-lg"></i></a></th>
						</tr>
					</thead>
					<tbody>
							<?php foreach ($rowFLocc as $key => $value) { ?>
			
						<tr>


								<td style="display: none;"><?=$value['loc_id']?></td>
								<td><?php echo $value['code']; ?></td>
								<td><?php echo $value['loc_name']; ?></td>
								<td><?php echo $value['address']; ?></td>
								<td><?php echo $value['contact_name']; ?></td>
								<td><a style="color: #0066CC;"  class="editExtlocations"><i class="fa fa-edit fa-lg"></i></a></td>

							
						</tr>


							<?php } ?>

			
					</tbody>
				</table>
			</div>









			
			<div class="tab-pane" id="tab_locationa">
				<form id="locationForm">
				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
				<table border="0" width="100%" style="table-layout:fixed"><tr><td style="vertical-align:top; width:550px; padding:0">
				<? foreach($scan_locations as $key=>$val){ ?>
				<table width="100%" border="0" class="basicTable inputs nowrap" style="margin-bottom:10px">
					<tr>
					  <th>Location name</th>
						<td style="width:100%" >
							<input type="text" name="location[<?=$key?>][name]" value="<?=$locations[$key]['name']?>">
							<input id="code<?=$key?>" type="hidden" name="location[<?=$key?>][code]" value="<?=$locations[$key]['code']?>">
							<input id="qr<?=$key?>" type="hidden" name="location[<?=$key?>][qr]" value="<?=$locations[$key]['qr']?>">
						</td>
						<td rowspan="6" style="width:160px">
							<? if(empty($locations[$key]['qr'])){ ?>
								<img id="QRimage<?=$key?>" style="width:160px; padding:6px" src="../images/1499401426qr_icon.svg">
							<? }else{ ?>
								<img id="QRimage<?=$key?>" width="160px" src="<?=$locations[$key]['qr']?>">
							<? } ?>						
						</td>
					</tr>
					<tr>
					  <th>Latitude</th>
						<td><input type="text" name="location[<?=$key?>][latitude]" value="<?=$locations[$key]['latitude']?>"></td>
					</tr>
					<tr>
					  <th>Longitude</th>
						<td><input type="text" name="location[<?=$key?>][longitude]" value="<?=$locations[$key]['longitude']?>"></td>
					</tr>
					<tr>
					  <th>Scan perimeter</th>
						<td><input class="numeric sel" type="text" name="location[<?=$key?>][perimeter]" value="<?=$locations[$key]['perimeter']?>"></td>
					</tr>
					<tr>
						<td colspan="2">
							<button data-id="<?=$key?>" type="button" style="width:48%; text-align:center; margin:8px 0" class="newQRcode btn btn-primary btn-fl">Create new QR code<? //=$lng['Today']?></button>
							<button <? if(empty($locations[$key]['qr'])){echo 'disabled';}?> data-id="<?=$key?>" type="button" style="width:48%; text-align:center; margin:8px 8px 8px 0" class="printLocation btn btn-primary btn-fr"><i class="fa fa-print"></i> &nbsp;Print QR code<? //=$lng['Today']?></button>
						</td>
					</tr>
				</table>
				<? } ?>
				</td><td valign="top" style="padding-left:10px">
					
					<h6 style="background:#eee; padding:6px 10px; margin:0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$compinfo[$lang.'_compname']?></span></h6>
					<div style="height:818px;" id="map-canvas"></div>
				
				</td></tr></table>
				
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


			<div class="tab-pane" id="tab_scansystems" style="position:relative">
				<table class="basicTable" border="0" style="margin-bottom:10px;">
					<thead>
						<tr>
							<th><?=$lng['Apply']?></th>
							<th><?=$lng['Code']?></th>
							<th><?=$lng['System Name']?></th>
							<th><?=$lng['Description']?></th>
							<th><?=$lng['Remarks']?></th>
							<th><?=$lng['Equipment File Type']?></th>
							<th><?=$lng['Example']?></th>
						</tr>
					</thead>
					<tbody>						
						<tr>
							<td><input class="scanCheck" id="scanCheck" type="checkbox"  name="scanCheck" value="REGOXLS" <?php if(in_array('REGOXLS', $checkboxArray)){echo 'checked';}?>></td>
							<td ><span class="fileValue">REGOXLS</span></td>
							<td>REGO Time Import (Excel file)</td>
							<td>REGO Time Import (Excel file)</td>
							<td>Use Of Excel File</td>
							<td><img src="<?php echo $exportexcelimg ;?>" style="height: 50px;margin:auto;"/></td>
							<td><a target="_blank" href="<?php echo $exportexcelFilePath ;?>"><i class="fa fa-file" style="font-size: 22px;color: #4a4848;margin-right: 4px;"></i>Scan Data.xlsx</a></td>
						</tr>
						<tr>
							<td><input id="scanCheck"type="checkbox"  class="scanCheck" name="scanCheck" value="FSV2011" <?php if(in_array('FSV2011', $checkboxArray)){echo 'checked';}?> ></td>
							<td><span class="fileValue">FSV2011</td>
							<td>Fingerscan V2011 ZKTECO</td>
							<td>Finger Print Attendance System 4.8.7</td>
							<td>Use Of Thumb Drive</td>
							<td><img src="<?php echo $fsv2011ImagePath ;?>" style="height: 50px;margin:auto;"/></td>
							<td><a target="_blank" href="<?php echo $fsv2011FilePath ;?>"><i class="fa fa-file" style="font-size: 22px;color: #4a4848;margin-right: 4px;"></i>Finger Print Scan.txt</a></td>
						</tr>						
<!-- 						<tr>
							<td><input class="scanCheck" id="scanCheck" type="checkbox"  name="scanCheck" value="FSV2012" <?php if(in_array('FSV2012', $checkboxArray)){echo 'checked';}?>></td>
							<td ><span class="fileValue">FSV2012</span></td>
							<td>Fingerscan V2011 ZKTECO</td>
							<td>Finger Print Attendance System 4.8.7</td>
							<td>Use Of Thumb Drive</td>
							<td><img src="<?php echo $fsv2011ImagePath ;?>" style="height: 50px;margin:auto;"/></td>
							<td><a target="_blank" href="<?php echo $fsv2011FilePath ;?>"><i class="fa fa-file" style="font-size: 22px;color: #4a4848;margin-right: 4px;"></i>Finger Print Scan.txt</a></td>
						</tr>	 -->

					</tbody>
				</table>
			</div>

		
		
		</div>
	
	</div>

	

<script>

	
	var heights = window.innerHeight-280;
	
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
		
		var condition = <?=json_encode($condition)?>;
		
		$(document).on('change', '.compType', function () {
			if(this.value == 'permonth'){
				$(this).closest('tr').find('.condition span').html(condition['nolateearly']);
				$(this).closest('tr').find('.condition input').val('nolateearly');
				$(this).closest('tr').find('.occur').prop('disabled', false);
				$(this).closest('tr').find('.comp_type').prop('disabled', false);
				$(this).closest('tr').find('.failure').val('suddendeath');
				$(this).closest('tr').find('.decending').prop('disabled', false);
			}
			if(this.value == 'pershift'){
				$(this).closest('tr').find('.condition span').html(condition['presence']);
				$(this).closest('tr').find('.condition input').val('presence');
				$(this).closest('tr').find('.occur').prop('disabled', true);
				$(this).closest('tr').find('.occurSelect').val(1);
				$(this).closest('tr').find('.comp_type').prop('disabled', true);
				$(this).closest('tr').find('.compSelect').val(1);
				$(this).closest('tr').find('.failure').val('suddendeath');
				$(this).closest('tr').find('.decending').prop('disabled', true);
			}
			
		});
		
		$(document).on('change', '.compSelect', function () {
			if(this.value == 1){
				$(this).closest('tr').find('.step2').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step3').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step4').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step5').prop('readonly', true).val(0).removeClass('sel');
			}
			if(this.value == 2){
				$(this).closest('tr').find('.step2').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step3').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step4').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step5').prop('readonly', true).val(0).removeClass('sel');
			}
			if(this.value == 3){
				$(this).closest('tr').find('.step2').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step3').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step4').prop('readonly', true).val(0).removeClass('sel');
				$(this).closest('tr').find('.step5').prop('readonly', true).val(0).removeClass('sel');
			}
			if(this.value == 4){
				$(this).closest('tr').find('.step2').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step3').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step4').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step5').prop('readonly', true).val(0).removeClass('sel');
			}
			if(this.value == 5){
				$(this).closest('tr').find('.step2').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step3').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step4').prop('readonly', false).val(0).addClass('sel');
				$(this).closest('tr').find('.step5').prop('readonly', false).val(0).addClass('sel');
			}
			
		});
		
		var cnr = 99;
		$(document).on('click', '#addCompensation', function () {
			var row = '<tr>'+
								'<td class="tac">'+
									'<input name="compensations['+cnr+'][apply]" type="hidden" value="0"  />'+
									'<label><input type="checkbox" name="compensations['+cnr+'][apply]" value="1" class="checkbox notxt"><span style="margin:0"></span></label>'+									
								'</td>'+
								'<td><input xreadonly maxlength="4" style="min-width:65px" name="compensations['+cnr+'][code]" type="text" value="" /></td>'+
								'<td><input name="compensations['+cnr+'][description]" type="text" value="" /></td>'+
								'<td>'+
									'<select class="compType" name="compensations['+cnr+'][type]" style="min-width:100%">'+
										'<option value="permonth">Per month</option>'+
										'<option value="pershift">Per shift</option>'+
									'</select>'+
								'</td>'+
								'<td class="condition" style="padding:0px 10px !important">'+
									'<input name="compensations['+cnr+'][condition]" type="hidden" value="" />'+
									'<span></span>'+
								'</td>'+
								'<td>'+
									'<select class="compSelect" name="compensations['+cnr+'][compensation_type]" style="min-width:100%">'+
										'<option value="1">1 Step / Fixed</option>'+
										'<option class="comp_type" value="2">2 Steps</option>'+
										'<option class="comp_type" value="3">3 Steps</option>'+
										'<option class="comp_type" value="4">4 Steps</option>'+
										'<option class="comp_type" value="5">5 Steps</option>'+
									'</select>'+
								'</td>'+
								'<td style="min-width:60px">'+
									'<input class="sel numeric tac" name="compensations['+cnr+'][step1]" type="text" value="" />'+
								'</td>'+
								'<td style="min-width:55px">'+
									'<input class="sel numeric tac step2" name="compensations['+cnr+'][step2]" type="text" value="0" />'+
								'</td>'+
								'<td style="min-width:55px">'+
									'<input class="sel numeric tac step3" name="compensations['+cnr+'][step3]" type="text" value="0" />'+
								'</td>'+
								'<td style="min-width:55px">'+
									'<input class="sel numeric tac step4" name="compensations['+cnr+'][step4]" type="text" value="0" />'+
								'</td>'+
								'<td style="min-width:55px">'+
									'<input class="sel numeric tac step5" name="compensations['+cnr+'>][step5]" type="text" value="0" />'+
								'</td>'+
								'<td class="tar">'+
									'<select class="occurSelect" name="compensations['+cnr+'>][occurance]" style="padding:4px 20px !important">'+
										'<option value="1">1</option>'+
										'<option class="occur" value="2">2</option>'+
										'<option class="occur" value="3">3</option>'+
										'<option class="occur" value="4">4</option>'+
										'<option class="occur" value="5">5</option>'+
									'</select>'+
								'</td>'+
								'<td>'+
									'<select class="failure" name="compensations['+cnr+'][failure]" style="min-width:100%">'+
										'<option value="suddendeath">Sudden death</option>'+
										'<option class="decending" value="decending">Decending</option>'+
									'</select>'+
								'</td>';

		})
		
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
		
		function bindSumoselect(){
			var mySelect = $('.sel_compensations').SumoSelect({
				csvDispCount:1,
				outputAsCSV : true,
				showTitle : false,
				placeholder: 'Select compensations<? //=$lng['Show Hide Columns']?>',
				captionFormat: 'Select compensations<? //=$lng['Show Hide Columns']?>',
				captionFormatAllSelected: 'Select compensations<? //=$lng['Show Hide Columns']?>',
			});
		}
	
		$("#compensationForm").submit(function(e){ 
			e.preventDefault();
			$(".submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			//alert('data')
			$.ajax({
				url: "ajax/update_compensations.php",
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
					setTimeout(function(){
						//$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
						//$(".submitBtn").removeClass('flash');
						//$("#sAlert").fadeOut(200);
						location.reload();
					},1000);
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
		
	

		
		$('.delShiftplan').confirmation({
			container				: 'body',
			rootSelector		: '.delShiftplan',
			singleton				: true,
			animated				: 'fade',
			placement				: 'left',
			popout					: true,
			html						: true,
			title 					: '<?=$lng['Are you sure']?>',
			btnOkClass 			: 'btn btn-danger btn-sm',
			btnOkLabel 			: '<?=$lng['Delete']?>',
			btnOkIconContent: '',
			btnCancelClass 	: 'btn btn-success btn-sm',
			btnCancelLabel 	: '<?=$lng['Cancel']?>',
			onConfirm: function() { 
				//alert(id);
				$.ajax({
					url:"settings/ajax/delete_shiftplan.php",
					data:{id: $(this).data('id')},
					success: function(result){
						//$("#dump").html(result);
						location.reload();
					}
				});
			}
		});
			
			
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
			//visibleRange: {
			//start: '2019-01-01',
			//end: '2019-12-31'
			//},			
			header: {
				center: 'title',
				left: '',//'month,year',
				right: 'prev,today,next'
			},
			showNonCurrentDates: false,
			events: {
				url: "ajax/server_get_shiftplan.php",
				data: function(){
					return { cid: '<?=$cid?>', code: $('#cshiftType').val() };
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
		var cnr = <?=json_encode($nr)?>;
		var team_bg = <?=json_encode($team_bg)?>;
		$("#addworkhours").click(function(){
			var wnr = $("#workTable tbody#tbodyO tr").length + 1;

			$(".submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);

			var addrow = '<tr><td class="input" style="background:'+team_bg[cnr]+'"><input type="hidden" name="shiftplan['+wnr+'][bg]" value="'+team_bg[cnr]+'" /><input maxlength="3" class="tac" placeholder="Code" style="min-width:50px; font-weight:600; background:transparent" type="text" name="shiftplan['+wnr+'][code]" value=""/></td><td class="input"><input style="min-width:200px" type="text" name="shiftplan['+wnr+'][name]" value="" /></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f1" type="text" name="shiftplan['+wnr+'][f1]" value="00:00" /><input type="hidden" class="f1hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u1" type="text" name="shiftplan['+wnr+'][u1]" value="00:00" /><input type="hidden" class="u1hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic f2" type="text" name="shiftplan['+wnr+'][f2]" value="00:00" /><input type="hidden" class="f2hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic u2" type="text" name="shiftplan['+wnr+'][u2]" value="00:00" /><input type="hidden" class="u2hidden" value="00:00"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input   readonly class="timePic first" type="text" name="shiftplan['+wnr+'][first]" value="00:00" /><input type="hidden" class="firsthidden" value="00:00"><input type="hidden" class="firstThidden" value="00:00" name="shiftplan['+wnr+'][firstThidden]"></div></td><td class="input"><div class="clockpicker"><button type="button"><i class="fa fa-clock-o"></i></button><input readonly class="timePic second" type="text" name="shiftplan['+wnr+'][second]" value="00:00" /><input type="hidden" class="secondhidden" value="00:00"><input type="hidden" class="secondThidden" value="00:00" name="shiftplan['+wnr+'][secondThidden]"></div></td><td class="input"><input style="margin-top:2px; font-weight:600" readonly class="nofocus net_hours" name="shiftplan['+wnr+'][hours]" type="text" value="00:00" /></td><td class="input"><input style="margin-top:2px; font-weight:400" readonly class="nofocus break" name="shiftplan['+wnr+'][break]" type="text" value="00:00" /></td><td class="input"><input style="margin-top:2px; font-weight:400" readonly class="nofocus addbreak" name="shiftplan['+wnr+'][addbreak]" type="text" value="00:00" /><input type="hidden" class="hiddenaddbreak" value=""></td></tr>';
			
			$("#workTable tbody#tbodyO").append(addrow);
			wnr ++;
			cnr ++; if(cnr >=12){cnr = 0;}
			bindClockPicker();
			bindSumoselect();
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
			
		$(document).on('click', '.newQRcode', function () {
			var id  = $(this).data('id');
			$.ajax({
				url: "ajax/create_qrcode.php",
				data: {id: id},
				dataType: 'json',
				success: function(result){
					//$("#dump").html(result);
					$("#QRimage"+id).attr('src',result.qr);
					$("#qr"+id).val(result.qr);
					$("#code"+id).val(result.code);
					$(".submitBtn").addClass('flash');
					$("#sAlert").fadeIn(200);
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
			
		$(".printLocation").on('click', function(e){ 
			var id = $(this).data('id');
			//alert(id)
			window.open('print_scan_location.php?id='+id,'_blank');
		});
		
		$("#locationForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();

			$.ajax({
				url: "ajax/update_scan_locations.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
						setTimeout(function(){location.reload();},1000);
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							//closeConfirm: true
						})
					}
					//setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
					
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
		
		$("#shiftplanForm").submit(function(e){ 
			e.preventDefault();
			$(".submitBtn i").removeClass('fa-save');
			$(".submitBtn i").addClass('fa-repeat fa-spin');
			var data = $(this).serialize();
			//alert('data')
			$.ajax({
				url: "ajax/update_shiftplan.php",
				type: 'POST',
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})

						$(".submitBtn").removeClass('flash');
						$("#sAlert").fadeOut(200);

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

		$("#otherForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();
			//alert('data')
			$.ajax({
				url: "ajax/update_other_time_settings.php",
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
		
		$("#teamForm").submit(function(e){ 
			e.preventDefault();
			var err = true;
			var err2 = true;
			$("#message").hide();
			if($("#id").val() == ""){err = false;}
			if($("#code").val() == ""){err = false;}
			if($("#name").val() == ""){err = false;}
			if($("#startdate").val() == ""){err = false;}
			if($("#shiftType").val() == 0){err = false;}
			if($("#holidays").val() == 0){err = false;}
			if($("#shiftType").val() == 'rd'){
			if($("#ondays").val() == 0){err = false;}
				//if($("#offdays").val() == 0){err = false;}
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

			$(".submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
				var data = $(this).serialize();
				$.ajax({
					url: "ajax/update_shiftteam.php",
					type: 'POST',
					data: data,
					success: function(result){
						//$("#dump").html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})
							location.reload();
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
		
		$("#commentForm").submit(function(e){ 
			e.preventDefault();
			//$(".submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var data = $(this).serialize();
			$.ajax({
				url: "ajax/update_comments.php",
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
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0"><?=$lng['Sequence']?></option><option value="early"><?=$lng['Early week']?></option><option value="late"><?=$lng['Late week']?></option><option value="night"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 week</option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[1][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0"><?=$lng['Sequence']?></option><option value="early"><?=$lng['Early week']?></option><option value="late"><?=$lng['Late week']?></option><option value="night"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[2][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[3]"><option selected value="0"><?=$lng['Sequence']?></option><option value="early"><?=$lng['Early week']?></option><option value="late"><?=$lng['Late week']?></option><option value="night"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[3]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[3][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='2x8'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0"><?=$lng['Sequence']?></option><option value="early"><?=$lng['Early week']?></option><option value="late"><?=$lng['Late week']?></option><option value="night"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[1][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0"><?=$lng['Sequence']?></option><option value="early"><?=$lng['Early week']?></option><option value="late"><?=$lng['Late week']?></option><option value="night"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[2][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					
				}else if(type=='2x12'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td class="input"><select name="sequence[1]"><option selected value="0"><?=$lng['Sequence']?></option><option value="day"><?=$lng['Day week']?></option><option value="late"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[1][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td class="input"><select name="sequence[2]"><option selected value="0"><?=$lng['Sequence']?></option><option value="night"><?=$lng['Day week']?></option><option value="late"><?=$lng['Night week']?></option></select></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[2][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='fly'){
					$('#quantTH').css('display','table-cell');
					
					addRow = '<tr><td>1st <?=$lng['Week']?></td>';
					addRow += '<td class="input"><select name="quant[1]"><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[1][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td>2th <?=$lng['Week']?></td>';
					addRow += '<td class="input"><select name="quant[2]"><option value="0"><?=$lng['Select']?></option><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[2][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					addRow += '<tr><td>3th <?=$lng['Week']?></td>';
					addRow += '<td class="input"><select name="quant[3]"><option value="0"><?=$lng['Select']?></option><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[3][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					addRow += '<tr><td>4th <?=$lng['Week']?></td>';
					addRow += '<td class="input"><select name="quant[4]"><option value="0"><?=$lng['Select']?></option><option value="1">1 <?=$lng['week']?></option><? for($i=2;$i<=9;$i++){ ?><option value="<?=$i?>"><?=$i?> <?=$lng['weeks']?></option><? } ?></select></td>';
					addRow += '<? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs workHrs" name="week[4][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='12d'){
					addRow = '<tr><td style="padding:4px 10px !important"><?=$lng['Day week']?></td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[day][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='12n'){
					addRow = '<tr><td style="padding:4px 10px !important"><?=$lng['Night week']?></td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[night][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
				}else if(type=='nw'){
					//$('.date_month').datepicker('setDaysOfWeekDisabled', []);
					addRow = '<tr><td style="padding:4px 10px !important"><?=$lng['Normal workweek']?></td><? foreach($weekdays as $k=>$v){ ?><td class="input"><select class="hrs" name="week[day][<?=$k?>]" style="width:auto"><option value="0"><?=$lng['Non-working day']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td><? } ?><td></td></tr>';
					
					
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
							tbody += '<td class="input"><select class="rndays" name="day['+i+']" style="width:auto"><option value="0"><?=$lng['Select']?></option><? foreach($shiftplan as $k=>$v){ ?><option value="<?=$k?>"><?=$v['name']?></option><? } ?></select></td>';
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
		bindSumoselect();
		
		var activeTabTime = localStorage.getItem('activeTabTime');
		if(activeTabTime){
			$('.nav-link[href="' + activeTabTime + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_workhours"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabTime', $(e.target).attr('href'));
			//dtable.fnAdjustColumnSizing();
			$('#calendar').fullCalendar('render');
		});
		
		$('input, textarea').on('keyup, change', function (e) {
			$(".submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);
		});
		$('select:not(.noflash)').on('change', function (e) {
			$(".submitBtn").addClass('flash');
			$("#sAlert").fadeIn(200);
		});
		//setTimeout(function(){
		//},1000);	
		$(document).on("click", "#exportTeams", function(e) {
			window.location.href='export_shiftplans.php';
		})
		//$('#calendar').fullCalendar('render');
		//$('#calendar').fullCalendar('refetchEvents');
		
		var locations = <?=json_encode($scan_locations)?>;
		var locs = <?=json_encode(count($scan_locations))?>;
		//alert(locs) 
		
		function addInfoWindow(marker, message) {
			var infoWindow = new google.maps.InfoWindow({
					content: message
			});
			google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
			});
		}		
		function initialize() {
			var myLatlng = new google.maps.LatLng(locations[1]['latitude'], locations[1]['longitude']);
			var mapOptions = {
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				zoom: 19,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: myLatlng
			}
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var marker, i, myinfo;
			for (i=1; i <= locs; i++) { 
				var content = locations[i]['name'];
				if(locations[i]['latitude'] != ''){
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i]['latitude'], locations[i]['longitude']),
						map: map,
						title: locations[i]['name']
					});
					var infowindow = new google.maps.InfoWindow()
					google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
							infowindow.setContent(content);
							infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 
				}
			}
					
			$(window).resize(function() {
				 google.maps.event.trigger(map, "resize");
			});
			google.maps.event.addListener(map, "idle", function(){
				google.maps.event.trigger(map, 'resize'); 
			});			
		}
		initialize();
		//google.maps.event.addDomListener(window, 'load', initialize);
		//setTimeout(function(){
		//},1000);
			
});


		$(document).on('click', '.editShiftplan', function(e) {

			var id = $(this).closest('tr').find('td:eq(0)').text();

			window.location.href="index.php?mn=7000&id="+id;
		})		

		$(document).on('click', '.editlocations', function(e) {

			var id = $(this).closest('tr').find('td:eq(0)').text();

			window.location.href="index.php?mn=7001&id="+id;
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
			



</script>	
	
	
<script type="text/javascript">
	
$(function () {

  $(document).on("click","img",function () {

    $(this).cpLightimg();

  });  
});


jQuery(function($) {

    $('.scanCheck').on('change', function() {


    	if($('.scanCheck').is(":checked"))
    	{
			var checkboxArray = [];
    		$("input:checkbox[name=scanCheck]:checked").each(function(){
			    checkboxArray.push($(this).val());
			});

    	} 
		
		if(checkboxArray == null)
		{
			var scanSystemVal = '0';
		}
		else
		{
			var scanSystemVal = '';
		}
    	

    	// If value is checked then run ajax


   	$.ajax({
				url: "ajax/update_scan_systems.php",
				data: {checkboxArray:checkboxArray,scanSystemVal:scanSystemVal},
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
});



function hideShowbtn(value)
{
	if(value == 'external')
	{
		$('#externalLocBtn').removeClass('hideshowBTn');
	}
	else
	{
		$('#externalLocBtn').addClass('hideshowBTn');
	}
}


// on reload check which li has active class get its id , name 




$( document ).ready(function() {
    // check if li has active class

    if ($("#externallocationanchor").hasClass("active")) {
			$('#externalLocBtn').removeClass('hideshowBTn');
		}
		else
		{
			$('#externalLocBtn').addClass('hideshowBTn');
		}


});


$(document).on('click', '.editExtlocations', function(e) {

	var id = $(this).closest('tr').find('td:eq(0)').text();

	window.location.href="index.php?mn=7003&id="+id;
})

$(document).on('click', '.addlocations', function(e) {


	window.location.href="index.php?mn=7002";
})



</script>
	
	
	
	
	
	
	
	
	
	
	
