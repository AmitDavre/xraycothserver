	
	<script type="text/javascript">
		
	$(document).ready(function() {
		
		$(document).on('click', ".dataTable.selectable tbody tr", function(){
			$(".dataTable tbody tr").removeClass('selected');
			$(this).addClass('selected');
		});

		/*setTimeout(function(){
			$('#modalExpired').modal('toggle');
		},logtime);*/	// one hour

		$('#relogForm').on("submit", function(e) {
			e.preventDefault();
			var data = $(this).serialize();
			$.ajax({
				url: ROOT+"ajax/relogin.php",
				data: data,
				success: function(result){
					//$('#dump').html(result);
					if(result == 'success'){
						window.location.reload();
					}else if(result == 'wrong'){
						$('#relogMsg').html('Wrong password');
					}else if(result == 'empty'){
						$('#relogMsg').html('Please provide password');
					}
				}
			});
		})
		
		$("#contactForm").submit(function (e) {
			e.preventDefault();
			$('#eSendContact').prop('disabled', true);
			$("#eSendContact i").removeClass('fa-paper-plane-o').addClass('fa-refresh fa-spin');
			var formData = new FormData($(this)[0]);
			setTimeout(function(){
				$.ajax({
					url: ROOT+"ajax/contact_us_form.php",
					type: 'POST',
					data: formData,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(response){
						//$("#dump").html(response);
						setTimeout(function(){$("#eSendContact i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane-o');}, 500);
						if(response == 'empty'){
							$("#conMess").html('<?=$lng['Please fill in required fields']?>');
							$('#eSendContact').prop('disabled', false);
							return false;
						}
						if(response == 'success'){
							$("#conMess").html('<?=$lng['Thank you for interest respond soon as possible']?>');
							setTimeout(function(){
								$('#modalContactForm').modal('toggle');
								$('#contactForm').trigger("reset");
								$('#contactAttach').val('');
								$("#conMess").html('');
							}, 2000);
						}else{
							$("#conMess").html('<?=$lng['Sorry but something went wrong please try again']?>');
							$('#eSendContact').prop('disabled', false);
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						//$("#conMess").html(thrownError);
						alert(thrownError)
					}
				});
			}, 50);
		});
		$('#modalContactForm').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#conMess").html('');
		});

		$("#changeUserPassword").submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				url: "ajax/change_user_password.php",
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
		
		$(".changeCustomer").on('click', function(){ 
			var cid = $(this).data('cid');
			$.ajax({
				url: ROOT+"ajax/change_customer.php",
				data:{cid: cid},
				success: function(result){
					//$('#dump').html(result)
					// location.reload();
					window.location.href = ROOT+'index.php';
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		});
		
		/*$(".logout").click(function(){ 
			$.ajax({
				url:"../ajax/logout.php",
				success: function(result){
					$('#dump').html(result)
					//window.location.href = ROOT+'/index.php';
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		})*/
		
		$('.closemyHelp').on('click', function (e) {
			$('#myhelp').animate({right: '-480px'}, 200);
		});
 		$('.closeHelp').on('click', function (e) {
			$('#help').animate({right: '-480px'}, 200);
		});
		$('.openHelp').on('click', function (e) {
			$('#help').animate({right: '0px'}, 200);
		});
		<? if(isset($_GET['H'])){ ?>
			$('.openHelp').trigger('click');
		<? } ?>

		$(window).on('resize', function(){
			var innerheight = window.innerHeight;
			$('.widget.autoheight').css('min-height', innerheight-150)
		});	
		
	});

	</script>
