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
						<th>Invoice #</th>
						<th>Date</th>
						<th class="tar">Amount</th>
						<th class="tac" style="width:1px">Status</th>
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
			<div id="dump2"></div>
		</div>
	</div>

	<div style="height:100%; background:#f9f9f9; width:68%; float:right; border-left:1px solid #ccc; position:relative">

		<div class="breadcrumbs" style="z-index:9999">
			<a data-id="#myInvoice" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Invoice']?></a>
			<a data-id="#myCertificate" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; WHT Certificate<? //=$lng['Invoice']?></a>
			<a data-id="#myTaxInvoice" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; Tax Invoice<? //=$lng['Invoice']?></a>
			<a class="activ" data-id="#myPayslip" style="border-right:1px solid #ccc"><i class="fa fa-file-text-o"></i>&nbsp; Payslip<? //=$lng['Invoice']?></a>
			<a style="border-left:1px solid #ccc; float:right"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></a>
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
			
			<div class="myDocument" id="myCertificate" style="box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left">
				<canvas style="width:880px; height:1245px; display:block" id="whtCertificate"></canvas>
			</div>
			
			<!--<div class="myDocument" id="whtCertificate" style="width:880px; height:1245px; box-shadow:0 0 10px rgba(0,0,0,0.2); background:url(../images/wht_certificate.png) no-repeat; background-size: cover; position:relative; display:none">
				<div class="field" style="top:33.8mm;left:148.2mm">0</div>
					<div class="field" style="top:33.8mm;left:155.4mm">0</div>
					<div class="field" style="top:33.8mm;left:160mm">0</div>
					<div class="field" style="top:33.8mm;left:164.6mm">0</div>
					<div class="field" style="top:33.8mm;left:169.4mm">0</div>
					<div class="field" style="top:33.8mm;left:176.8mm">0</div>
					<div class="field" style="top:33.8mm;left:181.4mm">0</div>
					<div class="field" style="top:33.8mm;left:185.8mm">0</div>
					<div class="field" style="top:33.8mm;left:190.6mm">0</div>
					<div class="field" style="top:33.8mm;left:195.4mm">0</div>
					<div class="field" style="top:33.8mm;left:202.4mm">0</div>
					<div class="field" style="top:33.8mm;left:207.2mm">0</div>
					<div class="field" style="top:33.8mm;left:214.8mm">0</div>

					<div class="field" style="top:40.6mm;left:22mm">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>
					<div class="field" style="top:49.8mm;left:24mm">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>

					<div class="field" style="top:60.6mm;left:148.2mm">0</div>
					<div class="field" style="top:60.6mm;left:155.4mm">0</div>
					<div class="field" style="top:60.6mm;left:160mm">0</div>
					<div class="field" style="top:60.6mm;left:164.6mm">0</div>
					<div class="field" style="top:60.6mm;left:169.4mm">0</div>
					<div class="field" style="top:60.6mm;left:176.8mm">0</div>
					<div class="field" style="top:60.6mm;left:181.4mm">0</div>
					<div class="field" style="top:60.6mm;left:185.8mm">0</div>
					<div class="field" style="top:60.6mm;left:190.6mm">0</div>
					<div class="field" style="top:60.6mm;left:195.4mm">0</div>
					<div class="field" style="top:60.6mm;left:202.4mm">0</div>
					<div class="field" style="top:60.6mm;left:207.2mm">0</div>
					<div class="field" style="top:60.6mm;left:214.8mm">0</div>

					<div class="field" style="top:69mm;left:22mm">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>
					<div class="field" style="top:79.2mm;left:24mm">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>

					<div class="field" style="top:97.2mm;left:156.2mm"><b>X</b></div>

					<div class="field" style="top:241mm;right:76mm">00-00-000</div>
					<div class="field" style="top:241mm;right:41.4mm">0.00</div>
					<div class="field" style="top:241mm;right:13.6mm">0.00</div>

					<div class="field" style="top:254.8mm;right:41.4mm">0.00</div>
					<div class="field" style="top:254.8mm;right:13.6mm">0.00</div>

					<div class="field" style="top:262.8mm;left:75mm">xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>

					<div class="field" style="top:296.6mm;left:135mm">00</div>
					<div class="field" style="top:296.6mm;left:152mm">00</div>
					<div class="field" style="top:296.6mm;left:172mm">0000</div>
			</div>-->
			
			<!--<div class="myDocument" id="myTaxInvoice" style="width:880px; box-shadow:0 0 10px rgba(0,0,0,0.2); padding:30px; background:#fff; display:none; opacity:0.5">
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
						<td style="vertical-align:top; font-size:24px; font-weight:600"><?=strtoupper($lng['Receipt / Tax Invoice'])?></td>
						<td style="padding:0">
							<div style="background:#fff; padding:10px 15px; border:1px solid #ddd; border-radius:5px; width:90%">
								<b style="font-size:15px"><?=$company[$lang.'_compname']?></b>
								<p><?=nl2br($company[$lang.'_address'])?></p>
							</div>
						</td>
					</tr>
					<tr style="height:40px">
						<th colspan="2" style="font-size:20px">
							<div style="float:left"><?=$lng['Receipt']?> # :&nbsp;&nbsp;<? //=$draft['inv_number']?>	
						</th>
					</tr>
					<tr>
						<td colspan="2" style="padding:0">
							<table border="0">
								<tr>
									<th style="padding-right:30px"><?=$lng['Receipt date']?> :</th>
									<th style="padding-right:40px"><?=$lng['Client ID']?> :</th>
									<th><?=$lng['Reference']?> :</th>
								</tr>
								<tr>
									<td><?=$draft['inv_date']?></td>
									<td><?=$rego?></td>
									<td></td>
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
										<? if($customer['wht']){ ?>
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

				<div style="padding:0 5px 5px; margin-top:15px; border:1px solid #ccc">
				
				<table class="receiptTable" border="0" style="white-space:nowrap">
					<tbody>
						<tr style="border-bottom:1px solid #ddd">
							<th>Paid by :</th>
							<td style="padding:4px 8px"><? //=$paid_by[$paidby]?></td>
							<th>on date : </th>
							<td style="padding:4px 8px"><? //=$pay_date?></td>
							<td style="width:80%"></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" style="width:100%; margin-top:10px">
					<tr>
						<td style="vertical-align:top; white-space:nowrap; width:40%">
							<b>Date : </b><?=date('d-m-Y')?><br>
							<b>Name : </b><? //=$_SESSION['rego']['name']?>
						</td>
						<td style="width:40%; vertical-align:top">
							<b>Authorized signature : </b><br>
							<img height="35px" src="<?=$template['signature'].'?'.time()?>" />
						</td>
						<td style="padding:0 30px 10px; vertical-align:top; position:relative; height:100px; min-width:200px; width:200px">
							<div style="position:absolute; top:0; left:5px"><img height="100px" src="<?=$template['stamp'].'?'.time()?>" /></div>
						</td>
					</tr>
				</table>
				</div>
				
				
				
				
				
				<table class="receiptTable" border="0" style="width:100%; white-space:nowrap; border:1px solid #ddd; display:none">
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
			
			<div class="myDocument" id="myPayslip" style="box-shadow:0 0 10px rgba(0,0,0,0.2); position:relative; display:none; float:left">
				<canvas style="width:880px; height:1245px; display:block" id="whtPayslip"></canvas>
			</div>
		
		</div>
	
	</div>

</div>

<script src="../showPDF/pdf.js"></script>
<script src="../showPDF/pdf.worker.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {
		
		$(".history tbody tr").on('click', function(e){
			var inv = $(this).closest('tr').find('.invNr').html();
			//alert(inv)
			$.ajax({
				url: ROOT+"myrego/ajax/get_documents.php",
				data: {inv: inv},
				dataType: 'json',
				success: function(data){
					//$('#dump2').html(data); return false;
					//location.reload();
					//alert(data.payslip)
					//alert(data.certificate)
					
					//var url = ROOT+'admin/billing/documents/payslip_IV202001-0001.pdf';
					showDocument(data.payslip, 'whtPayslip');
					showDocument(data.certificate, 'whtCertificate');
					
					return false;
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
		
		$(".breadcrumbs a").on('click', function(e){
			var id = $(this).data('id');
			$(".breadcrumbs a").removeClass('activ');
			$(".myDocument").hide();
			$(this).addClass('activ');
			$(id).fadeIn(200);
			//alert(id)
		}) 
		$('#modalCard').on('hidden.bs.modal', function () {
			$(this).find('form').trigger('reset');
			$("#cardMsg").html('');
		});

	
		// If absolute URL from the remote server is provided, configure the CORS
		// header on that server.
		// Disable workers to avoid yet another cross-origin issue (workers need
		// the URL of the script to be loaded, and dynamically loading a cross-origin
		// script does not work).
		// PDFJS.disableWorker = true;
		
		// The workerSrc property shall be specified.
		//PDFJS.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';
		
		// Asynchronous download of PDF
		function showDocument(url, id){
			var loadingTask = PDFJS.getDocument(url);
			loadingTask.promise.then(function(pdf) {
				pdf.getPage(1).then(function(page) {
					var viewport = page.getViewport(1.5);
					var canvas = document.getElementById(id);
					var context = canvas.getContext('2d');
					canvas.height = viewport.height;
					canvas.width = viewport.width;
					var renderContext = {
						canvasContext: context,
						viewport: viewport
					};
					var renderTask = page.render(renderContext);
					/*renderTask.then(function () {
						console.log('Page rendered');
					});*/
				});
			}, function (reason) {
				// PDF loading error
				//console.error(reason);
			});
		}
		
	
	
	
	});
	
	
	



</script>

















