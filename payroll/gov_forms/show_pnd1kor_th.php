<?php
	
	include('inc_pnd1_kor_form.php');

?>	

<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">
<style>

</style>

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(../images/forms/pnd1_kor_th.png?<?=time()?>) no-repeat; background-size:cover">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
	</div>
	
	<? if($address){ ?>
	<div style="position:relative; margin-top:2px">
	<form id="tax_return">	
	<div class="field" style="top:33.2mm;left:59.6mm"><?=$pin[0]?></div>
	<div class="field" style="top:33.2mm;left:66mm"><?=$pin[1]?></div>
	<div class="field" style="top:33.2mm;left:70.4mm"><?=$pin[2]?></div>
	<div class="field" style="top:33.2mm;left:74.6mm"><?=$pin[3]?></div>
	<div class="field" style="top:33.2mm;left:78.8mm"><?=$pin[4]?></div>
	<div class="field" style="top:33.2mm;left:85.4mm"><?=$pin[5]?></div>
	<div class="field" style="top:33.2mm;left:89.6mm"><?=$pin[6]?></div>
	<div class="field" style="top:33.2mm;left:93.8mm"><?=$pin[7]?></div>
	<div class="field" style="top:33.2mm;left:98.2mm"><?=$pin[8]?></div>
	<div class="field" style="top:33.2mm;left:102.4mm"><?=$pin[9]?></div>
	<div class="field" style="top:33.2mm;left:109mm"><?=$pin[10]?></div>
	<div class="field" style="top:33.2mm;left:113.4mm"><?=$pin[11]?></div>
	<div class="field" style="top:33.2mm;left:119.8mm"><?=$pin[12]?></div>

	<div class="field13" style="top:42.2mm;left:196mm"><?=$_SESSION['rego']['year_th']?></div>

	<div class="field" style="top:44.2mm;left:102.6mm"><?=$branch[0]?></div>
	<div class="field" style="top:44.2mm;left:107mm"><?=$branch[1]?></div>
	<div class="field" style="top:44.2mm;left:111.2mm"><?=$branch[2]?></div>
	<div class="field" style="top:44.2mm;left:115.6mm"><?=$branch[3]?></div>
	<div class="field" style="top:44.2mm;left:119.8mm"><?=$branch[4]?></div>

	<div class="field" style="top:51mm;left:13mm"><?=$edata['th_compname']?></div>
	
	<div class="field12" style="top:58.8mm;left:34mm"><?=$address["building"]?></div>
	<div class="field12" style="top:58.8mm;left:98.5mm"><?=$address["room"]?></div>
	<div class="field12" style="top:58.8mm;left:117.4mm"><?=$address["floor"]?></div>

	<div class="field12" style="top:65.2mm;left:21.6mm"><?=$address["number"]?></div>
	<div class="field12" style="top:65.2mm;left:44.4mm"><?=$address["moo"]?></div>
	<div class="field12" style="top:65.2mm;left:68mm"><?=$address["lane"]?></div>

	<div class="field12" style="top:71.2mm;left:21.5mm"><?=$address["road"]?></div>
	<div class="field12" style="top:71.2mm;left:99mm;"><?=$address["subdistrict"]?></div>

	<div class="field12" style="top:77mm;left:30mm"><?=$address["district"]?></div>
	<div class="field12" style="top:77mm;left:84mm"><?=$address["province"]?></div>

	<div class="field" style="top:83.6mm;left:35.2mm"><?=$post[0]?></div>
	<div class="field" style="top:83.6mm;left:39.6mm"><?=$post[1]?></div>
	<div class="field" style="top:83.6mm;left:43.8mm"><?=$post[2]?></div>
	<div class="field" style="top:83.6mm;left:48.2mm"><?=$post[3]?></div>
	<div class="field" style="top:83.6mm;left:52.6mm"><?=$post[4]?></div>

	<div class="field13" style="top:66.6mm;right:86.4mm">
		<input name="rfill" type="hidden" value="0" />
		<label><input checked type="radio" name="rfill" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:66.6mm;right:53mm">
		<label><input type="radio" name="rfill" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:67.0mm;right:5.2mm;width:50px"><input class="tac" maxlength="3" style="width:25px" name="fill" type="text" /></div>
	
	<div class="field13" style="top:121.8mm;left:96.2mm">
		<input name="pag" type="hidden" value="0" />
		<label><input checked type="radio" name="pag" value="1" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:128.6mm;left:96.2mm">
		<label><input disabled type="radio" name="pag" value="2" class="checkbox notxt"><span></span></label>
	</div>
	<div class="field13" style="top:122.0mm;left:195mm"><?=$pages?></div>
	<div class="field13" style="top:135.7mm;left:168.5mm"><input style="width:160px" name="control1" type="text" /></div>
	<div class="field13" style="top:141.2mm;left:182.5mm"><input style="width:100px" name="control2" type="text" /></div>

	<div class="field13n" style="top:164.8mm;left:110mm"><?=$emps?></div>
	<div class="field13n" style="top:219.4mm;left:110mm"><?=$emps?></div>

	<div class="field13n" style="top:164.8mm;left:145mm"><?=number_format($income,2)?></div>
	<div class="field13n" style="top:219.4mm;left:145mm"><?=number_format($income,2)?></div>
	
	<div class="field12" style="top:184.6mm;left:39mm"><input style="width:55px" name="docnr" type="text"></div>
	<div class="field12" style="top:184.6mm;left:72mm"><input style="width:90px" name="docdate" type="text"></div>
	
	<div class="field13n" style="top:164.8mm;left:181.4mm"><?=number_format($tax,2)?></div>
	<div class="field13n" style="top:219.4mm;left:181.4mm"><?=number_format($tax,2)?></div>

	<? if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){ ?>
	<div class="field" style="top:250.6mm;left:84mm"><img width="200px" src="<?=ROOT.$edata['dig_signature']?>?<?=time()?>" /></div>
	<? }if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){ ?>
	<div class="field" style="top:247mm;right:29mm"><img width="90px" src="<?=ROOT.$edata['dig_stamp']?>?<?=time()?>" /></div>
	<? } ?>
	
	<div class="field13" style="top:248.4mm;left:84mm"><input class="formName" name="form_name" style="width:215px; text-align:left" type="text" value="<?=$name_position['name']?>"></div>
	<div class="field13" style="top:262.4mm;left:87mm"><input class="formPosition" name="form_position" style="width:250px" type="text" value="<?=$name_position['position']?>"></div>
	
	<div class="field13 formDay" style="top:269.2mm;left:86mm"><?=$_SESSION['rego']['formdate']['d']?></div>
	<div class="field13 formMonth" style="top:269.2mm;left:102mm"><?=$_SESSION['rego']['formdate']['m']?></div>
	<div class="field13 formYear" style="top:269.2mm;left:140mm"><?=$_SESSION['rego']['formdate'][$lang.'y']?></div>
	
	</form>
	</div>
	<? } ?>

</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#tax_return").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd1_kor_th.php?'+data, '_blank')
			});
			
		})
	
	</script>













