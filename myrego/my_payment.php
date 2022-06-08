<?
	
	//var_dump($price_table); exit;
	if(!isset($_GET['status'])){$_GET['status'] = 0;}
	$template = array();
	$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template); exit;
	
	$xtax = str_replace('-','',$template['tax_id']);
	if(strlen($xtax)!== 13){$xtax = '?????????????';}
	$xtax = str_split($xtax);
	$xcompany = $template[$lang.'_company'];
	$xaddress = $template[$lang.'_address'];
	
	//var_dump($xcompany); //exit;
	//var_dump($xaddress); //exit;
	//var_dump(serialize($xtax)); exit;
	
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
	$date_start = date('01-m-Y', strtotime(date('d-m-Y', strtotime($customer['period_start']))));
	$date_end = date('d-m-Y', strtotime(date('d-m-Y', strtotime($customer['period_end']))));
	//if($customer['version'] != 0){unset($version[0]);}
	//var_dump($customer); exit;
	
	$company = array();
	$res = $dbc->query("SELECT * FROM ".$cid."_company_settings");
	if($row = $res->fetch_assoc()){
		$company = $row;
	}
	//var_dump($customer); exit;
	
	$ctax = str_replace('-','',$customer['tax_id']);
	if(strlen($ctax)!== 13){$ctax = '?????????????';}
	$ctax = str_split($ctax);
	$ccompany = $customer[$lang.'_compname'];
	$caddress = $customer[$lang.'_address'];
	
	$invoice = array();
	if($res = $dbx->query("SELECT * FROM rego_invoices WHERE cid = '".$cid."' AND status = 1 OR status = 2 ORDER BY id DESC LIMIT 1")){
		if($row = $res->fetch_assoc()){
			$invoice = $row;
			//$invoice['text_total'] = getWordsFromAmount($row['total'], $lang);
			//$invoice['text_net'] = getWordsFromAmount($row['net_amount'], $lang);
			//$invoice['text_wht'] = getWordsFromAmount($row['wht_amount'], $lang);
			$inv_body = unserialize($row['body']);
		}
	}
	
	if(!$invoice){
		$tmp = $dbx->query("SHOW COLUMNS FROM rego_invoices");
		while($field = $tmp->fetch_object()){
			$invoice[$field->Field] = '' ;
		}
		$invoice['customer'] = $customer[$lang.'_compname'];
		$invoice['address'] = $customer[$lang.'_billing'];
		$invoice['status'] = 0;
		$invoice['subtotal'] = '0.00';
		$invoice['discount'] = '0.00';
		$invoice['vat'] = '0.00';
		$invoice['total'] = '0.00';
		$invoice['wht_amount'] = '0.00';
		//$invoice['wht_percent'] = '0.00';
		$invoice['net_amount'] = '0.00';
		$invoice['net_amount'] = '0.00';
		$invoice['text_total'] = $lng['*** Zero Baht only ***'];
		$invoice['text_net'] = $lng['*** Zero Baht only ***'];
		$invoice['text_wht'] = $lng['*** Zero Baht only ***'];
		$invoice['inv_date'] = '...';
		$invoice['inv_due'] = '...';
		$invoice['inv'] = '...';
		$inv_body = array();
	}
	$disabled = '';
	if($invoice['inv'] == '...'){$disabled = 'disabled';}
	//var_dump($invoice); exit;


?>

<link href="<?=ROOT?>css/smart_wizard_theme_arrows.css?<?=time()?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/formTable.css?<?=time()?>">

<style>
	a.internetbank {
		cursor:pointer;
		float:left;
		margin:0 50px 5px 0;
		text-decoration:none;
		padding:5px;
	}
	a.internetbank:hover {
		box-shadow:0 0 5px rgba(0,0,0,0.2)
	}
	a.internetbank:hover img {
		opacity:1;
	}
	a.internetbank div {
		float:left;
	}
	a.internetbank img {
		height:45px;
		border-radius:2px;
		margin: 0 10px 0 0;
		float:left;
		opacity:0.7;
	}
	a.internetbank b {
		font-size:18px;
		color:#333;
	}
	a.internetbank:hover b {
		color:#b00;
	}
	a.internetbank i {
		font-size:13px;
		color:#999;
	}

</style>			
<!-- PAYMENT FORM /////////////////////////////////////////////////////////////////////////////-->
<div id="mr_purchase" style="border:0px solid red; position:absolute; top:90px; left:0; right:0; bottom:5px">

	<div style="height:100%; width:35%; float:left; position:relative">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<div style="position:relative">
				<table class="basicTable" border="0" style="margin-bottom:10px">
					<thead>
						<tr style="line-height:110%">
							<th colspan="2"><?=$lng['Pay with Credit / Debet Card']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding-top:10px; border:0; width:60%">
								<a id="ccButton" class="internetbank" style="padding-right:10px; width:100%">
									<img src="../images/creditcard2.jpg" style="float:left" />
									<div style="display:float:left">
										<b><?=$lng['Pay with Creditcard']?></b><br>
										<i><?=$lng['Secure payment by Omise']?></i>
									</div>
									<div style="clear:xboth"></div>
								</a>
							</td>
							<td style="width:40%">
								<img style="float:right; padding-top:6px" width="100%" src="images/cards6.png" />
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="basicTable" border="0" style="margin-bottom:10px">
					<thead>
						<tr style="line-height:110%">
							<th colspan="2"><?=$lng['Pay with Internet Banking']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding:0; position:relative; width:100%; border:0">
								<table border="0">
									<tr>
										<td style="padding:10px 10px 5px 10px; width:50%; border:0">
											<a data-id="internet_banking_scb" class="internetbank payonline" style="width:100%">
												<img src="../images/scb.jpg" />
												<div style="display:float:left">
													<b><?=$lng['SCB Easy Net']?></b><br>
													<i><?=$lng['Transfer fee']?> 30 <?=$lng['Baht']?></i>
												</div>
												<div style="clear:both"></div>
											</a>
											<a data-id="internet_banking_bay" class="internetbank payonline" style="width:100%">
												<img src="../images/kungsri.png" />
												<div style="display:float:left">
													<b><?=$lng['Krungsri Online']?></b><br>
													<i><?=$lng['Transfer fee']?> 15 <?=$lng['Baht']?></i>
												</div>
												<div style="clear:both"></div>
											</a>
										</td>
										<td style="padding:10px 10px 5px 10px; width:50%; border:0">
											<a data-id="internet_banking_bbl" class="internetbank payonline" style="width:100%">
												<img src="../images/bkb.png" />
												<div style="display:float:left">
													<b><?=$lng['Bualuang iBanking']?></b><br>
													<i><?=$lng['Transfer fee']?> 35 <?=$lng['Baht']?></i>
												</div>
												<div style="clear:both"></div>
											</a>
											<a data-id="internet_banking_ktb" class="internetbank payonline" style="width:100%">
												<img src="../images/ktb.png" />
												<div style="display:float:left">
													<b><?=$lng['KTB Netbank']?></b><br>
													<i><?=$lng['Transfer fee']?> 25 <?=$lng['Baht']?></i>
												</div>
												<div style="clear:both"></div>
											</a>
										</td>
									</tr>
								</table>	
								
								<div style="font-weight:600; color:#a00; font-size:14px; text-align:right; display:none" id="internetMsg"></div>
								
								<div id="bankingOverlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); text-align:center; font-size:50px; color:#ccc; padding-top:35px; display:none">
									<i class="fa fa-circle-o-notch fa-spin"></i>
									<p style="font-size:15px; color:#a00"><b>Redirecting to your Inline banking . . . Please wait . . .</b></p>
								</div>
								
								<div id="bankingSuccess" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); text-align:center; font-size:15px; color:#a00; padding-top:45px; display:none">
									<b><p>Thank you<br>Your payment was successful</p></b>
								</div>
								
								<div id="bankingFailed" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); text-align:center; font-size:15px; color:#a00; padding-top:45px; display:none">
									<b><p>Sorry, payment failed<br>Please try again</p></b>
								</div>
		
							</td>
						</tr>
					</tbody>
				</table>
				
				<table class="basicTable" border="0" style="margin-bottom:0px; table-layout:fixed">
					<thead>
						<tr style="line-height:110%">
							<th><?=$lng['Pay with Bank transfer']?></th>
						</tr>
					</thead>
					<tbody>
					<tr style="border:0">
						<td style="white-space:normal">
							<ul style="margin:0; padding:0 0 0 20px; font-weight:600; color:#b00">
								<li>Print your invoice</li>
								<li>Print your WHT certificate (if applicable)</li>
								<li>Upload payslip from bank</li>
								<li>Upload signed WHT certificate (if applicable)</li>
							</ul>
							
							<a href="<?=ROOT?>myrego/print_invoice.php?inv=<?=$invoice['inv']?>" target="_blank" <? if($invoice['status'] > 1){echo 'disabled';}?> style="margin-top:8px; width:100%" class="tal btn btn-primary btn-sm" type="button"><i class="fa fa-print"></i>&nbsp; Print invoice</a>
							
							<button onClick="$('#whtPrintForm').submit();" <? if($invoice['status'] > 1){echo 'disabled';}?> style="margin-top:5px; width:100%" class="tal btn btn-primary btn-sm" type="button"><i class="fa fa-print"></i>&nbsp; Print WHT certificate</button>
							
							<button onClick="$('#payslip').click();" <? if($invoice['status'] > 1){echo 'disabled';}?> style="margin-top:5px; width:100%" class="tal btn btn-primary btn-sm" type="button"><i class="fa fa-upload"></i>&nbsp; Upload payslip from bank</button>
							
							<button onClick="$('#whtcertificate').click();" <? if($invoice['status'] > 1){echo 'disabled';}?> style="margin-top:5px; width:100%" class="tal btn btn-primary btn-sm" type="button"><i class="fa fa-upload"></i>&nbsp; Upload signed WHT certificate</button>
							
						</td>
					</tr>
					</tbody>
				</table>
				<form id="transactionForm">
					<input name="inv_number" type="hidden" value="<?=$invoice['inv']?>">
					<input style="display:none" type="file" name="payslip" id="payslip">
					<input style="display:none" type="file" name="wht_certificate" id="whtcertificate">
				</form>

				<div id="paymentOverlay" style="position:absolute; top:0; left:0; right:0; bottom:0; background: rgba(255,255,255,0.5); <? if($invoice['status'] == 1){echo 'display:none';}?>"></div>
			</div>

			<div id="dump3"></div>
		</div>
	
	</div>

	<div style="height:100%; background:#f9f9f9; width:65%; float:right; border-left:1px solid #ccc; position:relative">

		<div class="breadcrumbs" style="z-index:1">
			
			<a class="subMenu activ" data-id="#myInvoice" style="border-right:1px solid #ccc; float:left"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Invoice']?></a>
			<a class="subMenu" data-id="#whtCertificate" style="border-right:1px solid #ccc; float:left"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['WHT Certificate']?></a>
			
			<!--<a href="<?=ROOT?>myrego/print_invoice.php?inv=<?=$invoice['inv']?>" id="myInvoice_print" class="printer" target="_blank" style="border-left:1px solid #ccc; float:right"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></a>
			
			<a class="printer" id="whtCertificate_print" target="_blank" style="border-left:1px solid #ccc; float:right; display:none"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></a>-->
			
		</div>
		
		<div style="height:calc(100% - 0px); overflow-y:auto; padding:50px 20px 20px 20px">	
			<div id="dump2"></div>

			<div class="myDocument" id="myInvoice" style="box-shadow:0 0 10px rgba(0,0,0,0.2); padding:30px; background:#fff; max-width:880px">
				<? include('invoice_doc.php');?>
			</div>				
			
			<div class="myDocument" id="whtCertificate" style="box-shadow:0 0 10px rgba(0,0,0,0.2); display:none; z-index:0; max-width:880px;">
				<div style="width:880px; height:1245px; background:url(../images/wht_certificate.png) no-repeat; background-size: cover; position:relative; z-index:0">
					<form id="whtPrintForm">
						<input type="hidden" name="ctax" value="<?=$customer['tax_id']?>">
						<input type="hidden" name="ccompany" value="<?=$ccompany?>">
						<input type="hidden" name="caddress" value="<?=$caddress?>">
						<input type="hidden" name="xtax" value="<?=$template['tax_id']?>">
						<input type="hidden" name="xcompany" value="<?=$xcompany?>">
						<input type="hidden" name="xaddress" value="<?=$xaddress?>">
						<input type="hidden" name="price_sub" value="<?=$invoice['subtotal']?>">
						<input type="hidden" name="price_wht" value="<?=$invoice['wht_amount']?>">
						<input type="hidden" name="text_wht" value="<?=$invoice['text_wht']?>">
					</form>
					<div class="field" style="top:33.8mm;left:148.2mm"><?=$ctax[0]?></div>
					<div class="field" style="top:33.8mm;left:155.4mm"><?=$ctax[1]?></div>
					<div class="field" style="top:33.8mm;left:160mm"><?=$ctax[2]?></div>
					<div class="field" style="top:33.8mm;left:164.6mm"><?=$ctax[3]?></div>
					<div class="field" style="top:33.8mm;left:169.4mm"><?=$ctax[4]?></div>
					<div class="field" style="top:33.8mm;left:176.8mm"><?=$ctax[5]?></div>
					<div class="field" style="top:33.8mm;left:181.4mm"><?=$ctax[6]?></div>
					<div class="field" style="top:33.8mm;left:185.8mm"><?=$ctax[7]?></div>
					<div class="field" style="top:33.8mm;left:190.6mm"><?=$ctax[8]?></div>
					<div class="field" style="top:33.8mm;left:195.4mm"><?=$ctax[9]?></div>
					<div class="field" style="top:33.8mm;left:202.4mm"><?=$ctax[10]?></div>
					<div class="field" style="top:33.8mm;left:207.2mm"><?=$ctax[11]?></div>
					<div class="field" style="top:33.8mm;left:214.8mm"><?=$ctax[12]?></div>

					<div class="field" style="top:40.6mm;left:22mm"><?=$ccompany?></div>
					<div class="field" style="top:49.8mm;left:24mm"><?=$caddress?></div>

					<div class="field" style="top:60.6mm;left:148.2mm"><?=$xtax[0]?></div>
					<div class="field" style="top:60.6mm;left:155.4mm"><?=$xtax[1]?></div>
					<div class="field" style="top:60.6mm;left:160mm"><?=$xtax[2]?></div>
					<div class="field" style="top:60.6mm;left:164.6mm"><?=$xtax[3]?></div>
					<div class="field" style="top:60.6mm;left:169.4mm"><?=$xtax[4]?></div>
					<div class="field" style="top:60.6mm;left:176.8mm"><?=$xtax[5]?></div>
					<div class="field" style="top:60.6mm;left:181.4mm"><?=$xtax[6]?></div>
					<div class="field" style="top:60.6mm;left:185.8mm"><?=$xtax[7]?></div>
					<div class="field" style="top:60.6mm;left:190.6mm"><?=$xtax[8]?></div>
					<div class="field" style="top:60.6mm;left:195.4mm"><?=$xtax[9]?></div>
					<div class="field" style="top:60.6mm;left:202.4mm"><?=$xtax[10]?></div>
					<div class="field" style="top:60.6mm;left:207.2mm"><?=$xtax[11]?></div>
					<div class="field" style="top:60.6mm;left:214.8mm"><?=$xtax[12]?></div>

					<div class="field" style="top:69mm;left:22mm"><?=$xcompany?></div>
					<div class="field" style="top:79.2mm;left:24mm"><?=$xaddress?></div>

					<div class="field" style="top:97.2mm;left:156.2mm"><b>X</b></div>

					<div class="field" style="top:241mm;right:76mm"><?=date('d-m-').(date('Y')+543)?></div>
					<div class="field" style="top:241mm;right:41.4mm"><?=number_format($invoice['subtotal'],2)?></div>
					<div class="field" style="top:241mm;right:13.6mm"><?=number_format($invoice['wht_amount'],2)?></div>

					<div class="field" style="top:254.8mm;right:41.4mm"><?=number_format($invoice['subtotal'],2)?></div>
					<div class="field" style="top:254.8mm;right:13.6mm"><?=number_format($invoice['wht_amount'],2)?></div>

					<div class="field" style="top:262.8mm;left:75mm"><?=$invoice['text_wht']?></div>

					<div class="field" style="top:296.6mm;left:135mm"><?=date('d')?></div>
					<div class="field" style="top:296.6mm;left:147mm"><?=$months[date('n')]?></div>
					<div class="field" style="top:296.6mm;left:172mm"><?=(date('Y')+543)?></div>
				</div>
			</div>
			
		</div>
	
	</div>

</div>

	<!-- MODAL CREDITCARD -->
	<div class="modal fade" id="modalCard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:400px">
			  <div class="modal-content" style="border-radius:10px;">
					<div class="modal-body" style="padding:40px 50px 40px; position:relative">
						<button style="position:absolute; top:30px; right:40px; font-size:30px" type="button" class="close" data-dismiss="modal">&times;</button>
						<form method="post" id="checkoutForm" class="omiseForm" >
							<input id="card_amount" type="hidden" value="<?=($invoice['net_amount']*100)?>" />
							<input id="card_invoice" type="hidden" value="<?=$invoice['inv']?>" />
							<input id="card_version" type="hidden" value="" />
							<input id="card_company" type="hidden" value="<?=$compinfo[$lang.'_compname']?>" />
							<table width="100%" style="margin-bottom:15px">
								<tr style="line-height:130%">
									<td style="width:40px"><img height="40" width="40" src="images/logo.png" /></td>
									<td style="padding-left:10px"><b style="font-size:16px">REGO HR</b><br><span style="color:#999"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Secured by']?> Omise</span></td>
								</tr>
								<tr>
									<td colspan="2" style="padding:20px 0 0 0"><b style="font-size:20px"><?=$lng['Credit']?> / <?=$lng['Debet']?></td>
								</tr>
							</table>
							<table width="100%">
								<tr>
									<td colspan="2">
										<label><?=$lng['Total amount']?></label>
										<input style="border:1px solid #ddd; cursor:default; font-weight:600" readonly name="totAmount" id="totAmount" type="text" value="<?=number_format($invoice['net_amount'],2)?> <?=$lng['THB']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label><?=$lng['Card number']?></label>
										<input class="creditcard_number numeric" name="number" id="number" type="text" value="4111111111111111" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label><?=$lng['Name on card']?></label>
										<input placeholder="Full name" name="name" id="name" type="text" value="Chucheep Chansaithong" />
									</td>
								</tr>
								<tr>
									<td style="padding-right:10px">
										<label><?=$lng['Expiry date']?></label>
										<input class="expire_date" name="expire_date" id="expire_date" type="text" value="02/22" />
									</td>
									<td style="padding-left:10px">
										<label><?=$lng['Security code']?></label>
										<input class="security_code numeric" name="security_code" id="security_code" type="text" value="111" />
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:0px">
										<div style="font-weight:600; color:#a00; font-size:14px; display:none" id="cardMsg"></div>
										<button style="width:100%; margin-top:10px" type="submit" class="payButton"><i class="fa fa-lock"></i>&nbsp; <?=$lng['PAY NOW']?></button>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding-top:20px; text-align:center; font-size:14px">
										<span style="color:#aaa"><?=$lng['Secured by']?></span> <img height="18" style="display:inline-block" src="images/omise.png" /> <b>Omise</b>
									</td>
								</tr>
							</table>
						</form>
						
					</div>
			  </div>
		 </div>
	</div>

<script src="<?=ROOT?>js/jquery.mask.js"></script>	
<script src="<?=ROOT?>js/omise.js"></script>	
	
<script type="text/javascript">
	
	Omise.setPublicKey("pkey_test_5ibhcak45cr121wkt2l");
		
	String.prototype.capitalize = function() {
		 return this.charAt(0).toUpperCase() + this.slice(1);
	}			

	$(".creditcard_number").mask("9999  9999  9999  9999", {placeholder: "....  ....  ....  ...."});
	$(".expire_date").mask("99 / 99", {placeholder: "MM / YY"});
	$(".security_code").mask("999", {placeholder: "..."});

	$(document).ready(function() {
		
		var iamount = <?=json_encode(($invoice['net_amount']*100))?>;
		var invoice = <?=json_encode($invoice['inv'])?>;
		var status = <?=json_encode($_GET['status'])?>;
		
		$("#whtCertificate_print").on('click', function(e){
			$("#whtPrintForm").submit();
		}) 
		$("#whtPrintForm").on('submit', function(e){
			e.preventDefault();
			var data = $(this).serialize();
			window.open(ROOT+"myrego/print_wht_certificate.php?"+data);
		}) 
		
		$(".subMenu").on('click', function(e){
			var id = $(this).data('id');
			$(".subMenu").removeClass('activ');
			$(".myDocument").hide();
			//$(".printer").hide();
			$(this).addClass('activ');
			$(id).fadeIn(200);
			//$(id+'_print').show();
			//alert(id)
		}) 
		
		
		/*$("#xxxwhtcertificate").on('change', function(e){
			e.preventDefault();
			var file = $(this)[0].files[0];
			var name = file.name;
			var ext = (name.split('.').pop()).toLowerCase();
			if(ext != 'jpeg' && ext != 'png' && ext != 'jpg' && ext != 'pdf' && ext != 'txt'){
				var msg = '<?=$lng['Please use only {xxx} files']?>';
				msg = msg.replace('{xxx}', '.jpg, .jpeg, .png, .pdf, .txt');
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;' + msg,
					duration: 2,
				})
				return false;
			}
			var data = new FormData($('#transactionForm')[0]);

			$.ajax({
				url: ROOT+"myrego/ajax/upload_payslip.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					$('#dump2').html(result); return false;
					//location.reload();
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;File uploaded successfuly<? //=$lng['Sorry but someting went wrong']?>',
						duration: 2,
					})
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 4,
					})
				}
			});
		})*/ 

		if(status == 'successful'){
			//$("#sConfirmed").removeClass('active')
			//$("#sPaid").addClass('active')
			$('#bankingSuccess').fadeIn(200);
			setTimeout(function(){
				$('#bankingSuccess').fadeOut(200)
				//$('#paymentOverlay').fadeOut(200)
				//location.href='index.php?mn=8';
			}, 4000);
			/*$.ajax({
				type: "POST",
				url: ROOT+"myrego/ajax/finish_payment.php",
				//data: {cid: cid},
				success: function(response){
					$('#dump3').html(response); 
					//window.open(response, '_self') 
					return false
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert('error ' + thrownError);
					//$('#internetMsg').html('Error : ' + thrownError).hide().fadeIn(300);
				}
			});*/
		}
		if(status == 'failed'){
			$('#bankingFailed').fadeIn(200);
			setTimeout(function(){$('#bankingFailed').fadeOut(200)}, 4000);
		}
		
		$("#ccButton").on('click', function(e){
			$('#cardMsg').html('').hide();
			$('#modalCard').modal('toggle');
		}) 
		$('#modalCard').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#cardMsg").html('').hide();
		});
		$("#checkoutForm").submit(function (e) {
			e.preventDefault();
			$('.payButton i').removeClass('fa-lock').addClass('fa-refresh fa-spin');
			$(".payButton").prop("disabled", true);
			$("#cardMsg").html('Submitting . . . Please wait . . .').hide().fadeIn(300);
			
			var form = document.getElementById('checkoutForm')
			var exp_month = (form.expire_date.value).substring(0,2);
			var exp_year = (form.expire_date.value).substring(5,7);
			var amount = (form.card_amount.value);
			//var invoice = (form.card_invoice.value);
			//alert(exp_month)
			var card = {
			  "name": form.name.value,
			  "number": form.number.value,
			  "expiration_month": exp_month,
			  "expiration_year": exp_year,
			  "security_code": form.security_code.value,
			}
			Omise.createToken("card", card, function (statusCode, response) {
				if (response.object == "error") {
					$("#cardMsg").html(response.message).hide().fadeIn(300);
					$(".payButton").prop("disabled", false);
				}else{
					setTimeout(function(){
						$.ajax({
							type: "POST",
							url: ROOT+"myrego/ajax/omise_charge_card.php",
							data: {data: response, amount: amount, invoice: invoice},
							success: function(response){
								//$('#dump3').html(response); return false; 
								if(response == 'successful'){
									$('.payButton i').removeClass('fa-refresh fa-spin').addClass('fa-lock');
									$('#cardMsg').html('Thanks, your payment was successful').hide().fadeIn(300);
									setTimeout(function(){
										$('#modalCard').modal('toggle');
									}, 2000);
									
									$.ajax({
										type: "POST",
										url: ROOT+"myrego/ajax/invoice_paid.php",
										//data: {data: response, amount: amount},
										success: function(response){
											//$('#dump3').html(response);
											setTimeout(function(){location.href='index.php?mn=8';}, 2000);
												
										},
										error:function (xhr, ajaxOptions, thrownError){
											alert(thrownError);
											//$('#cardMsg').html('Error : ' + thrownError).hide().fadeIn(300);;
										}
									});
									
									
								}else{
									$('.payButton i').removeClass('fa-refresh fa-spin').addClass('fa-lock');
									
									$(".payButton").prop("disabled", false);
								}
							},
							error:function (xhr, ajaxOptions, thrownError){
								//alert(thrownError);
								$('#cardMsg').html('Error : ' + thrownError).hide().fadeIn(300);;
							}
						});
					}, 1000);
				};
			});
			return false;
		})
		
		$(".payonline").on('click', function(e){
			//alert($(this).data('id'))
			var source = $(this).data('id');
			//alert(source)
			$("#bankingOverlay").fadeIn(100);
			Omise.createSource(source, {
				"amount": iamount,
				"currency": "THB"
			}, function (statusCode, response) {
				//alert('response '+response.object)
				if (response.object == "error") {
					//alert('response '+response.message)
					$("#bankingOverlay").fadeOut(100);
					$("#internetMsg").html(response.message).hide().fadeIn(300);
					//$(".payButton").prop("disabled", false);
				}else{
					//$("#internetMsg").html('Redirecting to your Inline banking . . . Please wait . . .').hide().fadeIn(300);
					
					//alert(ROOT+"myrego/ajax/omise_charge_internet_banking.php")
					setTimeout(function(){
						$.ajax({
							type: "POST",
							url: ROOT+"myrego/ajax/omise_charge_internet_banking.php",
							data: {amount: iamount, source: source, invoice: invoice},
							success: function(response){
								//$('#dump3').html(response); 
								window.open(response, '_self') 
								return false
							},
							error:function (xhr, ajaxOptions, thrownError){
								//alert('error ' + thrownError);
								$('#internetMsg').html('Error : ' + thrownError).hide().fadeIn(300);
							}
						});
					}, 1000);
				};
			});
			return false;
		})
		
		$("#payslip, #whtcertificate").on('change', function(e){
			e.preventDefault();
			var file = $(this)[0].files[0];
			var name = file.name;
			var ext = (name.split('.').pop()).toLowerCase();
			if(ext != 'jpeg' && ext != 'png' && ext != 'jpg' && ext != 'pdf'){
				var msg = '<?=$lng['Please use only {xxx} files']?>';
				msg = msg.replace('{xxx}', '.jpg, .jpeg, .png, .pdf, .txt');
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;' + msg,
					duration: 2,
				})
				return false;
			}
			var data = new FormData($('#transactionForm')[0]);
			$.ajax({
				url: ROOT+"myrego/ajax/upload_files.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					//$('#dump2').html(result); return false;
					//location.reload();
					if(result == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;File uploaded successfuly<? //=$lng['Sorry but someting went wrong']?>',
							duration: 2,
						})
					}
					$('#payslip').val('');
					$('#whtcertificate').val('');
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


	});
	
</script>

















