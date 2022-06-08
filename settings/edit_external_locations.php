<?

if($_GET['id'])
{
	// fetch branches data  
	$sql2 = "SELECT * FROM ".$cid."_location WHERE loc_id = '".$_GET['id']."'";


	if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc())
		{
			$location_name = $row2['loc_name'];
			$code = $row2['code'];
			$qr = $row2['qr'];
			$latitude = $row2['latitude'];
			$longitude = $row2['longitude'];
			$perimeter = $row2['perimeter'];
			$contact_name = $row2['contact_name'];
			$locations_address = $row2['address'];
			$contact_email = $row2['contact_email'];
			$scan_in_confirmation = $row2['scan_in_confirmation'];
			$scan_out_confirmation = $row2['scan_out_confirmation'];
		}
	
	}	
}




?>


<style>
.fc-event {
  position: relative;
  display: block;
  font-size: 15px;
  line-height:22px;
  xheight:62px;
  border:0px red solid;
  background-color: transparent;
  border-radius:0;
  color:#999;
  font-weight: 400;
  white-space:normal;
  text-align:left;
  padding:0 5px;
  top:-25px;
  cursor:default !important;
}
.fc-event .fc-title {
  padding:0;
  cursor:default !important;
}
.fc-event .fc-title span {
  font-size: 12px;
  line-height:14px;
  display:block;
  color:#999;
  background:#eee;
  font-weight:500;
  padding:3px 5px;
  border-radius:1px;
  white-space:normal;
  border-left:5px solid rgba(0,0,0,0.2);
  cursor:default !important;
}
.fc-event .fc-title span.fc-payday {
  color:#fff;
  background: #356e35;
}
.fc-event .fc-title span.fc-nonwork {
  color:#fff;
  background: #57889c;
}
.fc-event .fc-title span.fc-holiday {
  color: #fff;
  background: #ac5287;
}
.fc-event .fc-title span.fc-leave {
  color: #fff;
  background: #b09b5b;
}
.fc-event:hover {
  color:#c00;
}
.fc-toolbar {
	xdisplay:none;
	xbackground:red;
	margin:0 !important;
	xborder:1px red solid !important;
}
.fc-toolbar h2 {
  text-shadow: none !important;
  margin:0 !important;
  padding:0 !important;
  display:block !important;
  font-size: 24px !important;
  background:transparent !important;
  border:0 !important;
  font-weight: 600 !important;
  line-height:40px !important;
  color:#333 !important;
}
.fc-sat, .fc-sun {
}
.fc-week-number {
	font-weight:700;
	color: #0099CC;
	cursor:default !important;
}
td.fc-sat, td.fc-sun {
  xbackground-color: #eee;
}
.fc-day-number {
  padding: 3px 7px 0 0 !important;
  font-weight:600;
  color:#b00;
  font-size:16px;
  cursor:default !important;
}
.fc-day {
  cursor:default !important;
}
.fc-unthemed .fc-disabled-day {
	opacity: 0.5;
	background: #fff url(../images/bg-disabled.png);
}
.confDelete {
	background:red;
	border:0;
}
.SumoSelect{
	width: 99% !important;
	min-width: 200px !important;
	padding: 4px 0 0 10px !important;
	border:0 !important;
}
input.step2:read-only, 
input.step3:read-only ,
input.step4:read-only, 
input.step5:read-only  {
	color:#aaa;
	cursor:not-allowed;
}
</style>


	<h2>
		<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['External Location']?>
		<span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span>
	</h2>	
	
	<div class="main" style="overflow:hidden">
		<div id="dump"></div>
		
		<ul class="nav nav-tabs" id="myTab">
			
			<li style="visibility: hidden;" class="nav-item"><a id="location_active" class="nav-link" href="#tab_locationa" data-toggle="tab"><?=$lng['Location']?></a></li>
		</ul>
		
		<div class="tab-content" style="height:calc(100% - 40px)">				
			<div class="tab-pane active" id="tab_locationa">
				<form id="locationForm">
				<input type="hidden" name="ref_id" value="<?php echo $_GET['id'];?>">

				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
				<table border="0" width="100%" style="table-layout:fixed"><tr><td style="vertical-align:top; width:550px; padding:0">
				<table width="100%" border="0" class="basicTable inputs nowrap" style="margin-bottom:10px">
					<tr>
						<th style="text-align:center;" colspan="2">
							<span style="font-size: 23px;">Locations</span>
						</th>
					</tr>



					<tr>
					  <th>Location name</th>
						<td style="width:100%" >
							<input type="text" name="name" value="<?=$location_name?>">
							<input id="code" type="hidden" name="code" value="<?=$code?>">
							<input id="qr" type="hidden" name="qr" value="<?=$qr?>">
						</td>
				
					</tr>
					<tr>
					  <th>Latitude</th>
						<td><input class="sel" type="text" name="latitude" value="<?=$latitude?>"></td>
					</tr>
					<tr>
					  <th>Longitude</th>
						<td><input class="sel" type="text" name="longitude" value="<?=$longitude?>"></td>
					</tr>
					<tr>
					  <th>Scan perimeter</th>
						<td><input class="numeric sel" type="text" name="perimeter" value="<?=$perimeter?>"></td>
					</tr>
				
					<tr style="height: 30px;"></tr>


					<tr>
						<th style="text-align:center;" colspan="2">
							<span style="font-size: 23px;">QR Code</span>
						</th>
					</tr>
					<tr>

						<td   style="width:160px">
							<? if(empty($qr)){ ?>
								<img id="QRimage" style="width:160px; padding:6px;position: relative;left: 190px;" src="../images/1499401426qr_icon.svg">
							<? }else{ ?>
								<img id="QRimage" style="position: relative;left: 190px;"  width="160px" src="<?=$qr?>">
							<? } ?>						
						</td>	



					</tr>

					<tr>
						<td colspan="2">
							<button data-id="" type="button" style="width:48%; text-align:center; margin:8px 0" class="newQRcode btn btn-primary btn-fl">Create new QR code</button>
							<button <? if(empty($qr)){echo 'disabled';}?> data-id="" type="button" style="width:48%; text-align:center; margin:8px 8px 8px 0" class="printLocation btn btn-primary btn-fr"><i class="fa fa-print"></i> &nbsp;Print QR code</button>
						</td>
					</tr> 




					<tr style="height: 30px;"></tr>




					<tr>
					  <th colspan = "2" style="text-align: center;"><span style="font-size: 23px;">External Contact</span></th>
					</tr>
	
					<tr>
					  <th>Location Address</th>
						<td><input type="text" id="locations_address" name="locations_address" value="<?=$locations_address?>"></td>
					</tr>

					<tr>
					  <th>Contact Name</th>
						<td><input type="text" id= "contact_name" name="contact_name" value="<?=$contact_name?>"></td>
					</tr>
				
					<tr>
					  <th>Contact Email</th>
						<td><input type="text" id="contact_email" name="contact_email" value="<?=$contact_email?>"></td>
					</tr>
					<tr>
					  <th>Confirmation Email</th>
						<td>
						  <input type="checkbox" id="confirmationcheckIn" name="confirmationcheckIn" <?php if($scan_in_confirmation == '1'){echo "checked";}?> value="1" style="margin-left: 9px;">
							 
						</td>
					</tr>

					<tr>
					  <td colspan="2">
							<button  type="button" style="width:48%; text-align:center; margin:8px 0" class="pingqrcode btn btn-primary btn-fl">Ping Contact</button>
							<button type="button" style="width:48%; text-align:center; margin:8px 8px 8px 0" class="sendqrcode btn btn-primary btn-fr"><i class="fa fa-envelope"></i> &nbsp;Send QR Code</button>
						</td>
					</tr>


				</table>
				</td>
				<td valign="top" style="padding-left:10px">
					
					<h6 style="background:#eee; padding:6px 10px; margin:0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$compinfo[$lang.'_compname']?></span></h6>
					<div style="height:818px;" id="map-canvas"></div>
				
				</td>
				</tr>

			</table>
				
				</form>
			</div>
		</div>
	
	</div>
	

		<div class="modal fade" id="finalAcceptIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

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



							<button class="btn btn-primary" type="button" onclick="sendConfirmationEmail('in');" >&nbsp;<?=$lng['Yes']?> <i class="fa fa-check"></i></button>
							<button style="float: right;" class="btn btn-primary" type="button" data-dismiss="modal" ><i class="fa fa-times"></i>&nbsp;<?=$lng['Cancel']?></button>
					</div>
			 	</div>
			</div>
		</div>

		<div class="modal fade" id="finalAcceptOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

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



							<button class="btn btn-primary" type="button" onclick="sendConfirmationEmail('out');" >&nbsp;<?=$lng['Yes']?> <i class="fa fa-check"></i></button>
							<button style="float: right;" class="btn btn-primary" type="button" data-dismiss="modal" ><i class="fa fa-times"></i>&nbsp;<?=$lng['Cancel']?></button>
					</div>
			 	</div>
			</div>
		</div>


	



<script>

	
	var heights = window.innerHeight-280;
	
	
	$(document).ready(function() {

		$('#location_active').click();
		
			
		$(document).on('click', '.newQRcode', function () {
			var id  = $(this).data('id');
			$.ajax({
				url: "ajax/create_external_qr_code.php",
				data: {id: id},
				dataType: 'json',
				success: function(result){
					//$("#dump").html(result);
					$("#QRimage"+id).attr('src',result.qr);
					$("#qr"+id).val(result.qr);
					$("#code"+id).val(result.code);
					$(".submitBtn").addClass('flash');
					$("#sAlert").fadeIn(200);
					$('.submitBtn').click();
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
		})
			
		$(".printLocation").on('click', function(e){ 
			var bid = '<?php echo $_GET['id'] ?>';
			window.open('print_external_scan_location.php?id='+bid+'_blank');
		});

		$(".sendqrcode").on('click', function(e){ 

			// run ajax and send mail  to the selected user in the column    
			var contact_email = $('#contact_email').val();
			var qr_code = $('#qr').val();

			var cidvalue = '<?php echo $cid?>';

			var print_id = '<?php echo $_GET['id'] ?>';
			var printLink = 'settings/print_email_qr_code.php?cid='+cidvalue+'&id='+print_id;




			if(!contact_email)
			{
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Please enter email to continue.',
					duration: 4,
				})

				return false; 

			}
			// // check if contact email is empty or not if empty dont send the email  

			$.ajax({
				url: "ajax/send_qrcode_email.php",
				data: {contact_email:contact_email,qr_code:qr_code,printLink:printLink},
				success: function(result){
					if($.trim(result) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Email sent successfuly',
							duration: 2,
						})
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

		$(".pingqrcode").on('click', function(e){ 

			// run ajax and send mail  to the selected user in the column    
			var contact_email = $('#contact_email').val();
			var location_name_value = $('#locations_address').val();
			var cidvalue = '<?php echo $cid?>';
			var print_id = '<?php echo $_GET['id'] ?>';




			if(!contact_email)
			{
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Please enter email to continue.',
					duration: 4,
				})

				return false; 

			}
			// // check if contact email is empty or not if empty dont send the email  

			$.ajax({
				url: "ajax/ping_qrcode_location.php",
				data: {contact_email:contact_email,print_id:print_id,cidvalue:cidvalue,location_name_value:location_name_value},
				success: function(result){
					if($.trim(result) == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Email sent successfuly',
							duration: 2,
						})
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



		$("#locationForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();

			$.ajax({
				url: "ajax/update_location_request.php",
				data: data,
				success: function(result){
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})
						setTimeout(function(){location.reload();},1000);
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
		






		var latitude = '<?php echo $latitude?>';
		var longitude = '<?php echo $longitude?>';
		var location_name = '<?php echo $location_name?>';
		//alert(locs) 
		
		function addInfoWindow(marker, message) {
			var infoWindow = new google.maps.InfoWindow({
					content: message
			});
			google.maps.event.addListener(marker, 'click', function () {
					infoWindow.open(map, marker);
			});
		}		
		function initialize() {
			var myLatlng = new google.maps.LatLng(latitude, longitude);
			var mapOptions = {
				scrollwheel: false,
				navigationControl: false,
				mapTypeControl: false,
				scaleControl: false,
				draggable: true,
				zoom: 19,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: myLatlng
			}
			var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			var marker, i, myinfo;
			
				var content = location_name;
				if(latitude != ''){
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(latitude, longitude),
						map: map,
						title: location_name
					});
					var infowindow = new google.maps.InfoWindow()
					google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
							infowindow.setContent(content);
							infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 
				}
			
					
			$(window).resize(function() {
				 google.maps.event.trigger(map, "resize");
			});
			google.maps.event.addListener(map, "idle", function(){
				google.maps.event.trigger(map, 'resize'); 
			});			
		}
		initialize();
		//google.maps.event.addDomListener(window, 'load', initialize);
		//setTimeout(function(){
		//},1000);
			
});

	

		$(document).on('click', '.editlocations', function(e) {

			var id = $(this).closest('tr').find('td:eq(0)').text();

			window.location.href="index.php?mn=7001&id="+id;
		})

		$('input, textarea').on('keyup', function(e){
			$('#sAlert').fadeIn(200);
			$(".submitBtn").addClass('flash');
		})
			
		$('#confirmationcheckIn').change(function () {
		    // if (this.checked) {
		    //     $('#finalAcceptIn').modal('show');
		    // }
		});

		$('#confirmationcheckOut').change(function () {
		    if (this.checked) {
		        $('#finalAcceptOut').modal('show');
		    }
		});



		function sendConfirmationEmail(scan_value)
		{

			var contact_email = $('#contact_email').val();
			var contact_name = $('#contact_name').val();
			var cidvalue = '<?php echo $cid?>';
			var print_id = '<?php echo $_GET['id'] ?>';


			if(!contact_email)
			{
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : Please enter email to continue.',
					duration: 4,
				})

				return false; 

			}
			// // check if contact email is empty or not if empty dont send the email  

			// $.ajax({
			// 	url: "ajax/send_confirmation_email.php",
			// 	data: {contact_email:contact_email,print_id:print_id,cidvalue:cidvalue,contact_name:contact_name,scan_value:scan_value},
			// 	success: function(result){
			// 		if($.trim(result) == 'success'){
			// 			$("body").overhang({
			// 				type: "success",
			// 				message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Email sent successfuly',
			// 				duration: 2,
			// 			})

			// 			 $('#finalAcceptIn').modal('hide');
			// 			 $('#finalAcceptOut').modal('hide');


			// 		}else{
			// 			$("body").overhang({
			// 				type: "error",
			// 				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
			// 				duration: 4,
			// 			})
			// 		}
					
			// 	},
			// 	error:function (xhr, ajaxOptions, thrownError){
			// 		$("body").overhang({
			// 			type: "error",
			// 			message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
			// 			duration: 8,
			// 			closeConfirm: "true",
			// 		})
			// 	}
			// });


		}



</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
