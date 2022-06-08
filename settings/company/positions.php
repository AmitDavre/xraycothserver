<?
	
	$xpositions = getPositions();
	//var_dump($xpositions); exit;
	
?>
	<style>

	</style>
	
	<h2 style="padding-right:60px">
		<i class="fa fa-cog fa-mr"></i> <?=$lng['Positions']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
	
	<div class="main">
		<form id="positionForm">
			<div style="padding:0 0 0 20px" id="dump"></div>
			<table class="basicTable inputs" id="positionsTable" border="0">
				<thead>
					<tr>
						<th style="min-width:85px"><?=$lng['Code']?></th>
						<th style="width:20%"><?=$lng['Thai description']?></th>
						<th style="width:20%"><?=$lng['English description']?></th>
						<th style="width:10%"><?=$lng['Fixed allowances']?></th>
						<th style="width:10%"><?=$lng['Default value']?></th>
						<th style="width:50%"></th>
					</tr>
				</thead>
				<tbody>
				<? foreach($xpositions as $k=>$v){ ?>
					<tr>
						<td><input maxlength="6" readonly="readonly" style="font-weight:600" name="positions[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></td>
						<td><input name="positions[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
						<td><input name="positions[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<? } ?>
				</tbody>
			</table>
			<div style="height:10px"></div>
			<button class="btn btn-primary btn-xs" type="button" id="addPosition"><i class="fa fa-plus fa-mr"></i><?=$lng['Add row']?></button>
			<button class="btn btn-primary btn-fr" id="submitBtn" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
		</form>
	</div>
	
<script>
	
$(document).ready(function() {
			
	$("#addPosition").click(function(){
		var row = $("#positionsTable tbody tr").length + 1;
		var addrow = '<tr>'+
			'<td><input maxlength="6" style="font-weight:600" name="positions['+row+'][code]" type="text" /></td>'+
			'<td><input name="positions['+row+'][th]" type="text" /></td>'+
			'<td><input name="positions['+row+'][en]" type="text" /></td>'+
			'<td></td>'+
			'<td></td>'+
			'<td></td>'+
		'</tr>';
		$("#positionsTable tbody").append(addrow);
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});

	$("#positionForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var formData = $(this).serialize();
		$.ajax({
			url: "company/ajax/update_positions.php",
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
