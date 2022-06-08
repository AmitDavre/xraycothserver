<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//include('../inc/payroll_functions.php');
	$edata = getEntityData($_SESSION['rego']['gov_entity']);
	$address = unserialize($edata[$_SESSION['rego']['lang'].'_addr_detail']);
	$banks = array();
	$tmp = unserialize($edata['banks']);
	if($tmp){
		foreach($tmp as $k=>$v){
			$banks[$v['code']]['name'] = $v['name'];
			$banks[$v['code']]['number'] = $v['number'];
		}
	}
	//var_dump($edata); exit;

$style = '
	<style>
		@page {
			margin: 10px 10px 10px 10px;
		}
		body, html, table {
			font-family: "leelawadee", "garuda";
			font-size:12px;
		}
		table.printTable {
			border-collapse:collapse;
			width:100%;
			margin:6px 0 0 0;
		}
		table.printTable th, table.printTable td {
			border:1px solid #999;
			padding:3px 6px;
			line-height:140%;
			color:#111;
			vertical-align:middle;
			text-align:left;
			white-space:nowrap;
		}
		table.printTable th {
			background:#eee;
		}
		table.printTable th.tac, table.printTable td.tac {
			text-align:center;
		}
		table.printTable th.tar, table.printTable td.tar {
			text-align:right;
		}
		
		table.topTable {
			border-collapse:collapse;
			border:0;
		}
		table.topTable td {
			padding:3px 6px;
			vertical-align: baseline;
			white-space:nowrap;
			font-weight:normal;
			white-space:normal;
			line-height:140%;
			text-align:left;
			
		}
		
		i.fa {
			font-family: fontawesome;
			font-style:normal;
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
			font-size:10px;
			font-weight:normal;
			text-align:right;
			float:right;
			color:#999;
			width:300px;
		}
	</style>';

$header = '
	<table border="0" style="width:100%">
		<tr>';
			if(!empty($edata['logofile'])){ 
				$header .= '<td style="width:10px;padding:0 20px 0 0; vertical-align:top"><img style="height:40px;max-width:350px" src="../../'.$edata['logofile'].'?'.time().'" /></td>';}
				$header .= '<td style="padding:0;white-space:nowrap;vertical-align:middle"><span style="font-size:22px"><b>'.$edata[$lang.'_compname'].'</td>
		</tr>
	</table>';
			
$footer = '<div class="footer">'.$lng['Form generated on'].' : '.date('d-m-Y').'</div>';
	
$html = '<html><body>';

	$pattern = '%%%-%-%%%%%-%';
	
	$nr = 1;
	$total = 0;
	$sql = "SELECT * FROM ".$_SESSION['rego']['payroll_dbase']." WHERE month = '".$_SESSION['rego']['cur_month']."' AND entity = '".$_SESSION['rego']['gov_entity']."'";
	if($res = $dbc->query($sql)){
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$empinfo = getEmployeeInfo($cid, $row['emp_id']);
				$name = trim($empinfo['bank_account_name']);
				if(empty($name)){$name = $title[$empinfo['title']].' '.$empinfo['en_name'];}
				$data[$nr][1] = vsprintf(str_replace('%','%s',$pattern),str_split(str_replace('-', '', $empinfo['bank_account'])));
				$data[$nr][2] = $empinfo['bank_branch'];
				$data[$nr][3] = $name;
				$data[$nr][4] = number_format($row['net_income'],2);
				$total += $row['net_income'];
				$nr++;
			}
		}
	}
	$html .= '
		<table border="0" class="topTable">
			<tbody>
				<tr>
					<th>'.$lng['Company'].' :</th>
					<td>'.$banks[$_GET['acc']]['name'].'</td>
					<th>'.$lng['Account no.'].' :</th>
					<td>'.$banks[$_GET['acc']]['number'].'</td>
					<th>'.$lng['Subject'].' :</th>
					<td>'.$lng['Payment wages'].' '.$months[$_SESSION['rego']['cur_month']].' '.$_SESSION['rego']['year_th'].'</td>
				</tr>
			</tbody>
		</table>
		
		<table class="printTable">
			<thead>
				<tr>
					<th class="tac" style="width:10px">#</th>
					<th>'.$lng['Account no.'].'</th>
					<th>'.$lng['Branch'].'</th>
					<th style="width:60%">'.$lng['Account name'].'</th>
					<th class="tar">'.$lng['Amount'].'</th>
				</tr>
			</thead>
			<tbody>';
					
	foreach($data as $k=> $v){
		$html .= '
				<tr>
					<td class="tac">'.$k.'</td>
					<td>'.$v[1].'</td>
					<td>'.$v[2].'</td>
					<td>'.$v[3].'</td>
					<td class="tar">'.$v[4].'</td>
				</tr>';
	}
	$html .= '
				<tr>
					<td colspan="4" class="tar" style="font-weight:bold">'.$lng['Total'].'</td>
					<td class="tar" style="font-weight:bold">'.number_format($total,2).'</td>
				</tr>
			</tbody>
		</table>';
		
	$html .= '</body></html>';	
	//echo $style.$header.$html.$footer; exit;	
			
	require_once(DIR."mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-P', 9, '', 8, 8, 24, 10, 8, 5);
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(9);
	$mpdf->SetTitle($banks[$_GET['acc']]['name'].' - Payment list');
	$mpdf->WriteHTML($style,1);
	
	$mpdf->SetHTMLHeader($header);
	$mpdf->SetHTMLFooter($footer);
	
	$mpdf->WriteHTML($html);
	
	$dir = DIR.$_SESSION['rego']['cid'].'/documents/';
	$root = ROOT.$_SESSION['rego']['cid'].'/documents/';
	$filename = $_SESSION['rego']['cid'].'_'.$banks[$_GET['acc']]['name'].'_'.$lng['paymentlist'].'_'.$_SESSION['rego']['cur_month'].'_'.$_SESSION['rego']['cur_year'].'.pdf';
	$doc = $banks[$_GET['acc']]['name'].' - '.$lng['Payment list wages'];

	$mpdf->Output($dir.$filename,'F');
	$mpdf->Output($filename,'I');
	
	include('save_to_documents.php');

	exit;










