<?
	$disabled = '';
	//if($_SESSION['admin']['access']['settings']['leave'] == 0){$disabled = 'disabled';}
	
	$day = array(0=>$lng['days'],1=>$lng['day'],2=>$lng['days'],3=>$lng['days'],4=>$lng['days'],5=>$lng['days'],6=>$lng['days'],7=>$lng['days'],8=>$lng['days'],9=>$lng['days']);
	
	$disabledWeekdays = '[0,6]';
	$leave = array();
	$data = array();
	$res = $dbc->query("SELECT * FROM ".$cid."_leave_time_settings");
	if(mysqli_error($dbc)){ echo 'Error : '.mysqli_error($dbc);}else{
		if($data = $res->fetch_assoc()){
			//$holidays = unserialize($data['holidays']);
			$leave = unserialize($data['leave_types']); 
			if($data['workingdays'] == 6){$disabledWeekdays = '[0]';}
			if($data['workingdays'] == 7){$disabledWeekdays = '';}
		}
	}
	//var_dump($leave); exit;
	//unset($leave['AU'], $leave['FL'], $leave['ML'], $leave['MS'], $leave['OL'], $leave['PL'], $leave['WL'], $leave['TL'], $leave['EL']);
	//var_dump($data); exit;

	// echo '<pre>';
	// print_r($leave);
	// echo '</pre>';
	// exit;
	
	/*$dleave = array();
	if($res = $dbx->query("SELECT leave_types FROM rego_default_leave_time_settings")){
		if($row = $res->fetch_assoc()){
			$dleave = unserialize($row['leave_types']);
		}
	}
	
	$upgrade = 0;
	foreach($dleave as $k=>$v){
		if(!isset($leave[$k]) && $v['activ'] == 1){
			$leave[$k] = $v;
			$leave[$k]['activ'] = 0;
			$upgrade = 1;
		}
	}
	if($upgrade){
		$dbc->query("UPDATE ".$cid."_leave_time_settings SET leave_types = '".$dbc->real_escape_string(serialize($leave))."'"); 
	}*/
	
	//var_dump($upgrade); //exit;
	//var_dump(count($dleave)); //exit;
	//$leave['EL'] = $leave['AB'];
	//$leave['EL']['code'] = 'EL';
	//var_dump($leave); exit;
	//unset($leave['AU'],$leave['FL'],$leave['ML'],$leave['MS'],$leave['OL'],$leave['PL'],$leave['WL'],$leave['TL'],$leave['EL']);

	$shiftplan = getDefaultShiftplan();


	$maxsSN= $leave['SL']['max']['s'];
	$maxmSN= $leave['SL']['max']['m'];
	$paysSN= $leave['SL']['pay']['s'];
	$paymSN	  = $leave['SL']['pay']['m'];


	$leave['SN']['max']['s']= $maxsSN;
	$leave['SN']['max']['m']= $maxmSN;
	$leave['SN']['pay']['s']= $paysSN;
	$leave['SN']['pay']['m']  =  $paymSN;

	$maxsAU= $leave['AL']['max']['s'];
	$maxmAU= $leave['AL']['max']['m'];
	$paysAU= $leave['AL']['pay']['s'];
	$paymAU= $leave['AL']['pay']['m'];


	$leave['AU']['max']['s']= $maxsAU;
	$leave['AU']['max']['m']= $maxmAU;
	$leave['AU']['pay']['s']= $paysAU;
	$leave['AU']['pay']['m']= $paymAU;



	$years_values = getYears();



?>


<style>
	select {
		width:100% !important;
	}
</style>

	<h2>
		<i class="fa fa-plane"></i>&nbsp; <?=$lng['Leave settings']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>
	
	<form id="leaveForm" style="height:calc(100% - 50px)">	
	<div class="main" style="overflow:hidden">
		<div id="dump"></div>
		
		<ul class="nav nav-tabs" id="myTab">
			<li class="nav-item"><a class="nav-link" href="#tab_leavetypes" data-toggle="tab"><?=$lng['Leave types']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_options" data-toggle="tab"><?=$lng['Options']?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab_leave_period_settings" data-toggle="tab"><?=$lng['Leave Period Settings']?></a></li>
		</ul>
		
		<div class="tab-content" style="height:calc(100% - 40px)">
			<button class="btn btn-primary" id="submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
			
			<div class="tab-pane" id="tab_leavetypes">
				<div style="overflow-x:auto">
				<table id="leaveTable" class="basicTable inputs" border="0">
					<thead>
					 <tr style="border-bottom:1px #fff solid">
							<th colspan="4"><?=$lng['Leave types']?></th>
							<th colspan="2" class="tac"><?=$lng['Staff group']?></th>
							<th colspan="2" class="tac"><?=$lng['Management group']?></th>
							<th colspan="2"></th>
							<th colspan="4" class="tac"><?=$lng['Employee requests']?></th>
							<th colspan="2"><?=$lng['Attendance']?></th>
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
							<th><?=$lng['RQ Days']?></th>
							<th><?=$lng['Certificate']?></th>
							<th class="tac"><?=$lng['B/A Fact']?></th>
							<th><?=$lng['Min. request']?></th>
							<th class="tac"><?=$lng['Included']?></th>
					 </tr>
					</thead>
					<tbody>
						<? foreach($leave as $key=>$val){ 

		
							?>
							<tr>
								<td class="tac">
									<input name="type[<?=$key?>][activ]" type="hidden" value="0"  />
									<label><input <? if($val['activ'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][activ]" value="1" class="checkbox notxt style-0 tactiv"><span></span></label>
								</td>
								<td><input readonly class="nofocus" style="font-weight:600" name="type[<?=$key?>][code]" type="text" value="<?=$val['code']?>" /></td>
								<td><input name="type[<?=$key?>][th]" type="text" value="<?=$val['th']?>" /></td>
								<td><input name="type[<?=$key?>][en]" type="text" value="<?=$val['en']?>" /></td>
								<td style="border-left:1px #eee solid">
									<?php if($key == 'AU' || $key == 'SN')
									{
										$disabledclass= 'disabled';
									}
									else
									{
										 $disabledclass = '';
									}
									 
									 ?>
									 <?php if($val['code'] == 'AL')
									 {
									 	$maxsALclass = 'maxALsClass'; 
									 	$maxmALclass = 'maxALmClass'; 
									 	$paysALclass = 'payALsClass'; 
									 	$paymALclass = 'payALmClass'; 
									 }
									 else if($val['code'] == 'AU')
									 {
									 	$maxsALclass = 'maxAUsClass'; 
									 	$maxmALclass = 'maxAUmClass'; 
									 	$paysALclass = 'payAUsClass'; 
									 	$paymALclass = 'payAUmClass'; 
									 }		 
									 else if($val['code'] == 'SL')
									 {
									 	$maxsSLclass = 'maxSLsClass'; 
									 	$maxmSLclass = 'maxSLmClass'; 
									 	$paysSLclass = 'paySLsClass'; 
									 	$paymSLclass = 'paySLmClass'; 
									 }									 
									 else if($val['code'] == 'SN')
									 {
									 	$maxsSLclass = 'maxSNsClass'; 
									 	$maxmSLclass = 'maxSNmClass'; 
									 	$paysSLclass = 'paySNsClass'; 
									 	$paymSLclass = 'paySNmClass'; 
									 }
									 else
									 {
									 	$maxsALclass = ''; 
									 	$maxmALclass = ''; 
									 	$paysALclass = ''; 
									 	$paymALclass = ''; 
									 	$maxsSLclass = ''; 
									 	$maxmSLclass = ''; 
									 	$paysSLclass = ''; 
									 	$paymSLclass = ''; 
									 }


									 ?>


					
									 	<input <?php echo $disabledclass; ?> class="numeric tac  <?php echo $maxsALclass; ?> <?php echo $maxsSLclass; ?> " name="type[<?=$key?>][max][s]" type="text" value="<?=$val['max']['s']?>" />

							

									
								</td>
								<td>

									 	<input <?php echo $disabledclass; ?> class="numeric tac <?php echo $paysALclass; ?> <?php echo $paysSLclass; ?>" name="type[<?=$key?>][pay][s]" type="text" value="<?=$val['pay']['s']?>" />

							
									
								</td>
								<td style="border-left:1px #eee solid">

						
									 	<input <?php echo $disabledclass; ?> class="numeric tac <?php echo $maxmALclass; ?> <?php echo $maxmSLclass; ?>" name="type[<?=$key?>][max][m]" type="text" value="<?=$val['max']['m']?>" />

								
									
								</td>
								<td>

					
									 	<input <?php echo $disabledclass; ?> class="numeric tac <?php echo $paymALclass; ?> <?php echo $paymSLclass; ?>" name="type[<?=$key?>][pay][m]" type="text" value="<?=$val['pay']['m']?>" />

								

									
								</td>
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
									<label><input <? if($val['emp_request'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][emp_request]" value="1" class="request_checkbox_<?php echo $key;?> checkbox notxt style-0 reqest_lea_checkbox" onchange="changeReqLeav('<?php echo $key ?>');"><span></span></label>
								</td>
								<td class="tac" style="padding-left:11px">
									<select class="time request_lea_sel" name="type[<?=$key?>][request_leave]" id="request_leave_<?php echo $key;?>">
									<? foreach($day as $k=>$v){ ?>
										<option <? if($val['request_leave'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$k?>  </option>
									<? } ?>
									</select>

								</td>
								<td class="tac" style="padding-left:11px">
									<input name="type[<?=$key?>][certificate]" type="hidden" value="0"  />
									<label><input <? if($val['certificate'] == 1){echo 'checked';} ?> type="checkbox" name="type[<?=$key?>][certificate]" value="1" class="checkbox notxt style-0"><span></span></label>
								</td>
								<td style="border-left:1px #eee solid; padding-left:10px;">
									<select id="bab_request_<?php echo $key; ?>" name="type[<?=$key?>][bab_request]" style="width:auto !important" onchange="disableReqdays('<?php echo $key ?>');">
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
				</div>
			</div>
			
			<div class="tab-pane" id="tab_options">
				<div style="overflow-x:auto">
					<table class="basicTable inputs" border="0" style="min-width:500px">
					<tbody>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Request leave']?>
						</th>
						<td>
							<select class="time" name="request">
								<? foreach($day as $k=>$v){ ?>
									<option <? if($data['request'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$k?> <?=$v?> <?=$lng['before start date']?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Days per week']?>
						</th>
						<td>
							<select name="workingdays" id="workingdays">
								<option <? if($data['workingdays']==5){echo 'selected';}?> value="5"><?=$lng['5 days - no Weekends']?></option>
								<option <? if($data['workingdays']==6){echo 'selected';}?> value="6"><?=$lng['6 days - no Sundays']?></option>
								<option <? if($data['workingdays']==7){echo 'selected';}?> value="7">7 <?=$lng['days']?></option>
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

					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Default time settings']?>
						</th>
						<td>

							<table class="basicTable inputs" border="0">
								<thead>
								<tr>
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
									<th><?=$lng['Total hours Worked']?></th>
									
								</tr>
								</thead>
								<tbody id="tbodyO">
									<? $nr = 0; foreach($shiftplan as $key=>$val){ ?>
									<tr id="<?php echo $key;?>">
										
										<td class="input">
											<input  <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="min-width:200px" type="text" name="shiftplan[<?=$key?>][name]" value="<?=$val['name']?>" />
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic f1" type="text" name="shiftplan[<?=$key?>][f1]" value="<?=$val['f1']?>" />
												<!-- <input type="hidden" class="f1hidden" value="<?=$val['f1']?>"> -->
											</div>
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic u1" type="text" name="shiftplan[<?=$key?>][u1]" value="<?=$val['u1']?>" />
												<!-- <input type="hidden" class="u1hidden" value="<?=$val['u1']?>"> -->

											</div>
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic f2" type="text" name="shiftplan[<?=$key?>][f2]" value="<?=$val['f2']?>" />
												<!-- <input type="hidden" class="f2hidden" value="<?=$val['f2']?>"> -->

											</div>
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic u2" type="text" name="shiftplan[<?=$key?>][u2]" value="<?=$val['u2']?>" />
												<!-- <input type="hidden" class="u2hidden" value="<?=$val['u2']?>"> -->

											</div>
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input  <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic first" type="text" name="shiftplan[<?=$key?>][first]" value="<?=$val['first']?>" />
												<!-- <input type="hidden" class="firsthidden" value="<?=$val['first']?>">
												<input type="hidden" class="firstThidden" value="<?=$val['firstThidden']?>" name="shiftplan[<?=$key?>][firstThidden]"> -->

											</div>
										</td>
										<td class="input">
											<div class="clockpicker">
												<!-- <button type="button"><i class="fa fa-clock-o"></i></button> -->
												<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> readonly class="timePic second" type="text" name="shiftplan[<?=$key?>][second]" value="<?=$val['second']?>" />
												<!-- <input type="hidden" class="secondhidden" value="<?=$val['second']?>">
												<input type="hidden" class="secondThidden" value="<?=$val['secondThidden']?>" name="shiftplan[<?=$key?>][secondThidden]"> -->

											</div>
										</td>
										<td class="input">
											<input <?php if (in_array($val['code'], $wh_code)) { echo 'disabled="disabled"';}?> style="margin-top:2px; font-weight:600" readonly class="nofocus net_hours" name="shiftplan[<?=$key?>][hours]" type="text" value="<?=$val['hours']?>" />
										</td>
										
									
									</tr>
									<? $nr++; break; } ?>
								</tbody>
							</table>
							
						</td>
					</tr>
					</tbody>
				</table>
				</div>
			</div>			

			<div class="tab-pane" id="tab_leave_period_settings">
				<div style="overflow-x:auto">
					<table class="basicTable inputs" border="0" style="min-width:500px">
					<tbody>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Select Year']?>
						</th>
						<td>
							<select id="selectyear" class="time" name="selectyear" onchange="gettheSelectedYearData();">
								 <option  value="0"> Select Year</option>
								<? foreach($years_values as $k=>$v){ ?>
									 <option  value="<?=$k?>"> <?=$v?></option>
								<? } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Select Period Start']?>
						</th>
						<td>
							<input id="startperiod" class="startperiod sel addBOnC"  type="text" value=""  name="startperiod">
						</td>
					</tr>					
					<tr>
						<th valign="top" style="padding-top:7px">
							<?=$lng['Select Period End']?>
						</th>
						<td>
							<input id="endperiod" class="endperiod sel addBOnC"  type="text" value=""  name="endperiod">
						</td>
					</tr>




					</tbody>
				</table>
				</div>
			</div>
			
		</div>
		
	</div>
	</form>
	
<script>
	
	$(document).ready(function() {


		// loop through the table and get the td request 

		$('table#leaveTable tbody tr').each(function(i) {
		    // Cache checkbox selector
		    var chkbox = $(this).find('.reqest_lea_checkbox');
		    var selector = $(this).find('.request_lea_sel');

		    // Only check rows that contain a checkbox
		    if(chkbox.length) {
		    var status = chkbox.prop('checked');
		      if(status == false)
		      {
		      	// disable the request leave field 
		      	selector.prop('disabled', true);
		      }
		    }
		});



		$('.startperiod').datepicker({

			format: "dd/mm/yyyy",
			autoclose: true,
			inline: true,
			orientation: 'auto bottom',
			language: lang,
			todayHighlight: true,
			startView: 'year',

		}) 

		$('.endperiod').datepicker({

			format: "dd/mm/yyyy",
			autoclose: true,
			inline: true,
			orientation: 'auto bottom',
			language: lang,
			todayHighlight: true,
			startView: 'year',

		}) 






		





		
		$(document).on("change", "#allTypes", function(e){
			if($(this).is(':checked')){
				$('.tactiv').prop('checked', true);
			}else{
				$('.tactiv').prop('checked', false);
			}
		});

		$("#leaveForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				url: ROOT+"settings/ajax/update_leave_settings.php",
				type: 'POST',
				data: data,
				success: function(result){
					//$('#dump').html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
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
					setTimeout(function(){
						$(".submitBtn i").removeClass('fa-repeat fa-spin').addClass('fa-save');
						$("#sAlert").fadeOut(200);
						$("#submitBtn").removeClass('flash');
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
				$("#submitBtn").addClass('flash');
				$("#sAlert").fadeIn(200);
			});	
		},1000);
		
		var activeTabLeave = localStorage.getItem('activeTabLeave');
		if(activeTabLeave){
			$('.nav-link[href="' + activeTabLeave + '"]').tab('show');
		}else{
			$('.nav-link[href="#tab_leavetypes"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabLeave', $(e.target).attr('href'));
		});

	});


	function changeReqLeav(key){


		if($('.request_checkbox_'+key).is(':checked'))
		{
			$('#request_leave_'+key).prop('disabled', false);
		}
		else
		{
			$('#request_leave_'+key).prop('disabled', true);
			$('#request_leave_'+key).val(0);
		}
	}

	function disableReqdays(key)
	{

		var bafact = $('#bab_request_'+key).val();

		if(bafact == 'after')
		{
			$('#request_leave_'+key).prop('disabled', true);
			$('.request_checkbox_'+key).prop('checked', false); 
			$('.request_checkbox_'+key).prop('disabled', true); 
			$('#request_leave_'+key).val(0);

		}
		else
		{
			$('.request_checkbox_'+key).prop('disabled', false); 

		}
	}


$('.maxALsClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.maxAUsClass').val(dInput);
});

$('.payALsClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.payAUsClass').val(dInput);
});

$('.maxALmClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.maxAUmClass').val(dInput);
});
$('.payALmClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.payAUmClass').val(dInput);
});

// FOR SL AND SN  

$('.maxSLsClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.maxSNsClass').val(dInput);
});

$('.paySLsClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.paySNsClass').val(dInput);
});

$('.maxSLmClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.maxSNmClass').val(dInput);
});
$('.paySLmClass').keyup(function() {
    var dInput = this.value;
    console.log(dInput);
    $('.paySNmClass').val(dInput);
});

function gettheSelectedYearData()
{
	var year = $('#selectyear').val();
	if(year == '0')
	{ 
		$('#startperiod').val('');
		$('#endperiod').val('');
		return false;
	}
	else
	{
		$('#startperiod').val('');
		$('#endperiod').val('');
	}

	$.ajax({
		url: ROOT+"settings/ajax/get_selected_leave_year_data.php",
		type: 'POST',
		data:{year: year},

		success: function(result){
			if(result){
				var data = JSON.parse(result);

				// convert leave start date 
				var leaveStartDate = data.leave_period_start ;
				var leaveStartDateSplit = leaveStartDate.split('-');
				var newLeaveStartDate = leaveStartDateSplit[2]+'/'+leaveStartDateSplit[1]+'/'+leaveStartDateSplit[0];
				// convert leave end date 
				var leaveEndDate = data.leave_period_end ;
				var leaveEndDateSplit = leaveEndDate.split('-');
				var newLeaveEndDate = leaveEndDateSplit[2]+'/'+leaveEndDateSplit[1]+'/'+leaveEndDateSplit[0];

				$('#startperiod').val(newLeaveStartDate);
				$('#endperiod').val(newLeaveEndDate);

			}
		},
	});
}
</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
