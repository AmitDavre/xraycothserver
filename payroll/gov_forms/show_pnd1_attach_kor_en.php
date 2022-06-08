<?php
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$sso_codes = unserialize($edata['sso_codes']);
	$branch = sprintf("%05d",$edata['revenu_branch']);
	$per_id = $edata['tax_id'];
	$tax_id = '';//$edata['tax_id'];
	
	$data = getPND1attachYear($_SESSION['rego']['gov_entity']);
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
		<? if($data['rows']){ ?>
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
 <!--<div style="width:210mm;margin:0 auto;margin-top:10px; background:#fff; padding:30px 30px 100px; height:297mm; box-shadow:0 0 10px rgba(0,0,0,0.5);">-->
			<form id="pnd1_attach">
			
			<div style="font-size:16px; margin-bottom:5px"><b>Attachement of P.N.D.1 Kor Year <?=$_SESSION['rego']['year_en']?></b></div>
			<table class="formTable toptable" border="0" style="border-bottom:0;">
				<tbody>
				<tr>
					<td>
						 Taxpayer identification number : <b><?=$tax_id?></b><br />
						<span style="font-size:11px">(Withholding tax agent)</span></td>
					<td>
						Personal identification number : <b><?=$per_id?></b><br />
						<span style="font-size:11px">(Withholding tax agent in case of individual)</span></td>
					<td style="text-align:right">Branch : <b><?=$branch?></b></td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable2" border="0">
				<tbody>
				<tr>
					<td colspan="2"><b>Type of income</b> - Please in this attachment based on each type of income and tick only one &nbsp;<i class="fa fa-lg">&#xf046;</i></td>
				</tr>
				<tr>
					<td style="padding:0; width:46%">
						<table border="0">
							<tr><td><label><input checked type="radio" name="rr" value="1" class="checkbox"><span><b>1.</b> Income under Section <b>40(1)</b> : salaries, wages, etc.</span></label><br /><p>(In general cases)</p></td></tr>
							<tr><td><label><input type="radio" name="rr" value="2" class="checkbox"><span><b>2.</b> Income under Section <b>40(1)</b>: salaries, wages, etc.</span></label><br /><p>(in case where	the Revenue Department has given approval to apply <b>3% </b>withholding tax)</p></td></tr>
						</table>
					</td>
					<td style="padding:0 0 5px 0; width:54%">
						<table border="0">
							<tr><td><label><input type="radio" name="rr" value="3" class="checkbox"><span><b>3.</b> Income under Section <b>40(1)(2)</b></span></label><br /><p>(In the case of single payment made by employer by reason of termination of employment)</p></td></tr>
							<tr><td><label><input type="radio" name="rr" value="4" class="checkbox"><span><b>4.</b> Income under Section <b>40(2)</b> <i>where recipient is a resident of Thailand</i></span></label></td></tr>
							<tr><td><label><input type="radio" name="rr" value="5" class="checkbox"><span><b>5.</b> Income under Section <b>40(2)</b> <i>where recipient is a non-resident of Thailand</i></span></label></td></tr>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable taxtable" border="1">
				<thead>
				<tr>
					<th style="vertical-align:middle;">No.</th>
					<th class="tal">Taxpayer ID number</th>
					<th class="tal" style="width:80%">Name of recipient of income</th>
					<th style="min-width:110px">Amount paid</th>
					<th style="vertical-align:middle; min-width:110px">Amount of tax<br />withheld and<br />remitted</th>
					<th style="vertical-align:middle;">*<br />Con.</th>
				</tr>
				</thead>
				<tbody>
				<? if($data['rows']){ foreach($data['rows'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left">
						<span style="font-weight:600;"><?=$v['tax_id']?></span>
					</td>
					<td style="text-align:left">
						<?=$v['emp_id'].' - '.$v['title'].' '.$v['name']?><br>
						<?=$v['address']?>
					</td>
					<td style="text-align:right"><?=$v['grossincome']?></td>
					<td style="text-align:right"><?=$v['tax']?></td>
					<td><i>(1)</i></td>
				</tr>
				<? } } ?>
				<tr>
					<th colspan="3" style="text-align:right"><b>Total amount of income and withholding tax remittance</b><br><span style="font-size:10px">(to be included with other attachement of P.N.D.1 (if any))</span></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$data['tot_income']?></b></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$data['tot_tax']?></b></th>
					<th></th>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable" border="1" style="border-top:0;">
				<tr>
					<td style="line-height:160%; width:60%; position:relative">
						(Please fill in items in order for every attachment according to type of income)<br />
						<b>Note *</b> Please indicate conditions of withholding tax as follows :
						<ul>
							<li>Deducted at source, fill (1)</li>
							<li>Paid tax for recipient every time, fill (2)</li>
							<li>Paid tax for recipient one time, fill (3)</li>
						</ul>
						<!--<img style="width:70px; position:absolute; bottom:15px; right:15px" src="../<? //=$cid?>/stamp70.png" />-->
					</td>
					<td style="line-height:190%; padding-bottom:10px; width:40%">
						<table class="blank" border="0" width="100%"><tr>
                     <th><?=$lng['Name']?> : </th><td><input name="form_name" class="formName" placeholder="________________________________________________________" style="width:100%;" type="text" value="<?=$name_position['name']?>"></td></tr></table>
                  <table class="blank" border="0" width="100%"><tr>      
                     <th><?=$lng['Position']?> : </th><td><input name="form_position" class="formPosition" placeholder="________________________________________________________" style="width:100%;" type="text" value="<?=$name_position['position']?>"></td></tr></table>
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
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#pnd1_attach").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd1_kor_attach_en.php?'+data, '_blank')
			});
			
		})
	
	</script>













