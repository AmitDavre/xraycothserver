
<div class="A4form" style="width:960px">
<table style="width:100%; margin-bottom:15px;" border="0"><tr>
	<td style="font-size:20px; font-weight:600">P.N.D.3 text file<? //=$lng['P.N.D.1 text file']?></td>
	<td>
		<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_pnd3_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
	</td>
</tr></table>

<?php

	if(session_id()==''){session_start();}
	ob_start();
	//mb_internal_encoding('UTF-8');
	include(DIR.'files/arrays_th.php');

	$paydate = date('d/m/', strtotime($_SESSION['rego']['paydate']));
	$paydate .= date('Y', strtotime($_SESSION['rego']['paydate']))+543;
	
	$txt = '';
	$nr = 1;
	$sql = "SELECT emp_id, calc_base, gross_income, tax_month, calc_tax FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_tax = 3 ORDER by emp_id ASC";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$address = $empinfo['reg_address'].' '.$empinfo['sub_district'].' '.$empinfo['district'].' '.$empinfo['province'].' '.$empinfo['postnr'];
				$gross = $row['gross_income'];
				if($row['calc_base'] == 'gross'){
					$condition = 1;
				}else{
					$condition = 2;
					$gross -= $row['tax_month'];
				}
				$txt 	.= '<span style="color:#069; display:block">'.sprintf("%05d",$nr).'|'
						.str_replace('-','',$empinfo['tax_id']).'|'
						.trim($title[$empinfo['title']]).'|'
						.trim($empinfo['firstname']).'|'
						.trim($empinfo['lastname']).'|'
						.$paydate.'|'
						.'wages'.'|'
						.'03.00'.'|'
						.number_format($gross,2,'.','').'|'
						.number_format($row['tax_month'],2,'.','').'|'
						.$condition
						.'</span><hr style="margin:5px 0 5px 0">';
						$nr++;
			}
		}else{
			$txt = '<i class="fa fa-caret-right"></i> '.$lng['No data available for this month'];
		}
	}
	//ob_clean();
	echo trim($txt);	
	//exit;	
?>
</div>