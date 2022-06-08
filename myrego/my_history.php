<?
	//var_dump($price_table); exit;
	
	$invoices = array();
	if($res = $dbx->query("SELECT id, inv, inv_date, total, status FROM rego_invoices WHERE cid = '".$cid."' ORDER BY id DESC")){
		while($row = $res->fetch_assoc()){
			$invoices[$row['id']] = $row;
			//$invoices[$row['id']]['text_total'] = getWordsFromAmount($row['total'], $lang);
			//$invoices[$row['id']]['text_net'] = getWordsFromAmount($row['net'], $lang);
		}
	}
	//var_dump($invoices); exit;
	
	
	$template = array();
	$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template); exit;
	
	
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
	
	$draft = array();
	if($res = $dbx->query("SELECT * FROM rego_purchase_draft WHERE clientID = '".$cid."'")){
		if($row = $res->fetch_all(MYSQLI_ASSOC)){
			$draft = $row[0];
			$draft['text_total'] = getWordsFromAmount($draft['price_total'], $lang);
			$draft['text_net'] = getWordsFromAmount($draft['price_net'], $lang);
		}
	}
	if(!$draft){
		$tmp = $dbx->query("SHOW COLUMNS FROM rego_purchase_draft");
		while($field = $tmp->fetch_object()){
			$draft[$field->Field] = '' ;
		}
		$draft['version'] = $customer['version'];
		$draft['max_employees'] = $customer['employees'];
		$draft['price_year'] = $price_table[$customer['version']]['price_year'];
		$draft['price_period'] = '0.00';
		$draft['price_remain'] = '0.00';
		$draft['price_sub'] = '0.00';
	
		$draft['price_vat'] = '0.00';
		$draft['price_total'] = '0.00';
		$draft['price_wht'] = '0.00';
		//$draft['wht_percent'] = '0.00';
		$draft['price_net'] = '0.00';
		$draft['price_due'] = '0.00';
		$draft['text_total'] = $lng['*** Zero Baht only ***'];
		$draft['text_net'] = $lng['*** Zero Baht only ***'];
	
		$draft['inv_date'] = date('d-m-Y');
		$draft['inv_due'] = '...';
		$draft['inv_number'] = 'RG '.date('ymd').'-001';
	
	}
	
	//var_dump($draft); exit;


?>
<style>
	.basicTable.history tbody tr:hover {
		background:#cfc;
		cursor:pointer;
	}
</style>

<link href="<?=ROOT?>css/smart_wizard_theme_arrows.css?<?=time()?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/formTable.css?<?=time()?>">
<!-- HISTORY FORM /////////////////////////////////////////////////////////////////////////////-->
<div id="mr_purchase" style="border:0px solid red; position:absolute; top:90px; left:0; right:0; bottom:5px">

	<div style="height:100%; width:32%; float:left">
		<div style="height:calc(100% - 36px); overflow-y:auto; padding:15px">
			<table class="basicTable history" border="0" style="margin-bottom:10px">
				<thead>
					<tr style="line-height:110%">
						<th><?=$lng['Invoice']?> #</th>
						<th><?=$lng['Date']?></th>
						<th class="tar"><?=$lng['Amount']?></th>
						<th class="tac" style="width:1px"><?=$lng['Status']?></th>
					</tr>
				</thead>
				<tbody>
				<? foreach($invoices as $k=>$v){ ?>	
					<tr>
						<td class="invNr" style="font-weight:600"><?=$v['inv']?></td>
						<td><?=$v['inv_date']?></td>
						<td class="tar"><?=number_format($v['total'],2)?></td>
						<td><?=$inv_status[$v['status']]?></td>
					</tr>
				<? } ?>
				</tbody>
			</table>
			<button disabled onClick="$('#payslip').click();" style="width:100%" class="tal uploads btn btn-primary btn-sm" type="button"><i class="fa fa-upload"></i>&nbsp; Upload payslip<? //=$lng['Upload Transaction result']?></button>
			
			<button disabled onClick="$('#whtcertificate').click();" style="margin-top:5px; width:100%" class="tal uploads btn btn-primary btn-sm" type="button"><i class="fa fa-upload"></i>&nbsp; <?=$lng['Upload WHT certificate']?></button>
			
			<form id="transactionForm">
				<input name="inv_number" type="hidden" value="<? //=$invoice['inv']?>">
				<input style="display:none" type="file" name="payslip" id="payslip">
				<input style="display:none" type="file" name="wht_certificate" id="whtcertificate">
			</form>

			<div id="dump2"></div>
		</div>
	</div>

	<div style="height:100%; background:#f9f9f9; width:68%; float:right; border-left:1px solid #ccc; position:relative">

		<div class="breadcrumbs" style="z-index:9999">
			<a class="sBtn" id="myInv" data-id="#myInvoice" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Invoice']?></a>
			<a class="sBtn" data-id="#myCertificate" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['WHT Certificate']?></a>
			<a class="sBtn" data-id="#myReceipt" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Tax Receipt']?></a>
			<a class="sBtn" data-id="#myPayslip" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Payslip']?></a>
			
			<a id="printBtn" target="_blank" style="border-left:1px solid #ccc; float:right"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></a>
			
		</div>
		
		<div style="height:calc(100% - 0px); overflow-y:auto; padding:50px 20px 20px 20px">	
			<!--<div id="dump2"></div>-->
			
			<!--<div class="myDocument" id="myInvoice" style="width:880px; box-shadow:0 0 10px rgba(0,0,0,0.2); padding:30px; background:#fff; display:none">
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
						<td style="vertical-align:top; font-size:24px; font-weight:600"><?=strtoupper($lng['Invoice'])?></td>
						<td style="padding:0">
							<div style="background:#fff; padding:10px 15px; border:1px solid #ddd; border-radius:5px; width:90%">
								<b style="font-size:15px"><?=$company[$lang.'_compname']?></b>
								<p><?=nl2br($company[$lang.'_address'])?></p>
							</div>
						</td>
					</tr>
					<tr style="height:40px">
						<th colspan="2" style="font-size:20px">
							<div style="float:left"><?=$lng['Invoice']?> # :&nbsp;&nbsp;<?=$draft['inv_number']?>	
						</th>
					</tr>
					<tr>
						<td colspan="2" style="padding:0">
							<table border="0">
								<tr>
									<th style="padding-right:30px"><?=$lng['Invoice date']?> :</th>
									<th style="padding-right:30px"><?=$lng['Due date']?> :</th>
									<th><?=$lng['Client ID']?> :</th>
								</tr>
								<tr>
									<td><?=$draft['inv_date']?></td>
									<td><?=$draft['inv_due']?></td>
									<td><?=$rego?></td>
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
									<tr>
										<td class="tac">1</td>
										<td><?=$lng['Service fee for access to the RegoHR.com platform']?></td>
										<td class="tac">1</td>
										<td id="inv_unit" class="tar"><?=number_format($draft['price_sub'],2)?></td>
										<td class="tac"><?=$template['vat']?></td>
										<td id="inv_amount" class="tar"><?=number_format($draft['price_sub'],2)?></td>
									</tr>
									<tr>
										<td class="tac">2</td>
										<td id="inv_descr2"><?=$lng['Subscription']?> : <span id="inv_version"><?=$version[$draft['version']]?></span></td>
										<td></td><td></td><td></td><td></td>
									</tr>
									<tr>
										<td class="tac">3</td>
										<td id="inv_descr3"><?=$lng['Max employees']?> : <span id="inv_max_employees"><?=$draft['max_employees']?></span></td>
										<td></td><td></td><td></td><td></td>
									</tr>
									<tr>
										<td class="tac">4</td>
										<td id="inv_descr4">
											<?=$lng['Period']?> : 
											<?=$lng['From']?> <span id="inv_period_start"><?=$draft['period_start']?></span>
											<?=$lng['Until']?> <span id="inv_period_end"><?=$draft['period_end']?></span>
										</td>
										<td></td><td></td><td></td><td></td>
									</tr>
									<tr>
										<td class="tac">5</td><td>&nbsp;</td><td></td><td></td><td></td><td></td>
									</tr>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="3" rowspan="4" style="padding:0 0 5px 0; vertical-align:bottom">
										<? if(!$customer['wht']){ ?>
										<table width="100%" border="0">
											<tr>
												<td colspan="2" style="border:0; font-size:14px">
													<?=$lng['Deduct WHT']?> :&nbsp;&nbsp;<b><span id="wht_percent"><?=$template['wht']?></span></b>%&nbsp;&nbsp;<?=$lng['on']?>&nbsp;&nbsp;<b><span id="wht_amount"><?=number_format($draft['price_sub'],2)?></span></b>&nbsp;&nbsp;<?=$lng['is']?>&nbsp;&nbsp;<b><span id="inv_wht"><?=number_format($draft['price_wht'],2)?></span></b>&nbsp;&nbsp;<?=$lng['Baht']?></span>
												</td>
											</tr>
											<tr>
												<th style="width:1px; white-space:nowrap; font-size:16px; border:0"><?=$lng['Net to Pay']?> :</th>
												<th style="font-size:16px; border:0">
													<span id="inv_net"><?=number_format($draft['price_net'],2)?></span> <?=$lng['Baht']?>
												</th>
											</tr>
										  	<tr>
											 	<td colspan="2" style="border:0" id="text_net"><?=$draft['text_net']?></td>
										  	</tr>
										</table>
										<? } ?>
									</td>
									<th colspan="2"><?=$lng['Discount']?></th>
									<th class="tar"></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Sub total']?></th>
									<th id="inv_sub" class="tar"><?=number_format($draft['price_sub'],2)?></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['VAT']?></th>
									<th id="inv_vat" class="tar"><?=number_format($draft['price_vat'],2)?></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Grand total']?></th>
									<th id="inv_total" class="tar"><?=number_format($draft['price_total'],2)?></th>
								</tr>
								<tr>
									<td colspan="6" class="tar" id="text_total" style="border-bottom-width:1px; padding:4px 8px">
										<?=$draft['text_total']?>
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
						<td style="width:1px; vertical-align:baseline; text-align:right"><b>To : </b></td>
						<td style="vertical-align:baseline; width:50%; padding-left:0; border-right:1px solid #ddd">
							<?=nl2br($template[$lang.'_pay_to'])?>
						</td>
						<td style="width:1px; vertical-align:baseline; text-align:right">
							<b><?=$lng['Customer']?> : </b><br>
							<b><?=$lng['Invoice']?> # : </b><br>
							<b><?=$lng['Amount due']?> : </b><br>
							<b><?=$lng['Due date']?> : </b>
						</td>
						<td style="vertical-align:baseline; width:50%; padding-left:0">
							<b id="pd_customer"><?=$rego?></b><br>
							<span class="docNr"><?=$draft['inv_number']?></span><br>
							<b id="pd_amount"><?=number_format($draft['price_due'],2)?></b><br>
							<?=date('d-m-Y', strtotime($template['due'], strtotime(date('d-m-Y'))))?><br>
						
						</td>
					</tr>
					<tr>
						<td style="width:1px; vertical-align:top; text-align:right"><b>Bank : </b></td>
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
				<div class="footer" style="padding-top:25px">
					<div style="text-align:center; padding-top:5px; border-top:1px solid #ccc">
						<?=$template[$lang.'_footer']?> 
					</div>
				</div>
			</div>-->				
			
			<div class="myDocument" id="myInvoice" style="min-width:800px; height:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left; background-repeat:no-repeat; background-size:contain">
				<canvas style="display:block" id="cInvoice"></canvas>
			</div>
			
			<div class="myDocument" id="myCertificate" style="min-width:800px; height:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left">
				<canvas style="display:block" id="cCertificate"></canvas>
			</div>
			
			<div class="myDocument" id="myReceipt" style="min-width:800px; height:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left">
				<canvas style="display:block" id="cReceipt"></canvas>
			</div>
			
			<div class="myDocument" id="myPayslip" style="min-width:800px; height:auto; box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left">
				<canvas style="display:block" id="cPayslip"></canvas>
			</div>
			
		</div>
	
	</div>

</div>

<!--<script src="../showPDF/pdf.js"></script>
<script src="../showPDF/pdf.worker.js"></script>-->
<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

<script type="text/javascript">
		
	function showDocument(url, id){
		var loadingTask = pdfjsLib.getDocument(url);
		loadingTask.promise.then(function(pdf) {
			var pageNumber = 1;
			pdf.getPage(pageNumber).then(function(page) {
				//var scale = 1.5;
				var viewport = page.getViewport({scale: 1.4});
				var canvas = document.getElementById(id);
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;
				var renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
					console.log('Page rendered');
				});
			});
		}, function (reason) {
			// PDF loading error
			//console.error(reason);
		});
	}
	
	$(document).ready(function() {
		
		var prInvoice;
		var prCertificate;
		var prReceipt;
		var prPayslip;
		
		$(".history tbody tr").on('click', function(e){
			var inv = $(this).closest('tr').find('.invNr').html();
			$('input[name="inv_number"]').val(inv);
			$('.uploads').prop('disabled', false);
			//alert(inv)
			$.ajax({
				url: ROOT+"myrego/ajax/get_documents.php",
				data: {inv: inv},
				dataType: 'json',
				success: function(data){
					//$('#dump2').html(data); return false;
					//alert(data.payslip)
					if(data.invoice == ''){
						$('#myInvoice').html('<div style="padding:20px 25px; font-size:15px"><?=$lng['Document not available']?></div>');
					}else{
						//alert(data.invoice)
						prInvoice = data.invoice;
						var ext = (data.invoice).split('.').pop();
						if(ext == 'pdf'){
							showDocument(data.invoice, 'cInvoice');
						}else{
							$('#myInvoice').css('background-image', 'url('+data.invoice+')')
						}
					}

					if(data.certificate == ''){
						$('#myCertificate').html('<div style="padding:20px 25px; font-size:15px"><?=$lng['Document not available']?></div>');
					}else{
						prCertificate = data.certificate;
						var ext = (data.certificate).split('.').pop();
						if(ext == 'pdf'){
							showDocument(data.certificate, 'cCertificate');
						}else{
							$('#myCertificate').css('background-image', 'url('+data.certificate+')')
						}
					}

					if(data.receipt == ''){
						$('#myReceipt').html('<div style="padding:20px 25px; font-size:15px"><?=$lng['Document not available']?></div>');
					}else{
						prReceipt = data.receipt;
						var ext = (data.receipt).split('.').pop();
						if(ext == 'pdf'){
							showDocument(data.receipt, 'cReceipt');
						}else{
							$('#myReceipt').css('background-image', 'url('+data.receipt+')')
						}
					}

					if(data.payslip == ''){
						$('#myPayslip').html('<div style="padding:20px 25px; font-size:15px"><?=$lng['Document not available']?></div>');
					}else{
						prPayslip = data.payslip;
						var ext = (data.payslip).split('.').pop();
						if(ext == 'pdf'){
							showDocument(data.payslip, 'cPayslip');
						}else{
							$('#myPayslip').css('background-image', 'url('+data.payslip+')')
						}
					}
					$(".breadcrumbs a").removeClass('activ');
					$(".myDocument").hide();
					$('#myInv').addClass('activ');
					$('#myInvoice').fadeIn(200);
					//$('#myInvoice_print').show();
					$('#printBtn').attr('href', data.invoice);
					
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
		
		$(".breadcrumbs a.sBtn").on('click', function(e){
			var id = $(this).data('id');
			$(".breadcrumbs a").removeClass('activ');
			$(".myDocument").hide();
			$(this).addClass('activ');
			//$(id+'_print').show();
			$(id).fadeIn(200);
			//alert(prReceipt)
			$('#printBtn').removeAttr('href')
			if(id == '#myInvoice' && prInvoice != 'undefined'){
				$('#printBtn').attr('href', prInvoice)
			}
			if(id == '#myCertificate' && prCertificate != 'undefined'){
				$('#printBtn').attr('href', prCertificate)
			}
			if(id == '#myReceipt' && prReceipt != 'undefined'){
				$('#printBtn').attr('href', prReceipt)
			}
			if(id == '#myPayslip' && prPayslip != 'undefined'){
				$('#printBtn').attr('href', prPayslip)
			}
		}) 
		$('#modalCard').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#cardMsg").html('');
		});

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
					$("body").overhang({
						type: "success",
						message: '<i class="fa fa-check"></i>&nbsp;&nbsp;File uploaded successfuly<? //=$lng['Sorry but someting went wrong']?>',
						duration: 2,
					})
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

















