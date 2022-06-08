	
			
			
		<div class="container-fluid" style="xborder:1px solid red">
			<div class="row" style="xborder:1px solid green; padding:20px 25px">
				<div class="col-12">
					<div class="page-header">
						<h4 class=""><?=$lng['Contact']?></h4>
					</div>
					<div class="divider-icon">
						<div><i class="fa fa-comments-o fa-lg"></i></div>
					</div>
					
							<form class="contactForm" id="contactForm">
								<fieldset>
									<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
									<input name="email" type="hidden" value="<?=$_SESSION['rego']['username']?>" />
									<input style="visibility:hidden; height:0; position:absolute" id="contactAttach" type="file" name="contactAttach" />
									
									<div class="form-group">
										<label for="name"><?=$lng['Name']?></label>
										<input class="form-control" readonly name="name" type="text" value="<?=$_SESSION['rego']['name']?>" />
									</div>
									<div class="form-group">
										<label for="subject"><?=$lng['Subject']?></label>
										<input class="form-control" type="text" name="subject" />
									</div>
									<div class="form-group">
										<label for="comment"><?=$lng['Message Question']?></label>
										<textarea rows="4" class="form-control" name="comment"></textarea>
									</div>
									
									<div id="contactMsg" style="color:#b00; font-size:15px; text-align:center; margin-top:-15px; padding-bottom:5px; display:none"></div>

									<div class="contactFormButton">
										<button style="margin-bottom:10px" onClick="$('#contactAttach').click()" type="button" class="btn btn-info btn-block"><?=$lng['Attachement']?> : <span id="attachMsg">&nbsp;<?=$lng['No file selected']?></span></button>
									</div>
								
									<div class="contactFormButton">
										<button id="contactBtn" style="font-size:16px" type="submit" class="btn btn-default btn-block"><i class="fa fa-paper-plane"></i><?=$lng['Send message']?></button>
									</div>
									
								</fieldset>
							</form>       
						
				</div>
			</div>
		</div>	

<script type="text/javascript">
	
	$(document).ready(function() {
		
		function readAttURL(input) {
		  if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					var fileExtension = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
					var ext = input.files[0].name.split('.').pop();
					if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
						alert('Use only '+fileExtension+' files')
						$('#attachMsg').html('<?=$lng['No file selected']?>');
					}else{				
						$('#attachMsg').html(input.files[0].name);
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		};

		$('#contactAttach').on('change', function(){ 
			readAttURL(this);
		});
		$("#contactForm").submit(function(e){
			e.preventDefault();
			$("#contactBtn").prop('disabled', true);
			//$("#contactBtn i").removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			var formData = new FormData($(this)[0]);
			$.ajax({
				url: "ajax/send_contact_mail.php",
				data: formData,
				type: "POST", 
				cache: false,
				processData:false,
				contentType: false,
				success: function(response){
					//$('#dump3').html(response)
					if(response=='success'){
						$('#contactMsg').html('<?=$lng['Mail send successfully']?>').fadeIn(400);
					}else if(response=='empty'){
						$('#contactMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}else{
						$('#contactMsg').html('<?=$lng['Error']?> : ' + response).fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}
					//$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#contactMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				}
			});
		});
		
	})
	
</script>







