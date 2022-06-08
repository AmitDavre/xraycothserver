<?
	$sql = "SELECT * FROM ".$cid."_company_settings";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
			if(empty($data['logofile'])){$data['logofile'] = 'images/rego_logo.png';}
		}
	}else{
		//var_dump(mysqli_error($dbc));
	}
	//var_dump($data); exit;
	
	$sql = "SELECT version, employees, period_start, period_end, firstname, lastname, phone, email FROM rego_customers WHERE clientID = '".$cid."'";
	if($res = $dbx->query($sql)){
		$client = $res->fetch_assoc();
	}else{
		//var_dump(mysqli_error($dbc));
	}
	//var_dump($client); exit;
	
?>
<style>
	input:read-only {
		color:#aaa;
	}
</style>

	<h2 style="padding-right:60px"><i class="fa fa-cog fa-mr"></i> <?=$lng['Company settings']?>
		<span style="display:none; font-style:italic; color:#b00; padding-left:30px" id="sAlert"><i class="fa fa-exclamation-triangle fa-mr"></i><?=$lng['Data is not updated to last changes made']?></span>
	</h2>		

	<div class="main">
		<div style="padding:0 0 0 20px" id="dump"></div>
		<form id="systemForm" enctype="multipart/form-data" style="height:100%">
			<input style="visibility:hidden; height:0; float:left" type="file" name="complogo" id="complogo" />
			<button class="btn btn-primary" style="position:absolute; top:15px; right:15px" id="submitBtn" type="submit"><i class="fa fa-save fa-mr"></i><?=$lng['Update']?></button>
	
			<div class="tab-content-left">
				<table class="basicTable inputs" border="0" xstyle="height:100%">
					<tbody>
						<tr>
							<th style="width:5%;"><i class="man"></i><?=$lng['Company name in Thai']?></th>
							<td><input placeholder="..." name="th_compname" id="th_compname" type="text" value="<?=$data['th_compname']?>" /></td>
						</tr>
						<tr>
							<th><i class="man"></i><?=$lng['Company name in English']?></th>
							<td><input placeholder="..." name="en_compname" id="en_compname" type="text" value="<?=$data['en_compname']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Billing address']?> <?=$lng['Thai']?></th>
							<td><textarea name="billing_th" style="margin-bottom:5px" placeholder="..." rows="4" name=""><?=$data['billing_th']?></textarea></td>
						</tr>
						<tr>
							<th><?=$lng['Billing address']?> <?=$lng['English']?></th>
							<td><textarea name="billing_en" style="margin-bottom:5px" placeholder="..." rows="4" name=""><?=$data['billing_en']?></textarea></td>
						</tr>
						<tr>
							<th><i class="man"></i><?=$lng['Tax ID no.']?></th>
							<td><input class="tax_id_number"  name="tax_id" type="text" value="<?=$data['tax_id']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['WHT Certificate']?></th>
							<td>
								<select name="wht" style="width:100%">
								<? foreach($noyes01 as $k=>$v){ ?>
									<option <? if($data['wht'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
								<? } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['email']?></th>
							<td><input placeholder="..." name="email" type="text" value="<?=$data['email']?>" /></td>
						</tr>

						<tr>
							<td colspan="2" style="border:0; height:10px"></td>
						</tr>
						<tr>
							<th colspan="2" style="border:0; background:#eee; text-align:left; border-bottom:1px solid #ccc"><?=$lng['Subscription information']?></th>
						</tr>
						
						<tr>
							<th style="width:5%;"><?=$lng['Subscription']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['version']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['Employees']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['employees']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['Period start']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['period_start']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['Period end']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['period_end']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['First name']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['firstname']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['Last name']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['lastname']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['Phone']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['phone']?>" /></td>
						</tr>
						<tr>
							<th style="width:5%;"><?=$lng['email']?> / <?=$lng['Username']?></th>
							<td><input readonly placeholder="..." name="" type="text" value="<?=$client['email']?>" /></td>
						</tr>
					</tbody>
				</table>
				<div style="height:10px"></div>
				<button disabled data-toggle="modal" data-target="#modalSubscription" class="btn btn-primary btn-fr" type="button"><?=$lng['Change subscription']?></button>
			</div>
			
			<div class="tab-content-right">
				<table class="basicTable inputs" border="0">
					<tbody>
						<tr>
							<th class="vat"><?=$lng['Company logo']?><br>
								<small><?=$lng['Max. width']?>: 350px<br /><?=$lng['Max. height']?>: 80px</small>
							</th>
							<td style="padding:8px !important">
								<img class="logoimg" style="max-height:45px; margin-bottom:10px" src="<?=ROOT.$data['logofile'].'?'.time()?>" />
								<button style="margin-right:8px" onclick="$('#complogo').click()" class="btn btn-default btn-xs" type="button"><?=$lng['Select file']?></button><span id="logoname"><?=$lng['No file selected']?></span>
							</td>
						</tr>
					
						<tr>
							<td colspan="2" style="border:0; height:10px"></td>
						</tr>
						<tr>
							<th colspan="2" style="border:0; background:#eee; text-align:left; border-bottom:1px solid #ccc"><?=$lng['System settings']?></th>
						</tr>
						<tr>
							<th><?=$lng['Date format']?></th>
							<td><input style="color:#999" disabled type="text" value="<?=date('d-m-Y')?>&nbsp; (dd-mm-yyyy)" /></td>
						</tr>
						<tr>
							<th><?=$lng['Log-time']?></th>
							<td>
								<select name="logtime" style="width:100%">
									<option <? if($data['logtime']==900){echo 'selected';} ?> value="900">15 <?=$lng['min']?></option>
									<option <? if($data['logtime']==1800){echo 'selected';} ?> value="1800">30 <?=$lng['min']?></option>
									<option <? if($data['logtime']==3600){echo 'selected';} ?> value="3600">60 <?=$lng['min']?></option>
									<option <? if($data['logtime']==86400){echo 'selected';} ?> value="86400">1 <?=$lng['day']?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Employee groups']?></th>
							<td>
								<select name="emp_group" style="width:100%" disabled="disabled">
									<option  <? if($data['emp_group'] == 0){echo 'selected';}?> value="0"><?=$lng['Together (only Staff)']?></option>
									<option  <? if($data['emp_group'] == 1){echo 'selected';}?> value="1"><?=$lng['Separate (Staff & Management)']?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><?=$lng['Latitude']?></th>
							<td><input name="latitude" placeholder="..." id="latitude" type="text" value="<?=$data['latitude']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Longitude']?></th>
							<td><input name="longitude" placeholder="..." id="longitude" type="text" value="<?=$data['longitude']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Theme color']?></th>
							<td>
								<select name="txt_color" style="width:100%">
									<option  <? if($data['txt_color'] == 'red'){echo 'selected';}?> value="red"><?=$lng['Default']?></option>
									<option  <? if($data['txt_color'] == 'blue'){echo 'selected';}?> value="blue"><?=$lng['Blue']?></option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>			
	</div>
	
	<!-- Modal CHANGE SUBSCRIPTION -->
	<div class="modal fade" id="modalSubscription" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?=$lng['Change subscription request']?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form method="post" id="requestForm" class="sform">
						<input name="name" type="hidden" value="<?=$_SESSION['rego']['name']?>" />
						<input name="emp_id" type="hidden" value="<?=$_SESSION['rego']['emp_id']?>" />
						<input name="email" type="hidden" value="<?=$_SESSION['rego']['username']?>" />
						<input name="phone" type="hidden" value="<?=$_SESSION['rego']['phone']?>" />

						<label><?=$lng['Subject']?> <i class="man"></i></label>
						<input name="subject" id="subject" type="text" />
						
						<label><?=$lng['Request']?> <i class="man"></i></label>
						<textarea name="comment" id="comment" rows="5"></textarea>
						
						<div style="height:15px"></div>
						<button id="eSendContact" type="button" class="btn btn-primary btn-fl"><i class="fa fa-paper-plane-o"></i>&nbsp; <?=$lng['Send']?></button>
						<button type="button" class="btn btn-primary btn-fr" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; <?=$lng['Cancel']?></button>
						<div class="clear"></div>
					</form>
					</div>
			  </div>
		 </div>
	</div>

<script>
	
$(document).ready(function() {
	
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
				$('.logoimg').prop('src', e.target.result);
			}
		}
		//$('#message').html('');
		return false;
	});

	function readAttURL(input,id) {
	  if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				var fileExtension = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
				var ext = input.files[0].name.split('.').pop();
				if ($.inArray(ext.toLowerCase(), fileExtension) == -1) {
					//alert("Only  " + fileExtension.join(', ')+ "  formats are allowed !");
					$(id).html('<b style="color:#b00;font-weight:600">Only  ' + fileExtension.join(', ')+ '  formats are allowed !</b>');
				}else{				
					$(id).html(input.files[0].name);
					$("#submitBtn").addClass('flash');
					$("#sAlert").fadeIn(200);
				}
			}
			reader.readAsDataURL(input.files[0]);
	  }
   }
	
	$("#systemForm").submit(function(e){ 
		e.preventDefault();
		$("#submitBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
		var err = false;
		if($("#th_compname").val() == ''){err = true;}
		if($("#tax_id").val() == ''){err = true;}
		if($("#branch").val() == ''){err = true;}
		if($("#th_address").val() == ''){err = true;}
		if($("#th_number").val() == ''){err = true;}
		if($("#th_moo").val() == ''){err = true;}
		if($("#th_subdistrict").val() == ''){err = true;}
		if($("#th_district").val() == ''){err = true;}
		if($("#th_province").val() == ''){err = true;}
		if($("#th_postal").val() == ''){err = true;}
		
		if(err){
			$("body").overhang({
				type: "error",
				message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
				duration: 4,
			})
			setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			return false;
		}
		
		var data = new FormData($(this)[0]);
		$.ajax({
			url: "company/ajax/update_company_settings.php",
			type: 'POST',
			data: data,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			success: function(result){
				//$('#dump').html(result); return false;
				$("#submitBtn").removeClass('flash');
				$("#sAlert").fadeOut(200);
				if(result == 'success'){
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfully']?>',
						duration: 2,
					})
					setTimeout(function(){location.reload();},1500);
				}else{
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
						duration: 4,
					})
				}
				setTimeout(function(){$("#submitBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
			},
			error:function (xhr, ajaxOptions, thrownError){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
					duration: 4,
				})
			}
		});
	});
	
	$('input, textarea').on('keyup', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
	$('select, .checkbox').on('change', function (e) {
		$("#submitBtn").addClass('flash');
		$("#sAlert").fadeIn(200);
	});
			
});

</script>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
