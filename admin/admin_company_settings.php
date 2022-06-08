<?
	$err_msg = "";
	$data = array();
	$sql = "SELECT * FROM rego_company_settings"; 
	if($res = $dba->query($sql)){
		$data = $res->fetch_assoc();
	}else{
		$err_msg = '<div class="box_err ibox">'.$lng['Error'].' : '.mysqli_error($dba).' <a class="box_close"><i class="fa fa-times fa-lg"></i></a></div>';
	}
	//var_dump($data); exit;
		
	if(empty($data['complogo'])){
		$data['complogo'] = '../images/notavailable.jpg';
	}
	
	$contacts = unserialize($data['contacts']);
	//var_dump($contacts);
	if(!isset($contacts[1])){
		$contacts[1]['name'] = '';
		$contacts[1]['phone'] = '';
		$contacts[1]['email'] = '';
	}
	if(!isset($contacts[2])){
		$contacts[2]['name'] = '';
		$contacts[2]['phone'] = '';
		$contacts[2]['email'] = '';
	}
	if(!isset($contacts[3])){
		$contacts[3]['name'] = '';
		$contacts[3]['phone'] = '';
		$contacts[3]['email'] = '';
	}
	if(!isset($contacts[4])){
		$contacts[4]['name'] = '';
		$contacts[4]['phone'] = '';
		$contacts[4]['email'] = '';
	}
	//var_dump($contact);
		
?>
	
	<h2><i class="fa fa-industry"></i>&nbsp; <?=$lng['Company settings']?> <span style="float:right; display:none; font-style:italic; color:#b00" id="sAlert"><?=$lng['Data is not updated to last changes made']?></span></h2>	
	<div class="main">
		

		<form id="companyForm" style="height:100%">
			<ul style="position:relative" class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link active" data-target="#tab_company" data-toggle="tab"><?=$lng['Company info']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_contact" data-toggle="tab"><?=$lng['Contact']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_options" data-toggle="tab"><?=$lng['Options']?></a></li>
				<!--<li class="nav-item"><a class="nav-link" data-target="#tab_whitelabel" data-toggle="tab">White label<? //=$lng['Options']?></a></li>-->
				<? //if($_SESSION['RGadmin']['access']['comp_settings']['edit'] == 1){ ?>
				<button class="btn btn-primary" style="position:absolute; bottom:6px; right:1px;" id="subButton" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
				<? //} ?>
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				
				<div style="display:none" id="message"></div>
				
				<div class="tab-pane show active" id="tab_company">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th style="width:1%"><i class="man"></i><?=$lng['Company name in Thai']?></th>
								<td><input name="compname_th" type="text" value="<?=$data['compname_th']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Company name in English']?></th>
								<td><input name="compname_en" type="text" value="<?=$data['compname_en']?>" /></td>
							</tr>
							<tr>
								<th><?=$lng['Phone']?></th>
								<td><input name="phone" type="text" value="<?=$data['phone']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Info email']?></th>
								<td><input name="info_mail" id="info_mail" type="text" value="<?=$data['info_mail']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Admin email']?></th>
								<td><input name="admin_mail" type="text" value="<?=$data['admin_mail']?>" /></td>
							</tr>
							<tr>
								<th><i class="man"></i><?=$lng['Support email']?></th>
								<td><input name="support_mail" type="text" value="<?=$data['support_mail']?>" /></td>
							</tr>
							<tr>
								<th><?=$lng['Agents eMail']?></th>
								<td><input name="agents_mail" type="text" value="<?=$data['agents_mail']?>" /></td>
							</tr>
							<tr>
								<th><?=$lng['Registration no']?></th>
								<td><input name="regno" type="text" value="<?=$data['regno']?>" /></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Address in Thai']?></th>
								<td width="70%"><textarea name="address_th" rows="4"><?=$data['address_th']?></textarea></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Address in English']?></th>
								<td><textarea name="address_en" rows="4"><?=$data['address_en']?></textarea></td>
							</tr>
							<tr>
								<th class="vat">
									<?=$lng['Company logo']?><br>
									<small><?=$lng['Max. width']?>: 350px<br /><?=$lng['Max. height']?>: 80px</small>
								</th>
								<td style="padding:10px !important">
									<input style="visibility:hidden; height:0; float:left" type="file" name="complogo" id="complogo" />
									<img id="logoimg" style="max-height:40px; margin-bottom:10px; display:block" src="<?=$data['complogo'].'?'.time()?>" />
									<? //if($_SESSION['admin']['access']['comp_settings']['edit'] == 1){ ?>
									<button onclick="$('#complogo').click()" class="btn btn-outline-secondary btn-xs" style="margin:0; padding:0px 8px; display:inline-block" type="button"><?=$lng['Select file']?></button><span style="display:inline-block; padding-left:10px" id="logoname"><?=$lng['No file selected']?></span>
									<? //} ?>
								</td>
							</tr>

							<?php
								if($_SESSION['RGadmin']['username'] == 'admin@root.com'){
									$addstyle = '';
								}else{
									$addstyle = 'readonly="readonly"';
								}

							?>
							<tr>
								<th><?=$lng['Max. Employees']?></th>
								<td>
									<input name="maxEmp" type="text" value="<?=$data['max_employees']?>" <?php echo $addstyle;?>>
								</td>
							</tr>


						</tbody>
					</table>
					<div style="padding:0 0 0 20px" id="dump2"></div>
					<div style="height:15px"></div>
				
				</div>
			
				<div class="tab-pane" id="tab_contact">
					<table class="basicTable inputs" border="0">
						<thead>
							<tr>
								<th colspan="2">
									<i class="fa fa-arrow-circle-down"></i>&nbsp; <?=$lng['Application admin']?>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="vertical-align:top"><?=$lng['Name']?></th>
								<td><input name="contacts[1][name]" type="text" value="<?=$contacts[1]['name']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Phone']?></th>
								<td><input name="contacts[1][phone]" type="text" value="<?=$contacts[1]['phone']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['email']?></th>
								<td><input name="contacts[1][email]" type="text" value="<?=$contacts[1]['email']?>"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="2">
									<i class="fa fa-arrow-circle-down"></i>&nbsp; <?=$lng['Manager']?>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="vertical-align:top"><?=$lng['Name']?></th>
								<td><input name="contacts[2][name]" type="text" value="<?=$contacts[2]['name']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Phone']?></th>
								<td><input name="contacts[2][phone]" type="text" value="<?=$contacts[2]['phone']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['email']?></th>
								<td><input name="contacts[2][email]" type="text" value="<?=$contacts[2]['email']?>"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="2">
									<i class="fa fa-arrow-circle-down"></i>&nbsp; <?=$lng['Contact']?> 3
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="vertical-align:top"><?=$lng['Name']?></th>
								<td><input name="contacts[3][name]" type="text" value="<?=$contacts[3]['name']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Phone']?></th>
								<td><input name="contacts[3][phone]" type="text" value="<?=$contacts[3]['phone']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['email']?></th>
								<td><input name="contacts[3][email]" type="text" value="<?=$contacts[3]['email']?>"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="2">
									<i class="fa fa-arrow-circle-down"></i>&nbsp; <?=$lng['Contact']?> 4
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th style="vertical-align:top"><?=$lng['Name']?></th>
								<td><input name="contacts[4][name]" type="text" value="<?=$contacts[4]['name']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['Phone']?></th>
								<td><input name="contacts[4][phone]" type="text" value="<?=$contacts[4]['phone']?>"></td>
							</tr>
							<tr>
								<th style="vertical-align:top"><?=$lng['email']?></th>
								<td><input name="contacts[4][email]" type="text" value="<?=$contacts[4]['email']?>"></td>
							</tr>
						</tbody>
					</table>
					<div style="height:15px"></div>
				
				</div>
			
				<div class="tab-pane" id="tab_options">
					<table class="basicTable inputs" border="0">
						<tbody>
						<tr><th>
							<label><?=$lng['Prefered language']?> :</label>
						</th><td>
							<select name="preflang" style="width:100%">
								<option <? if($lang=='th'){echo 'selected';} ?> value="th">ไทย</option>
								<option <? if($lang=='en'){echo 'selected';} ?> value="en">English</option>
							</select>
						</td></tr>
						<tr><th>
							<label><?=$lng['Log-time']?> :</label>
						</th><td>
							<select name="logtime" style="width:100%">
								<option <? if($data['logtime']==900){echo 'selected';} ?> value="900">15 <?=$lng['min']?></option>
								<option <? if($data['logtime']==1800){echo 'selected';} ?> value="1800">30 <?=$lng['min']?></option>
								<option <? if($data['logtime']==3600){echo 'selected';} ?> value="3600">60 <?=$lng['min']?></option>
								<option <? if($data['logtime']==86400){echo 'selected';} ?> value="86400">1 <?=$lng['day']?></option>
							</select>
						</td></tr>
						<tr><th>
							<label><?=$lng['Latitude']?> :</label>
						</th><td>
							<input name="latitude" id="latitude" type="text" value="<?=$data['latitude']?>" />
						</td></tr>
						<tr><th>
							<label><?=$lng['Longitude']?> :</label>
						</th><td>
							<input name="longitude" id="longitude" type="text" value="<?=$data['longitude']?>" />
						</td></tr>
						</tbody>
					</table>
					
					<div style="height:0px"></div>
					
					<h6 style="background:#eee; padding:6px 10px; margin:10px 0 0 0; border-radius:3px 3px 0 0"><i class="fa fa-arrow-circle-down"></i>&nbsp;&nbsp;<?=$lng['Google Map']?> - <span style="text-transform:none"><?=$data['compname_'.$lang]?></span></h6>
					<div style="height:calc(100% - 155px);" id="map-canvas"><p style="padding:10px 15px">Map <?=$data['compname_'.$lang]?></p></div>

				</div>
			
				<!--<div class="tab-pane show" id="tab_whitelabel">
					<table class="basicTable inputs" border="0">
						<tbody>
							<tr>
								<th style="width:1%"><i class="man"></i>Header Admin platform<? //=$lng['Company name in Thai']?></th>
								<td><input name="" type="text" value="PKF People - Admin<? //=$data['compname_th']?>" /></td>
							</tr>
							<tr>
								<th class="vat">
									Header Logo Admin<br>
									<small><?=$lng['Max. width']?>: 350px<br /><?=$lng['Max. height']?>: 80px</small>
								</th>
								<td style="padding:10px !important">
									<input style="visibility:hidden; height:0" type="file" name="complogo" id="complogo" />
									<img id="logoimg" style="max-height:60px; margin-bottom:10px; display:block" src="<?=$data['complogo'].'?'.time()?>" />
									<? //if($_SESSION['admin']['access']['comp_settings']['edit'] == 1){ ?>
									<button xonclick="$('#complogo').click()" class="btn btn-outline-secondary btn-xs" style="margin:0; padding:0px 8px; display:inline-block" type="button"><?=$lng['Select file']?></button><span style="display:inline-block; padding-left:10px" id="logoname"><?=$lng['No file selected']?></span>
									<? //} ?>
								</td>
							</tr>
							<tr>
								<th><i class="man"></i>Header Customer platform</th>
								<td><input name="" type="text" value="PKF People<? //=$data['compname_en']?>" /></td>
							</tr>
							<tr>
								<th class="vat">
									Header Logo Customer<br>
									<small><?=$lng['Max. width']?>: 350px<br /><?=$lng['Max. height']?>: 80px</small>
								</th>
								<td style="padding:10px !important">
									<input style="visibility:hidden; height:0" type="file" name="complogo" id="complogo" />
									<img id="logoimg" style="max-height:60px; margin-bottom:10px; display:block" src="<?=$data['complogo'].'?'.time()?>" />
									<? //if($_SESSION['admin']['access']['comp_settings']['edit'] == 1){ ?>
									<button xonclick="$('#complogo').click()" class="btn btn-outline-secondary btn-xs" style="margin:0; padding:0px 8px; display:inline-block" type="button"><?=$lng['Select file']?></button><span style="display:inline-block; padding-left:10px" id="logoname"><?=$lng['No file selected']?></span>
									<? //} ?>
								</td>
							</tr>
						</tbody>
					</table>
					<div style="height:15px"></div>
				
				</div>-->
			
			
			
			
			</div>
				
		</form>
	
	</div>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBV4OwtunNsg-_t446caGdt1QCBZQQhWUs"></script>
	
	<script>
		
		$(document).ready(function() {
			
			var latitude = <?=json_encode($data['latitude'])?>;
			var longitude = <?=json_encode($data['longitude'])?>;
			var compname = <?=json_encode($data['compname_'.$lang])?>;
			
			$("#complogo").on("change", function(e){
				e.preventDefault();
				//var id = cid.replace('x',acc);
				//alert('id');
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				//alert(ff)
				$('#err_msg').html('');
				if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
					$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').fadeIn(200);
					setTimeout(function(){$("#message").fadeOut(200);},4000);
					return false;
				}
				$('#logoname').html(ff);
				var file = $(this)[0].files[0];
				if(file){
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						var img = new Image;
						$('#logoimg').prop('src', e.target.result);
						$("#submitbtn").addClass('warning').flicker({wait:1000, cssValue: 0.4});
					}
				}
				//$('#message').html('');
				return false;
			});
			
			$("#companyForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				
				if($('input[name="compname_th"]').val() == '' || 
					$('input[name="compname_en"]').val() == '' || 
					$('input[name="info_mail"]').val() == '' || 
					$('input[name="admin_mail"]').val() == '' || 
					$('input[name="support_mail"]').val() == ''){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
					})
					return false;
				}
				//return false;
				$.ajax({
					url: AROOT+"ajax/update_company_settings.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//$("#dump2").html(result); return false;
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
			
			$("#latitude").change(function(){
				latitude = $(this).val();
				initialize();
			});
			
			$("#longitude").change(function(){
				longitude = $(this).val();
				initialize();
			});
			
			function initialize() {
				var myLatlng = new google.maps.LatLng(latitude,longitude);
				var mapOptions = {
					scrollwheel: false,
					navigationControl: false,
					mapTypeControl: false,
					scaleControl: false,
					draggable: true,
					zoom: 13,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: myLatlng
				}
				var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: compname
				});
				$(window).resize(function() {
					 // (the 'map' here is the result of the created 'var map = ...' above)
					 google.maps.event.trigger(map, "resize");
				});
				google.maps.event.addListener(map, "idle", function(){
					google.maps.event.trigger(map, 'resize'); 
				});			
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			
			
		});
	
	</script>


















