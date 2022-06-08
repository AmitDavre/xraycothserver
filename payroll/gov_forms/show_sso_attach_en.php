<?php
	//$year['en'] = date('Y');
	//$year['th'] = date('Y')+543;

	$branch = sprintf("%06d",$entityBranches[$_SESSION['rego']['gov_branch']]['code']);
	$data = getSSOattach($_SESSION['rego']['payroll_dbase'], $_SESSION['rego']['gov_month'], $lang, $pr_settings['sso_act_max']);
	//var_dump($data); exit;
	
	
?>	
<style>
	table.blank th {
		padding:0 8px 0 0 !important;
		width:1px !important;
		white-space:nowrap !important;
		font-weight:600;
	}
	table.blank td {
		padding:0 !important;
	}
</style>
<link rel="stylesheet" type="text/css" media="screen" href="../assets/css/formTable.css?<?=time()?>">
	
 <div class="A4form">
 
 	<div style="position:absolute; top:20px; right:30px;">
		<select id="actMax" style="display:inline-block; margin:0 8px 0 0">
			<option <? if($pr_settings['sso_act_max'] == 'max'){echo 'selected';}?> value="max">Max. <?=number_format($rego_settings['sso_max_wage'])?> THB</option>
			<option <? if($pr_settings['sso_act_max'] == 'act'){echo 'selected';}?> value="act">Actual amount</option>
		</select>
		<? if($data['d']){ ?>
		<button data-type="p" type="button" class="print btn btn-primary" style="margin-top:-3px !important"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-top:-3px !important; margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
 <!--<div style="width:210mm;margin:0 auto;margin-top:10px; background:#fff; padding:30px 30px 100px; height:297mm; box-shadow:0 0 10px rgba(0,0,0,0.5);">-->
			<form id="pnd1_attach">
			<div style="font-size:16px; margin-bottom:5px"><b>Attachment for Social Security form<!-- / รายละเอียดการนําส่งเงินสมทบ--></b></div>
			<table class="formTable toptable" border="0" style="border-bottom:0;">
				<tr>
					<td style="width:65%">
						Establishment name : <span style="font-size:14px"><b><?=$edata['en_compname']?></b></span><br />
						Tax ID number : <b><?=$edata['sso_account']?></b><br>
					</td>	
					<td>
						Wages for months : <b><?=$months[$_SESSION['rego']['gov_month']].'</b> B.E. <b>'.$_SESSION['rego']['year_en']?></b><br>
						Branch : <b><?=$branch?></b>
					</td>
				</tr>
			</table>

			<table class="formTable taxtable" border="1">
				<thead>
				<tr>
					<th style="vertical-align:middle; width:10px">No.</th>
					<th>National Identification Number</th>
					<th>Title-name-surname of the insured person</th>
					<th style="vertical-align:middle;">Wages actually paid</th>
					<th style="vertical-align:middle;">Contribution to the insured person<br />(Wages used in the calculation<br />are not less than1,650 baht and not<br /> more than 15,000 baht)</th>
				</tr>
				</thead>
				<tbody>
				<? if($data['d']){ foreach($data['d'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left"><span style="font-weight:600;"><?=$v['tax_id']?></span></td>
					<td style="text-align:left; width:50%"><?=$v['title'].' '.$v['name']?></td>
					<td style="text-align:right"><?=$v['basic_salary']?></td>
					<td style="text-align:right"><?=$v['sso']?></td>
				</tr>
				<? } } ?>
				<tr>
					<th colspan="3" style="text-align:right"><b>รวม</th>
					<th style="text-align:right; vertical-align:bottom; width:100px"><b><?=$data['tot_salary']?></b></th>
					<th style="text-align:right; vertical-align:bottom; width:100px"><b><?=$data['tot_social']?></b></th>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable" border="0" style="border-top:0;border-bottom:0;">
				<tr>
					<td style="line-height:160%; width:55%;font-size:11px; padding-bottom:0">
						<b>Statement</b><br>
						<ul style="list-style-type:decimal;padding:0 0 0 25px;margin:0">
							<li>In the case of new employees, submit the form Sor Por Sor 1-03 or Sor Por Sor 1-03 / 1 first by posting or the local social security office where the establishment is located.</li>
							<li>For an alien insured person, enter the social security card number in the national ID field.</li>
							<li>In Box 4, fill in the amount of actual wages paid in Box 5, the calculation of contributions for those receiving wages less than 1,650 baht, calculated from 1,650 baht, and those who received wages over 15,000 baht, calculate from 15,000 baht.</li>
							<li>Contribution to each person If there is a fraction of 50 satang or more, it shall be rounded to 1 baht, if less than 50 satang, it shall be discarded and the employer shall remit the employer contribution equal to the amount of the insured contribution. That has been rounded.</li>
							<li>For the benefit of using the insured's replacement benefit Every time the contribution is remitted. Please fill out all items completely, correctly and clearly with printer or calligraphy.</li>
							<li>For insured insurers who are not paid, fill in fields 1-5 as well.</li>
						</ul>
						<b>Warning</b>
						<ul style="list-style-type:decimal;padding:0 0 0 25px;margin:0">
							<li>Paying wages below the daily minimum wage as required by law is guilty under the Labor Protection Act.</li>
							<li>It is an offense to enter a false statement under the Criminal Code.</li>
						</ul>
					</td>
				</tr>
				
			</table>
			<table class="formTable toptable" border="0" style="border-top:0;">
				<tr>
					<td style="position:relative; padding:15px 0 0 25px">
						<!--<img style="width:200px; position:absolute; top:15px; left:30px" src="../<?php //=$cid?>/dig_signature.png" />
						<img style="width:70px; position:absolute; top:0px; right:15px" src="../<?php //=$cid?>/stamp70.png" />-->
					</td>
					<td style="line-height:190%; padding:0 10px 10px 10px; width:45%">
						<table class="blank" border="0" width="100%"><tr>
                     <th><?=$lng['Name']?> : </th><td><input name="form_name" class="formName" placeholder="__________________________________________________________________" style="width:100%;" type="text" value="<?=$name_position['name']?>"></td></tr></table>
                  <table class="blank" border="0" width="100%"><tr>      
                     <th><?=$lng['Position']?> : </th><td><input name="form_position" class="formPosition" placeholder="__________________________________________________________________" style="width:100%;" type="text" value="<?=$name_position['position']?>"></td></tr></table>
                  <table class="blank" border="0" width="100%">
							<tr>
								<th><?=$lng['Filling date']?> : </th>
								<td>
									<span class="formDay"><?=$_SESSION['rego']['formdate']['d']?> </span>
									<span class="formMonth"><?=$_SESSION['rego']['formdate']['m']?> </span>
									<span class="formYear"><?=$_SESSION['rego']['formdate'][$lang.'y']?></span>
								</td>
							</tr>
						</table>
						<!--<b>Signature </b> :&nbsp;&nbsp; <img style="width:200px" src="../<? //=$cid?>/dig_signature.png" />-->
					</td>
				</tr>
			</table>
			</form>

</div>

	<script type="text/javascript">
		
		$(function() {
			
			$(document).on("click", ".print", function(e) {
				var type = $(this).data('type');
				var lang = '<?=$lang;?>';
				window.open(ROOT+'payroll/print/print_sso_attach_'+lang+'.php?'+type, '_blank')
			});
			
			$(document).on("change", "#actMax", function(e) {
				var val = $(this).val();
				$.ajax({
					url: ROOT+"payroll/ajax/update_sso_act_max.php",
					data: {val: val},
					success: function(response) {
						//$('#dump').html(response)
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
						//alert(thrownError);
					}
				});
			});
			
		})
	
	</script>













