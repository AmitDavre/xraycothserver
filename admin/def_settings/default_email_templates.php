<?
	//if($_SESSION['xhr']['access']['sys_users']['access'] == 0){echo '<div class="msg_nopermit">You have no permission to enter this module</div>'; exit;}
	//var_dump($pr_settings);

	$disabled = '';
?>

<style>

</style>
	
	<h2><i class="fa fa-user"></i>&nbsp; <?=$lng['eMail templates']?></h2>
	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
         
			<div id="showTable" style="display:none">
				
				<div class="searchFilter" style="width:300px">
					 <input style="width:100%" placeholder="<?=$lng['Filter']?>" id="searchFilter" class="sFilter" type="text" />
					 <button id="clearSearchbox" type="button" class="clearFilter btn btn-default"><i class="fa fa-times"></i></button>
				</div>
         <? //if($_SESSION['xhr']['access']['sys_users']['add'] == 1){ ?>
         <button style="margin-top:0px;" type="button" class="btn btn-primary btn-fr" id="add_template"><i class="fa fa-plus"></i>&nbsp; Add new template<? //=$lng['Add new user']?></button>
         <? //} ?>
         <a href="index.php?mn=100" type="button" class="btn btn-info btn-fr">
         	<i class="fa fa-envelope"></i>&nbsp; Email Templates Design
         </a>
				<div class="clear"></div>

         <table id="datatable" class="dataTable nowrap">
            <thead>
               <tr>
                  <th><?=$lng['Code']?>/<?=$lng['Name']?></th> 
                  <th data-sortable="false" class="tac" style="width:1px"><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-edit fa-lg"></i></th>
                  <!--<th><?=$lng['Subject']?></th>-->
				  		<th data-sortable="false" style="width:90%"><?=$lng['Description']?></th>
               </tr>
            </thead>
            <tbody>

            </tbody>
         </table>
			</div>

	</div>
	
<style>

</style>	
	
	<!-- Modal Add / Edit Templates -->
	<div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="min-width:900px">
			  <div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><i class="fa fa-envelope"></i>&nbsp;&nbsp;eMail template<? //=$lng['Language variables']?></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 10px">
						<form style="font-size:13px" class="sform" id="templateForm">
						<table class="my_form_group" border="0" style="width:100%; table-layout:auto; margin-bottom:10px">
							<tr>
								<td>
									<label>Template name <i class="man"></i><? //=$lng['Subject']?></label>
									<input name="name" id="name" type="text" />
								</td>
								<td style="padding-left:10px; width:40%">
									<label><?=$lng['Description']?> <i class="man"></i></label>
									<input name="description_<?=$lang?>" id="description" type="text" />
								</td>
								<td style="padding-left:10px; display:none" id="usersPlaceholders">
									<label><?=$lng['Placeholders']?></label>
									<select class="replace">
										<option disabled selected value="0"><?=$lng['Add placeholder after cursor']?></option>
										<option value="{RECIPIENT}">{RECIPIENT}</option>
										<option value="{USERNAME}">{USERNAME}</option>
										<option value="{PASSWORD}">{PASSWORD}</option>
										<option value="{CLICK_HERE_LINK}">{CLICK_HERE_LINK}</option>
										<option value="{REPLY_EMAIL}">{REPLY_EMAIL}</option>
										<option value="{EMPLOYEE_NAME}">{EMPLOYEE_NAME}</option>
										<option value="{LEAVE_TYPE}">{LEAVE_TYPE}</option>
										<option value="{LEAVE_DETAILS}">{LEAVE_DETAILS}</option>
										<option value="{CERTIFICATE_DETAILS}">{CERTIFICATE_DETAILS}</option>
										<option value="{LEAVE_REASON}">{LEAVE_REASON}</option>
										<option value="{DATE_REQUEST}">{DATE_REQUEST}</option>
										<option value="{STATUS}">{STATUS}</option>

									</select>
								</td>
								<td style="padding-left:10px; display:none" id="suportPlaceholders">
									<label><?=$lng['Placeholders']?></label>
									<select class="replace">
										<option disabled selected value="0"><?=$lng['Add placeholder after cursor']?></option>
										<option value="{RECIPIENT}">{RECIPIENT}</option>
										<option value="{TICKET_ID}">{TICKET_ID}</option>
										<option value="{SUBJECT}">{SUBJECT}</option>
										<option value="{PRIORITY}">{PRIORITY}</option>
										<option value="{STATUS}">{STATUS}</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<label><?=$lng['Subject']?> <i class="man"></i></label>
									<input name="subject_<?=$lang?>" id="subject" type="text" />
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<label><?=$lng['Content']?></label>
									<textarea name="body_<?=$lang?>" id="body" rows="17"></textarea>
								</td>
							</tr>
						</table>
									
						<button class="btn btn-primary btn-fr" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?=$lng['Cancel']?></button>
						<button class="btn btn-primary btn-fr" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>
						</form>
					</div>
			  </div>
		 </div>
	</div>

	<!-- PAGE RELATED PLUGIN(S) -->

	<script type="text/javascript">
		
		//var heights = window.innerHeight-260;
		var headerCount = 1;
		//var scrY = heights;//true;
		
		$(document).ready(function() {
			//alert('ready')
			
			$(".replace").change(function() {
				var txtToAdd = $(this).val();//"({HRmanager)}";
				//alert(id);
				if(txtToAdd !== '0'){
					var caretPos = $("#body")[0].selectionStart;
					var textAreaTxt = $("#body").val();
					var curpos = caretPos+txtToAdd.length;
					$("#body").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos));
					$("#body").focus();
					$("#body")[0].setSelectionRange(curpos,curpos);
					$(this).val('0');
				}
			});
			
			var dtable = $('#datatable').DataTable({
				scrollY:        false,
				scrollX:        false,
				scrollCollapse: false,
				fixedColumns:   false,
				lengthChange:  false,
				searching: true,
				ordering: true,
				paging: true,
				pageLength: 16,
				filter: true,
				info: true,
				<?=$dtable_lang?>
				processing: false,
				serverSide: true,
				ajax: {
					url: "def_settings/ajax/server_default_email_templates.php",
					type: 'POST',
					"data": function(d){
						//d.incomplete = $('#incomplete').val();
						//d.department = $('#depFilter').val();
					}
				},
				columnDefs: [
					  //{"targets": [1], "class": 'pad1' },
				],
				initComplete : function( settings, json ) {
					$('#showTable').fadeIn(400);
					dtable.columns.adjust().draw();
				}
			});
			setTimeout(function(){
				$("#statFilter").trigger('change');
			},50);
			$("#searchFilter").keyup(function() {
				var s = $(this).val();
				dtable.search(s).draw();
			});
			$(document).on("click", "#clearSearchbox", function(e) {
				$('#searchFilter').val('');
				dtable.search('').draw();
			})
			
			// ADD TEMPLATE ------------------------------------------------------------------------------ ADD TEMPLATE
			$(document).on("click", "#add_template", function(){
				edit = 0;
				$("#modalTemplate").modal('toggle');
			})
			
			// EDIT TEMPLATE ----------------------------------------------------------------------------- EDIT TEMPLATE
			$(document).on("click", ".editTemplate", function(){
				edit = 1;
				var id = $(this).data('id');
				$.ajax({
					url: "def_settings/ajax/get_default_template_info.php",
					data: {id: id},
					dataType: 'json',
					success:function(data){
						//$('#dump').html(data); return false;
						if(data.name == 'NEW_EMP_USER' || data.name == 'NEW_SYS_USER' || data.name == 'EXISTING_USER' || data.name == 'NEW_COMPANY' || data.name == 'NEW_LEAVE_REQUEST' || data.name == 'APPROVE_LEAVE_REQUEST' || data.name == 'STATUS'){$('#usersPlaceholders').show(); $('#suportPlaceholders').hide();}
						if(data.name == 'TICKET_CLOSED' || data.name == 'TICKET_NEW' || data.name == 'TICKET_NOTIFY' || data.name == 'TICKET_REOPEN' || data.name == 'TICKET_REPLY'){$('#suportPlaceholders').show(); $('#usersPlaceholders').hide();}
						$("#name").val(data.name).prop('readonly', true);
						$("#subject").val(data.subject);
						$("#body").val(data.body);
						$("#description").val(data.description);

						$("#modalTemplate").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			// SUBMIT TEMPLATE FORM ---------------------------------------------------------------------- SUBMIT TEMPLATE FORM
			$(document).on('submit','#templateForm', function(e){
				e.preventDefault();
				if($('#name').val()==''){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 2,
					})
					return false;
				};
				var data = $(this).serialize();
				$.ajax({
					url:"def_settings/ajax/save_default_email_template.php",
					type: 'POST',
					data: data,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})
							dtable.ajax.reload(null, false);
							$("#modalTemplate").modal('toggle');
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 2,
							})
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 3,
							closeConfirm: "true",
						})
					}
				});
			});
			$('#modalTemplate').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
				$("#name").prop('readonly', false);
				edit = 0;
			});
			
			// DELETE TEMPLATE ---------------------------------------------------------------------- DELETE TEMPLATE
/*			var last_id;
			$(document).on("click", ".delUser", function(){
				last_id = $(this).data('id');
			})
			$('.delUser').confirmation({
				container: 'body',
				singleton: true,
				animated: 'fade',
				placement: 'left',
				popout: true,
				html: true,
				title: '<div style="text-align:center"><b><?=$lng['Are you sure']?></b></div>',
				btnOkIcon: '',
				btnCancelIcon: '',
				btnOkLabel: '<?=$lng['Delete']?>',
				btnCancelLabel: '<?=$lng['Cancel']?>',
				onConfirm: function() { 
					//alert(last_id);
					//return false;
					$.ajax({
						url:"settings/ajax/delete_user.php",
						data:{id: last_id},
						success: function(result){
							location.reload();
						},
						error:function (xhr, ajaxOptions, thrownError){
							alert(thrownError);
						}
					});
				}
			});
*/			
			
		})
	
	</script>























