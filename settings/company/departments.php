<?
	
	$xdepartments = getDepartments();
	//var_dump($xdepartments); exit;
	
?>
	<style>

	</style>
	
	<h2 style="padding-right:60px">
		<i class="fa fa-cog fa-mr"></i> <?=$lng['Departments']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
	
	<div class="main">
		<form id="departmentForm">
			<div style="padding:0 0 0 20px" id="dump"></div>
					<table class="basicTable inputs" id="departmentsTable" border="0">
						<thead>
							<tr>
								<th style="min-width:85px"><?=$lng['Code']?></th>
								<th style="width:25%; min-width:280px"><?=$lng['Thai description']?></th>
								<th style="width:25%; min-width:280px"><?=$lng['English description']?></th>
								<th style="width:50%"></th>
							</tr>
						</thead>
						<tbody>
						<? foreach($xdepartments as $k=>$v){ ?>
							<tr>
								<td><input maxlength="6" readonly="readonly" style="font-weight:600" name="departments[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></td>
								<td><input name="departments[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
								<td><input name="departments[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
								<td></td>
							</tr>
						<? } ?>
						</tbody>
					</table>
					<div style="height:10px"></div>
					<button class="btn btn-primary btn-xs" type="button" id="addDepartment"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
				<button class="btn btn-primary btn-fr" id="submitBtn" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
			</div>
		</form>
	</div>
	
<script>
	
$(document).ready(function() {
			
	$("#addDepartment").click(function(){
		var row = $("#departmentsTable tbody tr").length + 1;
		var addrow = '<tr>'+
			'<td><input style="font-weight:600" maxlength="6" name="departments['+row+'][code]" type="text" /></td>'+
			'<td><input name="departments['+row+'][th]" type="text" /></td>'+
			'<td><input name="departments['+row+'][en]" type="text" /></td>'+
			'<td></td>'+
		'</tr>';
		$("#departmentsTable tbody").append(addrow);
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});

	$("#departmentForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "company/ajax/update_departments.php",
			type: 'POST',
			data: formData,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitBtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
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
				}
				setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	
	$('input').on('keyup', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
			
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
