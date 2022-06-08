<?php	

	include('gov_sso_form.php');
	//var_dump($rego_settings);
?>	
	<style>
		input[type=text] {
			padding:0;
			margin:0;
			border:0 !important;
			background:transparent;
			width:12px;
			/*-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;*/
		}
		input[type=text]:hover {
			background:#ffa;
		}
		input[type=text].ro {
			background:transparent;
			font-weight:bold;
		}
		input[type=text].calc1 {
			text-align:right;
			padding:0 5px;
			width:70px;
		}
		input[type=text].calc2 {
			text-align:right;
			padding:0 5px;
			width:120px;
		}
		div.field {
			position:absolute;
			min-height:12px;
			border:0px solid red;
			font-size:15px;
			line-height:100%;
			white-space:nowrap;
			color:#069;
		}
		div.field13, div.field12n, div.field13c, div.field11, div.field12 {
			position:absolute;
			min-height:12px;
			border:0px solid red;
			font-size:14px;
			line-height:100%;
			white-space:nowrap;
			color:#069;
		}
		div.field12n {
			width:100px;
			text-align:right;
			font-size:12px;
		}
		div.field13c {
			width:192px;
			text-align:center;
		}
		div.field11 {
			min-width:20px;
			font-size:11px;
		}
		div.field12 {
			min-width:20px;
			font-size:12px;
		}
		.center {
			text-align:center;
		}
		label input[type="radio"].radiobox + span:before,
		label input[type="checkbox"].checkbox + span:before {
		  font-size: 11px;
		  height: 11px;
		  line-height: 12px;
		  min-width: 12px;
		  margin-right: 5px;
		  margin-top: 1px;
		  padding-top:1px; 
		}
	</style>
		
<div class="A4form" style="width:1000px; height:730px; padding:30px 30px 100px; background:#fff url(<?=ROOT?>images/forms/sso_en.png?<?=time()?>) no-repeat; background-size:100%">
	<div style="position:absolute; top:20px; left:20px; width:720px"><span id="message"></span></div>
	<div style="position:absolute; top:20px; right:30px;">
		<? if($income[0] > 0 || $social[0] > 0 || $total[0] > 0){ ?>
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
	
	<div style="position:relative">
	<form id="sso_form">	
	
		<div class="field13" style="top:32.2mm;left:171.2mm"><?=$sso[0]?></div>
		<div class="field13" style="top:32.2mm;left:175.6mm"><?=$sso[1]?></div>
		<div class="field13" style="top:32.2mm;left:184.2mm"><?=$sso[2]?></div>
		<div class="field13" style="top:32.2mm;left:188.8mm"><?=$sso[3]?></div>
		<div class="field13" style="top:32.2mm;left:193.4mm"><?=$sso[4]?></div>
		<div class="field13" style="top:32.2mm;left:197.8mm"><?=$sso[5]?></div>
		<div class="field13" style="top:32.2mm;left:202mm"><?=$sso[6]?></div>
		<div class="field13" style="top:32.2mm;left:206.8mm"><?=$sso[7]?></div>
		<div class="field13" style="top:32.2mm;left:211.2mm"><?=$sso[8]?></div>
		<div class="field13" style="top:32.2mm;left:219.4mm"><?=$sso[9]?></div>
		
		<div class="field13" style="top:39.2mm;left:171.2mm"><?=$branch[0]?></div>
		<div class="field13" style="top:39.2mm;left:175.6mm"><?=$branch[1]?></div>
		<div class="field13" style="top:39.2mm;left:180.2mm"><?=$branch[2]?></div>
		<div class="field13" style="top:39.2mm;left:184.8mm"><?=$branch[3]?></div>
		<div class="field13" style="top:39.2mm;left:189.4mm"><?=$branch[4]?></div>
		<div class="field13" style="top:39.2mm;left:193.8mm"><?=$branch[5]?></div>

		<div class="field13" style="top:26.2mm;left:43mm"><?=$edata["en_compname"]?></div>
		
		<div class="field13" style="top:32.2mm;left:49mm"></div>
			
		<div class="field12" style="top:40.4mm;left:49mm"><?=$sso_codes[$_SESSION['rego']['gov_branch']]['line1_en']?></div>
		<div class="field12" style="top:46.6mm;left:15mm"><?=$sso_codes[$_SESSION['rego']['gov_branch']]['line2_en']?></div>
		
		<div class="field12" style="top:53.8mm;left:35.6mm"><?=$sso_codes[$_SESSION['rego']['gov_branch']]['postal_en']?></div>

		<div class="field12" style="top:53.8mm;left:61mm"><?=$edata["comp_phone"]?></div>
		<div class="field12" style="top:53.8mm;left:93.6mm"><?=$edata["comp_fax"]?></div>
		
		<div class="field12" style="top:46.8mm;left:171mm"><?=$sso_rate?></div>
			
		<div class="field12" style="top:60.6mm;right:137mm"><?=$months[(int)$_SESSION['rego']['gov_month']]?> <?=$_SESSION['rego']['year_th']?></div>

		<div class="field12n" style="top:78.4mm;left:76mm"><?=$income[0]?></div>
		<div class="field12n" style="top:85.0mm;left:76mm"><?=$social?></div>
		<div class="field12n" style="top:91.2mm;left:76mm"><?=$social_com?></div>
		<div class="field12n" style="top:97.6mm;left:76mm"><?=$total?></div>
		
		<div class="field12n" style="top:78.4mm;left:84.6mm"><?=$income[1]?></div>
		<div class="field12n" style="top:85.0mm;left:84.6mm">00</div>
		<div class="field12n" style="top:91.2mm;left:84.6mm">00</div>
		<div class="field12n" style="top:97.6mm;left:84.6mm">00</div>
		
		<div class="field12" style="top:104mm;left:21mm; text-transform:capitalize"><?=$chars?> Baht</div>
		<div class="field12n" style="top:110mm;right:146.6mm"><?=$emps?></div>
		
		<div class="field11" style="top:128.2mm;left:13.9mm">
			<label><input checked type="radio" name="rr" value="1" class="checkbox notxt"><span></span></label>
		</div>
		<div class="field12" style="top:128.6mm;left:85.2mm"><?=$pages?></div>
		
		<div class="field11" style="top:134.2mm;left:13.9mm">
			<label><input disabled type="radio" name="rr" value="2" class="checkbox notxt"><span></span></label>
		</div>

		<div class="field11" style="top:140mm;left:13.9mm">
			<label><input type="radio" name="rr" value="3" class="checkbox notxt"><span></span></label>
		</div>
		
		<div class="field11" style="top:146.0mm;left:13.9mm">
			<label><input type="radio" name="rr" value="4" class="checkbox notxt"><span></span></label>
		</div>
		<div class="field12" style="top:145.4mm;left:28mm">
			<input style="width:80px" name="other" type="text" value="" />
		</div>
		
		<div class="field12 center" style="top:153.4mm;left:64.4mm;width:250px"><input class="formName" name="form_name" style="width:210px;" type="text" value="<?=$name_position['name']?>"></div>
		<div class="field12" style="top:159.6mm;left:67mm"><input class="formPosition" name="form_position" style="width:200px" type="text" value="<?=$name_position['position']?>"></div>
		
		<? if($edata['digi_signature'] == 1 && !empty($edata['dig_signature'])){ ?>
		<div class="field" style="top:144mm;left:70mm"><img width="165px" src="<?=ROOT.$edata['dig_signature'].'?'.time()?>" /></div>
		<? }if($edata['digi_stamp'] == 1 && !empty($edata['dig_stamp'])){ ?>
		<div class="field" style="top:153mm;left:20mm"><img width="80px" src="<?=ROOT.$edata['dig_stamp'].'?'.time()?>" /></div>
		<? } ?>
	
		<div class="field12 formDay" style="top:166.4mm;left:71mm"><?=$_SESSION['rego']['formdate']['d']?></div>
		<div class="field12 formMonth" style="top:166.4mm;left:88mm"><?=$_SESSION['rego']['formdate']['m']?></div>
		<div class="field12 formYear" style="top:166.4mm;left:118.4mm"><?=$_SESSION['rego']['formdate']['eny']?></div>

	</form>
	</div>
	
</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#sso_form").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_sso_form_en.php?'+data, '_blank')
			});
			
			
		})
	
	</script>













