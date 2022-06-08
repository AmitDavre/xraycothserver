<?php
	
	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$data = array();
	if(isset($_GET['id'])){
		$sql = "SELECT * FROM ".$cid."_tax_simulation WHERE emp_id = '".$_GET['id']."'";
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			$data = unserialize($row['calculate_net']);
			$detail = unserialize($row['gross_from_net']);
		}
	}
	//var_dump($row);
	//var_dump($data);
	//var_dump($detail); exit;
	
$style = '
	<style>
		@page {
			margin: 10px 100px 10px 10px;
		}
		body, html, table {
			font-family: "leelawadee", "garuda";
			font-size:12px;
			color:#036;
		}
		table.printTable {
			width:100%;
			border-collapse:collapse;
			table-layout:auto;
		}
		table.printTable thead th {
			vertical-align:middle;
			text-align:left;
			padding:5px 10px;
			white-space:nowrap;
			font-size:14px;
			background:#c20; 
			border:1px solid #c20; 
			color:#fff
		}
		table.printTable tbody th {
			border:1px solid #ccc;
			background:#eee;
			padding:3px 10px;
			white-space:nowrap;
			font-weight:bold;
		}
		table.printTable tfoot td {
			border:1px solid #ccc;
			background:#eee;
			padding:3px 10px;
			white-space:nowrap;
			font-weight:bold;
		}
		table.printTable td {
			border:1px solid #ddd;
			vertical-align:middle;
			text-align:right;
			padding:3px 10px;
			width:100px;
		}
		table.printTable td.title {
			font-weight:bold;
			white-space:nowrap;
		}
		table.printTable td.fatborder {
			border-bottom:2px solid #ccc;
		}
		table.printTable td.tal {
			text-align:left;
		}
		table.printTable th.tac {
			text-align:center;
		}
	
	</style>';
	
$html = '
	<table class="printTable">
		<thead>
			<tr>
				<th colspan="5">
					'.$lng['Calculator Net Income'].'  :: '.$row['emp_id'].' - '.$row[$lang.'_name'].'
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="title tal">'.$lng['Gross income full year'].'</td>
				<td>'.number_format($data['tot_gross'],2).'</td>
				<td></td>
				<td class="title">'.$lng['Gross / month'].'</td>
				<td>'.number_format($data['gross_month'],2).'</td>
			</tr>
			<tr>
				<td class="title tal">'.$lng['Personal Tax deductions'].'</td>
				<td class="nopad">'.number_format($row['pers_tax_deduct_net'],2).'</td>
				<td></td>
				<td class="title">'.$lng['Tax / month'].'</td>
				<td>'.number_format($data['tax_month'],2).'</td>
			</tr>
			<tr>
				<td class="title tal">'.$lng['Taxable income'].'</td>
				<td>'.number_format($data['taxable'],2).'</td>
				<td></td>
				<td class="title">'.$lng['Net / month'].'</td>
				<td>'.number_format($data['net_month'],2).'</td>
			</tr>
			<tr>
				<td class="title fatborder tal">'.$lng['Netto income per year'].'</td>
				<td class="fatborder">'.number_format($data['tot_gross'],2).'</td>
				<td class="fatborder"></td>
				<td class="fatborder"></td>
				<td class="fatborder"></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 0 '.$lng['to'].' 150,000</td>
				<td>'.number_format($data['net'][0][1],2).'</td>
				<td>'.$data['net'][0][2].' %</td>
				<td>'.number_format($data['net'][0][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 150,001 '.$lng['to'].' 292,500</td>
				<td>'.number_format($data['net'][5][1],2).'</td>
				<td>'.$data['net'][5][2].' %</td>
				<td>'.number_format($data['net'][5][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 292,501 '.$lng['to'].' 472,500</td>
				<td>'.number_format($data['net'][10][1],2).'</td>
				<td>'.$data['net'][10][2].' %</td>
				<td>'.number_format($data['net'][10][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 472,501 '.$lng['to'].' 685,000</td>
				<td>'.number_format($data['net'][15][1],2).'</td>
				<td>'.$data['net'][15][2].' %</td>
				<td>'.number_format($data['net'][15][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 685,001 '.$lng['to'].' 885,000</td>
				<td>'.number_format($data['net'][20][1],2).'</td>
				<td>'.$data['net'][20][2].' %</td>
				<td>'.number_format($data['net'][20][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 885,001 '.$lng['to'].' 1,635,000</td>
				<td>'.number_format($data['net'][25][1],2).'</td>
				<td>'.$data['net'][25][2].' %</td>
				<td>'.number_format($data['net'][25][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From'].' 1,635,001 '.$lng['to'].' 3,735,000</td>
				<td>'.number_format($data['net'][30][1],2).'</td>
				<td>'.$data['net'][30][2].' %</td>
				<td>'.number_format($data['net'][30][3],2).'</td>
				<td></td>
			</tr>
			<tr>
				<td class="tal">'.$lng['Over'].' 3,735,000</td>
				<td>'.number_format($data['net'][35][1],2).'</td>
				<td>'.$data['net'][35][2].' %</td>
				<td>'.number_format($data['net'][35][3],2).'</td>
				<td></td>
			</tr>
		</tbody>
		<tfoot>
			<tr style="background:#eee">
				<td>'.$lng['Total per year'].'</td>
				<td>'.number_format($data['net_year'],2).'</td>
				<td></td>
				<td>'.number_format($data['tax_year'],2).'</td>
				<td>'.number_format($data['percent_tax'],2).' %</td>
			</tr>
		</tfoot>
	</table>

	<table class="printTable" border="0" style="table-layout:fixed; margin-top:20px">
		<thead>
			<tr>
				<th colspan="8">
					GROSS calculation from NET :: '.$row['emp_id'].' - '.$row[$lang.'_name'].'
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>'.$lng['Month'].'</th>
				<th class="tac">'.$lng['Fixed NET'].'</th>
				<th class="tac">'.$lng['Variable NET'].'</th>
				<th class="tac">'.$lng['Total NET'].'</th>
				<th class="tac">'.$lng['Gross income'].'</th>
				<th class="tac">'.$lng['SSO'].'</th>
				<th class="tac">'.$lng['PVF'].'</th>
				<th class="tac">'.$lng['Tax'].'</th>
			</tr>';
			if($detail){ foreach($detail as $k=>$v){ if($k < 13){
			$html .= '
			<tr>
				<td class="tal">'.$months[$k].'</td>
				<td>'.number_format($v['fix'],2).'</td>
				<td>'.number_format($v['var'],2).'</td>
				<td>'.number_format($v['net'],2).'</td>
				<td>'.number_format($v['gross'],2).'</td>
				<td>'.number_format($v['sso'],2).'</td>
				<td>'.number_format($v['xpvf'],2).'</td>
				<td>'.number_format($v['tax'],2).'</td>
			</tr>';
			} } }
		$html .= '
		</tbody>	
		<tfoot>
			<tr>
				<td class="tac">'.$lng['Totals'].'</td>
				<td class="ycal">'.number_format($detail[13]['xfix'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xvar'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xtot'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xgross'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xsso'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xpvf'],2).'</td>
				<td class="ycal">'.number_format($detail[13]['xtax'],2).'</td>
			</tr>
		</tfoot>
	</table>';
	
	//echo $style.$html; exit;
	ob_clean();
	$footer = '<div class="footer">Form generated on '.date('d-m-Y @ H:i').'</div>';

	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-L', 9, '', 8, 8, 10, 10, 8, 8);

	$mpdf->SetTitle(strtoupper($cid).' - '.$_GET['id'].' : Calculate Gross from Net');
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->Output(strtoupper($cid).'_'.$_GET['id'].'_Calculate_Gross_from_Net'.'.pdf','I');
	
	exit;













