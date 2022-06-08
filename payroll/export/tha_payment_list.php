<?php

	if(session_id()==''){session_start();}
	ob_start(); //065
	
	$pattern = '%%%-%-%%%%%-%';
	
	$_account = str_replace('-', '', $banks['065']['number']);
	$bank_account = $banks['065']['number'];
	$compname = substr($banks['065']['name'], 0, 60);
	
	$txt = "H 000001 065 ";
	$txt .= sprintf("%010d",str_replace('-', '', $_account)).' ';
	$txt .= $compname;
	$txt .= ' <span id="hDate">ddmmyy</span> ';
	$txt .= '000000';
	$txt .= str_repeat(' ', 71);
	$txt .= "<br>";

	$data = array();
	$total = 0;
	$nr = 1; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '065', 'all');
				//var_dump($empinfo);
				if($empinfo){
					//if($empinfo['bank_code'] == '065'){
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
						$account = str_replace('-', '', $empinfo['bank_account']);
						
						$data[$nr]['account'] = $account;
						if(strlen($account) == 10){
							$data[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
						}
						$data[$nr]['name'] = $name;
						$data[$nr]['income'] = number_format($row['net_income'],2);
						$data[$nr]['branch'] = $empinfo['bank_branch'];
						$data[$nr]['code'] = $empinfo['bank_code'];
						$total += round($row['net_income'],2);
						
						$tmp = number_format($row['net_income'],2);
						$tmp = str_replace(',','',$tmp);
						$salary = str_replace('.','',$tmp);
						
						$name = trim($empinfo['bank_account_name']);
						if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo['en_name']);}
						$name = preg_replace('!\s+!', ' ', $name);
						$len = strlen($name);
						
						$txt .= 'D ';
						$txt .= sprintf("%06d",$nr+1);
						$txt .= ' 065 ';
						$txt .= sprintf("%010d", $account);
						$txt .= ' C '.sprintf("%010d",$salary);
						$txt .= ' 089';
						$txt .= str_repeat(' ', 10);
						$txt .= ' <span id="dDate">yymmdd</span> ';
						$txt .= '0000001';
						$txt .= str_repeat(' ', 26);
						$txt .= $name;
						/*if(mb_strlen($name) < 35){
							$txt .= str_repeat(' ', 35 - $len);
						}*/
						$txt .= "<br>";
						
						$nr++;
					//}
				}
			}
		}
	}
	$txt .= 'T';
	$txt .= sprintf("%06d",$nr+1);
	$txt .= '065';
	$txt .= sprintf("%010d", $_account);
	$txt .= str_repeat('0', 7);
	$txt .= str_repeat('0', 13);
	$txt .= sprintf("%07d",($nr-1));
	$txt .= sprintf("%013d",$total);
	$txt .= str_repeat('0', 40);
	$txt .= str_repeat(' ', 28);
	$txt .= "\r\n";
?>

<style>
	table.codeTable {
		border-collapse:collapse;
		font-family: Courier New, Verdana;
	}
	table.codeTable td {
		color:#069;
		white-space:nowrap;
	}
	span.txt {
		font-family: Courier New, Verdana;
		font-size:12px;
		color:#069;
		padding-bottom:10px;
		display:block;
		white-space:pre-wrap;
	}
</style>

<div class="A4form" style="width:960px;padding:30px;">

	<ul class="nav nav-tabs" id="myTab">
		<li class="nav-item"><a class="nav-link active" data-target="#tab_list" data-toggle="tab"><?=$lng['Payment list']?></a></li>
		<!--<li class="nav-item"><a class="nav-link" data-target="#tab_upload" data-toggle="tab"><?=$lng['Text file']?></a></li>-->
		<!--<li><a data-target="#tab_other" data-toggle="tab">Other file<? //=$lng['Personal data']?></a></li>-->
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane show active" id="tab_list">
			<table border="0" width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600">
						<?=$lng['Thanachart Bank']?> <?=$lng['Payment list']?>
					</td>
					<td>
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_tmb_payment_list_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=065"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?> List</a>
					</td>
				</tr>
			</table>
			
			<table border="0" class="basicTable" width="100%">
				<thead>
					<tr>
						<th class="tac" style="width:10px">#</th>
						<th style="width:70%"><?=$lng['Account name']?></th>
						<th style="min-width:110px"><?=$lng['Account']?></th>
						<th class="tar" style="min-width:110px"><?=$lng['Amount']?></th>
						<th class="tar"><?=$lng['Bank code']?></th>
					</tr>
				</thead>
				<tbody>
				<? if($data){ foreach($data as $k=> $v){ ?>
					<tr>
						<td class="tac"><?=$k?></td>
						<td><?=$v['name']?></td>
						<td><?=$v['account']?></td>
						<td class="tar"><?=$v['income']?></td>
						<td class="tac"><?=$v['code']?></td>
					</tr>
				<? }} ?>
					<tr>
						<td colspan="3" class="tar" style="font-weight:600"><?=$lng['Total']?></td>
						<td class="tar" style="font-weight:600"><?=number_format($total,2)?></td>
						<td></td>
					</tr>
				</tbody>
			</table>

		</div>
		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['TMB Bank']?> <?=$lng['Text file']?> </td>
					<td style="text-align:right">
						<button id="tmbPrint" type="button" class="btn btn-primary btn-fr" xonclick="window.location.href='<?=ROOT?>payroll/export/download/download_tmb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
						<input id="txtdate" placeholder="<?=$lng['Select date']?>" readonly style="display:inline-block; width:100px; cursor:pointer; float:right" type="text">
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>
		
	</div>

</div>


<script>

	$(document).ready(function() {
		
		$('#txtdate').datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			inline: true,
			orientation: 'bottom',
			language: lang,//lang+'-th',
			todayHighlight: true,
			daysOfWeekDisabled: "0,6",
		}).on('changeDate', function(e) {
			 $('#hDate').html(e.format('ddmmyy'));
			 $('#dDate').html(e.format('yymmdd'));
		});
	
		$('#tmbPrint').on('click', function(){
			var date = $('#txtdate').val();
			if(date == ''){
				$("body").overhang({
					type: "error",
					message: '<i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;<?=$lng['Please select payment date']?>',
					duration: 4,
				})
				return false;
			}
			window.location.href = 'export/download/download_tmb_textfile.php?date=' + date;
		})
	
	});
	
</script>








