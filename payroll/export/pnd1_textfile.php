
<div class="A4form" style="width:960px">
<table style="width:100%; margin-bottom:15px;" border="0"><tr>
	<td style="font-size:20px; font-weight:600"><?=$lng['P.N.D.1 text file']?></td>
	<td>
		<button type="button" class="btn btn-primary btn-fr" onclick="window.location.href='<?=ROOT?>payroll/export/download/download_pnd1_textfile.php';"><i class="fa fa-download"></i>&nbsp; <?=$lng['Download']?></button>
	</td>
</tr></table>

<?php

	if(session_id()==''){session_start();}
	ob_start();
	//mb_internal_encoding('UTF-8');
	include(DIR.'files/arrays_th.php');

	// echo '<pre>'; 
	// print_r($_SESSION['rego']);
	// echo '</pre>';

	//echo $_SESSION['rego']['paydate'];

	$paydate = str_replace('/','',$_SESSION['rego']['paydate']);
	$paydate = str_replace('-','',$_SESSION['rego']['paydate']);
	$year = substr($paydate,-4)+543;
	$paydate = substr($paydate,0,-4).$year;
	
	$txt = '';
	$nr = 1;
	$sql = "SELECT emp_id, gross_income, tax_month, calc_tax, total_non_allow, tot_absence FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."' AND emp_group = '".$_SESSION['rego']['emp_group']."' AND calc_tax = 1 ORDER by emp_id ASC";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
					$gross = $row['gross_income'] - $row['total_non_allow'] - $row['tot_absence'];
					//$gross = $row['gross_income'];
					$txt 	.= '<span style="color:#069; display:block">'.sprintf("%03d",$nr).'|'
							.str_replace('-','',$edata['tax_id']).'|'
							.$empinfo['tax_id'].'|'
							.trim($title[$empinfo['title']]).'|'
							.trim($empinfo['firstname']).'|'
							.trim($empinfo['lastname']).'|'
							.'1|'
							.$paydate.'|'
							.number_format($gross,2,'.','').'|'
							.number_format($row['tax_month'],2,'.','').'|'
							.'1'
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