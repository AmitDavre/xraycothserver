<?php
	
	include('gov_pnd3_form.php');
	
?>	
<style>
	.field {
		font-size:13px !important;
	}
</style>

<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/forms/pnd3_en.png?<?=time()?>) no-repeat; background-size:cover">
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
		
	<div class="field" style="top:55mm;left:114.2mm"><img src="../images/forms/check.png"></div>

	<div class="field" style="top:52.6mm;left:54.8mm"><?=$pin[0]?></div>
	
	<div class="field" style="top:52.6mm;left:60.6mm"><?=$pin[1]?></div>
	<div class="field" style="top:52.6mm;left:63.8mm"><?=$pin[2]?></div>
	<div class="field" style="top:52.6mm;left:67.0mm"><?=$pin[3]?></div>
	<div class="field" style="top:52.6mm;left:70.0mm"><?=$pin[4]?></div>
	
	<div class="field" style="top:52.6mm;left:76.2mm"><?=$pin[5]?></div>
	<div class="field" style="top:52.6mm;left:79.6mm"><?=$pin[6]?></div>
	<div class="field" style="top:52.6mm;left:82.6mm"><?=$pin[7]?></div>
	<div class="field" style="top:52.6mm;left:85.6mm"><?=$pin[8]?></div>
	<div class="field" style="top:52.6mm;left:88.6mm"><?=$pin[9]?></div>
	
	<div class="field" style="top:52.6mm;left:94.8mm"><?=$pin[10]?></div>
	<div class="field" style="top:52.6mm;left:98mm"><?=$pin[11]?></div>
	
	<div class="field" style="top:52.6mm;left:104.2mm"><?=$pin[12]?></div>

	<div class="field13" style="top:81.2mm;left:209mm"><?=$_SESSION['rego']['year_en']?></div>

	<div class="field" style="top:74.6mm;left:91.2mm"><?=$branch[0]?></div>
	<div class="field" style="top:74.6mm;left:94.2mm"><?=$branch[1]?></div>
	<div class="field" style="top:74.6mm;left:97.4mm"><?=$branch[2]?></div>
	<div class="field" style="top:74.6mm;left:100.4mm"><?=$branch[3]?></div>
	<div class="field" style="top:74.6mm;left:103.6mm"><?=$branch[4]?></div>

	<div class="field" style="top:88.4mm;left:5mm"><?=$data[$lang.'_compname']?></div>
	
	<div class="field" style="top:96.8mm;left:63mm"><?=$address["building"]?></div>
	<div class="field" style="top:105.2mm;left:18mm"><?=$address["room"]?></div>
	<div class="field" style="top:105.2mm;left:47mm"><?=$address["floor"]?></div>
	<div class="field" style="top:105.2mm;left:68mm"><?=$address["number"]?></div>
	<div class="field" style="top:105.2mm;left:92mm"><?=$address["moo"]?></div>
	<div class="field" style="top:113.8mm;left:18mm"><?=$address["lane"]?></div>
	<div class="field" style="top:113.8mm;left:67mm"><?=$address["road"]?></div>
	<div class="field" style="top:122.8mm;left:71mm;"><?=$address["district"]?></div>
	<div class="field" style="top:122.8mm;left:21mm"><?=$address["subdistrict"]?></div>
	<div class="field" style="top:132mm;left:18mm"><?=$address["province"]?></div>
	
	<div class="field" style="top:132mm;left:78.6mm"><?=$post[0]?></div>
	<div class="field" style="top:132mm;left:81.6mm"><?=$post[1]?></div>
	<div class="field" style="top:132mm;left:84.6mm"><?=$post[2]?></div>
	<div class="field" style="top:132mm;left:87.8mm"><?=$post[3]?></div>
	<div class="field" style="top:132mm;left:90.8mm"><?=$post[4]?></div>

	<div class="field13" style="top:140.4mm;left:10mm"><?=$data['comp_phone']?></div>
	
	<div class="field" style="top:95.4mm;left:117mm">
		<? if($month==1){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:95.4mm;left:142mm">
		<? if($month==4){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:95.4mm;left:163mm">
		<? if($month==7){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:95.4mm;left:191.2mm">
		<? if($month==10){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:104.2mm;left:117mm">
		<? if($month==2){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:104.2mm;left:142mm">
		<? if($month==5){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:104.2mm;left:163mm">
		<? if($month==8){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:104.2mm;left:191.2mm">
		<? if($month==11){echo '<img src="../images/forms/check.png">';}?>
	</div>
	
	<div class="field13" style="top:113mm;left:117mm">
		<? if($month==3){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:113mm;left:142mm">
		<? if($month==6){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:113mm;left:163mm">
		<? if($month==9){echo '<img src="../images/forms/check.png">';}?>
	</div>
	<div class="field13" style="top:113mm;left:191.2mm">
		<? if($month==12){echo '<img src="../images/forms/check.png">';}?>
	</div>

	<div class="field13" style="top:189.2mm;left:74mm"><img src="../images/forms/check.png"></div>
	
	<div class="field13n" style="top:223.2mm;left:107mm"><?=$income?></div>
	
	<div class="field13n" style="top:232.2mm;left:107mm"><?=$tax?></div>

	<div class="field13n" style="top:250mm;left:107mm"><?=$tax?></div>

	<div class="field" style="top:263.4mm;left:78mm"><?=$chars?> Baht</div>

	<div class="field13 formDay" style="top:288.8mm;left:81mm"><?=$_SESSION['rego']['formdate']['d']?></div>
	<div class="field13 formMonth" style="top:288.8mm;left:104mm"><?=$_SESSION['rego']['formdate']['m']?></div>
	<div class="field13 formYear" style="top:288.8mm;left:144mm"><?=$_SESSION['rego']['formdate'][$lang.'y']?></div>
	
	</form>
	</div>
	<? } ?>
	
</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#tax_return").serialize();
				data += '&' + $(this).data('type');
				window.open('print/print_pnd3_monthly_en.php?'+data, '_blank')
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













