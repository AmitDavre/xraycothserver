<?
	/*if($_SESSION['xhr']['access']['time']['module'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}*/
	
	$shiftteams = getShifTeams();
	//var_dump($time_settings); exit;
	$compensations = unserialize($time_settings['compensations']);
	//var_dump($compensations); exit;
	$date = date('Y-m-d');
	
	$otplan = array();
	//$sql = "SELECT * FROM ".$cid."_ot_plans WHERE date >= '".$date."' ORDER BY date DESC";
	$sql = "SELECT * FROM ".$cid."_ot_plans ORDER BY date DESC";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$otplan[$row['id']] = $row;
			$otplan[$row['id']]['date'] = date('d-m-Y', strtotime($row['date']));
		}
	}
	//var_dump($otplan); exit;

	// echo  '<pre>';
	// print_r($shiftteams);
	// echo  '</pre>';

	// die();
	
?>

<link rel="stylesheet" href="../assets/css/jquery-clockpicker.min.css">


<style>
	.SumoSelect{
		width: 99% !important;
		min-width: 200px !important;
		padding: 4px 0 0 10px !important;
		border:0 !important;
	}
	.selected_row {
		background:#ffc;
	}
	input, select {
		background:transparent !important;
	}
</style>
		
<script>var otplan = <?=json_encode(count($otplan))?>;</script>

	<h2><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?=$lng['OT Requests']?></h2>
	
	<div class="main" style="padding-top:10px">
		<div id="dump"></div>
		
		<div style="height:100%; width:62%; float:left; padding-right:10px">
			<form id="planForm" style="height:calc(100% - 40px)">
			<button id="planBtn" <? if(count($otplan) == 0){echo 'disabled';}?> class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>
			
			<div style="height:100%; width:100%; clear:both; overflow:auto">
			<table id="planTable" class="basicTable inputs" width="100%" border="0">
				<thead>
					<tr>
						<th><?=$lng['Date']?><i class="man"></i></th>
						<th><?=$lng['Shiftteam']?><i class="man"></i></th>
						<th class="tac" style="color:#999"><?=$lng['Plan']?></th>
						<th class="tac" style="color:#999"><?=$lng['From']?></th>
						<th class="tac" style="color:#999"><?=$lng['Until']?></th>
						<th><?=$lng['From']?><i class="man"></i></th>
						<th><?=$lng['Until']?><i class="man"></i></th>
						<th class="tac"><?=$lng['Break']?></th>
						<th class="tac"><?=$lng['Hours']?></th>
						<!--<th>OT Type</th>-->
						<th style="width:80%"><?=$lng['Compensations']?></th>
						<th class="tac" data-toggle="tooltip" title="<?=$lng['Select employees']?>"><i class="fa fa-users fa-lg"></i></th>
					</tr>
				</thead>
				<tbody id="otPlan" style="display:none">
					<? if($otplan){ foreach($otplan as $key=>$val){ ?>
					<tr>
						<td>
							<input readonly placeholder="Date" class="datepick_ot" name="otplan[<?=$key?>][date]" style="width:100px; cursor:pointer" type="text" value="<?=$val['date']?>" />
						</td>
						<td>
							<select name="otplan[<?=$key?>][shiftteam]" class="shiftteam" style="background:transparent">
							<? foreach($shiftteams as $k=>$v){ 
									echo '<option value="'.$k.'" ';
									if($val['shiftteam'] == $k){echo 'selected ';}
									echo '>'.$v.'</option>';
							} ?>
							</select>
						</td>
						<td>
							<input style="color:#999; min-width:55px" readonly class="plan tac" name="otplan[<?=$key?>][plan]" type="text" value="<?=$val['plan']?>" />
						</td>
						<td>
							<input style="color:#999; min-width:55px" readonly class="plan_f1 tac" name="otplan[<?=$key?>][plan_f1]" type="text" value="<?=$val['plan_f1']?>" />
						</td>
						<td>
							<input style="color:#999; min-width:55px" readonly class="plan_u2 tac" name="otplan[<?=$key?>][plan_u2]" type="text" value="<?=$val['plan_u2']?>" />
						</td>
						<td>
							<div class="clockpicker">
								<button type="button"><i class="fa fa-clock-o"></i></button>
								<input readonly placeholder="00:00" class="timePic from" type="text" name="otplan[<?=$key?>][ot_from]" value="<?=$val['ot_from']?>" />
							</div>
						</td>
						<td>
							<div class="clockpicker">
								<button type="button"><i class="fa fa-clock-o"></i></button>
								<input readonly placeholder="00:00" class="timePic until" type="text" name="otplan[<?=$key?>][ot_until]" value="<?=$val['ot_until']?>" />
							</div>
						</td>
						<td>
							<div class="clockpicker">
								<button type="button"><i class="fa fa-clock-o"></i></button>
								<input readonly placeholder="0:00" class="timePic break" type="text" name="otplan[<?=$key?>][ot_break]" value="<?=$val['ot_break']?>" />
							</div>
						</td>
						<td>
							<input readonly placeholder="0:00" class="net_hours tac" name="otplan[<?=$key?>][hours]" type="text" value="<?=$val['hours']?>" />
						</td>
						<!--<td>
							<select style="width:auto; min-width:100%" name="otplan[<?=$key?>][type]">
								<option <? if($val['type'] == 'ot1'){echo 'selected';}?> value="ot1">OT 1</option>
								<option <? if($val['type'] == 'ot15'){echo 'selected';}?> value="ot15">OT 1.5</option>
								<option <? if($val['type'] == 'ot2'){echo 'selected';}?> value="ot2">OT 2</option>
								<option <? if($val['type'] == 'ot3'){echo 'selected';}?> value="ot3">OT 3</option>
							</select>
						</td>-->
						<td>
							<select multiple="multiple" name="otplan[<?=$key?>][compensations]" class="sel_compensations" style="background:transparent">
							<? foreach($compensations as $k=>$v){ if($v['apply'] == 1){
									echo '<option value="'.$k.'" ';
									if($val['compensations']){
										if(preg_match("/{$k}/i", $val['compensations'])){echo 'selected ';}
									}
									echo '>'.$v['code'].' - '.$v['description'].'</option>';
							}} ?>
							</select>
						</td>
						<td class="tac">
							<a class="selEmployees" data-id="<?=$key?>" href="#"><i class="fa fa-users fa-lg"></i></a>
						</td>
					</tr>
					<? }}else{ ?>
					<tr>
						<td class="tac" colspan="11" style="padding:5px !important; background: #FFFF99; font-weight:600"><?=$lng['No data available']?></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
			<button style="margin:10px 0 20px 0" class="btn btn-primary btn-xs" type="button" id="addPlan"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add row']?></button>
			</div>
			</form>
			
		
		</div>
		
		<div style="height:100%; width:38%; float:right; padding-left:10px; border-left:1px solid #ddd;">
			<button disabled id="uploadPlan" class="btn btn-primary btn-fr" type="button"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload plan']?></button>
			<!--<button disabled id="sendRequest" class="btn btn-primary btn-fl" type="button"><i class="fa fa-paper-plane"></i>&nbsp; Send Request<? //=$lng['Upload']?></button>-->
			<div style="height:calc(100% - 40px); width:100%; clear:both; overflow:auto">
			<table id="requestTable" class="basicTable" width="100%" border="0">
				<thead>
					<tr>
						<th><?=$lng['ID']?></th>
						<th style="width:70%"><?=$lng['Employee']?></th>
						<th><?=$lng['Position']?></th>
						<th><?=$lng['Shiftteam']?></th>
						<th data-toggle="tooltip" title="<?=$lng['Invite All']?>"><a id="inviteAll" href="#"><i class="fa fa-envelope-open fa-lg"></i></a></th>
						<th data-toggle="tooltip" title="<?=$lng['Confirm All']?>"><a id="confirmAll" href="#"><i class="fa fa-thumbs-up fa-lg"></i></a></th>
						<th data-toggle="tooltip" title="<?=$lng['Assign All']?>"><a id="assignAll" href="#"><i class="fa fa-user-plus fa-lg"></i></a></th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
			</div>
			
		</div>		
		
	</div>
	
	<script src="../assets/js/jquery-clockpicker.min.js"></script>





	
	<script>
	
	function getTimeInterval(from, until, otbreak){
		if(from == '' || until == ''){return '';}
		var start = moment.utc(from, "HH:mm");
		var end = moment.utc(until, "HH:mm");
		var br = moment.utc(otbreak, "HH:mm");
		var minutes = (br.hour()*60) + br.minute();
		// calculate the duration
		var d = moment.duration(end.diff(start));
		// subtract the lunch break
		d.subtract(minutes, 'minutes');
		// format a string result
		var s = moment.utc(+d).format('H:mm');		
		return s;
	}
	function bindClockPicker() {
		$('.timePic').clockpicker({
			autoclose: true,
		});
		$('.from, .until, .break').on("change", function(){
			var from = $(this).closest('tr').find('.from').val();
			var until = $(this).closest('tr').find('.until').val();
			var otbreak = $(this).closest('tr').find('.break').val();
			var hrs = getTimeInterval(from, until, otbreak);
			$(this).closest('tr').find('.net_hours').val(hrs);
		});
	}
	function bindSumoselect() {
		$('.sel_shiftteams').SumoSelect({
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: '<?=$lng['Select Shiftteams']?>',
			captionFormat: '<?=$lng['Select Shiftteams']?>',
			captionFormatAllSelected: '<?=$lng['Select Shiftteams']?>',
		});
		$('.sel_compensations').SumoSelect({
			csvDispCount:1,
			outputAsCSV : true,
			showTitle : false,
			placeholder: '<?=$lng['Select compensations']?>',
			captionFormat: '<?=$lng['Select compensations']?>',
			captionFormatAllSelected: '<?=$lng['Select compensations']?>',
		});
	}
	function updateAllEmployees(ids, field, status){
		$.ajax({
			url: "ajax/update_all_ot_employees.php",
			data: {ids: ids, field: field, status: status},
			success: function(result){
				//$("#dump").html(result); return false;
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
						duration: 2,
					})
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?>',
						duration: 4,
					})
				}
			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 4,
				})
			}
		});
	}
	
	$(document).ready(function() {

		
		$(document).on("change", ".datepick_ot, .shiftteam", function(e){
			var date = $(this).closest('tr').find('.datepick_ot').val();
			var team = $(this).closest('tr').find('.shiftteam').val();
			var plan = $(this).closest('tr').find('.plan');
			var plan_f1 = $(this).closest('tr').find('.plan_f1');
			var plan_u2 = $(this).closest('tr').find('.plan_u2');
			if(date != '' && team != ''){
				$.ajax({
					url: "ajax/get_hours_from_shiftplan.php",
					data: {date: date, team: team},
					dataType: 'json',
					success: function(response){
						plan.val(response.plan);
						plan_f1.val(response.plan_f1);
						plan_u2.val(response.plan_u2);
						//$('#dump').html(response.plan); return false;
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			}
		});
		
		var plan_id;
		
		var inviteAll = 1;
		$(document).on("click", "#inviteAll", function(e){
			var ids = [];
			if(inviteAll == 0){
				$('.inviteBox').prop('checked', true);
				inviteAll = 1;
			}else{
				$('.inviteBox').prop('checked', false);
				inviteAll = 0;
			}
			$('.inviteBox').each(function(){
				ids.push($(this).data('id'));
			})
			updateAllEmployees(ids, 'ot_invited', inviteAll);
		});
		
		var confirmAll = 1;
		
		$(document).on("click", "#confirmAll", function(e){
			var ids = [];
			if(confirmAll == 0){
				$('.confirmBox').prop('checked', true);
				confirmAll = 1;
			}else{
				$('.confirmBox').prop('checked', false);
				confirmAll = 0;
			}
			$('.confirmBox').each(function(){
				ids.push($(this).data('id'));
			})
			updateAllEmployees(ids, 'ot_confirmed', confirmAll);
		});
		
		var assignAll = 1;
		$(document).on("click", "#assignAll", function(e){
			var ids = [];
			if(assignAll == 0){
				$('.assignBox').prop('checked', true);
				assignAll = 1;
			}else{
				$('.assignBox').prop('checked', false);
				assignAll = 0;
			}
			$('.assignBox').each(function(){
				ids.push($(this).data('id'));
			})
			updateAllEmployees(ids, 'ot_assigned', assignAll);
		});
		
		$(document).on("click", "#uploadPlan", function(e) {
			$.ajax({
				url: "ajax/upload_ot_plan.php",
				type: "POST", 
				data: {id: plan_id},
				success: function(response){
					//$('#dump').html(response); return false;
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['OT plan uploaded successfully']?>',
							duration: 2,
						})
					}else if(response == 'assign'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['There are no employees assigned to this OT plan']?>',
							duration: 4,
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
							duration: 4,
						})
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$("#planForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				url: "ajax/update_otplan.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
						})
						setTimeout(function(){location.reload();},1500);
					}else if(result == 'empty'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
							duration: 2,
						})
					}else if(result == 'from_until'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['OT from &ne; Plan until <b>&nbsp;OR&nbsp;</b> OT until &ne; Plan from']?>',
							duration: 4,
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
		
		$(document).on("click", "#requestTable .checkbox", function(e) {
			$.ajax({
				url: "ajax/update_ot_employees.php",
				data: {id: $(this).data('id'), field: $(this).data('field'), status: $(this).is(':checked')},
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
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
						duration: 4,
					})
				}
			});
		})
		
		$(document).on("click", ".selEmployees", function(e) {
			
			
			plan_id = $(this).data('id');
			$('#planTable tbody tr').removeClass('selected_row');
			$(this).closest('tr').addClass('selected_row');
			$.ajax({
				url: "ajax/get_ot_employees.php",
				type: "POST", 
				data: {id: plan_id},
				success: function(response){
					$('#requestTable tbody').html(response);
					$('#uploadPlan').prop('disabled', false);
					$('.inviteBox').each(function(){
						if(!$(this).is(':checked')){inviteAll = 0;}
					})
					$('.confirmBox').each(function(){
						if(!$(this).is(':checked')){confirmAll = 0;}
					})
					$('.assignBox').each(function(){
						if(!$(this).is(':checked')){assignAll = 0;}
					})
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})
		
		$(document).on('click', '#addPlan', function () {
			if(otplan == 0){$('#planTable tbody').html('');}
			var rnr = 1+$('#planTable tbody tr').length;
			var row = '<tr>'+
						'<td>'+
							'<input id= "otplan_date" readonly placeholder="<?=$lng['Date']?>" class="datepick_ot" name="otplan['+rnr+'][date]" style="width:100px; cursor:pointer" type="text" />'+
						'</td>'+
						'<td>'+
							'<select name="otplan['+rnr+'][shiftteam]" class="shiftteam" style="background:transparent">'+
							'<? foreach($shiftteams as $k=>$v){echo '<option value="'.$k.'">'.$v.'</option>';} ?>'+
							'</select>'+
						'</td>'+
						'<td>'+
							'<input style="color:#999; min-width:55px" readonly class="plan tac" name="otplan['+rnr+'][plan]" type="text" />'+
						'</td>'+
						'<td>'+
							'<input style="color:#999; min-width:55px" readonly class="plan_f1 tac" name="otplan['+rnr+'][plan_f1]" type="text" />'+
						'</td>'+
						'<td>'+
							'<input style="color:#999; min-width:55px" readonly class="plan_u2 tac" name="otplan['+rnr+'][plan_u2]" type="text" />'+
						'</td>'+
						'<td>'+
							'<div class="clockpicker">'+
								'<button type="button"><i class="fa fa-clock-o"></i></button>'+
								'<input readonly placeholder="00:00" class="timePic from" type="text" name="otplan['+rnr+'][ot_from]" />'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<div class="clockpicker">'+
								'<button type="button"><i class="fa fa-clock-o"></i></button>'+
								'<input readonly placeholder="00:00" class="timePic until" type="text" name="otplan['+rnr+'][ot_until]" />'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<div class="clockpicker">'+
								'<button type="button"><i class="fa fa-clock-o"></i></button>'+
								'<input readonly placeholder="0:00" class="timePic break" type="text" name="otplan['+rnr+'][ot_break]" />'+
							'</div>'+
						'</td>'+
						'<td>'+
							'<input readonly placeholder="0:00" class="net_hours tac" name="otplan['+rnr+'][hours]" type="text" />'+
						'</td>'+
						'<td>'+
							'<select multiple="multiple" name="otplan['+rnr+'][compensations]" class="sel_compensations" style="background:transparent">'+
							'<? foreach($compensations as $k=>$v){ if($v['apply'] == 1){echo '<option value="'.$k.'">'.$v['code'].' - '.$v['description'].'</option>';}} ?>'+
							'</select>'+
						'</td>'+
						'<td class="tac">'+
							'<a class="selEmployees" data-id="'+rnr+'" href="#"><i class="fa fa-users fa-lg"></i></a>'+
						'</td>'+
					'</tr>';			
			
			$('#planTable tbody').append(row);
			$('#planBtn').prop('disabled', false);
			bindClockPicker();
			bindSumoselect();
			//rnr ++;
		})
		
		bindClockPicker();
		bindSumoselect();
		
		setTimeout(function(){$("#otPlan").fadeIn(200);},50);
		
	});
	
	</script>








	
