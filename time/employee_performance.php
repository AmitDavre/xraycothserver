<?
	
	if(!isset($_SESSION['rego']['dh'])){
		$_SESSION['rego']['dh'] = 'hrs';
	}
	if(isset($_GET['dh'])){
		$_SESSION['rego']['dh'] = $_GET['dh'];
	}
	if($_SESSION['rego']['dh'] == 'hrs'){
		$dayhrs = 'Hours';
	}else{
		$dayhrs = 'Days';
	}
	if(isset($_GET['id'])){
		$_SESSION['rego']['emp'] = $_GET['id'];
	}
	if(!isset($_SESSION['rego']['emp'])){
		$_SESSION['rego']['emp'] = '';
	}
	
	$emps = getEmployees($cid);
	$emp_array = getJsonIdsEmployees();
	
	$selEmployee = '';
	if(!empty($_SESSION['rego']['emp'])){
		$selEmployee = $_SESSION['rego']['emp'].' - '.$emps[$_SESSION['rego']['emp']][$lang.'_name'];
	}
	//var_dump($_SESSION['rego']['emp']); //exit;
	
	for($i=1;$i<=12;$i++){
		$planned_days[$i] = 0;
		$planned_hrs[$i] = 0;
		$normal_hrs[$i] = 0;
		$overtimes[$i] = 0;
		$paid_early_late[$i] = 0;
		$unpaid_early_late[$i] = 0;
		$paid_leave[$i] = 0;
		$unpaid_leave[$i] = 0;
		$holidays[$i] = 0;
		$leave_paid[$i] = 0;
		$leave_unpaid[$i] = 0;
		$late_early[$i] = 0;
		$sick[$i] = 0;
		$sicknc[$i] = 0;
		$attendance[$i] = 0;
		$ot_per[$i] = 0;
		$leave_per[$i] = 0;
		$late_per[$i] = 0;
		$sick_per[$i] = 0;
		$absence[$i] = 0;
	}
	
	$sql = "SELECT month, 
		SUM(planned_hrs) as planned_hrs, 
		SUM(planned_days) as planned_days,  
		SUM(normal_hrs) as normal_hrs, 
		SUM(ot1) as ot1, 
		SUM(ot15) as ot15, 
		SUM(ot2) as ot2, 
		SUM(ot3) as ot3, 
		SUM(paid_early) as paid_early, 
		SUM(paid_late) as paid_late, 
		SUM(unpaid_early) as unpaid_early, 
		SUM(unpaid_late) as unpaid_late, 
		SUM(hd) as holidays, 
		SUM(personal) as paid_leave, 
		SUM(unpaid_leave) as unpaid_leave 
		FROM ".$cid."_attendance WHERE emp_id = '".$_SESSION['rego']['emp']."' GROUP BY month";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if($_SESSION['rego']['dh'] == 'hrs'){
				$planned_hrs[$row['month']] = $row['planned_hrs'];
				$normal_hrs[$row['month']] = $row['normal_hrs'];
				$overtimes[$row['month']] = $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'];
				$paid_early_late[$row['month']] = $row['paid_early'] + $row['paid_late'];
				$unpaid_early_late[$row['month']] = $row['unpaid_early'] + $row['unpaid_late'];
				$holidays[$row['month']] = $row['holidays']*8;
				$paid_leave[$row['month']] = $row['paid_leave'];
				$unpaid_leave[$row['month']] = $row['unpaid_leave'];
			}else{
				$planned_hrs[$row['month']] = $row['planned_hrs']/8;
				$normal_hrs[$row['month']] = $row['normal_hrs']/8;
				$overtimes[$row['month']] = ($row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'])/8;
				$paid_early_late[$row['month']] = ($row['paid_early'] + $row['paid_late'])/8;
				$unpaid_early_late[$row['month']] = ($row['unpaid_early'] + $row['unpaid_late'])/8;
				$holidays[$row['month']] = $row['holidays'];
				$paid_leave[$row['month']] = $row['paid_leave']/8;
				$unpaid_leave[$row['month']] = $row['unpaid_leave']/8;
			}
			$planned_days[$row['month']] = $row['planned_hrs'];
			$attendance[$row['month']] = $row['normal_hrs']/$row['planned_hrs']*100;
			$ot = $row['ot1'] + $row['ot15'] + $row['ot2'] + $row['ot3'];
			$leave = $row['paid_leave'] + $row['unpaid_leave'];
			if($row['normal_hrs'] > 0){
				$ot_per[$row['month']] = $ot / $row['normal_hrs'] * 100;
				$leave_per[$row['month']] = $leave / $row['normal_hrs'] * 100;
				$absence[$row['month']] = ($row['unpaid_early'] + $row['unpaid_late'] + $row['paid_early'] + $row['paid_late'] + $row['unpaid_leave']) / $row['normal_hrs'] * 100;
			}
		}
	}
	
	$sql = "SELECT month, leave_paid, paid_late, unpaid_late, paid_early, unpaid_early, leave_type FROM ".$cid."_attendance WHERE emp_id = '".$_SESSION['rego']['emp']."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			if(!empty($row['leave_paid'])){
				$tmp = unserialize($row['leave_paid']);
				$leave_paid[$row['month']] += array_sum($tmp);
				$leave_unpaid[$row['month']] += count($tmp) - array_sum($tmp);
			}
			if($row['paid_late']>0 || $row['unpaid_late']>0 || $row['paid_early']>0 || $row['unpaid_early']>0){$late_early[$row['month']] += 1;}
			if($row['leave_type'] == 'SL'){$sick[$row['month']] += 1;}
			if($row['leave_type'] == 'SN'){$sicknc[$row['month']] += 1;}
			$late_per[$row['month']] = $late_early[$row['month']] / $planned_days[$row['month']] * 100;
			$sick_per[$row['month']] = ($sick[$row['month']] + $sicknc[$row['month']]) / $planned_days[$row['month']] * 100;
		}
			//var_dump($sick_per); exit;
	}
	
	/*$start = date('Y-m-d', strtotime($time_period['start']));
	$end = date('Y-m-d', strtotime($time_period['end']));
	$leave = array();
	$sql = "SELECT * FROM ".$cid."_leaves_data WHERE date >= '".$start."' AND date <= '".$end."'";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$leave[] = $row;
		}
	}*/
	
	//var_dump($time_settings); exit;
	//var_dump($leave_unpaid); exit;
	//var_dump($absence); exit;
	//$data = array();
	$target = $time_settings['attendance_target'];
	
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

	.red {
		color:#c00;
	}
	.blue {
		color: #03c;
	}
	 
</style>
	
  <h2><i class="fa fa-table"></i>&nbsp;&nbsp;Employee performance</h2>
	
	<div class="main">
		<div id="dump"></div>
		
		<div class="searchFilter btn-fl" style="width:300px">
			<input style="width:100%" class="sFilter selEmployee" placeholder="<?=$lng['Employee filter']?> ... <?=$lng['Type for hints']?> ..." type="text" value="<?=$selEmployee?>" />
			<button id="clearSearchbox" type="button" class="clearFilter btn btn-default btn-sm"><i class="fa fa-times"></i></button>
		</div>
		<select onChange="location.href='index.php?mn=8&dh='+this.value" class="btn-fl" style="padding:5px 10px 4px !important">
			<option <? if($_SESSION['rego']['dh'] == 'hrs'){echo 'selected';}?> value="hrs"><?=$lng['Hours']?></option>
			<option <? if($_SESSION['rego']['dh'] == 'days'){echo 'selected';}?> value="days"><?=$lng['Days']?></option>
		</select>
	
				
				<table class="basicTable" width="100%" border="0">
					<thead>
						<tr>
							<th class="tac">Description</th>
							<th style="width:10px">UOM</th>
							<? foreach($short_months as $k=>$v){ ?>
							<th class="tac" style="min-width:75px"><?=$v?></th>
							<? } ?>
							<th class="tac" style="min-width:80px">Total</th>
							<th style="width:40%"></th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<th class="tar">Total planned working</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($planned_hrs as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Public holidays</thd>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($holidays as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo $v;}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format(($tot),2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Total normal planned</th>
							<td><?=$dayhrs?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Normal working</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($normal_hrs as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Overtime</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($overtimes as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Paid leave</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($paid_leave as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Unpaid Leave - Absence</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($unpaid_leave as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Paid Late/Early - Absence</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($paid_early_late as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Unpaid Late/Early - Absence</th>
							<td><?=$dayhrs?></td>
							<? $tot = 0; foreach($unpaid_early_late as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="16" style="height:15px"></td>
						</tr>
						<tr>
							<th class="tar">Unpaid leave</th>
							<td>Times</td>
							<? $tot = 0; foreach($leave_unpaid as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Late/Early</th>
							<td>Times</td>
							<? $tot = 0; foreach($late_early as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Sick</th>
							<td>Times</td>
							<? $tot = 0; foreach($sick as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Sick without certificate</th>
							<td>Times</td>
							<? $tot = 0; foreach($sicknc as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{echo round($v,2);}?></td>
							<? } ?>
							<td class="tar" style="font-weight:600"><?=number_format($tot,2)?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="15" style="height:16px"></td>
						</tr>
						<tr>
							<th class="tar">Attendance</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($attendance as $v){ $tot += $v; ?>
							<td class="tar <? if($v > 0 && $v < $target){echo 'red';}else{echo 'blue';}?>"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar <? if($per > 0 && $per < $target){echo 'red';}else{echo 'blue';}?>" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Overtime</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($ot_per as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Absence</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($absence as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Leave</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($leave_per as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Sick</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($sick_per as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="tar">Late/Early</th>
							<td>Percent</td>
							<? $c=0; $tot = 0; foreach($late_per as $v){ $tot += $v; ?>
							<td class="tar"><? if($v == 0){echo '-';}else{$c++; echo round($v,2).' %';}?></td>
							<? } if($c>0){$per = number_format(($tot/$c),2);}else{$per = 0;} ?>
							<td class="tar" style="font-weight:600"><?=$per.' %'?></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
		
	</div>
	
	<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>	
	
	<script type="text/javascript">
	
	$(document).ready(function() {
		
		var employees = <?=json_encode($emp_array)?>;
		
		$('.selEmployee').devbridgeAutocomplete({
			 lookup: employees,
			 triggerSelectOnValidInput: false,
			 onSelect: function (suggestion) {
				//emp_id = suggestion.data;
				//$(".selEmployee").val(this.value);
				location.href='index.php?mn=8&id=' + suggestion.data;
				//$("#empFilter").val(suggestion.data);
				//$("#statFilter").val("status = 'RQ'");
				//$('input[name="emp_id"]').val(suggestion.data);
				//$('input[name="name"]').val(suggestion.name);
				//$('input[name="phone"]').val(suggestion.phone);
				//$("#emp_img").prop('src', '../'+suggestion.image);
				//$("#addLeave").prop('disabled', false);
				//refreshApp();
			 }
		});	
		$(document).on("click", "#clearSearchbox", function(e) {
			location.href='index.php?mn=8&id';
		})

		var activeTabTimeReport = localStorage.getItem('activeTabTimeReport');
		if(activeTabTimeReport){
			$('.nav-link[href="' + activeTabTimeReport + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_summary"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabTimeReport', $(e.target).attr('href'));
		});
		
	})
	
	</script>

































