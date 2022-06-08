<?php
	
	$year['en'] = date('Y');
	$year['th'] = date('Y')+543;
	$data = getPND1attach($_SESSION['rego']['payroll_dbase'],$_SESSION['rego']['gov_month'],$_SESSION['rego']['gov_entity']);
	
	if($res = $dbc->query("SELECT ".$lang."_addr_detail, ".$lang."_compname, comp_phone, tax_id, revenu_branch, dig_signature, digi_signature, dig_stamp, digi_stamp FROM ".$cid."_entities_data WHERE ref = '".$_SESSION['rego']['gov_entity']."'")){
		$xdata = $res->fetch_assoc();
		$address = unserialize($xdata[$lang.'_addr_detail']);
	}
	$branch = sprintf("%05d",$xdata['revenu_branch']);
	$tax_id = $xdata['tax_id'];
	//$per_id = $sys_settings['personal_idnr'];
	$title[''] = '? ?';
	
	if(!$_POST){$_POST['rr'] = 1;}

	$Paydate = $_SESSION['rego']['paydate'];
	$getdate = date('d', strtotime($Paydate));
	$getmonth = date('m', strtotime($Paydate));
	$newPaydate = $getdate.'-'.$getmonth.'-'.$_SESSION['rego']['year_'.$lang];

	
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
 <!--<div style="width:210mm;margin:0 auto;margin-top:10px; background:#fff; padding:30px 30px 100px; height:297mm; box-shadow:0 0 10px rgba(0,0,0,0.5);">-->
			<form id="pnd1_attach">
			
			<div style="font-size:16px; margin-bottom:5px"><b>ใบแนบ ภ.ง.ด. 1</b></div>
			<table class="formTable toptable" border="0" style="border-bottom:0;">
				<tbody>
				<tr>
					<td>
						 เลขประจำตัวผู้เสียภาษีอากร(13หลัก)* : <b><?=$tax_id?></b><br />
						<span style="font-size:11px">(ของผู้มีหน้าที่หักภาษี ณ ที่จ่าย)</span></td>
					<td>
						เลขประจำตัวผู้เสียภาษีอากร : <b><? //=$per_id?></b><br />
						<span style="font-size:11px">(ของผู้มีหน้าที่หักภาษี ณ ที่จ่ายที่เป็นผู้ไม่มีเลขประจำตัวผู้เสียภาษีอากร( 13 หลัก)*)</span></td>
					<td style="text-align:right">สาขาที่ : <b><?=$branch?></b></td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable2" border="0">
				<tbody>
				<tr>
					<td colspan="2"><b>ให้แยกกรอกรายการในใบแนบนี้ตามเงินได้แต่ละประเภท  โดยใส่เครื่องหมาย <i class="fa">&#xf046;</i> ลงใน <i class="fa">&#xf096;</i> หน้าข้อความแล้วแต่กรณี  เพียงข้อเดียว</b></td>
				</tr>
				<tr>
					<td style="padding:0; width:46%">
						<table border="0">
							<tr>
								<td>
								<label><input <?php if($_POST['rr']==1){echo 'checked';} ?> type="radio" name="rr" value="1" class="checkbox"><span><b>1.</b> เงินได้ตามมาตรา <b>40(1)</b> เงินเดือน ค่าจ้าง ฯลฯ กรณีทั่วไป</span></label>
								</td>
							</tr>
							<tr>
								<td>
								<label><input <?php if($_POST['rr']==2){echo 'checked';} ?> type="radio" name="rr" value="2" class="checkbox"><span><b>2.</b> เงินได้ตามมาตรา <b>40(1)</b> เงินเดือน ค่าจ้าง ฯลฯ กรณีทั่วไป</span></label><br /><p>กรณีได้รับอนุมัติจากกรมสรรพากรให้หักอัตรา ร้อยละ 3</p>

								</td>
							</tr>
						</table>
					</td>
					<td style="padding:0 0 5px 0; width:54%">
						<table border="0">
							<tr>
								<td><label><input <? if($_POST['rr']==3){echo 'checked';} ?> type="radio" name="rr" value="3" class="checkbox"><span><b>3.</b> เงินได้ตามมาตรา <b>40(1)(2)</b> กรณีนายจ้างจ่ายให้ครั้งเดียวเพราะเหตุออกจากงาน</span></label></td></tr>
							<tr><td><label><input <? if($_POST['rr']==4){echo 'checked';} ?> type="radio" name="rr" value="4" class="checkbox"><span><b>4.</b> เงินได้ตามมาตรา <b>40(2)</b> กรณีผู้รับเงินได้เป็นผู้อยู่ในประเทศไทย</span></label></td></tr>
							<tr><td><label><input <? if($_POST['rr']==5){echo 'checked';} ?> type="radio" name="rr" value="5" class="checkbox"><span><b>5.</b> เงินได้ตามมาตรา <b>40(2)</b> กรณีผู้รับเงินได้มิได้เป็นผู้อยู่ในประเทศไทย</span></label></td></tr>
						</table>
					</td>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable taxtable" border="1">
				<thead>
				<tr>
					<th rowspan="2" style="vertical-align:middle;">ลำดับที่</th>
					<th class="tal" rowspan="2" style="white-space:normal">เลขประจำตัวผู้เสียภาษีอากร (13 หลัก)* ของผู้มีเงินได้</th>
					<th class="tal" rowspan="2" style="white-space:normal">ชื่อผู้มีเงินได้ (ให้ระบุให้ชัดเจนว่าเป็น นาย นาง นางสาว หรือ ยศ)</th>
					<th colspan="2" style="vertical-align:middle;">รายละเอียดเกี่ยวกับการจ่ายเงิน</th>
					<th rowspan="2" style="vertical-align:middle;">จำนวนเงินภาษีที่หัก<br />และนำส่งในครั้งนี้</th>
					<th rowspan="2" style="vertical-align:middle;">*<br />เงื่อนไข</th>
				</tr>
				<tr>
					<th>วัน เดือน ปี ที่จ่าย</th>
					<th>จำนวนเงินได้ที่จ่ายในครั้งนี้</th>
				</tr>
				</thead>
				<tbody>
				<?php if($data['d']){ foreach($data['d'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left; font-weight:600"><?=$v['tax_id']?></td>
					<td class="tal"><?=$v['emp_id']?> - <?=$title[$v['title']].' '.$v['th_name']?></td>
					<!-- <td style="text-align:right"><?=$_SESSION['rego']['paydate']?></td> -->
					<td style="text-align:right"><?=$newPaydate?></td>
					<td style="text-align:right"><?=$v['grossincome']?></td>
					<td style="text-align:right"><?=$v['tax']?></td>
					<td><i>(<?=$v['type']?>)</i></td>
				</tr>
				<?php } } ?>
				
				<?php if($data['d2']){ ?>
				<tr>
					<th colspan="7" style="text-align:center;font-weight:bold;">40(2) เอกสารแนบแยกต่างหาก</th>
				</tr>	

				<? foreach($data['d2'] as $k=>$v){ ?>
				<tr>
					<td style="text-align:right"><?=$k?>&nbsp;</td>
					<td style="text-align:left; font-weight:600"><?=$v['tax_id']?></td>
					<td class="tal"><?=$v['emp_id']?> - <?=$title[$v['title']].' '.$v['th_name']?></td>
					<!-- <td style="text-align:right"><?=$_SESSION['rego']['paydate']?></td> -->
					<td style="text-align:right"><?=$newPaydate?></td>
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
					<th colspan="4" style="text-align:right"><b>รวมยอดเงินได้และภาษีที่นำส่ง</b><br><span style="font-size:10px">(นำไปรวมกับใบแนบ ภ.ง.ด. 1  แผ่นอื่น (ถ้ามี))</span></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$income?></b></th>
					<th style="text-align:right; vertical-align:bottom"><b><?=$tax?></b></th>
					<th></th>
				</tr>
				</tbody>
			</table>
			
			<table class="formTable toptable" border="1" style="border-top:0;">
				<tr>
					<td style="line-height:160%; width:60%; position:relative">
						(ให้กรอกลำดับที่ต่อเนื่องกันไปทุกแผ่นตามเงินได้แต่ละประเภท)<br />
						<b>หมายเหตุ*</b> เงื่อนไขการหักภาษีให้กรอกดังนี้<br />
						<ul style="list-style-type:decimal">
							<li>กรณีบุคคลธรรมดา  ให้ใช้เลขประจำตัวประชาชนที่กรมการปกครองออกให้</li>
							<li> กรณีนิติบุคคล ให้ใช้เลขทะเบียนนิติบุคคลที่กรมพัฒนาธุรกิจการค้าออกให้</li>
							<li>กรณีอื่น ๆนอกหนือจาก 1.และ 2.  ให้ใช้เลขประจำตัวผู้เสียภาษีอากร (13 หลัก) ที่กรมสรรพากรออกให้</li>
						</ul>
						<!--<img style="width:70px; position:absolute; top:10px; right:15px" src="../<?php //=$cid?>/stamp70.png" />-->
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
						<!--<b>Signature </b> :&nbsp;&nbsp; <img style="width:200px" src="../<?php //=$cid?>/dig_signature.png" />-->
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
				window.open(ROOT+'payroll/print/print_pnd1_monthly_attach_th.php?lng='+data, '_blank')
			});
			
		})
	
	</script>













