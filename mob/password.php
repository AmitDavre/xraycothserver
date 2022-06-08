
		<div class="container-fluid" style="xborder:1px solid red">
			<div class="row" style="xborder:1px solid green; padding:20px 25px">
				<div class="col-12">
					<div class="page-header">
						<h4 class=""><?=$lng['My account']?></h4>
					</div>
					<div class="divider-icon">
						<div><i class="fa fa-lock fa-lg"></i></div>
					</div>
				
					<div class="content">




						<form class="contactFoasdrm" id="changePaasdssForm">
							<fieldset>
								<div class="form-group">
									<label for="opass">Username</label>
									<input readonly type="text" name="opassaaaaa" class="form-control" value ="<?php echo $_SESSION['rego']['username'];?>"/>
								</div>
								<div class="form-group">
									<label for="opass">Password</label>
									<input readonly type="password" name="opassaasdaaaa" class="form-control" value ="********"/>
								</div>
								<div class="contactFormButton">
									<button id="passBtnnnn" style="font-size:16px" type="button" class="btn btn-default btn-block"><?=$lng['Change password']?></button>
									<button id="cookieBtnnnn" style="font-size:16px;margin-top: 38px;" type="button" class="btn btn-default btn-block"><?=$lng['Cookie Settings']?></button>

									<label for="opass" style="font-weight: 600;">Two-Factor Authentication (2FA can only work if your username is an email address)</label>
									<input style="width: 15px;height: 15px;margin-left: 11px;margin-top: 34px;" name="two_factor_authentication" <?php if($two_factor_authenticationnnn == '1'){ echo 'checked="checked"';}?>  id="two_factor_authentication" type="checkbox" value="1">

				<button class="btn btn-primary" style="position:absolute; bottom:6px; right:1px;display: none;" id="subButton" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>


								</div>
								<div id="dump"></div>
							</fieldset>
						</form>       
						<div class="clear"></div>
					</div>
                
				</div>
			</div>
		</div>







<script type="text/javascript">
	
	$(document).ready(function() {



			$("#two_factor_authentication").on('click', function(e){  

				$('#subButton').click();
			});



		$('#passBtnnnn').on('click', function(){

			$('#modalConsent1').modal('show');

		});		

		$('#cookieBtnnnn').on('click', function(){

			$('#modalConsent3').modal('show');

		});


		$("#changePassForm").submit(function(e){
			e.preventDefault();
			$("#passBtn").prop('disabled', true);
			var formData = $(this).serialize();
			//alert(formData)
			$.ajax({
				url: "ajax/change_password.php",
				data: formData,
				success: function(response){
					//$('#dump').html(response)
					if(response=='success'){
						$('#passMsg').html('<?=$lng['Password changed successfuly']?>').fadeIn(200);
					}else if(response=='empty'){
						$('#passMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(200);
						$("#passBtn").prop('disabled', false);
					}else if(response=='short'){
						$('#passMsg').html('<?=$lng['New password to short min 8 characters']?>').fadeIn(200);
						$("#passBtn").prop('disabled', false);
					}else if(response=='same'){
						$('#passMsg').html('<?=$lng['New passwords are not the same']?>').fadeIn(200);
						$("#passBtn").prop('disabled', false);
					}else if(response=='old'){
						$('#passMsg').html('<?=$lng['Old Password is wrong']?>').fadeIn(200);
						$("#passBtn").prop('disabled', false);
					}else{
						$('#passMsg').html('<?=$lng['Error']?> : '+response).fadeIn(200);
						$("#passBtn").prop('disabled', false);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#passMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(200);
					$("#passBtn").prop('disabled', false);
				}
			});
		});


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

					setTimeout(function(){
						// location.href = ROOT+'index.php?mn=461';
						$('#modalConsent3').modal('hide');
					},1000);
				}
				
			},

		});


	});




		$("#changePaasdssForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				//return false;
				$.ajax({
					url: "../settings/ajax/update_two_factor.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						if(result == 'success'){
							
						}else{
							
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





	})
	
</script>






