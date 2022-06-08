<?php
	//var_dump($comp_info);
	//$paydate = '12/12/2017';//getPaydate($_SESSION['xhr_comp_id']);
	//$branch = sprintf("%05d",$_SESSION['branch']);
	$branch = sprintf("%06d",$entityBranches[$_SESSION['rego']['gov_branch']]['code']);
	if($res = $dbc->query("SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['gov_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND pvf_employee > 0")){
		$nr=0; $tot_pvf = 0; $total_employee = 0; $total_employer = 0; $total_pvf = 0;
		while($row = $res->fetch_object()){
			$total_employee += $row->pvf_employee;
			$total_employer += $row->pvf_employer;
			$nr++;
		}
		$total_pvf = $total_employee + $total_employer;
	}
	//var_dump($total_pvf);
	
?>	
	<style>
		table.wrap {
			border-collapse:collapse;
			border:0px solid #ddd;
			width:100%;
			margin:6px 0 0px 0;
			table-layout:fixed;
		}

		table.topleft {
			border-collapse:collapse;
			border:0;
			width:100%;
		}
		table.topleft td {
			padding:5px 8px;
			vertical-align: baseline;
			white-space:nowrap;
			line-height:140%;
			text-align:left;
		}
		
		table.month_table {
			border-collapse:collapse;
			width:100%;
			table-layout:auto;
		}
		table.month_table td {
			padding:5px;
			vertical-align: baseline;
			white-space:nowrap;
			font-weight:normal;
			line-height:140%;
			text-align:left;
		}
		table.month_table td span {
			font-weight:normal;
		}
		table.subtable {
			width:100%; 
			table-layout:fixed;
		}
		table.subtable td {
			padding:5px;
		}
		
		table.taxtable {
			border-collapse:collapse;
			border:1px #000 solid;
			width:100%;
			line-height:140%;
		}
		table.taxtable th, table.taxtable td {
			border:0px solid #000;
			padding:10px 15px;
			line-height:140%;
			color:#111;
			font-family: inherit;
			vertical-align:middle;
			text-align:center;
			font-size:12px;
			white-space:nowrap;
			font-weight:normal;
		}
		table.taxtable tr {
			
		}
		table.taxtable th {
			background:#eee;
		}
		table.taxtable td.amt {
			vertical-align:baseline;
			text-align:right;
		}
		
		
		table input[type=text] {
			padding:0 4px;
			margin:0;
			border:0 !important;
			background:transparent;
			width:50px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			text-align:right;
		}
		input[type=text]:focus, input[type=text]:hover {
			background:#ffa;
		}
		
	</style>
	
		
 <div class="A4form">
 
 	<div style="position:absolute; top:20px; right:30px;">
		<? if($total_pvf){ ?>
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
 <!--<div style="width:210mm;margin:0 auto;margin-top:10px; background:#fff; padding:30px 30px 100px; height:297mm; box-shadow:0 0 10px rgba(0,0,0,0.5);">-->
			<form id="pvf_form">
			<div style="font-size:16px; margin-bottom:5px"><b>รายงานเงินนำส่งรายสมาชิก</b></div>
			<table class="taxtable" border="0" style="margin-bottom:10px; border:0">
				<tr>
					<td style="width:65%; text-align:left; line-height:200%">
						ชื่อสถานประกอบการ : <span style="font-size:14px"><b><?=$compinfo['th_compname']?></b></span><br />
						เลขประจำตัวผู้เสียภาษีอากร : <b><?=$compinfo['tax_id']?></b><br />
						ลำดับสาขาที่ : <b><?=$branch?></b>
					</td>	
					<td style="text-align:right; line-height:200%">
						เดือน : <b><span class="formMonth"><?=$_SESSION['rego']['formdate']['m'].'</span> <span class="formYear">'.$_SESSION['rego']['formdate'][$lang.'y']?></span></b> งวดที่ 1 <br>
						วันที่ : <b><span class="formDate"><?=$_SESSION['rego']['formdate'][$lang.'date']?></span></b>
					</td>
				</tr>
			</table>

			<table class="taxtable" border="0" style="table-layout:fixed; border:0">
				<tr>
					<th colspan="3" style="text-align:left"><b>จำนวนสมาชิก</b></th>
				</tr>
				<tr style="border-bottom:1px #ddd dotted;">
					<td style="text-align:right; padding-left:50px">จำนวนสมาชิกที่นำส่งเงิน</td>
					<td style="text-align:right"><input class="sel numeric" name="m" type="text" value="<?=$nr?>" /></td>
					<td style="text-align:left">ราย</td>
				</tr>
				<tr style="border-bottom:1px #ddd dotted;">
					<td style="text-align:right; padding-left:50px">จำนวนสมาชิกที่หยุดส่งเงิน</td>
					<td style="text-align:right"><input class="sel numeric" name="s" type="text" value="0" /></td>
					<td style="text-align:left">ราย</td>
				</tr>
				<tr style="border-bottom:1px #ddd dotted;">
					<td style="text-align:right; padding-left:50px">จำนวนสมาชิกรวม</td>
					<td style="text-align:right"><input class="sel numeric" name="t" type="text" value="<?=$nr?>" /></td>
					<td style="text-align:left">ราย</td>
				</tr>
				<tr>
					<td style="text-align:right; padding-left:50px">จำนวนสมาชิกใหม่</td>
					<td style="text-align:right"><input class="sel numeric" name="n" type="text" value="0" /></td>
					<td style="text-align:left">ราย</td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:left"><b>จำนวนเงินนำส่ง</b></th>
				</tr>
				<tr>
					<td style="text-align:center">เงินสะสม<br /><b><?=number_format($total_employee,2)?></b><input name="v1" type="hidden" value="<?=number_format($total_employee,2)?>" /></td>
					<td style="text-align:center">เงินสมทบ<br /><b><?=number_format($total_employer,2)?></b><input name="v2" type="hidden" value="<?=number_format($total_employer,2)?>" /></td>
					<td style="text-align:center">เงินลงทุนรวม<br /><b><?=number_format($total_pvf,2)?></b><input name="v3" type="hidden" value="<?=number_format($total_pvf,2)?>" /></td>
				</tr>
				<tr>
					<th colspan="3" style="text-align:left"><b>วันที่จ่ายเงินเข้ากองทุน</b> : <b><?=$_SESSION['rego']['formdate']['thdate']?></b></th>
				</tr>
				<tr>
					<td colspan="3" style="text-align:left; padding:5px">&nbsp;</td>
					
				</tr>
				<tr>
					<th style="text-align:left"><b>ผู้ประสานงาน (จัดทำ)</th>
					<th style="text-align:left"></th>
					<th style="text-align:left"><b>ผู้มีอำนาจลงนามของคณะกรรมการกองทุน</th>
				</tr>
				<tr>
					<td style="text-align:left; vertical-align:baseline"><?//=$_SESSION['xhr_user_name_'.$lang]?></td>
					<td></td>
					<td style="text-align:left"><br />........................................................................</td>
				</tr>
			</table>
			
			</form>

</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				var data = $("#pvf_form").serialize();
				data += '&' + $(this).data('type');
				window.open(ROOT+'payroll/print/print_pvf_form_th.php?'+data, '_blank')
			});
			
		})
	
	</script>













