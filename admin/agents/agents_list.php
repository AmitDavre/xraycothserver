<?

	//$password = generateStrongPassword(8, false);//randomPassword();
	//var_dump($password);

?>
	<link rel="stylesheet" type="text/css" href="../assets/css/croppie_users.css?<?=time()?>" />
	
<style>
	#upload-demo {
		background-size: 130px auto !important;
	}
	table.basicTable tbody th {
		padding:5px 10px !important;
	}
</style>

	<h2><i class="fa fa-user-secret"></i>&nbsp;&nbsp;REGO Agents<? //=$lng['System users']?></h2>
	<div class="main" style="padding:0">

	<div style="width:45%; height:100%; float:left">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
		
					<div class="searchFilter" style="margin:0 !important">
						<input style="margin:0" placeholder="<?=$lng['Filter']?>" class="sFilter" id="searchFilter" type="text" />
						<button id="clearSearchbox" type="button" class="clearFilter"><i class="fa fa-times"></i></button>
					</div>

					<select id="statFilter" class="button" style="margin:0 0 0 5px">
						<? foreach($agent_status as $k=>$v){
							echo '<option';
							if($k == 1){echo ' selected';}
							echo ' value="'.$k.'">'.$v.'</option>';
						} ?>
						<option value=""><?=$lng['Show all']?></option>
					</select>

					<button id="add_agent" class="btn btn-primary btn-fr" type="button"><i class="fa fa-plus"></i>&nbsp; Add Agent<? //=$lng['Cancel']?></button>

					<button id="all_agents" class="btn btn-primary btn-fr" type="button">Show all agents<? //=$lng['Cancel']?></button>

		 
		 <div class="showTable clear" style="display:none">
		 <table id="agentTable" class="hoverable selectable nowrap pad010">
				<thead>
					 <tr>
							<th class="tac"><i class="fa fa-user-secret fa-lg"></i></th>
							<th>Agent ID</th>
							<th><?=$lng['Name']?></th> 
							<th><?=$lng['Phone']?></th> 
							<th>Region<? //=$lng['Phone']?></th> 
							<th class="tac"><?=$lng['Status']?></th>
							<th><i data-toggle="tooltip" title="<?=$lng['Edit']?>" class="fa fa-edit fa-lg"></i></th>
					 </tr>
				</thead>
				<tbody>
	
				</tbody>
		 </table>
		 </div>
		</div>
	</div>

	<div style="width:55%; height:100%; background:#fff; float:right; border-left:1px solid #ccc">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<div id="dump2"></div>
			<table id="customerTable" class="nowrap pad010">
				<thead>
					<tr>
						<th><?=$lng['Client ID']?></th>
						<th><?=$lng['Company']?></th>
						<th><?=$lng['Subscriber']?></th>
						<th>Version<? //=$lng['Version']?></th>
						<th><?=$lng['Period start']?></th>
						<th><?=$lng['Period end']?></th>
						<th><?=$lng['Status']?></th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
	</div>

</div>
	 
	<!-- Modal Add / Edit agents -->
	<div class="modal fade" id="modalAgent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-width:700px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 style="font-weight:600" class="modal-title" id="myModalLabel"><i class="fa fa-user"></i>&nbsp;&nbsp;Add new Agent<? //=$lng['Add new user']?> <span id="aUser"></span></h4>
					</div>
					<div class="modal-body" style="padding:15px 20px 12px">
					<span style="font-weight:600; color:#cc0000; display:none; display:block; margin:-5px 0 5px 0" id="userMess"></span>
					<form id="agentForm">
						<input type="hidden" name="status" value="1" />
						<input type="hidden" name="img" id="img" value="uploads/agents/profile_image.jpg" />
								<table class="basicTable inputs" border="0">
									<tbody>
									<tr>
										<th><i class="man"></i>Agent ID<? //=$lng['Name']?></th>
										<td><b><input onKeyUp="$('#password').val(this.value)" placeholder="__" name="agent_id" type="text" value="" /></b></td>
										<td rowspan="7" style="width:1%; padding:0 0 0 10px !important; vertical-align:top">
											<div id="upload-demo" style="margin:0 0 1px; float:none"></div>
											<input style="height:0; visibility:hidden" id="selectUserImg" type="file" name="user_img" />
											<button onclick="$('#selectUserImg').click();" type="button" id="userBut" style="width:100%; margin-top:1px; float:none; border-radius:0" class="btn btn-primary btn-xs"><i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Select picture']?></button>
										</td>
									</tr>
										<th><i class="man"></i><?=$lng['Name']?> Thai</th>
										<td><input placeholder="__" name="th_name" type="text" value="" /></td>
									<tr>
										<th><i class="man"></i><?=$lng['Name']?> English</th>
										<td><input placeholder="__" name="en_name" type="text" value="" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['Phone']?></th>
										<td><input placeholder="__" name="phone" type="text" value="" /></td>
									</tr>
									<tr>
										<th><i class="man"></i><?=$lng['email']?></th>
										<td><input placeholder="__" name="email" type="text" value="" /></td>
									</tr>
									<tr>
										<th><?=$lng['Line ID']?></th>
										<td><input placeholder="__" name="line_id" type="text" value="" /></td>
									</tr>
									<tr>
										<th>Region<? //=$lng['Location']?></th>
										<td><input placeholder="__" name="region" type="text" value="" /></td>
									</tr>
									<tr>
										<th>Startdate<? //=$lng['Status']?></th>
										<td colspan="2"><input style="cursor:pointer" placeholder="__" name="startdate" class="datepick" type="text" value="" /></td>
									</tr>
									<tr>
										<th>Other job<? //=$lng['Location']?></th>
										<td colspan="2"><input placeholder="__" name="other_job" type="text" value="" /></td>
									</tr>
									<tr>
										<th><?=$lng['Address']?></th>
										<td colspan="2"><input placeholder="__" name="address" type="text" value="" /></td>
									</tr>
									<tr>
										<th>Tax ID<? //=$lng['Tax ID']?></th>
										<td colspan="2"><input placeholder="__" name="tax_id" type="text" value="" /></td>
									</tr>
									<tr class="hideRow">
										<th><i class="man"></i><?=$lng['Password']?></th>
										<td colspan="2"><input autocomplete="off" name="password" id="password" type="text" value="" /></td>
									</tr>
									<tr>
										<th><?=$lng['Remarks']?></th>
										<td colspan="2"><textarea placeholder="__" rows="2" name="remarks"></textarea></td>
									</tr>
									<tr>
										<th style="padding:3px 10px !important">
											<input style="visibility:hidden; height:0; width:0" type="file" name="certificate_attach" id="certificate_attach" />
											<button onclick="$('#certificate_attach').click();" style="width:100%" type="button" class="btn btn-outline-secondary btn-xs"><?=$lng['Certificate']?></button>
										</th>
										<td style="padding:4px 10px !important" colspan="2">
											<span id="certificate_name"><?=$lng['No file selected']?></span>
										</td>
									</tr>
									<tr>
										<th style="padding:3px 10px !important">
											<input style="visibility:hidden; height:0; width:0" type="file" name="agreement_attach" id="agreement_attach" />
											<button onclick="$('#agreement_attach').click();" style="width:100%" type="button" class="btn btn-outline-secondary btn-xs">Agreement</button>
										</th>
										<td style="padding:4px 10px !important" colspan="2">
											<span id="agreement_name"><?=$lng['No file selected']?></span>
										</td>
									</tr>
									<tr>
										<th style="padding:3px 10px !important">
											<input style="visibility:hidden; height:0; width:0" type="file" name="idcard_attach" id="idcard_attach" />
											<button onclick="$('#idcard_attach').click();" style="width:100%" type="button" class="btn btn-outline-secondary btn-xs"><?=$lng['ID card']?></button>
										</th>
										<td style="padding:4px 10px !important" colspan="2">
											<span id="idcard_name"><?=$lng['No file selected']?></span>
										</td>
									</tr>
									</tbody>
								</table>
								
					
						<div class="clear" style="height:10px"></div>
						<button id="saveUser" class="btn btn-primary btn-fl" type="button"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?></button>

						<button class="btn btn-primary btn-fr" type="button" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>

					</form>
					</div>
			  </div>
		 </div>
	</div>

	<!-- PAGE RELATED PLUGIN(S) -->
	<script type='text/javascript' src="../assets/js/croppie.js?<?=time()?>"></script>
	<script type="text/javascript" src="../assets/js/jquery.autocomplete.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.sumoselect.min.js"></script>

	<script type="text/javascript">
		
		//var heights = window.innerHeight-260;
		//var headerCount = 1;
		//var scrY = heights;//true;
		
		$(document).ready(function() {
			
			var sedit = 1;//<?//=json_encode($_SESSION['xhr']['access']['sys_users']['edit'])?>;
			var edit = 0;
			var user_id;
			var agent_id = 'xxx';

			function readAttURL(input,id) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function (e) {
						var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
						var ext = input.files[0].name.split('.').pop();
						if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
							//alert("Only  " + fileExtension.join(', ')+ "  formats are allowed !");
							$(id).html('<b style="color:#b00;font-weight:600">Only  ' + fileExtension.join(', ')+ '  formats are allowed !</b>');
						}else{				
							$(id).html(input.files[0].name);
						}
					}
					reader.readAsDataURL(input.files[0]);
				}
			 }
	 
			$("#certificate_attach").change(function(){
					readAttURL(this,'#certificate_name');
			});
			$("#agreement_attach").change(function(){
					readAttURL(this,'#agreement_name');
			});
			$("#idcard_attach").change(function(){
					readAttURL(this,'#idcard_name');
			});
			
			ctable = $('#customerTable').DataTable({
				lengthChange:  false,
				ordering: 		false,
				paging: 			true,
				pageLength: 	16,
				info: 				false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				ajax: {
					url: AROOT+"agents/ajax/server_get_agent_companies.php",
					type: 'POST',
					"data": function(d){
						d.agent = agent_id;
					}
				},
				columnDefs: [
					{ targets: 1, "width": '80%',},
				],	
				initComplete : function( settings, json ) {
					$('.showTable').fadeIn(200);
					setTimeout(function(){
						ctable.columns.adjust().draw();
					}, 10);
				},
			});
			
			atable = $('#agentTable').DataTable({
				lengthChange:  false,
				searching: 		true,
				ordering: 		false,
				pageLength: 	16,
				filter: 			true,
				info: 				true,
				autoWidth:		false,
				<?=$dtable_lang?>
				processing: 	false,
				serverSide: 	true,
				ajax: {
					url: AROOT+"agents/ajax/server_get_agents.php",
					type: 'POST',
					"data": function(d){
						d.status = $('#statFilter').val();
					}
				},
				columnDefs: [
					{ targets: 2, "width": '80%',},
					{ targets: [0,5], "class": 'nopadding',}
				],	
				initComplete : function( settings, json ) {
					$('.showTable').fadeIn(200);
					setTimeout(function(){
						atable.columns.adjust().draw();
					}, 10);
				}
			});
			$("#searchFilter").keyup(function() {
				atable.search(this.value).draw();
			});
			$("#clearSearchbox").click(function() {
				$("#searchFilter").val('');
				atable.search('').draw();
			});
			$(document).on("change", "#statFilter", function(e) {
				atable.ajax.reload(null, false);
			})
			$(document).on('click', '#agentTable td:nth-child(2), #agentTable td:nth-child(3), #agentTable td:nth-child(4), #agentTable td:nth-child(5)', function() {
				agent_id = $(this).closest('tr').find('.emp_id').html();
				ctable.ajax.reload(null, false);
			})
			$(document).on('click', '#all_agents', function() {
				agent_id = 'all';
				ctable.ajax.reload(null, false);
			})
			$(document).on("change", ".statusAgent", function(){
				var id = $(this).closest('tr').find('.emp_id').html();
				var status = $(this).val();
				$.ajax({
					url: AROOT+"agents/ajax/update_agent.php",
					data: {id: id, field: 'status', value: status},
					success:function(result){
						//$('#dump').html(result);
						atable.ajax.reload(null, false);
					}
				});
			})
			
			// ADD USER ----------------------------------------------------------------------------------------------------------- ADD USER
			$(document).on("click", "#add_agent", function(){
				edit = 0;
				$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;Add new Agent<? //=$lng['Add new user']?> <span id="aUser"></span>');
				/*$.ajax({
					url: AROOT+"agents/ajax/get_agent_user_ID.php",
					success:function(data){
						//$('#dump').html(data);
						$("#user_id").val(data);
						$("#password").val(data);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});*/
				$('input[name="agent_id"]').prop('readonly', false);
				$("#modalAgent").modal('toggle');
			})
		
			var $uploadCrop;
			var maxSize = 2000;
			var minHeight = 130;
			var minWidth = 130;
			$uploadCrop = $('#upload-demo').croppie({
				viewport: {
					width: 130,
					height: 130,
					type: 'square'
				},
				boundary: {
					width: 160,
					height: 160
				},
				mouseWheelZoom: true,
				showZoom: true
			});

			$('#selectUserImg').on('change', function () { 
				$("#userBut i").removeClass('fa-user').addClass('fa-repeat fa-spin');
				readFile(this);
			});
			
			function readFile(input) {
				if(input.files && input.files[0]) {
					var file = input.files[0];
					var reader = new FileReader();
					reader.onload = function (e) {
						var img = new Image;
						var t = file.type.split('/')[1];
						var n = file.name;
						var s = ~~(file.size/1024);
						var msg = "";
						if(s > maxSize){msg = '<?=$lng['Filesize is to bigg']?> ('+(s/1024).format(2)+' Mb) - Max. '+(maxSize/1000)+' Mb';}
						if(t != 'jpeg' && t != 'png' && t != 'jpg'){msg = "<?=$lng['Please use only ... files']?>";}
						if(msg!=''){
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+msg,
								duration: 4,
								closeConfirm: true
							})
							$("p#selpic i").removeClass('fa-repeat fa-spin').addClass('fa-user');
							$("#upload").val('');
							return false;
						}
						img.src = e.target.result;
						img.onload = function() {
							$("#userBut i").removeClass('fa-repeat fa-spin').addClass('fa-user');
						};
						//$('#klik').html('');
						$('#orig').attr('src', e.target.result);
						$uploadCrop.croppie('bind', {
							url: e.target.result
						});
						$('.upload-demo').addClass('ready');
						$("#message").fadeOut(400);
					}
					reader.readAsDataURL(input.files[0]);
				}else{
				  swal("Sorry - you're browser doesn't support the FileReader API");
				}
			};
			
			// CHECK AGENT ID ------------------------------------------------------------------------------------ SAVE USER
			$('#saveUser').on('click', function () { 
				if(edit == 1){$('form#agentForm').submit(); return false;}
				var agent_id = $('input[name="agent_id"]').val();
				if(agent_id == ''){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				}
				$.ajax({
					url: AROOT+"agents/ajax/check_agent_id.php",
					data: {agent_id: agent_id},
					success:function(result){
						//$('#dump').html(result);
						if(result == 'exist'){
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This Agent ID exist already<? //=$lng['This username exist already']?>',
								duration: 4,
								closeConfirm: true
							})
							return false;
						}
						if(result == 'success'){
							//$('#userMess').html('').hide();
							$('form#agentForm').submit();						
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			// SUBMIT AGENT FORM ------------------------------------------------------------------------------- SUBMIT USER FORM
			$(document).on('submit','form#agentForm', function(e){
				e.preventDefault();
				//alert(edit)
				//if(checkPassword($('#password').val()) != ''){
					//$('#userMess').html('<div class="msg_alert"><b>Weak password !</b><br>'+checkPassword($('#password').val())+'</div>').hide().fadeIn(400); return false;
				//}
				var err = true;
				//if($('#user_id').val()==''){err = false};
				if(edit == 0 && $('input[name="email"]').val()==''){err = false};
				if(edit == 0 && $('input[name="password"]').val()==''){err = false};
				if($('input[name="th_name"]').val()==''){err = false};
				if($('input[name="en_name"]').val()==''){err = false};
				if($('input[name="phone"]').val()==''){err = false};
				if($('input[name="email"]').val()==''){err = false};
				if(err == false){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				}
				/*if(edit == 0 && $('input[name="password"]').val().length < 8){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Password to short min 8 characters']?>',
						duration: 4,
						closeConfirm: true
					})
					return false
				};*/

				var data = new FormData($(this)[0]);
				var file = $('#selectUserImg')[0].files[0];
				if(!file){
					$.ajax({
						url: AROOT+"agents/ajax/save_agent_data.php",
						type: 'POST',
						data: data,
						async: false,
						cache: false,
						contentType: false,
						processData: false,
						success: function(result){
							//$('#dump2').html(result);
							atable.ajax.reload(null, false);
							$("#modalAgent").modal('toggle');
							//setTimeout(function(){location.reload();},400);
						},
						error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 4,
							})
						}
					});
					return false;
				}
				var reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function (e) {
					var img = new Image;
					img.src = e.target.result;
					img.onload = function() {
					$uploadCrop.croppie('result', {
						type: 'canvas',
						size: 'viewport'
					}).then(function (resp) {
						data.append('img_data', resp); 
						$.ajax({
							url: AROOT+"agents/ajax/save_agent_data.php",
							type: 'POST',
							data: data,
							async: false,
							cache: false,
							contentType: false,
							processData: false,
							success: function(result){
								//$('#dump').html(result);
								atable.ajax.reload(null, false);
								$("#modalAgent").modal('toggle');
								//setTimeout(function(){location.reload();},400);
							},
							error:function (xhr, ajaxOptions, thrownError){
								$("body").overhang({
									type: "error",
									message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
									duration: 4,
								})
							}
						});
					});
					};
				}
			});
			$('#modalAgent').on('hidden.bs.modal', function () {
				$(this).find('form').trigger('reset');
				$('#userMess').html('');
				$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;<?=$lng['Add new user']?>');
				$('#upload-demo').css('background-image', 'url(../../images/profile_image.jpg)');
				$('#upload-demo').removeClass('ready');
				$uploadCrop.croppie('bind', {url : ''})
				$('.hideRow').css('display','table-row');
				$("#password").prop('disabled', false);
				$("#certificate_attach").val('');
				$("#agreement_attach").val('');
				$("#idcard_attach").val('');
				edit = 0;
			});
			
			// EDIT AGENT DATA ----------------------------------------------------------------------------------------------- EDIT USER DATA
			$(document).on("click", ".editAgent", function(){
				edit = 1;
				var id = $(this).data('id');
				//alert(id)
				$.ajax({
					url: AROOT+"agents/ajax/get_agent_data.php",
					data: {id: id},
					dataType: 'json',
					success:function(data){
						//$('#dump2').html(data);
						
						$('.hideRow').css('display','none');
						$('input[name="agent_id"]').prop('readonly', true);
						$("#img").val(data.img);
						$("#password").prop('disabled', true);
						$('#myModalLabel').html('<i class="fa fa-user"></i>&nbsp;&nbsp;Edit Agent');
						$('#upload-demo').css('background-image', 'url('+data.img+'?<?=time()?>)');
						
						$('input[name="agent_id"]').val(data.agent_id);
						$('input[name="th_name"]').val(data.th_name);
						$('input[name="en_name"]').val(data.en_name);
						$('input[name="phone"]').val(data.phone);
						$('input[name="email"]').val(data.email);
						$('input[name="line_id"]').val(data.line_id);
						$('input[name="region"]').val(data.region);
						$('input[name="startdate"]').val(data.startdate);
						$('input[name="other_job"]').val(data.other_job);
						$('input[name="address"]').val(data.address);
						$('input[name="tax_id"]').val(data.tax_id);
						
						if(data.certificate != ''){
							$('#certificate_name').html('<a target="_blank" href="'+AROOT+'uploads/agents/'+data.certificate+'">'+data.certificate+'</a>');
						}else{
							$('#certificate_name').html('No file selected');
						}
						if(data.agreement != ''){
							$('#agreement_name').html('<a target="_blank" href="'+AROOT+'uploads/agents/'+data.agreement+'">'+data.agreement+'</a>');
						}else{
							$('#agreement_name').html('No file selected');
						}
						if(data.idcard != ''){
							$('#idcard_name').html('<a target="_blank" href="'+AROOT+'uploads/agents/'+data.idcard+'">'+data.idcard+'</a>');
						}else{
							$('#idcard_name').html('No file selected');
						}
						$('textarea[name="remarks"]').val(data.remarks);

						$("#modalAgent").modal('toggle');
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			// DELETE AGENT ----------------------------------------------------------------------------------------------- 
			var last_id;
			$(document).on("click", ".deleteAgent", function(){
				last_id = $(this).data('id');
			})
			$(document).ajaxComplete(function( event,request, settings ) {
				$('.delAgent').confirmation({
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
						$.ajax({
							url: AROOT+"agents/ajax/delete_agent.php",
							data:{id: last_id},
							success: function(result){
								//$('#dump').html(result);
								atable.ajax.reload(null, false);
							},
							error:function (xhr, ajaxOptions, thrownError){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
								duration: 4,
							})
							}
						});
					}
				});
			})
			
		})
	
	</script>















