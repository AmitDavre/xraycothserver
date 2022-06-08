<?php
	
	$year['en'] = date('Y');
	$year['th'] = date('Y')+543;
	$data = getPND1attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	
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
		
 <div class="A4form" style="width:950px">
 
 	<div style="position:absolute; top:20px; right:30px;">
		<? if($data['d']){ ?>
		<button data-type="" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>

			<form id="pnd1_attach">
			
			<div style="font-size:16px; margin-bottom:5px"><b>Attachement of P.N.D.1</b></div>
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
			
			<table class="formTable toptable2" border="0">
				<tbody>
				<tr>
					<td colspan="2"><b>Type of income</b> - Please in this attachment based on each type of income and tick only one <i class="fa">&#xf046;</i></td>
				</tr>
				<tr>
					<td style="padding:0; width:46%">
						<table border="0">
							<tr><td><label><input <? if($_POST['rr']==1){echo 'checked';} ?> type="radio" name="rr" value="1" class="checkbox"><span><b>1.</b> Income under Section <b>40(1)</b> : salaries, wages, etc.</span></label><br /><p>(In general cases)</p></td></tr>
							<tr><td><label><input <? if($_POST['rr']==2){echo 'checked';} ?> type="radio" name="rr" value="2" class="checkbox"><span><b>2.</b> Income under Section <b>40(1)</b>: salaries, wages, etc.</span></label><br /><p>(in case where	the Revenue Department has given approval to apply <b>3% </b>withholding tax)</p></td></tr>
						</table>
					</td>
					<td style="padding:0 0 5px 0; width:54%">
						<table border="0">
							<tr><td><label><input <? if($_POST['rr']==3){echo 'checked';} ?> type="radio" name="rr" value="3" class="checkbox"><span><b>3.</b> Income under Section <b>40(1)(2)</b></span></label><br /><p>(In the case of single payment made by employer by reason of termination of employment)</p></td></tr>
							<tr><td><label><input <? if($_POST['rr']==4){echo 'checked';} ?> type="radio" name="rr" value="4" class="checkbox"><span><b>4.</b> Income under Section <b>40(2)</b> <i>where recipient is a resident of Thailand</i></span></label></td></tr>
							<tr><td><label><input <? if($_POST['rr']==5){echo 'checked';} ?> type="radio" name="rr" value="5" class="checkbox"><span><b>5.</b> Income under Section <b>40(2)</b> <i>where recipient is a non-resident of Thailand</i></span></label></td></tr>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable taxtable" border="1">
				<thead>
				<tr>
					<th rowspan="2" style="vertical-align:middle;">No.</th>
					<th class="tal" rowspan="2">Taxpayer ID number</th>
					<th class="tal" rowspan="2" style="width:50%">Name of recipient of income</th>
					<th colspan="2" style="vertical-align:middle;">Detail of payment of assessable income</th>
					<th rowspan="2" style="vertical-align:middle;">Amount of tax<br />withheld and<br />remitted</th>
					<th rowspan="2" style="vertical-align:middle;">*<br />Con.</th>
				</tr>
				<tr>
					<th>Payment date</th>
					<th>Amount paid</th>
				</tr>
				</thead>
				<tbody>
				<?php if($data['d']){ foreach($data['d'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left; font-weight:600"><?=$v['tax_id']?></td>
					<td class="tal"><?=$v['emp_id']?> - <?=$title[$v['title']].' '.$v['en_name']?></td>
					<td style="text-align:right"><?=$_SESSION['rego']['paydate']?></td>
					<td style="text-align:right"><?=$v['grossincome']?></td>
					<td style="text-align:right"><?=$v['tax']?></td>
					<td><i>(<?=$v['type']?>)</i></td>
				</tr>
				<?php } } ?>
				
				<?php if($data['d2']){ ?>
				<tr>
					<th colspan="7" style="text-align:center;font-weight:bold;">40(2) separate attachment</th>
				</tr>

				<?	foreach($data['d2'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left; font-weight:600"><?=$v['tax_id']?></td>
					<td class="tal"><?=$v['emp_id']?> - <?=$title[$v['title']].' '.$v['en_name']?></td>
					<td style="text-align:right"><?=$_SESSION['rego']['paydate']?></td>
					<td style="text-align:right"><?=$v['grossincome']?></td>
					<td style="text-align:right"><?=$v['tax']?></td>
					<td><i>(<?=$v['type']?>)</i></td>
				</tr>
				<?php } } ?>
				<?php
					$income = str_replace(',', '', $data['tot_income']) + str_replace(',', '', $data['tot_income2']); 
					$tax = str_replace(',', '', $data['tot_tax']) + str_replace(',', '', $data['tot_tax2']); 
					$income = number_format($income,2);
					$tax = number_format($tax,2);
				?>
				<tr>
					<th colspan="4" style="text-align:right"><b>Total amount of income and withholding tax remittance</b><br><span style="font-size:10px">(to be included with other attachement of P.N.D.1 (if any))</span></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$income?></b></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$tax?></b></th>
					<th></th>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable" border="1" style="border-top:0;">
				<tr>
					<td style="line-height:160%; width:55%; position:relative">
						(Please fill in items in order for every attachment according to type of income)<br />
						<b>Note *</b> Please indicate conditions of withholding tax as follows :
						<ul>
							<li>Deducted at source, fill (1)</li>
							<li>Paid tax for recipient every time, fill (2)</li>
							<li>Paid tax for recipient one time, fill (3)</li>
						</ul>
						<!--<img style="width:70px; position:absolute; bottom:15px; right:15px" src="../<?php //=$cid?>/stamp70.png" />-->
					</td>
					<td style="line-height:190%; padding-bottom:10px; width:45%">
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
				var data = $("#pnd1_attach").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pnd1_monthly_attach_en.php?lng='+data, '_blank')
			});
			
		})
	
	</script>













