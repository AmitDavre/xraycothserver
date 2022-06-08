<?

if($_GET['id'])
{
	// fetch branches data  
	$sql2 = "SELECT * FROM ".$cid."_branches_data WHERE ref = '".$_GET['id']."'";


	if($res2 = $dbc->query($sql2)){
		if($row2 = $res2->fetch_assoc())
		{
			$loc1 = json_decode($row2['loc1']);
			$loc2 = json_decode($row2['loc2']);
			$loc3 = json_decode($row2['loc3']);
			$loc4 = json_decode($row2['loc4']);
			$loc5 = json_decode($row2['loc5']);
		}
	
	}	
}

	// echo '<pre>';
	// print_r($loc2);
	// echo '<pre>';


	$newLocations = array();

	$newLocations[1] = array(

		'name' => $loc1->name,
		'code' => $loc1->code,
        'qr' => $loc1->qr,
        'latitude' => $loc1->latitude,
        'longitude' => $loc1->longitude,
        'perimeter' => $loc1->perimeter,

	);		
	$newLocations[2] = array(

		'name' => $loc2->name,
		'code' => $loc2->code,
        'qr' => $loc2->qr,
        'latitude' => $loc2->latitude,
        'longitude' => $loc2->longitude,
        'perimeter' => $loc2->perimeter,

	);	

	$newLocations[3] = array(

		'name' => $loc3->name,
		'code' => $loc3->code,
        'qr' => $loc3->qr,
        'latitude' => $loc3->latitude,
        'longitude' => $loc3->longitude,
        'perimeter' => $loc3->perimeter,

	);	

	$newLocations[4] = array(

		'name' => $loc4->name,
		'code' => $loc4->code,
        'qr' => $loc4->qr,
        'latitude' => $loc4->latitude,
        'longitude' => $loc4->longitude,
        'perimeter' => $loc4->perimeter,

	);		

	$newLocations[5] = array(

		'name' => $loc5->name,
		'code' => $loc5->code,
        'qr' => $loc5->qr,
        'latitude' => $loc5->latitude,
        'longitude' => $loc5->longitude,
        'perimeter' => $loc5->perimeter,

	);	




	
	
	$data = array();
	$locations = array();
	$sql = "SELECT * FROM ".$cid."_leave_time_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data['time_start'] = $row['time_start'];
			$data['time_end'] = $row['time_end'];
			$data['accept_late'] = $row['accept_late'];
			$data['accept_early'] = $row['accept_early'];
			$data['ot_start_after'] = $row['ot_start_after'];
			$data['ot_period'] = $row['ot_period'];
			$data['ot_roundup'] = $row['ot_roundup'];
			//$data['fixed_break'] = $row['fixed_break'];
			$data['scan_system'] = $row['scan_system'];
			$data['gps'] = $row['gps'];
			$data['qrcode'] = $row['qrcode'];
			$data['perimeter'] = $row['perimeter'];
			$var_allow = unserialize($row['var_allow']);
			$compensations = unserialize($row['compensations']);
			$locations = unserialize($row['scan_locations']);
			$data['otnd'] = $row['otnd'];
			$otsa = unserialize($row['otsa']);
			$otsu = unserialize($row['otsu']);
			$othd = unserialize($row['othd']);
			$comments = unserialize($row['comments']);
		}
	}






	//$locations = array();
	if(!$newLocations){
		for($i=1;$i<=5;$i++){
			$newLocations[$i]['name'] = '';
			$newLocations[$i]['code'] = '';
			$newLocations[$i]['qr'] = '';
			$newLocations[$i]['latitude'] = '';
			$newLocations[$i]['longitude'] = '';
			$newLocations[$i]['perimeter'] = '';
		}
	}


	for($i=1;$i<=5;$i++){
		$scan_locations[$i] = array('name'=>$newLocations[$i]['name'],'latitude'=>$newLocations[$i]['latitude'],'longitude'=>$newLocations[$i]['longitude']);
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
		<i class="fa fa-clock-o"></i>&nbsp; <?=$lng['Time settings']?>
		<span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span>
	</h2>	
	
	<div class="main" style="overflow:hidden">
		<div id="dump"></div>
		
		<ul class="nav nav-tabs" id="myTab">
			
			<li class="nav-item"><a id="location_active" class="nav-link" href="#tab_locationa" data-toggle="tab"><?=$lng['Location']?></a></li>
		</ul>
		
		<div class="tab-content" style="height:calc(100% - 40px)">				
			<div class="tab-pane" id="tab_locationa">
				<form id="locationForm">
				<input type="hidden" name="location[ref_id]" value="<?php echo $_GET['id'];?>">

				<button style="position:absolute; top:15px; right:16px" class="btn btn-primary submitBtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				
				<table border="0" width="100%" style="table-layout:fixed"><tr><td style="vertical-align:top; width:550px; padding:0">
				<? foreach($scan_locations as $key=>$val){ ?>
				<table width="100%" border="0" class="basicTable inputs nowrap" style="margin-bottom:10px">
					<tr>
					  <th>Location <?php echo $key;?></th>
						<td style="width:100%" >
							<input type="text" name="location[<?=$key?>][name]" value="<?=$newLocations[$key]['name']?>">
							<input id="code<?=$key?>" type="hidden" name="location[<?=$key?>][code]" value="<?=$newLocations[$key]['code']?>">
							<input id="qr<?=$key?>" type="hidden" name="location[<?=$key?>][qr]" value="<?=$newLocations[$key]['qr']?>">
						</td>
						<td rowspan="6" style="width:160px">
							<? if(empty($newLocations[$key]['qr'])){ ?>
								<img id="QRimage<?=$key?>" style="width:160px; padding:6px" src="../images/1499401426qr_icon.svg">
							<? }else{ ?>
								<img id="QRimage<?=$key?>" width="160px" src="<?=$newLocations[$key]['qr']?>">
							<? } ?>						
						</td>
					</tr>
					<tr>
					  <th>Latitude</th>
						<td><input type="text" name="location[<?=$key?>][latitude]" value="<?=$newLocations[$key]['latitude']?>"></td>
					</tr>
					<tr>
					  <th>Longitude</th>
						<td><input type="text" name="location[<?=$key?>][longitude]" value="<?=$newLocations[$key]['longitude']?>"></td>
					</tr>
					<tr>
					  <th>Scan perimeter</th>
						<td><input class="numeric sel" type="text" name="location[<?=$key?>][perimeter]" value="<?=$newLocations[$key]['perimeter']?>"></td>
					</tr>
					<tr>
						<td colspan="2">
							<button data-id="<?=$key?>" type="button" style="width:48%; text-align:center; margin:8px 0" class="newQRcode btn btn-primary btn-fl">Create new QR code<? //=$lng['Today']?></button>
							<button <? if(empty($newLocations[$key]['qr'])){echo 'disabled';}?> data-id="<?=$key?>" type="button" style="width:48%; text-align:center; margin:8px 8px 8px 0" class="printLocation btn btn-primary btn-fr"><i class="fa fa-print"></i> &nbsp;Print QR code<? //=$lng['Today']?></button>
						</td>
					</tr>
				</table>
				<? } ?>
				</td><td valign="top" style="padding-left:10px">
					
					<h6 style="background:#eee; padding:6px 10px; margin:0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$compinfo[$lang.'_compname']?></span></h6>
					<div style="height:818px;" id="map-canvas"></div>
				
				</td></tr></table>
				
				</form>
			</div>
		</div>
	
	</div>
	

<script>

	
	var heights = window.innerHeight-280;
	
	
	$(document).ready(function() {

		$('#location_active').click();
		
		$("#addnew").on('click', function(){ 
			$("#id").val('').prop('readonly', false);
			$("#code").val('');
			$("#name").val('');
			$("#shiftType").val(0).trigger('change');
			$("#startdate").val('');
			$("#holidays").val(0);
			$("#description").val('');
			$("#addnewplan").slideDown(200);
		});
			
		$(document).on('click', '.newQRcode', function () {
			var id  = $(this).data('id');
			$.ajax({
				url: "ajax/create_qrcode.php",
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
			var id = $(this).data('id');
			//alert(id)
			var bid = '<?php echo $_GET['id'] ?>';
			window.open('print_scan_location.php?id='+id+'&bid='+bid,'_blank');
		});
		
		$("#locationForm").submit(function(e){ 
			e.preventDefault();
			var data = $(this).serialize();

			$.ajax({
				url: "ajax/update_scan_locations.php",
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
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
							//closeConfirm: true
						})
					}
					//setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
					
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
		
		$("#shiftplanForm").submit(function(e){ 
			e.preventDefault();
			$(".submitBtn i").removeClass('fa-save');
			$(".submitBtn i").addClass('fa-repeat fa-spin');
			var data = $(this).serialize();
			//alert('data')
			$.ajax({
				url: "ajax/update_shiftplan.php",
				type: 'POST',
				data: data,
				success: function(result){
					//$("#dump").html(result); return false;
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly',
							duration: 2,
						})

						$(".submitBtn").removeClass('flash');
						$("#sAlert").fadeOut(200);

					}else{
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
							duration: 4,
							//closeConfirm: true
						})
					}
					setTimeout(function(){$(".submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
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
		

	

		var locations = <?=json_encode($scan_locations)?>;
		var locs = <?=json_encode(count($scan_locations))?>;
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
			var myLatlng = new google.maps.LatLng(locations[1]['latitude'], locations[1]['longitude']);
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
			for (i=1; i <= locs; i++) { 
				var content = locations[i]['name'];
				if(locations[i]['latitude'] != ''){
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(locations[i]['latitude'], locations[i]['longitude']),
						map: map,
						title: locations[i]['name']
					});
					var infowindow = new google.maps.InfoWindow()
					google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
						return function() {
							infowindow.setContent(content);
							infowindow.open(map,marker);
						};
					})(marker,content,infowindow)); 
				}
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
			



</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
