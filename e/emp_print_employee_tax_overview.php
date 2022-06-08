<?
	if(session_id()==''){session_start();}
	ob_start();
	$lang = $_SESSION['xhr']['lang'];
	//$cid = $_SESSION['xhr']['cid'];
	include('../dbconnect/db_connect.php');
	//include('../e/emp_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['xhr']['lang'].'.php');
	include(DIR.'payroll/inc/tax_modulle.php');
	
	$lng = getLangVariables($lang);
	
	//$id = $_SESSION['xhr']['id'];
	$id = $_GET['id'];
	$data = array();
	$xdata = getEmployeeTaxdata($id, $_SESSION['xray']['payroll_dbase'], $_SESSION['xhr']['emp_dbase']);
	$data = $xdata['data'];
	$calc_method = $xdata['calc_method'];
	$emp_name = $xdata['emp_name'];
	
	//var_dump(ROOT); exit;

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
		table {
			border-collapse:collapse;
			border:0px solid #000;
			width:100%;
			margin:0;
		}
		table thead th {
			vertical-align:middle;
			text-align:center;
			padding:5px 8px;
			white-space:normal;
		}
		table tfoot td {
			vertical-align:middle;
			text-align:right;
			padding:5px 8px;
			white-space:nowrap;
			border:0.0001em solid #666;
			background:#eee;
			font-weight:bold;
		}
		table thead th, table tbody td {
			border:0.0001em solid #666;
		}
		table td {
			font-weight:normal;
			text-align:right;
			padding:5px 8px;
			white-space:nowrap;
		}
		table tr.igrey td {
			color:#999;
		}
	</style>';
	
	$html = '<html><body>';

$html .= '
			<table class="header" border="0">
				<tr>';
				if(!empty($compinfo['logofile'])){ $html .= '<td style="width:10px;padding:0 10px 0 0;"><img style="height:80px;max-width:350px" src="'.ROOT.$compinfo['logofile'].'?'.time().'" /></td>';}
				$html .= '<td style="padding:3px 0;height:80px;vertical-align:baseline;white-space:nowrap"><span style="font-size:22px"><b>'.$compinfo[$lang.'_compname'].'</b></span><br />'.nl2br($compinfo[$lang.'_address']).'</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:left;font-size:18px;padding:5px 5px 15px 0">'.$lng['Annual payroll overview employee'].' : '.$emp_name.'</td>
				</tr>
			</table>

			<table border="0" cellspacing="0">
			<thead>
			<tr style="background:#ddd">
				<th>'.strtoupper($calc_method).'</th>
				<th colspan="6">'.$lng['Income'].'</th>
				<th colspan="5">'.$lng['Deductions'].'</th>
				<th colspan="1">&nbsp;</th>
			</tr>
			<tr style="background:#eee">
				<th style="width:5px;white-space:nowrap">'.$lng['Month'].'</th>
				<th>'.$lng['Salary'].'</th>
				<th>'.$lng['Total OT'].'</th>
				<th>'.$lng['Allowances'].'</th>
				<th>'.$lng['Year bonus'].'</th>
				<th>'.$lng['Other income'].'</th>
				<th>'.$lng['Gross'].'</th>
				<th>'.$lng['PVF'].'</th>
				<th>'.$lng['SSO'].'</th>
				<th>'.$lng['Tax'].'</th>
				<th>'.$lng['Other deduct'].'</th>
				<th>'.$lng['Total deduct'].'</th>
				<th>'.$lng['Net Income'].'</th>
			</tr>
			</thead>
			<tbody>';
			foreach($data as $k=>$v){ // Paid months
				if($k < 13){
					$html .= '
					<tr class="'.$v['class'].'">
						<td style="text-align:right">'.$short_months[$k].' '.substr($_SESSION['xhr']['cur_year'],2).'</td>
						<td>'.number_format($v['salary'],2).'</td>
						<td>'.number_format($v['ot'],2).'</td>
						<td>'.number_format(($v['allow']),2).'</td>
						<td>'.number_format($v['bonus'],2).'</td>
						<td>'.number_format(($v['other_income']),2).'</td>
						<td>'.number_format($v['gross'],2).'</td>
						<td>'.number_format($v['pvf'],2).'</td>
						<td>'.number_format($v['sso'],2).'</td>
						<td>'.number_format($v['tax'],2).'</td>
						<td>'.number_format($v['deduct'],2).'</td>
						<td>'.number_format($v['deductions'],2).'</td>
						<td><b>'.number_format($v['net'],2).'</b></td>
					</tr>';
				}else{
					$html .= '</tbody><tfoot class="tar">';
					$html .= '
					<tr>
						<td colspan="1" class="tac">'.$v['date'].'</td>
						<td>'.number_format($v['salary'],2).'</td>
						<td>'.number_format($v['ot'],2).'</td>
						<td>'.number_format($v['allow'],2).'</td>
						<td>'.number_format($v['bonus'],2).'</td>
						<td>'.number_format($v['other_income'],2).'</td>
						<td>'.number_format($v['gross'],2).'</td>
						<td>'.number_format($v['pvf'],2).'</td>
						<td>'.number_format($v['sso'],2).'</td>
						<td>'.number_format($v['tax'],2).'</td>
						<td>'.number_format($v['deduct'],2).'</td>
						<td>'.number_format($v['deductions'],2).'</td>
						<td>'.number_format($v['net'],2).'</td>
					<tr></tfoot></table>';
				}
			} 


$html .= '</body></html>';	
//echo $style.$html; exit;

include("../mpdf7/vendor/autoload.php");
//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
$mpdf=new mPDF('utf-8', 'A4-L', 11, 'leelawadee', 10, 10, 10, 10, 0, 0);
$mpdf->SetTitle($emp_name.' : '.$lng['Annual overview'].' '.$_SESSION['xhr']['year_'.$lang]);

$mpdf->WriteHTML($style,1);
$mpdf->WriteHTML($html);
//$mpdf->Output();
$mpdf->Output($id.'_annual_payroll_overview_employee.pdf','I');
exit;

?>





















