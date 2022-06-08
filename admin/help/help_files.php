<?php
	
	$res = $dba->query("SELECT * FROM rego_helpfiles ORDER BY page ASC");
	while($row = $res->fetch_assoc()){
		$data[$row['page']] = $row[$_SESSION['RGadmin']['lang'].'_title'];
	}	
	unset($data[5]);
	//var_dump($data); exit;
	
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
		<h2><i class="fa fa-question-circle fa-lg"></i>&nbsp;&nbsp;Help files</h2>
		<div class="widget_body" style="border:0px solid red; position:absolute; bottom:30px; top:90px; right:0; left:0; padding:50px 20px 0 20px">
			<form id="helpForm" style="height:100%">
			<input type="hidden" name="page" value="">
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
			<tr>
			<td style="width:18%; vertical-align:top; padding-right:10px; border-right:1px solid #eee">
				
				<table id="pageTable" class="basicTable pagesTable" border="0" style="width:100%">
					<thead>
						<tr>
							<th style="width:1px" class="tac">#</th>
							<th>Page</th>
						</tr>
					</thead>
					<!-- <tbody>
					<? foreach($data as $k=>$v){ ?>
						<tr>
							<td class="tac"><span class="page"><?=$k?></span></td>
							<td style="white-space:normal"><?=$v?></td>
						</tr>
					<? } ?>	
					</tbody> -->

					<tbody>

						<tr data-toggle="collapse" data-target="#empreg">
							<th class="tal" colspan="2"><?=$lng['Employee register']?></th>	
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 101 || $k == 102 || $k == 103){ ?>
								<tr id="empreg" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>

						<tr data-toggle="collapse" data-target="#payroll">
							<th class="tal" colspan="2"><?=$lng['Payroll']?></th>
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 3 || $k == 410 || $k == 411 || $k == 420 || $k == 430 || $k == 440 || $k == 442 || $k == 450 || $k == 460){ ?>
								<tr id="payroll" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>

						<tr data-toggle="collapse" data-target="#Time">
							<th class="tal" colspan="2"><?=$lng['Time module']?></th>
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 33 || $k == 4 || $k == 44 || $k == 55){ ?>
								<tr id="Time" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>

						<tr data-toggle="collapse" data-target="#Leave">
							<th class="tal" colspan="2"><?=$lng['Leave module']?></th>
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 201 || $k == 202 || $k == 203 || $k == 205){ ?>
								<tr id="Leave" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>

						<tr data-toggle="collapse" data-target="#ccModule">
							<th class="tal" colspan="2"><?=$lng['Communication center']?></th>
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 801 || $k == 802){ ?>
								<tr id="ccModule" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>

						<tr data-toggle="collapse" data-target="#Settings">
							<th class="tal" colspan="2"><?=$lng['Settings']?></th>
						</tr>
						<? foreach($data as $k=>$v){ 
							if($k == 630 || $k == 632 || $k == 640 || $k == 651 || $k == 602 || $k == 6010 || $k == 610 || $k == 608 || $k == 607 || $k == 300){ ?>
								<tr id="Settings" class="collapse">
									<td class="tac"><span class="page"><?=$k?></span></td>
									<td style="white-space:normal"><?=$v?></td>
								</tr>
						<? } } ?>
						
					</tbody>
					
				</table>
				<button style="margin-top:10px" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>

			</td>	
			<td style="width:41%; vertical-align:top; padding-right:10px; padding-left:10px; border-right:1px solid #eee">
				
				<table id="helpTable1" class="basicTable wsnormal" style="height:100%; width:100%">
					<thead>
						<tr>
							<th class="tac">Thai</th>
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
			<td style="width:41%; vertical-align:top; padding-left:10px">
					
				<table id="helpTable2" class="basicTable wsnormal" style="height:100%; width:100%">
					<thead>
						<tr>
							<th class="tac">English</th>
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
			
			$('#pageTable tbody tr').on('click', function(){
				$.each($('#pageTable tbody tr'), function(){
					$(this).removeClass('active');
				})
				$(this).addClass('active');
				var page = $(this).find('.page').html();
				//alert(page)
				$.ajax({
					url: "help/ajax/get_helpfiles.php",
					data: {page: page},
					dataType: 'json',
					success: function(data){
						$('#helpForm input[name="page"]').val(data.page);
						$(".summernote1").summernote("code", data.th);
						$(".summernote2").summernote("code", data.en);
						$('.summernote1').summernote('enable');
						$('.summernote2').summernote('enable');
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
				placeholder: '. . .',
				//tooltip: false,
				height: targetOffset,
				disableResizeEditor: true,
				disableDragAndDrop: true,
				lang: 'th-TH',
				fontNames: ['Arial', 'Arial Black'],
				fontSizes: ['13','15','18','20','22','26'],
				toolbar: [
					//['cleaner',['cleaner']],
					['style', ['style', 'bold', 'italic', 'underline', 'cleaner']],
					['fontsize', ['fontsize']],
					//['fontname', ['fontname']],
					['color', ['color']],
					['para', ['ul', 'ol', 'paragraph']],
					['insert', ['link', 'picture']],
					['misc', ['undo', 'redo']],
					['view', ['fullscreen', 'codeview']],
				],
    		cleaner:{
          action: 'button', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
          newline: '<br>', // Summernote's default is to use '<p><br></p>'
          notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
          icon: '<i class="fa fa-file-code-o"></i>',
          keepHtml: false, // Remove all Html formats
          keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
          keepClasses: false, // Remove Classes
          badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
          badAttributes: ['style', 'start'], // Remove attributes from remaining tags
          limitChars: false, // 0/false|# 0/false disables option
          limitDisplay: 'both', // text|html|both
          limitStop: false // true/false
    		},
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
			$('.summernote1').summernote('disable');
			$('.summernote2').summernote('disable');

		
			$("#helpForm").submit(function(e){ 
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url:"help/ajax/save_helpfiles.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); 
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
		
		
		
		});
	
	</script>
						














