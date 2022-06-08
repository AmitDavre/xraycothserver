<?php

	if($_SESSION['RGadmin']['access']['customer']['add'] == 0){
		//echo '<div class="msg_error">You have no permission to add new clients</div>'; exit;
	}
	//var_dump(getFirstCustomerID()); exit;
	
	$msg = '';
	//$clientID = getNewCustomerID();
	//echo $clientID;
	
	$standard = array();
	$sql = "SELECT standard FROM rego_company_settings";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$standard = unserialize($row['standard']);
		}
	}else{
		echo mysqli_error($dba);
	}
	//var_dump($standard); exit;
	
	$demo = false;
	if($demo){
		$data['firstname'] = "Willy";
		$data['lastname'] = "Thaimans";
		$data['phone'] = "092 709.77.66";
		$data['line_id'] = "line ID";
		$data['email'] = "willy@xrayict.com";
		$data['password'] = generateStrongPassword(8, false, 'luds');
		$data['th_compname'] = "REGO Demo Customer Co. TH";
		$data['en_compname'] = "REGO Demo Customer Co. EN";
		$data['tax_id'] = '1234567890123';
		$data['certificate'] = 1;
		$data['date'] = date('d-m-Y @ H:i:s');
		$data['remarks'] = $lng['Setup by Admin'];

		$data['address'] = '222/149 Mo 7';
		$data['subdistrict'] = 'Nongprue';
		$data['district'] = 'Banglamung';
		$data['province'] = 'Chonburi';
		$data['postcode'] = '20150';
		
		$data['version'] = 200;
		$data['employees'] = $standard[$data['version']]['max_employees'];
		$data['emp_platform'] = $standard[$data['version']]['mobile'];
		$data['period_start'] = date('d-m-Y');
		if($data['version'] == '0'){
			$data['period_end'] = date('d-m-Y', strtotime('+1 months', strtotime($data['period_start'])));
			//$data['price'] = 0;
			//$data['discount'] = 0;
			//$data['vat'] = 0;
			//$data['wht'] = 0;
			//$data['net'] = 0;
			//$data['price_year'] = 0;
		}else{
			$data['period_end'] = date('d-m-Y', strtotime('+12 months', strtotime($data['period_start'])));
			//$data['price'] = $price_schedule[$data['version']]['price_year'];
			//$data['discount'] =  $price_schedule[$data['version']]['discount'];
			//$data['price_year'] = $data['price'] - $data['discount'];
			//$data['vat'] = $data['price_year']*0.07;
			//$data['wht'] = 0;
			//if($data['certificate'] == 1){
				//$data['wht'] = $data['price_year']*0.03;
			//}
			//$data['net'] = $data['price_year'] + $data['vat'] - $data['wht']; 
		}
		$data['payment_type'] = 'transfer';
	}else{
		$data['firstname'] = "";
		$data['lastname'] = "";
		$data['phone'] = "";
		$data['line_id'] = "";
		$data['email'] = "";
		$data['password'] = generateStrongPassword(8, false, 'luds');
		$data['th_compname'] = "";
		$data['en_compname'] = "";
		$data['tax_id'] = '';
		$data['certificate'] = 1;
		$data['date'] = date('d-m-Y @ H:i:s');
		$data['remarks'] = $lng['Setup by Admin'];

		$data['address'] = '';
		$data['subdistrict'] = '';
		$data['district'] = '';
		$data['province'] = '';
		$data['postcode'] = '';
		
		$data['version'] = 200;
		$data['employees'] = $standard[$data['version']]['max_employees'];
		$data['emp_platform'] = $standard[$data['version']]['mobile'];
		$data['period_start'] = date('d-m-Y');
		$data['period_end'] = date('d-m-Y', strtotime('+1 year', strtotime($data['period_start'])));
		$data['price'] = 0;
		/*$data['discount'] = 0;
		$data['vat'] = 0;
		$data['wht'] = 0;
		$data['net'] = 0;
		$data['price_year'] = 0;*/
		$data['payment_type'] = 'transfer';
	}
	
?>
	
	<h2><i class="fa fa-user-plus"></i>&nbsp; <?=$lng['New Customer Setup']?></h2>	
	
	<div class="main" style="overflow:hidden">
	
		<div class="pannel-left">
			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link active" data-target="#tab_subscriber" data-toggle="tab"><?=$lng['Subscriber']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_address" data-toggle="tab"> <?=$lng['Billing address']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_subscription" data-toggle="tab"><?=$lng['Subscription']?></a></li>
			</ul>
			<form id="new_client" method="post" style="height:100%">
				<input style="display:none" onClick="return false;" type="submit" />
				<input name="payment_type" type="hidden" value="<?=$data['payment_type']?>" />
				<input name="emp_platform" type="hidden" value="<?=$data['emp_platform']?>" />
				<input name="admin" type="hidden" value="admin" />
				<div class="tab-content" style="height:calc(100% - 40px);">
				
					<div class="tab-submit">
						<button class="btn btn-primary" id="subButton" type="submit"><i class="fa fa-save"></i>&nbsp; <?=$lng['Create new customer']?></button>
					</div>
					
					<div class="tab-pane show active" id="tab_subscriber">
						<table class="basicTable inputs" border="0">
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['First name']?> : </th>
									<td><input name="firstname" type="text" value="<?=$data['firstname']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Last name']?> : </th>
									<td><input name="lastname" type="text" value="<?=$data['lastname']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Phone']?> : </th>
									<td><input name="phone" type="text" value="<?=$data['phone']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Line ID']?> : </th>
									<td><input name="line_id" type="text" value="<?=$data['line_id']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['email']?> /  <?=$lng['Username']?></th>
									<td><input name="email" id="email" type="text" value="<?=$data['email']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Password']?> : </th>
									<td><input name="password" id="password" type="text" value="<?=$data['password']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Company name']?> <?=$lng['Thai']?></th>
									<td><input name="th_compname" type="text" value="<?=$data['th_compname']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Company name']?> <?=$lng['English']?></th>
									<td><input name="en_compname" type="text" value="<?=$data['en_compname']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Tax ID no.']?></th>
									<td><input class="tax_id_number" name="tax_id" type="text" value="<?=$data['tax_id']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['WHT Certificate']?></th>
									<td>
										<select name="certificate" id="certificate" style="width:100%">
											<? foreach($noyes01 as $k=>$v){ ?>
											<option <? if($data['certificate'] == $k){echo 'selected="selected"';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th><?=$lng['Subscription date']?></th>
									<td><input name="date" type="text" value="<?=$data['date']?>" /></td>
								</tr>
								<tr>
									<th valign="top"><?=$lng['Remarks']?></th>
									<td><textarea name="remarks" rows="5"><?=$data['remarks']?></textarea></td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="tab-pane" id="tab_address">
						<table class="basicTable inputs" border="0">
							<tbody>
								<tr>
									<th valign="top"><i class="man"></i><?=$lng['Address']?></th>
									<td><input name="address" type="text" value="<?=$data['address']?>" /></td>
								</tr>
								<tr>
									<th valign="top"><?=$lng['Sub district']?></th>
									<td><input name="subdistrict" type="text" value="<?=$data['subdistrict']?>" /></td>
								</tr>
								<tr>
									<th valign="top"><i class="man"></i><?=$lng['District']?></th>
									<td><input name="district" type="text" value="<?=$data['district']?>" /></td>
								</tr>
								<tr>
									<th valign="top"><i class="man"></i><?=$lng['Province']?></th>
									<td><input name="province" type="text" value="<?=$data['province']?>" /></td>
								</tr>
								<tr>
									<th valign="top"><i class="man"></i><?=$lng['Postal code']?></th>
									<td><input name="postcode" type="text" value="<?=$data['postcode']?>" /></td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="tab-pane" id="tab_subscription">
						<table class="basicTable inputs" border="0">
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['Subscription']?></th>
									<td>
										<select name="version" id="version" style="width:100%">
											<? foreach($version as $k=>$v){ ?>
											<option <? if($data['version'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th style="width:5%"><i class="man"></i><?=$lng['Employees']?></th>
									<td><input class="sel numeric" name="employees" type="text" value="<?=$data['employees']?>" /></td>
								</tr>
								<!--<tr>
									<th>Price<? //=$lng['Price per Year']?></th>
									<td><input class="sel numeric" name="price" type="text" value="<?=$data['price']?>" /></td>
								</tr>
								<tr>
									<th>Discount<? //=$lng['Discount']?></th>
									<td><input class="sel numeric" name="discount" type="text" value="<?=$data['discount']?>" /></td>
								</tr>
								<tr>
									<th>VAT<? //=$lng['VAT']?></th>
									<td><input class="sel numeric" name="vat" type="text" value="<?=$data['vat']?>" /></td>
								</tr>
								<tr>
									<th>WHT<? //=$lng['WHT']?></th>
									<td><input class="sel numeric" name="wht" type="text" value="<?=$data['wht']?>" /></td>
								</tr>
								<tr>
									<th>Net to pay<? //=$lng['Net to pay']?></th>
									<td><input class="sel numeric" name="net" type="text" value="<?=$data['net']?>" /></td>
								</tr>-->
								<!--<tr>
									<th><?=$lng['Payment type']?></th>
									<td><input readonly type="text" value="<?=$ptype[$data['payment_type']]?>" /></td>
								</tr>-->
								<tr>
									<th><i class="man"></i><?=$lng['Period start']?></th>
									<td><input readonly name="period_start" type="text" value="<?=$data['period_start']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Period end']?></th>
									<td><input readonly name="period_end" type="text" value="<?=$data['period_end']?>" /></td>
								</tr>
								<!--<tr>
									<th>Price Year<? //=$lng['Price Year']?></th>
									<td><input class="sel numeric" name="price_year" type="text" value="<?=$data['price_year']?>" /></td>
								</tr>-->
							</tbody>
						</table>
					</div>
													
				</div>
			</form>
		
		</div>
		
		<div class="pannel-right">
			<div id="modal_msg" style="height:calc(100% - 5px); overflow-y:auto"></div>
		</div>
				
	</div>
		
	<script type="text/javascript">
		
		var height = window.innerHeight-265;

		$(document).ready(function() {

			var standard = <?=json_encode($standard)?>;
			var password = <?=json_encode($data['password'])?>;

			/*$("#same").on('click', function(e){
				if(this.checked){
					$('textarea[name="th_billing"]').val($('textarea[name="th_address"]').val())
					$('textarea[name="en_billing"]').val($('textarea[name="en_address"]').val())
				}else{
					$('textarea[name="th_billing"]').val('')
					$('textarea[name="en_billing"]').val('')
				}
			})*/ 
			
			$("#email").on('change', function(e){
				var user = this.value;
				$.ajax({
					url: "ajax/check_if_user_exist.php",
					data: {user: user},
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'exist'){
							$('#password').val('');
							$('#password').prop('placeholder', 'User exist already - Use existing password');
							$('#password').prop('readonly', true);
						}else{
							$('#password').val(password);
							$('#password').prop('placeholder', '');
							$('#password').prop('readonly', false);
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			})
			
			$("#certificate").on('change', function(e){
				$("#version").trigger('change');
			})
			$("#version").on('change', function(e){
				/*var price = parseFloat(standard[this.value]['price_year']);
				var discount = parseFloat(standard[this.value]['discount']);
				var price_year = price - discount;
				var vat = (price_year * 0.07).toFixed(2);
				var wht = 0;
				if($('#certificate').val() == 1){
					var wht = (price_year * 0.03).toFixed(2);
				}
				var net = parseFloat(price_year) + parseFloat(vat) - parseFloat(wht);*/
				$('input[name="employees"]').val(standard[this.value]['max_employees']);
				$('input[name="emp_platform"]').val(standard[this.value]['mobile']);
				
				if(this.value == 0){
					$('input[name="period_end"]').val(moment($('input[name="period_start"]').val(), "DD-MM-YYYY").add('months', 1).format('DD-MM-YYYY'));
				}else{
					$('input[name="period_end"]').val(moment($('input[name="period_start"]').val(), "DD-MM-YYYY").add('years', 1).format('DD-MM-YYYY'));
				}
				//alert(moment("$('input[name="period_start"]').val()", "DD-MM-YYYY").add('years', 1).format('DD-MM-YYYY'));
				//alert($('input[name="period_start"]').val())
				
				//$('input[name="price"]').val(price);
				//$('input[name="discount"]').val(discount);
				//$('input[name="vat"]').val(vat);
				//$('input[name="wht"]').val(wht);
				//$('input[name="net"]').val(net);
				//$('input[name="price_year"]').val(price_year);
			}) 
			
			$("#new_client").on('submit', function(e){ 
				e.preventDefault();
				var err = false;
				if($('input[name="firstname"]').val() == ''){err = true;}
				if($('input[name="lastname"]').val() == ''){err = true;}
				if($('input[name="email"]').val() == ''){err = true;}
				//if($('input[name="password"]').val() == ''){err = true;}
				if($('input[name="th_compname"]').val() == ''){err = true;}
				if($('input[name="en_compname"]').val() == ''){err = true;}
				if($('input[name="address"]').val() == ''){err = true;}
				if($('input[name="district"]').val() == ''){err = true;}
				if($('input[name="province"]').val() == ''){err = true;}
				if($('input[name="postcode"]').val() == ''){err = true;}
				if($('input[name="employees"]').val() == 0){err = true;}
				if(err){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
					})
					return false
				}
				var formData = $(this).serialize();
				
				$("#subButton i").removeClass('fa-save').addClass('fa-rotate-right fa-spin');
				$("#subButton").prop('disabled', true);
				$.ajax({
					url: ROOT+"admin/ajax/create_new_customer.php",
					data: formData,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'exist'){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;System user email exist already<? //=$lng['Please fill in required fields']?>',
								duration: 4,
							})
							$("#subButton i").removeClass('fa-rotate-right fa-spin').addClass('fa-save');
							return false
						}else if(result == 'email'){
							$("body").overhang({
								type: "error",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please enter a valid email address']?>',
								duration: 4,
							})
							$("#subButton i").removeClass('fa-rotate-right fa-spin').addClass('fa-save');
							return false
						}else{
							//$('#dump').html(result);
							$("#modal_msg").html(result);
							$("#subButton i").removeClass('fa-rotate-right fa-spin').addClass('fa-save');
							//$("#subButton").prop('disabled', false);
							//location.reload();
						}
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 4,
						})
					}
				});
			});
			
			/*var activeTab = localStorage.getItem('addCustomer');
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				localStorage.setItem('addCustomer', $(e.target).data('target'));
			});
			if(activeTab){
				$('#myTab a[data-target="' + activeTab + '"]').tab('show');
			}else{
				$('#myTab a[data-target="#tab_subscriber"]').tab('show');
			}*/
	
			/*$('.autoheight').css('min-height', height);
			$(window).on('resize', function(){
				var height = window.innerHeight-265;
				$('.autoheight').css('min-height', height);
			});*/	
			
		});

	</script>








	
