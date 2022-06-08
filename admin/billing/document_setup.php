<?php
	
	$data = array();
	$res = $dba->query("SELECT * FROM rego_documents");
	if($row = $res->fetch_assoc()){
		$data = $row;
	}
	if(!isset($data['logo']) || empty($data['logo'])){$data['logo'] = '../images/notavailable.jpg';}
	if(!isset($data['stamp']) || empty($data['stamp'])){$data['stamp'] = '../images/notavailable.jpg';}
	if(!isset($data['signature']) || empty($data['signature'])){$data['signature'] = '../images/notavailable.jpg';}
		
	//var_dump($data); exit;
?>

  	<link rel="stylesheet" href="<?=ROOT?>summernote/dist/summernote-lite.css?<?=time()?>">
  	<script type="text/javascript" src="<?=ROOT?>summernote/dist/summernote-lite.js?<?=time()?>"></script>
	
<style>
	
	.note-editor.note-frame {
		border: 0;
	}
	.note-editor.note-frame .note-editing-area .note-editable[contenteditable="false"] {
		 background-color: #fcfcfc;
	}
	.note-toolbar {
		padding: 0;
		margin: 0;
	}
	.note-toolbar>.note-btn-group {
		margin-top: 0;
		margin-right: 0;
	}
	.note-editable ul, .note-editable ol {
		padding:0;
		margin:0 0 0 25px;
	}
	.note-editable ul li, .note-editable ol li {
		padding:0;
		margin:0 0 5px 0;
	}
	.note-editable b {
		font-weight:bold;
	}

</style>
	
	<h2><i class="fa fa-file-text-o fa-lg"></i>&nbsp;&nbsp;<? if(isset($_GET['inv'])){echo $lng['Invoice'];}else{echo $lng['Receipt / Tax Invoice'];}?></h2>
	<div class="main" style="top:115px; padding-right:0">
		<div style="padding:0 0 0 20px" id="dump"></div>

		<form id="documentForm" style="height:100%">
			<input style="visibility:hidden; height:0" type="file" name="logo" id="inv_logo" />
			<input style="visibility:hidden; height:0" type="file" name="stamp" id="inv_stamp" />
			<input style="visibility:hidden; height:0" type="file" name="signature" id="inv_signature" />
			<table style="width:100%; height:100%; table-layout:fixed" border="0">
				<tr>
					<td style="width:22%; vertical-align:top; padding:15px 0 0 0;">
				
						<ul style="position:relative" class="nav nav-tabs" id="myTab">
							<li class="active"><a data-target="#tab_header" data-toggle="tab"><?=$lng['Header']?></a></li>
							<li><a data-target="#tab_body" data-toggle="tab"><?=$lng['Body']?></a></li>
							<li><a data-target="#tab_details" data-toggle="tab"><?=$lng['Details']?></a></li>
							<li><a data-target="#tab_footer" data-toggle="tab"><?=$lng['Footer']?></a></li>
							<button class="btn btn-primary btn-sm" style="position:absolute; bottom:10px; right:1px;" id="submitbtn" type="submit"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=$lng['Update']?></button>
						</ul>
		
						<div class="tab-content" style="padding:10px 15px 15px 15px; border:1px #ccc solid; border-top:0; height:calc(100% - 40px)">
						
							<div class="tab-pane active" id="tab_header">
								<table class="basicTable wsnormal compact" border="0" style="width:100%">
									<tbody>
										<tr>
											<th><?=$lng['Company']?></th>
											<td style="padding:0">
												<input name="<?=$lang?>_company" placeholder="..." type="text" value="<?=$data[$lang.'_company']?>" />
											</td>
										</tr>
										<tr>
											<th><?=$lng['Address']?></th>
											<td style="padding:0">
												<textarea style="overflow:hidden" name="<?=$lang?>_address" placeholder="..." rows="3"><?=$data[$lang.'_address']?></textarea>
											</td>
										</tr>
										<tr>
											<th><button onclick="$('#inv_logo').click()" style="width:100%; margin-top:5px" class="btn btn-default btn-xs" type="button"><?=$lng['Logo']?></button></th>
											<td style="padding:5px">
												<img id="invLogo" style="height:70px" src="<?=$data['logo'].'?'.time()?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						
							<div class="tab-pane" id="tab_body">
								<table class="basicTable wsnormal compact" border="0" style="width:100%">
									<tbody>
										<? if(isset($_GET['inv'])){ ?>
										<tr>
											<th><?=$lng['Document type']?></th>
											<td style="padding:0">
												<input name="<?=$lang?>_inv_type" placeholder="..." type="text" value="<?=$data[$lang.'_inv_type']?>" />
											</td>
										</tr>
										<tr>
											<th><?=$lng['Prefix Invoice']?></th>
											<td style="padding:0; vertical-align:middle">
												<input name="inv_prefix" style="width:41px; padding-right:0px; padding-left:8px" maxlength="3" placeholder="..." type="text" value="<?=$data['inv_prefix']?>" />
												<select name="inv_date" style="width:auto; display:inline">
													<option <? if($data['inv_date'] == 'Ym'){echo 'selected';}?> value="Ym"><?=date('Ym')?> (YM)</option>
													<option <? if($data['inv_date'] == 'Ymd'){echo 'selected';}?> value="Ymd"><?=date('Ymd')?> (YMD)</option>
													<option <? if($data['inv_date'] == 'Y'){echo 'selected';}?> value="Y"><?=date('Y')?> (Y)</option>
												</select>
											</td>
										</tr>
										<? }else{ ?>
										<tr>
											<th><?=$lng['Document type']?></th>
											<td style="padding:0">
												<input name="<?=$lang?>_rec_type" placeholder="..." type="text" value="<?=$data[$lang.'_rec_type']?>" />
											</td>
										</tr>
										<tr>
											<th><?=$lng['Prefix Receipt']?></th>
											<td style="padding:0">
												<input name="rec_prefix" style="width:41px; padding-right:0px; padding-left:8px" maxlength="3" placeholder="..." type="text" value="<?=$data['rec_prefix']?>" />
												<select name="rec_date" style="width:auto; display:inline">
													<option <? if($data['rec_date'] == 'Ym'){echo 'selected';}?> value="Ym"><?=date('Ym')?> (YM)</option>
													<option <? if($data['rec_date'] == 'Ymd'){echo 'selected';}?> value="Ymd"><?=date('Ymd')?> (YMD)</option>
													<option <? if($data['rec_date'] == 'Y'){echo 'selected';}?> value="Y"><?=date('Y')?> (Y)</option>
												</select>
											</td>
										</tr>
										<? } ?>
										
										<tr>
											<th><?=$lng['Due date']?></th>
											<td style="padding:0">
												<select name="due" style="width:100%">
													<option <? if($data['due'] == '+0 day'){echo 'selected';}?> value="+0 day">Date + 0 days</option>
													<option <? if($data['due'] == '+7 day'){echo 'selected';}?> value="+7 day">Date + 7 days</option>
													<option <? if($data['due'] == '+14 day'){echo 'selected';}?> value="+14 day">Date + 2 weeks</option>
													<option <? if($data['due'] == '+1 month'){echo 'selected';}?> value="+1 month">Date + 1 month</option>
												</select>
											</td>
										</tr>
										<tr>
											<th><?=$lng['Description']?></th>
											<td style="padding:0">
												<input style="width:100%" name="<?=$lang?>_description" placeholder="..." type="text" value="<?=$data[$lang.'_description']?>" />
												<!--<table style="width:100%" style="border-collapse:collapse" border="0">
													<tr style="border-bottom:1px solid #eee;">
														<td style="width:1px; border-right:0; padding-left:8px">1.</td>
														<td style="padding:4px 0">
															<input style="margin:0; padding:0; width:100%" name="<?=$lang?>_description[1]" placeholder="..." type="text" value="<? //=$data[$lang.'_description']?>" />
														</td>
													<tr>
													<tr style="border-bottom:1px solid #eee;">
														<td style="width:1px; border-right:0; padding-left:8px">2.</td>
														<td style="padding:4px 0">
															<input style="margin:0; padding:0; width:100%" name="<?=$lang?>_description[2]" placeholder="..." type="text" value="<? //=$data[$lang.'_description']?>" />
														</td>
													<tr>
													<tr style="border-bottom:1px solid #eee;">
														<td style="width:1px; border-right:0; padding-left:8px">3.</td>
														<td style="padding:4px 0">
															<input style="margin:0; padding:0; width:100%" name="<?=$lang?>_description[3]" placeholder="..." type="text" value="<? //=$data[$lang.'_description']?>" />
														</td>
													<tr>
													<tr>
														<td style="width:1px; border-right:0; padding-left:8px">4.</td>
														<td style="padding:4px 0">
															<input style="margin:0; padding:0; width:100%" name="<?=$lang?>_description[4]" placeholder="..." type="text" value="<? //=$data[$lang.'_description']?>" />
														</td>
													<tr>
												</table>-->
											</td>
										</tr>
										<tr>
											<th><?=$lng['VAT']?> %</th>
											<td style="padding:0">
												<input name="vat" class="sel numeric" placeholder="..." type="text" value="<?=$data['vat']?>" />
											</td>
										</tr>
										<tr>
											<th><?=$lng['WHT']?> %</th>
											<td style="padding:0">
												<input name="wht" class="sel numeric" placeholder="..." type="text" value="<?=$data['wht']?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						
							<div class="tab-pane" id="tab_details">
								<table id="pageTable" class="basicTable wsnormal compact" border="0" style="width:100%">
									<tbody>
										<tr>
											<th>Pay To</th>
											<td style="padding:0"><textarea name="<?=$lang?>_pay_to" rows="6"><?=$data[$lang.'_pay_to']?></textarea>
											</td>
										</tr>
										<tr>
											<th>Bank details</th>
											<td style="padding:0"><textarea name="<?=$lang?>_bank_details" rows="6"><?=$data[$lang.'_bank_details']?></textarea>
											</td>
										</tr>
										<!--<tr>
											<th>Tax ID</th>
											<td style="padding:0"><input name="tax_id" placeholder="..." type="text" value="<?=$data['tax_id']?>" /></td>
										</tr>
										<tr>
											<th>Bank name</th>
											<td style="padding:0"><input name="<?=$lang?>_bank" placeholder="..." type="text" value="<?=$data[$lang.'_bank']?>" /></td>
										</tr>
										<tr>
											<th>Bank Branch</th>
											<td style="padding:0"><input name="branch" placeholder="..." type="text" value="<?=$data['branch']?>" /></td>
										</tr>
										<tr>
											<th>Bank account</th>
											<td style="padding:0"><input name="account" placeholder="..." type="text" value="<?=$data['account']?>" /></td>
										</tr>
										<tr>
											<th>eMail payslip</th>
											<td style="padding:0"><input name="email" placeholder="..." type="text" value="<?=$data['email']?>" /></td>
										</tr>-->
										<tr>
											<th><button onclick="$('#inv_stamp').click()" style="width:100%; margin-top:5px" class="btn btn-default btn-xs" type="button">Stamp<? //=$lng['Logo']?></button></th>
											<td style="padding:5px">
												<img id="invStamp" style="height:100px" src="<?=$data['stamp'].'?'.time()?>" />
											</td>
										</tr>
										<tr>
											<th><button onclick="$('#inv_signature').click()" style="width:100%; margin-top:5px" class="btn btn-default btn-xs" type="button">Signature<? //=$lng['Signature']?></button></th>
											<td style="padding:5px">
												<img class="invSignature" style="height:35px" src="<?=$data['signature'].'?'.time()?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
		
							<div class="tab-pane" id="tab_footer">
								<textarea name="<?=$lang?>_footer" class="summernote1"><?=$data[$lang.'_footer']?></textarea>
							</div>
						
						</div>
				

					</td>	
					<td style="width:39%; vertical-align:top; padding-right:0px; padding-left:10px; border-right:0px solid #eee">
			
						<div style="height:100%; padding:0; overflow-y:auto">
						<div style="height:1123px; width:800px; background-size:contain; position:relative; padding:35px; box-shadow:0 0 10px rgba(0,0,0,0.2); margin:15px">
			
				
				<table class="invoice" width="100%" border="0">
				  <tr style="border-bottom:1px solid #ccc">
					 <td><img id="headerLogo" style="height:80px; margin-bottom:15px" src="<?=$data['logo'].'?'.time()?>" /></td>
					 <td style="text-align:right">
					 	<b class="company" style="font-size:16px"><?=$data[$lang.'_company']?></b><br>
						<textarea style="width:100%; text-align:right; padding:0; resize:none; overflow:hidden; border:0" class="address" rows="4"><?=$data[$lang.'_address']?></textarea>
					 </td>
				  </tr>
				  <tr style="height:30px">
					 <td colspan="2">&nbsp;</td>
				  </tr>
				  <tr style="">
					 <td id="docType" style="vertical-align:top; font-size:24px; font-weight:600">
						<? if(isset($_GET['inv'])){echo $data[$lang.'_inv_type'];}else{echo $data[$lang.'_rec_type'];}?>
					</td>
					 <td>
					 	<div style="color:#aaa; padding:10px 15px; border:1px solid #ddd; border-radius:5px; width:90%; height:110px">
					 		<i><b><?=$lng['Customer name']?></b><br>
							<?=$lng['Address']?></i>
					 	</div>
					 </td>
				  </tr>
				  <tr style="height:40px">
					 <th colspan="2" style="font-size:20px">
					 	<? if(isset($_GET['inv'])){echo $lng['Invoice'];}else{echo $lng['Receipt'];}?> # : <span class="docNr">...</span>
					</th>
				  </tr>
				  <tr>
					 <td colspan="2" style="padding:0">
					 	<table border="0">
						  <tr>
							 <th style="padding-right:20px"><?=$lng['Invoice date']?> :</th>
							 <th style="padding-right:20px"><?=$lng['Due date']?> :</th>
							 <th style="padding-right:20px"><?=$lng['Client ID']?> :</th>
							 <? if(isset($_GET['rec'])){ ?>
							 <th style="padding-right:20px"><?=$lng['Reference']?> :</th>
							 <? } ?>
						  </tr>
						  <tr>
							 <td style="color:#aaa"><i><?=date('d-m-Y')?></i></td>
							 <td style="color:#aaa"><i id="dueDate"><?=date('d-m-Y', strtotime($data['due'], strtotime(date('d-m-Y'))))?></i></td>
							 <td style="color:#aaa"><i><?=$lng['Client ID']?></i></td>
							 <? if(isset($_GET['rec'])){ ?>
							 <td style="color:#aaa"><i><?=$lng['Invoice']?> # & <?=$lng['Date']?></i></td>
							 <? } ?>
						  </tr>
						</table>
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
						 <td class="tal">
						 	<?=$data[$lang.'_description']?>
						 </td>
						 <td class="tar">1</td>
						 <td class="tar"></td>
						 <td class="tar"><?=$data['vat']?></td>
						 <td class="tar"></td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  <tr>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
						 <td>&nbsp;</td>
					  </tr>
					  </tbody>
					  <tfoot>
					  <tr>
						 <td colspan="3" rowspan="4" style="xborder-bottom-width:1px !important; padding:0 0 5px 0; vertical-align:bottom">
						 	<table width="100%" border="0">
							  <tr>
								 <td colspan="2" style="border:0; font-size:14px"><?=$lng['Deduct WHT']?>&nbsp; 0% &nbsp;<?=$lng['on']?> &nbsp;0.00 &nbsp;<?=$lng['is']?> &nbsp;0.00 &nbsp;<?=$lng['Baht']?></td>
							  </tr>
							  <tr>
								 <th style="width:1px; white-space:nowrap; font-size:16px; border:0"><?=$lng['Net to Pay']?> :</th>
								 <th style="font-size:16px; border:0">0.00</th>
							  </tr>
							  <tr>
								 <td colspan="2" style="border:0"><?=$lng['*** Zero Baht only ***']?></td>
							  </tr>
							</table>
						 </td>
						 <th colspan="2"><?=$lng['Discount']?></th>
						 <th class="tar">0.00</th>
					  </tr>
					  <tr>
						 <th colspan="2"><?=$lng['Sub total']?></th>
						 <th class="tar">0.00</th>
					  </tr>
					  <tr>
						 <th colspan="2"><?=$lng['VAT']?></th>
						 <th class="tar">0.00</th>
					  </tr>
					  <tr>
						 <th colspan="2"><?=$lng['Grand total']?></th>
						 <th class="tar">0.00</th>
					  </tr>
					  <tr>
						 <td colspan="6" class="tar" style="border-bottom-width:1px; padding:4px 8px">
						 	<?=$lng['*** Zero Baht only ***']?>
						</td>
					  </tr>
					  </tfoot>
					</table>
					 
					 </td>
				  </tr>
				</table>

				<div style="height:20px"></div>
				
				<? if(isset($_GET['inv'])){ ?>
				
				<table class="receiptTable" border="0" style="width:100%; white-space:nowrap; border:1px solid #ddd">
					<tr style="background:#eee; border-bottom:1px solid #ddd">
						<td colspan="4"><b>Payment details</b></td>
					</tr>
					<tr style="border-bottom:1px solid #ddd">
						<td style="width:1px; vertical-align:baseline; text-align:right"><b>To : </b></td>
						<td style="vertical-align:baseline; width:50%; padding-left:0; border-right:1px solid #ddd">
							<?=nl2br($data[$lang.'_pay_to'])?>
						</td>
						<td style="width:1px; vertical-align:baseline; text-align:right">
							<b>Customer : </b><br>
							<b>Invoice number : </b><br>
							<b>Amount due : </b><br>
							<b>Due date : </b>
						</td>
						<td style="vertical-align:baseline; width:50%; padding-left:0">
							<b><i style="color:#aaa"><?=$lng['Customer name']?></i></b><br>
							<i style="color:#aaa"><span class="docNr">...</span></i><br>
							<i style="color:#aaa">0.00</i><br>
							<i style="color:#aaa"><?=date('d-m-Y', strtotime($data['due'], strtotime(date('d-m-Y'))))?></i><br>
						
						</td>
					</tr>
					<tr>
						<td style="width:1px; vertical-align:top; text-align:right"><b>Bank : </b></td>
						<td style="vertical-align:baseline; padding-left:0; border-right:1px solid #ddd">
							<?=nl2br($data[$lang.'_bank_details'])?><br>
						</td>
						<td colspan="2" style="vertical-align:baseline; position:relative">
							<b>Athorized signature :</b>
							<div style=""><img class="invSignature" style="width:160px; max-height:80px" src="<?=$data['signature'].'?'.time()?>" /></div>
							<div style=" position:absolute; bottom:5px; right:10px"><img class="sStamp" style="height:120px; max-width:200px" src="<?=$data['stamp'].'?'.time()?>" /></div>
						
						</td>
					</tr>
				</table>
				
				<? }else{ ?>
				
				<div style="padding:0 5px 5px; margin-top:10px; border:1px solid #ccc">
				<table class="receiptTable" border="0" style="white-space:nowrap">
					<tbody>
						<tr style="border-bottom:1px solid #ddd">
							<th>Paid by :</th>
							<td style="padding:0">
								<select name="due" style="width:auto; border:0; padding:0; color:#aaa; font-style:italic">
									<option disabled selected value="">Select</option>
									<option value="">Creditcard</option>
									<option value="">Bank transfer</option>
									<option value="">Cheque</option>
								</select>
							</td>
							<th>on date : </th>
							<td style="padding:0; color:#aaa">
								<i><?=date('d-m-Y')?></i>
							</td>
							<td style="width:80%"></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" style="width:100%; table-layout:xfixed; xheight:100px; margin-top:10px">
					<tr>
						<td style="padding:0 20px 10px; vertical-align:top; position:relative; height:100px; width:200px">
						<div style="position:absolute; top:0; left:5px"><img class="sStamp" height="120px" src="<?=$data['stamp'].'?'.time()?>" /></div>
						</td>
						<td style="vertical-align:top; white-space:nowrap">
							<b>Date : </b><i style="color:#aaa"><?=date('d-m-Y')?></i><br>
							<b>Name : </b><i style="color:#aaa">Current user</i><br>
						</td>
						<td style="width:50%; vertical-align:top">
							<b>Authorized signature : </b><br>
							<img class="sSignature" height="35px" src="<?=$data['signature'].'?'.time()?>" />
						</td>
					</tr>
				</table>
				</div>
				<? } ?>
				
				<div class="footer" style="position:absolute; bottom:30px; left:30px; right:30px; padding-top:5px">
					<div style="text-align:center; padding-top:5px; border-top:1px solid #ccc">
						<?=$data[$lang.'_footer']?> 
					</div>
				</div>
			
			</div>
			</div>
			
			
			
			</td>
			</tr>
			</table>	
			</form>				

		</div>
   </div>
	
	<script>

		$(document).ready(function() {
			
			$("#inv_logo").on("change", function(e){
				e.preventDefault();
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please use only .jpg - .jpeg - .png - .gif files',
						duration: 4,
						closeConfirm: true
					})
					return false;
				}
				var file = $(this)[0].files[0];
				if(file){
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						var img = new Image;
						$('#invLogo').prop('src', e.target.result);
						$('#headerLogo').prop('src', e.target.result);
					}
				}
				return false;
			});
			
			$("#inv_stamp").on("change", function(e){
				e.preventDefault();
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please use only .jpg - .jpeg - .png - .gif files',
						duration: 4,
						closeConfirm: true
					})
					return false;
				}
				var file = $(this)[0].files[0];
				if(file){
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						var img = new Image;
						$('#invStamp').prop('src', e.target.result);
						$('.sStamp').prop('src', e.target.result);
					}
				}
				return false;
			});
			
			$("#inv_signature").on("change", function(e){
				e.preventDefault();
				var ff = $(this).val().toLowerCase();
				ff = ff.replace(/.*[\/\\]/, '');
				var ext =  ff.split('.').pop();
				f = ff.substr(0, ff.lastIndexOf('.'));
				var r = f.split('_');
				if(!(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')){
					$("body").overhang({
						type: "warn",
						message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Please use only .jpg - .jpeg - .png - .gif files',
						duration: 4,
						closeConfirm: true
					})
					return false;
				}
				var file = $(this)[0].files[0];
				if(file){
					var reader = new FileReader();
					reader.readAsDataURL(file);
					reader.onload = function(e) {
						var img = new Image;
						$('.invSignature').prop('src', e.target.result);
						$('#sSignature').prop('src', e.target.result);
					}
				}
				return false;
			});
			
			
			
			$('input[name="'+lang+'_company"]').on('keyup', function(){
				$('.company').html(this.value)
			})
			
			$('textarea[name="'+lang+'_address"]').on('keyup', function(){
				$('.address').html(this.value)
			})
			
			$('input[name="inv_prefix"]').on('keyup', function(){
				var invDate = '';
				if($('select[name="inv_date"]').val() == 'Ymd'){
					invDate = '<?=date('Ymd')?>';
				}
				if($('select[name="inv_date"]').val() == 'Ym'){
					invDate = '<?=date('Ym')?>';
				}
				if($('select[name="inv_date"]').val() == 'Y'){
					invDate = '<?=date('Y')?>';
				}
				$('.docNr').html($('input[name="inv_prefix"]').val()+invDate+'-0001')
			})
			$('select[name="inv_date"]').on('change', function(){
				var invDate = '';
				if(this.value == 'Ymd'){
					invDate = '<?=date('Ymd')?>';
				}
				if(this.value == 'Ym'){
					invDate = '<?=date('Ym')?>';
				}
				if(this.value == 'Y'){
					invDate = '<?=date('Y')?>';
				}
				$('.docNr').html($('input[name="inv_prefix"]').val()+invDate+'-0001')
			})
			$('select[name="inv_date"]').trigger('change');
			
			$('input[name="rec_prefix"]').on('keyup', function(){
				var recDate = '';
				if($('select[name="rec_date"]').val() == 'Ymd'){
					recDate = '<?=date('Ymd')?>';
				}
				if($('select[name="rec_date"]').val() == 'Ym'){
					recDate = '<?=date('Ym')?>';
				}
				if($('select[name="rec_date"]').val() == 'Y'){
					recDate = '<?=date('Y')?>';
				}
				$('.docNr').html($('input[name="rec_prefix"]').val()+recDate+'-0001')
			})
			$('select[name="rec_date"]').on('change', function(){
				var recDate = '';
				if(this.value == 'Ymd'){
					recDate = '<?=date('Ymd')?>';
				}
				if(this.value == 'Ym'){
					recDate = '<?=date('Ym')?>';
				}
				if(this.value == 'Y'){
					recDate = '<?=date('Y')?>';
				}
				$('.docNr').html($('input[name="rec_prefix"]').val()+recDate+'-0001')
			})
			$('select[name="rec_date"]').trigger('change');
			
			$('input[name="'+lang+'_inv_type"]').on('keyup', function(){
				$('#docType').html(this.value)
			})
			
			$('input[name="'+lang+'_rec_type"]').on('keyup', function(){
				$('#docType').html(this.value)
			})
			
			$('select[name="due"]').on('change', function(){
				var str = this.value;
				if(str == '+0 day'){
					$('#dueDate').html('<?=date('d-m-Y')?>');
				}
				if(str == '+7 day'){
					$('#dueDate').html('<?=date('d-m-Y', strtotime('+7 day', strtotime(date('d-m-Y'))))?>');
				}
				if(str == '+14 day'){
					$('#dueDate').html('<?=date('d-m-Y', strtotime('+14 day', strtotime(date('d-m-Y'))))?>');
				}
				if(str == '+1 month'){
					$('#dueDate').html('<?=date('d-m-Y', strtotime('+1 month', strtotime(date('d-m-Y'))))?>');
				}
			})

			var summerOptions = {
				placeholder: '...',
				tooltip: false,
				height: 150,
				disableResizeEditor: false,
				disableDragAndDrop: true,
				//fontNames: ['Arial', 'Arial Black'],
				fontSizes: ['13','15','18','20','22','26'],
				toolbar: [
					['style', ['bold', 'italic', 'clear']],
					//['fontsize', ['fontsize']],
					//['fontname', ['fontname']],
					//['color', ['color']],																																																						
					//['para', ['ul', 'ol', 'paragraph']],
              	//['insert', ['link', 'picture']],
              	//['view', ['fullscreen', 'codeview']],
              	['view', ['fullscreen']],
					['misc', ['undo', 'redo']],
				],
				//styleTags: ['p', 'h2', 'h3'],
				callbacks: {
				  onPaste: function (e) {
					var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
					e.preventDefault();
					// Firefox fix
					setTimeout(function () {
						 document.execCommand('insertText', false, bufferText);
					}, 10);
				  }
				 }
		  	}
			$('.summernote1').summernote(summerOptions);

			$("#documentForm").submit(function(e){ 
				e.preventDefault();
				var data = new FormData($(this)[0]);
				$.ajax({
					url: AROOT+"billing/ajax/save_document_setup.php",
					type: 'POST',
					data: data,
					async: false,
					cache: false,
					contentType: false,
					processData: false,
					success: function(result){
						//$('#dump').html(result); return false;
						if(result == 'success'){
							$("body").overhang({
								type: "success",
								message: '<i class="fa fa-check"></i>&nbsp;&nbsp;Data updated successfuly<? //=$lng['Data updated successfuly']?>',
								duration: 2,
							})
							//setTimeout(function(){location.reload();},2000);	
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
						














