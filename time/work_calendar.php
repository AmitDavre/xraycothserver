<?
	if($_SESSION['xhr']['access']['time']['module'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}

	$day = array(1=>'day',2=>'days',3=>'days',4=>'days',5=>'days',6=>'days',7=>'days',8=>'days',9=>'days');
	$bab_request = array('before'=>'Before', 'after'=>'After', 'both'=>'Both');
	$min_request = array('half'=>'Half day', 'hrs'=>'Hours');
	
	$disabledWeekdays = '[0,6]';
	$workingdays = 5;
	
	$res = $dbc->query("SELECT * FROM ".$cid."_leave_time_settings");
	if(mysqli_error($dbc)){ echo 'Error : '.mysqli_error($dbc);}else{
		if($row = $res->fetch_assoc()){
			//$leave = unserialize($row['leave_types']); 
			//$leave = $leaves['leave'];
			//$request = $row['request'];
			//$leave_start = $row['leave_start'];
			//$leave_end = $row['leave_end'];
			//$pr_leave_start = $row['pr_leave_start'];
			//$pr_leave_end = $row['pr_leave_end'];
			$workingdays = $row['workingdays'];
			//$calc_attendance = $row['calc_attendance'];
			//$attendance_target = $row['attendance_target'];
		}
	}
	if($workingdays == 6){$disabledWeekdays = '[0]';}
	//var_dump($workingdays);
	
	$holidays = array();
	$res = $dbc->query("SELECT * FROM ".$cid."_holidays WHERE year = '".$_SESSION['xhr']['cur_year']."'");
	while($row = $res->fetch_assoc()){
		$holidays[] = $row;
	}
	//var_dump($holidays); //exit;
	
	function xdate_range($first, $last, $step = '+1 day', $output_format = 'd-m-Y' ) {
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		while( $current <= $last ) {
			$dates[$current]['tts'] = $current;
			$dates[$current]['date'] = date($output_format, $current);
			$dates[$current]['m'] = date('m', $current);
			$dates[$current]['d'] = date('w', $current);
			$current = strtotime($step, $current);
		}
		return $dates;
	}
	
	$days = xdate_range('01-01-'.$_SESSION['xhr']['cur_year'],'31-12-'.$_SESSION['xhr']['cur_year']);
	//var_dump($days);
	
	for($i=1;$i<=12;$i++){
		$nw[$i]=0;
		$wd[$i]=0;
		$hd[$i]=0;
	}
	foreach($holidays as $k=>$v){
		$hd[date('n', strtotime($v['cdate']))]++;
	}
	//var_dump($hd);
	foreach($days as $k=>$v){
		if($workingdays == 6){
			if($v['d']==0){
				$nw[(int)$v['m']]++;
			}else{
				$wd[(int)$v['m']]++;
			}
		}else{
			if($v['d']==6 || $v['d']==0){
				$nw[(int)$v['m']]++;
			}else{
				$wd[(int)$v['m']]++;
			}
		}
	}
	foreach($nw as $k=>$v){
		$nw[$k] += $hd[$k];
		$wd[$k] -= $hd[$k];
	}
	$monthly_hours = $wd;
	//var_dump($monthly_hours);
	
	
	$dbc->query("UPDATE ".$cid."_leave_time_settings SET monthly_hours = '".serialize($monthly_hours)."'"); 
	//var_dump($nw);
	//var_dump($wd);
?>
	<link rel="stylesheet" href="../yearcalendar/css/bootstrap-year-calendar2.css?<?=time()?>">
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
		  font-weight:600;
		  line-height:120%;
		}
		.popover.bottom {
		  margin-top: 0px;
		}
		.popover.top {
		  xmargin-top: -3px;
		}
		
	</style>

	<div class="widget autoheight" style="margin-bottom:15px">
		<h2><i class="fa fa-calendar"></i>&nbsp; Public holidays</h2>		
		<div class="widget_body">
			
			<div id="showCalendar" style="display:none">	
				
				<div id="calendar"></div>
				
				<table class="xbasicTable" border="0" style="width:750px; margin:10px 0 0 8px">
					<tr>
						<th>&nbsp;</th>
						<? foreach($short_months as $k=>$v){
							echo '<th class="tac">'.$v.'</td>';
						} ?>
						<th class="tac">Tot</th>
					</tr>
					<tr>
						<th>Working days</th>
						<? foreach($wd as $k=>$v){
							echo '<td class="tac">'.$v.'</td>';
						} ?>
						<td class="tac"><?=array_sum($wd)?></td>
					</tr>
					<tr>
						<th>Non-working days</th>
						<? foreach($nw as $k=>$v){
							echo '<td class="tac">'.$v.'</td>';
						} ?>
						<td class="tac"><?=array_sum($nw)?></td>
					</tr>
					<tr>
						<th>Total days</th>
						<? foreach($wd as $k=>$v){
							echo '<td class="tac">'.($v+$nw[$k]).'</td>';
						} ?>
						<td class="tac"><?=(array_sum($wd)+array_sum($nw))?></td>
					</tr>
				</table>
				<div style="height:10px"></div>
				
			</div>	
		
		</div>
	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	<script src="../yearcalendar/js/bootstrap-year-calendar.js?<?=time()?>"></script>
	<? if($lang == 'th'){ ?>
	<script src="../yearcalendar/js/languages/bootstrap-year-calendar.th.js?<?=time()?>"></script>
	<? } ?>

	<script>
		
	$(document).ready(function() {
		
		$('#calendar').calendar({ 
			enableContextMenu: true,
			enableRangeSelection: false,
			//disabledWeekDays: <?=$disabledWeekdays?>,
			displayWeekNumber: true,
			displayHeader: false,
			language: '<?=$lang?>',
			startYear: <?=$_SESSION['xhr']['cur_year']?>,
			weekStart: 1,
			style: 'custom',
			customDayRenderer: function(element, date) {
				if (date.getDay() === 6 || date.getDay() === 0) {
					$(element).css('background-color', '#eee');
					$(element).css('border-radius', '15px');
				} 
			},
			customDataSourceRenderer: function(element, date, event) {
				// This will override the background-color to the event's color
				$(element).css('background-color', event[0].color);
				$(element).css('border-radius', '15px');
			},
			yearChanged: function(e) {
				e.preventRendering = true;
				//$(e.target).append('<div style="text-align:center"><img src="../images/loading.gif" /></div>');
				$.ajax({ 
					url: "../settings/ajax/json_workcalendar_events.php", 
					dataType: "json",
					success: function(response) {
						//alert(response.color);
						 var myData = [];
						 for (var i = 0; i < response.length; i++) {
							  myData.push({
								 id: response[i].id,
								 name: response[i].title,
								 type: response[i].type,
								 startDate: new Date(response[i].start),
								 endDate: new Date(response[i].start),
								 color: response[i].color
							  });
						 }
				
						 $(e.target).data('calendar').setDataSource(myData);
					} 
				});
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
		$('#showCalendar').fadeIn(200); 
	
	});
		
	
	</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
