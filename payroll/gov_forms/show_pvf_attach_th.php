<?php
	$year['en'] = date('Y');
	$year['th'] = date('Y')+543;
	
	$branch = sprintf("%06d",$entityBranches[$_SESSION['rego']['gov_branch']]['code']);
	$data = getPVFattach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['lang']);
	//var_dump($data); exit;
?>	
	<style>
		.jhkhkjh {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		table.xheader {
			border-collapse:collapse;
			width:100%;
			margin:10px 0 15px;
		}
		table.xheader td {
			padding:0;
			font-size:12px;
			vertical-align:center;
			white-space:nowrap;
			font-weight:normal;
		}
		
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
			border:1px solid #000;
			padding:5px 8px;
			line-height:140%;
			color:#111;
			font-family: inherit;
			vertical-align:middle;
			text-align:center;
			font-size:12px;
			white-space:nowrap;
			font-weight:normal;
		}
		table.taxtable td {
			text-align:left;
			line-height:140%;
			vertical-align:bottom;
		}
		table.taxtable th {
			background:#eee;
			text-align:left;
		}
		table.taxtable td.amt {
			vertical-align:baseline;
			text-align:right;
		}
		
		
		table input[type=text] {
			padding:0;
			margin:0;
			border:0;
			background:#eee;
			width:100%;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		input[type=text].ro {
			background:transparent;
			font-weight:bold;
		}
		input[type=text].calc1 {
			text-align:right;
			padding:0 5px;
			width:80px;
		}
		input[type=text].calc2 {
			text-align:right;
			padding:0 5px;
			width:100px;
		}
		
		.toptable {
			width:100%;
			border-collapse:collapse;
			table-layout:auto;
			border:1px solid #000;
		}
		.toptable td {
			text-align:left;
			padding:5px 8px;
			vertical-align:baseline;
		}
		
		.toptable2 {
			width:100%;
			border-collapse:collapse;
			table-layout:auto;
			border:1px solid #000;
			border-bottom:0;
		}
		.toptable2 td {
			text-align:left;
			padding:5px 8px;
			vertical-align:baseline;
		}
		.toptable2 td table {
			border-collapse:collapse;
			width:100%;
			font-size:12px;
			line-height:120%;
		}
		.toptable2 td table td p {
			font-size:11px;
			margin:0;
			padding:0 0 0 22px;
		}
		.toptable2 td table td i {
			font-size:11px;
			margin:0;
			font-style:normal;
		}
		.toptable2 td table td span {
			font-weight:normal;
		}
	</style>
	
		
 <div class="A4form">
 
 	<div style="position:absolute; top:20px; right:30px;">
		<? if($data['d']){ ?>
		<button data-type="p" type="button" class="print btn btn-primary"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print']?></button>
		<button data-type="a" type="button" class="print btn btn-primary" style="margin-left:3px"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print & Archive']?></button>
		<? } ?>
	</div>
 <!--<div style="width:210mm;margin:0 auto;margin-top:10px; background:#fff; padding:30px 30px 100px; height:297mm; box-shadow:0 0 10px rgba(0,0,0,0.5);">-->
			<form id="pnd1_attach">
			<div style="font-size:16px; margin-bottom:5px"><b>รายงานเงินนำส่งรายสมาชิก</b></div>
			<table class="toptable" border="0" style="border-bottom:0;">
				<tr>
					<td style="width:65%">
						ชื่อสถานประกอบการ : <span style="font-size:14px"><b><?=$compinfo[$lang.'_compname']?></b></span><br />
						เลขประจำตัวผู้เสียภาษีอากร : <b><?=$compinfo['tax_id']?></b><br />
						ลำดับสาขาที่ : <b><?=$branch?></b>
					</td>	
					<td>
						สำหรับค่าจ้างเดือน : <b><span class="formMonth"><?=$_SESSION['rego']['formdate']['m'].'</b></span> <b><span class="formYear">'.$_SESSION['rego']['formdate'][$lang.'y']?></span></b><br>
						วันที่ : <b><span class="formDate"><?=$_SESSION['rego']['formdate'][$lang.'date']?></span></b>
					</td>
				</tr>
			</table>

			<table class="taxtable" border="1">
				<thead>
				<tr>
					<th>ลำดับที่</th>
					<th>ประจำตัวผู้เสียภาษี</th>
					<th style="width:50%">ชื่อ-สกุล</th>
					<th class="tar" style="min-width:110px">เงินสะสม</th>
					<th class="tar" style="min-width:110px">เงินสมทบ</th>
					<th class="tar" style="min-width:110px">เงินลงทุนรวม</th>
				</tr>
				</thead>
				<tbody>
				<? if($data['d']){ foreach($data['d'] as $k=>$v){ ?>
				<tr>
					<td class="tac"><?=$k?></td>
					<td><?=$v['tax_id']?></td>
					<td><?=$v['title'].' '.$v['firstname'].' '.$v['lastname']?></td>
					<td class="tar"><?=$v['pvf_employee']?></td>
					<td class="tar" style="<? if($v['pvf_employee'] <> $v['pvf_employer']){echo 'color:#dd0000';} ?>"><?=$v['pvf_employer']?></td>
					<td class="tar"><?=$v['tot_pvf']?></td>
				</tr>
				<? } } ?>
				<tr>
					<th class="tar" colspan="3"><b>รวม</b></th>
					<th class="tar"><b><?=$data['total_employee']?></b></th>
					<th class="tar"><b><?=$data['total_employer']?></b></th>
					<th class="tar"><b><?=$data['total_pvf']?></b></th>
				</tr>
				</tbody>
			</table>
			
			</form>

</div>

	<script type="text/javascript">
		
		$(document).ready(function() {
			
			$(document).on("click", ".print", function(e) {
				window.open(ROOT+'payroll/print/print_pvf_attach_th.php?'+$(this).data('type'), '_blank')
			});
			
		})
	
	</script>













