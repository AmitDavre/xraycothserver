<?

	$fix_allow = unserialize($sys_settings['fix_allow']);
	$var_allow = unserialize($sys_settings['var_allow']);
	//var_dump($fix_allow);

	$account_codes = unserialize($sys_settings['account_codes']);
	if($account_codes){
		$acc_count = count($account_codes);
	}else{
		$acc_count = 0;
	}
	//var_dump($account_codes); exit;

?>

	<h2 style="padding-right:60px"><i class="fa fa-file-code-o"></i>&nbsp; <?=$lng['Accounting Codes']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		
	
	<form id="codeForm">
		<div class="main">
			<div style="padding:0 0 0 20px" id="dump"></div>

			<table class="basicTable inputs" id="accounting_table" border="0">
				<thead>
					<tr>
						<th style="width:10%"><?=$lng['Code']?></th>
						<th style="width:25%"><?=$lng['Account name Thai']?></th>
						<th style="width:25%"><?=$lng['Account name English']?></th>
						<th style="width:40%"></th>
						<th class="tac"><i class="fa fa-trash fa-lg"></i></th>
					</tr>
				</thead>
				<tbody>
				<? if($account_codes){ foreach($account_codes as $k=>$v){ ?>
					<tr>
						<td><b><input name="account_codes[<?=$k?>][code]" type="text" value="<?=$v['code']?>" /></b></td>
						<td><input name="account_codes[<?=$k?>][th]" type="text" value="<?=$v['th']?>" /></td>
						<td><input name="account_codes[<?=$k?>][en]" type="text" value="<?=$v['en']?>" /></td>
						<td></td>
						<td class="tac"><a href="#" class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>
					</tr>
				<? } } ?>
				</tbody>
			</table>
			<div style="height:10px"></div>
			<button class="btn btn-primary btn-sm" type="button" id="addAccountCode"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add row']?></button>
      <button class="btn btn-primary" style="margin:0 0 8px 0; float:right" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

		</div>
	</form>
	
<script>
	
$(document).ready(function() {
	
	var acc = <?=json_encode($acc_count + 1)?>;
	$("#addAccountCode").click(function(){
		var rowCount = $('#accounting_table tbody tr').length;
		var addrow = '<tr>'+
			'<td><b><input placeholder="<?=$lng['Code']?>" name="account_codes['+acc+'][code]" type="text" /></b></td>'+
			'<td><input placeholder="<?=$lng['Account name Thai']?>" name="account_codes['+acc+'][th]" type="text" /></td>'+
			'<td><input placeholder="<?=$lng['Account name English']?>" name="account_codes['+acc+'][en]" type="text" /></td>'+
			'<td></td>'+
			'<td class="tac"><a class="delCode"><i class="fa fa-trash fa-lg"></i></a></td>'+
		'</tr>';
		if(acc == 1 || rowCount == 0){
			$("#accounting_table tbody").html(addrow);
		}else{
			$("#accounting_table tr:last").after(addrow);
		}
		acc ++;
	});
	
	$(document).on('click', '.delCode', function (e) {
		$(this).closest('tr').remove();
		$("#sAlert").fadeIn(200);
		$("#submitbtn").addClass('flash');
	});
	
	$("#codeForm").submit(function(e){ 
		e.preventDefault();
		$("#submitbtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var data = $(this).serialize();
		//alert(data)
		$.ajax({
			url: ROOT+"settings/ajax/update_account_codes.php",
			type: 'POST',
			data: data,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitbtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly . . . Please check Accounting allocations']?>',
						duration: 4,
					})
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
						//closeConfirm: true
					})
				}
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
	
	$('input, textarea').on('keyup', function (e) {
		$("#submitbtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
	$('select, .checkbox').on('change', function (e) {
		$("#submitbtn").addClass('flash');
		$("#sAlert").fadeIn(200);
		//alert('click')
	});
			
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
