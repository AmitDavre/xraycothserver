<?

	// echo '<pre>';
	// print_r($_SESSION);
	// echo '</pre>';

	// die();

	$err_msg = "";
	$data = array();
	$sql1 = "SELECT * FROM rego_default_settings"; 
	if($res1 = $dbx->query($sql1)){
		$data = $res1->fetch_assoc();
	}else{
		$err_msg = '<div class="box_err ibox">'.$lng['Error'].' : '.mysqli_error($dbx).' <a class="box_close"><i class="fa fa-times fa-lg"></i></a></div>';
	}



	
	$sql = "SELECT * FROM rego_all_users WHERE username = '".$_SESSION['rego']['username']."'";
	if($res = $dbx->query($sql)){
		$userData = $res->fetch_assoc();

		$phpsessid = $userData['phpsessid'];
		$lang =  $userData['lang'];
		$rego_lang =  $userData['rego_lang'];
		$scanlang =  $userData['scanlang'];
		$two_factor_authentication =  $userData['two_factor_authentication'];
	}else{
		//var_dump(mysqli_error($dbc));
	}

?>
<style>
	input:read-only {
		color:#aaa;
	}
</style>

	
	<h2><i class="fa fa-industry"></i>&nbsp; <?=$lng['Consent Settings']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>	
	<div class="main">
		

		<form id="companyForm" style="height:100%">
			<ul style="position:relative" class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link active" data-target="#tab_company" data-toggle="tab"><?=$lng['My account']?></a></li>
				<button class="btn btn-primary" style="position:absolute; bottom:6px; right:1px;" id="subButton" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<? //} ?>
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				
				<div style="display:none" id="message"></div>
				
				<div class="tab-pane show active" id="tab_company">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th style="width:1%"><?=$lng['Username']?></th>
								<td>
									<input readonly="readonly" style = "width: 25%;color: #000;" type="text" name="show_username" id="show_username" value="<?php echo $_SESSION['rego']['username']; ?>">
								</td>
							</tr>							
							<tr>
								<th style="width:1%"><?=$lng['Password']?></th>
								<td>
									<input readonly="readonly" style = "width: 25%;color: #000;" type="text" name="show_password" id="show_password" value="********">
								</td>
							</tr>							
							<tr>
								<th style="width:1%"><?=$lng['Change password']?></th>
								<td>
									<button style="margin-left: 1%;" data-toggle="modal" data-target="#passModal" class="btn btn-sm btn-primary " type="button" ><i class="fa fa-edit"></i>&nbsp;Edit</button>

								</td>
							</tr>							
							<tr>
								<th style="width:1%"><?=$lng['Cookie Settings']?></th>
								<td>
									<button style="margin-left: 1%;" class="btn-sm  btn btn-primary " data-toggle="modal" data-target="#modalConsent" type="button" ><i class="fa fa-edit"></i>&nbsp;Edit</button>
								</td>
							</tr>
							<tr>
								<th style="width:1%"><?=$lng['Two-Factor Authentication (2FA)']?></th>
								<td>
									<input style="width: 15px;height: 15px;margin-left: 11px;margin-top: 3px;" <?php if($two_factor_authentication == '1'){ echo 'checked="checked"';}?> name="two_factor_authentication" id="two_factor_authentication" type="checkbox" value="1" >
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

	
	<div class="modal fade" id="modalConsent" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="width: 550px;">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Change Cookie Consent Access']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" id="requestForm" class="sform">

						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox" <?php if($phpsessid == '1'){echo 'checked="checked"';}?>  name="cookie_phpsessid" id= "cookie_phpsessid" value="1"><span style="margin-left: 22px;font-weight: 600;"> Auto generated cookies for the functioning of our website: <i class="man"></i></span><p style="font-weight: 100;margin-left: 38px" > Cookies like (but not limited to) : PHPSESSID, rego_lang, scanlang  are auto generated cookies during your session and are required for the functioning of our website.</p></label>
						</div>
						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox" <?php if($lang == '1'){echo 'checked="checked"';}?> name="cookie_lang"  id="cookie_lang" value="1" ><span style="margin-left: 22px;font-weight: 600;"> Cookie Remember username : </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the username at the sign-in process.</p></label>
						</div>
						<div style="padding: 5px 2px;">
							<label><input style="height: 14px;width: 14px;" type="checkbox" <?php if($rego_lang == '1'){echo 'checked="checked"';}?> name="cookie_rego_lang" id="cookie_rego_lang" value="1" ><span style="margin-left: 22px;font-weight: 600;"> Cookie remember password : </span><p style="font-weight: 100;margin-left: 38px" > This is not a required Cookie for our website. It tells the browser to save a cookie so that you will not have to type the password at the sign-in process.</p></label>
						</div>						
		
						<div style="height:15px"></div>
						<button id="modalConsentBtn" type="button" class="btn btn-primary btn-fl"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Submit']?></button>
						<button id="modalCancelConsentBtn" type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</div>
				</form>
			  </div>
		 </div>
	</div>


		<!-- Modal Change Password -->
	<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="max-widt:450px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Change password']?></h4>
					</div>
					<div class="modal-body" style="padding:10px 25px 25px 25px">
					<span style="font-weight:600; color:#cc0000;" id="pass_msg"></span>
					<form id="changeUserPassword" class="sform" style="padding-top:10px;">
						 <label><?=$lng['Old password']?> <i class="man"></i></label>
						 <input name="opass" id="opass" type="password" />
						 <label><?=$lng['New password']?> <i class="man"></i></label>
						 <input name="npass" id="npass" type="password" />
						 <label><?=$lng['Repeat new password']?> <i class="man"></i></label>
						 <input name="rpass" id="rpass" type="password" />
						 <button class="btn btn-primary" style="margin-top:15px" type="submit"><i class="fa fa-save"></i> <?=$lng['Change password']?></button>
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

	// $('#modalConsent').modal('show');

		$("#changeUserPassword").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "../ajax/change_user_password.php",
				data: formData,
				success: function(response){
					//$("#dump").html(response); return false;
					if(response == 'success'){
						$("#pass_msg").html('<div class="msg_alert nomargin"><?=$lng['Password changed successfuly']?></div>');
						setTimeout(function(){
							$('#passModal').modal('toggle');
						}, 2000);
					}else if(response=='empty'){
						$("#pass_msg").html('<div class="msg_alert nomargin"><?=$lng['Please fill in required fields']?></div>');
					}else if(response=='old'){
						$("#pass_msg").html('<div class="msg_alert nomargin"><?=$lng['Old Password is wrong']?></div>');
					}else if(response=='short'){
						$("#pass_msg").html('<div class="msg_alert nomargin"><?=$lng['New password to short min 8 characters']?></div>');
					}else if(response=='same'){
						$("#pass_msg").html('<div class="msg_alert nomargin"><?=$lng['New passwords are not the same']?></div>');
					}else{
						$("#pass_msg").html(response);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("#pass_msg").html(thrownError);
				}
			});
		});
		$('#passModal').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#pass_msg").html('');
		});


		// $("#modalCancelConsentBtn").on('click', function(e){ 

		// 	location.href = ROOT+'index.php?mn=461';
		// });

		$("#modalConsentBtn").on('click', function(e){  



			if($('#cookie_phpsessid').is(":checked")){
				var cookie_phpsessid = '1';
			}else{
				var cookie_phpsessid = '0';
			}			

			if($('#cookie_lang').is(":checked")){
				var cookie_lang = '1';
			}else{
				var cookie_lang = '0';
			}

			if($('#cookie_rego_lang').is(":checked")){
				var cookie_rego_lang = '1';
			}else{
				var cookie_rego_lang = '0';
			}

			if($('#cookie_scanlang').is(":checked")){
				var cookie_scanlang = '1';
			}else{
				var cookie_scanlang = '0';
			}




		$.ajax({
			url: "../settings/ajax/update_consent_choice.php",
			data: { cookie_phpsessid: cookie_phpsessid,cookie_lang:cookie_lang,cookie_rego_lang:cookie_rego_lang,cookie_scanlang:cookie_scanlang },
			success: function(result){
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Cookie consent updated successfuly',
						duration: 2,
					})
					setTimeout(function(){
						// location.href = ROOT+'index.php?mn=461';
						$('#modalConsent').modal('hide');
					},1000);
				}else{
					$("body").overhang({
						type: "error",
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


		$("#companyForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				//return false;
				$.ajax({
					url: ROOT+"settings/ajax/update_two_factor.php",
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

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
