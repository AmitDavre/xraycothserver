<?php

	if(session_id()==''){session_start();}
	ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$lang.'.php');

	$xdata = array();
	if(isset($_GET['id'])){
		$sql = "SELECT * FROM ".$cid."_tax_simulation WHERE emp_id = '".$_GET['id']."'";
		if($res = $dbc->query($sql)){
			$row = $res->fetch_assoc();
			$xdata = unserialize($row['calculate_current']);
			//$detail = unserialize($row['current_detail']);
			$emp_id = $row['emp_id'];
			$emp_name = $row[$lang.'_name'];
		}
	}
	//var_dump($row); exit;
	//var_dump($xdata); exit;
	//var_dump($detail);
	
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions_tax.php');
	//$empinfo = getEmployeeInfo($cid, $_GET['id']);
	//$xdata = getEmployeeReport($_REQUEST['id']); 
	$tmp = getEmployeeTaxdata($_GET['id']);
	$cdata = $tmp['data'];
	$calc_method = $tmp['calc_method'];
	//$emp_name = $cdata['emp_name'];
	//var_dump($tmp); exit;
	
	include(DIR.'payroll/ajax/annual_tax_overview.php');
	
	//var_dump($data); exit;
	
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
			background:green; 
			border:1px solid green; 
			color:#fff
		}
		table.printTable tbody th {
			border:1px solid #ccc;
			border-top:0;
			background:#eee;
			padding:3px 10px;
			white-space:nowrap;
			font-weight:bold;
		}
		table.printTable tfoot td {
			border:1px solid #ccc;
			border-top:0;
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
			xwidth:100px;
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
		table.printTable td.igrey {
			color:#999;
		}
		table.printTable th.tac, table.printTable td.tac {
			text-align:center;
		}
		table.printTable tbody tr.igrey td{
			color:#999;
		}
	</style>';
	
$html = '
	<table class="printTable" style="table-layout:fixed">
		<thead>
			<tr>
				<th colspan="5">
					'.$lng['Current Package'].'  :: '.$emp_id.' - '.$emp_name.'
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="title tal">'.$lng['Gross income full year'].'</td>
				<td>'.number_format($xdata['income'],2).'</td>
				<td colspan="3" style="border-top:0">&nbsp;</td>
			</tr>
			<tr>
				<td class="title tal">'.$lng['Personal Tax deductions'].'</td>
				<td class="nopad">'.number_format(($xdata['income'] - $xdata['taxable']),2).'</td>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="title tal fatborder">'.$lng['Taxable income'].'</td>
				<td class="fatborder">'.number_format($xdata['taxable'],2).'</td>
				<td class="fatborder" colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 0 to 150,000'].'</td>
				<td>'.number_format($xdata['current'][0][1],2).'</td>
				<td>'.$xdata['current'][0][2].' %</td>
				<td>'.number_format($xdata['current'][0][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 150,001 to 300,000'].'</td>
				<td>'.number_format($xdata['current'][5][1],2).'</td>
				<td>'.$xdata['current'][5][2].' %</td>
				<td>'.number_format($xdata['current'][5][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 300,001 to 500,000'].'</td>
				<td>'.number_format($xdata['current'][10][1],2).'</td>
				<td>'.$xdata['current'][10][2].' %</td>
				<td>'.number_format($xdata['current'][10][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 500,001 to 750,000'].'</td>
				<td>'.number_format($xdata['current'][15][1],2).'</td>
				<td>'.$xdata['current'][15][2].' %</td>
				<td>'.number_format($xdata['current'][15][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 750,001 to 1,000,000'].'</td>
				<td>'.number_format($xdata['current'][20][1],2).'</td>
				<td>'.$xdata['current'][20][2].' %</td>
				<td>'.number_format($xdata['current'][20][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 1000001 to 2000000'].'</td>
				<td>'.number_format($xdata['current'][25][1],2).'</td>
				<td>'.$xdata['current'][25][2].' %</td>
				<td>'.number_format($xdata['current'][25][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['From 2,000,001 to 5,000,000'].'</td>
				<td>'.number_format($xdata['current'][30][1],2).'</td>
				<td>'.$xdata['current'][30][2].' %</td>
				<td>'.number_format($xdata['current'][30][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="tal">'.$lng['Over 5,000,000'].'</td>
				<td>'.number_format($xdata['current'][35][1],2).'</td>
				<td>'.$xdata['current'][35][2].' %</td>
				<td>'.number_format($xdata['current'][35][3],2).'</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
		<tfoot>
			<tr style="background:#eee">
				<td>'.$lng['Total per year'].'</td>
				<td>'.number_format($xdata['tot_gross'],2).'</td>
				<td>&nbsp;</td>
				<td>'.number_format($xdata['tax_year'],2).'</td>
				<td>'.number_format($xdata['percent_tax'],2).' %</td>
			</tr>
		</tfoot>
	</table>';
	
/*	<table class="printTable" border="0" style="table-layout:auto; margin-top:20px">
		<thead>
			<tr>
				<th colspan="14">
					Year overview :: '.$row['emp_id'].' - '.$row[$lang.'_name'].'
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th colspan="2">'.strtoupper($calc_method).'</th>
				<th colspan="6">'.$lng['Income'].'</th>
				<th colspan="5">'.$lng['Deductions'].'</th>
				<th></th>
			<tr>
				<th>'.$lng['Month'].'</th>
				<th class="tac">'.$lng['Paid'].'</th>
				<th class="tac">'.$lng['Salary'].'</th>
				<th class="tac">'.$lng['Fix allow'].'</th>
				<th class="tac">'.$lng['Total OT'].'</th>
				<th class="tac">'.$lng['Bonus'].'</th>
				<th class="tac">'.$lng['Var income'].'</th>
				<th class="tac">'.$lng['Gross'].'</th>
				<th class="tac">'.$lng['PVF'].'</th>
				<th class="tac">'.$lng['Social'].'</th>
				<th class="tac">'.$lng['Tax'].'</th>
				<th class="tac">'.$lng['Other'].'</th>
				<th class="tac">'.$lng['Total'].'</th>
				<th class="tac">'.$lng['Net'].'</th>
			</tr>';
			if($cdata){ foreach($data as $k=>$v){ if($k < 13){
				if($v['salary'] != '-'){$salary = number_format($v['salary'],2);}else{$salary = '-';}
				if($v['allow'] != '-'){$allow = number_format($v['allow'],2);}else{$allow = '-';}
				if($v['ot'] != '-'){$ot = number_format($v['ot'],2);}else{$ot = '-';}
				if($v['bonus'] != '-'){$bonus = number_format($v['bonus'],2);}else{$bonus = '-';}
				if($v['other_income'] != '-'){$other_income = number_format($v['other_income'],2);}else{$other_income = '-';}
				if($v['gross'] != '-'){$gross = number_format($v['gross'],2);}else{$gross = '-';}
				if($v['pvf'] != '-'){$pvf = number_format($v['pvf'],2);}else{$pvf = '-';}
				if($v['sso'] != '-'){$sso = number_format($v['sso'],2);}else{$sso = '-';}
				if($v['tax'] != '-'){$tax = number_format($v['tax'],2);}else{$tax = '-';}
				if($v['deduct'] != '-'){$deduct = number_format($v['deduct'],2);}else{$deduct = '-';}
				if($v['deductions'] != '-'){$deductions = number_format($v['deductions'],2);}else{$deductions = '-';}
				if($v['net'] != '-'){$net = number_format($v['net'],2);}else{$net = '-';}
			$html .= '
			<tr>
				<td class="tal '.$v['class'].'">'.$v['date'].'</td>
				<td style="width:10px" class="tac '.$v['class'].'">'.$v['paid'].'</td>
				<td class="'.$v['class'].'">'.$salary.'</td>
				<td class="'.$v['class'].'">'.$allow.'</td>
				<td class="'.$v['class'].'">'.$ot.'</td>
				<td class="'.$v['class'].'">'.$bonus.'</td>
				<td class="'.$v['class'].'">'.$other_income.'</td>
				<td class="'.$v['class'].'">'.$gross.'</td>
				<td class="'.$v['class'].'">'.$pvf.'</td>
				<td class="'.$v['class'].'">'.$sso.'</td>
				<td class="'.$v['class'].'">'.$tax.'</td>
				<td class="'.$v['class'].'">'.$deduct.'</td>
				<td class="'.$v['class'].'">'.$deductions.'</td>
				<td class="'.$v['class'].'">'.$net.'</td>
				
			</tr>';
			} } }
		$html .= '
		</tbody>	
		<tfoot>
			<tr>
				<td class="tac" colspan="2">'.$lng['Totals'].'</td>
				<td class="ycal">'.number_format($data[13]['salary'],2).'</td>
				<td class="ycal">'.number_format($data[13]['allow'],2).'</td>
				<td class="ycal">'.number_format($data[13]['ot'],2).'</td>
				<td class="ycal">'.number_format($data[13]['bonus'],2).'</td>
				<td class="ycal">'.number_format($data[13]['other_income'],2).'</td>
				<td class="ycal">'.number_format($data[13]['gross'],2).'</td>
				<td class="ycal">'.number_format($data[13]['pvf'],2).'</td>
				<td class="ycal">'.number_format($data[13]['sso'],2).'</td>
				<td class="ycal">'.number_format($data[13]['tax'],2).'</td>
				<td class="ycal">'.number_format($data[13]['deduct'],2).'</td>
				<td class="ycal">'.number_format($data[13]['deductions'],2).'</td>
				<td class="ycal">'.number_format($data[13]['net'],2).'</td>
			</tr>
		</tfoot>
	</table>';
*/	
	
	$colspan = 4;
	if(!$empinfo['calc_pvf'] && !$empinfo['calc_psf']){$colspan = 3;}

	$html .= '
		<table class="printTable" border="0" style="table-layout:auto; margin-top:20px">
		<thead>
			<tr>
				<th colspan="14">
					Year overview :: '.$emp_id.' - '.$emp_name.'
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th class="tac" style="line-height:160%" colspan="2">'.strtoupper($calc_method).'</th>
				<th class="tac" style="line-height:160%" colspan="4">'.$lng['Income'].'</th>
				<th class="tac" style="line-height:160%">'.$lng['Deduct'].'</th>
				<th class="tac" style="line-height:160%">'.$lng['Gross'].'</th>
				<th class="tac" style="line-height:160%" colspan="'.$colspan.'">'.$lng['Deductions'].'</th>
				<th class="tac" style="line-height:160%">Net</th>
			</tr>
			<tr>
				<th class="tac">'.$lng['Month'].'</th>
				<th class="tac">'.$lng['Paid'].'</th>
				<th class="tac">'.$lng['Salary'].'</th>
				<th class="tac">'.$lng['Fixed allowances'].'</th>
				<th class="tac">'.$lng['Total OT'].'</th>
				<th class="tac">'.$lng['Variable income'].'</th>
				<th class="tac">'.$lng['Before tax'].'</th>
				<th class="tac">'.$lng['Income'].'</th>
				<th class="tac">'.$lng['After tax'].'</th>';
			if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$html .= '<th class="tac" style="width:8%">PSF/'.$lng['PVF'].'</th>';}	
			$html .= '
				<th class="tac">'.$lng['Social'].'</th>
				<th class="tac">'.$lng['Tax'].'</th>
				<th class="tac">'.$lng['Income'].'</th>
			</tr>';
			if($data){ 
				foreach($data as $k=>$v){ 
					if($k < 13){
					$html .= '
					<tr class="tar '.$v['class'].'">
						<td style="white-space:nowrap">'.$v['date'].'</td>
						<td class="tac">'.$v['paid'].'</td>
						<td>'; if($v['salary'] > 0){$html .= number_format($v['salary'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['allow'] > 0){$html .= number_format($v['allow'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['ot'] > 0){$html .= number_format($v['ot'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['other_income'] > 0){$html .= number_format($v['other_income'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['deduct_before'] > 0){$html .= number_format($v['deduct_before'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['gross'] > 0){$html .= number_format($v['gross'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['deduct_after'] > 0){$html .= number_format($v['deduct_after'],2);}else{$html .= '-';} $html .= '</td>';
					if($empinfo['calc_pvf'] || $empinfo['calc_psf']){
						$html .= '<td>'; 
						if($v['pvf'] > 0){
							$html .= number_format($v['pvf'],2);
						}else{
							$html .= '-';
						} 
					$html .= '</td>';}	
					$html .= '
						<td>'; if($v['sso'] > 0){$html .= number_format($v['sso'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['tax_month'] > 0){$html .= number_format($v['tax_month'],2);}else{$html .= '-';} $html .= '</td>
						<td>'; if($v['net'] > 0){$html .= number_format($v['net'],2);}else{$html .= '-';} $html .= '</td>
					</tr>';
					}else{
						$html .= '</tbody><tfoot class="tar">';
						$html .= '
						<tr>
							<td colspan="2" class="tar">'.$v['date'].'</td>
							<td>'.number_format($v['salary'],2).'</td>
							<td>'.number_format($v['allow'],2).'</td>
							<td>'.number_format($v['ot'],2).'</td>
							<td>'.number_format($v['other_income'],2).'</td>
							<td>'.number_format($v['deduct_before'],2).'</td>
							<td>'.number_format($v['gross'],2).'</td>
							<td>'.number_format($v['deduct_after'],2).'</td>';
						if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$html .= '<td>'.number_format($v['pvf'],2).'</td>';}	
						$html .= '
							<td>'.number_format($v['sso'],2).'</td>
							<td>'.number_format($v['tax_month'],2).'</td>
							<td>'.number_format($v['net'],2).'</td>
						<tr></tfoot></table></html>';
					}
				}
			}else{
				$html .= '
				<table><tbody><tr>
					<td colspan="15" class="tac">No data available</td>
				<tr></tbody></table>';
			}

	//echo $style.$html; exit;
	ob_clean();
	$footer = '<div class="footer">Form generated on '.date('d-m-Y @ H:i').'</div>';

	require_once("../../mpdf7/vendor/autoload.php");
	$mpdf=new mPDF('utf-8', 'A4-L', 9, '', 8, 8, 10, 10, 8, 8);

	$mpdf->SetTitle(strtoupper($cid).' - '.$_GET['id'].' : Current Package');
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetFontSize(12);
	$mpdf->WriteHTML($style,1);
	$mpdf->WriteHTML($html);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->Output(strtoupper($cid).'_'.$_GET['id'].'_Current_Package'.'.pdf','I');
	
	exit;













