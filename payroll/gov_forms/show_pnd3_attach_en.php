<?php
	
	$year['en'] = date('Y');
	$year['th'] = date('Y')+543;
	$data = getPND3attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	
	if($res = $dbc->query("SELECT ".$lang."_addr_detail, ".$lang."_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$xdata = $res->fetch_assoc();
		$address = unserialize($xdata[$lang.'_addr_detail']);
	}else{
		//echo mysqli_error($dbc);
	}

	$branch = sprintf("%05d",$xdata['revenu_branch']);

	$tax_id = $xdata['tax_id'];
	//$per_id = $sys_settings['personal_idnr'];
	$title[''] = '? ?';
	
	if(!$_POST){$_POST['rr'] = 1;}
	
	//var_dump($data);
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
		
 <div class="A4form" style="width:1000px">
 
 	<div style="position:absolute; top:20px; right:30px;">
		<? if($data['d']){ ?>
		<button data-type="" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>

			<form id="pnd3_attach">
			
			<div style="font-size:16px; margin-bottom:5px"><b>Attachement of P.N.D.3</b></div>
			<table class="formTable toptable" border="0" style="border-bottom:0;">
				<tbody>
				<tr>
					<td>
						 Taxpayer identification number : <b><?=$tax_id?></b><br />
						<span style="font-size:11px">(Withholding tax agent)</span></td>
					<td>
						Personal identification number : <b><? //=$per_id?></b><br />
						<span style="font-size:11px">(Withholding tax agent in case of individual)</span></td>
					<td style="text-align:right">Branch : <b><?=$branch?></b></td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable taxtable" border="1">
				<thead>
					<tr>
						<th rowspan="2" style="vertical-align:middle;">No</th>
						<th rowspan="2" style="vertical-align:middle; width:25%; text-align:left">Tax ID</th>
						<th rowspan="2" style="vertical-align:middle; width:25%; text-align:left">Name recipient</th>
						<th colspan="4" style="white-space:normal; line-height:100%; vertical-align:middle">Detail of payment of assessable income</th>
						<th colspan="2" style="white-space:normal; line-height:100%">Total of Tax withheld and remitted</th>
					</tr>
					<tr>
						<th style="white-space:normal; line-height:100%">Payment date</th>
						<th style="white-space:normal; line-height:100%">Type of income</th>
						<th style="white-space:normal; line-height:100%">Tax rate</th>
						<th style="white-space:normal; line-height:100%">Amount paid</th>
						<th style="white-space:normal; line-height:100%">Amount of Tax</th>
						<th style="white-space:normal; line-height:100%">Condition</tdh>
					</tr>
				</thead>
				<tbody>
				<?php if($data['d']){ foreach($data['d'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left; font-weight:600"><?=$v['tax_id']?></td>
					<td class="tal"><?=$title[$v['title']].' '.$v['en_name']?></td>
					<td style="text-align:right"><?=$_SESSION['rego']['paydate']?></td>
					<td style="text-align:center">Wages</td>
					<td style="text-align:right"><?=$pr_settings['wht']?> %</td>
					<td style="text-align:right"><?=$v['grossincome']?></td>
					<td style="text-align:right"><?=$v['tax']?></td>
					<td><i>(<?=$v['type']?>)</i></td>
				</tr>
				<?php } } ?>
				<tr>
					<th colspan="5" style="text-align:right;white-space:normal"><b>(Fill in items in order for every attachment)<br>Total amount of income and withholding tax (to be included with other attachment(s) of P.N.D.3 (if any)</span></th>
					<th></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$data['tot_income']?></b></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$data['tot_tax']?></b></th>
					<th></th>
				</tr>
				</tbody>
      </table>
			
			<table class="formTable toptable" border="1" style="border-top:0;">
				<tr>
					<td style="line-height:160%; width:65%; position:relative">
						<b>(1)</b> Please specify the payment, e.g. building rent, accounting fee, legal fee, physician professional fee, construction, prize received from contest/compettition/game show), movie actor, singer, musician, service, advertisement, etc.<br>
						<b>(2)</b> Please indicate conditions of withholding tax as follows:
						<ul>
							<li>Deducted at source, fill (1)</li>
							<li>Paid tax for recipient every time, fill (2)</li>
							<li>Paid tax for recipient one time, fill (3)</li>
						</ul>
						<!--<img style="width:70px; position:absolute; bottom:15px; right:15px" src="../<?php //=$cid?>/stamp70.png" />-->
					</td>
					<td style="line-height:190%; padding-bottom:10px; width:35%">
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
						<!--<b>Signature </b> :&nbsp;&nbsp; <img style="width:200px" src="../<?php //=$cid?>/dig_signature.png" />-->
					</td>
				</tr>
			</table>
			</form>

 </div>

	<script type="text/javascript">
		
		$(function() {
			$(document).on("click", ".print", function(e) {
				var data = $("#pnd3_attach").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd3_monthly_attach_en.php?lng='+data, '_blank')
			});
			
		})
	
	</script>













