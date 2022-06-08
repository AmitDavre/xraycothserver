<?php 
include("../dbconnect/db_connect.php");

$employeeId = base64_decode(base64_decode($_GET['emp_id']));
$cId 		= base64_decode(base64_decode($_GET['cid']));
// check if ping is expire or not if expire redirect to expire page 




$my_dbcname = $prefix.$cId;
$dbe = new mysqli($my_database,$my_username,$my_password,$my_dbcname);
if($dbe->connect_error) {
	echo '<p style="width:900px; margin:0 auto; margin-top:20px;" class="box_err">Error 2 : ('.$dbe->connect_errno.') '.$dbe->connect_error.'<br>Please try again later or report this error to the Admin</p>';
}else{
	mysqli_set_charset($dbe,"utf8");
}

$sql = "SELECT ping_expire FROM ".$cId."_employees  WHERE emp_id = '".$employeeId."'";

if($result = $dbe->query($sql)){
	if($row_value = $result->fetch_assoc()){

		if($row_value['ping_expire'] == '1')
		{
			// redirect to expire page 
			$urlVariable = ROOT.'wl/link_expire.php';
			header("Location: ".$urlVariable."");
			exit();
		}

	}
}





$lattitude_txt = $lng['Latitude'];
$longtitude_txt = $lng['Longitude'];
$thankyoutxt = $lng['We have saved your GPS coordinates in the employee register. Have a nice working day !!  If something went wrong, you need to contact your superior to restart the process.'];






?> 


<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, maximum-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<title><?=$www_title?></title>
	
		<link rel="icon" type="image/png" sizes="192x192" href="https://regodemo.com/assets/images/192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="https://regodemo.com/assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="https://regodemo.com/assets/images/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="https://regodemo.com/assets/images/favicon-16x16.png">
		
		<link rel="stylesheet" href="https://regodemo.com/assets/css/bootstrap.min.css">
    	<link rel="stylesheet" href="https://xray.co.th/hr/assets/css/font-awesome.min.css">
    	<link rel="stylesheet" href="https://regodemo.com/assets/css/login_RegoDemo.css?<?=time()?>">
		<link rel="stylesheet" href="https://regodemo.com/assets/css/myBootstrap.css?<?=time()?>">

		<script src="https://regodemo.com/assets/js/jquery-3.2.1.min.js"></script>
		<script src="https://regodemo.com/assets/js/popper.min.js"></script>
		<script src="https://xray.co.th/hr/assets/js/bootstrap.min.js"></script>


	</head>
	
	<body>
		

		<div class="header">
			<table width="100%" border="0"><tr>
				<td>
					<img style="margin:0 0 3px 15px; height:40px;" src="<?=$default_logo.'?'.time()?>">
				</td>
				<td style="width:95%"></td>
				<td style="padding-right:20px">
				<? if($lang=='en'){ ?>
					<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
				<? }else{ ?>
					<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
				</td>
				<? } ?>
			</tr></table>
		</div>
		
		<div style="padding-top:10vh; xborder:1px solid red;">
			<div class="brand">
				<img src="../images/pkf_people.png">
				<p></p>
			</div>

		</div>
		

		
		<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

		 	<div class="modal-dialog" style="max-width:450px">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i>&nbsp;<?=$lng['Message']?></h4>

						<? if($lang=='en'){ ?>
							<a style="margin:0px 0 0 0" data-lng="th" class="langbutton <? if($lang=='th'){echo 'activ';} ?>"><img src="../images/flag_th.png"></a>
						<? }else{ ?>
							<a style="margin:0px 0 0 0" data-lng="en" class="langbutton <? if($lang=='en'){echo 'activ';} ?>"><img src="../images/flag_en.png"></a>
						</td>
						<? } ?>


					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p>
							<?=$lng['Hi, We need 5 minutes of your time to define the coordinates of your location.Please move physically to the exact spot you want to set and press the Ping button.']?>
							</p>
							<table>
								<tr>
									<td>
										<code><p style="font-weight: 600" id = "location1"></p></code>
									</td>
									<td>
										<code><p style="margin-left:15px;font-weight: 600" id = "location2"></p></code> 
									</td>
								</tr>
							</table>
						</div>
						<div style="height:10px"></div>

							<input type="hidden" name="hidden_lat" id="hidden_lat">
							<input type="hidden" name="hidden_long" id="hidden_long">


							<button class="btn btn-primary" type="button" onclick="pingLocation_check();">&nbsp;  <?=$lng['Ping Location']?><i class="fa fa-map-marker"></i></button>
							<button style="float: right;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="refreshPage();"><i class="fa fa-refresh"></i>&nbsp;<?=$lng['Reload']?></button>
					</div>
			 	</div>
			</div>
		</div>		

		<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

		 	<div class="modal-dialog" style="max-width:450px">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i>&nbsp;<?=$lng['Message']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p id="successMessage" style="font-weight: 600;font-size: 15px;"></p>

						
							    <span class="wrap"></span>
							  </a>
						</div>
						<div style="height:10px"></div>

						
					</div>
			 	</div>
			</div>
		</div>		


		<div class="modal fade" id="finalAccept" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

		 	<div class="modal-dialog" style="max-width:450px">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-bell"></i>&nbsp;<?=$lng['Message']?></h4>
					</div>
					<div class="modal-body" style="padding:20px 25px 25px">
						<div style="max-height:462px; overflow-y:auto">
							<p><?=$lng['Are you sure']?></p>
						</div>
						<div style="height:10px"></div>



							<button class="btn btn-primary" type="button" onclick="pingLocation();">&nbsp;<?=$lng['Yes']?> <i class="fa fa-check"></i></button>
							<button style="float: right;" class="btn btn-primary" type="button" data-dismiss="modal" onclick="refreshPage();"><i class="fa fa-times"></i>&nbsp;<?=$lng['Cancel']?></button>
					</div>
			 	</div>
			</div>
		</div>



<script type="text/javascript">
	
	// load modal on page load 


	


	$( document ).ready(function() {
		watchLocation();
		// getLocation();



		$('.langbutton').on('click', function(){
			$.ajax({
				url: "../ajax/change_lang.php",
				data: {lng: $(this).data('lng')},
				success: function(ajaxresult){
					location.reload();
				}
			});
		});



	});


	// function getLocation() {
	//   if (navigator.geolocation) {
	//     navigator.geolocation.getCurrentPosition(showPosition);
	//   } 
	// }

	function watchLocation()
	{
		// alert('Watching Position')
	  // Obtain the location at regularly interval
	  watchID = navigator.geolocation.watchPosition(showPosition);
	}


	function showPosition(position) {
	 var latitude  =  position.coords.latitude;
	 var longitude = position.coords.longitude;

	 var latitudeTxt = '<?php echo $lattitude_txt ?>';
	 var longitudeTxt = '<?php echo $longtitude_txt ?>';
	 $('#location1').html(''+latitudeTxt+' : '+latitude);
	 $('#location2').html(''+longitudeTxt+' : '+longitude);
	 $('#hidden_lat').val(latitude);
	 $('#hidden_long').val(longitude);

	 $('#locationModal').modal('show');

	}

	function refreshPage()
	{
		location.reload();
	}



	function pingLocation()
	{

		// get lattitude and longtitude and save them in employees profile 
		var employeeID =  '<?php echo $employeeId ?>';
		var cId =  '<?php echo $cId ?>';
		var latitude = $('#hidden_lat').val();
		var longitude = $('#hidden_long').val();

		console.log(cId);
		console.log(employeeID);

		$.ajax({
					url: "../ajax/save_employee_location.php",
					data: {employeeID: employeeID,latitude:latitude,longitude:longitude,cId:cId},
					success: function(result){
						if($.trim(result) == 'success')
						{
							// $("#submitBtn").removeClass('flash');
							// $('#sAlert').fadeOut();
							// closeEmailModal();	
							// $('#chooseEmail').modal('hide');

							// hide the modal and show success modal 
							$('#locationModal').modal('hide');
							$('#finalAccept').modal('hide');
							$('#successModal').modal('show');

							typeWriter();
							// show a success modal

						}
					}
				})


	}


var initial = 0;
function typeWriter(txt) {
	var textthank = '<?php echo $thankyoutxt ?>';
	txt = textthank;
	var speed = 30;


  if (initial < txt.length) {
    document.getElementById("successMessage").innerHTML += txt.charAt(initial);
    initial++;
    setTimeout(typeWriter, speed);
  }
}

function pingLocation_check()
{
	// popup a new modal to show the yes and no for the location  

	$('#locationModal').modal('hide');
	$('#finalAccept').modal('show');

}


</script>
	</body>
</html>










