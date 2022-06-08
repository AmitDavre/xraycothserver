<?php
	
	if(!isset($_GET['new'])){$_GET['new'] = 0;}

	$template = array();
	$res = $dba->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template);
	
	$customers = getJsonCustoners($cid, $lang);
	//var_dump($customers);

	$nr = 1;
	$res = $dba->query("SELECT * FROM rego_invoices ORDER BY id DESC LIMIT 1");
	if($row = $res->fetch_assoc()){
		$nr = $row['id']+1;
	}
	//var_dump($nr)	;
	$invNr = $template['inv_prefix'].date($template['inv_date']).'-'.sprintf('%03d', $nr);
	
	$date = langDate(date('d-m-Y'), $lang);
	$due = date('d-m-Y', strtotime($template['due'], strtotime($date)));
	$subdate = date('d-m-Y', strtotime('+1 day', strtotime($date)));
	
	//var_dump($subdate); //exit;
	//var_dump(numberToWords(25689));
	$data = array();
	if(!isset($_GET['id'])){
		$_GET['id'] = 0;
		$customer = '';
		$billing_address = '';
		$clientID = '';
		$body[1]['description'] = $template[$lang.'_description'];
		$body[1]['quantity'] = 1;
		$body[1]['unit'] = '';
		$body[1]['per'] = $template['vat'];
		$body[1]['amount'] = '';
		for($i=2;$i<=10;$i++){
			$body[$i]['description'] = '';
			$body[$i]['quantity'] = '';
			$body[$i]['unit'] = '';
			$body[$i]['per'] = '';
			$body[$i]['amount'] = '';
		}
		$wht_percent = 0;
		$wht_amount = '0.00';
		$wht = '0.00';
		$net = '0.00';
		$discount = '0.00';
		$sub = '0.00';
		$vat = '0.00';
		$total = '0.00';
		$tot_letters = $lng['*** Zero Baht only ***'];
		$wht_letters = $lng['*** Zero Baht only ***'];
	}else{
		$res = $dba->query("SELECT * FROM rego_invoices WHERE id = '".$_GET['id']."'");
		if($row = $res->fetch_assoc()){
			$nr = $row['id'];
			$customer = $row['customer'];
			$billing_address = $row['address'];
			$invNr = $row['inv'];
			$date = $row['inv_date'];
			$due = $row['inv_due'];
			$clientID = $row['clientID'];
			$body = unserialize($row['body']);
			$wht_percent = $row['wht_percent'];
			if($wht_percent != 0){
				$wht_amount = number_format($row['total'],2);
			}else{
				$wht_amount = '0.00';
			}
			$wht = number_format($row['wht_amount'],2);
			$net = number_format($row['net_amount'],2);
			$discount = number_format($row['discount'],2);
			$sub = number_format($row['subtotal'],2);
			$vat = number_format($row['vat'],2);
			$total = number_format($row['total'],2);
			$tot_letters = getWordsFromAmount($row['total'], $lang);
			$wht_letters = getWordsFromAmount($row['net_amount'], $lang);
		}
	}
	//var_dump($body);
	
	$price_table = array();
	$sql = "SELECT price_schedule FROM rego_company_settings";
	if($res = $dba->query($sql)){
		if($row = $res->fetch_assoc()){
			$price_table = unserialize($row['price_schedule']);
		}
	}
	//var_dump($price_table);
	
	$clients = array();
	$res = $dba->query("SELECT * FROM rego_customers");
	while($row = $res->fetch_assoc()){
		$clients[$row['clientID']]['company'] = $row[$lang.'_compname'];
		$clients[$row['clientID']]['email'] = $row['email'];
		$clients[$row['clientID']]['address'] = $row[$lang.'_billing'];
		if(empty($row[$lang.'_billing'])){
			$clients[$row['clientID']]['address'] = $row[$lang.'_address'];
		}
		$clients[$row['clientID']]['wht'] = $row['wht'];
	}
	//var_dump($_GET['id']);
	//var_dump($_GET['new']); //exit;
	
?>

<style>
	table.invoice {
		table-layout:fixed;
	}
	table.invoice td {
		vertical-align:top;
		padding:0 5px;
	}
	table.invoice th {
		vertical-align:top;
		padding:0 5px;
		font-weight:600;
	}
	
	table.invoice .invBody thead th {
		border:1px solid #ccc;
		background:#eee;
		padding:3px 8px;
		white-space:nowrap;
	}
	table.invoice .invBody tbody td {
		border:1px solid #ccc;
		padding:2px 8px;
	}
	table.invoice .invBody tfoot tr {
		border-bottom:0;
	}
	table.invoice .invBody tfoot td, 
	table.invoice .invBody tfoot th {
		border:1px solid #ccc;
		border-bottom:2px solid #bbb;
		padding:2px 8px;
	}
	input[type="text"] {
		border:0 !important;
		padding:2px 8px;
		width:100%;
		xbackground:red;
	}
	table.receiptTable {
	}
	table.receiptTable tbody th {
		padding:3px 8px;
		white-space:nowrap;
	}
	table.receiptTable tbody td {
		padding:2px 8px;
	}
</style>
	
	<h2><i class="fa fa-file-text-o fa-lg"></i>&nbsp;&nbsp;<?=$lng['Invoice']?></h2>
	<div class="main" style="top:115px; padding-right:0">
		<div style="padding:0 0 0 20px" id="dump"></div>
			
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
				<tr>
					<td style="width:20%; vertical-align:top; padding:15px 0 0 0">
						<? if($_GET['id'] == '0' && $_GET['new'] == '0'){?>
						<input id="auto-customer" placeholder="Select customer" type="text" style="width:100%; margin-bottom:5px; border:1px solid #ddd !important; padding:5px 10px">
						<? } if($_GET['id'] == 0){?>
						<select id="subscription" style="width:100%; margin-bottom:5px; border:1px solid #ddd; display:none">
							<option value="">Select subscription</option>
							<? foreach($version as $k=>$v){ if($k != 0){ ?>
							<option <? if(isset($_GET['ver']) && $_GET['ver'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
							<? }} ?>
						</select>
						<button disabled id="submitBtn" onClick="$('#invoiceForm').submit()" style="margin-bottom:5px; margin-top:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Save invoice']?> </button>
						<? } ?>
						
						<button <? if($_GET['id'] == 0){ echo 'disabled';}?> id="print" style="margin-bottom:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-print"></i>&nbsp;&nbsp;<?=$lng['Print invoice']?></button>
						
						<button <? if($_GET['id'] == 0){ echo 'disabled';}?> id="sprint" style="margin-bottom:5px; width:100%; text-align:left; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-paper-plane"></i>&nbsp;&nbsp;<?=$lng['Send by eMail']?></button>
					
					</td>	
					<td style="width:80%; vertical-align:top; padding:0px">
					
					<div style="height:100%; padding:0; overflow-y:auto">
					
					<div style="height:1123px; width:800px; position:relative; padding:35px; box-shadow:0 0 10px rgba(0,0,0,0.2); margin:15px">
			
			<form id="invoiceForm">
				<fieldset disabled>
				<input name="id" type="hidden" value="<?=$nr?>">
				<input name="inv_user" type="hidden" value="<?=$_SESSION['RGadmin']['name']?>">
				<input name="email" type="hidden" value="">
				<input name="wht_percent" type="hidden" value="0">
				<input name="wht_amount" type="hidden" value="0">
				<input name="net_amount" type="hidden" value="0">
				<input name="subscription" type="hidden">
				<table class="invoice" width="100%" border="0">
					<tr style="border-bottom:1px solid #ccc">
						<td><img style="height:80px; margin-bottom:15px" src="<?=$template['logo'].'?'.time()?>" /></td>
						<td style="text-align:right">
							<b style="font-size:16px"><?=$template[$lang.'_company']?></b><br>
							<?=nl2br($template[$lang.'_address'])?>
						</td>
					</tr>
					<tr style="height:30px">
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td style="vertical-align:top; font-size:24px; font-weight:600"><?=$template[$lang.'_inv_type']?></td>
						<td style="padding:0">
							<div style="background:#fff; padding:10px 15px; border:1px solid #ddd; border-radius:5px; width:90%">
								<b><input placeholder="<?=$lng['Customer']?>" style="padding:0; width:100%; background:transparent" name="customer" readonly type="text" value="<?=$customer?>"></b>
								<textarea placeholder="<?=$lng['Billing address']?>" rows="4" style="padding:0; width:100%; resize:none; border:0; background:transparent" name="address"><?=$billing_address?></textarea>
							</div>
						</td>
					</tr>
					<tr style="height:40px">
						<th colspan="2" style="font-size:20px">
							<div style="float:left"><?=$lng['Invoice']?> # :&nbsp;&nbsp;</div><input style="padding:0; width:auto; float:left; font-size:20px" name="inv" type="text" value="<?=$invNr?>">	
						</th>
					</tr>
					<tr>
						<td colspan="2" style="padding:0">
							<table border="0">
								<tr>
									<th style="width:120px"><?=$lng['Invoice date']?> :</th>
									<th style="width:120px"><?=$lng['Due date']?> :</th>
									<th style="width:120px"><?=$lng['Client ID']?> :</th>
								</tr>
								<tr>
									<td><input readonly name="inv_date" class="datepick" placeholder="..." style="width:100px; padding:0; cursor:pointer" type="text" value="<?=$date?>"></td>
									<td><input readonly name="inv_due" class="datepick" placeholder="..." style="width:100px; padding:0; cursor:pointer" type="text" value="<?=$due?>"></td>
									<td><input name="clientID" placeholder="..." style="width:120px; padding:0" type="text" value="<?=$clientID?>"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:0">
						
							<table class="invBody" width="100%" border="0">
								<thead>
								<tr>
									<th class="tac" style="width:30px">#</th>
									<th class="tal"><?=$lng['Description']?></th>
									<th class="tar" style="width:60px"><?=$lng['Quantity']?></th>
									<th class="tar" style="width:100px"><?=$lng['Unit price']?></th>
									<th class="tar" style="width:60px"><?=$lng['VAT']?> %</th>
									<th class="tar" style="width:100px"><?=$lng['Amount']?></th>
								</tr>
								</thead>
								<tbody>
								<? foreach($body as $k=>$v){ ?>
								<tr>
									<td class="tac"><?=$k?></td>
									<td style="padding:0"><input name="body[<?=$k?>][description]" placeholder="..." type="text" value="<?=$v['description']?>" ></td>
									<td style="padding:0"><input name="body[<?=$k?>][quantity]" class="calc qty sel tar numeric" type="text" value="<?=$v['quantity']?>" ></td>
									<td style="padding:0"><input name="body[<?=$k?>][unit]" class="calc price sel tar numeric" type="text" value="<?=$v['unit']?>" ></td>
									<td style="padding:0"><input name="body[<?=$k?>][per]" class="calc percent sel tar numeric" type="text" value="<?=$v['per']?>" ></td>
									<td style="padding:0">
										<input readonly class="famount tar" type="text" value="<? if($v['amount'] > 0){echo $v['amount'];}?>" >
										<input name="body[<?=$k?>][amount]" class="amount" type="hidden" value="0" >
									</td>
								</tr>
								<? } ?>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="3" rowspan="4" style="xborder-bottom-width:1px !important; padding:0 0 5px 0; vertical-align:bottom">
										<table width="100%" border="0">
											<tr>
												<td colspan="2" style="border:0; font-size:14px">
													<? if($_GET['id'] == 0){ ?>
													<label><input id="check_deduct" type="checkbox" class="checkbox" /><span>&nbsp;<?=$lng['Deduct WHT']?> :&nbsp;&nbsp;<b><span id="wht_percent"><?=$wht_percent?></span></b>%&nbsp;&nbsp;<?=$lng['on']?>&nbsp;&nbsp;<b><span id="wht_amount"><?=$wht_amount?></span></b>&nbsp;&nbsp;<?=$lng['is']?>&nbsp;&nbsp;<b><span id="wht"><?=$wht?></span></b>&nbsp;&nbsp;<?=$lng['Baht']?></span></label>
													<? }else{ ?>
													<?=$lng['Deduct WHT']?> :&nbsp;&nbsp;<b><span id="wht_percent"><?=$wht_percent?></span></b>%&nbsp;&nbsp;<?=$lng['on']?>&nbsp;&nbsp;<b><span id="wht_amount"><?=$wht_amount?></span></b>&nbsp;&nbsp;<?=$lng['is']?>&nbsp;&nbsp;<b><span id="wht"><?=$wht?></span></b>&nbsp;&nbsp;<?=$lng['Baht']?>
													<? } ?>
												</td>
											</tr>
											<tr>
												<th style="width:1px; white-space:nowrap; font-size:16px; border:0"><?=$lng['Net to Pay']?> :</th>
												<th style="font-size:16px; border:0"><span id="net"><?=$net?></span> <?=$lng['Baht']?></th>
											</tr>
										  	<tr>
											 	<td colspan="2" style="border:0">
													<input style="padding:0" readonly id="wht_letters" type="text" value="<?=$wht_letters?>">
												</td>
										  	</tr>
										</table>
									</td>
									<th colspan="2"><?=$lng['Discount']?></th>
									<th class="tar"><span id="discount"><?=$discount?></span>
										<input name="discount" type="hidden" value="0" >
									</th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Sub total']?></th>
									<th style="padding:0">
										<input id="sub" readonly class="tar" type="text" value="<?=$sub?>" >
										<input name="subtotal" type="hidden" value="0" >
									</th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['VAT']?></th>
									<th style="padding:0">
										<input id="vat" readonly class="tar" type="text" value="<?=$vat?>" >
										<input name="vat" type="hidden" value="0" >
									</th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Grand total']?></th>
									<th style="padding:0">
										<input id="total" readonly class="tar" type="text" value="<?=$total?>" >
										<input name="total" type="hidden" value="0" >
									</th>
								</tr>
								<tr>
									<td colspan="6" class="tar" style="border-bottom-width:1px; padding:4px 8px">
										<input style="padding:0" readonly id="letters" class="tar" type="text" value="<?=$tot_letters?>">
									</td>
								</tr>
								</tfoot>
							</table>
								
						</td>
					</tr>
				</table>

				<div style="height:20px"></div>
				
				<table class="receiptTable" border="0" style="width:100%; white-space:nowrap; border:1px solid #ddd">
					<tr style="background:#eee; border-bottom:1px solid #ddd">
						<td colspan="4"><b><?=$lng['Payment details']?></b></td>
					</tr>
					<tr style="border-bottom:1px solid #ddd">
						<td style="width:1px; vertical-align:baseline; text-align:right"><b><?=$lng['Pay to']?> : </b></td>
						<td style="vertical-align:baseline; width:50%; padding-left:0; border-right:1px solid #ddd">
							<?=nl2br($template[$lang.'_pay_to'])?>
						</td>
						<td style="width:1px; vertical-align:baseline; text-align:right">
							<b><?=$lng['Customer']?> : </b><br>
							<b><?=$lng['Invoice']?> : </b><br>
							<b><?=$lng['Amount due']?> : </b><br>
							<b><?=$lng['Due date']?> : </b>
						</td>
						<td style="vertical-align:baseline; width:50%; padding-left:0">
							<b id="pd_customer">...</b><br>
							<span class="docNr"><?=$invNr?></span><br>
							<span id="pd_amount">0.00</span><br>
							<?=$due?><br>
						</td>
					</tr>
					<tr>
						<td style="width:1px; vertical-align:top; text-align:right"><b><?=$lng['Bank']?> : </b></td>
						<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
							<?=nl2br($template[$lang.'_bank_details'])?>
						</td>
						<td colspan="2" style="vertical-align:baseline; position:relative">
							<b><?=$lng['Athorized signature']?> :</b>
							<div style=""><img class="invSignature" style="width:160px; max-height:80px" src="<?=$template['signature'].'?'.time()?>" /></div>
							<div style=" position:absolute; bottom:5px; right:10px"><img class="sStamp" style="height:120px; max-width:200px" src="<?=$template['stamp'].'?'.time()?>" /></div>
						
						</td>
					</tr>
				</table>
				
				<div class="footer" style="position:absolute; bottom:30px; left:30px; right:30px; padding-top:5px">
					<div style="text-align:center; padding-top:5px; border-top:1px solid #ccc">
						<?=$template[$lang.'_footer']?> 
					</div>
				</div>
				
				</fieldset>
			</form>				

					</td>
				</tr>
			</table>	
		</div>
	</div>

	<script>

		$(document).ready(function() {
			
			var nr = <?=json_encode($nr)?>;
			var customers = <?=json_encode($customers)?>;
			var clients = <?=json_encode($clients)?>;
			var id = <?=json_encode($_GET['new'])?>;
			var price = <?=json_encode($price_table)?>;
			var subdate = <?=json_encode($subdate)?>;
			var discount = 0;
			
			$('#auto-customer').devbridgeAutocomplete({
				 lookup: customers,
				 paramName: "data",
				 onSelect: function (suggestion) {
				 	//alert(suggestion.id)
				 	$('input[name="customer"]').val(suggestion.data);
				 	$('#pd_customer').html(suggestion.data);
				 	$('input[name="clientID"]').val((suggestion.id).toUpperCase());
				 	$('input[name="email"]').val(suggestion.email);
				 	$('textarea[name="address"]').val(suggestion.address);
					if(suggestion.wht == 'Y'){
				 		$('#check_deduct').prop('checked', true);
					}else{
				 		$('#check_deduct').prop('checked', false);
					}
				 	$('#withholdingtax').val(suggestion.wht);
				 	$('#invoiceForm fieldset').prop('disabled', false);
				 	$('#submitBtn').prop('disabled', false);
				 	$('#subscription').show();
					
				 }
			});	
		
			if(id != 0){
				$('input[name="customer"]').val(clients[id]['company']);
				$('#pd_customer').html(clients[id]['company']);
				$('input[name="clientID"]').val((id).toUpperCase());
				$('input[name="email"]').val(clients[id]['email']);
				$('textarea[name="address"]').val(clients[id]['address']);
				if(clients[id]['wht'] == 'Y'){
					$('#check_deduct').prop('checked', true);
				}else{
					$('#check_deduct').prop('checked', false);
				}
				$('#withholdingtax').val(clients[id]['wht']);
				
				$('#invoiceForm fieldset').prop('disabled', false);
				$('#submitBtn').prop('disabled', false);
				$('#subscription').show();
			}
			
			$('#subscription').on('change', function(e){
				var ppy = parseFloat(price[this.value]['price_year']);
				discount = parseFloat(price[this.value]['discount']);
				//alert(discount)
				//$('#ppy').html(ppy.format(2))
				$('input[name="body[1][unit]"]').val(ppy);
				$('input[name="discount"]').val(discount);
				$('input[name="subscription"]').val(this.value);
				$('#discount').html(discount.format(2));
				$('input[name="body[2][description]"]').val('<?=$lng['Subscription']?> : ' + $("#subscription option:selected").text());
				$('input[name="body[3][description]"]').val('<?=$lng['Max employees']?> : ' + price[this.value]['max_employees']);
				$('input[name="body[4][description]"]').val('<?=$lng['Subscription date']?> : ' + subdate);
				$('.calc').trigger('change')
			}) 

			$('#checkNote').on("change", function(e) {
				if(this.checked){
					$('textarea[name="inv_note"]').show().prop('disabled', false);
				}else{
					$('textarea[name="inv_note"]').hide().prop('disabled', true);
				}
			});
			
			$('#check_deduct').on("change", function(e) {
				$('input[name="body[1][quantity]"]').trigger('change');
			});
			
			$(document).on("click", "#print", function(e) {
				window.open(AROOT+'billing/print_invoice.php?nr='+nr, '_blank')
			});
			$(document).on("click", "#sprint", function(e) {
				$("#sprint i").removeClass('fa-paper-plane').addClass('fa-rotate-right fa-spin');
				$.ajax({
					url: AROOT+"billing/ajax/ajax_print_invoice.php",
					data: {nr: nr},
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;eMail send successfuly<? //=$lng['xxx']?>',
								duration: 2,
							})
							//$("#print").prop('disabled', false);
							//$("#sprint").prop('disabled', false);
							setTimeout(function(){$("#sprint i").removeClass('fa-rotate-right fa-spin').addClass('fa-paper-plane');},500);	
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
								duration: 4,
								closeConfirm: true
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
			
			function Calculate($this, $wht){
				var qty = $($this).closest('tr').find('.qty').val();
				var price = $($this).closest('tr').find('.price').val();
				var sub = 0;
				var vat = 0;
				var amount = qty * price;
				if(amount > 0){
					$($this).closest('tr').find('.famount').val(amount.format(2));
				}
				$($this).closest('tr').find('.amount').val(amount);
				//alert(sub)
				$.each($('.amount'), function(){
					if(this.value !== 0){
						//alert(this.value)
						var percent = $(this).closest('tr').find('.percent').val();
						sub += parseFloat(this.value);
						if(percent){
							vat += (parseFloat(this.value) * parseFloat(percent))/100;
						}
					}
				})
				//alert(sub)
				//var discount = $('input[name="discount"]').val();
				//alert(discount); return false;
				sub = sub - discount;
				var total = sub + vat;
				var wht = 0;
				//$('#dump').html(qty+'-'+price+'-'+vat+' -- '+sub)
				$('#sub').val(sub.format(2));
				$('#vat').val(vat.format(2));
				$('#total').val(total.format(2));
				$('input[name="subtotal"]').val(sub);
				$('input[name="vat"]').val(vat);
				$('input[name="total"]').val(total);
				//alert($wht)
				if($wht){
					$('#wht_percent').html(3);
					$('#wht_amount').html(sub.format(2));
					wht = sub*3/100;
					$('#wht').html(wht.format(2));
					$('#net').html((total-wht).format(2));
					$('input[name="wht_percent"]').val(3);
					$('input[name="wht_amount"]').val(wht);
					$('input[name="net_amount"]').val(total-wht);
				}else{
					$('#wht_percent').html(0);
					$('#wht_amount').html('0.00');
					$('#wht').html('0.00');
					$('#net').html((total-wht).format(2));
					$('input[name="wht_percent"]').val(0);
					$('input[name="wht_amount"]').val(0);
					$('input[name="ne_amountt"]').val(total-wht);
				}
				$('#pd_amount').html((total-wht).format(2));
				$.ajax({
					url: AROOT+"billing/ajax/get_words_from_amount.php",
					data: {amount: total},
					success: function(data){
						$('#letters').val(data);
					}
				})
				$.ajax({
					url: AROOT+"billing/ajax/get_words_from_amount.php",
					data: {amount: (total-wht)},
					success: function(data){
						$('#wht_letters').val(data);
					}
				})
			}
			
			$(document).on('change', '.calc', function(){
				Calculate(this, $('#check_deduct').prop('checked'));
			});
			
			/*$(document).on('change', '#withholdingtax', function(){
				$('input[name="body[quantity][1]"]').trigger('change');
			});*/
			
			$("#invoiceForm").submit(function(e){ 
				e.preventDefault();
				var data = $(this).serialize();
				$.ajax({
					url: AROOT+"billing/ajax/save_invoice.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							$("#print").prop('disabled', false);
							$("#sprint").prop('disabled', false);
							//setTimeout(function(){location.reload();},2000);	
						}else if(result == 'zero'){
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Grand total is zero ?',
								duration: 4,
								closeConfirm: true
							})
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+data.result,
								duration: 4,
								closeConfirm: true
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
		
		
		
		});
	
	</script>
						














