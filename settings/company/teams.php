<?
	
	$xentities = getEntities();
	$xbranches = getBranches();
	$xdivisions = getDivisions();
	$xdepartments = getDepartments();
	$xteams = getTeams();
	//var_dump($xteams); exit;
	
?>
	<style>
		table.basicTable td select {
			padding:3px 8px !important;
		}
	</style>
	
	<h2 style="padding-right:60px">
		<i class="fa fa-cog fa-mr"></i> <?=$lng['Teams']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>	
	
	<div class="main">
		<form id="teamsForm">
			<div style="padding:0 0 0 20px" id="dump"></div>
				<table class="basicTable inputs" id="teamsTable" border="0">
					<thead>
						<tr>
							<th style="min-width:85px"><?=$lng['Code']?><i class="man"></i></th>
							<th style="width:20%; min-width:200px"><?=$lng['Thai description']?><i class="man"></i></th>
							<th style="width:20%; min-width:200px"><?=$lng['English description']?><i class="man"></i></th>
							<th style="min-width:100px"><?=$lng['Entity']?><i class="man"></i></th>
							<th style="min-width:100px"><?=$lng['Branch']?><i class="man"></i></th>
							<th style="min-width:100px"><?=$lng['Division']?><i class="man"></i></th>
							<th style="min-width:80px"><?=$lng['Department']?><i class="man"></i></th>
							<th style="width:80%"></th>
						</tr>
					</thead>
					<tbody>
					<? foreach($xteams as $key=>$val){ ?>
						<tr>
							<td><input class="addteamsec"  readonly maxlength="6" style="font-weight:600" name="teams[<?=$key?>][code]" type="text" value="<?=$val['code']?>" /></td>
							<td><input name="teams[<?=$key?>][th]" type="text" value="<?=$val['th']?>" /></td>
							<td><input name="teams[<?=$key?>][en]" type="text" value="<?=$val['en']?>" /></td>
							<td style="padding:0px 10px !important"><?=$entities[$val['entity']][$lang]?>
								<input name="teams[<?=$key?>][entity]" type="hidden" value="<?=$val['entity']?>" />
								<!--<select style="min-width:100%; width:auto" name="teams[<?=$key?>][entity]">
									<option disabled value="">Select</option>
									<? foreach($xentities as $k=>$v){ ?>
										<option <? if($val['entity'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
									<? } ?>
								</select>-->
							</td>
							<td style="padding:0px 10px !important"><?=$branches[$val['branch']][$lang]?>
								<input name="teams[<?=$key?>][branch]" type="hidden" value="<?=$val['branch']?>" />
								<!--<select style="min-width:100%; width:auto" name="teams[<?=$key?>][branch]">
									<option value="">Select</option>
									<? foreach($xbranches as $k=>$v){ ?>
										<option <? if($val['branch'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
									<? } ?>
								</select>-->
							</td>
							<td style="padding:0px 10px !important"><?=$divisions[$val['division']][$lang]?>
								<input name="teams[<?=$key?>][division]" type="hidden" value="<?=$val['division']?>" />
								<!--<select style="min-width:100%; width:auto" name="teams[<?=$key?>][division]">
									<option value="">Select</option>
									<? foreach($xdivisions as $k=>$v){ ?>
										<option <?  if($val['division'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
									<? } ?>
								</select>-->
							</td>
							<td style="padding:0px 10px !important"><?=$departments[$val['department']][$lang]?>
								<input name="teams[<?=$key?>][department]" type="hidden" value="<?=$val['department']?>" />
								<!--<select style="min-width:100%; width:auto" name="teams[<?=$key?>][department]">
									<option value="">Select</option>
									<? foreach($xdepartments as $k=>$v){ ?>
										<option <? if($val['department'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v[$lang]?></option>
									<? } ?>
								</select>-->
							</td>
							<td></td>
						</tr>
					<? } ?>
					</tbody>
				</table>
				<div style="height:10px"></div>
				<button class="btn btn-primary btn-xs" type="button" id="addTeam"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
				<button class="btn btn-primary btn-fr" id="submitBtn" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
			</div>
		</form>
	</div>
	
<script>
	
$(document).ready(function() {
	
	$(document).on('change','.selEntity', function() {
		var options = $(this).closest('tr').find('.selBranch');
		$.ajax({
			url: "company/ajax/get_branches.php",
			data: {entity: $(this).val()},
			dataType: 'json',
			success: function(result){
				//$('#dump').html(result); //return false;
				options.empty();
				$.each(result, function(k,v) {
					options.append($("<option />").val(k).text(v));
					//alert(v)
				});				
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
	
	$("#addTeam").click(function(){
		var row = $("#teamsTable tbody tr").length + 1;
		var addrow = '<tr >'+
			'<td ><input  class="addteamsec" onInput="showCurrentValue(event)" style="font-weight:600" maxlength="6" name="teams['+row+'][code]" type="text" /></td>'+
			'<td><input name="teams['+row+'][th]" type="text" /></td>'+
			'<td><input name="teams['+row+'][en]" type="text" /></td>'+
			'<td>'+
				'<select class="selEntity" style="min-width:100%; width:auto" name="teams['+row+'][entity]">'+
					'<option value="">Select</option>'+
					'<? foreach($xentities as $k=>$v){ ?>'+
						'<option value="<?=$k?>"><?=$v[$lang]?></option>'+
					'<? } ?>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<select class="selBranch" style="min-width:100%; width:auto" name="teams['+row+'][branch]">'+
					'<option value="">Select</option>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<select style="min-width:100%; width:auto" name="teams['+row+'][division]">'+
					'<option value="">Select</option>'+
					'<? foreach($xdivisions as $k=>$v){ ?>'+
						'<option value="<?=$k?>"><?=$v[$lang]?></option>'+
					'<? } ?>'+
				'</select>'+
			'</td>'+
			'<td>'+
				'<select style="min-width:100%; width:auto" name="teams['+row+'][department]">'+
					'<option value="">Select</option>'+
					'<? foreach($xdepartments as $k=>$v){ ?>'+
						'<option value="<?=$k?>"><?=$v[$lang]?></option>'+
					'<? } ?>'+
				'</select>'+
			'</td>'+
			'<td></td>'+
		'</tr>';
		$("#teamsTable tbody").append(addrow);
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});

	$("#teamsForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "company/ajax/update_teams.php",
			type: 'POST',
			data: formData,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');
				$("#submitBtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'empty'){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 2,
					})
					return false;
					//setTimeout(function(){location.reload();},2000);
				}else if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
						duration: 2,
					})
					//setTimeout(function(){location.reload();},2000);
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
					})
					return false;
				}
				window.location.reload();
				//setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	


	/*$('input').on('keyup', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
	$('select').on('change', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
	*/



			
});



// function giveerrormsg(that)
// {
// 	// check if code exists if exists disable the update button 
// 	 var dInput = this.value;
// 	 console.log(dInput);

// 	// $("body").overhang({
// 	// 	type: "error",
// 	// 	message: '<i class="fa fa-times"></i>&nbsp;&nbsp;<?=$lng['Please fill a unique team code']?>',
// 	// 	duration: 2,
// 	// })
// 	// return false;

// }

// $('table#teamsTable tbody tr.addteamsec2 td.addteamsec3 input.addteamsec').addClass('asdasda');


function showCurrentValue(event)
{
    var value = event.target.value;

    $.ajax({
				url: "company/ajax/check_code__teams.php",
				type: 'POST',
				data: {value: value},
				success: function(result){
					if($.trim(result) == 'exists'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-times"></i>&nbsp;&nbsp;<?=$lng['Please fill a unique team code']?>',
							duration: 2,
						})
						return false;
			
					
				}

				}
		  });



}
</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
