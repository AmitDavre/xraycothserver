<?php

	if(session_id()==''){session_start();}
	ob_start(); //011
	
	if(!isset($_GET['bank'])){$_GET['bank'] = 'all';}
	$pattern = '%%%-%-%%%%%-%';
	
	$_account = str_replace('-', '', $banks['011']['number']);
	$bank_account = $banks['011']['number'];
	$compname = substr($banks['011']['name'], 0, 60);

	$txt = '';
	$data = array();
	$total = 0;
	$nr = 1; ;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '011', '011');
				//var_dump($empinfo);
				if($empinfo){

					if($empinfo['bank_code'] == '011'){

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
						
						$txt .= '102100001';
						$txt .= sprintf("%03d",$empinfo['bank_code']);
						$txt .= sprintf("%04d", substr($empinfo['bank_account'],0,3));
						$txt .= sprintf("%011d", $empinfo['bank_account']);

						$txt .= sprintf("%03d",$compinfo['bank_name']);
						$txt .= sprintf("%04d", substr($account,0,3));
						$txt .= sprintf("%011d", $account);
						$txt .= date('dmY', strtotime($_SESSION['payroll']['paydate']));
						$txt .= '01';
						$txt .= sprintf("%014d",$salary);
						$txt .= $name.str_repeat(' ', (60-strlen($name)));
						//$txt .= str_repeat(' ', 47);
						$txt .= $compname;
						if(strlen($compname) < 60){
							$txt .= str_repeat(' ', 60 - strlen($compname));
						}
						
						$txt .= '0000000000';
						$txt .= str_repeat(' ', 90);
						$txt .= sprintf("%06d",$nr);
						$txt .= 'GI';
						$txt .= '<hr style="margin:2px 0 5px 0">';
						$txt .= "<br>";
						
						$nr++;
					}
				}
			}

		}
	}
	



	echo '</pre>';
	$smart_txt = 'H ';
	$smart_txt .= sprintf("%06d",1);
	$smart_txt .= ' 011';
	$smart_txt .= ' 0000';
	$smart_txt .= ' '.sprintf("%011d", $_account);
	$smart_txt .= ' '.$compname;
	$smart_txt .= ' <span id="sDate">ddmmyy</span>';
	$smart_txt .= ' UN1 ';
	$smart_txt .= ' '.str_repeat('0', 48);
	$smart_txt .= '<br>';
	
	$nr = 1;
	$tot_salary = 0;
	$sdata = array();
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeesByBank($cid, $row['emp_id'], '011', $_GET['bank']);
				if($empinfo){
					$tmp = round($row['net_income'],2);
					$salary = $tmp * 100;
					$name = trim($empinfo['bank_account_name']);
					if(empty($name)){$name = $title[$empinfo['title']].' '.trim($empinfo[$lang.'_name']);}
					$account = str_replace('-', '', $empinfo['bank_account']);
					
					$sdata[$nr]['account'] = $account;
					if(strlen($account) == 10){
						$sdata[$nr]['account'] = vsprintf(str_replace('%','%s',$pattern),str_split($account));
					}
					$sdata[$nr]['name'] = $name;
					$sdata[$nr]['income'] = number_format($row['net_income'],2);
					$sdata[$nr]['branch'] = $empinfo['bank_branch'];
					$sdata[$nr]['code'] = $empinfo['bank_code'];
					$stotal += round($row['net_income'],2);
					
					$name = preg_replace('!\s+!', ' ', $name);
					$smart_txt .= 'D ';
					$smart_txt .= sprintf("%06d",$nr+1);
					$smart_txt .= ' '.sprintf("%03d",$empinfo['bank_code']);
					$smart_txt .= ' '.sprintf("%04d", substr($account,0,3));
					$smart_txt .= ' '.sprintf("%011d", $account);
					$smart_txt .= ' C'.str_repeat('0', (12 - mb_strlen($salary)));
					$smart_txt .= $salary;
					$smart_txt .= ' 01'; // Payroll service
					$smart_txt .= ' '.$name.str_repeat(' ', (30 - mb_strlen($name)));
					$smart_txt .= '<br>';
					$nr++;
					$tot_salary += $salary;
				}
			}
			$smart_txt .= 'T '.sprintf("%06d",$nr+1);
			$smart_txt .= ' 011';
			$smart_txt .= ' '.sprintf("%011d", $_account);
			$smart_txt .= ' '.sprintf("%07d",'0');
			$smart_txt .= ' '.sprintf("%013d",'0');
			$smart_txt .= ' '.sprintf("%07d",($nr-1));
			$smart_txt .= str_repeat('0', (13 - mb_strlen($tot_salary)));
			$smart_txt .= $tot_salary;
			$smart_txt .= ' '.str_repeat('0', 48);
		}
	}
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
		<li class="nav-item"><a class="nav-link active" data-target="#tab_list" data-toggle="tab"><?=$lng['List TMB accounts']?></a></li>
		<li class="nav-item"><a class="nav-link" data-target="#tab_other" data-toggle="tab"><?=$lng['List Other accounts']?></a></li>

		<li class="nav-item"><a class="nav-link" data-target="#tab_upload" data-toggle="tab"><?=$lng['Textfile TMB accounts']?></a></li>

		<li class="nav-item"><a class="nav-link" data-target="#tab_smart" data-toggle="tab"><?=$lng['Textfile Other accounts']?></a></li>

		<li class="nav-item">
			<select id="bank_filter">
				<option <? if($_GET['bank'] == 'all'){echo 'selected';}?> value="all"><?=$lng['All Bank accounts']?></option>
				<option <? if($_GET['bank'] == '011'){echo 'selected';}?> value="011"><?=$lng['Only TMB accounts']?></option>
				<option <? if($_GET['bank'] == 'other'){echo 'selected';}?> value="other"><?=$lng['Other Bank accounts']?></option>
			</select>
		</li>
		<!--<li><a data-target="#tab_other" data-toggle="tab">Other file<? //=$lng['Personal data']?></a></li>-->
	</ul>
	
	<div class="tab-content" style="min-height:400px">
		
		<div class="tab-pane show active" id="tab_list">
			<table border="0" width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600">
						<?=$lng['TMB Bank Payment List (SMART PAYROLL)']?>
					</td>
					<td>
						<!-- <a type="button" class="btn btn-primary btn-fr" href="export/download/download_tmb_payment_list_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a> -->
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_tmb_payment_Newlist_excel.php"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=011"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?></a>
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


		<div class="tab-pane" id="tab_other">
			<table border="0" width="100%" style="margin-bottom:8px">
				<tr>
					<td style="font-size:18px; font-weight:600">
						<?=$lng['TMB Bank']?> <?=$lng['Payment list']?> (SMART OTHER)
					</td>
					<td>
						
						<a type="button" class="btn btn-primary btn-fr" href="export/download/download_tmb_smart_excel.php?bank=<?=$_GET['bank']?>"><i class="fa fa-file-excel-o"></i>&nbsp; <?=$lng['Download Excel file']?></a>
						<a target="_blank" type="button" class="btn btn-primary btn-fr" href="export/print_paymentlist.php?acc=011"><i class="fa fa-print"></i>&nbsp; <?=$lng['Print list']?></a>
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
				<? $totSalary = 0; if($sdata){ foreach($sdata as $k=> $v){ 
					$totSalary += str_replace(',','',$v['income']); ?>
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
						<td class="tar" style="font-weight:600"><?=number_format($totSalary,2)?></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>


		
		<div class="tab-pane" id="tab_upload">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['TMB Bank']?> Payroll </td>
					<td style="text-align:right">
						<button id="tmbPrint" type="button" class="btn btn-primary btn-fr" xonclick="window.location.href='<?=ROOT?>payroll/export/download/download_tmb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
						<input id="txtdate" placeholder="<?=$lng['Select date']?>" readonly style="display:inline-block; width:100px; cursor:pointer; float:right" type="text">
					</td>
				</tr>
			</table>
			<span class="txt"><?=$txt?></span>
		</div>


		<div class="tab-pane" id="tab_smart">
			<table style="width:100%; margin-bottom:15px;" border="0">
				<tr>
					<td style="font-size:18px; font-weight:600"><?=$lng['TMB Bank']?> Smart Pay </td>
					<td style="text-align:right">
						<button id="tmbPrint" type="button" class="btn btn-primary btn-fr" xonclick="window.location.href='<?=ROOT?>payroll/export/download/download_tmb_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
						<input id="txtdate" placeholder="<?=$lng['Select date']?>" readonly style="display:inline-block; width:100px; cursor:pointer; float:right" type="text">
					</td>
				</tr>
			</table>
			<span class="txt"><?=$smart_txt?></span>
		</div>
		
	</div>

</div>


<script>

	$(document).ready(function() {

		$('#bank_filter').on('change', function(){
			window.location.href = 'index.php?mn=420&sm=49&bank=' + this.value;
		})
	
		
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


		var activeTab = localStorage.getItem('activeTabExp');
		if(activeTab){
			$('#myTab a[data-target="' + activeTab + '"]').tab('show');
		}else{
			$('#myTab a[data-target="#tab_list"]').tab('show');
		}
		$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			localStorage.setItem('activeTabExp', $(e.target).data('target'));
		});
	
	});
	
</script>








