<?
	$err_msg = "";
	$data = array();
	$sql = "SELECT * FROM rego_default_settings"; 
	if($res = $dba->query($sql)){
		$data = $res->fetch_assoc();
	}else{
		$err_msg = '<div class="box_err ibox">'.$lng['Error'].' : '.mysqli_error($dba).' <a class="box_close"><i class="fa fa-times fa-lg"></i></a></div>';
	}


	$nonemailsss =  unserialize($data['non_email']);
	// echo '<pre>';
	// print_r($nonemailsss);
	// echo '</pre>';

	// die();
		
?>
	
	<h2><i class="fa fa-industry"></i>&nbsp; <?=$lng['Consent Settings']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>	
	<div class="main">
		

		<form id="companyForm" style="height:100%">
			<ul style="position:relative" class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link active" data-target="#tab_company" data-toggle="tab"><?=$lng['Settings']?></a></li>
				<button class="btn btn-primary" style="position:absolute; bottom:6px; right:1px;" id="subButton" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<? //} ?>
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				
				<div style="display:none" id="message"></div>
				
				<div class="tab-pane show active" id="tab_company">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th style="width:1%"><?=$lng['Terms & Condition Renewal']?></th>
								<td>
									<select name="terms_renewal" id="terms_renewal" >
										<option value="select"><?=$lng['Select']?></option>
										<? foreach($renewalOptions as $k=>$v){ ?>
											<option <?php if($data['terms_renewal'] == $k){echo "selected";}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Privacy Policy Renewal']?></th>
								<td>
									<select name="privacy_renewal" id="privacy_renewal">
										<option value="select"><?=$lng['Select']?></option>
										<? foreach($renewalOptions as $k=>$v){ ?>
											<option <?php if($data['privacy_renewal'] == $k){echo "selected";}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Cookie Consent Renewal']?></th>
								<td>
									<select name="cookie_renewal" id="cookie_renewal" >
										<option value="select"><?=$lng['Select']?></option>
										<? foreach($renewalOptions as $k=>$v){ ?>
											<option <?php if($data['cookie_renewal'] == $k){echo "selected";}?> value="<?=$k?>"><?=$v?></option>
										<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th><?=$lng['Consent Process']?></th>
								<td>
	
									<input style="width: 15px;height: 15px;margin-left: 11px;margin-top: 3px;" <?php if($data['consent_process'] == '1'){ echo 'checked="checked"';}?> name="consent_process" id="consent_process" type="checkbox" value="1" >
								</td>
							</tr>							

							<tr>
								<th><?=$lng['Show Confirmation Text']?></th>
								<td>
	
									<input style="width: 15px;height: 15px;margin-left: 11px;margin-top: 3px;" <?php if($data['show_confirmation_text'] == '1'){ echo 'checked="checked"';}?> name="show_confirmation_text" id="show_confirmation_text" type="checkbox" value="1" >
								</td>
							</tr>							
							<tr>
								<th>Email recipients in case of non-consent</th>
								<td>
	
									<!-- <input style="width: 15px;height: 15px;margin-left: 11px;margin-top: 3px;" <?php if($data['show_confirmation_text'] == '1'){ echo 'checked="checked"';}?> name="show_confirmation_text" id="show_confirmation_text" type="checkbox" value="1" > -->
									<button style="margin-left: 1%;" data-toggle="modal" data-target="#addEmailModal" class="btn btn-sm  btn-primary " type="button"><i class="fa fa-edit"></i>&nbsp;Add</button>

									<button style="margin-left: 1%;" data-toggle="modal" data-target="#ShowEmailModal" class="btn btn-sm  btn-primary " type="button"><i class="fa fa-eye"></i>&nbsp;Show</button>

								</td>
							</tr>

						</tbody>
					</table>
					<div style="padding:0 0 0 20px" id="dump2"></div>
					<div style="height:15px"></div>
				
				</div>	
			</div>
				
		</form>
	
	</div>


	<!-- Modal Change Password -->
	<div class="modal fade" id="addEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; Email recipients in case of non-consent</h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<form id="changeUserPassword" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Enter Email']?> <i class="man"></i></label>
						 <input name="emailnon" id="emailnon" type="text" />
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Add Email']?></button>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>	

	<div class="modal fade" id="ShowEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; Email recipients in case of non-consent</h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">

			<table id="datatable" class="dataTable hoverable selectable nowrap">
				<thead>
				<tr>
					<th><?=$lng['S.No']?></th>
					<th><?=$lng['Email']?></th>
					<th><i class="fa fa-trash"></i></th>
				</tr>
				</thead>
				<tbody>
					<?php 

						$counter   =  1; 
						foreach ($nonemailsss as $key => $value) {
							# code...
							$counterss =  $counter++ ;  

							?>

							<tr>
								<td> <?php echo $counterss; ?> </td>
									<td><?php  echo  $value; ?></td>
									<td><a onclick="updatenonemail('<?php echo $key;?>');" id="<?php echo $key;?>"> <i class="fa fa-trash"></i></a></td>


							</tr>


						<?php }
					 ?>
				
				</tbody>

			</table>
						<button style="float:right;margin-top:15px" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
					<div class="clear"></div>
			  </div>
		 </div>
	</div>
	
	<script>
		
		$(document).ready(function() {




			$("#changeUserPassword").submit(function(e) {
			e.preventDefault();
			var nonemail  = $('#emailnon').val();
			if(nonemail == '')
			{
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Plese enter an email',
					duration: 4,
					closeConfirm: true
				})
				return false;
			}
			var formData = $(this).serialize();
			$.ajax({
					url: AROOT+"ajax/consent/add_non_consent_email.php",
				data: formData,
				success: function(response){

						if($.trim(response) == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})

							setTimeout(function(){
								$("#subButton i").removeClass('fa-refresh fa-spin').addClass('fa-save');
								$("#subButton").removeClass('flash');
								$("#sAlert").fadeOut(200);
							     $('#emailnon').val('');
								$('#addEmailModal').modal('hide');
								location.reload();
							},500);

						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
								duration: 4,
								closeConfirm: true
							})
						}


				},

			});
		});


			
			$("#companyForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');


				if($('select[name="terms_renewal"]').val() == 'select' || 
					$('select[name="privacy_renewal"]').val() == 'select' || 
					$('select[name="cookie_renewal"]').val() == 'select' ){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please select the field with correct value']?>',
						duration: 4,
					})
					return false;
				}
				//return false;
				$.ajax({
					url: AROOT+"ajax/update_consent_settings.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								closeConfirm: true
							})
						}
						setTimeout(function(){
							$("#subButton i").removeClass('fa-refresh fa-spin').addClass('fa-save');
							$("#subButton").removeClass('flash');
							$("#sAlert").fadeOut(200);
						},500);
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
			
			setTimeout(function(){
				$(document).on('change', 'input, textarea, select', function (e) {
					$("#subButton").addClass('flash');
					$("#sAlert").fadeIn(200);
				});	
			},1000);
			
			
		});
	


				function updatenonemail(valuee)
			{

				$.ajax({
					url: AROOT+"ajax/consent/delete_non_consent_email.php",
				data: {valuee:valuee},
				success: function(response){

						if($.trim(response) == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
								duration: 2,
							})

							setTimeout(function(){
								$("#subButton i").removeClass('fa-refresh fa-spin').addClass('fa-save');
								$("#subButton").removeClass('flash');
								$("#sAlert").fadeOut(200);
							     $('#emailnon').val('');
								$('#addEmailModal').modal('hide');
								location.reload();
							},500);

						}else{
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
								duration: 4,
								closeConfirm: true
							})
						}


				},

			});
				
			}


	</script>


















