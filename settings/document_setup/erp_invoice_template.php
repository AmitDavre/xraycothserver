<?
	//var_dump($compinfo);
	$data = array();
	$sql = "SELECT * FROM erp_document_templates WHERE id = '1'";
	if($res = $dbc->query($sql)){
		if($row = $res->fetch_assoc()){
			$data = $row;
		}
	}

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
	
	
	
</style>

	<div style="height:100%; border:0px solid red; position:relative">
		
		<div class="breadcrumbs">
			<a onClick="location.reload()"><i class="fa fa-file-invoice" ></i> &nbsp;<?=$lng['Document templates']?></a>
			<span id="eSpinner" style="padding-left:10px; display:none"><i class="fa fa-refresh fa-spin"></i></span> 
			<span id="eMsg" style="display:none; padding:0 10px; color:#a00; font-weight:400"></span>
		</div>
		
		
		<div style="position:absolute; left:0; top:33px; right:60%; bottom:0; background:#fff;">
			
			<div class="smallNav">
				<ul>
					<li id="btnUpdate" class="flr"><a><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
				</ul>
			</div>
			
			<div id="leftTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:0; display:xnone; overflow-X:hidden">
				
				<form id="tempForm">
				<table border="0" class="basicTable inputs" style="margin-bottom:15px">
					<thead>
						<tr>
							<th colspan="11"><?=$lng['Document header']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Company name']?> :</th>
							<td><input class="nopad" type="text" name="company" value="<?=$data['company']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Address']?> :</th>
							<td><textarea name="address" rows="3"><?=$data['address']?></textarea></td>
						</tr>
						<tr>
							<th style="vertical-align:bottom; padding-bottom:8px">
								<input style="visibility:hidden; height:0; width:0; position:absolute" type="file" name="logo" id="complogo" />
								<button onclick="$('#complogo').click()" class="csm btn btn-info btn-xs" style="margin:0; padding:0px 8px; float:right" type="button"><?=$lng['Select file']?></button>
							</th>
							<td style="padding:8px">
							<img id="logoimg" style="max-width:100%; max-height:60px" src="<?=$data['logo'].'?'.time()?>" /></td>
						</tr>
					</tbody>
				</table>
				
				<table border="0" class="basicTable inputs" style="margin-bottom:2px">
					<thead>
						<tr>
							<th colspan="11"><?=$lng['Document footer']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=$lng['Phone']?> :</th>
							<td><input class="nopad" type="text" name="phone" value="<?=$data['phone']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Fax']?> :</th>
							<td><input class="nopad" type="text" name="fax" value="<?=$data['fax']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['email']?> :</th>
							<td><input class="nopad" type="text" name="email" value="<?=$data['email']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Website']?> :</th>
							<td><input class="nopad" type="text" name="website" value="<?=$data['website']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['VAT']?> :</th>
							<td><input class="nopad" type="text" name="vat" value="<?=$data['vat']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Bank']?> #1 :</th>
							<td><input class="nopad" type="text" name="bank1" value="<?=$data['bank1']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Account']?> #1 :</th>
							<td><input class="nopad" type="text" name="acc1" value="<?=$data['acc1']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Swift/Bic']?> #1 :</th>
							<td><input class="nopad" type="text" name="bic1" value="<?=$data['bic1']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Bank']?> #2 :</th>
							<td><input class="nopad" type="text" name="bank2" value="<?=$data['bank2']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Account']?> #2 :</th>
							<td><input class="nopad" type="text" name="acc2" value="<?=$data['acc2']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Swift/Bic']?> #2 :</th>
							<td><input class="nopad" type="text" name="bic2" value="<?=$data['bic2']?>" /></td>
						</tr>
						<tr>
							<th><?=$lng['Default note']?> :</th>
							<td><textarea name="note" rows="3"><?=$data['note']?></textarea></td>
						</tr>
					</tbody>
            </table>
				<input name="id" type="hidden" value="<?=$data['id']?>" />
				</form>

			</div>
		</div>
					
		<div style="position:absolute; left:40%; top:33px; right:0; bottom:0; background: #f6f6f6; border-left:1px solid #ddd">
			
			<div class="smallNav">
				<ul>
					<li class="xhide" style="display:none"><a id="btnEdit"><i class="fa fa-edit"></i> &nbsp;<?=$lng['Edit']?></a>
					<li class="xxhide" style="display:none"><a id="btnUpdate"><i class="fa fa-save"></i> &nbsp;<?=$lng['Update']?></a>
					<li class="xxhide" style="display:none"><a id="btnCancel"><i class="fa fa-times"></i> &nbsp;<?=$lng['Cancel']?></a>
				</ul>
				  
			</div>
			
			<div id="rightTable" style="position:absolute; left:15px; top:45px; right:15px; bottom:15px; background:#fff; overflow-Y:auto; padding:30px; display:xnone">

<style>		
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
	table.itemsTable tbody tr {
		
	}
	table.itemsTable tbody td {
		padding:4px 0px 4px 10px;
		font-weight:400;
		text-align:right;
		white-space:nowrap;
		border-bottom:1px solid #eee;
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
		text-align:right;
	}
</style>
		
				<table border="0" style="width:100%; margin-bottom:50px">
					<tr style="border-bottom:1px solid #ddd">
						<td style="vertical-align:top; max-height:100px; padding-bottom:15px">
							<img id="temp_logo" style="display:block; max-height:60px; max-width:400px" src="<?=$data['logo'].'?'.time()?>" />
						</td>
						<td style="vertical-align:top; text-align:right; padding-bottom:8px; height:85px">
							<span id="company" style="font-size:16px; font-weight:600">comp_name_en</span><br>
							<span style="white-space:pre; font-size:13px; line-height:100%" id="address">address_en</span>
						</td>
					<tr>
				</table>
			
				<div style="border:1px solid #eee; border-radius:5px; padding:10px 15px; float:right; display:inline-block; width:400px; min-height:110px; margin-right:50px;">
					<span id="customer" style="font-size:15px; font-weight:600">X-RAY ICT & CONSULTING Co., Ltd.</span><br>
					<span style="">222/75, Moo 7<br>Nongprue - Banglamung<br>20150 Chonburi<br>Thailand</span>
				</div>
				
				
				<div style="clear:both; margin-bottom:10px">
					<span style="font-size:20px; font-weight:600; border-bottom:1px solid #ddd;"><?=$lng['Invoice']?> # INV2018-00001</span><br>
				</div>
				
				<table border="0" style="margin:0 0 0 5px">
					<tr>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Invoice date']?> :</b>
							<p><?=date('d-m-Y')?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Due date']?> :</b>
							<p><?=date('d-m-Y', strtotime("+30 days"))?></p>
						</td>
						<td style="vertical-align:top; padding-right:50px">
							<b><?=$lng['Your reference']?> :</b>
							<p style="color:#999; font-style:italic">Customer reference</p>
						</td>
					</tr>
				</table>
				
				<table class="itemsTable" border="0">
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
				
				<div id="words" style="text-align:right; padding:10px 0 20px;">--- Six thousand three hundred and ninety Bath Only ---</div>
				
				<div style="height:20px"></div>	
				
				<div id="note" style="padding:10px 0 20px;"></div>
				
				<div style="border-top:1px solid #ddd; text-align:center; line-height:160%; padding-top:5px; font-size:12px;">
					<span id="footer1">xxx</span><br>
					<span id="footer2">xxx</span><br>
				</div>
			
			</div>
			
		</div>
		<div style="clear:both"></div>

	</div>
	
	<!-- PAGE RELATED PLUGIN(S) -->

	<script type="text/javascript">


	$(document).ready(function() {

		$(document).on('click', "#btnUpdate", function(e){
			$('#tempForm').submit()
		})
		
		$(document).on('submit', "#tempForm", function(e){
			e.preventDefault();
			$('#eSpinner').show()
			var err = false;
			//var data = $(this).serialize();
			var data = new FormData($(this)[0]);
			$.ajax({
				url:"settings/ajax/save_document_template.php",
				type: 'POST',
				data: data,
				async: false,
				cache: false,
				contentType: false,
				processData: false,
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





















	
