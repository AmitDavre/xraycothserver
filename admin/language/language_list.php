 
	<h2><i class="fa fa-table"></i>&nbsp;&nbsp;Language List</h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		
		<div id="showTable" style="display:none">
						
			<div class="searchFilter" style="margin:0 0 8px 0; width:450px">
				<input style="margin:0" placeholder="<?=$lng['Filter']?>" class="sFilter" id="searchFilter" type="text" />
				<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
			</div>
			<!--<span id="message"></span>-->
			<button onclick="window.location.href='language/export_language_list.php';" class="btn btn-primary btn-fr"><i class="fa fa-download"></i>&nbsp; <?=$lng['Export data file']?></button>
			<? //if($_SESSION['RGadmin']['access']['lang']['add'] == 1){ ?>
			<button class="btn btn-primary btn-fr" id="addNew"><i class="fa fa-plus"></i>&nbsp; <?=$lng['Add new']?></button>
			<? //} ?>
			<div class="clear"></div>
			
			<table id="datatable" class="dataTable hoverable selectable">
				<thead>
				<tr>
					<th><?=$lng['Code']?></th>
					<th><?=$lng['English']?></th>
					<th><?=$lng['Thai']?></td>
					<th data-orderable="false"><i rel="tooltip" title="<?=$lng['Edit']?>" class="fa fa-edit fa-lg"></i></th>
				</tr>
				</thead>
	
			</table>
		</div>

   </div>

	<!-- Modal EDIT -->
	<div class="modal fade" id="modalEditLang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:700px">
			  <div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-language"></i>&nbsp;&nbsp;<?=$lng['Language variables']?></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 12px">
					<form class="sform" id="langForm" method="post">
						<label><?=$lng['Code']?></label>
						<input readonly style="font-weight:600;color:#a00; background:#f3f3f3" name="code" id="code" type="text" />

						<label><?=$lng['English']?></label>
						<input name="en" id="en" type="text" />

						<label><?=$lng['Thai']?></label>
						<input name="th" id="th" type="text" />

						<div style="height:10px"></div>
						<button class="btn btn-primary btn-fr" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?=$lng['Cancel']?></button>
						<button class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save"></i> <?=$lng['Update']?></button>
					</form>
					</div>
			  </div>
		 </div>
	</div>

	<script>
		var heights = window.innerHeight-305;
		var headerCount = 1;

		$(document).ready(function() {
			
			var eDit = 1;//<? //=json_encode($_SESSION['RGadmin']['access']['lang']['edit'])?>;
			if(eDit == 1){edit = true;}else{edit = false;}
			
			$("#langForm").submit(function(e){ 
				e.preventDefault();
				if($("#code").val()=='' || $("#en").val()=='' || $("#th").val()==''){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in all the fields']?>',
						duration: 4,
					})
					return false;
				}
				var data = $(this).serialize();
					$.ajax({
						url: "language/ajax/update_language_list.php",
						data: data,
						success: function(result){
							if(result == 'ok'){
								$("body").overhang({
									type: "success",
									message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
									duration: 2,
								})
								dtable.ajax.reload(null, false);
								setTimeout(function(){$('#modalEditLang').modal('toggle');},500);
							}else{
								$("body").overhang({
									type: "warn",
									message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
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
			$("#addNew").on('click', function(){
				$('#code').prop('readonly', false);
				$('#code').css('background','#fff');
				$('#modalEditLang').modal('toggle');
			});
			
			$(document).on('click','#datatable tbody tr a', function() {
				var code = $(this).closest('tr').find('.code').text();
				var en = $(this).closest('tr').find('.en').text();
				var th = $(this).closest('tr').find('.th').text();
				$('#code').css('background','#eee');
				$('#code').val(code);
				$('#en').val(en);
				$('#th').val(th);
				$('#modalEditLang').modal('toggle');
			})
			$('#modalEditLang').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
				$('#code').prop('readonly', true);
				$("#modMess").html('');
				$('#code').css('background','#f3f3f3');
			});
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        false,
				scrollCollapse: false,
				fixedColumns:   false,
				lengthChange:  	false,
				searching: 		true,
				ordering: 		true,
				paging: 		true,
				pageLength: 	19,
				filter: 		true,
				info: 			true,
				autoWidth:		false,
				processing: 	false,
				serverSide: 	true,
				<?=$dtable_lang?>
				ajax: {
					url: "language/ajax/server_get_language_list.php", 
				},
				columnDefs: [
					{ targets: 3, "visible": edit},
					{ targets: 3, "searchable": false},
					{ targets: 0, "width": '30%'},
					{ targets: 1, "width": '35%'},
					{ targets: 2, "width": '35%'},
					{ targets: 3, "width": '1px'}
				],	
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(200);
					dtable.columns.adjust().draw();
				}
			});

			$("#searchFilter").keyup(function() {
				dtable.search(this.value).draw();
			});
			$("#clearSearchbox").click(function() {
				$("#searchFilter").val('');
				dtable.search('').draw();
			});
			
		});
	
	</script>
						














