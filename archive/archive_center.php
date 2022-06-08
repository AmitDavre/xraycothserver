<?
	/*if($_SESSION['rego']['access']['payroll']['module'] == 0 && $_SESSION['rego']['access']['approve']['payroll']['access'] == 0){
		echo '<div class="msg_nopermit">You have no permission<br>to enter this page</div>'; 
		exit;
	}*/

?>
<style>
	.dataTable > thead > tr > th[class*="sort"]:after{
		 content: "" !important;
	}
	.conf-btn-green {
		background:green;
		color:#fff;
		display:block;
	}
	.conf-btn-red {
		background:red;
		color:#fff;
		display:block;
	}
</style>
	
	<link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css?<?=time()?>">
	<link rel="stylesheet" href="../assets/css/myDatatables.css?<?=time()?>">
	<link rel="stylesheet" href="../assets/css/dropzone.css?<?=time()?>">
      
	<h2><i class="fa fa-file-pdf-o"></i>&nbsp; <?=$lng['Archive center']?></h2>
			
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">		
		<table class="basicTable" border="0" style="margin-bottom:10px; width:100%">
			<tbody>
				<tr style="border:0">
					<th style="border-right:0; border-bottom:1px solid #eee; padding:0 0 0 10px; vertical-align:middle"><?=$lng['Save in']?> :</th>
					<td style="padding:0 10px 0 0; border-bottom:1px solid #eee">
						<select id="month" style="width:120px">
							<? foreach($months as $k=>$v){
								echo '<option ';
								if($_SESSION['rego']['cur_month'] == $k){echo 'selected ';}
								echo 'value="'.$k.'">'.$v.'</option>';
							} ?>
						</select>
					</td>
					<td style="width:90%; padding:0; border-bottom:1px solid #eee">
						
						<form action="../archive/upload_documents.php" class="dropzone" id="myDropzone">
							<div class="fallback">
								<input style="visibility:hidden" name="file" type="file" multiple />
								<input name="table" type="hidden" value="approvals" />
							</div>
						</form>
						
					</td>
					<td style="border:0 !important; padding:0; padding-left:5px">
						<button id="upload" class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload file']?></button></td>
				</tr>
			</tbody>
			
		</table>
				
		<? include('../include/tabletabs.php'); ?>
		
		<table id="datatable" class="dataTable compact nowrap" width="100%" border="0">
			<thead>
				<tr>
					<th data-sortable="false"><i class="fa fa-file-pdf-o"></i></th>
					<th data-sortable="true"><?=$lng['Filename']?></th>
					<th data-sortable="false"><?=$lng['Filesize']?></th>
					<th><?=$lng['Uploaded']?></th>
					<th data-sortable="false" style="width:90%"><?=$lng['By']?></th>
					<th data-sortable="false"><i class="fa fa-download fa-lg"></i></th>
					<th data-sortable="false"><i class="fa fa-trash fa-lg"></i></th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
		</div>

	</div>
               
	<script src="../assets/js/jquery.dataTables.min.js"></script>
	<script src="../assets/js/dataTables.bootstrap4.min.js"></script>
	<script src="../assets/js/dropzone.js?<?=time()?>"></script>
	
<script type="text/javascript">
	var headerCount = 1;
	
	$(document).ready(function() {
	
		var month = <?=json_encode($_SESSION['rego']['cur_month'])?>;
		
		var dtable = $('#datatable').DataTable({
			scrollY:        false,//scrY,//heights-288,
			scrollX:        true,
			scrollCollapse: false,
			fixedColumns:   false,
			lengthChange:  	false,
			searching: 		true,
			ordering: 		true,
			paging: 		true,
			pageLength: 	20,
			filter: 		false,
			info: 			false,
			//autoWidth:		false,
			processing: 	false,
			serverSide: 	true,
			order: [[3, "desc"]],
			<?=$dtable_lang?>
			ajax: {
				url: "ajax/sever_get_report_files.php",
				type: "POST",
				data: function(d){ 
					d.month = month;
				}
			},
			columnDefs: [
				//{ targets: [3], "class": 'tar' },
			],
			initComplete : function( settings, json ) {
				$('#showTable').fadeIn(200);
				dtable.columns.adjust().draw();
			}
		})
		$('#tableTab'+month).addClass('activ');
		
		$(document).ajaxComplete(function( event,request, settings ) {
			$('.delete').confirmation({
				container: 'body',
				rootSelector: '.delete',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title 			: '<?=$lng['Are you sure']?>',
				btnOkClass 		: 'btn btn-danger btn-sm',
				btnOkLabel 		: '<?=$lng['Delete']?>',
				btnOkIconContent: '',
				btnCancelClass 	: 'btn btn-success btn-sm',
				btnCancelLabel 	: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					//alert('Confirm'); return false;
					$.ajax({
						url: ROOT+"archive/ajax/delete_document.php",
						data:{id: $(this).data('id')},
						success: function(result){
							//$("#dump").html(result); return false;
							dtable.ajax.reload(null, false);
						}
					});
				}
			});
		});
		
		function removeFilterClass(){
			$('.month-btn').each(function(){
				$(this).removeClass('activ')
			})
		}
		$('.fnMonth').on('click', function () {
			month = $(this).data('val');
			removeFilterClass()
			$(this).addClass('activ')
			dtable.ajax.reload(null, false);
		});			
		$('.fnClear').on('click', function () {
			month = '';
			removeFilterClass()
			$(this).addClass('activ')
			dtable.ajax.reload(null, false);
		});			
			
		var myDrop = Dropzone.options.myDropzone = {
			paramName: "file",
			maxFilesize: 10, // MB
			autoProcessQueue: false,
			url: ROOT+'archive/upload_documents.php',
			uploadMultiple: false,
			parallelUploads: 1,
			maxFiles: 1,
			addRemoveLinks: true,
			dictDefaultMessage: '<b><i class="fa fa-caret-right"></i>&nbsp;<?=$lng['Drop file']?></b> <small><?=$lng['to upload']?> &nbsp; (<?=$lng['or click']?>)</small><? //=$lng['Drop files to upload']?>',
			dictInvalidFileType: "<?=$lng['Wrong file type']?>",
			dictFileTooBig: "<?=$lng['File is too big']?>",
			dictRemoveFile: "<?=$lng['Remove file']?>",
			dictMaxFilesExceeded: "<?=$lng['You can not upload more files']?>",
			//acceptedFiles: ".pdf,.xls,.xlsx,.doc,.docx,.txt",
		
			init: function() {
				var thisDropzone = this;
				var uploadButton = document.querySelector("#upload");
				uploadButton.addEventListener("click", function () {
					//alert('upload')
					thisDropzone.processQueue();
					setTimeout(function(){thisDropzone.removeAllFiles();}, 1000);
				});		
				this.on("drop", function(event) {
					//alert("drop");    
				});					
				this.on("complete", function(file) {
					//alert("complete")
					//this.removeFile(file);
				});		
				this.on("sending", function(file, xhr, formData) {
					formData.append('document', $('#document').val());
					formData.append('month', $('#month').val());
					
				});	
				this.on("error", function(file, message) { 
					//this.removeFile(file);
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;' + message,
						duration: 4,
					})
					this.removeFile(file);
				});		
				this.on("maxfilesexceeded", function(file){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['You can only upload 1 file at a time']?>',
						duration: 4,
					})
					this.removeFile(file);
				});			
				this.on("success", function(file, responseText) {
					//$("#dump").html(responseText); return false;
					//$("#btnSubmit").prop('disabled', true);
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['File uploaded successfuly']?>',
						duration: 2,
					})
					setTimeout(function(){dtable.ajax.reload(null, false);}, 1000);
				});		
			},
			accept: function(file, done) {
				done();
			}
		};
		
		
  	})
	
</script>

