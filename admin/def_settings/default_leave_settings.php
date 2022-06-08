<?
	$disabled = '';
	//if($_SESSION['admin']['access']['settings']['leave'] == 0){$disabled = 'disabled';}
	//$min_request = array('half'=>'Half day', 'hrs'=>'Hours');
	
	//$disabledWeekdays = '[0,6]';
	$data = array();
	$res = $dba->query("SELECT * FROM rego_default_leave_time_settings");
	if(mysqli_error($dba)){ echo 'Error : '.mysqli_error($dba);}else{
		if($data = $res->fetch_assoc()){
			//$holidays = unserialize($row['holidays']);
			$leave = unserialize($data['leave_types']); 
		}
	}
	
	if(!isset($leave)){$leave = array();}
	//unset($leave['dfh']);
	//var_dump($leave); exit;

	// echo '<pre>';
	// print_r($leave);
	// echo '</pre>';
	// die();
	
?>
 
<style>
	select {
		width:100% !important;
	}
</style>
	
	
	<h2><i class="fa fa-cogs"></i>&nbsp;&nbsp;<?=$lng['Default leave settings']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<form id="leaveForm" style="height:100%">
		<ul style="position:relative" class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" data-target="#tab_types" data-toggle="tab"><?=$lng['Leave types']?></a></li>
			<li class="nav-item"><a class="nav-link" data-target="#tab_options" data-toggle="tab"><?=$lng['Options']?></a></li>
		</ul>
		
		<div class="tab-content" style="height:calc(100% - 40px)">
			
			<button style="position:absolute; top:15px; right:16px;" class="btn btn-primary" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
		
			<div class="tab-pane" id="tab_types" style="height:100%; overflow-y:auto">
				<table id="leaveTable" class="basicTable inputs" border="0">
					<thead>
					 <tr style="border-bottom:1px #fff solid">
							<th colspan="4"><?=$lng['Leave types']?></th>
							<th colspan="2" class="tac"><?=$lng['Staff group']?></th>
							<th colspan="2" class="tac"><?=$lng['Management group']?></th>
							<th colspan="2"></th>
							<th colspan="4" class="tac"><?=$lng['Employee requests']?></th>
							<th><?=$lng['Attendance']?></th>
					 </tr>
					 <tr>
							<th><label><input id="allTypes" type="checkbox" class="checkbox notxt style-0"><span></span></label></th>
							<th><?=$lng['Code']?></th>
							<th style="width:20%"><?=$lng['Thai description']?></th>
							<th style="width:20%"><?=$lng['English description']?></th>
							<th><?=$lng['Max. days']?></th>
							<th><?=$lng['Max. pay']?></th>
							<th><?=$lng['Max. days']?></th>
							<th><?=$lng['Max. pay']?></th>
							<th><?=$lng['Planned']?></th>
							<th>&nbsp;&nbsp;&nbsp;<?=$lng['Paid']?>&nbsp;&nbsp;&nbsp;</th>
							<th><?=$lng['Request']?></th>
							<th><?=$lng['Certificate']?></th>
							<th class="tac"><?=$lng['B/A Fact']?></th>
							<th><?=$lng['Min. request']?></th>
							<th class="tac"><?=$lng['Included']?></th>
					 </tr>
					</thead>
					<tbody>
						<? foreach($leave as $key=>$val){ ?>
							<tr>
								<td class="tac">
									<input name="type[<?=$key?>][activ]" type="hidden" value="0"  />
									<label><input <? if($val['activ'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][activ]" value="1" class="checkbox notxt style-0 tactiv"><span></span></label>
								</td>
								<td><input readonly class="nofocus" style="font-weight:600" name="type[<?=$key?>][code]" type="text" value="<?=$val['code']?>" /></td>
								<td><input name="type[<?=$key?>][th]" type="text" value="<?=$val['th']?>" /></td>
								<td><input name="type[<?=$key?>][en]" type="text" value="<?=$val['en']?>" /></td>
								<td style="border-left:1px #eee solid"><input class="numeric tac" name="type[<?=$key?>][max][s]" type="text" value="<?=$val['max']['s']?>" /></td>
								<td><input class="numeric tac" name="type[<?=$key?>][pay][s]" type="text" value="<?=$val['pay']['s']?>" /></td>
								<td style="border-left:1px #eee solid"><input class="numeric tac" name="type[<?=$key?>][max][m]" type="text" value="<?=$val['max']['m']?>" /></td>
								<td><input class="numeric tac" name="type[<?=$key?>][pay][m]" type="text" value="<?=$val['pay']['m']?>" /></td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][planned]" type="hidden" value="0"  />
									<label><input <? if($val['planned'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][planned]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][paid]" type="hidden" value="0"  />
									<label><input <? if($val['paid'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][paid]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][emp_request]" type="hidden" value="0"  />
									<label><input <? if($val['emp_request'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][emp_request]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][certificate]" type="hidden" value="0"  />
									<label><input <? if($val['certificate'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][certificate]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
								<td style="border-left:1px #eee solid; padding-left:10px;">
									<select name="type[<?=$key?>][bab_request]" style="width:auto !important">
										<? foreach($bab_request as $k=>$v){ ?>
												<option <? if($val['bab_request'] == $k){echo 'selected';} ?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select> 
								</td>
								<td style="border-left:1px #eee solid; padding-left:10px;">
									<select name="type[<?=$key?>][min_request]" style="width:auto !important">
										<? foreach($min_request as $k=>$v){ ?>
												<option <? if($val['min_request'] == $k){echo 'selected';} ?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][attendance]" type="hidden" value="0"  />
									<label><input <? if($val['attendance'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][attendance]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
							</tr>
						<? } ?>
					</tbody>
				</table>
				<div style="height:8px"></div>
				<button class="btn btn-primary btn-xs" type="button" id="add_leave"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?=$lng['Add row']?></button>
				<div style="height:20px"></div>
			</div>
			
			<div class="tab-pane" id="tab_options">
				<table class="basicTable inputs" border="0">
					<tbody>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Request leave']?>
						</th>
						<td>
							<select class="time" name="request">
								<? foreach($day as $k=>$v){ ?>
									<option <? if($data['request']==$k){echo 'selected';}?> value="<?=$k?>"><?=$k?> <?=$v?> <?=$lng['before start date']?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<!--<tr>
						<th valign="top" style="padding-top:7px">
							Leave period start
						</th>
						<td>
						<select name="leave_start">
							<option <? if($data['leave_start']==0){echo 'selected';}?> value="0">First day current month</option>
							<option <? if($data['leave_start']==31){echo 'selected';}?> value="31">Last day previous month</option>
							<option <? if($data['leave_start']==1){echo 'selected';}?> value="1">Last day previous month - 1 day</option>
							<option <? if($data['leave_start']==2){echo 'selected';}?> value="2">Last day previous month - 2 days</option>
							<option <? if($data['leave_start']==3){echo 'selected';}?> value="3">Last day previous month - 3 days</option>
							<option <? if($data['leave_start']==4){echo 'selected';}?> value="4">Last day previous month - 4 days</option>
							<option <? if($data['leave_start']==5){echo 'selected';}?> value="5">Last day previous month - 5 days</option>
							<option <? if($data['leave_start']==6){echo 'selected';}?> value="6">Last day previous month - 6 days</option>
							<option <? if($data['leave_start']==7){echo 'selected';}?> value="7">Last day previous month - 7 days</option>
							<option <? if($data['leave_start']==8){echo 'selected';}?> value="8">Last day previous month - 8 days</option>
							<option <? if($data['leave_start']==9){echo 'selected';}?> value="9">Last day previous month - 9 days</option>
						</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							Leave period payroll start
						</th>
						<td>
						<select name="pr_leave_start">
							<option <? if($data['pr_leave_start']==0){echo 'selected';}?> value="0">First day current month</option>
							<option <? if($data['pr_leave_start']==31){echo 'selected';}?> value="31">Last day previous month</option>
							<option <? if($data['pr_leave_start']==1){echo 'selected';}?> value="1">Last day previous month - 1 day</option>
							<option <? if($data['pr_leave_start']==2){echo 'selected';}?> value="2">Last day previous month - 2 days</option>
							<option <? if($data['pr_leave_start']==3){echo 'selected';}?> value="3">Last day previous month - 3 days</option>
							<option <? if($data['pr_leave_start']==4){echo 'selected';}?> value="4">Last day previous month - 4 days</option>
							<option <? if($data['pr_leave_start']==5){echo 'selected';}?> value="5">Last day previous month - 5 days</option>
							<option <? if($data['pr_leave_start']==6){echo 'selected';}?> value="6">Last day previous month - 6 days</option>
							<option <? if($data['pr_leave_start']==7){echo 'selected';}?> value="7">Last day previous month - 7 days</option>
							<option <? if($data['pr_leave_start']==8){echo 'selected';}?> value="8">Last day previous month - 8 days</option>
							<option <? if($data['pr_leave_start']==9){echo 'selected';}?> value="9">Last day previous month - 9 days</option>
						</select>
						</td>
					</tr>-->
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Days per week']?>
						</th>
						<td>
							<select name="workingdays" id="workingdays">
								<option <? if($data['workingdays']==5){echo 'selected';}?> value="5"><?=$lng['5 days - no Weekends']?></option>
								<option <? if($data['workingdays']==6){echo 'selected';}?> value="6"><?=$lng['6 days - no Sundays']?></option>
								<option <? if($data['workingdays']==7){echo 'selected';}?> value="6">7 <?=$lng['days']?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Hours per day']?>
						</th>
						<td>
							<select name="dayhours" id="dayhours">
								<? for($i=6; $i<=12; $i++){ ?>
									<option <? if($data['dayhours']==$i){echo 'selected';}?> value="<?=$i?>"><?=$i?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Attendance calculation']?>
						</th>
						<td>
							<select name="calc_attendance">
								<? foreach($yesno as $k=>$v){ ?>
									<option <? if($data['calc_attendance']==$k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Attendance target']?> %
						</th>
						<td>
							<input type="text" class="numeric sel" name="attendance_target" id="attendance_target" value="<?=$data['attendance_target']?>">
						</td>
					</tr>
					</tbody>
				</table>
			</div>
			
		</div>
		</form>
		
	</div>
	
	
<script>

	$(document).ready(function() {
		
		$(document).on("change", "#allTypes", function(e){
			if($(this).is(':checked')){
				$('.tactiv').prop('checked', true);
			}else{
				$('.tactiv').prop('checked', false);
			}
		});

		var lvnr = '<?=count($leave)+1?>'
		$("#add_leave").on('click', function(){ 
			var addrow = '<tr>'+
				'<td class="tac">'+
				'<input name="type['+lvnr+'][activ]" type="hidden" value="0"  />'+
				'<label><input type="checkbox" name="type['+lvnr+'][activ]" value="1" class="checkbox style-0 tactiv"><span style="margin:0"></span></label>'+
				'</td>'+
				'<td><input maxlength="3" style="font-weight:600" name="type['+lvnr+'][code]" type="text" /></td>'+
				'<td><input name="type['+lvnr+'][th]" type="text" /></td>'+
				'<td><input name="type['+lvnr+'][en]" type="text" /></td>'+
				'<td style="border-left:1px #eee solid"><input class="numeric tac" name="type['+lvnr+'][max][s]" type="text" /></td>'+
				'<td><input class="numeric tac" name="type['+lvnr+'][pay][s]" type="text" /></td>'+
				'<td style="border-left:1px #eee solid"><input class="numeric tac" name="type['+lvnr+'][max][m]" type="text" /></td>'+
				'<td><input class="numeric tac" name="type['+lvnr+'][pay][m]" type="text" /></td>'+
				'<td class="tac" style="padding-left:11px">'+
					'<input name="type['+lvnr+'][planned]" type="hidden" value="0" />'+
					'<label><input type="checkbox" name="type['+lvnr+'][planned]" value="1" class="checkbox notxt style-0"><span></span></label>'+
				'</td>'+
				'<td class="tac" style="padding-left:11px">'+
					'<input name="type['+lvnr+'][paid]" type="hidden" value="0" />'+
					'<label><input type="checkbox" name="type['+lvnr+'][paid]" value="1" class="checkbox notxt style-0"><span></span></label>'+
				'</td>'+
				'<td class="tac" style="padding-left:11px">'+
					'<input name="type['+lvnr+'][emp_request]" type="hidden" value="0" />'+
					'<label><input type="checkbox" name="type['+lvnr+'][emp_request]" value="1" class="checkbox notxt style-0"><span></span></label>'+
				'</td>'+
				'<td class="tac" style="padding-left:11px">'+
					'<input name="type['+lvnr+'][certificate]" type="hidden" value="0" />'+
					'<label><input type="checkbox" name="type['+lvnr+'][certificate]" value="1" class="checkbox notxt style-0"><span></span></label>'+
				'</td>'+
				'<td style="border-left:1px #eee solid; padding-left:10px;">'+
					'<select name="type['+lvnr+'][bab_request]" id="bab_request" style="width:auto">'+
						'<? foreach($bab_request as $k=>$v){ ?><option value="<?=$k?>"><?=$v?></option><? } ?>'+
					'</select>'+ 
				'</td>'+
				'<td style="border-left:1px #eee solid; padding-left:10px;">'+
					'<select name="type['+lvnr+'][min_request]" style="width:auto !important">'+
						'<? foreach($min_request as $k=>$v){ ?><option <? if($val['min_request'] == $k){echo 'selected';} ?> value="<?=$k?>"><?=$v?></option><? } ?>'+
					'</select>'+
				'</td>'+
				'<td class="tac" style="padding-left:11px">'+
					'<input name="type['+lvnr+'][attendance]" type="hidden" value="0" />'+
					'<label><input type="checkbox" name="type['+lvnr+'][attendance]" value="1" class="checkbox notxt style-0"><span></span></label>'+
				'</td>'+
				'<td></td>'+
			'</tr>';
			
			$("#leaveTable tr:last").after(addrow);
			lvnr++;
		});

		$("#leaveForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				url: AROOT+"def_settings/ajax/update_default_leave_settings.php",
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
					setTimeout(function(){
						$(".submitbtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#sAlert").fadeOut(200);
						$("#submitbtn").removeClass('warning');
					},500);
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
		
		setTimeout(function(){
			$('body').on('change', 'input, textarea, select', function (e) {
				$("#submitbtn").addClass('warning').flicker({wait:1000, cssValue: 0.4});
				$("#sAlert").fadeIn(200);
			});	
		},1000);

		var activeTab = localStorage.getItem('activeTab07');
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTab07', $(e.target).data('target'));
			 //dtable.fnAdjustColumnSizing();
			 //$('#calendar').fullCalendar('render');
		});
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_types"]').tab('show');
		}

	});

</script>	













