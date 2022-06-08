<?php
	
	include('gov_pnd1_form.php');
	
?>	

<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/forms/pnd1_th.png?<?=time()?>) no-repeat; background-size:cover">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<? if(isset($_POST)){ ?>
		<button type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
	
	<? if($address){ ?>
	<div style="position:relative; margin-top:2px">
	<form id="tax_return">
		
	<div class="field" style="top:32mm;left:57.6mm"><?=$pin[0]?></div>
	<div class="field" style="top:32mm;left:64.4mm"><?=$pin[1]?></div>
	<div class="field" style="top:32mm;left:68.8mm"><?=$pin[2]?></div>
	<div class="field" style="top:32mm;left:73.4mm"><?=$pin[3]?></div>
	<div class="field" style="top:32mm;left:78mm"><?=$pin[4]?></div>
	<div class="field" style="top:32mm;left:84.6mm"><?=$pin[5]?></div>
	<div class="field" style="top:32mm;left:89mm"><?=$pin[6]?></div>
	<div class="field" style="top:32mm;left:94mm"><?=$pin[7]?></div>
	<div class="field" style="top:32mm;left:98.4mm"><?=$pin[8]?></div>
	<div class="field" style="top:32mm;left:103mm"><?=$pin[9]?></div>
	<div class="field" style="top:32mm;left:109.6mm"><?=$pin[10]?></div>
	<div class="field" style="top:32mm;left:114.2mm"><?=$pin[11]?></div>
	<div class="field" style="top:32mm;left:120.8mm"><?=$pin[12]?></div>
	
	<div class="field13" style="top:39.8mm;left:203mm"><?=$_SESSION['rego']['year_th']?></div>

	<div class="field" style="top:44mm;left:103.6mm"><?=$branch[0]?></div>
	<div class="field" style="top:44mm;left:108.2mm"><?=$branch[1]?></div>
	<div class="field" style="top:44mm;left:112.6mm"><?=$branch[2]?></div>
	<div class="field" style="top:44mm;left:117.2mm"><?=$branch[3]?></div>
	<div class="field" style="top:44mm;left:121.4mm"><?=$branch[4]?></div>

	<div class="field" style="top:50.6mm;left:8mm"><?=$data[$lang.'_compname']?></div>
	<div class="field12" style="top:57.8mm;left:28mm"><?=$address["building"]?></div>
	<div class="field12" style="top:57.8mm;left:68mm"><?=$address["room"]?></div>
	<div class="field12" style="top:57.8mm;left:83mm"><?=$address["floor"]?></div>
	<div class="field12" style="top:57.8mm;left:99mm"><?=$address["village"]?></div>
	<div class="field12" style="top:64mm;left:16.6mm"><?=$address["number"]?></div>
	<div class="field12" style="top:64mm;left:54.8mm"><?=$address["moo"]?></div>
	<div class="field12" style="top:64mm;left:76.6mm"><?=$address["lane"]?></div>
	<div class="field12" style="top:70.4mm;left:15.6mm"><?=$address["road"]?></div>
	<div class="field12" style="top:70.4mm;left:79mm;"><?=$address["subdistrict"]?></div>
	<div class="field12" style="top:76.8mm;left:25mm"><?=$address["district"]?></div>
	<div class="field12" style="top:76.8mm;left:79mm"><?=$address["province"]?></div>
	<div class="field" style="top:82.4mm;left:29.4mm"><?=$post[0]?></div>
	<div class="field" style="top:82.4mm;left:33.8mm"><?=$post[1]?></div>
	<div class="field" style="top:82.4mm;left:38mm"><?=$post[2]?></div>
	<div class="field" style="top:82.4mm;left:42.4mm"><?=$post[3]?></div>
	<div class="field" style="top:82.4mm;left:46.6mm"><?=$post[4]?></div>
	<div class="field13" style="top:82.8mm;left:57mm"><?=$data['comp_phone']?></div>

	<div class="field13" style="top:99.2mm;left:25.8mm">
		<input name="rfill" type="hidden" value="0" />
		<label><input <? if($rfill==1){echo 'checked';} ?> type="radio" name="rfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:99.2mm;left:64.8mm">
		<label><input <? if($rfill==2){echo 'checked';} ?> type="radio" name="rfill" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:99.8mm;left:103.8mm;width:55px"><input class="sel numeric tac" maxlength="3" style="width:26px" name="fill" type="text" value="<?=$fill?>" /></div>
	
	<div class="field13" style="top:117.2mm;left:98.2mm">
		<input name="pag" type="hidden" value="0" />
		<label><input checked type="radio" name="pag" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:125.2mm;left:98.2mm">
		<label><input disabled type="radio" name="pag" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:117.6mm;left:195mm"><?=$pages?></div>
	<div class="field13" style="top:131.2mm;left:171.4mm"><input style="width:150px" name="pnd1_controlnr" type="text" value="<?=$pnd1_controlnr?>" /></div>

	<div class="field" style="top:48.4mm;left:128mm">
		<? if($month==1){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:48.4mm;left:150.8mm">
		<? if($month==4){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:48.4mm;left:174mm">
		<? if($month==7){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:48.4mm;left:196mm">
		<? if($month==10){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:57.8mm;left:128mm">
		<? if($month==2){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:57.8mm;left:150.8mm">
		<? if($month==5){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:57.8mm;left:174mm">
		<? if($month==8){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:57.8mm;left:196mm">
		<? if($month==11){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:67.6mm;left:128mm">
		<? if($month==3){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:67.6mm;left:150.8mm">
		<? if($month==6){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:67.6mm;left:174mm">
		<? if($month==9){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:67.6mm;left:196mm">
		<? if($month==12){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13n" style="top:159.6mm;left:111mm"><?=$emps?></div>
	<div class="field13n" style="top:202mm;left:111mm"><?=$empsd2?></div>
	<div class="field13n" style="top:215.8mm;left:111mm"><?=$empsTot?></div>

	<div class="field13n" style="top:159.4mm;left:146.6mm"><?=$income1?></div>
	<div class="field13n" style="top:202mm;left:146.6mm"><?=$income2?></div>
	<div class="field13n" style="top:215.6mm;left:146.6mm"><?=$income?></div>
	
	<div class="field12" style="top:180.4mm;left:43.8mm"><input style="width:85px" name="docnr" type="text" value="<?=$docnr?>"></div>
	<div class="field12" style="top:180.4mm;left:81.4mm"><input style="width:90px" name="docdate" type="text" value="<?=$docdate?>"></div>
	
	<div class="field13n" style="top:159.4mm;left:181.6mm"><?=$tax1?></div>
	<div class="field13n" style="top:202mm;left:181.6mm"><?=$tax2?></div>
	<div class="field13n" style="top:215.6mm;left:181.6mm"><?=$tax?></div>

	<div class="field13n" style="top:229.8mm;left:181.6mm"><?=$tax?></div>
	
	<? if($data['digi_signature'] == 1 && !empty($data['dig_signature'])){ ?>
	<div class="field" style="top:259mm;left:86mm"><img width="200px" src="<?=ROOT.$data['dig_signature']?>?<?=time()?>" /></div>
	<? }if($data['digi_stamp'] == 1 && !empty($data['dig_stamp'])){ ?>
	<div class="field" style="top:259mm;left:170mm"><img width="90px" src="<?=ROOT.$data['dig_stamp']?>?<?=time()?>" /></div>
	<? } ?>
	
	<div class="field13" style="top:270.4mm;left:85mm"><input placeholder="<?=$lng['Name']?>" class="formName" name="form
	_name" style="width:220px; text-align:center" type="text" value="<?=$name_position['name']?>"></div>
	<div class="field13" style="top:277.2mm;left:91mm"><input class="formPosition" name="form_position" style="width:200px" type="text" value="<?=$name_position['position']?>"></div>
	
	<div class="field13 formDay" style="top:284.2mm;left:88mm"><?=$_SESSION['rego']['formdate']['d']?></div>
	<div class="field13 formMonth" style="top:284.2mm;left:104mm"><?=$_SESSION['rego']['formdate']['m']?></div>
	<div class="field13 formYear" style="top:284.2mm;left:136mm"><?=$_SESSION['rego']['formdate'][$lang.'y']?></div>
	
	</form>
	</div>
	<? } ?>
	
</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#tax_return").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd1_monthly_th.php?'+data, '_blank')
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













