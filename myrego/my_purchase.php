<?
	if(!isset($_GET['status'])){$_GET['status'] = 0;}
	
	$data['version'] = '';
	$data['price_net'] = 0;
	$data['price_topay'] = 0;
	
	$upgrade['version'] = '';
	$upgrade['max_employees'] = '';
	$upgrade['upgrade_period'] = '';
	$upgrade['period_start'] = '';
	$upgrade['period_end'] = '';
	$upgrade['price_year'] = '0.00';
	$upgrade['price_period'] = '0.00';
	$upgrade['price_remain'] = '0.00';
	$upgrade['price_discount'] = '0.00';
	$upgrade['price_sub'] = '0.00';
	$upgrade['price_vat'] = '0.00';
	$upgrade['price_wht'] = '0.00';
	$upgrade['price_topay'] = '0.00';
	$upgrade['price_net'] = '0';
	
	$new = $upgrade;
	
	if(isset($_SESSION['rego']['upgrade'])){
		$upgrade = $_SESSION['rego']['upgrade'];
		$data['version'] = $upgrade['version'];
		$data['price_net'] = $upgrade['price_net'];
		$data['price_topay'] = $upgrade['price_topay'];
	}
	if(isset($_SESSION['rego']['new'])){
		$new = $_SESSION['rego']['new'];
		$data['version'] = $new['version'];
		$data['price_net'] = $new['price_net'];
		$data['price_topay'] = $new['price_topay'];
	}
	//var_dump($version); //exit;
	//var_dump($new['version']); //exit;
		
	$template = array();
	$res = $dbx->query("SELECT * FROM rego_documents WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$template = $row;
	}	
	//var_dump($template); exit;
	
	$terms_conditions = '';
	$res = $dbx->query("SELECT * FROM rego_terms_conditions WHERE id = 1");
	if($row = $res->fetch_assoc()){
		$terms_conditions = $row[$lang.'_content'];
	}	
	//var_dump($terms_conditions); exit;
	
	
	$customer = array();
	$remaining = 0;
	$res = $dbx->query("SELECT * FROM rego_customers WHERE clientID = '".$_SESSION['rego']['cid']."'");
	if($row = $res->fetch_assoc()){
		$customer = $row;
		$diff = strtotime($row['period_end']) - strtotime(date('d-m-Y'));
		$remaining = floor($diff / (60*60*24));
		$remaining_days = $remaining;
		if($remaining == 1){$remaining_days .= ' '.$lng['day'];}else{$remaining_days .= ' '.$lng['day'];}
	}
	
	//$date_start = date('01-m-Y', strtotime(date('d-m-Y', strtotime($customer['period_start']))));
	//$date_end = date('d-m-Y', strtotime(date('d-m-Y', strtotime($customer['period_end']))));
	$complete = 1;
	if(empty($customer[$lang.'_compname']) || empty($customer[$lang.'_billing']) || empty($customer['tax_id'])){
		$complete = 0;
	}
	//var_dump($complete); exit;
	//var_dump($customer); exit;
	
	$company = array();
	$res = $dbc->query("SELECT * FROM ".$cid."_company_settings");
	if($row = $res->fetch_assoc()){
		$company = $row;
	}
	//var_dump($company); exit;
	
	/*$draft = array();
	if($res = $dbx->query("SELECT * FROM rego_purchase_draft WHERE cid = '".$cid."'")){
		if($row = $res->fetch_assoc()){
			$draft = $row;
			$draft['text_total'] = getWordsFromAmount($draft['price_total'], $lang);
			$draft['text_net'] = getWordsFromAmount($draft['price_net'], $lang);
		}
	}
	//var_dump($draft); exit;
	
	
	if(!$draft){*/
		/*$tmp = $dbx->query("SHOW COLUMNS FROM rego_purchase_draft");
		while($field = $tmp->fetch_object()){
			$draft[$field->Field] = '' ;
		}
		$draft['version'] = $customer['version'];
		if($customer['version'] == 0){
			$draft['max_employees'] = 0;
		}else{
			$draft['max_employees'] = $customer['employees'];
		}
		$draft['price_year'] = $price_table[$customer['version']]['price_year'];
		$draft['discount'] = '0.00';
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
	
		$draft['clientID'] = $rego;
		$draft['inv_date'] = date('d-m-Y');
		$draft['inv_due'] = date('d-m-Y');
		$draft['inv_number'] = '';*/
	
	//}
	//var_dump($draft); exit;

?>	

<link href="../assets/css/smart_wizard_theme_arrows.css?<?=time()?>" rel="stylesheet" type="text/css" />

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
	.bank-info p {
		padding:0;
		margin:0;
		font-weight:600;
		font-size:15px
	}
	.bank-info i {
		padding:0;
		margin:0;
		font-weight:500;
		color:#666;
		font-style:normal;
		font-size:14px;
	}
</style>			

<!-- PURCHASE FORM /////////////////////////////////////////////////////////////////////////////-->
<div id="mr_purchase" style="border:0px solid red; position:absolute; top:90px; left:0; right:0; bottom:0px">

	<div style="height:100%; width:50%; float:left">
		<div style="height:calc(100% - 0px); overflow-y:auto; padding:10px">
			<div id="dump"></div>

			<ul class="nav nav-tabs" id="myTab">
				<li class="nav-item"><a class="nav-link active" data-target="#tab_upgrade" data-toggle="tab"><?=$lng['Upgrade']?></a></li>
				<li class="nav-item"><a class="nav-link" data-target="#tab_new" data-toggle="tab"><?=$lng['New Subscription']?></a></li>
			</ul>
			
			<div class="tab-content" style="height:calc(100% - 40px)">
				
				<div class="tab-pane active" id="tab_upgrade">
					<form id="upgradeForm">
						<input name="cid" type="hidden" value="<?=$cid?>" />
						<input name="prev_price" type="hidden" value="<?=$customer['price_year']?>" />
						<input name="days_left" type="hidden" value="<?=$remaining?>" />
						<input name="certificate" type="hidden" value="<?=$customer['certificate']?>" />
						<input name="company" type="hidden" value="<?=$customer[$lang.'_compname']?>" />
		
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
									<th><?=$lng['Max employees']?></th>
									<td><?=$customer['employees']?></td>
								</tr>
								<tr>
									<th><?=$lng['Days left']?></th>
									<td><?=$remaining_days?></td>
									<th><?=$lng['Paid excl. VAT']?></th>
									<td style="text-align:right; font-weight:600">
										<?=number_format($customer['price_year'],2)?>
										
									</td>
								</tr>
								<tr>
									<th><?=$lng['Period start']?></th>
									<td style="padding:0"><input readonly name="prev_start" type="text" value="<?=$customer['period_start']?>" /></td>
									<th><?=$lng['Period end']?></th>
									<td style="padding:0"><input readonly name="prev_end" type="text" value="<?=$customer['period_end']?>" /></td>
								</tr>
							</tbody>
						</table>
						
						<table class="basicTable" border="0" style="margin-bottom:10px">
							<thead>
								<tr style="line-height:110%">
									<th colspan="4"><?=$lng['Upgrade']?></th>
								</tr>
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['Subscription']?></th>
									<td style="padding:0">
										<select id="upgrade_version" name="new_version" style="width:100%">
											<option disabled selected value=""><?=$lng['Select']?></option>
											<? foreach($version as $k=>$v){ if($k > 0){ ?>
											<option <? if($upgrade['version'] == $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? }} ?>
										</select>
									</td>
									<th><?=$lng['Max employees']?></th>
									<td style="padding:0; width:15%"><input readonly name="max_employees" type="text" value="<?=$upgrade['max_employees']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Upgrade period']?></th>
									<td style="padding:0">
										<select onChange="$('#upgrade_version').trigger('change');" name="upgrade_period" style="width:100%">
											<option <? if($upgrade['upgrade_period'] == 'fullyear'){echo 'selected';}?> value="fullyear">Full Year from today</option>
											<option <? if($upgrade['upgrade_period'] == 'remaining'){echo 'selected';}?> value="remaining">Remaining Period</option>
											<option <? if($upgrade['upgrade_period'] == 'extend'){echo 'selected';}?> value="extend">Current Period + 1 year</option>
										</select>
									</td>
									<th><?=$lng['Price full year']?></th>
									<td style="padding:0">
										<input name="price_year" style="font-weight:600; text-align:right; color:#a00" type="text" value="<?=$upgrade['price_year']?>" />
									</td>
								</tr>
								<tr>
									<th><?=$lng['Period start']?></th>
									<td style="padding:0"><input placeholder="..." readonly name="period_start" type="text" value="<?=$upgrade['period_start']?>" /></td>
									<th colspan="1">Price period<? //=$lng['Price period ex. VAT']?></th>
									<td style="padding:0">
										<input name="price_period" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_period']?>" />
									</td>
								</tr>
								<tr>
									<th><?=$lng['Period end']?></th>
									<td style="padding:0"><input placeholder="..." readonly name="period_end" type="text" value="<?=$upgrade['period_end']?>" /></td>
									<th>Remaining period<? //=$lng['Remaining period']?></th>
									<td style="padding:0">
										<input name="price_remain" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_remain']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th style="border-bottom:1px solid #ccc"><?=$lng['Discount']?></th>
									<td style="padding:0; border-bottom:1px solid #ccc">
										<input name="price_discount" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_discount']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th><?=$lng['Subtotal']?></th>
									<td style="padding:0">
										<input name="price_sub" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_sub']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th><?=$lng['VAT']?></th>
									<td style="padding:0">
										<input name="price_vat" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_vat']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th><?=$lng['WHT']?></th>
									<td style="padding:0">
										<input name="price_wht" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_wht']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th style="border-bottom:2px solid #ccc">Price upgrade<? //=$lng['Price upgrade']?></th>
									<td style="padding:0; border-bottom:2px solid #ccc">
										<input name="price_topay" style="font-weight:600; text-align:right" type="text" value="<?=$upgrade['price_topay']?>" />
									</td>
								</tr>
								
								<tr>
									<th colspan="4" style="border-right:0; text-align:left">
										<i class="man"></i> <a onClick="$('#termsModal').modal('toggle');" id="terms" style="color:#a00"><?=$lng['I agree with the terms and conditions from REGO']?></a> &nbsp;&nbsp;<label><input type="checkbox" class="checkbox style-0" name="agree" id="agree" value="1"><span></span></label>
									</th>
								</tr>
							</tbody>
						</table>
						
						<button id="confirmBtn" style="margin:0 5px 0 0" class="btn btn-primary btn-fr" type="submit"><i class="fa fa-check"></i>&nbsp; <?=$lng['Confirm purchase']?></button>
					
					</form>
				</div>
				
				<div class="tab-pane xactive" id="tab_new" style="overflow-y:auto; height:100%">
					<form id="newForm">
						<input name="cid" type="hidden" value="<?=$cid?>" />
						<input name="certificate" type="hidden" value="<?=$customer['certificate']?>" />
						<input name="company" type="hidden" value="<?=$customer[$lang.'_compname']?>" />
		
						<table class="basicTable" border="0" style="margin-bottom:10px">
							<thead>
								<tr style="line-height:110%">
									<th colspan="4"><?=$lng['New Subscription']?></th>
								</tr>
							<tbody>
								<tr>
									<th><i class="man"></i><?=$lng['New Subscription']?></th>
									<td style="padding:0">
										<select id="new_subscription" name="new_version" style="width:100%">
											<option disabled selected value=""><?=$lng['Select']?></option>
											<? foreach($version as $k=>$v){ ?>
											<option <? if($new['version'] === $k){echo 'selected';}?> value="<?=$k?>"><?=$v?></option>
											<? } ?>
										</select>
									</td>
									<th><?=$lng['Max employees']?></th>
									<td style="padding:0; width:15%"><input placeholder="..." readonly name="max_employees" type="text" value="<?=$new['max_employees']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Period start']?></th>
		
									<td style="padding:0"><input name="period_start" readonly type="text" value="<?=date('d-m-Y')?>" /></td>
									
									<th><?=$lng['Price full year']?></th>
									<td style="padding:0"><input name="price_year" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_year']?>" /></td>
								</tr>
								<tr>
									<th><?=$lng['Period end']?></th>
									<td style="padding:0"><input name="period_end" readonly type="text" value="<?=date('d-m-Y', strtotime('+1 year'))?>" /></td>
									
									<th style="border-bottom:1px solid #ccc"><?=$lng['Discount']?></th>
									<td style="padding:0; border-bottom:1px solid #ccc"><input name="price_discount" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_discount']?>" /></td>
								</tr>
								<tr>
									<th><i class="man"></i><?=$lng['Company name']?></th>
									<td style="padding:0"><input placeholder="..." type="text" value="" /></td>
									<th><?=$lng['Subtotal']?></th>
									<td style="padding:0"><input name="price_sub" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_sub']?>" /></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th><?=$lng['VAT']?></th>
									<td style="padding:0"><input name="price_vat" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_vat']?>" /></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th><?=$lng['WHT']?></th>
									<td style="padding:0"><input name="price_wht" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_wht']?>" /></td>
								</tr>
								<tr>
									<td colspan="2"></td>
									<th style="border-bottom:2px solid #ccc"><?=$lng['Net to pay']?></th>
									<td style="padding:0; border-bottom:2px solid #ccc"><input name="price_topay" style="font-weight:600; text-align:right" type="text" value="<?=$new['price_topay']?>" /></td>
								</tr>
								
								<tr>
									<th colspan="4" style="border-right:0; text-align:left">
										<i class="man"></i> <a onClick="$('#termsModal').modal('toggle');" id="terms" style="color:#a00"><?=$lng['I agree with the terms and conditions from REGO']?></a> &nbsp;&nbsp;<label><input type="checkbox" class="checkbox style-0" name="agree" id="agree" value="1"><span></span></label>
									</th>
								</tr>
							</tbody>
						</table>
						
						<button id="confirmBtn" style="margin:0 5px 0 0" class="btn btn-primary btn-fr" type="button"><i class="fa fa-check"></i>&nbsp; <?=$lng['Confirm purchase']?></button>
					
					</form>
				</div>
				
			</div>

		</div>
	</div>

	<div style="height:100%; width:50%; float:right; border-left:1px solid #ccc; position:relative">
		<div style="height:calc(100% - 0px); overflow-y:auto; padding:15px">	
			<div id="dump2"></div>	
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
							<img style="float:right; padding-top:6px" width="100%" src="../images/cards6.png" />
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
								<tr style="border:0">
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
								<p style="font-size:18px; color:#a00"><b><?=$lng['Redirecting to your Inline banking']?> . . . <?=$lng['Please wait']?> . . .</b></p>
							</div>
							
							<div id="bankingSuccess" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); text-align:center; font-size:24px; color:#a00; padding-top:35px; display:none">
								<b><p><?=$lng['Thank you']?><br><?=$lng['Your payment has been successful']?></p></b>
							</div>
							
							<div id="bankingFailed" style="position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); text-align:center; font-size:24px; color:#a00; padding-top:35px; display:none">
								<b><p><?=$lng['Sorry, payment failed']?><br><?=$lng['Please try again']?></p></b>
							</div>
	
						</td>
					</tr>
				</tbody>
			</table>
		
			<table class="basicTable" border="0">
				<thead>
					<tr style="line-height:110%">
						<th colspan="4"><?=$lng['Pay with Bank transfer']?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="vertical-align:top; width:40%; padding:0 0 10px; border:0">
							<center><img style="width:100%; max-width:100%" src="../images/qrcode_bank.png"></center>
						</td>
						<td class="bank-info" style="vertical-align:baseline; width:60%; padding:10px 10px 0 20px">
							<? if($lang == 'en'){ ?>
							<p>Bangkok Bank<p>
							<p>Siamcountryclub Branch</p>
							<p><i>Account name : </i>Xray ICT and Consulting Co.,Ltd.</p>
							<p><i>Account no. : </i>701-703247-0</p>
							<p><i>Branch code : </i>2166</p>
							<p><i>Swiftcode : </i>BKKBTHBK</p>
							<p><i>Amount to pay<? //=$lng['Amount to pay']?> : </i><span class="priceTot">0.00<? //=$price['tot']?></span> <?=$lng['Baht']?></p>
							<? }else{ ?>
							<p>รายละเอียดของธนาคาร<p>
							<p>ธนาคารกรุงเทพ สาขาสยามคันทรีคลับ</p>
							<i>ชื่อบัญชี :</i>
							<p>บจ.เอ็กซ์เรย์ ไอซีที แอนด์ คอนซัลติ้ง</p>
							<p><i>บัญชีเลขที่ : </i>701-703247-0</p>
							<p><i>รหัสสาขา : </i>2166</p>
							<p><i>สวิฟท์โค้ด : </i>BKKBTHBK</p>
							<p><i>Amount to pay<? //=$lng['Amount to pay']?> : </i><span class="priceTot">0.00<? //=$price['tot']?></span> <?=$lng['Baht']?></p>
							<? } ?>
							<div style="padding-top:10px; white-space:normal">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas posuere augue ex, vel molestie nisi maximus id. Fusce sed massa vel turpis suscipit pulvinar et id sem. Nullam bibendum ex a mauris venenatis, sed imperdiet risus finibus. Etiam fringilla porttitor suscipit. Fusce sollicitudin quam quis sem pulvinar, venenatis vulputate massa tempor. Nulla id nisl viverra, cursus purus sed, commodo tortor. Maecenas tincidunt odio at nunc gravida rhoncus. Etiam ultrices tellus eget faucibus ornare. Aliquam efficitur risus id odio blandit dignissim.
							</div>
						</td>
					</tr>
				</tbody>
			</table>

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
							<input id="card_amount" type="hidden" value="<?=$data['price_net']?>" />
							<input id="card_version" type="hidden" value="<?=$data['version']?>" />
							<input id="card_company" type="hidden" value="<? //=$compinfo[$lang.'_compname']?>" />
							<table width="100%" style="margin-bottom:15px">
								<tr style="line-height:130%">
									<td style="width:40px"><img height="40" width="40" src="../images/logo.png" /></td>
									<td style="padding-left:10px"><b style="font-size:16px">REGO HR</b><br><span style="color:#999"><i class="fa fa-lock"></i>&nbsp; <?=$lng['Secured by']?> Omise</span></td>
								</tr>
								<tr>
									<td colspan="2" style="padding:20px 0 0 0">
										<b style="font-size:20px"><?=$lng['Credit']?> / <?=$lng['Debet']?></b>
									</td>
								</tr>
							</table>
							<table width="100%">
								<tr>
									<td colspan="2">
										<label><?=$lng['Total amount']?> (<?=$lng['THB']?>)</label>
										<input style="border:1px solid #ddd !important; cursor:default; font-weight:600" readonly name="totAmount" type="text" value="<?=$data['price_topay']?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<label><?=$lng['Card number']?></label>
										<input class="creditcard_number numeric20" name="number" id="number" type="text" value="4111111111111111" />
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
										<span style="color:#aaa"><?=$lng['Secured by']?></span> <img height="18" style="display:inline-block" src="../images/omise.png" /> <b>Omise</b>
									</td>
								</tr>
							</table>
						</form>
						
					</div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal Terms & Conditions -->
	<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:900; min-width:900px">
			  <div class="modal-content">
					<div class="modal-header">
						 <h4 class="modal-title" id="myModalLabel"><i class="fa fa-file-text-o"></i>&nbsp; <?=$lng['Terms & Conditions']?></h4>
					</div>
					<div class="modal-body" style="padding:0">
						<div style="padding:20px 25px 25px 25px; height:500px; overflow-y:auto">
							<?=$terms_conditions?>	
						</div>
						<button style="margin:15px 0 25px 25px" data-dismiss="modal" class="btn btn-primary" type="button"><i class="fa fa-times"></i>&nbsp; <?=$lng['Close']?></button>
					</div>
			  </div>
		 </div>
	</div>
	
	<!-- Modal INCOMPLETE -->
	<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		 <div class="modal-dialog" style="width:300px">
			  <div class="modal-content" style="border-radius:10px">
					<div class="modal-body" style="padding:30px 25px 25px 25px; text-align:center; font-size:16px">
						<p><?=$lng['Please complete billing information first']?></p>	
						<button onClick="location.href='index.php?mn=6'" style="margin:5px 0 0 0" data-dismiss="modal" class="btn btn-primary" type="button"><i class="fa fa-external-link"></i>&nbsp; <?=$lng['Billing information']?></button>
					</div>
			  </div>
		 </div>
	</div>
	
	<script src="../assets/js/jquery.mask.js"></script>	
	<script src="../assets/js/omise.js"></script>	
	
<script type="text/javascript">
	
	Omise.setPublicKey("pkey_test_5ibhcak45cr121wkt2l");
		
	/*String.prototype.capitalize = function() {
		 return this.charAt(0).toUpperCase() + this.slice(1);
	}*/			

	$(".creditcard_number").mask("9999  9999  9999  9999", {placeholder: "....  ....  ....  ...."});
	$(".expire_date").mask("99 / 99", {placeholder: "MM / YY"});
	$(".security_code").mask("999", {placeholder: "..."});

	$(document).ready(function() {
		
		var amount = <?=json_encode($data['price_net'])?>;
		var price = <?=json_encode($price_table)?>;
		var version = <?=json_encode($version)?>;
		var complete = <?=json_encode($complete)?>;
		var status = <?=json_encode($_GET['status'])?>;
		
		if(status == 'successful'){
			//$("#sConfirmed").removeClass('active')
			//$("#sPaid").addClass('active')
			$('#bankingSuccess').fadeIn(200);
			setTimeout(function(){
				$('#bankingSuccess').fadeOut(400)
				//$('#paymentOverlay').fadeOut(200)
				//location.href='index.php?mn=8';
			}, 5000);
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
			setTimeout(function(){$('#bankingFailed').fadeOut(400)}, 5000);
		}
		
		$("#ccButton").on('click', function(e){
			$('#cardMsg').html('').hide();
			$('#modalCard').modal('toggle');
		}) 
		$('#modalCard').on('hidden.bs.modal', function () {
			//$(this).find('form').trigger('reset');
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
				if(response.object == "error") {
					$("#cardMsg").html(response.message).hide().fadeIn(300);
					$(".payButton").prop("disabled", false);
				}else{
					setTimeout(function(){
						$.ajax({
							type: "POST",
							url: ROOT+"myrego/ajax/omise_charge_card.php",
							data: {data: response, amount: amount},
							success: function(response){
								//$('#dump2').html(response); return false; 
								if(response == 'successful'){
									$('.payButton i').removeClass('fa-refresh fa-spin').addClass('fa-lock');
									$('#cardMsg').html('Thanks, your payment was successful').hide().fadeIn(300);
									setTimeout(function(){
										$('#modalCard').modal('toggle');
									}, 2000);
									/*$.ajax({
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
									});*/
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
				"amount": amount,
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
							data: {amount: amount, source: source},
							success: function(response){
								//$('#dump2').html(response); 
								window.open(response, '_self') 
								return false
							},
							error:function (xhr, ajaxOptions, thrownError){
								//alert('error ' + thrownError);
								$('#internetMsg').html('Error 1: ' + thrownError).hide().fadeIn(300);
							}
						});
					}, 1000);
				};
			});
			return false;
		})
		
		$('#upgrade_version').on('change', function(e){
			var version = this.value;
			//alert(version)
			var formData = $("#upgradeForm").serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/ajax_calculate_upgrade.php",
				data: formData,
				dataType: 'json',
				success: function(data){
					//$('#dump').html(data); return false;
					//alert(data.new_start)
					$('#upgradeForm input[name="max_employees"]').val(price[version]['max_employees']);
					$('#upgradeForm input[name="period_start"]').val(data.period_start);
					$('#upgradeForm input[name="period_end"]').val(data.period_end);
					$('#upgradeForm input[name="price_year"]').val(data.price_year);
					$('#upgradeForm input[name="price_period"]').val(data.price_period);
					$('#upgradeForm input[name="price_remain"]').val(data.price_remain);
					$('#upgradeForm input[name="price_discount"]').val(data.price_discount);
					$('#upgradeForm input[name="price_sub"]').val(data.price_sub);
					$('#upgradeForm input[name="price_vat"]').val(data.price_vat);
					$('#upgradeForm input[name="price_wht"]').val(data.price_wht);
					$('#upgradeForm input[name="price_topay"]').val(data.price_topay);
					
					$('input[name="totAmount"]').val(data.price_topay);
					$('#card_version').val(version);
					$('#card_amount').val(data.price_net);
					amount = data.price_net;
					clearNewForm();
				},
				error:function (xhr, ajaxOptions, thrownError){
					$("body").overhang({
						type: "error",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Sorry but someting went wrong']?> <b><?=$lng['Error']?></b> : '+thrownError,
						duration: 8,
						closeConfirm: "true",
					})
				}
			})
		}) 
		
		/*$('#upgrade_period').on('change', function(e){
			$("#upgradeForm").submit();
		})*/
		function clearNewForm(){
			$('#new_subscription').val('');
			$('#newForm input[name="max_employees"]').val('');
			$('#newForm input[name="price_year"]').val('0.00');
			$('#newForm input[name="price_discount"]').val('0.00');
			$('#newForm input[name="price_sub"]').val('0.00');
			$('#newForm input[name="price_vat"]').val('0.00');
			$('#newForm input[name="price_wht"]').val('0.00');
			$('#newForm input[name="price_topay"]').val('0.00');
		} 
		function clearUpgradeForm(){
			$('#upgrade_version').val('');
			$('#upgradeForm input[name="max_employees"]').val('');
			$('#upgradeForm input[name="period_start"]').val('');
			$('#upgradeForm input[name="period_end"]').val('');
			$('#upgradeForm input[name="price_year"]').val('0.00');
			$('#upgradeForm input[name="price_period"]').val('0.00');
			$('#upgradeForm input[name="price_remain"]').val('0.00');
			$('#upgradeForm input[name="price_discount"]').val('0.00');
			$('#upgradeForm input[name="price_sub"]').val('0.00');
			$('#upgradeForm input[name="price_vat"]').val('0.00');
			$('#upgradeForm input[name="price_wht"]').val('0.00');
			$('#upgradeForm input[name="price_topay"]').val('0.00');
		} 
		
		$('#new_subscription').on('change', function(e){
			var version = this.value;
			var formData = $("#newForm").serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/ajax_calculate_subscription.php",
				data: formData,
				dataType: 'json',
				success: function(data){
					//$('#dump2').html(data); return false;
					$('#newForm input[name="max_employees"]').val(price[version]['max_employees']);
					//$('#newForm input[name="period_start"]').val(data.period_start);
					//$('#newForm input[name="period_end"]').val(data.new_end);
					$('#newForm input[name="price_year"]').val(data.price_year);
					$('#newForm input[name="price_discount"]').val(data.price_discount);
					$('#newForm input[name="price_sub"]').val(data.price_sub);
					$('#newForm input[name="price_vat"]').val(data.price_vat);
					$('#newForm input[name="price_wht"]').val(data.price_wht);
					$('#newForm input[name="price_topay"]').val(data.price_topay);
					
					$('input[name="totAmount"]').val(data.price_topay);
					$('#card_version').val(version);
					$('#card_amount').val(data.price_net);
					amount = data.price_net;
					clearUpgradeForm();
				}
			})
		}) 
		
		$('#xxxconfirmBtn').on('click', function(){
			$('#confirmBtn i').removeClass('fa-check').addClass('fa-repeat fa-spin');
			if($('input[name="agree"]').is(':checked') == false){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please agree with our Terms & Conditions<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#confirmBtn i").removeClass('fa-repeat fa-spin').addClass('fa-check');},500);
				return false;
			}			
			if($("#price_upgrade").val() == 0){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;There is nothing to pay<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#confirmBtn i").removeClass('fa-repeat fa-spin').addClass('fa-check');},500);
				return false;
			}			
			var formData = $("#upgradeForm").serialize() + '&confirm';
			//formData += '&confirm';
			$.ajax({
				url: ROOT+"myrego/ajax/ajax_purchase_rego.php",
				data: formData,
				success: function(result){
					//$('#dump2').html(result); return false;
					//$("#confirmBtn").prop('disabled', true)
					setTimeout(function(){location.href = 'index.php?mn=8';},500);
					
				}
			})
		})
	
		$("#upgradeForm").submit(function (e) {
			e.preventDefault();
			var err = 0;
			/*if($('#upgrade_version').val() == null){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select Subscription<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				//setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}
			
			if($('#upgrade_version').val() == $('#old_version').val()){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please select a higher Subscription<? //=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				//setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}
			/*if($('select[name="subscription"]').val() == null){err = 1;}
			if($('input[name="phone"]').val() == ''){err = 1;}
			if($('select[name="iam"]').val() == null){err = 1;}
			if($('input[name="compname"]').val() == ''){err = 1;}
			if($('textarea[name="address"]').val() == ''){err = 1;}
			if($('textarea[name="billing"]').val() == ''){err = 1;}
			if($('input[name="tax_id"]').val() == '' && $('select[name="iam"]').val() == '0'){err = 1;}
			if($('select[name="wht"]').val() == null){err = 1;}*/
			/*if(err){
				$("body").overhang({
					type: "warn",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please fill in required fields']?>',
					duration: 4,
					closeConfirm: true
				})
				setTimeout(function(){$("#submitbtn i").removeClass('fa-refresh fa-spin').addClass('fa-save');},500);
				return false;
			}*/
			
			
			//alert()
			var formData = $(this).serialize();
			$.ajax({
				url: ROOT+"myrego/ajax/ajax_calculate_upgrade.php",
				data: formData,
				//dataType: 'json',
				success: function(data){
					$('#dump2').html(data); return false;
					
					$("#confirmBtn").prop('disabled', false)
					$("#new_start").val(data.period_start)
					$("#new_end").val(data.period_end)
					$("#pricePeriod").html(parseFloat(data.price_period).format(2))
					$("#priceDiscount").html(parseFloat(data.price_discount).format(2))
					$("#priceRemain").html(parseFloat(data.price_remain).format(2))
					$("#priceUpgrade").html(parseFloat(data.price_upgrade).format(2))
					$("#price_period").val(data.price_period)
					//$("#price_discount").val(data.price_discount)
					$("#price_remain").val(data.price_remain)
					$("#price_upgrade").val(data.price_sub)
					
					$("#confirmBtn").prop('disabled', false)
					//var amount = parseFloat(data.price_period) - parseFloat(data.price_remain);
					$("#inv_unit").html(parseFloat(data.price_amount).format(2))
					$("#inv_amount").html(parseFloat(data.price_amount).format(2))
					
					$("#inv_version").html(version[data.version])
					$("#inv_max_employees").html(data.max_employees)
					$("#inv_period_start").html(data.period_start)
					$("#inv_period_end").html(data.period_end)
					
					$("#inv_discount").html(parseFloat(data.price_discount).format(2))
					$("#inv_sub").html(parseFloat(data.price_upgrade).format(2))
					$("#inv_vat").html(parseFloat(data.price_vat).format(2))
					$("#inv_total").html(parseFloat(data.price_total).format(2))
					
					$("#wht_amount").html(parseFloat(data.price_upgrade).format(2))
					$("#inv_wht").html(parseFloat(data.price_wht).format(2))
					$("#inv_net").html(parseFloat(data.price_net).format(2))
					
					$("#pd_amount").html(parseFloat(data.price_net).format(2))
					
					$("#text_net").html(data.text_net)
					$("#text_total").html(data.text_total)
					
					$.ajax({
						url: ROOT+"ajax/get_words_from_amount.php",
						data: {amount:data.price_total},
						success: function(data){
							$("#text_total").html(data)
						}
					})
					$.ajax({
						url: ROOT+"ajax/get_words_from_amount.php",
						data: {amount: data.price_net},
						success: function(data){
							$("#text_net").html(data)
						}
					})
					return false;
					
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfully, please proceed to payment',
							duration: 8,
							closeConfirm: true
						})
						$("#overlay").fadeOut(400);
					}
					return false;
					
					if(response == 'success'){
						$("body").overhang({
							type: "success",
							message: '<i class="fa fa-check"></i>&nbsp;&nbsp;eMail sed to ... ?',
							duration: 2,
						})
					}else{
						$("body").overhang({
							type: "warn",
							message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Error']?> : '+response,
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
		
		if(complete == 0){
			$('#completeModal').modal('toggle');
		}
		
/*		$('#processing').on('click', function(e){
			//alert('Set status back to Processing')
			$.ajax({
				url: ROOT+"myrego/ajax/unconfirm_purchase.php",
				success: function(result){
					//alert(result)
					location.href = 'index.php?mn=7';
					//$("#sConfirmed").removeClass('active')
					//$("#sProcess").addClass('active')
					//$("#confirmBtn").prop('disabled', false)
					//setTimeout(function(){},500);
					
				}
			})
		}) 
*/			

	});
	
</script>

















