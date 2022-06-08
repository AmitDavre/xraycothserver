<?php
	
	$slider = array();
	$res = $dba->query("SELECT * FROM rego_company_settings");
	while($row = $res->fetch_assoc()){
		$promo = $row['promo'];
		$th_promo = $row['th_promo'];
		$en_promo = $row['en_promo'];
		$slider = unserialize($row['promo_slider']);
	}	
	$sCount = count($slider);
	
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
	.slideimg {
		visibility:hidden;
		height:0;
	}
	.slideDiv {
		cursor:pointer;
		margin:5px 0;
		position:relative;
	}
	.slideDiv i {
		position:absolute; 
		top:5px; 
		left:5px; 
		font-size:30px; 
		color:#d00; 
		display:block; 
		padding:0 2px; 
		background:#fff; 
		border-radius:50%; 
		opacity:0.4;
	}
	.slideDiv:hover > a i {
		opacity:1;
	}
	.card {
		box-shadow:none;
	}
</style>
	
	<div class="widget">
		<h2><i class="fa fa-product-hunt fa-lg"></i>&nbsp;&nbsp;Promo files</h2>
		<div class="widget_body" style="border:px solid red; position:absolute; bottom:35px; top:90px; right:0; left:0; padding:50px 20px 0 20px">
	<? 
		//var_dump($slider);
		//$slider = array();
		//$slider[1] = 'uploads/promo.png';
		//$slider[2] = 'uploads/promo.png';
		//$slider[3] = 'uploads/promo.png';
		//var_dump($slider); //exit;
	?>
		<form id="promoForm" style="height:100%">
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
				<tr>
					<td style="width:18%; vertical-align:top; padding-right:10px; border-right:1px solid #eee">
				
						<table id="" class="basicTable" border="0" style="width:100%">
							<thead>
								<tr>
									<th>Images</th>
								</tr>
							</thead>
							<tbody>
								<tr style="border:0;">
									<td style="padding:5px 0 0">
										<select name="promo" style="width:100%; border:1px solid #ddd">
											<option <? if($promo == 1){echo 'selected';}?> value="1">Show Promo on Log-in form</option>
											<option <? if($promo == 0){echo 'selected';}?> value="0">Hide Promo</option>
										</select>
									</td>
								</tr>
								<tr style="border:0;">
									<td id="sliderImg" style="padding:0">
										<? if($slider){ foreach($slider as $k=>$v){ ?>
											<div class="slideDiv">
												<img data-id="<?=$k?>" id="img<?=$k?>" width="100%" src="<?=$v?>?<?=time()?>">
												<input class="slideimg" id="slide<?=$k?>" data-id="#img<?=$k?>" name="slide[<?=$k?>]" type="file">
												<input id="slider<?=$k?>" name="slider[<?=$k?>]" type="hidden" value="<?=$v?>">
												<a data-id="<?=$k?>" class="delImg"><i class="fa fa-times-circle"></i></a>
											</div>
										<? } } ?>		
									</td>
								</tr>
							</tbody>
						</table>
						<!--<button id="addImg" style="margin-top:5px; float:right" class="btn btn-default btn-xs" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add image<? //=$lng['Update']?></button>-->
						
						<button style="margin-top:5px; float:right" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

					</td>	
					<td style="width:41%; vertical-align:top; padding-right:10px; padding-left:10px; border-right:1px solid #eee">
						<table class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th class="tac">Thai</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="summer" id="summerTD" style="vertical-align:top; padding:0; opacity:0">
										<textarea name="th_promo" class="summernote1"><?=$th_promo?></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="width:41%; vertical-align:top; padding-left:10px">
						<table class="basicTable wsnormal" style="height:100%; width:100%">
							<thead>
								<tr>
									<th class="tac">English</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="summer" style="vertical-align:top; padding:0; opacity:0">
										<textarea name="en_promo" class="summernote2"><?=$en_promo?></textarea>
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
			
			//var imgs = <? //=json_encode($sCount)?>;
			
			$("#promoForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				//$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				$.ajax({
					url:"help/ajax/update_promo_files.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
								duration: 2,
							})
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
			
			/*$('#addImg').on('click', function(){
				//if(imgs <= 2){
					var addRow = 
						'<div class="slideDiv">'+
							'<img data-id="'+imgs+'" id="img'+imgs+'" onClick="$("#slide'+imgs+'").trigger("click");" width="100%" src="../images/promo.png">'+
							'<input class="slideimg" id="slide'+imgs+'" data-id="#img'+imgs+'" name="slide['+imgs+']" type="file">'+
							'<a data-id="'+imgs+'" class="delImg"><i class="fa fa-times-circle"></i></a>'+
						'</div>';
						
					$('#sliderImg').append(addRow);
					imgs ++;
				//}
			})*/
			$(document).on('click', '.delImg', function(){
				//$(this).closest('div').remove();
				var id = $(this).data('id');
				$('#img'+id).prop('src', 'uploads/promo.png');
				$('#slider'+id).val('uploads/promo.png');
			})
			$(document).on('click', '.slideDiv img', function(){
				var id = $(this).data('id');
				$('#slide'+id).trigger('click');
			})
			
			
			$(document).on("change", ".slideimg", function(e){
				e.preventDefault();
				var slide = $(this).data('id');
				//alert(slide);
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				//alert(ff)
				$('#err_msg').html('');
				if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
					$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').fadeIn(200);
					setTimeout(function(){$("#message").fadeOut(200);},4000);
					return false;
				}
				//$('#logoname').html(ff);
				var file = $(this)[0].files[0];
				if(file){
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						var img = new Image;
						$(slide).prop('src', e.target.result);
						//$("#submitbtn").addClass('warning').flicker({wait:1000, cssValue: 0.4});
					}
				}
				//$('#message').html('');
				return false;
			});
			
			
			var targetOffset = $('#summerTD').height()-38;
			var summerOptions = {
				placeholder: 'Start typing ...',
				tooltip: false,
				height: targetOffset,
				disableResizeEditor: true,
				disableDragAndDrop: true,
				fontNames: ['Arial', 'Arial Black'],
				fontSizes: ['13','15','18','20','22','26'],
				toolbar: [
					['style', ['style', 'bold', 'italic', 'underline', 'clear']],
					['fontsize', ['fontsize']],
					//['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture']],
					['misc', ['undo', 'redo']],
					['view', ['fullscreen', 'codeview']],
				],
				styleTags: [
				  { title: 'Normal', tag: 'span', className: '', value: 'p' }, 
				  { title: 'Header 1', tag: 'span', className: 'head1', value: 'h5' }, 
				  { title: 'Header 2', tag: 'span', className: 'head2', value: 'h6' }, 
				  { title: 'Header 3', tag: 'span', className: 'head3', value: 'h3' }, 
				],
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
			$('.summer').css('opacity', 1);
		
		
		
		
		});
	
	</script>
						














