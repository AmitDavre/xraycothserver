<?php
	
	include('inc_pnd1_kor_form.php');
		
?>	

<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/forms/pnd1_kor_en.png?<?=time()?>) no-repeat; background-size:cover">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
	</div>
	
	<? if($address){ ?>
	<div style="position:relative; margin-top:2px">
	<form id="tax_return">	
	<div class="field" style="top:33.2mm;left:57.6mm"><?=$pin[0]?></div>
	<div class="field" style="top:33.2mm;left:64mm"><?=$pin[1]?></div>
	<div class="field" style="top:33.2mm;left:68.4mm"><?=$pin[2]?></div>
	<div class="field" style="top:33.2mm;left:72.6mm"><?=$pin[3]?></div>
	<div class="field" style="top:33.2mm;left:76.8mm"><?=$pin[4]?></div>
	<div class="field" style="top:33.2mm;left:83.4mm"><?=$pin[5]?></div>
	<div class="field" style="top:33.2mm;left:87.6mm"><?=$pin[6]?></div>
	<div class="field" style="top:33.2mm;left:91.8mm"><?=$pin[7]?></div>
	<div class="field" style="top:33.2mm;left:96.2mm"><?=$pin[8]?></div>
	<div class="field" style="top:33.2mm;left:100.4mm"><?=$pin[9]?></div>
	<div class="field" style="top:33.2mm;left:107mm"><?=$pin[10]?></div>
	<div class="field" style="top:33.2mm;left:111.4mm"><?=$pin[11]?></div>
	<div class="field" style="top:33.2mm;left:117.8mm"><?=$pin[12]?></div>

	<div class="field13" style="top:42mm;left:189mm"><?=$_SESSION['rego']['year_en']?></div>

	<div class="field" style="top:45mm;left:101.6mm"><?=$branch[0]?></div>
	<div class="field" style="top:45mm;left:106mm"><?=$branch[1]?></div>
	<div class="field" style="top:45mm;left:110.2mm"><?=$branch[2]?></div>
	<div class="field" style="top:45mm;left:114.6mm"><?=$branch[3]?></div>
	<div class="field" style="top:45mm;left:118.8mm"><?=$branch[4]?></div>

	<div class="field" style="top:51.8mm;left:12mm"><?=$edata['en_compname']?></div>
	
	<div class="field12" style="top:58.2mm;left:48.6mm"><?=$address["building"]?></div>
	<div class="field12" style="top:58.2mm;left:96mm"><?=$address["room"]?></div>
	<div class="field12" style="top:58.2mm;left:117.4mm"><?=$address["floor"]?></div>

	<div class="field12" style="top:64.8mm;left:17.6mm"><?=$address["number"]?></div>
	<div class="field12" style="top:64.8mm;left:40.7mm"><?=$address["moo"]?></div>
	<div class="field12" style="top:64.8mm;left:61.5mm"><?=$address["lane"]?></div>

	<div class="field12" style="top:70.8mm;left:21.5mm"><?=$address["road"]?></div>
	<div class="field12" style="top:70.8mm;left:95.8mm;"><?=$address["subdistrict"]?></div>

	<div class="field12" style="top:76.6mm;left:23mm"><?=$address["district"]?></div>
	<div class="field12" style="top:76.6mm;left:85mm"><?=$address["province"]?></div>

	<div class="field" style="top:83.2mm;left:29.8mm"><?=$post[0]?></div>
	<div class="field" style="top:83.2mm;left:34.2mm"><?=$post[1]?></div>
	<div class="field" style="top:83.2mm;left:38.4mm"><?=$post[2]?></div>
	<div class="field" style="top:83.2mm;left:42.6mm"><?=$post[3]?></div>
	<div class="field" style="top:83.2mm;left:47mm"><?=$post[4]?></div>

	<div class="field13" style="top:66.6mm;right:88.6mm">
		<input name="rfill" type="hidden" value="0" />
		<label><input checked type="radio" name="rfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:66.6mm;right:57.6mm">
		<label><input type="radio" name="rfill" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:67.2mm;right:-0.5mm;width:50px"><input class="tac" maxlength="3" style="width:25px" name="fill" type="text" /></div>
	
	<div class="field13" style="top:121.8mm;left:114.6mm">
		<input name="pag" type="hidden" value="0" />
		<label><input checked type="radio" name="pag" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:128.6mm;left:114.6mm">
		<label><input disabled type="radio" name="pag" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:121.9mm;left:198.5mm"><?=$pages?></div>
	<div class="field13" style="top:135.2mm;left:175.5mm"><input style="width:150px" name="control1" type="text" /></div>
	<div class="field13" style="top:140.5mm;left:195.5mm"><input style="width:75px" name="control2" type="text" /></div>

	<div class="field13n" style="top:164.8mm;left:110mm"><?=$emps?></div>
	<div class="field13n" style="top:219.4mm;left:110mm"><?=$emps?></div>

	<div class="field13n" style="top:164.8mm;left:145mm"><?=number_format($income,2)?></div>
	<div class="field13n" style="top:219.4mm;left:145mm"><?=number_format($income,2)?></div>
	
	<div class="field12" style="top:184.6mm;left:65.5mm"><input style="width:55px" name="docnr" type="text"></div>
	<div class="field12" style="top:184.6mm;left:92mm"><input style="width:60px" name="docdate" type="text""></div>
	
	<div class="field13n" style="top:164.8mm;left:181.4mm"><?=number_format($tax,2)?></div>
	<div class="field13n" style="top:219.4mm;left:181.4mm"><?=number_format($tax,2)?></div>

	<? if($edata['digi_signature'] && !empty($edata['dig_signature'])){ ?>
	<div class="field" style="top:244mm;left:94mm"><img width="200px" src="<?=ROOT.$edata['dig_signature']?>?<?=time()?>" /></div>
	<? }if($edata['digi_stamp'] && !empty($edata['dig_stamp'])){ ?>
	<div class="field" style="top:247mm;right:29mm"><img width="90px" src="<?=ROOT.$edata['dig_stamp']?>?<?=time()?>" /></div>
	<? } ?>
	
	<div class="field13" style="top:256.2mm;left:84mm"><input class="formName" name="form_name" style="width:260px; text-align:left" type="text" value="<?=$name_position['name']?>"></div>
	<div class="field13" style="top:262.2mm;left:87mm"><input class="formPosition" name="form_position" style="width:250px" type="text" value="<?=$name_position['position']?>"></div>
	
	<div class="field13 formDay" style="top:267.6mm;left:92mm"><?=$_SESSION['rego']['formdate']['d']?></div>
	<div class="field13 formMonth" style="top:267.6mm;left:111mm"><?=$_SESSION['rego']['formdate']['m']?></div>
	<div class="field13 formYear" style="top:267.6mm;left:144mm"><?=$_SESSION['rego']['formdate'][$lang.'y']?></div>
	
	</form>
	</div>
	<? } ?>
	
</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#tax_return").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd1_kor_en.php?'+data, '_blank')
			});
			
			
			$(document).on("click", "#submit", function(e) {
				//if(!confirm("Are you sure you want to update employees ?")){return false;}	
				var data = $("#tax_return").serialize();
				//alert(data)
				//return false;
				$.ajax({
					url: ROOT+"payroll/ajax/ajax_save_income_tax_return.php",
					data: data,
					success:function(response){
						//alert(response)
						if($.trim(response)=='ok'){
							$("#message").fadeIn();
							$("#message").html('<div class="msg_success">Form data saved successfuly.</div>')
							$("#message").fadeOut(4000);
						}else{
							$("#message").html('<div class="msg_error"><b>Error : </b>'+$.trim(response)+'</div>')
						}
						
					},
					error:function (xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});
			
			$(document).on("change", ".tax", function(e) {
				var v = $(this).val();
				val = v.split(',').join('');
				//var s = $("#sur").val();
				//sur = s.split(',').join('');
				var tot = 0;
				$('input[name^="tax"]').each(function() {
					var e = $(this).val().split(',').join('');
					var eVal = (!$.isNumeric(e)) ? 0 : e;
					tot += parseFloat(eVal);
				});
				var total = parseFloat(tot);//+parseFloat(sur);
				$(this).val(parseFloat(val).format(2));
				$("#tottax").val(parseFloat(tot).format(2));
				$("#total").val(parseFloat(total).format(2));
				//alert(tot);
			});
			
			$(document).on("change", ".income", function(e) {
				var val = $(this).val();
				val = val.split(',').join('');
				var tot = 0;
				$('input[name^="income"]').each(function() {
					var e = $(this).val().split(',').join('');
					var eVal = (!$.isNumeric(e)) ? 0 : e;
					tot += parseFloat(eVal);
				});
				$(this).val(parseFloat(val).format(2));
				$("#totincome").val(parseFloat(tot).format(2));
				//alert(tot);
			});
			
			$(document).on("change", ".person", function(e) {
				var tot = 0;
				$('input[name^="person"]').each(function() {
					var e = $(this).val().split(',').join('');
					var eVal = (!$.isNumeric(e)) ? 0 : e;
					tot += parseFloat(eVal);
				});
				$("#totperson").val(parseFloat(tot));
				//alert(tot);
			});
			
			/*$(document).on("change", "#xxsur", function(e) {
				var val = $(this).val();
				val = val.split(',').join('');
				var tot = $("#tottax").val();
				tot = tot.split(',').join('');
				var total = parseFloat(val)+parseFloat(tot);
				$(this).val(parseFloat(val).format(2));
				$("#total").val(parseFloat(total).format(2));
				//alert(tot);
			});*/
			
			 $(".tax").change();
			 //$("#sur").change();
			 $(".income").change();
			 $(".person").change();
			
		})
	
	</script>













