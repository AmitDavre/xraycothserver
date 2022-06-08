<?php
	
	if(session_id()==''){session_start();}
	include('db_connect.php');
	include(DIR.'files/arrays_'.$lang.'.php');
	
	$logger = false;
	if(isset($_SESSION['agent']['agent_id'])){
		$logtime = 3600;
		if(time() - $_SESSION['agent']['timestamp'] > $logtime) {
			$_SESSION['agent']['timestamp'] = 0;
			$logger = false; 
		}else{
			$_SESSION['agent']['timestamp'] = time();
			$logger = true;
		}
		$res = $dbx->query("SELECT * FROM rego_agents WHERE agent_id = '".$_SESSION['agent']['agent_id']."'");
		$data = $res->fetch_assoc();
	}
	if(empty($_SESSION['agent']['img'])){$_SESSION['agent']['img'] = '../images/profile_image.jpg';}
	
	if(!isset($_GET['mn']) && $logger == true){$_GET['mn'] = 2;}
	//if(!isset($_GET['mn'])){$_GET['mn'] = 1;}
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<title><?=$www_title?></title>
	<link rel="icon" type="image/png" sizes="192x192" href="../assets/images/192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="assets/css/style.css?<?=time()?>" rel="stylesheet">
	<link href="assets/css/mobStyle.css?<?=time()?>" rel="stylesheet">
	<link href="assets/css/bootstrap-datepicker.css?<?=time()?>" rel="stylesheet">
	<!--<link href="../timepicker/dist/jquery-clockpicker.min.css" rel="stylesheet" >-->
	
	<script>
		var headerCount = 2;
		var lang = <?=json_encode($lang)?>;
		var dtable_lang = <?=json_encode($dtable_lang)?>;
		var ROOT = <?=json_encode(ROOT)?>;
		var logtime = 3600;
	</script>
	
</head>
	<? if($_GET['mn'] == 2){ ?>
	<body style="background:#399">
	<? }else{ ?>
	<body style="background:#fff">
	<? }?>	
	<?
		if($logger){
			switch($_GET['mn']){
				case 1: 
					//header('location: agent_login.php'); break;
				case 2: 
					include('agent_dashboard.php'); break;
				case 3: 
					include('agent_personal_data.php'); break;
				case 4: 
					include('agent_free_trial.php'); break;
				case 5: 
					include('agent_price_table.php'); break;
				case 7: 
					include('agent_account.php'); break;
				case 8: 
					include('agent_password.php'); break;
				case 9: 
					include('agent_contact.php'); break;
			}
		}else{
			header('location: agent_login.php');
		}
	?>
	<div id="dump"></div>
	
	<? //include('../include/modal_relog.php')?>

	<script type="text/javascript" src="assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/popper.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/custom.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-datepicker.th.min.js"></script>
	<!--<script src="../timepicker/dist/jquery-clockpicker.min.js"></script>-->
	<!--<script src="js/moment.min.js"></script>
	<script src='js/moment-duration-format.min.js'></script>-->

	

<script type="text/javascript">
	
	$(document).ready(function() {
		
		function validateEmail($email) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			return emailReg.test( $email );
		}
		function checkPassword(password) {
			var number = /([0-9])/;
			var lowers = /([a-z])/;
			var uppers = /([A-Z])/;
			var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
			var msg = '';
			if(password.length < 8) {
				msg += <?=json_encode($lng['Password should be at least 8 characters'])?>+"\n";
			}
			if(/([0-9])/.test(password) == false) {
				msg += <?=json_encode($lng['Password should contain at least 1 number'])?>+"\n";
			}
			if(/([A-Z])/.test(password) == false) {
				msg += <?=json_encode($lng['Password should contain at least 1 UPPERCASE character'])?>+"\n"; 
			}
			if(/([a-z])/.test(password) == false) {
				msg += <?=json_encode($lng['Password should contain at least 1 lowercase character'])?>+"\n"; 
			}
			/*if(/([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/.test(password) == false) {
				msg += <? //=json_encode($lng['Password should contain at least 1 special character'])?>+"\n( ~ ! @ # $ % ^ & * - _ + = ? > < )"; 
			}*/
			return msg;
		}	

		$(".logout").click(function(){ 
			$.ajax({
				url:"ajax/logout.php",
				success: function(result){
					//$('#dump').html(result)
				}
			});
		})
		
		$(document).on("click", ".account_details", function(e){
			var id = $(this).data('id');
			//alert(id);
			$.ajax({
				url: "ajax/get_account_details.php",
				data: {id: id},
				success:function(result){
					$("#detailsTable").html(result);
					$(".basicTable").addClass('table table-bordered table-sm table-striped');
					$("#detailsModal").modal('toggle');
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('<?=$lng['Error']?> ' + thrownError);
				}
			});
		});
		
		$("#trialForm").submit(function (e) {
			e.preventDefault();
			if($('select[name="version"]').val() == 'xxx' || $('input[name="firstname"]').val() == '' || $('input[name="lastname"]').val() == '' || $('input[name="company"]').val() == '' || $('input[name="phone"]').val() == '' || $('input[name="email"]').val() == '') {
				alert(<?=json_encode($lng['Please fill in required fields'])?>);
				return false;
			}
			if(!validateEmail($('input[name="email"]').val())) {
				alert(<?=json_encode($lng['Please enter a valid email address'])?>);
				return false;
			}
			
			var pass1 = $('input[name="pass1"]').val();
			var pass2 = $('input[name="pass2"]').val();
			if(checkPassword(pass1)){
				alert(checkPassword(pass1));
				return false;
			}
			if(pass1 != pass2){
				alert(<?=json_encode($lng['Passwords are not the same'])?>);
				return false;
			}

			$('#trialBtn').prop('disabled', true);
			$("#trialBtn i").removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
			var formData = $(this).serialize();
			
			//var url = 'http://census/admin/ajax/create_new_customer.php';
			var url = 'https://supreme.xraydemo.com/admin/ajax/create_new_customer.php';
			var msg = 'Customer created successfully<br>Secure login on laptop or PC<br>https://supreme.xraydemo.com';
			if($('select[name="version"]').val() == 'mob'){
				//url = 'http://regomobile/admin/ajax/ajax_register.php';
				url = 'https://xray.co.th/admin/ajax/ajax_register.php';
				msg = 'Customer created successfully<br>Secure login on https://xray.co.th';
			}
			$.ajax({
				type: "POST",
				crossDomain: true,
				url: url,
				data: formData,
				success: function(response){
					$("#trialBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
					//$('#dump3').html(response);
					//return false;
					if($.trim(response) == 'success'){
						$('#trialMsg').html(msg);
						$('#successModal').modal('toggle');
					}else{
						alert('Sorry but something went wrong.');
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('Error : '+thrownError);
				}
			});
		});
		
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
		
		$('#certificate').on('change', function(){ 
			readAttURL(this);
		});
		
		$(document).on('click', '.delete', function(e){
			//alert($(this).data('id'))
			$('#deleteRequest').data('id',$(this).data('id'));
			$('#deleteModal').modal('toggle');
		})
		$('#startPicker').datepicker({
			autoclose: true,
			format: 'D dd-mm-yyyy',
			language: '<?=$lang?>',
			startDate: new Date(),
		}).on('changeDate', function(e){
			startDate = e.format();
			$('#startModal').modal('toggle');
			$('#leavestart').html(e.format());
			$('#enddate').val(e.format());
			$('#endPicker').datepicker('setStartDate', e.format());
			$('#endPicker').datepicker('setDate', e.format());
			//alert(e.format())
		
		});	
		$('#endPicker').datepicker({
			autoclose: true,
			format: 'D dd-mm-yyyy',
			language: '<?=$lang?>',
		}).on('changeDate', function(e){
			endDate = e.format();
			$('#leaveend').html(e.format());
			$('#endModal').modal('toggle');
			$('#enddate').val(e.format());
			$.ajax({
				url: "ajax/get_leave_range.php",
				data: {startDate: startDate, endDate: endDate},
				success: function(result){
					//alert(result);
					$('#rangeTable').html(result); return false;
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
		
		});
		
		$('#contactAttach').on('change', function(){ 
			readAttURL(this);
		});
		$("#contactForm").submit(function(e){
			e.preventDefault();
			$("#contactBtn").prop('disabled', true);
			$("#contactBtn i").removeClass('fa-paper-plane').addClass('fa-refresh fa-spin');
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
						$('#contactMsg').html('Mail send successfully<? //=$lng['Mail send successfully']?>').fadeIn(400);
					}else if(response=='empty'){
						$('#contactMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}else{
						$('#contactMsg').html('<?=$lng['Error']?> : ' + response).fadeIn(400);
						$("#contactBtn").prop('disabled', false);
					}
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#contactMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
					$("#contactBtn i").removeClass('fa-refresh fa-spin').addClass('fa-paper-plane');
				}
			});
		});

		$("#changePassForm").submit(function(e){
			e.preventDefault();
			var formData = $(this).serialize();
			//alert(formData)
			$.ajax({
				url: "ajax/change_agent_password.php",
				data: formData,
				success: function(response){
					$('#dump3').html(response)
					if(response=='success'){
						$('#passMsg').html('<?=$lng['Password changed successfuly']?>').fadeIn(400);
					}else if(response=='empty'){
						$('#passMsg').html('<?=$lng['Please fill in required fields']?>').fadeIn(400);
					}else if(response=='short'){
						$('#passMsg').html('<?=$lng['New password to short min 8 characters']?>').fadeIn(400);
					}else if(response=='same'){
						$('#passMsg').html('<?=$lng['New passwords are not the same']?>').fadeIn(400);
					}else if(response=='old'){
						$('#passMsg').html('<?=$lng['Old Password is wrong']?>').fadeIn(400);
					}else{
						$('#passMsg').html('<?=$lng['Error']?> : '+response).fadeIn(400);
					}
				},
				error:function (xhr, ajaxOptions, thrownError){
					$('#passMsg').html('<?=$lng['Error']?> : ' + thrownError).fadeIn(400);
				}
			});
		});

	})
	
</script>	

</body>

</html>















