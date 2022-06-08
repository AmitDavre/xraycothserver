<?php
	
	$emps = 0; $income[0] = 0; $tax[0] = 0; $mileage = 0; $meal = 0;
	if($res = $dbc->query("SELECT gross_income, tax_month FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['gov_month']."' AND tax > 0")){
		while($row = $res->fetch_object()){
			$emps ++;
			//$income[0] += ($row->gross_income - $row->total_non_allow - $row->tot_absence - $row->tot_deduct_after);
			$income[0] += $row->gross_income;
			$tax[0] += $row->tax_month;
		}
	}
	//$income[0] -= ($mileage + $meal);
	$pages = '';
	if(isset($_SESSION['rego']['pnd1_pages'])){ $pages = $_SESSION['rego']['pnd1_pages'];}

	$month = (int)$_SESSION['rego']['gov_month'];
	$p = str_replace('-','',$compinfo['tax_id']);
	if(strlen($p)!== 13){$p = '?????????????';}
	$pin = str_split($p);
	
	$branch = sprintf("%05d",$compinfo['branch']);
	$branch = str_split($branch);
	
	$address = unserialize($compinfo['th_addr_detail']);
	//var_dump($address); exit;
	if($address && $address['postal'] == ''){$address['postal'] = '?????';}
	if(strlen($address['postal']) != 5){$address['postal'] = '?????';}
	$post = str_split($address['postal']);
	
	//$post = $address['postal'];
	//if(strlen($post) !== 5){$post = '?????';}
	//$post = str_split($post);
	//var_dump($compinfo);
	
	$rfill = 1;
	$fill = '';
	$pag = 1;
	$pnd1_att_pages = '';
	$pnd1_disk_pages = '';
	$pnd1_controlnr = '';
	$person[0] = $emps;
	$person[1] = 0;
	$person[2] = 0;
	$person[3] = 0;
	$person[4] = 0;
	//$income[0] = $income;
	$income[1] = 0;
	$income[2] = 0;
	$income[3] = 0;
	$income[4] = 0;
	//$tax[0] = $tax;
	$tax[1] = 0;
	$tax[2] = 0;
	$tax[3] = 0;
	$tax[4] = 0;
	$docnr = '';
	$docdate = '';
	$totperson = 0;
	$totincome = 0;
	$tottax = 0;
	$surcharge = 0;
	$total = 0;

	//var_dump($_POST);
	
?>	

<link rel="stylesheet" type="text/css" media="screen" href="<?=ROOT?>css/formTable.css?<?=time()?>">

<div class="A4form" style="width:900px;height:1260px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/pnd1_monthly_th_check.png?<?=time()?>) no-repeat; background-size:cover">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<? if(isset($_POST)){ ?>
		<button type="button" class="print btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary btn-sm" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
	
	<? if($address){ ?>
	<div style="position:relative; margin-top:2px">
	<form id="tax_return">
		
	<div class="field" style="top:32mm;left:57.2mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[0]?>" /></div>
	
	<div class="field" style="top:32mm;left:64mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[1]?>" /></div>
	<div class="field" style="top:32mm;left:68.4mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[2]?>" /></div>
	<div class="field" style="top:32mm;left:73mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[3]?>" /></div>
	<div class="field" style="top:32mm;left:77.6mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[4]?>" /></div>
	
	<div class="field" style="top:32mm;left:84.4mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[5]?>" /></div>
	<div class="field" style="top:32mm;left:88.6mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[6]?>" /></div>
	<div class="field" style="top:32mm;left:93.6mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[7]?>" /></div>
	<div class="field" style="top:32mm;left:97.8mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[8]?>" /></div>
	<div class="field" style="top:32mm;left:102.4mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[9]?>" /></div>
	
	<div class="field" style="top:32mm;left:109.2mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[10]?>" /></div>
	<div class="field" style="top:32mm;left:113.8mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[11]?>" /></div>
	
	<div class="field" style="top:32mm;left:120.6mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$pin[12]?>" /></div>

	<div class="field13" style="top:39.8mm;left:203mm"><input style="width:70px" type="text" value="<?=$_SESSION['rego']['year_th']?>" /></div>

	<div class="field" style="top:44mm;left:103.2mm"><input maxlength="1" class="sel numeric tac" name="branch[]" type="text" value="<?=$branch[0]?>" /></div>
	<div class="field" style="top:44mm;left:107.8mm"><input maxlength="1" class="sel numeric tac" name="branch[]" type="text" value="<?=$branch[1]?>" /></div>
	<div class="field" style="top:44mm;left:112.2mm"><input maxlength="1" class="sel numeric tac" name="branch[]" type="text" value="<?=$branch[2]?>" /></div>
	<div class="field" style="top:44mm;left:116.8mm"><input maxlength="1" class="sel numeric tac" name="branch[]" type="text" value="<?=$branch[3]?>" /></div>
	<div class="field" style="top:44mm;left:121mm"><input maxlength="1" class="sel numeric tac" name="branch[]" type="text" value="<?=$branch[4]?>" /></div>

	<div class="field" style="top:50.6mm;left:8mm"><input style="width:442px" type="text" value="<?=$compinfo[$lang.'_compname']?>" /></div>
	
	<div class="field12" style="top:57mm;left:28mm"><input style="width:83px" type="text" value="<?=$address["building"]?>" /></div>
	<div class="field12" style="top:57mm;left:68mm"><input maxlength="5" style="width:32px" type="text" value="<?=$address["room"]?>" /></div>
	<div class="field12" style="top:57mm;left:82mm"><input maxlength="3" style="width:20px" type="text" value="<?=$address["floor"]?>" /></div>
	<div class="field12" style="top:57mm;left:99mm"><input style="width:100px" type="text" value="<?=$address["village"]?>" /></div>

	<div class="field12" style="top:63mm;left:16.6mm"><input style="width:118px" type="text" value="<?=$address["number"]?>" /></div>
	<div class="field12" style="top:63mm;left:54.4mm"><input maxlength="3" style="width:22px" type="text" value="<?=$address["moo"]?>" /></div>
	<div class="field12" style="top:63mm;left:76.6mm"><input style="width:175px" type="text" value="<?=$address["lane"]?>" /></div>

	<div class="field12" style="top:69.2mm;left:15.6mm"><input style="width:170px" type="text" value="<?=$address["road"]?>" /></div>
	<div class="field12" style="top:69.2mm;left:79mm;"><input style="width:170px" type="text" value="<?=$address["subdistrict"]?>" /></div>

	<div class="field12" style="top:75.6mm;left:24.4mm"><input style="width:162px" type="text" value="<?=$address["district"]?>" /></div>
	<div class="field12" style="top:75.6mm;left:79mm"><input style="width:170px" type="text" value="<?=$address["province"]?>" /></div>

	<div class="field" style="top:82.4mm;left:29mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$post[0]?>" /></div>
	<div class="field" style="top:82.4mm;left:33.4mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$post[1]?>" /></div>
	<div class="field" style="top:82.4mm;left:37.6mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$post[2]?>" /></div>
	<div class="field" style="top:82.4mm;left:42mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$post[3]?>" /></div>
	<div class="field" style="top:82.4mm;left:46.2mm"><input maxlength="1" class="sel numeric tac" type="text" value="<?=$post[4]?>" /></div>

	<div class="field13" style="top:82.6mm;left:58.4mm"><input style="width:213px" type="text" value="<?=$compinfo['comp_phone']?>" /></div>

	<div class="field13" style="top:98.8mm;left:26.4mm">
		<input name="rfill" type="hidden" value="0" />
		<label><input <? if($rfill==1){echo 'checked';} ?> type="radio" name="rfill" value="1" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:98.8mm;left:64.8mm">
		<label><input <? if($rfill==2){echo 'checked';} ?> type="radio" name="rfill" value="2" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:99.8mm;left:103.8mm;width:55px"><input class="sel numeric tac" maxlength="3" style="width:26px" name="fill" type="text" value="<?=$fill?>" /></div>
	
	<div class="field13" style="top:117mm;left:98.2mm">
		<input name="pag" type="hidden" value="0" />
		<label><input <? if($pag==1){echo 'checked';} ?> type="radio" name="pag" value="1" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:125mm;left:98.2mm">
		<label><input <? if($pag==2){echo 'checked';} ?> type="radio" name="pag" value="2" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:117.6mm;left:195mm"><input style="width:40px" name="pnd1_att_pages" type="text" value="<?=$pages?>" /></div>
	<div class="field13" style="top:125.4mm;left:195mm"><input style="width:40px" name="pnd1_disk_pages" type="text" value="<?=$pnd1_disk_pages?>" /></div>
	<div class="field13" style="top:131.2mm;left:171.4mm"><input style="width:150px" name="pnd1_controlnr" type="text" value="<?=$pnd1_controlnr?>" /></div>

	<div class="field" style="top:49mm;left:127.6mm">
		<input name="month" type="hidden" value="0" />
		<label><input <? if($month==1){echo 'checked';} ?> type="radio" name="month" value="1" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:49mm;left:150.2mm">
		<label><input <? if($month==4){echo 'checked';} ?> type="radio" name="month" value="4" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:49mm;left:173.2mm">
		<label><input <? if($month==7){echo 'checked';} ?> type="radio" name="month" value="7" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:49mm;left:195.2mm">
		<label><input <? if($month==10){echo 'checked';} ?> type="radio" name="month" value="10" class="radiobox style-0"><span></span></label>
	</div>
	
	<div class="field13" style="top:58.6mm;left:127.6mm">
		<label><input <? if($month==2){echo 'checked';} ?> type="radio" name="month" value="2" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:58.6mm;left:150.2mm">
		<label><input <? if($month==5){echo 'checked';} ?> type="radio" name="month" value="5" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:58.6mm;left:173.2mm">
		<label><input <? if($month==8){echo 'checked';} ?> type="radio" name="month" value="8" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:58.6mm;left:195.2mm">
		<label><input <? if($month==11){echo 'checked';} ?> type="radio" name="month" value="11" class="radiobox style-0"><span></span></label>
	</div>
	
	<div class="field13" style="top:68mm;left:127.6mm">
		<label><input <? if($month==3){echo 'checked';} ?> type="radio" name="month" value="3" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:68mm;left:150.2mm">
		<label><input <? if($month==6){echo 'checked';} ?> type="radio" name="month" value="6" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:68mm;left:173.2mm">
		<label><input <? if($month==9){echo 'checked';} ?> type="radio" name="month" value="9" class="radiobox style-0"><span></span></label>
	</div>
	<div class="field13" style="top:68mm;left:195.2mm">
		<label><input <? if($month==12){echo 'checked';} ?> type="radio" name="month" value="12" class="radiobox style-0"><span></span></label>
	</div>
	
	<div class="field13n" style="top:159.2mm;left:120mm">
		<input class="calc1 sel numeric person" name="person[]" type="text" value="<?=$person[0]?>">
	</div>
	<div class="field13n" style="top:180mm;left:120mm">
		<input class="calc1 sel numeric person" name="person[]" type="text" value="<?=$person[1]?>">
	</div>
	<div class="field13n" style="top:194.4mm;left:120mm">
		<input class="calc1 sel numeric person" name="person[]" type="text" value="<?=$person[2]?>">
	</div>
	<div class="field13n" style="top:201.2mm;left:120mm">
		<input class="calc1 sel numeric person" name="person[]" type="text" value="<?=$person[3]?>">
	</div>
	<div class="field13n" style="top:208.4mm;left:120mm">
		<input class="calc1 sel numeric person" name="person[]" type="text" value="<?=$person[4]?>">
	</div>
	<div class="field13n" style="top:215.6mm;left:120mm">
		<input readonly tabindex="-1" class="calc1" name="totperson" id="totperson" type="text" value="">
	</div>

	<div class="field13n" style="top:159.2mm;left:144.4mm">
		<input class="calc2 sel numeric income" name="income[]" type="text" value="<?=$income[0]?>">
	</div>
	<div class="field13n" style="top:180mm;left:144.4mm">
		<input class="calc2 sel numeric income" name="income[]" type="text" value="<?=$income[1]?>">
	</div>
	<div class="field13n" style="top:194.4mm;left:144.4mm">
		<input class="calc2 sel numeric income" name="income[]" type="text" value="<?=$income[2]?>">
	</div>
	<div class="field13n" style="top:201.2mm;left:144.4mm">
		<input class="calc2 sel numeric income" name="income[]" type="text" value="<?=$income[3]?>">
	</div>
	<div class="field13n" style="top:208.4mm;left:144.4mm">
		<input class="calc2 sel numeric income" name="income[]" type="text" value="<?=$income[4]?>">
	</div>
	<div class="field13n" style="top:215.6mm;left:144.4mm">
		<input readonly tabindex="-1" class="calc2" name="totincome" id="totincome" type="text" value="">
	</div>
	
	<div class="field12" style="top:180.4mm;left:42mm"><input style="width:85px" name="docnr" type="text" value="<?=$docnr?>"></div>
	<div class="field12" style="top:180.4mm;left:80.4mm"><input style="width:115px" name="docdate" type="text" value="<?=$docdate?>"></div>
	
	<div class="field13n" style="top:159.2mm;left:179.2mm">
		<input class="calc2 sel numeric tax" name="tax[]" type="text" value="<?=$tax[0]?>">
	</div>
	<div class="field13n" style="top:180mm;left:179.2mm">
		<input class="calc2 sel numeric tax" name="tax[]" type="text" value="<?=$tax[1]?>">
	</div>
	<div class="field13n" style="top:194mm;left:179.2mm">
		<input class="calc2 sel numeric tax" name="tax[]" type="text" value="<?=$tax[2]?>">
	</div>
	<div class="field13n" style="top:201.2mm;left:179.2mm">
		<input class="calc2 sel numeric tax" name="tax[]" type="text" value="<?=$tax[3]?>">
	</div>
	<div class="field13n" style="top:208.4mm;left:179.2mm">
		<input class="calc2 sel numeric tax" name="tax[]" type="text" value="<?=$tax[4]?>">
	</div>
	<div class="field13n" style="top:215.6mm;left:179.2mm">
		<input readonly tabindex="-1" class="calc2" name="tottax" id="tottax" type="text" value="">
	</div>

	<div class="field13n" style="top:222.8mm;left:179.2mm">
		<input class="calc2 sel numeric" name="surcharge" id="sur" type="text" value="<?=$surcharge?>">
	</div>
	<div class="field13n" style="top:230mm;left:179.2mm">
		<input style="font-weight:600" readonly tabindex="-1" class="calc2" name="total" id="total" type="text" value="">
	</div>
	
	<? if($compinfo['digi_signature'] == 1 && !empty($compinfo['dig_signature'])){ ?>
	<div class="field" style="top:258.2mm;left:86mm"><img width="200px" src="<?=ROOT.$compinfo['dig_signature']?>?<?=time()?>" /></div>
	<? }if($compinfo['digi_stamp'] == 1 && !empty($compinfo['dig_stamp'])){ ?>
	<div class="field" style="top:259mm;left:170mm"><img width="90px" src="<?=ROOT.$compinfo['dig_stamp']?>?<?=time()?>" /></div>
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













