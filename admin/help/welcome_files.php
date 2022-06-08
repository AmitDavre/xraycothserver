<?php
	
	$res = $dba->query("SELECT * FROM rego_welcomefiles ORDER BY page ASC");
	while($row = $res->fetch_assoc()){
		$data[$row['page']] = $row[$_SESSION['RGadmin']['lang'].'_title'];
	}	
	//var_dump($data);
?>

  	<link rel="stylesheet" href="../assets/css/summernote-bs4.css?<?=time()?>">
		<script type="text/javascript" src="../assets/js/summernote-bs4.js?<?=time()?>"></script>
		<script type="text/javascript" src="../assets/js/summernote-cleaner.js?<?=time()?>"></script>
		<? if($lang == 'th'){ ?>
		<script type="text/javascript" src="../assets/js/summernote-th-TH.js?<?=time()?>"></script>
		<? } ?>
	
<style>
	
	table.basicTable.pagesTable tbody tr:hover {
		background:#dfd;
		cursor:pointer;
	}
	table.basicTable.pagesTable tbody tr:hover > td {
		color:#b00;
		font-weight:600;
	}
	table.basicTable.pagesTable tbody tr.active {
		background:#ffc;
	}
	table.basicTable.pagesTable tbody tr.active > td {
		color:#b00;
		font-weight:600;
	}
	
	.basicTable tbody td p {
		padding:0 0 0 10px;
		margin:0 0 5px !important;
	}
	.basicTable thead th {
		background:#eee;
		border-bottom:1px solid #ccc;
		font-weight:600;
	}
	.card {
		box-shadow:none;
	}
</style>
	
	<div class="widget">
		<h2><i class="fa fa-handshake-o fa-lg"></i>&nbsp;&nbsp;Welcome files</h2>
		<div class="widget_body" style="border:0px solid red; position:absolute; bottom:30px; top:90px; right:0; left:0; padding:50px 20px 0 20px">
			<form id="helpForm" style="height:100%">
			<input type="hidden" name="page" value="">
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
			<tr>
			<td style="width:22%; vertical-align:top; padding-right:10px; border-right:1px solid #eee">
				
				<table id="pageTable" class="basicTable pagesTable" border="0" style="width:100%">
					<thead>
						<tr>
							<th style="width:1px" class="tac">#</th>
							<th>Button</th>
						</tr>
					</thead>
					<tbody>
					<? foreach($data as $k=>$v){ ?>
						<tr>
							<td><span class="page"><?=$k?></span></td>
							<td><?=strtoupper($v)?></td>
						</tr>
					<? } ?>	
					</tbody>
				</table>
				<button id="submitBtn" disabled style="margin-top:10px; float:right" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<button id="addLine" style="margin-top:10px; float:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Line<? //=$lng['Update']?></button>

			</td>	
			<td style="width:39%; vertical-align:top; padding-right:10px; padding-left:10px; border-right:1px solid #eee">
				
				<table id="helpTable1" class="basicTable wsnormal" style="height:100%; width:100%">
					<thead>
						<tr>
							<th>Thai</th>
						</tr>
						<tr style="background:#fff">
							<td style="padding-left:10px"><b>Button text : </b><input disabled maxlength="35" style="background:transparent; display:inline; width:60%; border:0" type="text" name="th_title" id="th_title" placeholder="..."></td>
						</tr>
					</thead>
					<tbody>
						<tr style="border:x0">
							<td id="helpTD" style="vertical-align:top; padding:0">
								<textarea name="th_content" id="th_content" class="summernote1"><? //=$th_content?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
					
			</td>
			<td style="width:39%; vertical-align:top; padding-left:10px">
					
				<table id="helpTable2" class="basicTable wsnormal" style="height:100%; width:100%">
					<thead>
						<tr>
							<th>English</th>
						</tr>
						<tr style="background:#fff">
							<td style="padding-left:10px"><b>Button text : </b><input disabled maxlength="35" style="background:transparent; display:inline; width:60%; border:0" type="text" name="en_title" id="en_title" placeholder="..."></td>
						</tr>
					</thead>
					<tbody>
						<tr style="border:x0">
							<td style="vertical-align:top; padding:0">
								<textarea name="en_content" id="en_content" class="summernote2"><? //=$en_content?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			
			</td>
			</tr>
			</table>	
			</form>				

		</div>
   </div>
	
	<script>

		$(document).ready(function() {
			
			var line = <?=json_encode(count($data)+1)?>;
			$('#addLine').on('click', function(){
				var addrow = '<tr>'+
						'<td><span class="page">'+line+'</span></td>'+
						'<td>New button</td>'+
					'</tr>';
				$("#pageTable tbody tr:last").after(addrow);
				$("#pageTable tbody tr:last").trigger('click');
				line ++;
			})
			
			$(document).on('click', '#pageTable tbody tr', function(){
				$.each($('#pageTable tbody tr'), function(){
					$(this).removeClass('active');
				})
				$(this).addClass('active');
				var page = $(this).find('.page').html();
				//alert(page)
				$.ajax({
					url: "help/ajax/get_welcomefiles.php",
					data: {page: page},
					dataType: 'json',
					success: function(data){
						$('#helpForm input[name="page"]').val(data.page);
						$("#th_title").val(data.th_title).prop('disabled', false);
						$("#en_title").val(data.en_title).prop('disabled', false);
						$(".summernote1").summernote("code", data.th_content);
						$(".summernote2").summernote("code", data.en_content);
						$('.summernote1').summernote('enable');
						$('.summernote2').summernote('enable');
						$('#submitBtn').prop('disabled', false);
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
			
			var targetOffset = $('#helpTD').height()-38;
			var summerOptions = {
				placeholder: 'Start typing ...',
				tooltip: true,
				height: targetOffset,
				disableResizeEditor: true,
				disableDragAndDrop: true,
				fontNames: ['Arial', 'Arial Black'],
				fontSizes: ['13','15','18','20','22','26'],
				toolbar: [
					['style', ['bold', 'italic', 'clear']],
					['fontsize', ['fontsize']],
					//['fontname', ['fontname']],
					['color', ['color']],																																																						
					['para', ['ul', 'ol', 'paragraph']],
              	['insert', ['link', 'picture']],
              	//['view', ['fullscreen', 'codeview']],
              	['view', ['fullscreen']],
					['misc', ['undo', 'redo']],
				],
				styleTags: ['p', 'h2', 'h3'],
				callbacks: {
				  onPaste: function (e) {
					var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
					e.preventDefault();
					// Firefox fix
					setTimeout(function () {
						 document.execCommand('insertText', false, bufferText);
					}, 10);
				  }
				 }
		  	}
			$('.summernote1').summernote(summerOptions);
			$('.summernote2').summernote(summerOptions);
			$('.summernote1').summernote('disable');
			$('.summernote2').summernote('disable');

			$("#helpForm").submit(function(e){ 
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url:"help/ajax/save_welcomefiles.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							//setTimeout(function(){location.reload();},2000);	
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
								duration: 4,
								closeConfirm: true
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
	
	</script>
						














