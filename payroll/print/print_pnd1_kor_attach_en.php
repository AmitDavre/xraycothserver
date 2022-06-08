<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_en.php');
	include(DIR.'files/payroll_functions.php');
	
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	
	$branch = sprintf("%05d",$edata['revenu_branch']);
	$branch = str_split($branch);
	
	$tin = str_replace('-','',$edata['tax_id']);
	if(strlen($tin)!== 13){$tin = '0000000000000';}
	$tin = str_split($tin);

	$data = getPND1attachYear($_SESSION['rego']['gov_entity']);
	
	//var_dump($data); exit;
	

$style = '
<style>
	@page {
		margin: 10px 100px 10px 10px;
	}
	body, html {
		font-family: "leelawadee", "garuda";
		font-family: "garuda";
		font-size:14px;
		position:relative;
	}
	table.taxtable {
		border-collapse:collapse;
		xborder:1px #000 solid;
		width:100%;
		line-height:140%;
	}
	table.taxtable thead th, 
	table.taxtable tbody th, 
	table.taxtable tbody td {
		border:1px solid #999;
		padding:5px 8px;
		line-height:140%;
		font-family: inherit;
		vertical-align:middle;
		text-align:center;
		font-size:12px;
		white-space:nowrap;
		font-weight:normal;
	}
	table.taxtable tbody td {
		text-align:center;
		line-height:140%;
		vertical-align:bottom;
	}
	table.taxtable thead th {
		background:#eee;
	}
	table.taxtable td.amt {
		vertical-align:baseline;
		text-align:right;
	}
	.pnr {
		font-weight:normal;
		text-align:right;
		float:right;
		width:100px;
	}
</style>';
	
	$header = '
		<div style="font-size:16px; float:left; width:80%; border:0px solid red">
			<b>'.$xdata['en_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Kor Attachment - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year'].'</b>
		</div>
		<div class="pnr" style="white-space:nowrap">
			'.$lng['Page'].' {PAGENO}/{nbpg}</div>
		</div>';
	
	$header .= '	
			<table border="0" style="border-bottom:0; width:100%">
				<tbody>
				<tr>
					<td style="width:35%">
						 Taxpayer identification number : <b></b><br />
						<span style="font-size:11px">(Withholding tax agent)</span></td>
					<td>
						Personal identification number : <b>'.$edata['tax_id'].'</b><br />
						<span style="font-size:11px">(Withholding tax agent in case of individual)</span></td>
					<td style="text-align:right">Branch : <b>'.$edata['revenu_branch'].'</b></td>
				</tr>
				</tbody>
			</table>';
			
	$html = '
			<table class="taxtable" border="0">
				<thead>
					<tr>
						<th rowspan="2" style="vertical-align:middle;">No.</th>
						<th style="text-align:left" rowspan="2">Taxpayer ID number</th>
						<th style="text-align:left; width:50%" rowspan="2">Name of recipient of income</th>
						<th colspan="2" style="vertical-align:middle;">Detail of payment of assessable income</th>
						<th rowspan="2" style="vertical-align:middle;">Amount of tax<br />withheld and<br />remitted</th>
						<th rowspan="2" style="vertical-align:middle;">*<br />Con.</th>
					</tr>
					<tr>
						<th>Payment date</th>
						<th>Amount paid</th>
					</tr>
				</thead>
				<tbody>';
				$tot_income = 0;
				$tot_tax = 0;
				if($data['rows']){
					foreach($data['rows'] as $k=>$v){
						$tot_income += str_replace(',','',$v['grossincome']);
						$tot_tax += str_replace(',','',$v['tax']);
						$html .= '
						<tr>
							<td style="text-align:right">'.$k.'</td>
							<td style="text-align:left; font-weight:600">'.$v['tax_id'].'</td>
							<td style="text-align:left">'.$v['emp_id'].' : '.$v['firstname'].' '.$v['lastname'].'<br>'.$v['address'].'</td>
							<td style="text-align:right">'.$_SESSION['rego']['paydate'].'</td>
							<td style="text-align:right">'.$v['grossincome'].'</td>
							<td style="text-align:right">'.$v['tax'].'</td>
							<td><i>(1)</i></td>
						</tr>';
					}
				}
	$html .= '
				<tr>
					<th colspan="4" style="text-align:right"><b>Total amount of income and withholding tax remittance</b>&nbsp;&nbsp;(to be included with other attachement of P.N.D.1 Kor (if any))</th>
					<th style="text-align:right; vertical-align:bottom"><b>'.number_format($tot_income,2).'</b></th>
					<th style="text-align:right; vertical-align:bottom"><b>'.number_format($tot_tax,2).'</b></th>
					<th></th>
				</tr>
				</tbody>
			</table>';


	//echo $style.$html; exit;	

	ob_clean();
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf = new mPDF('utf-8', 'A4-L', 11, '', 8, 8, 25, 8, 5, 5);
	$mpdf->SetDisplayMode('fullpage');
	//$mpdf->SetFontSize(9);
	$mpdf->SetTitle($edata['en_compname'].' ('.strtoupper($_SESSION['rego']['cid']).') - PND1 Kor Attachment - '.$months[(int)$_SESSION['rego']['gov_month']].' '.$_SESSION['rego']['cur_year']);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->SetHTMLHeader($header);
	$mpdf->WriteHTML($html);

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$baseName = $_SESSION['rego']['cid'].'_pnd1_kor_attachment_'.$_SESSION['rego']['curr_month'].'_'.$_SESSION['rego']['year_en'];
	$extension = 'pdf';		
	$filename = getFilename($baseName, $extension, $dir);
	$doc = 'PND1 Kor Attachment'.$_SESSION['rego']['curr_month'].'-'.$_SESSION['rego']['year_en'];
	
	$mpdf->Output($filename,'I');
	
	if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}
	
	exit;












