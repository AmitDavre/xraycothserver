
<?
	if(session_id()==''){session_start(); ob_start();}
	$lang = $_SESSION['rego']['lang'];
	//$cid = $_SESSION['rego']['cid'];
	include('../dbconnect/db_connect.php');
	include('emp_functions.php');
	include(DIR.'payroll/inc/tax_modulle.php');
	include(DIR.'payroll/inc/payroll_functions.php');
	include(DIR.'files/functions.php');
	include(DIR.'files/arrays_'.$_SESSION['rego']['lang'].'.php');
	//include('../files/get_metadata.php');

	$db_payroll = $_SESSION['rego']['payroll_dbase'];
	$db_employee = $cid.'_employees';
	//var_dump($pr_settings);
	$month = $_GET['m'];
	$paydate = '';//substr($_SESSION['payroll']['paydate'],0,-4).$_SESSION['rego']['year_th'];
	
	//$pr_settings = getPayrollSettings();
	$field = unserialize($pr_settings['payslip_field']);
	//$compinfo = getCompinfo($_SESSION['rego']['cid']);
	$bank_codes = unserialize($sys_settings['bank_codes']);
	//var_dump($bank_codes);
	
$style = '
	<style>
		.page {
			width: 21cm;
			height: 29.7cm;
    		margin: 30mm 45mm 30mm 45mm;
			margin: 0 auto;
			margin-top: 50px;
			background:#fff;
			padding:40px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
		body, div, p, table td, table th {
			font-family: "leelawadee", "garuda";
			font-family: "garuda";
			font-size: 13px;
		}
		table.wrap {
			border-collapse:collapse;
			border:0px solid red;
			width:100%;
			margin:6px 0 0px 0;
		}
		table.topslip {
			border-collapse:collapse;
			border:0px #000 solid;
			table-layout:auto;
		}
		table.topslip th {
			padding:3px 0px;
			white-space:nowrap;
			text-align:right;
		}
		table.topslip td {
			padding:3px 5px;
			white-space:nowrap;
			text-align:left;
		}
		table.payslip {
			border-collapse:collapse;
			border:0px #000 solid;
			table-layout:fixed;
			width:100%;
			
		}
		table.payslip th, table.payslip td {
			border:0.1em solid #000;
			padding:5px 10px;
			vertical-align:middle;
			font-size:13px;
			white-space:nowrap;
		}
		table.payslip th {
			background:#eee;
			text-align:center;
		}
		table.payslip td {
			font-weight:normal;
			text-align:right;
			vertical-align:baseline;
			padding:7px 10px;
		}
	</style>';
	
$html = '<html><body>';

	$data = getPayslipData($_SESSION['rego']['emp_id'], $month, $lang, "em");
	$empinfo = getEmpinfo($db_employee, $_SESSION['rego']['emp_id']);
	//var_dump($data);

	$html .= '
		
		<table border="0" style="width:100%;padding:0;">
			<tr>';
	if(!empty($compinfo['logofile'])){ $html .= '<td style="width:10px;padding:0 20px 0 0;"><img style="height:80px;max-width:350px" src="../'.$compinfo['logofile'].'?'.time().'" /></td>';}
	$html .= '<td style="padding:3px 0;height:80px;vertical-align:baseline;white-space:nowrap"><span style="font-size:22px"><b>'.$compinfo[$lang.'_compname'].'</b></span><br />'.nl2br($compinfo[$lang.'_address']).'</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left;font-size:18px;padding-top:15px"><b>ใบจ่ายเงินเดือน / PAYSLIP</b></td>
			</tr>
		</table>
		
		<div style="top:45mm; left:95mm; width:85mm; height:25mm; position:absolute; border-radius:10px; background:#eee; padding:20px 0 0 40px; font-size:14px">
			'.$title[$empinfo['title']].' '.$data['name_'.$lang].'<br>
			'.$empinfo['address1'].'<br>
			'.$empinfo['sub_district'].', '.$empinfo['district'].'<br>
			'.$empinfo['postnr'].' '.$empinfo['province'].'<br>
		</div>
		<div style="top:90mm; right:10mm; width:3mm; position:absolute; border-bottom:0.1em solid #666"></div>
		
		<table border="0" style="margin-top:50mm;width:100%;border-collapse:collapse;"><tr><td style="text-align:left">
		
			<table class="topslip" border="0">
				<tr>
					<th>Emp. ID / รหัส :</th><td style="">'.$data['emp_id'].'</td>
				</tr>
				<tr>
					<th>Position / ตำแหน่ง :</th><td>'.$data['position'].'</td>
				</tr>
				<tr>
					<th>Department / แผนก :</th><td>-</td>
				</tr>
			</table>
			
		</td><td style="text-align:right">
		
			<table class="topslip" border="0">
				<tr>
					<th>Period / งวดเดือน :</th><td>'.$months[(int)$month].' '.$_SESSION['rego']['year_'.$lang].'</td>
				</tr>
				<tr>
					<th>Account no. / เลขที่บัญชี :</th><td>'.$data['account'].'</td>
				</tr>
				<tr>
					<th>Bank / ธนาคาร :</th><td>'.$bank_codes[$data['bank']][$lang].'</td>
				</tr>
			</table>
		
		</td></tr></table>

		<table class="payslip" border="1" style=" margin-top:10px">
			<tr>
				<th style="width:25%">รายได้<br>Earnings</th>
				<th style="width:10%">จำนวน<br>Number</th>
				<th style="width:15%">จำนวนเงิน<br>Ammount</th>
				<th style="width:25%">รายการหัก<br>Deductions</th>
				<th style="width:10%">จำนวน<br>Number</th>
				<th style="width:15%">จำนวนเงิน<br>Ammount</th>
			</tr>
			<tr>
				<td valign="top" style="text-align:left;height:380px;line-height:150%">';
				foreach($data['earnings'] as $k=>$v){
					$html .= $v[0].'<br>';
				}
		$html .= '</td>
				<td valign="top" style="line-height:150%">';
				foreach($data['earnings'] as $k=>$v){
					$html .= $v[1].'<br>';
				}
		$html .= '</td>
				<td valign="top" style="line-height:150%">';
				foreach($data['earnings'] as $k=>$v){
					$html .= $v[2].'<br>';
				}
		$html .= '</td>
				<td valign="top" style="text-align:left;line-height:150%">';
				foreach($data['deduct'] as $k=>$v){
					$html .= $v[0].'<br>';
				}
		$html .= '</td>
				<td valign="top" style="line-height:150%">';
				foreach($data['deduct'] as $k=>$v){
					$html .= $v[1].'<br>';
				}
		$html .= '</td>
				<td valign="top" style="line-height:150%">';
				foreach($data['deduct'] as $k=>$v){
					$html .= $v[2].'<br>';
				}
		$html .= '</td>
			</tr>
			<tr>
				<th>รวมเงินได้<br>Total Earnings</th>
				<td colspan="2" style="vertical-align:bottom"><b>'.$data['gross_income'].'</b></td>
				<th>รวมรายการหัก<br>Total Deductions</th>
				<td colspan="2" style="vertical-align:bottom"><b>'.$data['tot_deductions'].'</b></td>
			</tr>
			<tr>
				<td colspan="4" style="font-size:14px;background:#ddd"><b>เงินรับสุทธิ / Net to Pay</b></td>
				<td colspan="2" style="vertical-align:bottom;font-size:14px;background:#ddd"><b>'.$data['net_income'].'</b></td>
			</tr>
		</table>

		<table class="payslip" border="1" style="margin-top:15px">
			<tr>
				<td>';
				if(isset($field['ytd1'])){ $html .= $data['asalary'];}
				$html .= '</td>
				<td>';
				if(isset($field['ytd2'])){ $html .= $data['atax'];}
				$html .= '</td>
				<td>';
				if(isset($field['ytd3'])){ $html .= $data['aprovfund'];}
				$html .= '</td>
				<td>';
				if(isset($field['ytd4'])){ $html .= $data['asocial'];}
				$html .= '</td>
				<td>';
				if(isset($field['ytd5'])){ $html .= $data['aother'];}
				$html .= '</td>
			</tr>
			<tr>
				<th>เงินได้สะสมต่อปี<br>YTD. Income</th>
				<th>ภาษีสะสมต่อปี<br>YTD. Tax</th>
				<th>เงินสะสมกองทุนต่อปี<br>YTD. Prov. Fund</th>
				<th><span>เงินสะสมกองทุนต่อปี</span><br>YTD. Social SF</th>
				<th>ค่าลดหย่อนอื่นๆ<br>Other Allowance</th>
			</tr>
		</table></div>';

$html .= '</body></html>';	
//echo $style.$html; exit;

include("../mpdf7/vendor/autoload.php");
//class mPDF ([ string $mode [, mixed $format [, float $default_font_size [, string $default_font [, float $margin_left , float $margin_right , float $margin_top , float $margin_bottom , float $margin_header , float $margin_footer [, string $orientation ]]]]]])
$mpdf=new mPDF('utf-8', 'A4-P', 11, 'leelawadee', 10, 10, 10, 10, 0, 0);
$mpdf->SetTitle($compinfo[$lang.'_compname'].' - '.$data['name_'.$lang].' - Payslip '.date('F', mktime(0, 0, 0, $month, 1)).' '.$_SESSION['rego']['year_'.$lang]);
ob_clean();
$mpdf->WriteHTML($style,1);
$mpdf->WriteHTML($html);
//$mpdf->Output();
$mpdf->Output($data['name_'.$lang].'_payslip_'.$month.'_'.$_SESSION['rego']['year_'.$lang].'.pdf','I');

exit;

?>





















