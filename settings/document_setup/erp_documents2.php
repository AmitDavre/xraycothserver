<?
	//var_dump($compinfo);
	$data = array();
	$sql = "SELECT * FROM erp_document_templates WHERE id = '1'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
		}
	}
	//var_dump($data);
	
	$setup = array();
	$sql = "SELECT * FROM erp_document_setup WHERE id = '1'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$setup = $row;
			$settings = unserialize($row['settings']);
		}
	}
	var_dump($setup);
	var_dump($settings);
	
	
	
?>
<style>
	table.docTemplate {
		width:100%;
		border-collapse:collapse;
	}
	table.docTemplate thead th {
		padding:5px 10px;
		background:#f6f6f6;
		font-weight:600;
		border-bottom:1px solid #ddd;
	}
	table.docTemplate tbody th {
		padding:5px 10px;
		font-weight:600;
		white-space:nowrap;
		color: #003399;
		width:1px;
	}
	table.docTemplate tbody td {
		padding:0;
	}
	table.docTemplate tbody td input[type="text"] {
		display:block;
		text-align:left;
		padding:4px 10px;
		margin:0;
		width:100%;
		border:0;
		border-bottom:1px solid #eee;
	}
	table.docTemplate tbody td input[type="text"].nopad {
		padding:2px;
	}
	table.docTemplate tbody td textarea {
		display:block;
		text-align:right;
		padding:4px 10px;
		margin:0;
		width:100%;
		border:0;
		border-bottom:1px solid #eee;
		overflow:hidden;
	}
	table.docTemplate tbody td img {
		height:70px;
		width:auto;
	}


	table.itemsTable {
		width:100%; 
		table-layout:fixed;
		border-collapse:collapse;
	}
	table.itemsTable thead th {
		padding:4px 0px 4px 10px;
		background:#eee;
		text-align:right;
		width:12%;
		white-space:nowrap;
		font-weight:600;
	}
	table.itemsTable tbody td {
		padding:4px 0px 4px 10px;
		font-weight:400;
		text-align:right;
		white-space:nowrap;
		border-bottom:1px solid #eee;
		color:#999;
		font-style:italic;
	}
	table.itemsTable tbody th {
		padding:4px 10px;
		font-weight:600;
		text-align:left;
		white-space:nowrap;
	}
	table.itemsTable thead th.pl0, 
	table.itemsTable tbody td.pl0 {
		padding-right:5px;
		text-align:center;
	}
	
	
	table.docTable {
		width:100%; 
		table-layout:fixed;
		border-collapse:collapse;
	}
	table.docTable thead th {
		padding:4px 10px;
		background:#eee;
		text-align:left;
		width:12%;
		white-space:nowrap;
		font-weight:600;
	}
	table.docTable tbody td {
		padding:4px 10px;
		font-weight:400;
		text-align:left;
		white-space:nowrap;
		border-bottom:1px solid #eee;
	}
	table.docTable tbody th {
		padding:4px 10px;
		font-weight:600;
		text-align:left;
		white-space:nowrap;
	}
	
	
</style>

	<div style="height:100%; border:0px solid red; position:relative">
		
		<div class="breadcrumbs">
			<a onClick="location.reload()"><i class="fa fa-file-invoice" ></i> &nbsp;<?=$lng['Document templates']?></a>
			<span id="eSpinner" style="padding-left:10px; display:none"><i class="fa fa-refresh fa-spin"></i></span> 
			<span id="eMsg" style="display:none; padding:0 10px; color:#a00; font-weight:400"></span>
		</div>
		
		
		<div style="position:absolute; left:0; top:33px; right:65%; bottom:0; background:#fff;">
			
			<div class="smallNav">
				<ul>
					<!--<li id="btnUpdate" class="flr"><a><i class="fa fa-save"></i> &nbsp;<? //=$lng['Update']?></a>-->
				</ul>
			</div>
			
			<div id="leftTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:0; display:xnone; overflow-X:hidden">
				
			<table id="docTable" class="dataTables dubhead xhover">
				<thead>
					<tr>
						<th>Documents</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Quotation<span class="id" style="display:none">1</span></td>
					</tr>
					<tr>
						<td>Order confirmation<span class="id" style="display:none">2</span></td>
					</tr>
					<tr>
						<td>Contract<span class="id" style="display:none">3</span></td>
					</tr>
					<tr>
						<td>Invoice<span class="id" style="display:none">4</span></td>
					</tr>
					<tr>
						<td>Purchase order<span class="id" style="display:none">5</span></td>
					</tr>
				</tbody>
			</table>


			</div>
		</div>
					
		<div style="position:absolute; left:35%; top:33px; right:0; bottom:0; background: #f6f6f6; border-left:1px solid #ddd">
			
			<div class="smallNav">
				<ul>
					<li id="btnView" style="display:xnone"><a><i class="fa fa-eye"></i> &nbsp;Toggle view<? //=$lng['Edit']?></a>
					<li id="btnUpdate" style="display:xnone"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
					<li class="xxhide" style="display:none"><a id="btnCancel"><i class="fa fa-times"></i> &nbsp;<?=$lng['Cancel']?></a>
				</ul>
				  
			</div>
			
			<div id="rightTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:25px; display:none">
			
			<form id="docForm">
			
				<input type="hidden" name="id" value="<?=$setup['id']?>">
				<input class="view" style="width:100%; margin-bottom:10px !important; display:none" type="text" name="name" value="<?=$setup['name']?>">

			<!--HEADER------------------------------------------------------------------------->
				<label class="view" style="margin-bottom:10px; display:block; display:none">
					<input type="hidden" name="settings[header]" value="0">
					<input <? if($settings['header'] == 1){echo 'checked';}?> id="cHeader" type="checkbox" name="settings[header]" value="1" class="checkbox"><span> Show header</span>
				</label>
				<table id="headerTable" border="0" style="width:100%; margin-bottom:10px; <? if($settings['header'] == 0){echo 'display:none';}?> ">
					<tr style="border-bottom:1px solid #ddd">
						<td style="vertical-align:top; max-height:100px; padding-bottom:15px">
							<img id="temp_logo" style="display:block; max-height:70px; max-width:400px" src="<?=$data['logo'].'?'.time()?>" />
						</td>
						<td style="vertical-align:top; text-align:right; padding-bottom:8px; height:85px">
							<span id="company" style="font-size:16px; font-weight:600"><?=$data['company']?></span><br>
							<span style="white-space:pre; font-size:13px; line-height:100%" id="address"><?=$data['address']?></span>
						</td>
					<tr>
				</table>
			
			<!--ADDRESS------------------------------------------------------------------------->
				<label class="view" style="margin-bottom:10px display:block; display:none">
					<input type="hidden" name="settings[address]" value="0">
					<input <? if($settings['address'] == 1){echo 'checked';}?> id="cAddress" type="checkbox" name="settings[address]" value="1" class="checkbox"><span> Show customer address</span>
				</label>
				
				<div id="addressTable" style="border:1px solid #eee; border-radius:5px; padding:10px 15px; float:right; display:inline-block; width:400px; min-height:110px; margin-right:50px; <? if($settings['address'] == 0){echo 'display:none';}?>; color:#aaa; font-style: italic; margin-top:30px">
					<span id="customer" style="font-size:15px; font-weight:600">X-RAY ICT & CONSULTING Co., Ltd.</span><br>
					<span style="">222/75, Moo 7<br>Nongprue - Banglamung<br>20150 Chonburi<br>Thailand</span>
				</div>
				<div class="clear"></div>
				
			<!--REFERENCES------------------------------------------------------------------------->
				<select id="cRef" class="view" name="settings[reference]" style="width:100%; margin:10px 0 !important; display:none">
					<option <? if($settings['reference'] == 0){echo 'selected';}?> value="0">Reference section : Hide</option>
					<option <? if($settings['reference'] == 'invTable'){echo 'selected';}?> value="invTable">Reference section : Invoice</option>
					<option <? if($settings['reference'] == 'quoTable'){echo 'selected';}?> value="quoTable">Reference section : Quotation</option>
					<option <? if($settings['reference'] == 'refTable'){echo 'selected';}?> value="refTable">Reference section : Date & Reference</option>
				</select>
				<div class="refTable" id="invTable" style=" <? if($settings['reference'] != 'invTable'){echo 'display:none';}?>">
				
				<div style="clear:both; margin-bottom:10px">
					<span style="font-size:20px; font-weight:600; border-bottom:1px solid #ddd;"><?=$lng['Invoice']?> # <span style="color:#999; font-style:italic">INV1800001</span></span><br>
				</div>
				<table border="0" style="margin:0 0 0 5px">
					<tr>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Invoice date']?> :</b>
							<p style="color:#999; font-style:italic"><?=date('d-m-Y')?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Due date']?> :</b>
							<p style="color:#999; font-style:italic"><?=date('d-m-Y', strtotime("+30 days"))?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Customer reference</p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b>Our reference<? //=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Our reference</p>
						</td>
					</tr>
				</table>
				</div>
				
				<div class="refTable" id="quoTable" style=" <? if($settings['reference'] != 'quoTable'){echo 'display:none';}?>">
				<div style="clear:both; margin-bottom:10px">
					<span style="font-size:20px; font-weight:600; border-bottom:1px solid #ddd;">Quotation<? //=$lng['Quotation']?> # <span style="color:#999; font-style:italic">QU1800001</span></span><br>
				</div>
				<table border="0" style="margin:0 0 0 5px">
					<tr>
						<td style="vertical-align:top; padding-right:50px">
							<b>Quotation date<? //=$lng['Quotation date']?> :</b>
							<p style="color:#999; font-style:italic"><?=date('d-m-Y')?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b>Expire date<? //=$lng['Expire date']?> :</b>
							<p style="color:#999; font-style:italic"><?=date('d-m-Y', strtotime("+30 days"))?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Customer reference</p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b>Our reference<? //=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Our reference</p>
						</td>
					</tr>
				</table>
				</div>
				
				<div class="refTable" id="refTable" style=" <? if($settings['reference'] != 'refTable'){echo 'display:none';}?>">
				<table border="0" style="margin:0 0 0 5px">
					<tr>
						<td style="vertical-align:top; padding-right:50px">
							<b>Date<? //=$lng['Quotation date']?> :</b>
							<p style="color:#999; font-style:italic"><?=date('d-m-Y')?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Customer reference</p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b>Our reference<? //=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Our reference</p>
						</td>
					</tr>
				</table>
				</div>
				
			<!--ITEMS------------------------------------------------------------------------->
				<select id="cItems" class="view" name="settings[items]" style="width:100%; margin:10px 0 !important; display:none">
					<option <? if($settings['items'] == 0){echo 'selected';}?> value="0">Items section : Hide</option>
					<option <? if($settings['items'] == 'invSec'){echo 'selected';}?> value="invSec">Items section : Invoice</option>
					<option <? if($settings['items'] == 'quoSec'){echo 'selected';}?> value="quoSec">Items section : Quotation</option>
					<option <? if($settings['items'] == 'refSec'){echo 'selected';}?> value="refSec">Items section : Order</option>
					<option <? if($settings['items'] == 'refTxt'){echo 'selected';}?> value="refTxt">Items section : Text</option>
				</select>
				
				<div class="itemTable" id="invSec" style=" <? if($settings['items'] != 'invSec'){echo 'display:none';}?>">
				<table class="itemsTable" border="0" style="margin-bottom:10px">
					<thead>
						<tr>
							<th style="text-align:left; width:40%"><?=$lng['Description']?></th>
							<th style="width:10%"><?=$lng['Quantity']?></th>
							<th style="width:10%"><?=$lng['Unit price']?></th>
							<th style="width:10%"><?=$lng['VAT']?> %</th>
							<th style="width:10%"><?=$lng['WHT']?> %</th>
							<th style="width:10%"><?=$lng['Amount']?></th>
							<th style="width:5%" class="pl0"><small><?=$currency?></small></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align:left">Product description</td>
							<td><?=number_format(2,2)?></td>
							<td><?=number_format(2500,2)?></td>
							<td><?=number_format(7,2)?></td>
							<td><?=number_format(0,2)?></td>
							<td><?=number_format(5000,2)?></td>
							<td class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="text-align:left">Service description</td>
							<td><?=number_format(1,2)?></td>
							<td><?=number_format(1000,2)?></td>
							<td><?=number_format(7,2)?></td>
							<td><?=number_format(3,2)?></td>
							<td><?=number_format(1000,2)?></td>
							<td class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr><td style="border:0; padding:5px"></td></tr>
						<tr>
							<td style="border-bottom:0" colspan="3"></td>
							<th style="border-bottom:1px solid #eee" colspan="2"><?=$lng['Subtotal']?></th>
							<td style="border-bottom:1px solid #eee"><?=number_format(6000,2)?></td>
							<td style="border-bottom:1px solid #eee" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0" colspan="3"></td>
							<th style="border-bottom:1px solid #999" colspan="2"><?=$lng['VAT']?></th>
							<td style="border-bottom:1px solid #999"><?=number_format(420,2)?></td>
							<td style="border-bottom:1px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0" colspan="3"></td>
							<th style="border-bottom:2px solid #999" colspan="2"><?=$lng['Grand total']?></th>
							<td style="border-bottom:2px solid #999"><?=number_format(6420,2)?></td>
							<td style="border-bottom:2px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0" colspan="3"></td>
							<th style="border-bottom:1px solid #999" colspan="2"><?=$lng['Withholding Tax']?></th>
							<td style="border-bottom:1px solid #999"><?=number_format(30,2)?></td>
							<td style="border-bottom:1px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0" colspan="3"></td>
							<th style="border-bottom:2px solid #999" colspan="2"><?=$lng['Net to pay']?></th>
							<td style="border-bottom:2px solid #999"><?=number_format(6390,2)?></td>
							<td style="border-bottom:2px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
					</tbody>
				</table>
				</div>
				
			<!--AMOUNT IN LETTERS------------------------------------------------------------------------->
				<label class="view" style="margin-bottom:10px; display:block; display:none">
					<input type="hidden" name="settings[words]" value="0">
					<input <? if($settings['words'] == 1){echo 'checked';}?> id="cWords" type="checkbox" name="settings[words]" value="1" class="checkbox"><span> Show amount in letters</span>
				</label>
				<div id="words" style="text-align:right; padding:0; <? if($settings['words'] == 0){echo 'display:none';}?>">--- Six thousand three hundred and ninety Bath Only ---</div>
				
			<!--NOTE------------------------------------------------------------------------->
				<label class="view" style="margin-bottom:10px; display:block; display:none">
					<input type="hidden" name="settings[note]" value="0">
					<input <? if($settings['note'] == 1){echo 'checked';}?> id="cNote" type="checkbox" name="settings[note]" value="1" class="checkbox"><span> Show bottom note</span>
				</label>
				<div id="Note" style="padding:0; <? if($settings['note'] == 0){echo 'display:none';}?>"><b>Note : </b><i style="color:#999; font-style:italic">Comments</i></div>
				
			<!--FOOTER------------------------------------------------------------------------->
				<label class="view" style="margin-bottom:10px display:block; display:none">
					<input type="hidden" name="settings[footer]" value="0">
					<input <? if($settings['footer'] == 1){echo 'checked';}?> id="cFooter" type="checkbox" name="settings[footer]" value="1" class="checkbox"><span> Show footer</span>
				</label>
				<div id="footerTable" style="border-top:1px solid #ddd; text-align:center; line-height:160%; padding-top:5px; font-size:12px; <? if($settings['footer'] == 0){echo 'display:none';}?>; margin-top:15px">
					<span id="footer1"></span><br>
					<span id="footer2"></span><br>
				</div>
			</form>	
			</div>
			
		</div>
		<div style="clear:both"></div>

	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->

	<script type="text/javascript">


	$(document).ready(function() {

		$(document).on('click', '#docTable tbody tr', function(){
			var id = $(this).find('.id').html()
			//alert(id)
			getDocSetup(id)
		})
		function getDocSetup(id) {
			$.ajax({
				url:"document_setup/ajax/get_doc_template.php",
				data: {id: id},
				dataType: "json",
				success: function(data){
					//$('#dump').html(data);
					//return false
					$('input[name="id"]').val(data.id)
					$('input[name="name"]').val(data.name)
					alert(data.settings.header)
					
					//$('.summernote').summernote(summerOptions);
					$("#rightTable").fadeIn(200)
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError+' 1');
				}
			});
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		$(document).on('click', "#btnView", function(e){
			$('.view').toggle()
		})
		
		$(document).on('click', "#cHeader", function(e){
			if($(this).is(':checked')){
				$('#headerTable').fadeIn(200)
			}else{
				$('#headerTable').fadeOut(200)
			}
		})
		
		$(document).on('click', "#cAddress", function(e){
			if($(this).is(':checked')){
				$('#addressTable').fadeIn(200)
			}else{
				$('#addressTable').fadeOut(200)
			}
		})
		
		$(document).on('change', "#cRef", function(e){
			$('.refTable').hide()
			$('#'+$(this).val()).fadeIn(200)
		})
		
		$(document).on('change', "#cItems", function(e){
			$('.itemTable').hide()
			$('#'+$(this).val()).fadeIn(200)
		})
		
		$(document).on('click', "#cWords", function(e){
			if($(this).is(':checked')){
				$('#words').fadeIn(200)
			}else{
				$('#words').fadeOut(200)
			}
		})
		
		$(document).on('click', "#cNote", function(e){
			if($(this).is(':checked')){
				$('#Note').fadeIn(200)
			}else{
				$('#Note').fadeOut(200)
			}
		})
		
		$(document).on('click', "#cFooter", function(e){
			if($(this).is(':checked')){
				$('#footerTable').fadeIn(200)
			}else{
				$('#footerTable').fadeOut(200)
			}
		})
		
		$(document).on('click', "#btnUpdate", function(e){
			$('#docForm').submit()
		})
		
		$(document).on('submit', "#docForm", function(e){
			e.preventDefault();
			//alert('submit')
			$('#eSpinner').show()
			var err = false;
			var data = $(this).serialize();
			$.ajax({
				url:"document_setup/ajax/save_document_template.php",
				data: data,
				success: function(result){
					$('#dump').html(result);
					$('#eSpinner').hide()
					$('#eMsg').html('<i class="fa fa-check-square-o"></i> &nbsp;Data saved successfuly.').fadeIn(200);
					setTimeout(function(){
						$('#eMsg').fadeOut(200)
					},3000);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				}
			});
			
			
		})
		
		$('input[type="text"], textarea').on("change", function(e){
			//alert("change")
			$('#company').html($('input[name="company"]').val())
			$('#address').html($('textarea[name="address"]').val())
			
			var phone = $('input[name="phone"]').val()
			var fax = $('input[name="fax"]').val()
			var email = $('input[name="email"]').val()
			var website = $('input[name="website"]').val()
			var vat = $('input[name="vat"]').val()
			var footer1 = ''
			if(phone != ''){footer1 += '<b><?=$lng['Phone']?> : </b>' + phone + ' '}
			if(fax != ''){if(footer1 != ''){ footer1 += '<b> &nbsp;&bull;&nbsp; </b>'}; footer1 += '<b><?=$lng['Fax']?> : </b>' + fax + ' '}
			if(email != ''){if(footer1 != ''){ footer1 += '<b> &nbsp;&bull;&nbsp; </b>'}; footer1 += '<b><?=$lng['email']?> : </b>' + email + ' '}
			if(website != ''){if(footer1 != ''){ footer1 += '<b> &nbsp;&bull;&nbsp; </b>'}; footer1 += '<b><?=$lng['Website']?> : </b>' + website + ' '}
			if(vat != ''){if(footer1 != ''){ footer1 += '<b> &nbsp;&bull;&nbsp; </b>'}; footer1 += '<b><?=$lng['VAT']?> : </b>' + vat + ' '}
			$('#footer1').html(footer1)
			
			var bank1 = $('input[name="bank1"]').val()
			var acc1 = $('input[name="acc1"]').val()
			var bic1 = $('input[name="bic1"]').val()
			var footer2 = ''
			if(bank1 != ''){footer2 += '<b>' + bank1 + ' : </b>' + acc1}
			if(bic1 != ''){footer2 += '<b> - <?=$lng['Swift/Bic']?> : </b>' + bic1 + ' '}
			
			var bank2 = $('input[name="bank2"]').val()
			var acc2 = $('input[name="acc2"]').val()
			var bic2 = $('input[name="bic2"]').val()
			var footer3 = ''
			if(bank2 != ''){if(footer2 != ''){ footer2 += '<b> &nbsp;&bull;&nbsp; </b>'}; footer3 += '<b>' + bank2 + ' : </b>' + acc2}
			if(bic2 != ''){footer3 += '<b> - <?=$lng['Swift/Bic']?> : </b>' + bic2 + ' '}
			$('#footer2').html(footer2 + footer3)
			
			var note = $('textarea[name="note"]').val()
			if(note != ''){$('#note').html('<b><?=$lng['Note']?> : </b>' + note)}else{$('#note').html('')}
			
			
		})
		
		$('input[type="text"]').trigger("change")
		
		
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
				$('#message').html('<div class="msg_alert">Please use only .jpg - .jpeg - .png - .gif files</div>').hide().fadeIn(400);
				return false;
			}
			$('#logoname').html(ff);
			var file = $(this)[0].files[0];
			if(file){
				var reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = function(e) {
					var img = new Image;
					$('#logoimg').prop('src', e.target.result);
					$('#temp_logo').prop('src', e.target.result);
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
					}
				}
				reader.readAsDataURL(input.files[0]);
		  }
		 }
	
	})
	
</script>





















	
