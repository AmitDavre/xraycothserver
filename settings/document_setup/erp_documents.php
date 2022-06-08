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
	
	/*$setup = array();
	$sql = "SELECT * FROM erp_document_setup WHERE status = 0";
	if($res = $dbc->query($sql)){
		while($row = $res->fetch_assoc()){
			$setup[$row['id']] = $row['id'];
			$setup[$row['id']] = $row['name'];
		}
	}
	var_dump($setup);*/
	//var_dump($settings);
	
	
	
?>
    <link href="summernote/summernote-bs4.css?<?=time()?>" rel="stylesheet" />

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
			<a onClick="location.reload()"><i class="fa fa-file-invoice" ></i> &nbsp;<?=$lng['Document templates']?></a> : <span id="docTitle"></span>
			<span id="eSpinner" style="padding-left:10px; display:none"><i class="fa fa-refresh fa-spin"></i></span> 
			<span id="eMsg" style="display:none; padding:0 10px; color:#a00; font-weight:400"></span>
		</div>
		
		
		<div style="position:absolute; left:0; top:33px; right:65%; bottom:0; background:#fff;">
			
			<div class="smallNav">
				<ul>
					<!--<li id="btnUpdate" class="flr"><a><i class="fa fa-save"></i> &nbsp;<? //=$lng['Update']?></a>-->
				</ul>
			</div>
			
			<div id="leftTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:0; display:none; overflow-X:hidden">
				
			<table id="docTable" class="dataTables dubhead xhover">
				<thead>
					<tr>
						<th>&nbsp;#&nbsp;</th>
						<th>Documents</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>


			</div>
		</div>
					
		<div style="position:absolute; left:35%; top:33px; right:0; bottom:0; background: #f6f6f6; border-left:1px solid #ddd">
			
			<div class="smallNav">
				<ul>
					<li id="btnView" style="display:xnone"><a><i class="fa fa-eye"></i> &nbsp;Toggle view<? //=$lng['Edit']?></a>
					<li id="btnUpdate" style="display:none"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
					<li class="xxhide" style="display:none"><a id="btnCancel"><i class="fa fa-times"></i> &nbsp;<?=$lng['Cancel']?></a>
				</ul>
				  
			</div>
			
			<div id="rightTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:25px; display:none">
			
			<form id="docForm">
			
				<input type="hidden" name="id" />
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:0;">
				<input placeholder="Document name" style="width:100%; background:#f6f6f6; border:1px solid #f6f6f6; font-weight:600" type="text" name="name" />
				</div>

			<!--HEADER------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:5px 10px 7px; background:#f6f6f6">
					<label>
						<input type="hidden" name="settings[header]" value="0">
						<input id="cHeader" type="checkbox" name="settings[header]" value="1" class="checkbox"><span> Show header</span>
					</label>
				</div>
				
				<div id="headerSec" style="display:none">
				<table border="0" style="width:100%; margin-bottom:10px;">
					<tr style="border-bottom:1px solid #ddd">
						<td style="vertical-align:top; max-height:100px; padding-bottom:15px">
							<img id="temp_logo" style="display:block; max-height:70px; max-width:400px" src="<?=$data['logo'].'?'.time()?>" />
						</td>
						<td style="vertical-align:top; text-align:right; padding-bottom:8px; height:85px">
							<span id="company" style="font-size:16px; font-weight:600"><?=$data['company']?></span><br>
							<span style="white-space:pre; font-size:13px; line-height:100%" id="address"><?=$data['address']?></span>
						</td>
					</tr>
				</table>
				</div>
			
			<!--ADDRESS------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:5px 10px 7px; background:#f6f6f6">
				<label>
					<input type="hidden" name="settings[address]" value="0">
					<input id="cAddress" type="checkbox" name="settings[address]" value="1" class="checkbox"><span> Show customer address</span>
				</label>
				</div>
				<div id="addressSec" style="display:none">
				<div id="addressSec" style="border:1px solid #eee; border-radius:5px; padding:10px 15px; float:right; display:inline-block; width:400px; min-height:110px; margin-right:50px; color:#aaa; font-style: italic; margin-top:10px">
					<span id="customer" style="font-size:15px; font-weight:600">X-RAY ICT & CONSULTING Co., Ltd.</span><br>
					<span style="">222/75, Moo 7<br>Nongprue - Banglamung<br>20150 Chonburi<br>Thailand</span>
				</div>
				<div class="clear" style="height:10px"></div>
				</div>
				<div class="clear"></div>
			
			<!--REFERENCES------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:0; background:#f6f6f6">
				<select id="cRef" name="settings[reference]" style="width:100%; border:1px solid #f6f6f6; cursor:pointer">
					<option value="0">Reference section : Hide</option>
					<option value="invTable">Reference section : Invoice</option>
					<option value="quoTable">Reference section : Quotation</option>
					<option value="refTable">Reference section : Date & Reference</option>
				</select>
				</div>
				
				<div class="refTable" id="invTable" style="display:none">
				<div style="margin-bottom:10px">
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
				
				<div class="refTable" id="quoTable" style="display:none">
				<div style="margin-bottom:10px">
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
				
				<div class="refTable" id="refTable" style="display:none">
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
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:0; background:#f6f6f6">
				<select id="cItems" name="settings[items]" style="width:100%; border:1px solid #f6f6f6; cursor:pointer">
					<option value="0">Items section : Hide</option>
					<option value="invSec">Items section : Invoice</option>
					<option value="quoSec">Items section : Quotation</option>
					<option value="ordSec">Items section : Order</option>
					<option value="txtSec">Items section : Text area</option>
				</select>
				</div>
				
				<div class="itemTable" id="invSec" style="display:none">
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
				
				<div class="itemTable" id="quoSec" style="display:none">
				<table class="itemsTable" border="0" style="margin-bottom:10px; table-layout:auto">
					<thead>
						<tr>
							<th colspan="2" style="text-align:left; width:60%"><?=$lng['Description']?></th>
							<th style="width:10%"><?=$lng['VAT']?> %</th>
							<th style="width:10%"><?=$lng['Amount']?></th>
							<th style="width:1%" class="pl0"><small><?=$currency?></small></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2" style="text-align:left">Product description</td>
							<td><?=number_format(7,2)?></td>
							<td><?=number_format(5000,2)?></td>
							<td class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:left">Service description</td>
							<td><?=number_format(7,2)?></td>
							<td><?=number_format(1000,2)?></td>
							<td class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr><td colspan="5" style="border:0; padding:5px"></td></tr>
						<tr>
							<td style="border-bottom:0; width:80%"></td>
							<th style="border-bottom:1px solid #eee" colspan="2"><?=$lng['Subtotal']?></th>
							<td style="border-bottom:1px solid #eee"><?=number_format(6000,2)?></td>
							<td style="border-bottom:1px solid #eee" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0"></td>
							<th style="border-bottom:1px solid #999" colspan="2"><?=$lng['VAT']?></th>
							<td style="border-bottom:1px solid #999"><?=number_format(420,2)?></td>
							<td style="border-bottom:1px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
						<tr>
							<td style="border-bottom:0"></td>
							<th style="border-bottom:2px solid #999" colspan="2"><?=$lng['Grand total']?></th>
							<td style="border-bottom:2px solid #999"><?=number_format(6420,2)?></td>
							<td style="border-bottom:2px solid #999" class="pl0"><small><?=$currency?></small></td>
						</tr>
					</tbody>
				</table>
				</div>
				
				<div class="itemTable" id="txtSec" style="display:none; margin-bottom:10px">
					<textarea class="summernote"></textarea>
				</div>
				
			<!--AMOUNT IN LETTERS------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:5px 10px 7px; background:#f6f6f6">
				<label>
					<input type="hidden" name="settings[words]" value="0">
					<input id="cWords" type="checkbox" name="settings[words]" value="1" class="checkbox"><span> Show amount in letters</span>
				</label>
				</div>
				<div id="wordSec" style="text-align:right; padding:0 0 10px; display:none">--- Six thousand three hundred and ninety Bath Only ---</div>
				
			<!--NOTE------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:5px 10px 7px; background:#f6f6f6">
				<label>
					<input type="hidden" name="settings[note]" value="0">
					<input id="cNote" type="checkbox" name="settings[note]" value="1" class="checkbox"><span> Show bottom note</span>
				</label>
				</div>
				<div id="noteSec" style="padding:0 0 10px; display:none"><b>Note : </b><i style="color:#999; font-style:italic">Comments</i></div>
				
			<!--FOOTER------------------------------------------------------------------------->
				<div class="view" style="margin-bottom:10px; display:block; display:xnone; padding:5px 10px 7px; background:#f6f6f6">
				<label>
					<input type="hidden" name="settings[footer]" value="0">
					<input id="cFooter" type="checkbox" name="settings[footer]" value="1" class="checkbox"><span> Show footer</span>
				</label>
				</div>
				<div id="footerSec" style="border-top:1px solid #ddd; text-align:center; line-height:160%; padding-top:5px; font-size:12px; display:none; margin-top:15px">
					<?=$data['footer']?>
				</div>
			</form>	
			</div>
			
		</div>
		<div style="clear:both"></div>

	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->
	<script src="datatables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
	<script src="datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
	<script src="summernote/summernote-bs4.js?<?=time()?>"></script>

	<script type="text/javascript">
		var headerCount = 1;

	$(document).ready(function() {

		$(document).on('click', '#docTable tbody tr', function(){
			var id = $(this).find('.id').html()
			//alert(id)
			getDocSetup(id)
		})
		function getDocSetup(id) {
			$('input[name="id"]').val(0)
			$('input[name="name"]').val('')
			$('#cHeader').prop('checked',false)
			$('#cAddress').prop('checked',false)
			$('#cRef').val(0).trigger('change')
			$('#cItems').val(0).trigger('change')
			$('#cWords').prop('checked',false)
			$('#cNote').prop('checked',false)
			$('#cFooter').prop('checked',false)

			$('#headerSec').hide()
			$('#addressSec').hide()
			$('#wordSec').hide()
			$('#noteSec').hide()
			$('#footerSec').hide()
			//$('#btnUpdate').hide()
			$.ajax({
				url:"document_setup/ajax/get_doc_template.php",
				data: {id: id},
				dataType: "json",
				success: function(data){
					$("#rightTable").hide()
					//$('#dump').html(data);
					//return false
					$('input[name="id"]').val(data.id)
					$('#docTitle').html(data.name)
					$('input[name="name"]').val(data.name)
					if(data.settings.header == 1){
						$('#cHeader').prop('checked',true)
						$('#headerSec').show()
					}
					if(data.settings.address == 1){
						$('#cAddress').prop('checked',true)
						$('#addressSec').show()
					}
					$('#cRef').val(data.settings.reference).trigger('change')
					$('#cItems').val(data.settings.items).trigger('change')
					if(data.settings.words == 1){
						$('#cWords').prop('checked',true)
						$('#wordSec').show()
					}
					if(data.settings.note == 1){
						$('#cNote').prop('checked',true)
						$('#noteSec').show()
					}
					if(data.settings.footer == 1){
						$('#cFooter').prop('checked',true)
						$('#footerSec').show()
					}

					$('#btnUpdate').show()
					//$('.summernote').summernote(summerOptions);
					setTimeout(function(){
						$("#rightTable").fadeIn(200)
					},100);
				},
				error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError+' 1');
				}
			});
		}
		
		
		$(document).on('click', "#btnView", function(e){
			$('.view').slideToggle(200)
		})
		$(document).on('click', "#cHeader", function(e){
			if($(this).is(':checked')){
				$('#headerSec').slideDown(200)
			}else{
				$('#headerSec').slideUp(200)
			}
		})
		$(document).on('click', "#cAddress", function(e){
			if($(this).is(':checked')){
				$('#addressSec').slideDown(200)
			}else{
				$('#addressSec').slideUp(200)
			}
		})
		$(document).on('change', "#cRef", function(e){
			$('.refTable').hide()
			$('#'+$(this).val()).slideDown(200)
		})
		
		$(document).on('change', "#cItems", function(e){
			$('.itemTable').hide()
			$('#'+$(this).val()).slideDown(200)
		})
		$(document).on('click', "#cWords", function(e){
			if($(this).is(':checked')){
				$('#wordSec').slideDown(200)
			}else{
				$('#wordSec').slideUp(200)
			}
		})
		$(document).on('click', "#cNote", function(e){
			if($(this).is(':checked')){
				$('#noteSec').slideDown(200)
			}else{
				$('#noteSec').slideUp(200)
			}
		})
		$(document).on('click', "#cFooter", function(e){
			if($(this).is(':checked')){
				$('#footerSec').slideDown(200)
			}else{
				$('#footerSec').slideUp(200)
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
					//$('#dump').html(result);
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
		var summerOptions = {
			//placeholder: 'Start typing ...',
			tooltip: false,
			height: 200,
			disableResizeEditor: true
		}
		$('.summernote').summernote(summerOptions);
		$('.summernote').summernote('disable');
		
		var dtable = $('#docTable').DataTable({
			scrollY:        	false,
			scrollX:        	false,
			scrollCollapse: 	false,
			fixedColumns:   	false,
			lengthChange:  	false,
			searching: 			true,
			ordering: 			false,
			paging: 				false,
			pageLength: 		16,//tablerows,
			filter: 				false,
			info: 				false,
			processing: 		false,
			serverSide: 		true,
			<?=$dtable_lang?>
			//order: 				[[0, "desc"]],
			columnDefs: [
				{ targets: [1], width: '99%' },
				{ targets: [0], class: 'tac bold' },
			],
			ajax: {
				url: "document_setup/ajax/server_get_document_setup.php"
			},
			initComplete : function( settings, json ) {
				$('#leftTable').fadeIn(200);
				dtable.columns.adjust().draw();
			}
		});
		$("#searchFilter").on('keyup', function() {
			dtable.column(0).search(this.value).draw();
		});
		$(document).on("click", "#clearSearchbox", function(e) {
			$('#searchFilter').val('');
			dtable.column(0).search('').draw();
		})
		
	})
	
</script>





















	
