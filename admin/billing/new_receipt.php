<?php
	
	if(isset($_GET['nid'])){$id = $_GET['nid'];}else{$id = 0;}
	
	$template = array();
	$res = $dba->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	if(!isset($template['logo']) || empty($template['logo'])){$template['logo'] = '../images/notavailable.jpg';}
	if(!isset($template['stamp']) || empty($template['stamp'])){$template['stamp'] = '../images/notavailable.jpg';}
	if(!isset($template['signature']) || empty($template['signature'])){$template['signature'] = '../images/notavailable.jpg';}
	//var_dump($template);
	
	//$invoices = getJsonInvoices($lang);
	//var_dump($template);

	
	//$nnew = false;
	//$new = false;
	//$view = false;

	/*if(isset($_GET['vid'])){
		$view = true;
		$id = $_GET['vid'];
	}elseif(isset($_GET['nid'])){
		$new = true;
		$id = $_GET['nid'];
	}else{
		$nnew = true;
		$id = 0;
	}*/
	//var_dump($id);
	
	$body = array();
	$data['rec'] = 1;
	$disabled = 'disabled';
	if($id){
		$res = $dba->query("SELECT * FROM rego_invoices WHERE id = '".$id."'");
		if($data = $res->fetch_assoc()){
			$nr = $data['id'];
			$body = unserialize($data['body']);
			$data['text_total'] = getWordsFromAmount($data['total'], $lang);
			$data['text_net'] = getWordsFromAmount($data['net_amount'], $lang);
		}
	}
	if(empty($data['rec_id'])){
		$res = $dba->query("SELECT rec FROM rego_invoices ORDER BY rec DESC LIMIT 1");
		if($tmp = $res->fetch_assoc()){
			$data['rec'] = $tmp['rec']+1;
		}
		$data['rec_id'] = $template['rec_prefix'].date($template['rec_date']).'-'.sprintf('%03d', $data['rec']);
		$disabled = '';
	}
	//var_dump($body);
	//var_dump($recNr);
	//var_dump($_GET['nid']); 
	//exit;
	
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
</style>
	
	<div class="widget">
		<h2><i class="fa fa-file-text-o fa-lg"></i>&nbsp;&nbsp;<?=$lng['Receipt / Tax Invoice']?></h2>
		<div class="widget_body" style="border:0px solid red; position:absolute; bottom:30px; top:90px; right:0; left:0; padding:50px 0px 0 20px">
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
				<tr>
					<td style="width:20%; vertical-align:top; padding:10px 0 0 0">
						<!--<? //if($nnew){?>
						<input id="auto-invoices" placeholder="Select invoice" type="text" style="width:100%; margin-bottom:10px; border:1px solid #ddd !important; padding:5px 10px">
						<? //} ?>-->
						<button disabled id="submitBtn" onClick="$('#receiptForm').submit()" style="margin-bottom:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Save receipt']?> </button>
						
						<form id="certificateForm">
							<input name="id" type="hidden" value="<?=$id?>">
							<input id="certificate" type="file" name="certificate" style="height:0; width:0; visibility:hidden">
						</form>
						
						<button id="certBtn" onClick="$('#certificate').click()" style="margin-bottom:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-upload"></i>&nbsp;&nbsp;<?=$lng['Upload certificate']?></button>
						
						<? //if($nnew || $new){?>
						<? //} ?>
						<button <? //if(!$view){ echo 'disabled';}?> id="print" style="margin-bottom:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-print"></i>&nbsp;&nbsp;<?=$lng['Print receipt']?> </button>
						
						<button <? //if(!$view){ echo 'disabled';}?> id="sprint" style="margin-bottom:5px; width:100%; text-align:left" class="btn btn-primary btn-sm" type="button"><i class="fa fa-paper-plane"></i>&nbsp;&nbsp;<?=$lng['Send by eMail']?></button>
					
					</td>	
					<td style="width:80%; vertical-align:top; padding-right:0px; padding-left:10px; border-right:0px solid #eee">
				
					<div style="height:100%; padding:10px; overflow-y:auto">
					<div style="height:1123px; width:794px; background-size:contain; position:relative; padding:35px; box-shadow:0 0 10px rgba(0,0,0,0.2)">
			
			<form id="receiptForm">
				<fieldset <?=$disabled?>>
				<input name="id" type="hidden" value="<?=$data['id']?>">
				<input name="rec" type="hidden" value="<?=$data['rec']?>">
				<input name="rec_id" type="hidden" value="<?=$data['rec_id']?>">
				<input name="rec_ref" type="hidden" value="<?=$data['inv']?>">
				<input name="rec_date" type="hidden" value="<?=date('d-m-Y')?>">
				<input name="rec_user" type="hidden" value="<?=$_SESSION['RGadmin']['name']?>">
				
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
						<td style="vertical-align:top; font-size:24px; font-weight:600"><?=$template[$lang.'_rec_type']?></td>
						<td style="padding:0">
							<div style="background:#fff; padding:10px 15px; border:1px solid #ddd; border-radius:5px; width:90%">
								<b><?=$data['customer']?></b><br>
								<?=nl2br($data['address'])?>
							</div>
						</td>
					</tr>
					<tr style="height:40px">
						<th colspan="2" style="font-size:20px">
							<div style="float:left"><?=$lng['Receipt']?> # :&nbsp;&nbsp;<?=$data['rec_id']?></div>
						</th>
					</tr>
					<tr>
						<td colspan="2" style="padding:0">
							<table border="0">
								<tr>
									<th style="padding-right:30px"><?=$lng['Receipt date']?> :</th>
									<th style="padding-right:30px"><?=$lng['Client ID']?> :</th>
									<th><?=$lng['Reference']?> :</th>
								</tr>
								<tr>
									<td style="padding-right:30px"><?=langDate(date('d-m-Y'), $lang)?></td>
									<td style="padding-right:30px"><?=$data['clientID']?></td>
									<td><?=$data['inv']?></td>
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
									<th class="tar" style="width:60px"><?=$lng['VAT']?>&nbsp;%</th>
									<th class="tar" style="width:100px"><?=$lng['Amount']?></th>
								</tr>
								</thead>
								<tbody>
								<? if($body){ foreach($body as $k=>$v){ ?>
								<tr>
									<td class="tac"><?=$k?></td>
									<td><?=$v['description']?></td>
									<td class="tac"><?=$v['quantity']?></td>
									<td class="tar"><?=$v['unit']?></td>
									<td class="tac"><?=$v['per']?></td>
									<td class="tar"><? if($v['amount'] > 0){echo $v['amount'];}?></td>
								</tr>
								<? }}else{$k = 0;} for($i=$k+1;$i<=9;$i++){ ?>
								<tr>
									<td class="tac"><?=$i?></td><td></td><td></td><td></td><td></td><td></td>
								</tr>
								  <? } ?>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="3" rowspan="4" style="padding:0 0 5px 0; vertical-align:bottom">
										<? if($data['wht_percent'] > 0){ ?>
										<table width="100%" border="0">
											<tr>
												<td colspan="2" style="border:0; font-size:14px">
													<?=$lng['Deduct WHT']?> :&nbsp;&nbsp;<b><?=$data['wht_percent']?></b>%&nbsp;&nbsp;<?=$lng['on']?>&nbsp;&nbsp;<b><?=number_format($data['subtotal'],2)?></b>&nbsp;&nbsp;<?=$lng['is']?>&nbsp;&nbsp;<b><?=number_format($data['wht_amount'],2)?></b>&nbsp;&nbsp;<?=$lng['Baht']?>
												</td>
											</tr>
											<tr>
												<th style="width:1px; white-space:nowrap; font-size:16px; border:0"><?=$lng['Net to Pay']?> :</th>
												<th style="font-size:16px; border:0"><?=number_format($data['net_amount'],2)?> <?=$lng['Baht']?></th>
											</tr>
										  	<tr>
											 	<td colspan="2" style="border:0"><?=$data['text_net']?></td>
										  	</tr>
										</table>
										<? } ?>
									</td>
									<th colspan="2"><?=$lng['Discount']?></th>
									<th class="tar"><?=number_format($data['discount'],2)?></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Sub total']?></th>
									<th class="tar"><?=number_format($data['subtotal'],2)?></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['VAT']?></th>
									<th class="tar"><?=number_format($data['vat'],2)?></th>
								</tr>
								<tr>
									<th colspan="2"><?=$lng['Grand total']?></th>
									<th class="tar"><?=number_format($data['total'],2)?></th>
								</tr>
								<tr>
									<td colspan="6" class="tar" style="border-bottom-width:1px; padding:6px 10px"><?=$data['text_total']?></td>
								</tr>
								</tfoot>
							</table>
								
						</td>
					</tr>
				</table>
				
				<div style="padding:0 5px 5px; margin-top:15px; border:1px solid #ccc">
				
				<table class="receiptTable" border="0" style="white-space:nowrap">
					<tbody>
						<tr style="border-bottom:1px solid #ddd">
							<th><i class="man"></i><?=$lng['Payment type']?> :</th>
							<? //if(!$view){ ?>
							<td style="padding:4px 8px">
								<select name="payment_type" id="payment_type" style="width:auto; border:0; padding:0;">
									<option disabled selected value=""><?=$lng['Select']?></option>
									<? foreach($paid_by as $k=>$v){ ?>
									<option <? if($data['payment_type'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
									<? } ?>
								</select>
							</td>
							<th><i class="man"></i><?=$lng['on date']?> : </th>
							<td style="padding:4px 8px">
								<input placeholder="<?=$lng['Paydate']?>" type="text" name="pay_date" readonly class="datepick" style="cursor:pointer; padding:0; width:200px" value="<?=langDate(date('d-m-Y'), $lang)?>">
							</td>
							<!--<? //}else{ ?>
							<td style="padding:4px 8px"><?=$paid_by[$paidby]?></td>
							<th>on date : </th>
							<td style="padding:4px 8px"><?=$pay_date?></td>
							<?	//} ?>-->
							<td style="width:80%"></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" style="width:100%; margin-top:10px">
					<tr>
						<td style="vertical-align:top; white-space:nowrap; width:40%">
							<b><?=$lng['Date']?> : </b><?=langDate(date('d-m-Y'), $lang)?><br>
							<b><?=$lng['Name']?> : </b><?=$_SESSION['RGadmin']['name']?>
						</td>
						<td style="width:40%; vertical-align:top">
							<b><?=$lng['Athorized signature']?> : </b><br>
							<img height="35px" src="<?=$template['signature'].'?'.time()?>" />
						</td>
						<td style="padding:0 30px 10px; vertical-align:top; position:relative; height:100px; min-width:200px; width:200px">
							<div style="position:absolute; top:0; left:5px"><img height="100px" src="<?=$template['stamp'].'?'.time()?>" /></div>
						</td>
					</tr>
				</table>
				</div>
				
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
			
			var id = <?=json_encode($id)?>;
			
			$('#payment_type').on('change', function () { 
				$("#submitBtn").prop('disabled', false);
			});
			
			$('#certificate').on('change', function () { 
				$("#certificateForm").submit();
			});
			$("#certificateForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$("#certBtn i").removeClass('fa-upload').addClass('fa-rotate-right fa-spin');
				$.ajax({
					url: AROOT+"billing/ajax/upload_certificate.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//$("#dump").html(result);
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Certificate uploaded successfuly',
								duration: 2,
							})
						}else{
							$("body").overhang({
								type: "warn",
								message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+result,
								duration: 4,
								closeConfirm: true
							})
						}
						setTimeout(function(){$("#certBtn i").removeClass('fa-rotate-right fa-spin').addClass('fa-upload');},500);
					},
					error:function (xhr, ajaxOptions, thrownError){
						$("body").overhang({
							type: "error",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
							duration: 8,
							closeConfirm: "true",
						})
						setTimeout(function(){$("#certBtn i").removeClass('fa-rotate-right fa-spin').addClass('fa-upload');},500);
					}
				});
			});

			$(document).on("click", "#print", function(e) {
				window.open(AROOT+'billing/print_receipt.php?nr='+id, '_blank')
			});
			$(document).on("click", "#sprint", function(e) {
				$("#sprint i").removeClass('fa-paper-plane').addClass('fa-rotate-right fa-spin');
				$.ajax({
					url: AROOT+"billing/ajax/ajax_print_receipt.php",
					data: {nr: nr},
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;eMail send successfuly<? //=$lng['Data updated successfuly']?>',
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

			$("#receiptForm").submit(function(e){ 
				e.preventDefault();
				if($('select[name="payment_type"]').val() == null || $('input[name="pay_date"]').val() == ''){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
						duration: 4,
						closeConfirm: true
					})
					return false;
				}
				var data = $(this).serialize();
				$.ajax({
					url: AROOT+"billing/ajax/save_receipt.php",
					data: data,
					type: 'POST',
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;<?=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							//$("#print").prop('disabled', false);
							//$("#sprint").prop('disabled', false);
							setTimeout(function(){location.reload();},2000);	
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
						














