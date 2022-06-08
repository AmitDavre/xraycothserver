<?php
		
	$th_content = '';
	$en_content = '';
	$res = $dba->query("SELECT * FROM rego_cookie_confirmation");
	if($row = $res->fetch_assoc()){
		$th_content = $row['th_content'];
		$en_content = $row['en_content'];
	}	
	//var_dump($th_content);
	//var_dump($en_content);
?>

  	<link rel="stylesheet" href="../assets/css/summernote-bs4.css?<?=time()?>">
		<script type="text/javascript" src="../assets/js/summernote-bs4.js?<?=time()?>"></script>
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
		xfont-weight:600;
	}
	table.basicTable.pagesTable tbody tr.active {
		background:#ffc;
	}
	table.basicTable.pagesTable tbody tr.active > td {
		color:#b00;
		font-weight:600;
	}
	.card {
		box-shadow:none;
	}

</style>
	
	<form id="termsForm">
	<h2><i class="fa fa-file-text-o fa-lg"></i>&nbsp;&nbsp;Cookie Confirmation Text<button class="btn btn-primary" style="float:right; margin-top:3px"><i class="fa fa-save"></i>&nbsp; Update</button></h2>
	
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
			<table style="width:100%; height:calc(100% - 3px); table-layout:fixed" border="0">
				<tr>
					<td style="width:50%; vertical-align:top; padding-right:10px; border-right:1px solid #eee">
				
						<table id="helpTable1" class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th>Thai</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border:x0">
									<td id="helpTD" style="vertical-align:top; padding:0">
										<table class="my_form_group" border="0" style="width:100%; table-layout:auto; margin-bottom:10px">
											<tr>
												<td style="padding-left:10px;" id="usersPlaceholders">
													<label><?=$lng['Placeholders']?></label>
													<select class="replace1">
														<option disabled selected value="0"><?=$lng['Add placeholder after cursor']?></option>
														<option value="{USERNAME}">{USERNAME}</option>
														<option value="{TERMS_DATE}">{TERMS_DATE}</option>
														<option value="{TERMS_TIME}">{TERMS_TIME}</option>
														<option value="{PRIVACY_DATE}">{PRIVACY_DATE}</option>
														<option value="{PRIVACY_TIME}">{PRIVACY_TIME}</option>
														<option value="{COOKIE_DATE}">{COOKIE_DATE}</option>
														<option value="{COOKIE_TIME}">{COOKIE_TIME}</option>
													</select>
												</td>
											</tr>
											<br>
											<tr>
												<td colspan="3">
													<label><?=$lng['Content']?></label>
													<textarea name="body_th" id="body1" rows="17" class="summernote1" ><?php echo $th_content; ?></textarea>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					
					</td>
					<td style="width:50%; vertical-align:top; padding-left:10px">
							
						<table id="helpTable2" class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th>English</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border:x0">
									<td style="vertical-align:top; padding:0">
											<table class="my_form_group" border="0" style="width:100%; table-layout:auto; margin-bottom:10px">
												<tr>
													<td style="padding-left:10px;" id="usersPlaceholders">
														<label><?=$lng['Placeholders']?></label>
														<select class="replace2">
															<option disabled selected value="0"><?=$lng['Add placeholder after cursor']?></option>
															<option value="{USERNAME}">{USERNAME}</option>
															<option value="{DATE}">{DATE}</option>
															<option value="{TIME}">{TIME}</option>
														</select>
													</td>
												</tr>
												<br>
												<tr>
													<td colspan="3">
														<label><?=$lng['Content']?></label>
														<textarea name="body_en" id="body2" rows="17" class="summernote2"><?php echo $en_content; ?></textarea>
													</td>
												</tr>
											</table>
														
									</td>
								</tr>
							</tbody>
						</table>
			
					</td>
				</tr>
			</table>	

   </div>
	</form>
					
	<script>

		$(document).ready(function() {


			$(".replace1").change(function() {
				var txtToAdd = $(this).val();
				if(txtToAdd !== '0'){
					var caretPos = $("#body1")[0].selectionStart;
					var textAreaTxt = $("#body1").val();
					var curpos = caretPos+txtToAdd.length;
					$("#body1").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
					$("#body1").focus();
					$("#body1")[0].setSelectionRange(curpos,curpos);
					$(this).val('0');
				}
			});			

			$(".replace2").change(function() {
				var txtToAdd = $(this).val();//"({HRmanager)}";
				//alert(id);
				if(txtToAdd !== '0'){
					var caretPos = $("#body2")[0].selectionStart;
					var textAreaTxt = $("#body2").val();
					var curpos = caretPos+txtToAdd.length;
					$("#body2").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
					$("#body2").focus();
					$("#body2")[0].setSelectionRange(curpos,curpos);
					$(this).val('0');
				}
			});


			
			var thcontent = <?=json_encode($th_content)?>;
			var encontent = <?=json_encode($en_content)?>;
			
			var targetOffset = $('#helpTD').height()-38;
			var summerOptions = {
				placeholder: 'Start typing ...',
				tooltip: false,
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
              	//['insert', ['link', 'picture']],
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
			$(".summernote1").summernote("code", thcontent);
			$(".summernote2").summernote("code", encontent);
			
			$("#termsForm").submit(function(e){ 
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url:"help/ajax/update_cookie_confirmation.php",
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
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
								duration: 4,
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
						














