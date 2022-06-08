<?php

	if($_GET['tbid'] > 0){

		$txtfields = array();
		$sql1 = "SELECT * FROM ".$cid."_textblock_fields WHERE txtblock_id = '".$_GET['tbid']."'";
		if($res1 = $dbc->query($sql1)){
			while($rowss = $res1->fetch_assoc()){
				$txtfields[$rowss['id']] = $rowss['name'];
			}
		}
	}

?>

<!-- <link href="css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" /> -->
<!-- <link rel="stylesheet" href="assets/js/summernote-lite.css?<?//=time()?>"> -->

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<!--<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />-->
	
	<!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
    <!--<link href="assets/plugins/summernote/dist/summernote.css?<?=time()?>" rel="stylesheet" />-->
    <link href="../assets/css/summernote-bs4.css?<?=time()?>" rel="stylesheet" />

<style>
div.note-statusbar {
	display:none;
}

li.list-group-item {
    width: 48%;
    padding: 3px;
}

.form-group.note-form-group.note-group-select-from-files {
    display: none;
}
</style>
	
	<div style="height:100%; border:0px solid red; position:relative">
		
		<div style="position:absolute; left:0; top:51px; right:70%; bottom:0; background:#fff;">
			
			<div class="smallNav">
				<ul>
					<div class="xsearchFilter" style="display:none">
						<input placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
					</div>
					<li id="btnNew" class="flr" style="display:none" ><a><i class="fa fa-plus"></i> &nbsp;New Textblock</a>
				</ul>
			</div>
			
			<div id="leftTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:0; overflow-X:hidden">

				<div class="row mb-2">
					<div class="col-md-6">
						<input type="text" name="addmore" id="morefield" placeholder="Enter field name..." onkeyup="this.value = this.value.toUpperCase();">
					</div>
					<?
						$btnStyle = '';
						if($_GET['tbid'] <= 0 || $_GET['tbid'] == 'n'){ $btnStyle = 'disabled="disabled"';}
					?>
					<div class="col-md-6">
						<button type="button" <?=$btnStyle;?> onclick="addMorefields(this)" class="btn btn-success"><i class="fa fa-plus mr-2"></i> <?=$lng['Add Field']?></button>
					</div>
				</div>
				
				<span class="text-danger"><i><?=$lng['Please click on the below buttons to add values in the rght side editer']?></i></span>
				
				<ul class="list-group" id="listddd">
					<? if(isset($txtfields) && is_array($txtfields)){ foreach($txtfields as $k => $v){ ?>
					  <li class="list-group-item"><a class="btn btn-default btn-block"><?=$v?></a> </li>
					<? } } ?>
				</ul>
				
			</div>
		</div>
					
		<div style="position:absolute; left:30%; top:51px; right:0; bottom:0; background: #f6f6f6; border-left:1px solid #ddd">
			
			<div class="smallNav">
				<ul>
					<li id="btnEdit" style="display:none"><a><i class="fa fa-edit"></i> &nbsp;Edit</a>
					<li id="btnSave" style="display:none"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Save']?></a>
					<li id="btnUpdate" style="display:none"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
					<li id="btnCancel" style="display:none" class="flr"><a><i class="fa fa-times"></i> &nbsp;Cancel</a>
					<li id="btnPrint" style="display:none"><a id="qPrint" target="_blank"><i class="fa fa-print"></i> &nbsp;Print PDF</a>
					<li onclick="modalGenUrlfn()"><a><i class="fa fa-link"></i> &nbsp;<?=$lng['Generate image URL']?></a>
				</ul>
				  
			</div>
			
			<div id="rightTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:15px 15px 15px 15px;display: none;">

			<form id="blockForm" autocomplete="nope">
				<fieldset xdisabled>
					<table id="textTable" width="100%">
						<tbody>
							<tr>
								<td>
									<input style="width:100%" placeholder="Textblock name" type="text" name="name" />
								</td>
							</tr>
							<tr>
								<td>
									<textarea class="summernote" name="text"></textarea>
								</td>
							</tr>
						</tbody>
					</table>
					
					<input name="id" type="hidden" value="">
					<input name="status" type="hidden" value="0">
				</fieldset>
			</form>
			
			</div>
			
		</div>
		

	</div>

	<!---------- Generate image URL ------>
	<div id="modalGenUrl" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document" style="max-width:500px">
			<div class="modal-content">
				<form id="GenUrl" autocomplete="off" method="post" enctype="multipart/form-data">
					<div class="modal-header">
					  	<h5 class="modal-title"><i class="fa fa-link"></i>&nbsp;&nbsp;<?=$lng['Generate image URL'];?></h5>
					  	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						 	<span aria-hidden="true">&times;</span>
					  	</button>
					</div>
					<div class="modal-body" style="padding: 10px">

						<div class="row" id="appendLink">
							<div class="col-md-12 m-2">
								<input type="file" name="genfile">
							</div>
						</div>

					</div>
					<div class="modal-footer">
				        <button id="GenUrlss" type="button" class="btn btn-primary"> <?=$lng['Generate Link'];?></button>
				    </div>
				</form>
			</div>
		</div>
	</div>
	<!----------- Generate image URL ---->
	
	<!-- PAGE RELATED PLUGIN(S) -->

 	<script src="../assets/js/summernote-bs4.js?<?=time()?>"></script>
	
	<script type="text/javascript">

	function modalGenUrlfn(){
		$('#modalGenUrl').modal('toggle');
	}

	function addMorefields(that){
		var mval = $('#morefield').val();
		if(mval !=''){

			var tbid = '<?=$_GET['tbid']?>';

			$.ajax({
				type: 'post',
				url:"document_setup/ajax/textblock_fields.php",
				data: {'mval': mval, 'tbid': tbid },
				success: function(result){
					if($.trim(result) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
							duration: 2,
							callback: function(value){
								location.reload();
							}
						})
					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>:'+result,
							duration: 3,
						})
					}
				}
			})

			$("#listddd").append('<li class="list-group-item"><a class="btn btn-default btn-block">'+mval+'</a></li>');
		}else{
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
				duration: 3,
			})

			$('#morefield').focus();
		}
	}

	$(document).ready(function() {

		var tbid = '<?=$_GET['tbid']?>';
		if(tbid !='n'){
			getTextblock(tbid);
		}

		function getTextblock(id) {

			$.ajax({
				url:"document_setup/ajax/get_textblock.php",
				data: {id: id},
				dataType: "json",
				success: function(data){
					//$('#dump').html(data);
					//return false
					$('#btnSave').hide()
					$('#btnUpdate').show()
					$('#btnCancel').hide()
					$('input[name="id"]').val(data.id)
					$("#textTable tbody").html('')
					var addRow = '<tr>'+
						'<tr>'+
							'<td style="padding-bottom:10px">'+
								'<input style="width:100%" placeholder="Textblock name" type="text" name="name" value="'+data.name+'" />'+
							'</td>'+
						'</tr>'+
						'<td style="padding-bottom:10px">'+
							'<textarea name="text" class="summernote">'+data.text+'</textarea>'+
						'</td>'+
					'</tr>';
					
					$('#textTable tbody').append(addRow)
					$('.summernote').summernote(summerOptions);
					$("#rightTable").fadeIn(200)
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError+' 1');
				}
			});
		}
		
      	var targetOffset = $('#rightTable').height() - 90;
		var summerOptions = {
			placeholder: 'Start typing ...',
			//tooltip: false,
			maximumImageFileSize: 50000,
			height: targetOffset,
			disableResizeEditor: true,
			toolbar: [
					['cleaner',['cleaner']],
					['style', ['style', 'bold', 'italic', 'underline', 'cleaner']],
					['fontsize', ['fontsize']],
					['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture']],
					['misc', ['undo', 'redo']],
					['view', ['fullscreen', 'codeview']],
				],
			callbacks: {
                    onImageUpload: function (data) {
                        data.pop();
                    }
                }

		}
		$('.summernote').summernote(summerOptions);


		$("#listddd li a").click(function() {
			$('.summernote').summernote('editor.saveRange');
			$('.summernote').summernote('editor.restoreRange');
			$('.summernote').summernote('editor.focus');
		  	$('.summernote').summernote('editor.insertText', '{'+$(this).text()+'}');
		});
		
		
		function clearForm(){
			$('#rightTable').fadeOut(200);
			//$('input[name="status"]').val(0)
			$('input[name="id"]').val('')
			$('#blockForm').trigger('reset');
			$("#textTable tbody").html('')

			$('#btnUpdate').hide()
			$('#btnSave').hide()
			$('#btnCancel').hide()
			$('#btnPrint').hide()
			$("#eMsg").html('').hide();

		}
		clearForm()
		//$('#rightTable').fadeIn(200);
		
		
		$(document).on('click', ".delItem", function(e) {
			$(this).closest('tr').remove()
		})
		
		openEditer();

		function openEditer(){
		//$(document).on('click', "#btnNew", function(e) {

			//alert('dfdf');
			clearForm()
			$('#btnSave').show()
			//$('#btnCancel').show()
			//$('input[name="id"]').val('')
			//$('#quoteForm fieldset').prop('disabled', false);
			//$("#textTable tbody").html('')
			var addRow = '<tr>'+
				'<tr>'+
					'<td style="width:100%; padding-bottom:10px">'+
						'<input style="width:100%" placeholder="Textblock name" type="text" name="name" />'+
					'</td>'+
				'</tr>'+
				'<td style="padding-bottom:10px">'+
					'<textarea name="text" class="summernote"></textarea>'+
				'</td>'+
			'</tr>';
			$('#textTable tbody').append(addRow)
			$('.summernote').summernote(summerOptions);
			$('#rightTable').fadeIn(200);
		//})
		}

		$(document).on('click', "#btnCancel", function(e) {
			clearForm()
		})

		$("#btnUpdate, #btnSave").on('click', function(e){ 
			$("#blockForm").submit()
		})
		$("#blockForm").submit(function(e){ 
			e.preventDefault();
			
			var data = $(this).serialize();
			var id = $('input[name="id"]').val()
			$.ajax({
				url:"document_setup/ajax/save_textblock.php",
				data: data,
				type: 'POST',
				success: function(result){
					
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Textblock saved successfully',
							duration: 3,
							callback: function(v){

								var tbid = '<?=$_GET['tbid']?>';
								if(tbid == 'n'){
									window.location.href = 'index.php?mn=303';
								}else{
									location.reload();
								}
								
							}
						})
					}
				}
			});
		});


		$('#GenUrlss').on('click', function (e) { 
			$('#GenUrl').submit();
		});

		$('#GenUrl').on('submit', function (e) { 
			e.preventDefault();
			
			var formData = new FormData($('#GenUrl')[0]);
			
			$.ajax({
				type : 'post',
				url: "document_setup/ajax/generate_img_link.php",
				data: formData,
				//dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){	

					if(result == 'error'){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?>: '+result,
							duration: 3,
						})
						
					}else{

						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Link generated successfully<br>'+result,
							duration: 10,
						})
		
						// var html = '<br><h2><i class="fa fa-link"></i>  '+result+'</h2>';
						// $('#modalGenUrl #appendLink').append(html);

					}
				}
			})
			
		});

	})
	
</script>





















	
