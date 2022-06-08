<?php
	
	include('gov_pnd3_form.php');
	
?>	
<style>
	.field {
		xfont-size:13px !important;
	}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/forms/pnd3_th.png?<?=time()?>) no-repeat; background-size:cover">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<? if(isset($_POST)){ ?>
		<button data-type="" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
	
	<? if($address){ ?>
	<div style="position:relative; margin-top:2px">
	
	<form id="tax_return">
		
	<div class="field" style="top:33mm;left:56mm"><?=$pin[0]?></div>
	<div class="field" style="top:33mm;left:62.8mm"><?=$pin[1]?></div>
	<div class="field" style="top:33mm;left:67.6mm"><?=$pin[2]?></div>
	<div class="field" style="top:33mm;left:72mm"><?=$pin[3]?></div>
	<div class="field" style="top:33mm;left:76.2mm"><?=$pin[4]?></div>
	<div class="field" style="top:33mm;left:83mm"><?=$pin[5]?></div>
	<div class="field" style="top:33mm;left:87.4mm"><?=$pin[6]?></div>
	<div class="field" style="top:33mm;left:91.8mm"><?=$pin[7]?></div>
	<div class="field" style="top:33mm;left:96.6mm"><?=$pin[8]?></div>
	<div class="field" style="top:33mm;left:101mm"><?=$pin[9]?></div>
	<div class="field" style="top:33mm;left:108mm"><?=$pin[10]?></div>
	<div class="field" style="top:33mm;left:112.4mm"><?=$pin[11]?></div>
	<div class="field" style="top:33mm;left:119.2mm"><?=$pin[12]?></div>

	<div class="field13" style="top:41mm;left:203mm"><?=$_SESSION['rego']['year_th']?></div>

	<div class="field" style="top:45.8mm;left:102.4mm"><?=$branch[0]?></div>
	<div class="field" style="top:45.8mm;left:106.8mm"><?=$branch[1]?></div>
	<div class="field" style="top:45.8mm;left:111.2mm"><?=$branch[2]?></div>
	<div class="field" style="top:45.8mm;left:115.8mm"><?=$branch[3]?></div>
	<div class="field" style="top:45.8mm;left:120.0mm"><?=$branch[4]?></div>

	<div class="field" style="top:52mm;left:7mm"><?=$data[$lang.'_compname']?></div>
	
	<div class="field12" style="top:59.2mm;left:26.6mm"><?=$address["building"]?></div>
	<div class="field12" style="top:59.2mm;left:66mm"><?=$address["room"]?></div>
	<div class="field12" style="top:59.2mm;left:81.0mm"><?=$address["floor"]?></div>
	<div class="field12" style="top:65.8mm;left:16mm"><?=$address["number"]?></div>
	<div class="field12" style="top:65.8mm;left:53mm"><?=$address["moo"]?></div>
	<div class="field12" style="top:65.8mm;left:75.6mm"><?=$address["lane"]?></div>
	<div class="field12" style="top:72.2mm;left:14.0mm"><?=$address["road"]?></div>
	<div class="field" style="top:71.4mm;left:78mm;"><?=$address["subdistrict"]?></div>
	<div class="field" style="top:78.2mm;left:23mm"><?=$address["district"]?></div>
	<div class="field" style="top:78.2mm;left:79mm"><?=$address["province"]?></div>
	
	<div class="field" style="top:83.8mm;left:27.6mm"><?=$post[0]?></div>
	<div class="field" style="top:83.8mm;left:32.2mm"><?=$post[1]?></div>
	<div class="field" style="top:83.8mm;left:36.6mm"><?=$post[2]?></div>
	<div class="field" style="top:83.8mm;left:40.8mm"><?=$post[3]?></div>
	<div class="field" style="top:83.8mm;left:44.8mm"><?=$post[4]?></div>

	<div class="field13" style="top:84.4mm;left:55mm"><?=$data['comp_phone']?></div>

	<div class="field13" style="top:100.6mm;left:23mm">
		<!--<input name="rfill" type="hidden" value="0" />-->
		<label><input <? if($rfill==1){echo 'checked';} ?> type="radio" name="rfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:100.6mm;left:62.4mm">
		<!--<input name="rfill" type="hidden" value="0" />-->
		<label><input <? if($rfill==2){echo 'checked';} ?> type="radio" name="rfill" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:100.6mm;left:102.6mm;width:55px">
		<input class="sel numeric tac" maxlength="3" style="width:26px" name="fill" type="text" value="<?=$fill?>" />
	</div>
	
	<div class="field13" style="top:117.4mm;left:43.4mm">
		<!--<input name="xfill" type="hidden" value="0" />-->
		<label><input checked type="radio" name="xfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:117.4mm;left:89.4mm">
		<!--<input name="xfill" type="hidden" value="0" />-->
		<label><input disabled type="radio" name="xfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:117.4mm;left:136mm">
		<!--<input name="xfill" type="hidden" value="0" />-->
		<label><input disabled type="radio" name="xfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	
	<div class="field13" style="top:130.6mm;left:197.8mm">
		<input class="sel numeric tac" maxlength="4" style="width:38px" name="emps" type="text" value="<?=$emps?>" />
	</div>
	<div class="field13" style="top:137.2mm;left:197.8mm">
		<input class="sel numeric tac" maxlength="4" style="width:38px" name="atts" type="text" value="<?=$pages?>" />
	</div>
	
	<div class="field" style="top:49.4mm;left:126.6mm">
		<? if($month==1){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:49.4mm;left:149.4mm">
		<? if($month==4){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:49.4mm;left:172.6mm">
		<? if($month==7){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:49.4mm;left:194.4mm">
		<? if($month==10){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:58.8mm;left:126.6mm">
		<? if($month==2){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:58.8mm;left:149.4mm">
		<? if($month==5){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:58.8mm;left:172.6mm">
		<? if($month==8){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:58.8mm;left:194.4mm">
		<? if($month==11){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:68.4mm;left:126.6mm">
		<? if($month==3){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:68.4mm;left:149.4mm">
		<? if($month==6){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:68.4mm;left:172.6mm">
		<? if($month==9){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:68.4mm;left:194.4mm">
		<? if($month==12){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13n" style="top:193.4mm;left:172.6mm"><?=$income?></div>
	
	<div class="field13n" style="top:200.6mm;left:172.6mm"><?=$tax?></div>

	<div class="field13n" style="top:214.8mm;left:172.6mm"><?=$tax?></div>

	<? if($data['digi_signature'] == 1 && !empty($data['dig_signature'])){ ?>
	<div class="field" style="top:242mm;left:85mm"><img width="200px" src="<?=ROOT.$data['dig_signature']?>?<?=time()?>" /></div>
	<? }if($data['digi_stamp'] == 1 && !empty($data['dig_stamp'])){ ?>
	<div class="field" style="top:247mm;right:25mm"><img width="90px" src="<?=ROOT.$data['dig_stamp']?>?<?=time()?>" /></div>
	<? } ?>
	
	<div class="field13" style="top:253.8mm;left:83mm"><input class="formName" name="form_name" style="width:215px;" type="text" value="<?=$name_position['name']?>"></div>
	<div class="field13" style="top:260.6mm;left:89mm"><input class="formPosition" name="form_position" style="width:225px" type="text" value="<?=$name_position['position']?>"></div>
	
	<div class="field13 formDay" style="top:267.2mm;left:85.6mm"><?=$_SESSION['rego']['formdate']['d']?></div>
	<div class="field13 formMonth" style="top:267.2mm;left:102mm"><?=$_SESSION['rego']['formdate']['m']?></div>
	<div class="field13 formYear" style="top:267.2mm;left:140mm"><?=$_SESSION['rego']['formdate'][$lang.'y']?></div>
	
	</form>
	</div>
	<? } ?>
	
</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#tax_return").serialize();
				data += '&' + $(this).data('type');
				window.open('print/print_pnd3_monthly_th.php?'+data, '_blank')
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
				var s = $("#sur").val();
				sur = s.split(',').join('');
				var tot = 0;
				$('input[name^="tax"]').each(function() {
					var e = $(this).val().split(',').join('');
					var eVal = (!$.isNumeric(e)) ? 0 : e;
					tot += parseFloat(eVal);
				});
				var total = parseFloat(tot)+parseFloat(sur);
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
			
			$(document).on("change", "#sur", function(e) {
				var val = $(this).val();
				val = val.split(',').join('');
				var tot = $("#tottax").val();
				tot = tot.split(',').join('');
				var total = parseFloat(val)+parseFloat(tot);
				$(this).val(parseFloat(val).format(2));
				$("#total").val(parseFloat(total).format(2));
				//alert(tot);
			});
			
			 $(".tax").change();
			 $("#sur").change();
			 $(".income").change();
			 $(".person").change();
			
		})
	
	</script>













