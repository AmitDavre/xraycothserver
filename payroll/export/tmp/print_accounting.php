<?php
	if(session_id()==''){session_start(); ob_start();}
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$accounting = array();
	if($res = $dbc->query("SELECT accounting FROM ".$cid."_payroll_months WHERE month = '".$_SESSION['rego']['cur_month']."'")){
		if($row = $res->fetch_assoc()){
			$accounting = unserialize($row['accounting']);
			//var_dump($accounting);
			$debet_data = $accounting['debet_data'];
			$credit_data = $accounting['credit_data'];
			$total_debet = $accounting['total_debet'];
			$total_credit = $accounting['total_credit'];
		}
	}
	
$style = '<link rel="stylesheet" type="text/css" media="screen" href="../fonts/font-awesome/css/font-awesome.min.css">
	<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html {
			font-family: "leelawadee", "garuda";
			font-family: "leelawadee";
			font-size:14px;
			color:#000;
		}
		body {
		}
		table.basicTable {
			width:100%;
			border-collapse:collapse;
		}
		table.basicTable thead th, table.basicTable tbody th {
			background:#eee;
			border:1px solid #ccc;
			padding:4px 10px;
		}
		table.basicTable tbody td {
			border:1px solid #ccc;
			padding:4px 10px;
		}
		.pnr {
			font-size:12px;
			font-weight:normal;
			text-align:right;
			float:right;
			width:150px;
			padding-top:4px;
		}
		.footer {
			font-size:12px;
			font-weight:normal;
			text-align:right;
			float:right;
			color:#999;
			width:300px;
		}
		.tal {text-align:left;}
		.tar {text-align:right;}
		
	</style>';
	
	$filename = strtoupper($_SESSION['rego']['cid']).'_accounting_entries_'.$_SESSION['rego']['year_'.$lang].'_'.$_SESSION['rego']['curr_month'].'.pdf';
	
	$header = '
		<table border="0" style="width:100%; border-collapse:collapse">
			<tr>';
				if(!empty($compinfo['logofile'])){ 
					$header .= '<td style="width:10px;padding:0 20px 0 0; vertical-align:top"><img style="height:80px;max-width:350px" src="../../'.$compinfo['logofile'].'?'.time().'" /></td>';}
					$header .= '<td style="padding:0;white-space:nowrap;vertical-align:top"><span style="font-size:22px"><b>'.$compinfo['en_compname'].'</b></span><br />'.nl2br($compinfo['en_address']).'</td>
			</tr>
		</table>';
				
	$footer = '<div class="footer">Form generated on : '.date('d-m-Y').'</div>';
		
	$html = '
			<div style="padding:5px 0; font-weight:bold; font-size:18px">Accounting Entries : '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_'.$lang].'</div>
			<table class="basicTable">
				<thead>
					<tr>
						<th class="tal">Account code</th>
						<th class="tar">Debet</th>
						<th class="tar">Credit</th>
					</tr>
				</thead>
				<tbody>';
					foreach($debet_data as $k=>$v){
						$html .= '
						<tr>
							<td>'.$k.'</td>
							<td class="tar">'.number_format($v,2).'</td>
							<td class="tar">'.number_format(0,2).'</td>
						</tr>';
					}
					foreach($credit_data as $k=>$v){
						$html .= '
						<tr>
							<td>'.$k.'</td>
							<td class="tar">'.number_format(0,2).'</td>
							<td class="tar">'.number_format($v,2).'</td>
						</tr>';
					}
					$html .= '
					<tr style="background:#eee; border-bottom:2px solid #ccc">
						<th class="tar">'.$lng['Totals'].'</th>
						<th class="tar">'.number_format($total_debet,2).'</th>
						<th class="tar">'.number_format($total_credit,2).'</th>
					</tr>
				</tbody>
			</table>';
			
			
			
			
	//echo $style.$header.$html.$footer; exit;			

	$dir = DIR.$_SESSION['rego']['cid'].'/archive/';
	$root = ROOT.$_SESSION['rego']['cid'].'/archive/';
	$doc = 'SSO Form English';
	
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 30, 10, 8, 5);
	$mpdf->SetTitle($filename);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->SetHTMLHeader($header);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->WriteHTML($html);
	//$mpdf->Output($_SESSION['rego']['cid'].'_SSO form_'.$_SESSION['rego']['cur_year'].'_'.$_SESSION['rego']['cur_month'].'.pdf',$action);


	$mpdf->Output($filename,'I');
	
	/*if(isset($_REQUEST['a'])){
		$mpdf->Output($dir.$filename,'F');
		include('save_to_documents.php');
	}*/
	
	exit;








