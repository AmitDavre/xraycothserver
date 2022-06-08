<?

	$customer = array();
	$remaining = 0;
	$res = $dbx->query("SELECT * FROM rego_customers WHERE clientID = '".$_SESSION['rego']['cid']."'");
	if($row = $res->fetch_assoc()){
		$customer = $row;
		$diff = strtotime($row['period_end']) - strtotime(date('d-m-Y'));
		$remaining = floor($diff / (60*60*24));
		$remaining_days = $remaining;
		if($remaining == 1){$remaining_days .= ' day';}else{$remaining_days .= ' days';}
	}
	//var_dump($customer); exit;
	
	//var_dump($_SESSION['rego']['cid']); //exit;
	$users = array();
	$res = $dbx->query("SELECT * FROM rego_company_users WHERE type = 'sys' AND access LIKE '%".$_SESSION['rego']['cid']."%'");
	while($row = $res->fetch_assoc()){
		$users[$row['id']] = $row['username'];
	}
	//var_dump($users); //exit;
	
?>	

<!-- COMPANY FORM /////////////////////////////////////////////////////////////////////////////-->
<div id="mr_company" style="border:0px solid red; position:absolute; top:90px; left:0; right:0; bottom:5px">

	<div style="width:30%; height:100%; float:left">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<form id="subscriberForm">
			<table class="basicTable inputs" border="0" style="margin-bottom:8px">
				<thead>
					<tr style="line-height:110%">
						<th colspan="4"><?=$lng['Subscriber']?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><i class="man"></i><?=$lng['First name']?></th>
						<td><input name="firstname" type="text" value="<?=$customer['firstname']?>"></td>
					</tr>
					<tr>
						<th><i class="man"></i><?=$lng['Last name']?></th>
						<td><input name="lastname" type="text" value="<?=$customer['lastname']?>"></td>
					</tr>
					<tr>
						<th><?=$lng['Phone']?></th>
						<td><input name="phone" type="text" value="<?=$customer['phone']?>"></td>
					</tr>
					<tr>
						<th><?=$lng['Line ID']?></th>
						<td><input name="line_id" type="text" value="<?=$customer['line_id']?>"></td>
					</tr>
					<tr>
						<th><i class="man"></i><?=$lng['email']?></th>
						<td>
							<select name="email" style="width:100%">
								<? foreach($users as $v){ ?>
								<option <? if($v == $_SESSION['rego']['cid']){echo 'selected';}?> value="<?=$v?>"><?=$v?></option>
								<? } ?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<button id="subscribeBtn" style="margin-bottom:10px" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update']?> Subscriber</button>
			</form>
		</div>
	</div>
		
	<div style="width:35%; height:100%; float:left; border-left:1px solid #ccc">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">	
			<form id="billingForm">
			<table class="basicTable inputs" border="0" style="margin-bottom:8px">
				<thead>
					<tr style="line-height:110%">
						<th colspan="4"><?=$lng['Billing information (REGO Admin)']?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><i class="man"></i><?=$lng['Company']?></th>
						<td style="padding:0"><input name="compname" type="text" value="<?=$customer[$lang.'_compname']?>"></td>
					</tr>
					<tr>
						<th valign="top"><i class="man"></i><?=$lng['Address']?></th>
						<td style="padding:0"><textarea name="address" rows="4"><?=$customer[$lang.'_address']?></textarea></td>
					</tr>
					<tr>
						<th valign="top">
							<i class="man"></i><?=$lng['Billing address']?>
							<br><label style="color:#a00"><input id="same" type="checkbox" class="checkbox style-0"><span><?=$lng['Same above']?></span></label>
							</th>
						<td style="padding:0"><textarea name="billing" rows="4"><?=$customer[$lang.'_billing']?></textarea></td>
					</tr>
					<tr>
						<th id="tax_mandatory"><i class="man"></i><?=$lng['Tax ID no.']?></th>
						<td style="padding:0"><input class="tax_id_number" name="tax_id" type="text" value="<?=$customer['tax_id']?>"></td>
					</tr>
					<tr>
						<th><i class="man"></i><?=$lng['Withholding Tax']?></th>
						<td style="padding:0">
							<select name="certificate" style="width:100%">
								<!--<option disabled selected value=""><?=$lng['Select']?></option>-->
								<option <? if($customer['certificate'] == 'Y'){echo 'selected';}?> value="Y"><?=$lng['Need certificate']?></option>
								<option <? if($customer['certificate'] == 'N'){echo 'selected';}?> value="N"><?=$lng["Don't need certificate"]?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<button id="billingBtn" style="" class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Update Billing info']?></button>
			</form>
		</div>
	</div>

	<div style="width:35%; height:100%; float:left; border-left:1px solid #ccc">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">	
			<table class="basicTable" border="0" style="margin-bottom:10px">
				<thead>
					<tr style="line-height:110%">
						<th colspan="4"><?=$lng['Current Subscription']?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><?=$lng['Subscription']?></th>
						<td><?=$version[$customer['version']]?></td>
					</tr>
					<tr>
						<th><?=$lng['Joining date']?></th>
						<td><?=$customer['joiningdate']?></td>
					</tr>
					<tr>
						<th><?=$lng['Max employees']?></th>
						<td><?=$customer['employees']?></td>
					</tr>
					<tr>
						<th><?=$lng['Price per Year']?></th>
						<td><?=number_format($customer['price_year'],2)?></td>
					</tr>
					<tr>
						<th><?=$lng['Period start']?></th>
						<td><?=$customer['period_start']?></td>
					</tr>
					<tr>
						<th><?=$lng['Period end']?></th>
						<td><?=$customer['period_end']?></td>
					</tr>
					<tr>
						<th><?=$lng['Days left']?></th>
						<td><?=$remaining?></td>
					</tr>
					<tr>
						<th><?=$lng['Status']?></th>
						<td><?=$client_status[$customer['status']]?></td>
					</tr>
				</tbody>
			</table>
			<div id="dump2"></div>
		</div>
	</div>

</div>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$("#subscriberForm").submit(function (e) {
			e.preventDefault();
			$("#subscribeBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var err = 0;
			if($('input[name="firstname"]').val() == ''){err = 1;}
			if($('input[name="lastname"]').val() == ''){err = 1;}
			if(err){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#subscribeBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}
			
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/update_subscriber.php",
				data: formData,
				success: function(response){
					//$('#dump2').html(response); return false;
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
							duration: 2,
							closeConfirm: true
						})
						setTimeout(function(){window.location.href = "index.php?mn=5";},2000);
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$("#subscribeBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){$("#subscribeBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				}
			});
		});
		
		$("#billingForm").submit(function (e) {
			e.preventDefault();
			$("#billingBtn i").removeClass('fa-save').addClass('fa-refresh fa-spin');
			var err = 0;
			if($('input[name="compname"]').val() == ''){err = 1;}
			if($('textarea[name="address"]').val() == ''){err = 1;}
			if($('textarea[name="billing"]').val() == ''){err = 1;}
			if($('input[name="tax_id"]').val() == ''){err = 1;}
			if($('select[name="certificate"]').val() == null){err = 1;}
			if(err){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}
			
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/update_billing_info.php",
				data: formData,
				success: function(response){
					//$('#dump2').html(response); return false;
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
							duration: 2,
							closeConfirm: true
						})
						$("#overlay").fadeOut(400);
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
							duration: 4,
							closeConfirm: true
						})
					}
					setTimeout(function(){$("#billingBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
					setTimeout(function(){$("#billingBtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				}
			});
		});
		
		$("#same").on('click', function(e){
			if(this.checked){
				$('textarea[name="billing"]').val($('textarea[name="address"]').val())
			}else{
				$('textarea[name="billing"]').val('')
			}
		}) 
			
		/*$("#iam").on('change', function(e){
			if(this.value == '1'){
				$('select[name="wht"]').val(1)
				//$('#tax_mandatory i').addClass('man')
			}else{
				$('select[name="wht"]').val(0)
				//$('#tax_mandatory i').removeClass('man')
			}
		}) */
		
	});
	
</script>

















