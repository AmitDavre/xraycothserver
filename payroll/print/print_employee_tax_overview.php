<?php

	if(session_id()==''){session_start();}; ob_start();
	include('../../dbconnect/db_connect.php');
	include(DIR.'files/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');

	include(DIR.'payroll/ajax/annual_tax_overview.php');
	
	if($res = $dbc->query("SELECT en_compname, tax_id, revenu_branch, logofile FROM ".$cid."_entities_data WHERE ref = '".$empinfo['entity']."'")){
		$xdata = $res->fetch_assoc();
	}

	//var_dump($xdata); exit;
	
$style = '
	<style>
		@page {
			  margin:0px;
			 }
		body, div, p {
			font-family: "leelawadee", "garuda";
			font-size: 12px;
			line-height:130%;
		}
		table.header {
			border-collapse:collapse;
			border:0px solid #000;
			width:100%;
			margin:0;
		}
		table.header td {
			border:0px solid #000;
			text-align:left;
		}
		table.overview {
			border-collapse:collapse;
			border:0px solid #000;
			width:100%;
			margin:0;
		}
		table.overview thead th {
			vertical-align:middle;
			text-align:center;
			padding:5px 8px;
			white-space:normal;
		}
		table.overview thead th, table.overview tbody td {
			border:0.0001em solid #666;
		}
		table.overview tbody td {
			font-weight:normal;
			text-align:right;
			padding:5px 8px;
			white-space:nowrap !important;
			background:#fff;
		}
		table.overview tbody tr.igrey td {
			color:#aaa;
		}
		table.overview tfoot tr {
			background:#eee;
		}
		table.overview tfoot td {
			font-weight:bold;
			text-align:right;
			padding:5px 8px;
			white-space:nowrap !important;
			border:0.0001em solid #666;
		}
		table.overview tbody td.tac {
			text-align:center;
		}
	</style>';
	//var_dump($_REQUEST['id']);
	
	$header = '
		<table border="0" style="width:100%">
			<tr>';
				if(!empty($xdata['logofile'])){ 
					$header .= '<td style="width:10px;padding:0 20px 0 0; vertical-align:top"><img style="height:40px;max-width:350px" src="../../'.$xdata['logofile'].'?'.time().'" /></td>';}
					$header .= '<td style="padding:0;white-space:nowrap;vertical-align:middle"><span style="font-size:22px"><b>'.$xdata[$lang.'_compname'].'</td>
			</tr>
			<tr><td colspan="2" style="text-align:left;font-size:16px;padding:8px 0 0">'.$lng['Annual payroll overview employee'].' : '.$empinfo['emp_id'].' '.$empinfo[$lang.'_name'].'</td></tr>
		</table>';
				
	$footer = '<div class="footer">'.$lng['Form generated on'].' : '.date('d-m-Y').'</div>';
	
	$html = '<html><body>';
	//var_dump($data);

	$colspan = 5;
	if(!$empinfo['calc_pvf'] && !$empinfo['calc_psf']){$colspan = 4;}

$html .= '
			<table class="overview" border="0" cellspacing="0">
			<thead>
			<tr style="background:#ddd">
				<th colspan="2">'.$lng['Year'].' : '.$_SESSION['rego']['year_'.$lang].'</th>
				<th colspan="4">'.$lng['Income'].'</th>
				<th>'.$lng['Deduct'].'</th>
				<th class="tac">'.$lng['Gross'].'</th>
				<th colspan="'.$colspan.'">'.$lng['Deductions'].'</th>
				<th>'.$lng['Net'].'</th>
			</tr>
			<tr style="background:#eee">
				<th style="">'.$lng['Month'].'</th>
				<th style="width:1px">'.$lng['Paid'].'</th>
				<th style="width:8%">'.$lng['Salary'].'</th>
				<th style="width:8%">'.$lng['Fixed allowances'].'</th>
				<th style="width:8%">'.$lng['Total OT'].'</th>
				<th style="width:8%">'.$lng['Variable income'].'</th>
				<th style="width:8%">'.$lng['Before tax'].'</th>
				<th style="width:8%">'.$lng['Income'].'</th>
				<th style="width:8%">'.$lng['After tax'].'</th>';
			if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$html .= '<th style="width:8%">PSF / '.$lng['PVF'].'</th>';}	
			$html .= '
				<th style="width:8%">'.$lng['SSO'].'</th>
				<th style="width:8%">'.$lng['Tax'].'</th>
				<th style="width:8%">'.$lng['Total deductions'].'</th>
				<th style="width:8%">'.$lng['Income'].'</th>
			</tr>
			</thead>
			<tbody>';
			foreach($data as $key=>$val){ 
				if($key < 13){
					$html .= '<tr class="'.$val['class'].'">';
					$html .= '<td>'.$val['date'].'</td>
					<td class="tac">'.$val['paid'].'</td>
					<td>'; if(is_numeric($val['salary'])){$html .= number_format((float)$val['salary'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['allow'])){$html .= number_format((float)$val['allow'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['ot'])){$html .= number_format((float)$val['ot'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['other_income'])){$html .= number_format((float)$val['other_income'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['deduct_before'])){$html .= number_format((float)$val['deduct_after'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['gross'])){$html .= number_format((float)$val['gross'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['deduct_after'])){$html .= number_format((float)$val['deduct_after'],2);}else{$html .= '-';} $html .= '</td>';
					if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$table .= '<td>'; if(is_numeric($val['pvf'])){$html .= number_format((float)$val['pvf'],2);}else{$html .= '-';} $html .= '</td>';}	
					$html .= '
					<td>'; if(is_numeric($val['sso'])){$html .= number_format((float)$val['sso'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['tax_month'])){$html .= number_format((float)$val['tax_month'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['deductions'])){$html .= number_format((float)$val['deductions'],2);}else{$html .= '-';} $html .= '</td>
					<td>'; if(is_numeric($val['net'])){$html .= number_format((float)$val['net'],2);}else{$html .= '-';} $html .= '</td>';
					$html .= '</tr>';
				}else{
					$html .= '</tbody><tfoot class="tar">';
					$html .= '
					<tr>
						<td colspan="2" class="tac">'.$val['date'].'</td>
						<td>'.number_format($val['salary'],2).'</td>
						<td>'.number_format($val['allow'],2).'</td>
						<td>'.number_format($val['ot'],2).'</td>
						<td>'.number_format($val['other_income'],2).'</td>
						<td>'.number_format($val['deduct_before'],2).'</td>
						<td>'.number_format($val['gross'],2).'</td>
						<td>'.number_format($val['deduct_after'],2).'</td>';
					if($empinfo['calc_pvf'] || $empinfo['calc_psf']){$html .= '<td>'.number_format($val['pvf'],2).'</td>';}	
			$html .= '
						<td>'.number_format($val['sso'],2).'</td>
						<td>'.number_format($val['tax_month'],2).'</td>
						<td>'.number_format($val['deductions'],2).'</td>
						<td>'.number_format($val['net'],2).'</td>
					<tr></tfoot></table>';
				}
					
			}
			$html .= '</tbody></table>';
			if($tax_return > 0){
				$html .= '<div style="text-align:right; padding:10px 0; font-size:14px"><b>'.$lng['Tax return'].' : '.number_format($tax_return,2).' '.$lng['Baht'].'</b></div>';
			}

	$html .= '</body></html>';	
	//var_dump($tax_return);
	//echo $style.$html; exit;	
	
	include(DIR."mpdf7/vendor/autoload.php");
	//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
	$mpdf = new mPDF('utf-8', 'A4-L', 11, 'leelawadee', 8, 8, 28, 10, 8, 5);
	$filename = $_REQUEST['id'].' - '.$empinfo[$lang.'_name'].' : '.$lng['Annual overview'].' '.$_SESSION['rego']['year_'.$lang];
	$mpdf->SetTitle($filename);
	
	$mpdf->WriteHTML($style,1);
	$mpdf->SetHTMLHeader($header);
	$mpdf->SetHTMLFooter($footer);
	$mpdf->WriteHTML($html);
	//$mpdf->Output();
	$mpdf->Output($filename.'.pdf','I');

	
